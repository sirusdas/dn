<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* TODO:
	[ ] For images / media, save with error loses sort.  Fix.
*/

final class NCMAdmin extends NCMCore {

	private static $instance;

	/**
	 * Local instance of the item class
	 * @var NCMItem class
	 */
	private static $item;

	/**
	 * Local instance of the item class
	 * @var NCMCategory class
	 */
	private static $category;

	/**
	 * Constructor magic method.
	 * Private because this class should not be called on its own.
	 */
	public function __construct() {
		self::stripslashes();
		self::$self_url = 'admin.php?page=purchase';
		self::$item     = new NCMItem();
		self::$category = new NCMCategory();
		self::$label    = new NCMLabel();
		self::prep_sort();
	}

	/**
	 * This is here purely to prevent someone from cloning the class
	 */
	private function __clone() {
	}

	public static function getInstance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function stripslashes() {
		$_POST    = array_map( 'stripslashes_deep', $_POST );
		$_GET     = array_map( 'stripslashes_deep', $_GET );
		$_COOKIE  = array_map( 'stripslashes_deep', $_COOKIE );
		$_REQUEST = array_map( 'stripslashes_deep', $_REQUEST );
	}

	private static function get_items( $args = NULL ) {
		return self::$item->get_all( $args );
	}

	/**
	 * General Instructions Page
	 */
	public static function instructions() {
		self::admin_heading( self::__( 'Instructions' ) );
		echo '<h3>' . self::__( 'Quick-Start Guide' ) . '</h3>';
		echo '<ol>';
		echo '<li>' . self::__( 'To add a Customer Click -> ' ) . '<a href="admin.php?page=manage_customer_details&action=add">' . self::__( 'Add Customer' ) . '</a></li>' . PHP_EOL;
                echo '<li>' . self::__( 'Edit Customer Records Click -> ' ) . '<a href="admin.php?page=manage_customer_details">' . self::__( 'Edit Customer Records' ) . '</a></li>' . PHP_EOL;
                echo '<li>' . self::__( 'Check Customer Reports Click -> ' ) . '<a href="admin.php?page=manage_customer_reports">' . self::__( 'Reports' ) . '</a></li>' . PHP_EOL;
                echo '<li>' . self::__( 'For Analysis Click -> ' ) . '<a href="http://webxarc.in/netra/nbm/?tr=1">' . self::__( 'Data Analysis' ) . '</a></li>' . PHP_EOL;
		echo '</ol>';
		self::admin_footer();
	}

	public static function manage_customer_details() {

		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		$action       = self::get_action();
		$inventory_id = self::request( "inventory_id" );

		// Do our work here
		if ( $action == 'save' ) {
			if ( self::save_item() ) {
				$action        = '';
				self::$message = self::__( 'Inventory Item' ) . ' ' . self::__( 'saved successfully.' );
			} else {
				$action = 'edit';
			}
		} else if ( $action == 'delete' ) {
			$inventory_id = self::request( 'delete_id' );
			$success      = self::delete_item( $inventory_id );
			$action       = '';
		}

		// Do our display here
		self::admin_heading( self::__( 'Manage Customer Details' ) );

		if ( $action == 'edit' || $action == 'add' ) {
			self::edit_item( $inventory_id );
		}

		if ( ! $action ) {
			self::list_items();
		}

		self::admin_footer();
	}
        
        public static function manage_customer_reports() {

		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		$action       = self::get_action();
		$inventory_id = self::request( "inventory_id" );

		// Do our work here
		if ( $action == 'save' ) {
			if ( self::save_item() ) {
				$action        = '';
				self::$message = self::__( 'Inventory Item' ) . ' ' . self::__( 'saved successfully.' );
			} else {
				$action = 'edit';
			}
		} else if ( $action == 'delete' ) {
			$inventory_id = self::request( 'delete_id' );
			$success      = self::delete_item( $inventory_id );
			$action       = '';
		}

		// Do our display here
		self::admin_heading( self::__( 'Reports' ) );

		if ( $action == 'edit' || $action == 'add' ) {
			//self::edit_item( $inventory_id );
		}

		if ( ! $action ) {
			self::list_reports_items();
		}

		self::admin_footer();            
            
        }

        	/**
	 * View for displaying the inventory items in the admin dashboard.
	 */
	public static function list_items() {
		$inventory_display = netracustomer_get_display_settings( 'admin' );

		$columns        = array();
		$name_columns   = array( 'inventory_name', 'inventory_description' );
		$ignore_columns = array( 'inventory_image', 'inventory_images', 'inventory_media' );

		foreach ( $inventory_display AS $item ) {
			$class = ( in_array( $item, $name_columns ) ) ? 'name' : 'medium';
			if ( ! in_array( $item, $ignore_columns ) ) {
				$columns[ $item ] = array(
					'title' => self::get_label( $item ),
					'class' => $class
				);
			}
		}

		echo netracustomer_filter_form_admin();

		$args = '';

		$filters = array(
			"inventory_search"      => "search",
			"inventory_sort_by"     => "order",
			"inventory_category_id" => "category_id",
			"inventory_page"        => "page"
		);

		foreach ( $filters AS $filter => $field ) {
			if ( self::request( $filter ) ) {
				$args[ $field ] = self::request( $filter );
			}
		}

		$args = self::permission_args( $args );

		$loop = new NCMLoop( $args );

		global $netracustomer_item;

		?>
		<?php if ( self::check_permission( 'add_item', FALSE ) ) { ?>
			<a class="button button-primary"
			   href="<?php echo self::$self_url; ?>&action=add"><?php self::_e( 'Add Inventory Item' ); ?></a>
		<?php } ?>
		<table class="grid itemgrid">
			<?php echo self::grid_columns( $columns, self::$self_url, 'inventory_number' );
			while ( $loop->have_items() ) {
				$loop->the_item();
				$edit_url   = ( self::check_permission( 'view_item', $netracustomer_item->inventory_id ) ) ? self::$self_url . '&action=edit&inventory_id=' . $netracustomer_item->inventory_id . '&iv_no=' . $netracustomer_item->invoice_no : '';
				$delete_url = ( self::check_permission( 'edit_item', $netracustomer_item->inventory_id ) ) ? self::$self_url . '&action=delete&delete_id=' . $netracustomer_item->inventory_id : '';

				if ( ! $edit_url ) {
					continue;
				}
				?>
				<tr>
					<?php
					foreach ( $columns as $field => $data ) {
						$field = ( $field == 'category_id' ) ? 'inventory_category' : $field;

						$url = $edit_url;
						if ($field == 'user_id' || $field == 'inventory_user_id') {
							$url = get_edit_user_link( $netracustomer_item->{$field} );
						}
						echo '<td class="' . $field . '"><a href="' . $url . '">' .  $loop->get_field( $field ) . '</a></td>';
					}
					?>
					<td class="action">
						<?php if ( $edit_url ) { ?>
							<a href="<?php echo $edit_url; ?>"><?php self::_e( 'edit' ); ?></a>
						<?php }
						if ( $delete_url ) { ?>
							<a class="delete" data-name="<?php echo $netracustomer_item->inventory_name; ?>"
							   href="<?php echo $delete_url; ?>"><?php self::_e( 'delete' ); ?></a>
						<?php } ?>
						<?php do_action( 'ncm_admin_action_links', $netracustomer_item->inventory_id ); ?>
					</td>
				</tr>
			<?php } ?>
		</table>

		<?php
		echo netracustomer_pagination( self::$self_url, $loop->get_pages() );
		do_action( 'ncm_admin_items_listing', $loop->get_query_args() );
	}
        
	public static function list_reports_items() {
		$inventory_display = netracustomer_get_display_settings( 'admin' );

		$columns        = array();
		$name_columns   = array( 'inventory_name', 'inventory_description' );
		$ignore_columns = array( 'inventory_image', 'inventory_images', 'inventory_media' );

		foreach ( $inventory_display AS $item ) {
			$class = ( in_array( $item, $name_columns ) ) ? 'name' : 'medium';
			if ( ! in_array( $item, $ignore_columns ) ) {
				$columns[ $item ] = array(
					'title' => self::get_label( $item ),
					'class' => $class
				);
			}
		}

		echo netracustomer_records_filter_form_admin();

		$args = '';

		$filters = array(
			"inventory_search"      => "search",
			"inventory_sort_by"     => "order",
			"inventory_category_id" => "category_id",
			"inventory_page"        => "page"
		);

		foreach ( $filters AS $filter => $field ) {
			if ( self::request( $filter ) ) {
				$args[ $field ] = self::request( $filter );
			}
		}

		$args = self::permission_args( $args );

		$loop = new NCMLoop( $args );

		global $netracustomer_item;

		?>
		<?php //if ( self::check_permission( 'add_item', FALSE ) ) { ?>
			<!-- <a class="button button-primary"
			   href="<?php //echo self::$self_url; ?>&action=add"><?php //self::_e( 'Add Inventory Item' ); ?></a> -->
		<?php //} ?>
		<table class="grid itemgrid">
			<?php echo self::grid_columns( $columns, self::$self_url, 'inventory_number' );
			while ( $loop->have_items() ) {
				$loop->the_item();
				$edit_url   = ( self::check_permission( 'view_item', $netracustomer_item->inventory_id ) ) ? self::$self_url . '&action=edit&inventory_id=' . $netracustomer_item->inventory_id . '&iv_no=' . $netracustomer_item->invoice_no : '';
				$delete_url = ( self::check_permission( 'edit_item', $netracustomer_item->inventory_id ) ) ? self::$self_url . '&action=delete&delete_id=' . $netracustomer_item->inventory_id : '';

				if ( ! $edit_url ) {
					continue;
				}
				?>
				<tr>
					<?php
					foreach ( $columns as $field => $data ) {
						$field = ( $field == 'category_id' ) ? 'inventory_category' : $field;

						$url = $edit_url;
						if ($field == 'user_id' || $field == 'inventory_user_id') {
							$url = get_edit_user_link( $netracustomer_item->{$field} );
						}
						echo '<td class="' . $field . '"><a href="' . $url . '">' .  $loop->get_field( $field ) . '</a></td>';
                                               //my code to calculate the toatl
                                                if($field=="s_qty"){
                                                    $totalQty = $totalQty + $loop->get_field( $field );
                                                }
					}
					?>
					<!-- <td class="action">
						<?php //if ( $edit_url ) { ?>
							<a href="<?php //echo $edit_url; ?>"><?php //self::_e( 'edit' ); ?></a>
						<?php //}
						//if ( $delete_url ) { ?>
							<a class="delete" data-name="<?php //echo $netracustomer_item->inventory_name; ?>"
							   href="<?php //echo $delete_url; ?>"><?php //self::_e( 'delete' ); ?></a>
						<?php //} ?>
						<?php //do_action( 'ncm_admin_action_links', $netracustomer_item->inventory_id ); ?>
					</td> -->
				</tr>
			<?php } ?>
		</table>

		<?php
		echo netracustomer_pagination( self::$self_url, $loop->get_pages() );
		do_action( 'ncm_admin_items_listing', $loop->get_query_args() );
	}        

	/**
	 * Creates the admin view for editing an inventory item.
	 *
	 * @param int $inventory_id
	 */
	public static function edit_item( $inventory_id = NULL ) {

		if ( ! self::check_permission( 'edit_item', $inventory_id ) ) {
			echo '<div class="error"><p>' . self::__( 'You do not have permission to edit this item.' ) . '</p></div>';

			return;
		}

		$image       = array();
		$media       = array();
		$media_title = array();

		$fields = self::get_item_fields();
		foreach ( $fields AS $f ) {
			if ( ! isset( ${$f} ) ) {
				${$f} = '';
			}
		}

		if ( isset( $_POST['inventory_name'] ) ) {
			extract( $_POST );
			$inventory_id = $inventory_item_id;
		} else if ( $inventory_id ) {
                    $iv_no=$_GET['iv_no'];
                         echo "<script type='text/javascript'>alert('$iv_no');</script>";
			$item = self::get_item( $inventory_id ); 
                        $productData = self::get_productData( $iv_no );
                      //  global $wpdb;
                      //  $productData = $wpdb->get_results($wpdb->prepare('SELECT * FROM  wp_netracustomer_item  WHERE invoice_no = %s', $iv_no));
                       // foreach($productData as $pData){
                            //echo "<script type='text/javascript'>alert('Invoices=$invoice');</script>";
                       // }
			extract( (array) $item );
			$image       = self::get_item_images( $inventory_id );
			$media       = self::get_item_media( $inventory_id );
			$media_title = array();
			foreach ( $media AS $i => $m ) {
				$media_title[ $i ] = $m->media_title;
			}
		}
                $invoice = self::get_invoiceItem();

		// TODO: Status drop-down method
                $c=1;
		?>
                
                <form method="post" action="<?php echo self::$self_url; ?>">
			<table class="form-table">
				<tr>
					<th><label for="invoice_no"><?php self::label( 'invoice_no' ); ?></label></th>
					<td><input name="invoice_no" class="regular-text" readonly
                                                   value="<?php if(esc_attr( $invoice_no )!=''){ echo esc_attr( $invoice_no ); } 
                                                   else{ echo $invoice_no= $invoice + 1;  }?>"/></td>
				</tr>                               
				<tr>
					<th><label for="category_id" > <?php self::label( 'category_id' ); ?></label></th>
					<td><?php echo self::$category->dropdown( 'category_id', $category_id ); ?></td>
				</tr>
                                <?php
				if ( self::label_is_on( 'vendor_name' ) ) { ?>
					<tr>
						<th><?php self::label( 'vendor_name' ); ?></th>
						<td><div class="ui-widget"><input id="vendor_name" name="vendor_name" class="regular-text onfocus" 
                                                                                  value="<?php echo esc_attr( $vendor_name ); ?>"/></div></td>
					</tr>
				<?php }
				if ( self::label_is_on( 'pdate' ) ) { ?>
					<tr>
						<th><?php self::label( 'pdate' ); ?></th>
						<td><input name="pdate" class="MyDate" 
						           value="<?php echo esc_attr( $inventory_date_added ); ?>"/></td>
					</tr>
				<?php }
				if ( self::label_is_on( 'vendor_address' ) ) { ?>
					<tr>
						<th><?php self::label( 'vendor_address' ); ?></th>
						<td><input id="vendor_address" name="vendor_address" class="regular-text"
						           value="<?php echo esc_attr( $vendor_address ); ?>"/></td>
					</tr>
				<?php }
				if ( self::label_is_on( 'p_name' ) ) { ?>
					<tr>
						<th><?php self::label( 'p_name' ); ?></th>
						<td><div class="ui-widget"><input name="p_name" class="regular-text onfocused" id="p_name"
                                                                                  value="<?php echo esc_attr( $p_name ); ?>"/></div></td>
					</tr>
				<?php }
				if ( self::label_is_on( 'p_model_no' ) ) { ?>
					<tr>
						<th><?php self::label( 'p_model_no' ); ?></th>
						<td><div class="ui-widget"><input name="p_model_no" class="regular-text onfocused" id="p_model_no"
                                                                                  value="<?php echo esc_attr( $p_model_no ); ?>"/></div></td>
					</tr>
				<?php }
				if ( self::label_is_on( 'p_qty' ) ) { ?>
					<tr>
						<th><?php self::label( 'p_qty' ); ?></th>
						<td><input name="p_qty" class="regular-text" id="p_qty" oninput='calProData()'
						           value="<?php echo esc_attr( $p_qty ); ?>"/></td>
					</tr>
				<?php }
				if ( self::label_is_on( 'p_rate' ) ) { ?>
					<tr>
						<th><?php self::label( 'p_rate' ); ?></th>
						<td><input name="p_rate" class="regular-text" id="p_rate" oninput='calProData()'
						           value="<?php echo esc_attr( $p_rate ); ?>"/></td>
					</tr>
				<?php }
				if ( self::label_is_on( 'p_total' ) ) { ?>
					<tr>
						<th><?php self::label( 'p_total' ); ?></th>
						<td><input name="p_total" class="regular-text" id="p_total" oninput='calProData()'
						           value="<?php echo esc_attr( $p_total ); ?>"/></td>
					</tr>
				<?php }
				if ( self::label_is_on( 'p_adv' ) ) { ?>
					<tr>
						<th><?php self::label( 'p_adv' ); ?></th>
						<td><input name="p_adv" class="regular-text" id="p_adv" oninput='calProData()'
						           value="<?php echo esc_attr( $p_adv ); ?>"/></td>
					</tr>
				<?php }                                
				if ( self::label_is_on( 'p_bal' ) ) { ?>
					<tr>
						<th><?php self::label( 'p_bal' ); ?></th>
						<td><input name="p_bal" class="regular-text" id="p_bal" oninput='calProData()'
						           value="<?php echo esc_attr( $p_bal ); ?>"/></td>
					</tr>
				<?php }
				if ( self::label_is_on( 'p_ddate' ) ) { ?>
					<tr>
						<th><?php self::label( 'p_ddate' ); ?></th>
						<td><input name="p_ddate" class="MyDate" 
						           value="<?php echo esc_attr( $inventory_date_updated ); ?>"/></td>
					</tr>
				<?php }                                
                                
                                if ( self::label_is_on( 'p_details' ) ) { ?>
					<tr>
						<th><?php self::label( 'p_details' ); ?></th>
						<td><?php wp_editor( $p_details, 'description', array(
								'media_buttons' => FALSE,
								'textarea_name' => 'p_details'
							) ); ?></td>
					</tr>
				<?php } ?>
				<tr class="images">
					<th><?php self::_e( 'Images' ); ?>
					<td>
						<?php // TODO:
						// For images / media, save with error loses sort.  Fix.
						self::item_image_input( $inventory_id, $image ); ?>
					</td>
				</tr>
				<?php if ( self::$config->get( 'use_media' ) ) { ?>
					<tr class="media">
						<th><?php self::_e( 'Media' ); ?>
						<td>
							<?php self::item_media_input( $inventory_id, $media, $media_title ); ?>
						</td>
					</tr>
				<?php } ?>
			</table>
                    <div class="" id="newTable"></div>
                    <input style="bottom: 55px; position: absolute;" type="button" onclick="my()" name="Add More" value="Add More" id="addMore" disabled>
                    
			<?php
			do_action( 'ncm_edit_item', $inventory_id ); ?>
			<input type="hidden" name="action" value="save"/>
			<input type="hidden" name="inventory_item_id" value="<?php echo $inventory_id; ?>"/>
			<?php wp_nonce_field( self::NONCE_ACTION, 'nonce' ); ?>
                        <p class="submit" style="bottom: 0px; position: absolute;">
				<a href="<?php echo self::$self_url; ?>" class="button"><?php self::_e( 'Cancel' ); ?></a>
				<?php if ( self::check_permission( 'save_item', $inventory_id ) ) { ?>
					<input type="submit" name="save" class="button button-primary"
					       value="<?php self::_e( 'Save Item' ); ?>"/>
				<?php } ?>
			</p>
		</form>

                        <?php $file= plugins_url( 'search/search.php' , dirname(__FILE__) ); 
                              $file2= plugins_url( 'search/searchBrand.php' , dirname(__FILE__) );
                              $file3= plugins_url( 'search/searchallstock.php' , dirname(__FILE__) );
                              $fileV= plugins_url( 'search/searchVendor.php' , dirname(__FILE__) );
                              $file4= plugins_url( 'search/searchVendorAddress.php' , dirname(__FILE__) );
                        // $output = "<script>console.log( 'Debug Objects: " . $file . "' );</script>";
                       //  echo $output;
                        
                        ?>
        <script>
            function calProData(){
                                var p_rate = document.getElementById("p_rate").value;
                                var p_qty = document.getElementById("p_qty").value;                            
                                
                document.getElementById("p_total").value = (p_rate*1)*(p_qty*1);
                document.getElementById("p_bal").value = document.getElementById("p_total").value - document.getElementById("p_adv").value ;

            }
                                        jQuery(function($) {                                          
                                //getting the vendor name
                                 $( "#vendor_name" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $fileV; ?>', {
                                                term: $('#vendor_name').val()
                                              }, response );
                                            }
                                 });
                              /*  $( "#skills" ).autocomplete({
                                 source: '<?php //echo $file; ?>'
                                }); */
                                $( "#p_name" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file2; ?>', {
                                                term: $('#p_name').val(),
                                                model: $('#p_model_no').val()
                                              }, response );
                                            }
                                 });
                                 
                                  $( "#p_model_no" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file; ?>', {
                                                term: $('#p_model_no').val(),
                                                f_brand: $('#p_name').val()
                                              }, response );
                                            }
                                    });
                                    
                                    //json for filling as per retrival of data
                                   
                                     $(".onfocused").focusout(function(){
                                        var data;
                                          data= "model="+$("#p_model_no").val()+"&brand="+$("#p_name").val();

                                       $.ajax({
                                         url: '<?php echo $file3; ?>',  
                                         type: "POST",
                                         dataType: "json",
                                         data: data,
                                         success: function(data) {
                                             if(data!=""){
                                                if($('#p_model_no').val(data[1])!=""){ $('#p_model_no').val(data[1]); }
                                                if($('#p_name').val(data[0])!=""){  $('#p_name').val(data[0]); }
                                            }
                                         }
                                       });
                                       return false;
                                     });
                                     
                                     //same code but to autofill Vendor Address
                                   
                                     $(".onfocus").focusout(function(){
                                        var data;
                                          data= "vname="+$("#vendor_name").val();

                                       $.ajax({
                                         url: '<?php echo $file4; ?>',  
                                         type: "POST",
                                         dataType: "json",
                                         data: data,
                                         success: function(data) {
                                             if(data!=""){
                                                if($('#vendor_address').val(data[0])!=""){  $('#vendor_address').val(data[0]); }
                                            }
                                         }
                                       });
                                       return false;
                                     });
                            });
        
        </script>

<?php

if($productData!==null){
foreach ($productData as $pData){
    $c=$c+1;
    ?>
<form id='form<?php echo ''.$c ?>' method='post' >  
    <input type="hidden" id="iid<?php echo ''.$c; ?>" name="iid<?php echo ''.$c; ?>" value="<?php echo $pData->inventory_id; ?>"/>
    <table class='form-table<?php echo ''.$c ?>'>
				<tbody>                        
									<tr>
						<th>Product Name</th>
                                                <td><div class="ui-widget"><input value="<?php echo $pData->p_name; ?>" class='regular-text onfocused' id='p_name<?php echo ''.$c ?>' name='p_name<?php echo ''.$c ?>'></div></td>
					</tr>
									<tr>
						<th>Model No</th>
                                                <td><div class="ui-widget"><input value="<?php echo $pData->p_model_no; ?>" class='regular-text onfocused' id='p_model_no<?php echo ''.$c ?>' name='p_model_no<?php echo ''.$c ?>'></div></td>
					</tr>
									<tr>
						<th>Quantity</th>
						<td><input value="<?php echo $pData->p_qty; ?>" class='regular-text' oninput='calProData()' id='p_qty<?php echo ''.$c ?>' name='p_qty<?php echo ''.$c ?>'></td>
					</tr>
									<tr>
						<th>Rate</th>
						<td><input value="<?php echo $pData->p_rate; ?>" class='regular-text' oninput='calProData()' id='p_rate<?php echo ''.$c ?>' name='p_rate<?php echo ''.$c ?>'></td>
					</tr>
									<tr>
						<th>Total</th>
                                                <td><input value="<?php echo $pData->p_total; ?>" class='regular-text' oninput='calProData()' id='p_total<?php echo ''.$c ?>' name='p_total<?php echo ''.$c ?>'></td>
					</tr>
									<tr>
						<th>Advance</th>
						<td><input value="<?php echo $pData->p_adv; ?>" class='regular-text' oninput='calProData()' id='p_adv<?php echo ''.$c ?>' name='p_adv<?php echo ''.$c ?>'></td>
					</tr>                                        
									<tr>
						<th>Balance</th>
						<td><input value="<?php echo $pData->p_bal; ?>" class='regular-text' oninput='calProData()' id='p_bal<?php echo ''.$c ?>' name='p_bal<?php echo ''.$c ?>'></td>
					</tr>
									<tr>
						<th>Due Date</th>
						<td><input value="<?php echo $pData->inventory_date_updated; ?>" class='MyDate' name='p_duedate<?php echo ''.$c ?>' id='dp1458535185551'></td>
					</tr>
							</tbody></table>
                        <p class='submit'>
				
				
					<input type='submit' id='abc'  name='save' class='button button-primary' value='Save' />
				
			</p>
</form>
        
        <script>
            function calProData(){
                                var p_rate = document.getElementById("p_rate<?php echo ''.$c ?>").value;
                                var p_qty = document.getElementById("p_qty<?php echo ''.$c ?>").value;                            
                                
                document.getElementById("p_total<?php echo ''.$c ?>").value = (p_rate*1)*(p_qty*1);
                //document.getElementById("bal").value = document.getElementById("total").value - document.getElementById("adv").value ;

            }
                            jQuery(function($) {
                              /*  $( "#skills" ).autocomplete({
                                 source: '<?php //echo $file; ?>'
                                }); */
                                $( "#p_name<?php echo ''.$c ?>" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file2; ?>', {
                                                term: $('#p_name<?php echo ''.$c ?>').val(),
                                                model: $('#p_model_no<?php echo ''.$c ?>').val()
                                              }, response );
                                            }
                                 });
                                 
                                  $( "#p_model_no<?php echo ''.$c ?>" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file; ?>', {
                                                term: $('#p_model_no<?php echo ''.$c ?>').val(),
                                                f_brand: $('#p_name<?php echo ''.$c ?>').val()
                                              }, response );
                                            }
                                    });
                                    
                                    //json for filling as per retrival of data
                                   
                                     $(".onfocused").focusout(function(){
                                        var data;
                                          data= "model="+$("#p_model_no<?php echo ''.$c ?>").val()+"&brand="+$("#p_name<?php echo ''.$c ?>").val();

                                       $.ajax({
                                         url: '<?php echo $file3; ?>',  
                                         type: "POST",
                                         dataType: "json",
                                         data: data,
                                         success: function(data) {
                                             if(data!=""){
                                                if($('#p_model_no<?php echo ''.$c ?>').val(data[1])!=""){ $('#p_model_no<?php echo ''.$c ?>').val(data[1]); }
                                                if($('#p_name<?php echo ''.$c ?>').val(data[0])!=""){  $('#p_name<?php echo ''.$c ?>').val(data[0]); }
                                            }
                                         }
                                       });
                                       return false;
                                     });
                                     
                            });
        
        </script>
<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('.MyDate').datepicker({
        dateFormat : 'yy-mm-dd'
    });
    jQuery("#form<?php echo ''.$c; ?>").submit(function(event) {
     
        alert("inner js was called iid is "+jQuery('input[name=iid<?php echo ''.$c; ?>]' ).val());
        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)
        var formData = {
                        'iid'                   : jQuery('input[name=iid<?php echo ''.$c; ?>]' ).val(),
			'i_no'                  : jQuery('input[name=invoice_no]' ).val(),
			'v_name'                 : jQuery('input[name=vendor_name]' ).val(),
                        'pdate'                       : jQuery('input[name=pdate]' ).val(),
			'v_address'              : jQuery('input[name=vendor_address]' ).val(),
			'p_name'                      : jQuery('input[name=p_name<?php echo ''.$c; ?>]' ).val(),
                        'p_model_no'                  : jQuery('input[name=p_model_no<?php echo ''.$c; ?>]').val(),
                        'p_qty'                       : jQuery('input[name=p_qty<?php echo ''.$c; ?>]').val(),
                        'p_rate'                      : jQuery('input[name=p_rate<?php echo ''.$c; ?>]').val(),
                        'p_total'                     : jQuery('input[name=p_total<?php echo ''.$c; ?>]').val(),
                        'p_adv'                       : jQuery('input[name=p_adv<?php echo ''.$c; ?>]').val(),
                        'p_bal'                       : jQuery('input[name=p_bal<?php echo ''.$c; ?>]').val(),
                        'p_duedate'                   : jQuery('input[name=p_duedate<?php echo ''.$c; ?>]').val(),
                        'p_details'                   : jQuery('input[name=p_details<?php echo ''.$c; ?>]').val(),
			'category_id'                 : jQuery('#category_id').val()                     
        };
alert(jQuery('input[name=invoice_no<?php echo ''.$c; ?>]' ).val());
       // process the form
jQuery.ajax({
    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
    url         : '../wp-content/plugins/netra-customer-management/includes/netracustomer.saveproduct.class.php', // the url where we want to POST
    data        : formData, // our data object
    dataType    : 'json' // what type of data do we expect back from the server
})
    // using the done promise callback
    .done(function(data) {

        // log data to the console so we can see
        console.log(data);

        // here we will handle errors and validation messages
        if ( ! data.success) {
            
            // handle errors for name ---------------
            if (data.errors.name) {
                jQuery('#name-group').addClass('has-error'); // add the error class to show red input
                jQuery('#name-group').append('<div class="help-block">' + data.errors.name + '</div>'); // add the actual error message under our input
            }

            // handle errors for email ---------------
            if (data.errors.email) {
                jQuery('#email-group').addClass('has-error'); // add the error class to show red input
                jQuery('#email-group').append('<div class="help-block">' + data.errors.email + '</div>'); // add the actual error message under our input
            }

            // handle errors for superhero alias ---------------
            if (data.errors.superheroAlias) {
                jQuery('#superhero-group').addClass('has-error'); // add the error class to show red input
                jQuery('#superhero-group').append('<div class="help-block">' + data.errors.superheroAlias + '</div>'); // add the actual error message under our input
            }

        } else {

            // ALL GOOD! just show the success message!
            jQuery('form').append('<div class="alert alert-success">' + data.message.Name + '</div>');

            // usually after form submission, you'll want to redirect
            // window.location = '/thank-you'; // redirect a user to another page
            alert('success'); // for now we'll just alert the user

        }

    });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });
});





</script>                        
<?php    }}
    ?>
<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('.MyDate').datepicker({
        dateFormat : 'yy-mm-dd'
    });
});





</script>
	<?php
	}

	/**
	 * Creates the image input fields when editing an item.
	 *
	 * @param $inventory_id
	 * @param array $images_posted
	 */
	private static function item_image_input( $inventory_id, $images_posted = NULL ) {
		$count  = 0;
		$images = array();

		// Load the images
		echo '<div data-type="image" class="mediasortable media-container">';
		if ( $inventory_id ) {
			$images = self::get_item_images( $inventory_id );
		} else if ( $images_posted ) {
			$images = array();
			foreach ( $images_posted AS $key => $image ) {
				$images[ $key ] = (object) array(
					'thumbnail' => $image,
					'post_id'   => NULL
				);
			}
		}

		// Loop through the images
		foreach ( (array) $images as $image ) {
			// Output the field for each existing
			if ( $image->thumbnail ) {
				self::item_image_field( $count, $image );
				$count ++;
			}
		}
		// Output one more new one
		self::item_image_field( $count );
		echo '<input type="hidden" name="imagesort" value="" id="imagesort" />';
		echo '</div>';
		echo ( $count > 1 ) ? '<p class="sortnotice">' . self::__( 'Drag and drop images to change sort order' ) . '</p>' : '';
	}

	/**
	 * Creates the markup for a single image input
	 *
	 * @param int $count
	 * @param string $image
	 */
	private static function item_image_field( $count, $image = NULL, $field_name = '' ) {

		$word = ( $image ) ? 'Change' : 'Add New';
		if ( is_object( $image ) ) {
			$url = '';
			$url = ( ! empty( $image->thumbnail ) ) ? $image->thumbnail : $url;
			$url = ( ! empty( $image->medium ) ) ? $image->medium : $url;
			$url = ( ! empty( $image->large ) ) ? $image->large : $url;
			$url = ( ! empty( $image->full ) ) ? $image->full : $url;
		} else {
			$url = $image;
		}

		if ( ! $field_name ) {
			$field_name = 'image[' . $count . ']';
		}

		echo '<div class="imagewrapper mediawrap" data-count="' . $count . '">';
		echo '<div class="imagecontainer" id="inventory-div-' . $count . '">';
		if ( $url ) {
			echo '<img class="image-upload" id="inventory-image-' . $count . '" src="' . $url . '" />';
			echo '<a href="javascript:removeImage(' . $count . ');" class="delete" id="inventory-delete-' . $count . '" title="Click to remove image">X</a>';
		}
		echo '</div>';
		echo '<a href="media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=673" data-count="' . $count . '" id="inventory-link-' . $count . '" class="netracustomer-upload">' . $word . ' ' . self::__( 'Image' ) . '</a>';
		echo '<input type="hidden" name="' . $field_name . '" value="' . $url . '" id="inventory-field-' . $count . '" />';
		echo '</div>';
	}

	private static function item_media_input( $inventory_id, $media_posted = NULL, $media_title_posted = NULL ) {
		$count = 0;
		$media = array();

		// Load the media
		echo '<div data-type="media" class="mediasortable media-container">';
		if ( $inventory_id ) {
			$media = self::get_item_media( $inventory_id );
		} else if ( $media_posted ) {
			$media = array();
			foreach ( $media_posted AS $key => $m ) {
				$media[ $key ] = (object) array(
					'media'       => $m,
					'media_title' => $media_title_posted[ $key ],
					'post_id'     => NULL
				);
			}
		}

		// Loop through the images
		foreach ( (array) $media as $item ) {
			// Output the field for each existing
			if ( $item->media ) {
				self::item_media_field( $count, $item );
				$count ++;
			}
		}
		// Output one more new one
		self::item_media_field( $count );
		echo '<input type="hidden" name="mediasort" value="" id="mediasort" />';
		echo '</div>';
		echo '<a href="media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=673" data-count="' . $count . '" id="inventory-link-' . $count . '" class="button netracustomer-upload">' . self::__( 'Add Media' ) . '</a>';
		echo ( $count > 1 ) ? '<p class="mediasortnotice">' . self::__( 'Drag and drop media to change sort order' ) . '</p>' : '';
	}

	private static function item_media_field( $count, $media = NULL ) {
		$url   = ( ! empty( $media->media ) ) ? $media->media : '';
		$title = ( ! empty( $media->media_title ) ) ? $media->media_title : '';
		if ( $url ) {
			echo '<div class="mediacontainer mediawrap" data-count="' . $count . '" id="inventory-media-' . $count . '">';
			echo '<a href="javascript:removeMedia(' . $count . ');" class="delete" id="inventory-delete-' . $count . '" title="Click to remove Media">X</a>';
			echo '<p><label>' . self::__( 'Title' ) . ':</label><input type="text" class="widefat" name="media_title[' . $count . ']" value="' . esc_attr( $title ) . '" />';
			echo '<p class="media_url"><label>' . self::__( 'URL' ) . ':</label>' . $url . '</p>';
			echo '<input type="hidden" name="media[' . $count . ']" value="' . $url . '" id="inventory-media-field-' . $count . '" />';
			echo '</div>';
		}
	}

	/**
	 * Function to save an item.
	 * Checks permission first.
	 * Then loads all the labels that are configured (and can be extended via filter 'ncm_default_labels') and loads from _$POST
	 *
	 * @return bool
	 */
	public static function save_item() {

		$inventory_slug = '';
		$image          = array();
		$media          = array();

		// Rather than extract $_POST, get the specific fields we require
		$fields = self::get_labels();
		foreach ( $fields AS $field => $labels ) {
			${$field} = self::request( $field );
		}

		$inventory_id = self::request( "inventory_item_id" );

		if ( ! self::check_permission( 'save_item', $inventory_id ) ) {
			self::$error = self::__( 'You do not have permission to save this item.' );
		}

		if ( ! wp_verify_nonce( self::request( "nonce" ), self::NONCE_ACTION ) ) {
			self::$error = self::__( 'Security failure.  Please try again.' );
		}
	/* used to check for required fields my code i have commented this as i was connfused :D	
         * if ( ! $inventory_number && ! $inventory_name ) {
			self::$error = self::__( 'Either ' ) .
			               self::get_label( 'inventory_name' ) .
			               ' or ' . self::get_label( 'inventory_number' ) .
			               ' ' . self::__( 'is required.' );
		}  */

		if ( ! self::$error ) {
               global $current_user;
                global $wpdb;
                
                $sirus=$current_user->user_login;
                $gid = $wpdb->get_var( "SELECT gid FROM $wpdb->users where user_login='$sirus'" );
               // $message = "The User_id is ".$current_user->user_login." The gid is ".$gid;
               // echo "<script type='text/javascript'>alert('$message');</script>";
			$data = array(
				'inventory_id'               => $inventory_id,
				'invoice_no'                  => $invoice_no,
                                'vendor_name' => $vendor_name,
                                'vendor_address' => $vendor_address,
                                'p_name' => $p_name,
                                'p_model_no' => $p_model_no,
                                'p_qty' => $p_qty,
                                'p_rate' => $p_rate,
                                'p_total' => $p_total,
                                'p_adv' => $p_adv,
                                'p_bal' => $p_bal,                                
                                'p_ddate' => $p_ddate,
                                'p_details' => $p_details,
                                'category_id' => $category_id,
                                'gid' => $gid,
			);

			if ( $inventory_id = self::$item->save( $data ) ) {
				$imagesort = explode( ',', self::request( 'imagesort' ) );
				$mediasort = explode( ',', self::request( 'mediasort' ) );

				self::$item->save_images( $inventory_id, self::request( 'image' ), $imagesort );

				do_action( 'ncm_save_item', $inventory_id, $data );

				// Only call this if enabled
				if ( self::$config->get( 'use_media' ) ) {
					self::$item->save_media( $inventory_id, self::request( 'media' ), self::request( 'media_title' ), $mediasort );
				}

				return TRUE;
			}
		}
	}

	public static function delete_item() {
		$inventory_id = (int) self::request( "delete_id" );
		if ( ! $inventory_id ) {
			self::$error = self::__( 'Inventory id not set.  Item not deleted.' );

			return FALSE;
		}

		if ( ! self::$item->delete( $inventory_id ) ) {
			self::$error = self::$item->get_message();

			return FALSE;
		}

		self::$message = self::__( 'Inventory item deleted successfully.' );

		return TRUE;
	}

	/**
	 * Mini controller method for handling categories
	 */
	public static function manage_edit_type() {

		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		$action      = self::get_action();
		$category_id = self::request( "category_id" );

		if ( $action == 'save' ) {
			if ( self::save_category() ) {
				$action        = '';
				self::$message = self::__( 'Category' ) . ' ' . self::__( 'saved successfully.' );
			} else {
				$action = 'edit';
			}
		}

		if ( $action == 'delete' ) {
			if ( self::delete_category( $category_id ) ) {
				self::$message = self::__( 'Category' ) . ' ' . self::__( 'deleted successfully.' );
			} else {
				self::output_errors();
			}
			$action = '';
		}

		self::admin_heading( self::__( 'Manage Categories' ) );

		if ( $action == 'edit' || $action == 'add' ) {
			self::edit_category( $category_id );
		}

		if ( ! $action ) {
			self::list_categories();
		}

		self::admin_footer();
	}

	public static function list_categories() {

		$categories = self::get_categories();

		$columns = array(
			'category_name'       => array(
				'title' => self::__( 'Category' ),
				'class' => 'name'
			),
			'category_sort_order' => array(
				'title' => self::__( 'Sort Order' ),
				'class' => 'number'
			)
		);

		?>
		<a class="button button-primary"
		   href="<?php echo self::$self_url; ?>&action=add"><?php self::_e( 'Add Category' ); ?></a>
		<table class="grid categorygrid">
			<?php echo self::grid_columns( $columns, self::$self_url, 'category_name' );
			foreach ((array) $categories AS $category) { ?>
			<tr>
				<td class="name"><a
						href="<?php echo self::$self_url; ?>&action=edit&category_id=<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></a>
				</td>
				<td class="number"><?php echo $category->category_sort_order; ?></td>
				<td class="action">
					<a href="<?php echo self::$self_url; ?>&action=edit&category_id=<?php echo $category->category_id; ?>"><?php self::_e( 'Edit' ); ?></a>
					<a class="delete"
					   href="<?php echo self::$self_url; ?>&action=delete&category_id=<?php echo $category->category_id; ?>"><?php self::_e( 'Delete' ); ?></a>
				</td>
				<?php } ?>
		</table>

	<?php
	}

	public static function edit_category( $category_id ) {
		$category_name        = '';
		$category_description = '';
		$category_slug        = '';
		$category_sort_order  = 1;

		if ( isset( $_POST['category_name'] ) ) {
			extract( $_POST );
		} else if ( $category_id ) {
			$category = self::get_category( $category_id );
			extract( (array) $category );
		}

		?>
		<form method="post" action="<?php echo self::$self_url; ?>">
			<table class="form-table">
				<tr>
					<th><?php self::_e( 'Category Name' ); ?></th>
					<td><input name="category_name" class="regular-text"
					           value="<?php echo esc_attr( $category_name ); ?>"/></td>
				</tr>
				<?php if ( self::getOption( 'seo_friendly' ) ) { ?>
					<tr>
						<th><?php self::_e( 'Permalink' ); ?></th>
						<td><input name="category_slug" value="<?php echo $category_slug; ?>"/></td>
					</tr>
				<?php } ?>
				<tr>
					<th><?php self::_e( 'Description' ); ?></th>
					<td><textarea
							name="category_description"><?php echo esc_textarea( $category_description ); ?></textarea>
					</td>
				</tr>
				<tr>
					<th><?php self::_e( 'Sort Order' ); ?></th>
					<td><input name="category_sort_order" class="small-text"
					           value="<?php echo $category_sort_order; ?>"/></td>
				</tr>
			</table>
			<input type="hidden" name="action" value="save"/>
			<input type="hidden" name="category_id" value="<?php echo $category_id; ?>"/>
			<?php wp_nonce_field( self::NONCE_ACTION, 'nonce' ); ?>
			<p class="submit">
				<a href="<?php echo self::$self_url; ?>" class="button"><?php self::_e( 'Cancel' ); ?></a>
				<input type="submit" name="save" class="button button-primary"
				       value="<?php self::_e( 'Save Category' ); ?>"/>
			</p>
		</form>
	<?php
	}

	public static function save_category() {

		$category_slug = '';

		extract( $_POST );

		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			self::$error = self::__( 'Security failure.  Please try again.' );
		}

		if ( ! $category_name ) {
			self::$error = self::__( 'Category Name' ) . ' ' . self::__( 'is required.' );
		}

		if ( ! self::$error ) {
			$data = array(
				'category_name'        => $category_name,
				'category_slug'        => $category_slug,
				'category_description' => $category_description,
				'category_sort_order'  => $category_sort_order,
				'category_id'          => $category_id
			);

			return self::$category->save( $data );
		}

	}

	public static function delete_category() {
		$category_id = (int) self::request( "category_id" );
		if ( ! $category_id ) {
			self::$error = self::__( 'Category id not set.  Category not deleted.' );

			return FALSE;
		}

		if ( ! self::$category->delete( $category_id ) ) {
			self::$error = self::$category->get_message();

			return FALSE;
		}

		self::$message = self::__( 'Category item deleted successfully.' );

		return TRUE;
	}

	/**
	 * Mini controller method for handling labels
	 */
	public static function manage_edit_labels() {

		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		$action      = self::get_action();
		$category_id = self::request( "label_id" );

		if ( $action == 'save' ) {
			if ( self::save_labels() ) {
				$action        = '';
				self::$message = self::__( 'Labels' ) . ' ' . self::__( 'saved successfully.' );
			} else {
				$action = 'edit';
			}
		}

		self::admin_heading( self::__( 'Manage Labels' ) );

		if ( $action == 'edit' || $action == 'add' ) {
			self::list_labels( TRUE );
		}

		if ( $action == 'default' ) {
			self::reset_labels();
			$action = '';
		}

		if ( ! $action ) {
			self::list_labels();
		}

		self::admin_footer();
	}

	public static function list_labels( $edit = FALSE ) {

		$always_on = self::get_labels_always_on();

		$labels = self::get_labels();

		if ( ! $edit ) { ?>
			<a class="button-primary"
			   href="<?php echo self::$self_url; ?>&action=edit"><?php self::_e( 'Edit Labels' ); ?></a>
		<?php } ?>
		<form method="post" action="<?php echo self::$self_url; ?>">
			<?php if ( $edit ) { ?>
				<input type="hidden" name="action" value="save"/>
				<p class="submit">
					<a href="<?php echo self::$self_url; ?>" class="button"><?php self::_e( 'Cancel' ); ?></a>
					<input type="submit" class="button-primary" name="save"
					       value="<?php self::_e( 'Save Labels' ); ?>"/>
					<a class="button"
					   href="<?php echo self::$self_url; ?>&action=default"><?php self::_e( 'Reset to Defaults' ); ?></a>
				</p>
			<?php } ?>
			<table class="form-table">
				<?php foreach ($labels AS $field => $label) {
				$class = ( ! $label['is_used'] ) ? ' class="not_used"' : ''; ?>
				<tr<?php echo $class; ?>>
					<th><label for="<?php echo $field; ?>"><?php echo $label['default']; ?>:</label></th>
					<?php if ( $edit ) {
						$in_use_checked = ( $label['is_used'] ) ? ' checked' : '';
						$numeric_checked = ( $label['is_numeric'] ) ? ' checked' : ''; ?>
						<td><input type="text" name="<?php echo $field; ?>"
						           value="<?php echo esc_attr( $label['label'] ); ?>"/>
							</td>
							<td>
							<?php if ( ! in_array( $field, $always_on ) ) { ?>
								<input type="checkbox" class="is_used" id="is_used<?php echo $field; ?>"
								       name="is_used[<?php echo $field; ?>]"<?php echo $in_use_checked; ?> />
								<label for="is_used<?php echo $field; ?>"><?php self::_e( 'Use Field' ); ?></label>
							<?php } else { ?>
								<span class="always_on"><?php self::_e( 'Always On' ); ?></span>
							<?php } ?>
						</td>
						<td>
							<input type="checkbox" class="is_numeric" id="is_used<?php echo $field; ?>"
								   name="is_numeric[<?php echo $field; ?>]"<?php echo $numeric_checked; ?> />
							<label for="is_numeric<?php echo $field; ?>"><?php self::_e( 'Sort Numerically' ); ?></label>
						</td>
					<?php } else { ?>
						<td><span><?php echo $label['label']; ?></span></td>
					<?php }
					} ?>
			</table>
		</form>
	<?php
	}

	public static function save_labels() {
		$labels  = self::get_labels();
		$is_used = self::request( "is_used" );
		$is_numeric = self::request("is_numeric");

		$save_data = array();

		foreach ( $labels AS $field => $data ) {
			if ( isset( $_POST[ $field ] ) ) {
				$save_data[ $field ] = $_POST[ $field ];
			}
			$is_used[ $field ] = ( isset( $is_used[ $field ] ) ) ? 1 : 0;
			$is_numeric[ $field ] = ( isset( $is_numeric[ $field ] ) ) ? 1 : 0;
		}

		return self::$label->save( $save_data, $is_used, $is_numeric );
	}

	public static function manage_edit_display() {
		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		$action = self::get_action();

		if ( $action == 'save' ) {
			if ( self::save_display() ) {
				$action        = '';
				self::$message = self::__( 'Display Settings' ) . ' ' . self::__( 'saved successfully.' );
			} else {
				$action = 'edit';
			}
		}

		self::admin_heading( self::__( 'Manage Display' ) );
		self::edit_display();
		self::admin_footer();
	}

	public static function edit_display() {
		$display_listing = (array) self::getDisplay( 'listing' );
		$display_detail  = (array) self::getDisplay( 'detail' );
		$display_admin   = (array) self::getDisplay( 'admin' );
		$settings        = self::getOptions();

		$labels = self::get_labels();

		$available        = '';
		$selected_listing = '';
		$selected_detail  = '';
		$selected_admin   = '';

		foreach ( $display_listing AS $sort => $key ) {
			$data = ( isset( $labels[ $key ] ) ) ? $labels[ $key ] : NULL;
			if ( $data ) {
				$selected_listing .= '<li data-field-id="' . $key . '">' . $data['label'] . '</li>';
			}
		}

		foreach ( $display_detail AS $sort => $key ) {
			$data = ( isset( $labels[ $key ] ) ) ? $labels[ $key ] : NULL;
			if ( $data ) {
				$selected_detail .= '<li data-field-id="' . $key . '">' . $data['label'] . '</li>';
			}
		}

		foreach ( $display_admin AS $sort => $key ) {
			$data = ( isset( $labels[ $key ] ) ) ? $labels[ $key ] : NULL;
			if ( $data ) {
				$selected_admin .= '<li data-field-id="' . $key . '">' . $data['label'] . '</li>';
			}
		}

		foreach ( $labels AS $key => $data ) {
			$available .= ( $data['is_used'] ) ? '<li data-field-id="' . $key . '">' . $data['label'] . '</li>' : '';
		}

		$sizes = array(
			'thumbnail' => 'Thumbnail',
			'medium'    => 'Medium',
			'large'     => 'Large',
			'full'      => 'Full'
		);
		?>
		<form method="post" action="<?php echo self::$self_url; ?>">
			<div class="submit">
				<a href="<?php echo self::$self_url; ?>" class="button"><?php _e( 'Cancel' ); ?></a>
				<input type="submit" name="save" value="<?php self::_e( 'Save Settings' ); ?>" class="button-primary"/>
			</div>
			<p><?php _e( 'Drag-and-drop items from the Available Fields list to the desired list to control what shows on the front-end inventory.' ); ?></p>

			<div class="list list_available"><h3><?php self::_e( 'Available Fields' ); ?></h3>
				<ul id="available" class="sortable">
					<?php echo $available; ?>
					<li style="display: none !important; data-field-id=''">Shiv for jQuery to insert before</li>
				</ul>
			</div>
			<div class="list list_selected"><h3><?php self::_e( 'Show in Listing' ); ?></h3>
				<ul id="selected_listing" class="sortable">
					<?php echo $selected_listing; ?>
				</ul>
				<a href="javascript:void(0)" class="add_all"><?php self::_e( 'Add All Fields' ); ?></a>
			</div>
			<div class="list list_selected"><h3><?php self::_e( 'Show on Detail' ); ?></h3>
				<ul id="selected_detail" class="sortable">
					<?php echo $selected_detail; ?>
				</ul>
				<a href="javascript:void(0)" class="add_all"><?php self::_e( 'Add All Fields' ); ?></a>
			</div>
			<div class="list list_selected"><h3><?php self::_e( 'Show in Admin' ); ?></h3>
				<ul id="selected_admin" class="sortable">
					<?php echo $selected_admin; ?>
				</ul>
				<a href="javascript:void(0)" class="add_all"><?php self::_e( 'Add All Fields' ); ?></a>
			</div>
			<input id="detail" name="selected_detail" type="hidden" value=""/>
			<input id="listing" name="selected_listing" type="hidden" value=""/>
			<input id="admin" name="selected_admin" type="hidden" value=""/>
			<table>
				<tr>
					<th><?php self::_e( 'Show Labels in Listing' ); ?></th>
					<td><?php echo self::dropdown_yesno( "display_listing_labels", $settings['display_listing_labels'] ); ?></td>
				</tr>
				<tr>
					<th><?php self::_e( 'Display Listing as Table' ); ?></th>
					<td><?php echo self::dropdown_yesno( "display_listing_table", $settings['display_listing_table'] ); ?>
						<p class="description"><?php self::_e( 'Table (spreadsheet) view, or each item in a separate box' ); ?>
					</td>
				</tr>
				<tr>
					<th><?php self::_e( 'Image size in Listing' ); ?></th>
					<td><?php echo self::dropdown_array( "display_listing_image_size", $settings['display_listing_image_size'], $sizes ); ?></td>
				</tr>
				<tr>
					<th><?php self::_e( 'Show Labels on Detail' ); ?></th>
					<td><?php echo self::dropdown_yesno( "display_detail_labels", $settings['display_detail_labels'] ); ?></td>
				</tr>
				<tr>
					<th><?php self::_e( 'Image size in Detail' ); ?></th>
					<td><?php echo self::dropdown_array( "display_detail_image_size", $settings['display_detail_image_size'], $sizes ); ?></td>
				</tr>
			</table>
			<input type="hidden" name="action" value="save"/>

			<div class="submit">
				<a href="<?php echo self::$self_url; ?>" class="button"><?php _e( 'Cancel' ); ?></a>
				<input type="submit" name="save" value="<?php self::_e( 'Save Settings' ); ?>" class="button-primary"/>
			</div>
		</form>
                       
                 
		<script>
			jQuery(function ($) {
				var pos;
				$('.sortable').sortable({
					connectWith: '.sortable',
					placeholder: 'ui-state-highlight',
					helper: 'clone',
					start: function (event, ui) {
						ui.placeholder.html($(ui.item).html());
						pos = $(ui.item).index();
					},
					receive: function (event, ui) {
						var sender = ui.sender.attr('id');
						if (sender == 'available') {
							$(ui.item).clone().insertBefore('#available li:eq(' + pos + ')');
						} else if ($(ui.item).closest('ul').attr('id') == 'available') {
							ui.item.remove();
						}

						var receiver = $(this).closest('ul').attr('id');

						if (receiver == 'selected_admin') {
							var field = ui.item.attr('data-field-id');
							if (field == 'inventory_image' || field == 'inventory_images' || field == 'inventory_media') {
								alert('<?php echo self::__('You may not display images or media in the admin listing.'); ?>');
								ui.item.remove();
							}
						}
					},
					update: function () {
						updateDisplay();
					}

				});

				$('.add_all').click(
					function () {
						var _this = $(this).siblings('ul');
						$('#available li').each(
							function () {
								if (_this.find('li[data-field-id="' + $(this).attr('data-field-id') + '"]').length <= 0) {
									$(this).clone().appendTo(_this);
								}
							}
						);
						updateDisplay();
					}
				);

			});

			updateDisplay();

			function updateDisplay() {
				var val = '';
				jQuery('#selected_listing li').each(function () {
					val += jQuery(this).attr('data-field-id') + ',';
				});
				jQuery('#listing').val(val);

				val = '';
				jQuery('#selected_detail li').each(function () {
					val += jQuery(this).attr('data-field-id') + ',';
				});
				jQuery('#detail').val(val);

				val = '';
				jQuery('#selected_admin li').each(function () {
					val += jQuery(this).attr('data-field-id') + ',';
				});
				jQuery('#admin').val(val);
			}
		</script>

	<?php }

	public static function save_display() {
		$display = trim( self::request( 'selected_detail' ), ' ,' );
		self::updateOption( 'display_detail', $display );

		$display = trim( self::request( 'selected_listing' ), ' ,' );
		self::updateOption( 'display_listing', $display );

		$display = trim( self::request( 'selected_admin' ), ' ,' );
		self::updateOption( 'display_admin', $display );

		$fields = array(
			'display_listing_labels',
			'display_listing_table',
			'display_detail_labels',
			'display_listing_image_size',
			'display_detail_image_size'
		);

		foreach ( $fields AS $field ) {
			self::updateOption( $field, self::request( $field ) );
		}

		return TRUE;
	}

	public static function manage_edit_settings() {
		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		$action = self::get_action();

		if ( $action == 'save' ) {
			if ( self::save_settings() ) {
				$action = '';
				//Ensure the $wp_rewrite global is loaded
				global $wp_rewrite;
				//Call flush_rules() as a method of the $wp_rewrite object
				$wp_rewrite->flush_rules();
				self::$message = self::__( 'Settings' ) . ' ' . self::__( 'saved successfully, and rewrite rules flushed.' );
			} else {
				$action = 'edit';
			}
		}

		self::admin_heading( self::__( 'Manage Settings' ) );
		self::edit_settings();
		self::admin_footer();
	}

	public static function edit_settings() {
		$settings = self::getOptions();

		$themes = self::load_available_themes();
		$themes = array_keys( $themes );
		$themes = array_combine( $themes, $themes );
		$themes = array_merge( array( '' => self::__( ' - None / No CSS -' ) ), $themes );

		/**
		 * Currency formatting.  Names are pretty clear.
		 * 'currency_symbol'                => '$',
		 * 'currency_thusands_separator'    => ',',
		 * 'currency_decimal_separator'    => '.',
		 * 'currency_decimal_precision'    => '2',
		 * // Date format.  Uses PHP formats: http://php.net/manual/en/function.date.php
		 * 'date_format'                    => 'm/d/Y',
		 */

		$dropdown_array = array(
			'manage_options'    => self::__( 'Administrator' ),
			'edit_others_posts' => self::__( 'Editor' ),
			'publish_posts'     => self::__( 'Author' ),
			'edit_posts'        => self::__( 'Contributor' ),
			'read'              => self::__( 'Subscriber' )
		);

		$permission_dropdown = self::dropdown_array( "permissions_lowest_role", $settings['permissions_lowest_role'], $dropdown_array );

		$dropdown_array = array(
			1 => self::__( "Any items" ),
			2 => self::__( "Only their own items" )
		);

		$permission_user_dropdown = self::dropdown_array( "permissions_user_restricted", $settings['permissions_user_restricted'], $dropdown_array );

		$date_format_dropdown = self::dropdown_date_format( "date_format", $settings['date_format'] );

		$dropdown_array = array(
			''      => self::__( 'Do not display' ),
			'g:i'   => '3:45',
			'h:i'   => '03:45',
			'g:i a' => '3:45 pm',
			'h:i a' => '03:45 pm',
			'g:i A' => '3:45 PM',
			'h:i A' => '03:45 PM',
			'H:i'   => '15:45',
			'H:i a' => '15:45 pm',
			'H:i A' => '15:45 PM',
		);

		$time_format_dropdown = self::dropdown_array( "time_format", $settings['time_format'], $dropdown_array );

		$currency_symbol_location_array = array(
			'0' => self::__( 'Before' ),
			'1' => self::__( 'After' )
		);

		$currency_decimal_precision_array = array(
			0 => 0,
			1 => 1,
			2 => 2,
			3 => 3,
			4 => 4
		);

		$permalinks    = get_option( 'permalink_structure' );
		$permalink_tip = '';
		if ( ! $permalinks ) {
			$settings['seo_urls'] = 0;
			$permalink_tip        = '<p class="description">' . self::__( 'SEO URLs will not work with your current ' ) . '<a href="options-permalink.php">' . self::__( 'Permalink Structure' ) . '</a></p>';
		}

		$add_ons = self::get_add_ons(TRUE);
		$all_reg_info = self::get_reg_info();

		$reg_tip = array();
		$reg_info = (isset($all_reg_info['core'])) ? $all_reg_info['core'] : FALSE;
		$reg_tip['core'] = ( empty( $reg_info['expires'] ) || ( $reg_info['expires'] < time() ) )
			? '<p class="license license_invalid">' . self::__( 'License key is invalid or expired.' ) . '</p>'
			: '<p class="license license_valid">' . sprintf( self::__( 'License key is valid until %s' ), self::format_date( $reg_info['expires'] ) ) . '</p>';

		foreach($add_ons AS $key => $add_on) {
			$reg_info = (isset($all_reg_info[$add_on->key])) ? $all_reg_info[$add_on->key] : FALSE;
			$reg_tip[$add_on->key] = ( empty( $reg_info['expires'] ) || ( $reg_info['expires'] < time() ) )
				? '<p class="license license_invalid">' . self::__( 'License key is invalid or expired.' ) . '</p>'
				: '<p class="license license_valid">' . sprintf( self::__( 'License key is valid until %s' ), self::format_date( $reg_info['expires'] ) ) . '</p>';
		}
		?>
		<form method="post" action="<?php echo self::$self_url; ?>" class="inventory-config">
			<table class="form-table">
				<tr>
					<th colspan="2"><h3><?php self::_e( 'Inventory Manager License Keys' ); ?></h3></th>
				</tr>
				<tr>
					<td><?php self::_e( 'Inventory Manager License Key' ); ?></td>
					<td><input type="text" class="large-text" style="max-width: 300px;" name="license_key"
					           value="<?php echo $settings['license_key']; ?>"/>
						<?php echo $reg_tip['core']; ?></td>
				</tr>
				<?php
				foreach($add_ons AS $add_on) {
					$value = (isset($settings['license_key_' . $add_on->key])) ? $settings['license_key_' . $add_on->key] : '';
					echo '<tr><td>' . sprintf(self::__('%s License Key'), $add_on->item_name) . '</td>';
					echo '<td><input type="text" class="large-text" style="max-width:300px;" name="license_key_' . $add_on->key . '" value="' . $value . '" > ';
					echo $reg_tip[$add_on->key];
					echo '</td></tr>';
				} ?>
				<tr>
					<th colspan="2"><h3><?php self::_e( 'Permission Settings' ); ?></h3></th>
				</tr>
				<tr>
					<th><?php self::_e( 'Minimum Role to Add/Edit Items' ); ?></th>
					<td><?php echo $permission_dropdown; ?></td>
				</tr>
				<tr>
					<th><?php self::_e( 'Allow Users to Edit' ); ?></th>
					<td><?php echo $permission_user_dropdown; ?></td>
				</tr>
				<tr>
					<th colspan="2"><h3><?php self::_e( 'General Settings' ); ?></h3></th>
				</tr>
				<tr>
					<th><?php self::_e( 'Use SEO URLs' ); ?></th>
					<td><?php
						echo self::dropdown_yesno( "seo_urls", $settings['seo_urls'] );
						echo $permalink_tip;
						?></td>
				</tr>

				<tr class="seo_urls">
					<th><?php self::_e( 'SEO Endpoint' ); ?></th>
					<td><input type="text" class="medium-text" name="seo_endpoint"
					           value="<?php echo $settings['seo_endpoint']; ?>"/></td>
				</tr>
				<tr>
					<th><?php self::_e( 'Theme' ); ?></th>
					<td><?php
						echo self::dropdown_array( "theme", $settings['theme'], $themes, 'netracustomer_themes' );
						echo '<div class="theme_screenshot"></div>';
						?></td>
				</tr>
				<tr>
					<th><?php self::_e( 'Use Media Fields' ); ?></th>
					<td><?php echo self::dropdown_yesno( "use_media", $settings['use_media'] ); ?>
						<p class="description"><?php self::_e( 'Setting this to no still allows images, just not additional media' ); ?>
					</td>
				</tr>
				<tr>
					<th><?php self::_e( 'Items Per Page' ); ?></th>
					<td><input type="text" class="small-text" name="page_size"
					           value="<?php echo $settings['page_size']; ?>"></td>
				</tr>
				<tr>
					<th colspan="2"><h3><?php self::_e( 'Date Format Settings' ); ?></h3></th>
				</tr>
				<tr>
					<th><?php self::_e( 'Date Format' ); ?></th>
					<td><?php echo $date_format_dropdown; ?></td>
				</tr>
				<tr>
					<th><?php self::_e( 'Time Format' ); ?></th>
					<td><?php echo $time_format_dropdown; ?></td>
				</tr>
				<tr>
					<th colspan="2"><h3><?php self::_e( 'Currency Format Settings' ); ?></h3></th>
				</tr>
				<tr>
					<th><?php self::_e( 'Currency Symbol' ); ?></th>
					<td><input type="text" name="currency_symbol" class="small-text"
					           value="<?php echo $settings['currency_symbol']; ?>"/></td>
				</tr>
				<tr>
					<th><?php self::_e( 'Currency Symbol Location' ); ?></th>
					<td><?php echo self::dropdown_array( 'currency_symbol_location', $settings['currency_symbol_location'], $currency_symbol_location_array ); ?></td>
				</tr>
				<tr>
					<th><?php self::_e( 'Currency Thousands Separator' ); ?></th>
					<td><input type="text" name="currency_thousands_separator" class="small-text"
					           value="<?php echo $settings['currency_thousands_separator']; ?>"/></td>
				</tr>
				<tr>
					<th><?php self::_e( 'Currency Decimal Separator' ); ?></th>
					<td><input type="text" name="currency_decimal_separator" class="small-text"
					           value="<?php echo $settings['currency_decimal_separator']; ?>"/></td>
				</tr>
				<tr>
					<th><?php self::_e( 'Currency Precision (decimal places)' ); ?></th>
					<td><?php echo self::dropdown_array( 'currency_decimal_precision', $settings['currency_decimal_precision'], $currency_decimal_precision_array ); ?></td>
				</tr>
				<tr>
					<td><?php self::_e( 'Currency Example (with settings):' ); ?></td>
					<td><?php echo self::format_currency( 45250.25555 ) ?>
				</tr>
				<tr>
					<th colspan="2"><h3><?php self::_e( 'Reserve Settings' ); ?></h3></th>
				</tr>
				<tr>
					<th><?php self::_e( 'Allow Visitors to Reserve Items' ); ?></th>
					<td><?php echo self::dropdown_yesno( "reserve_allow", $settings['reserve_allow'] ); ?></td>
				</tr>
				<tr class="reserve">
					<th><?php self::_e( 'Ask for Qty When Reserving' ); ?></th>
					<td><?php echo self::dropdown_yesno( "reserve_quantity", $settings['reserve_quantity'] ); ?></td>
				</tr>
				<tr class="reserve">
					<th><?php self::_e( 'Decrease Qty in System on Reserve' ); ?></th>
					<td><?php echo self::dropdown_yesno( "reserve_decrement", $settings['reserve_decrement'] ); ?></td>
				</tr>
				<tr class="reserve">
					<th><?php self::_e( 'Send-to Email When Reserve' ); ?></th>
					<td><input type="text" class="widefat" name="reserve_email"
					           value="<?php echo $settings['reserve_email']; ?>">
					<p class="description"><?php self::_e('If left blank, the E-Mail Address from Settings -> General will be used.'); ?></p></td>
				</tr>
				<tr class="reserve">
					<th><?php self::_e( 'Send Reserve Confirmation' ); ?></th>
					<td><?php echo self::dropdown_yesno( "reserve_confirmation", $settings['reserve_confirmation'] ); ?>
					<p class="description">Should a confirmation e-mail be sent to the submitter when a reserve form is submitted?</p></td>
				</tr>
				<tr class="reserve">
					<th><?php self::_e( 'Require Name' ); ?></th>
					<td><?php echo self::dropdown_required( "reserve_require_name", $settings['reserve_require_name'] ); ?></td>
				</tr>
				<tr class="reserve">
					<th><?php self::_e( 'Require Address' ); ?></th>
					<td><?php echo self::dropdown_required( "reserve_require_address", $settings['reserve_require_address'] ); ?></td>
				</tr>
				<tr class="reserve">
					<th><?php self::_e( 'Require City' ); ?></th>
					<td><?php echo self::dropdown_required( "reserve_require_city", $settings['reserve_require_city'] ); ?></td>
				</tr>
				<tr class="reserve">
					<th><?php self::_e( 'Require State' ); ?></th>
					<td><?php echo self::dropdown_required( "reserve_require_state", $settings['reserve_require_state'] ); ?></td>
				</tr>
				<tr class="reserve">
					<th><?php self::_e( 'Require Zip' ); ?></th>
					<td><?php echo self::dropdown_required( "reserve_require_zip", $settings['reserve_require_zip'] ); ?></td>
				</tr>
				<tr class="reserve">
					<th><?php self::_e( 'Require Phone' ); ?></th>
					<td><?php echo self::dropdown_required( "reserve_require_phone", $settings['reserve_require_phone'] ); ?></td>
				</tr>
				<tr class="reserve">
					<th><?php self::_e( 'Require Email' ); ?></th>
					<td><?php echo self::dropdown_required( "reserve_require_email", $settings['reserve_require_email'] ); ?></td>
				</tr>
				<tr class="reserve">
					<th><?php self::_e( 'Require Message' ); ?></th>
					<td><?php echo self::dropdown_required( "reserve_require_message", $settings['reserve_require_message'] ); ?></td>
				</tr>
			</table>
			<?php do_action( 'ncm_edit_settings' ); ?>
			<table class="form-table">
				<?php // TODO: Move this to a tools subsection ?>
				<tr>
					<th colspan="2"><h3><?php self::_e( 'Image Tools' ); ?></h3></th>
				</tr>
				<tr>
					<th><?php self::_e( 'Placeholder Image' ); ?></th>
					<td>
						<div data-type="image" class="media-container">
							<?php
							$placeholder = netracustomer_get_placeholder_image( 'all' );
							self::item_image_field( 0, $placeholder, 'placeholder_image' ); ?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php self::_e( 'Rebuild Image Thumbnails' ); ?></th>
					<td><input type="checkbox" name="rebuild_thumbnails"/></td>
				</tr>
			</table>
			<input type="hidden" name="action" value="save"/>

			<p class="submit">
				<a href="<?php echo self::$self_url; ?>" class="button"><?php self::_e( 'Cancel' ); ?></a>
				<input type="submit" class="button-primary" name="save" value="<?php self::_e( 'Save Settings' ); ?>"/>
				<a class="button"
				   href="<?php echo self::$self_url; ?>&action=default"><?php self::_e( 'Reset to Defaults' ); ?></a>
			</p>
		</form>
		<script>
			jQuery(function ($) {
				$('.inventory-config').on('change', 'select[name="seo_urls"]', function () {
					if ($(this).val() == "1") {
						$('tr.seo_urls').fadeIn();
					} else {
						$('tr.seo_urls').fadeOut();
					}
				});

				$('.inventory-config').on('change', 'select[name="reserve_allow"]', function () {
					if ($(this).val() == "1") {
						$('tr.reserve').fadeIn();
					} else {
						$('tr.reserve').fadeOut();
					}
				});

				$('select[name="seo_urls"], select[name="reserve_allow"]').trigger('change');
				<?php do_action('ncm_edit_settings_script'); ?>
			});
		</script>
	<?php
	}

	private static function save_settings() {
		$settings = self::getOptions();

		$placeholder_image = self::request( 'placeholder_image' );
		if ( $placeholder_image ) {
			$item = new NCMItem();
			// Images can be id's as well...
			if ( ! is_numeric( $placeholder_image ) ) {
				// Get the attachment id
				$post_id = $item->get_attachment_id_from_url( $placeholder_image );
			} else {
				$post_id           = (int) $placeholder_image;
				$placeholder_image = wp_get_attachment_url( $post_id );
			}

			// Now - get large size, medium, plus thumbnail
			$sizes         = $item->get_image_urls( $post_id );
			$sizes['full'] = $placeholder_image;

			$_POST['placeholder_image'] = json_encode( $sizes );
		} else {
			$_POST['placeholder_image'] = '';
		}

		foreach ( $settings AS $field => $value ) {
			if ( isset( $_POST[ $field ] ) ) {
				self::updateOption( $field, $_POST[ $field ] );
			}
		}

		self::update_reg_key( $_POST );

		if ( self::request( "rebuild_thumbnails" ) ) {
			self::rebuild_thumbnails();
		}

		do_action( 'ncm_save_settings' );

		return TRUE;
	}

	private static function update_reg_key( $settings ) {

		$all_reg_info = self::get_reg_info();

		$add_ons = self::get_add_ons(TRUE);
		$add_ons['core'] = FALSE;

		foreach($add_ons AS $add_on => $data) {
			$field_key = ($add_on == 'core') ? 'license_key' : 'license_key_' . $add_on;
			$reg_info = (isset($all_reg_info[$add_on])) ? $all_reg_info[$add_on] : FALSE;
			$reg_key = (isset($settings[$field_key])) ? $settings[$field_key] : '';
			if ( ! $reg_key || ( $reg_key && ! empty($reg_info['key']) && $reg_key == $reg_info['key'] ) ) {
				// Do nothing if they didn't enter a key, or it's the same key as already saved.
			} else {
				NCMAPI::activate( $data, $reg_key );
			}
		}
	}

	public static function grid_columns( $columns, $self, $default = 'name', $action = FALSE ) {
		if ( ! self::$sortby ) {
			self::$sortby = $default;
		}
		$content = '<tr class="title">';
		foreach ( $columns as $sortfield => $column ) {
			$class   = ( isset( $column['class'] ) ) ? $column['class'] : '';
			$sortdir = ( $sortfield == self::$sortby && strtolower( self::$sortdir ) == 'asc' ) ? 'DESC' : 'ASC';
			$content .= '<th class="' . $class . '">';
			if ( is_numeric( $sortfield ) ) {
				$sortfield = '';
			}
			if ( $sortfield ) {
				$content .= '<a href="' . $self . '&sortby=' . $sortfield . '&sortdir=' . $sortdir . '">';
			}
			$content .= $column['title'];
			if ( $sortfield ) {
				$content .= '</a>';
			}

			// TODO: Get sort images!
			if ( self::$sortby == $sortfield ) {
				$alt = ( self::$sortdir == 'ASC' ) ? '&uarr;' : '&darr;';
				// $content.= '<img src="' . $this->url . '/images/sort_' . strtolower(self::$sortdir) . '.gif" alt="' . $alt . '" />';
				$content .= '<strong>' . $alt . '</strong>';
			}
		}
		$content .= ( $action == NULL ) ? '<th class="actions">' . self::__( 'Actions' ) . '</th>' : '';
		$content .= '</tr>';

		$content = apply_filters('ncm_grid_columns', $content, $columns, $self);

		return $content;
	}

	private static function get_item( $inventory_id ) {
		return self::$item->get( $inventory_id );
	}
	private static function get_invoiceItem() {
		return self::$item->getInvoice();
	}        
	private static function get_productData( $iv_no ) {
		return self::$item->get_pdata( $iv_no );
	}

	private static function get_item_fields( $args = NULL ) {
		return self::$item->get_fields();
	}

	private static function get_item_images( $inventory_id ) {
		return self::$item->get_images( $inventory_id );
	}

	private static function get_item_media( $inventory_id ) {
		return self::$item->get_media( $inventory_id );
	}

	private static function get_categories( $args = NULL ) {
		return self::$category->get_all( $args );
	}

	private static function get_category( $category_id = NULL ) {
		if ( ! $category_id ) {
			$category_id = self::$config->query_vars["category_id"];
		}

		return self::$category->get( $category_id );
	}

	private static function rebuild_thumbnails() {
		self::$item->rebuild_image_thumbs();
	}

	public static function manage_add_ons() {

		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		self::admin_heading( self::__( 'NETRA Customer Add Ons' ) );

		$force = (isset($_GET['force_update'])) ? TRUE : FALSE;

		$add_ons = self::get_add_ons(FALSE, $force);
		if ( $add_ons ) {
			foreach ( $add_ons AS $add_on ) {
				$installed       = ( ! empty( $add_on->installed ) ) ? '<span>' . self::__( 'Installed' ) . '</span>' : FALSE;
				$installed_class = ( $installed ) ? ' add_on_installed' : '';
				echo '<div class="add_on' . $installed_class . '">';
				echo '<h3>' . $add_on->title . $installed . '</h3>';
				if ( ! empty( $add_on->image ) ) {
					echo '<div class="image"><img src="' . $add_on->image . '"></div>';
				}
				echo '<div class="description">' . $add_on->description . '</div>';
				echo '<p class="learn_more">';
				if ( ! empty( $add_on->learn_more_url ) ) {
					echo '<a href="' . $add_on->learn_more_url . '">' . self::__( 'Learn More' ) . '</a></p>';
				}
				if ( ! empty( $add_on->download_url ) ) {
					echo '<a class="download" href="' . $add_on->download_url . '">' . self::__( 'Download' ) . '</a>';
				}
				echo '</p>';
				echo '</div>';
			}
		} else {
			echo '<p>' . self::__( 'No add ons found.  Please check back soon!' ) . '</p>';
		}

//		echo '<p>' . self::__( 'Is your add-on not appearing in the menu? Click the button below to force the system to check for license changes.' ) . '</p>';
//		echo '<p><a class="button" href="' . self::$self_url . '&force_update=true">' . self::__( 'Update License Info' ) . '</a></p>';
		self::admin_footer();
	}

	private static function admin_heading( $subtitle ) {
		echo '<div class="wrap inventorywrap">' . PHP_EOL;
		echo '<h2>Netra Products Management<span class="version">Netra Products Management ' . self::__( 'Version' ) . ' ' . self::VERSION . '</span></h2>' . PHP_EOL;
		echo '<h3>' . $subtitle . '</h3>' . PHP_EOL;
		echo self::output_errors();
		echo self::output_messages();
		//echo self::donate_button();
	}

	private static function admin_footer() {
		echo '</div>' . PHP_EOL;
	}
}
