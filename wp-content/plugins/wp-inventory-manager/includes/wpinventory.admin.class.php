
<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* TODO:
	[ ] For images / media, save with error loses sort.  Fix.
*/

final class WPIMAdmin extends WPIMCore {
      



    private static $instance;

	/**
	 * Local instance of the item class
	 * @var WPIMItem class
	 */
	private static $item;

	/**
	 * Local instance of the item class
	 * @var WPIMCategory class
	 */
	private static $category;

	/**
	 * Constructor magic method.
	 * Private because this class should not be called on its own.
	 */
	public function __construct() {
		self::stripslashes();
		self::$self_url = 'admin.php?page=orders';
		self::$item     = new WPIMItem();
		self::$category = new WPIMCategory();
		self::$label    = new WPIMLabel();
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
		echo '<li>' . self::__( 'To add a Product Click -> ' ) . '<a href="admin.php?page=manage_inventory_items&action=add">' . self::__( 'Add Product' ) . '</a></li>' . PHP_EOL;
                echo '<li>' . self::__( 'Edit Products Click -> ' ) . '<a href="admin.php?page=manage_inventory_items">' . self::__( 'Edit Customer Records' ) . '</a></li>' . PHP_EOL;
                echo '<li>' . self::__( 'Reports -> ' ) . '<a href="admin.php?page=manage_inventory_reports">' . self::__( 'Reports' ) . '</a></li>' . PHP_EOL;                
                echo '<li>' . self::__( 'Check Products Click -> ' ) . '<a href="http://webxarc.in/netra/npm/">' . self::__( 'Check Customer Records' ) . '</a></li>' . PHP_EOL;
                echo '<li>' . self::__( 'For Analysis Click -> ' ) . '<a href="http://webxarc.in/netra/nbm/?tr=1">' . self::__( 'Data Analysis' ) . '</a></li>' . PHP_EOL;
		echo '</ol>';
		self::admin_footer();
	}

	public static function manage_inventory_items() {

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
			wp_enqueue_style('wpinventory', self::$url . '/css/style-admin.css');
		}

		// Do our display here
		self::admin_heading( self::__( 'Manage Orders' ) );

		if ( $action == 'edit' || $action == 'add' ) {
			self::edit_item( $inventory_id );
		}

		if ( ! $action ) {
			self::list_items();
		}

		self::admin_footer();
	}
      
	//trying something here
	public static function orders() {
	
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
		self::admin_heading( self::__( 'Manage Orders' ) );
	
		if ( $action == 'edit' || $action == 'add' ) {
			self::edit_item( $inventory_id );
		}
	
		if ( ! $action ) {
			self::list_items();
		}
	
		self::admin_footer();
	}
	
	
	public static function manage_inventory_reports() {

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
		self::admin_heading( self::__( 'Manage Inventory Items' ) );

		if ( $action == 'edit' || $action == 'add' ) {
			self::edit_item( $inventory_id );
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
		$inventory_display = wpinventory_get_display_settings( 'admin' );

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

		echo wpinventory_filter_form_admin();

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

		$loop = new WPIMLoop( $args );

		global $wpinventory_item;

		?>
		<?php if ( self::check_permission( 'add_item', FALSE ) ) { ?>
			<a class="button button-primary"
			   href="<?php echo self::$self_url; ?>&action=add"><?php self::_e( 'New Order' ); ?></a>
		<?php } ?>
		<table class="grid itemgrid">
			<?php echo self::grid_columns( $columns, self::$self_url, 'inventory_number' );
			while ( $loop->have_items() ) {
				$loop->the_item();
				$edit_url   = ( self::check_permission( 'view_item', $wpinventory_item->inventory_id ) ) ? self::$self_url . '&action=edit&inventory_id=' . $wpinventory_item->inventory_id : '';
				$delete_url = ( self::check_permission( 'edit_item', $wpinventory_item->inventory_id ) ) ? self::$self_url . '&action=delete&delete_id=' . $wpinventory_item->inventory_id : '';
                                $print_url =  admin_url(). 'admin.php?page=manage_bill&inventory_id=' . $wpinventory_item->inventory_id;
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
							$url = get_edit_user_link( $wpinventory_item->{$field} );
						}
                                                if($field=='plstats'){}else{
						echo '<td class="' . $field . '"><a href="' . $url . '">' .  $loop->get_field( $field ) . '</a></td>';
                                                }
					}
					?>
					<td class="action">
						<?php if ( $edit_url ) { ?>
							<a href="<?php echo $edit_url; ?>"><?php self::_e( 'edit' ); ?></a>
						<?php }
						if ( $delete_url ) { ?>
							<a class="delete" data-name="<?php echo $wpinventory_item->inventory_name; ?>"
							   href="<?php echo $delete_url; ?>"><?php self::_e( 'delete' ); ?></a>
						<?php } 
                                                if ( $print_url ) { ?>
							<a class="print" data-name="<?php echo $wpinventory_item->inventory_name; ?>"
                                                           href="<?php echo $print_url; ?>" target="_blank" ><?php self::_e( 'print' ); ?></a>
						<?php } ?>
						<?php do_action( 'wpim_admin_action_links', $wpinventory_item->inventory_id ); ?>
					</td>
				</tr>
			<?php } ?>
		</table>

		<?php
		echo wpinventory_pagination( self::$self_url, $loop->get_pages() );
		do_action( 'wpim_admin_items_listing', $loop->get_query_args() );
                

	}
        
	public static function list_reports_items() {
		$inventory_display = wpinventory_get_display_settings( 'admin' );
                 $total=0;$totalProfit=0;$totalLoss=0;
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

		echo wpinventory_filter_form_reports_admin();

		$args = '';

		$filters = array(
			"inventory_search"      => "search",
                        "inventory_search1"      => "search1",
                        "inventory_search2"      => "search2",
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

		$loop = new WPIMLoop( $args );

		global $wpinventory_item;

		?>
		<?php //if ( self::check_permission( 'add_item', FALSE ) ) { ?>
			<!-- <a class="button button-primary"
			   href="<?php //echo self::$self_url; ?>&action=add"><?php //self::_e( 'Add Inventory Item' ); ?></a>-->
		<?php //} ?>
		<table class="grid itemgrid">
			<?php echo self::grid_reports_columns( $columns, self::$self_url, 'inventory_number' );
			while ( $loop->have_items() ) {
                              
				$loop->the_item();
				$edit_url   = ( self::check_permission( 'view_item', $wpinventory_item->inventory_id ) ) ? self::$self_url . '&action=edit&inventory_id=' . $wpinventory_item->inventory_id : '';
				$delete_url = ( self::check_permission( 'edit_item', $wpinventory_item->inventory_id ) ) ? self::$self_url . '&action=delete&delete_id=' . $wpinventory_item->inventory_id : '';
                                $print_url =  admin_url(). 'admin.php?page=manage_bill&inventory_id=' . $wpinventory_item->inventory_id;
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
							$url = get_edit_user_link( $wpinventory_item->{$field} );
						}
						echo '<td class="' . $field . '"><a href="' . $url . '">' .  $loop->get_field( $field ) . '</a></td>';
					}
					?>
					<!--<td class="action">
						<?php //if ( $edit_url ) { ?>
							<a href="<?php //echo $edit_url; ?>"><?php //self::_e( 'edit' ); ?></a>
						<?php// }
					//	if ( //$delete_url ) { ?>
							<a class="delete" data-name="<?php //echo $wpinventory_item->inventory_name; ?>"
							   href="<?php //echo $delete_url; ?>"><?php //self::_e( 'delete' ); ?></a>
						<?php //} 
                                                //if ( $print_url ) { ?>
							<a class="print" onclick="printDiv('printableArea')" data-name="<?php //echo $wpinventory_item->inventory_name; ?>"
                                                           href="<?php //echo $print_url; ?>" target="_blank" ><?php //self::_e( 'print' ); ?></a>
						<?php //} ?>
						<?php //do_action( 'wpim_admin_action_links', $wpinventory_item->inventory_id ); ?>
					</td>-->
				</tr>
                                
                            <?php   
                                       $total= $total + $loop->get_field("plstats");
                                       
                                       if($loop->get_field("plstats")<0){ $totalLoss=$totalLoss + $loop->get_field("plstats"); }
                                       if($loop->get_field("plstats")>0){ $totalProfit=$totalProfit + $loop->get_field("plstats"); }
                             ?>
			<?php } ?>
                               <?php if($total<0){ ?><div style="color: #CC0000; font-size: 1.3em; font-weight: bold; margin: 1em 0;"> <h3>Net Loss = Rs: <?php echo $total*(-1); ?></h3> <?php } ?>
                               <?php if($total>0){ ?><div style="color: #222; font-size: 1.3em; font-weight: bold; margin: 1em 0;"> Net Profit = Rs: <?php echo $total; ?> <?php } ?>
                               <?php if($total==0){ ?><div style="color: #00ff00; font-size: 1.3em; font-weight: bold; margin: 1em 0;">You got No Profit and No Loss</h3> <?php } ?>
                               <k4 style="color: #CC0000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total Loss = Rs: <?php echo $totalLoss; ?></k4>
                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total Profit = Rs: <?php echo $totalProfit; ?></div>
		</table>

		<?php
		echo wpinventory_pagination( self::$self_url, $loop->get_pages() );
		do_action( 'wpim_admin_items_listing', $loop->get_query_args() );
                
                $content .= ( $action == NULL ) ? '<th class="actions">' . self::__( 'P/L Stats' ) . '</th>' : '';
		$content .= '</tr>'; 
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
                //$f_model = array();
               // $f_color = array();

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
			$item = self::get_item( $inventory_id );
			extract( (array) $item );
           //my code      
                        
                      $output = "<script>console.log( 'Order ID: " . $order_no . "' );</script>";
                        echo $output;
                        
                        
                        $fitem = self::get_fitem( $order_no);
                        $i=0;
                        foreach ($fitem as $item){
                            $f_brand[$i]=$item->f_brand;
                            $f_model[$i]=$item->f_model;$f_size[$i]=$item->f_size;
                            $f_color[$i]=$item->f_color;$f_sp[$i]=$item->f_sp;$f_rate[$i]=$item->f_rate;
                            
                        $output = "<script>console.log( 'inside For brand=: " .$f_brand[$i] . "' );</script>";
                        //echo $output;
                        $i++;
                        }
                        
                        
                        $litem = self::get_litem( $order_no);
                        $i=0;
                        foreach ($litem as $item){
                            $r[$i]=$item->r;$r_size[$i]=$item->r_size;$r_tint[$i]=$item->r_tint;
                            $l[$i]=$item->l;$l_size[$i]=$item->l_size;$l_tint[$i]=$item->l_tint;
                            $spec[$i]=$item->spec;$note[$i]=$item->note;$l_sp[$i]=$item->l_sp; 
                            $l_brand[$i]=$item->l_brand;
                            $l_model[$i]=$item->l_model;$l_rate[$i]=$item->l_rate;
                            $i++;
                        }       
                                $f_brand0=$f_brand[0];
                        	$f_model0=$f_model[0];
				$f_color0=$f_color[0];                            
				$f_size0=$f_size[0];
				$f_sp0=$f_sp[0];
                                $f_rate0=$f_rate[0];
                               // var_dump($f_color);
                                
                                $f_brand1=$f_brand[1];
				$f_model1=$f_model[1];
				$f_color1=$f_color[1];                            
				$f_size1=$f_size[1];
				$f_sp1=$f_sp[1];
                                $f_rate1=$f_rate[1];
                                
                                $f_brand2=$f_brand[2];
				$f_model2=$f_model[2];
				$f_color2=$f_color[2];                            
				$f_size2=$f_size[2];
				$f_sp2=$f_sp[2];
                                $f_rate2=$f_rate[2];
                                
                                $f_brand3=$f_brand[3];
				$f_model3=$f_model[3];
				$f_color3=$f_color[3];                            
				$f_size3=$f_size[3];
				$f_sp3=$f_sp[3];
                                $f_rate3=$f_rate[3];
                                
                                $f_brand4=$f_brand[4];
				$f_model4=$f_model[4];
				$f_color4=$f_color[4];                            
				$f_size4=$f_size[4];
				$f_sp4=$f_sp[4];
                                $f_rate4=$f_rate[4];
                                
                                $r0=$r[0];
				$r_size0=$r_size[0];
				$r_tint0=$r_tint[0];
				$l0=$l[0];
				$l_size0=$l_size[0];
				$l_tint0=$l_tint[0];
                                $l_sp0=$l_sp[0];
				$spec0=$spec[0];
				$note0=$note[0];
                                $l_brand0=$l_brand[0];
                                $l_model0=$l_model[0];
                                $l_rate0=$l_rate[0];                                 
                                
                                $output = "<script>console.log( 'inside For l_sp0=: " .$l_sp[0] . "' );</script>";
                                echo $output;
                                
				$r1=$r[1];
				$r_size1=$r_size[1];
				$r_tint1=$r_tint[1];
				$l1=$l[1];
				$l_size1=$l_size[1];
				$l_tint1=$l_tint[1];
                                $l_sp1=$l_sp[1];
				$spec1=$spec[1];
				$note1=$note[1];
                                $l_brand1=$l_brand[1];
                                $l_model1=$l_model[1];
                                $l_rate1=$l_rate[1];                                
                                
				$r2=$r[2];
				$r_size2=$r_size[2];
				$r_tint2=$r_tint[2];
				$l2=$l[2];
				$l_size2=$l_size[2];
				$l_tint2=$l_tint[2];
                                $l_sp2=$l_sp[2];
				$spec2=$spec[2];
				$note2=$note[2];
                                $l_brand2=$l_brand[2];
                                $l_model2=$l_model[2];
                                $l_rate2=$l_rate[2];                                 
                                
				$r3=$r[3];
				$r_size3=$r_size[3];
				$r_tint3=$r_tint[3];
				$l3=$l[3];
				$l_size3=$l_size[3];
				$l_tint3=$l_tint[3];
                                $l_sp3=$l_sp[3];
				$spec3=$spec[3];
				$note3=$note[3];
                                $l_brand3=$l_brand[3];
                                $l_model3=$l_model[3];
                                $l_rate3=$l_rate[3];                                 
                                
				$r4=$r[4];
				$r_size4=$r_size[4];
				$r_tint4=$r_tint[4];
				$l4=$l[4];
				$l_size4=$l_size[4];
				$l_tint4=$l_tint[4];
                                $l_sp4=$l_sp[4];
				$spec4=$spec[4];
				$note4=$note[4];
                                $l_brand4=$l_brand[4];
                                $l_model4=$l_model[4];
                                $l_rate4=$l_rate[4];                                 
                                

           //ends here             
			$image       = self::get_item_images( $inventory_id );
			$media       = self::get_item_media( $inventory_id );
			$media_title = array();
			foreach ( $media AS $i => $m ) {
				$media_title[ $i ] = $m->media_title;
			}
		}
                else{
                                     //getting the creating the current Order ID
                        $order_no=self::get_order();
                        $order_no=$order_no+1;
                   $output = "<script>console.log( 'Current Order ID: " . $order_no . "' );</script>";
                   echo $output;
                }

		// TODO: Status drop-down method
		?>
		<form method="post" action="<?php echo self::$self_url; ?>">
		
		<ul class="wp-inventory-tab">
			  <li><a href="#" class="tablinks" onclick="openTab(event, 'customerProfile'); return false;">Customer Profile</a></li>
			  <li><a href="#" class="tablinks" onclick="openTab(event, 'orderProfile'); return false;">Order Profile</a></li>
			  <li><a href="#" class="tablinks" onclick="openTab(event, 'accounts'); return false;">Accounts</a></li>
			  <li><a href="#" class="tablinks" onclick="openTab(event, 'printableArea'); return false;">Specifications</a></li>
			  <li><a href="#" class="tablinks" onclick="openTab(event, 'images-media'); return false;">Image or Media</a></li>
		</ul>
		
		<div class="accordion">Customer Profile</div>
		<section id="customerProfile" class="tabcontent" style="display: block;">
            <div id="table">
                <div class="row">
                     <span class="cell"><label for="order_no"><?php self::label( 'order_no' ); ?></label></span>
                     <span class="cell1"><input name="order_no" class="regular-text order_no"
					           value="<?php echo esc_attr( $order_no ); ?>" readonly /></span>                   
                    <span class="cell"><label for="d_date"><?php self::label( 'd_date' ); ?></label></span>
                    <span class="cell1"><input name="d_date" class="MyDate d_date" 
					           value="<?php echo esc_attr( $d_date ); ?>"/></span>                    
                </div>
                <!--<div class="row">
					<span class="cell"><label for="category_id"><?php //self::label( 'category_id' ); ?></label></span>
					<span class="cell1"><?php //echo self::$category->dropdown( 'category_id', $category_id ); ?></span>
		</div>-->
                <div class="row">
                    <span class="cell"><label for="c_no"><?php self::label( 'c_no' ); ?></label></span>
                    <span class="cell1"><input name="c_no" class="regular-text c_no onfocusCS" id="c_no" 
					           value="<?php echo esc_attr( $c_no ); ?>"/></span>
                    <span class="cell"><label for="date"><?php self::label( 'date' ); ?></label></span>
                    <span class="cell1"><input name="date" class="MyDate date"  
					           value="<?php echo esc_attr( $date ); ?>"/></span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="c_fname"><?php self::label( 'c_fname' ); ?></label></span>
                    <span class="cell1"><input name="c_fname" class="regular-text c_fname  onfocusCS" id="c_fname" 
					           value="<?php echo esc_attr( $c_fname ); ?>"/></span>
                    <span class="cell"><label for="c_lname"><?php self::label( 'c_lname' ); ?></label></span>
                    <span class="cell1"><input name="c_lname" class="regular-text c_lname onfocusCS" id="c_lname" 
					           value="<?php echo esc_attr( $c_lname ); ?>"/></span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="c_gender"><?php self::label( 'c_gender' ); ?></label></span>
		        <span class="cell1"><select name="c_gender">
                                <option <?php if(esc_attr( $c_gender )=="Male"){echo 'selected'; } ?> value="Male">Male</option>
                                <option <?php if(esc_attr( $c_gender )=="Female"){echo 'selected'; } ?>  value="Female">Female</option>
                                <option <?php if(esc_attr( $c_gender )=="Others"){echo 'selected'; } ?> value="Others">Others</option>
                            </select>
                        </span>                    
                   <!-- <span class="cell1"><input name="c_gender" class="regular-text"
					           value="<?php// echo esc_attr( $c_gender ); ?>"/></span> -->
                </div>

                <div class="row">
                    <span class="cell"><label for="c_add"><?php self::label( 'c_add' ); ?></label></span>
                    <span class="cell1"><input name="c_add" class="regular-text c_add" id="c_add"
					           value="<?php echo esc_attr( $c_add ); ?>"/></span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="c_city"><?php self::label( 'c_city' ); ?></label></span>
                    <span class="cell1"><input name="c_city" class="regular-text c_city onfocusCS"  id="c_city" 
					           value="<?php echo esc_attr( $c_city ); ?>"/></span>
                    <span class="cell"><label for="c_city_pin"><?php self::label( 'c_city_pin' ); ?></label></span>
                    <span class="cell1"><input name="c_city_pin" class="regular-text c_city_pin onfocusCS" id="c_city_pin"
					           value="<?php echo esc_attr( $c_city_pin ); ?>"/></span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="c_email"><?php self::label( 'c_email' ); ?></label></span>
                    <span class="cell1"><input name="c_email" class="regular-text c_email onfocusCS" id="c_email" 
					           value="<?php echo esc_attr( $c_email ); ?>"/></span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="c_birth"><?php self::label( 'c_birth' ); ?></label></span>
                    <span class="cell1"><input name="c_birth" class="MyDate c_birth" 
					           value="<?php echo esc_attr( $c_birth ); ?>"/></span>
                    <span class="cell"><label for="c_anni"><?php self::label( 'c_anni' ); ?></label></span>
                    <span class="cell1"><input name="c_anni" class="MyDate c_anni onfocusCS" 
					           value="<?php echo esc_attr( $c_anni ); ?>"/></span>
                </div>                
                
            </div> 
        </section>
    
    <div class="accordion">Order Profile</div>
    <section id="orderProfile" class="tabcontent">
            <div class="no-of-frame">No of Frame:-<input id="nofFrame" class="small-text" oninput="myFunctionFrame()"></div>
			
            <div class="table frame_t1" style="clear:left;">
                <div class="caption">Frame1</div>
                <div class="row">
                    <span class="cell"><label for="f_brand0"><?php self::label( 'f_brand0' ); ?></label></span>
                    <span class="cell1"><div class="ui-widget"><input name="f_brand0" class="regular-text onfocus" id="f_brand0"
					           value="<?php echo esc_attr( $f_brand0 ); ?>"/></div></span>
                    <span class="cell"><label for="f_model0"><?php self::label( 'f_model0' ); ?></label></span>
                    <span class="cell1"><div class="ui-widget"><input name="f_model0" class="regular-text onfocus" id="skills" 
                                                      value="<?php echo esc_attr( $f_model0 ); ?>"/></div></span>
                     <span class="cell"><label for="f_rate0">Rate</label></span>
                     <span class="cell1"><div class="rate f_rate0"><select name="f_rate0" class="small-text onfocus" id="vrate" />
                             <option  value="<?php echo $f_rate0;  ?>"><?php echo $f_rate0 ?></option>
                             </select></div></span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="f_size0"><?php self::label( 'f_size0' ); ?></label></span>
                    <span class="cell1"><input name="f_size0" class="regular-text"
					           value="<?php echo esc_attr( $f_size0 ); ?>"/></span>
                    <span class="cell"><label for="f_color0"><?php self::label( 'f_color0' ); ?></label></span>
                    <span class="cell1"><input name="f_color0" class="regular-text"
					           value="<?php echo esc_attr( $f_color0 ); ?>"/></span>
                </div>
            </div>
        

        <div class="table frame_t2">
                <div class="caption">Frame2</div>
                <div class="row">
                    <span class="cell"><label for="f_brand1">Company</label></span>
                    <span class="cell1"><div class="ui-widget"><input name="f_brand1" class="regular-text" id="f_brand1"
					           value="<?php echo esc_attr( $f_brand1 ); ?>"/></div></span>
                    <span class="cell"><label for="f_model1"><?php self::label( 'f_model1' ); ?></label></span>
                    <span class="cell1"><input name="f_model1" class="regular-text" id="frame2_model" oninput="myFunction()" 
					           value="<?php echo esc_attr( $f_model1 ); ?>"/></span>    
					
					<span class="cell"><label for="f_rate1">Rate</label></span>
                    <span class="cell1"><div class="rate f_rate1"><select name="f_rate1" class="small-text onfocus" id="vrate1" />
                             <option  value="<?php echo $f_rate1;  ?>"><?php echo $f_rate1 ?></option>
                             </select></div>
					</span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="f_size1"><?php self::label( 'f_size1' ); ?></label></span>
                    <span class="cell1"><input name="f_size1" class="regular-text"
					           value="<?php echo esc_attr( $f_size1 ); ?>"/></span>
                    <span class="cell"><label for="f_color1"><?php self::label( 'f_color1' ); ?></label></span>
                    <span class="cell1"><input name="f_color1" class="regular-text"
					           value="<?php echo esc_attr( $f_color1 ); ?>"/></span>
                </div>
            </div>
        

        <div class="table frame_t3">
                <div class="caption">Frame3</div>
                <div class="row">
                    <span class="cell"><label for="f_brand2">Company</label></span>
                    <span class="cell1"><div class="ui-widget"><input name="f_brand2" class="regular-text" id="f_brand2"
					           value="<?php echo esc_attr( $f_brand2); ?>"/></div></span>
                    <span class="cell"><label for="f_model2"><?php self::label( 'f_model2' ); ?></label></span>
                    <span class="cell1"><input name="f_model2" class="regular-text" id="frame3_model" oninput="myFunction()" 
					           value="<?php echo esc_attr( $f_model2 ); ?>"/></span>       

					<span class="cell"><label for="f_rate2">Rate</label></span>
                    <span class="cell1"><div class="rate f_rate2"><select name="f_rate2" class="small-text onfocus" id="vrate2" />
                             <option  value="<?php echo $f_rate2;  ?>"><?php echo $f_rate2 ?></option>
                             </select></div>
					</span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="f_size2"><?php self::label( 'f_size2' ); ?></label></span>
                    <span class="cell1"><input name="f_size2" class="regular-text"
					           value="<?php echo esc_attr( $f_size2 ); ?>"/></span>
                    <span class="cell"><label for="f_color2"><?php self::label( 'f_color2' ); ?></label></span>
                    <span class="cell1"><input name="f_color2" class="regular-text"
					           value="<?php echo esc_attr( $f_color2 ); ?>"/></span>
                </div>
            </div>
        

        <div class="table frame_t4">
                <div class="caption">Frame4</div>
                <div class="row">
                    <span class="cell"><label for="f_brand3">Company</label></span>
                    <span class="cell1"><div class="ui-widget"><input name="f_brand3" class="regular-text" id="f_brand3"
					           value="<?php echo esc_attr( $f_brand3 ); ?>"/></div></span>
                    <span class="cell"><label for="f_model3"><?php self::label( 'f_model3' ); ?></label></span>
                    <span class="cell1"><input name="f_model3" class="regular-text" id="frame4_model" oninput="myFunction()" 
					           value="<?php echo esc_attr( $f_model3 ); ?>"/></span>            

					<span class="cell"><label for="f_rate3">Rate</label></span>
                    <span class="cell1"><div class="rate f_rate3"><select name="f_rate3" class="small-text onfocus" id="vrate3" />
                             <option  value="<?php echo $f_rate3;  ?>"><?php echo $f_rate3 ?></option>
                             </select></div>
					</span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="f_size3"><?php self::label( 'f_size3' ); ?></label></span>
                    <span class="cell1"><input name="f_size3" class="regular-text"
					           value="<?php echo esc_attr( $f_size3 ); ?>"/></span>
                    <span class="cell"><label for="f_color3"><?php self::label( 'f_color3' ); ?></label></span>
                    <span class="cell1"><input name="f_color3" class="regular-text"
					           value="<?php echo esc_attr( $f_color3 ); ?>"/></span>
                </div>
            </div>
        

        <div class="table frame_t5">
                <div class="caption">Frame5</div>
                <div class="row">
                    <span class="cell"><label for="f_brand4">Company</label></span>
                    <span class="cell1"><div class="ui-widget"><input name="f_brand4" class="regular-text" id="f_brand4"
					           value="<?php echo esc_attr( $f_brand4 ); ?>"/></div></span>
                    <span class="cell"><label for="f_model4"><?php self::label( 'f_model4' ); ?></label></span>
                    <span class="cell1"><input name="f_model4" class="regular-text" id="frame5_model" oninput="myFunction()" 
					           value="<?php echo esc_attr( $f_model4 ); ?>"/></span>   

				    <span class="cell"><label for="f_rate4">Rate</label></span>
                     <span class="cell1"><div class="rate f_rate4"><select name="f_rate4" class="small-text onfocus" id="vrate4" />
                             <option  value="<?php echo $f_rate4;  ?>"><?php echo $f_rate4 ?></option>
                             </select></div>
					</span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="f_size4"><?php self::label( 'f_size4' ); ?></label></span>
                    <span class="cell1"><input name="f_size4" class="regular-text"
					           value="<?php echo esc_attr( $f_size4 ); ?>"/></span>
                    <span class="cell"><label for="f_color4"><?php self::label( 'f_color4' ); ?></label></span>
                    <span class="cell1"><input name="f_color4" class="regular-text"
					           value="<?php echo esc_attr( $f_color4 ); ?>"/></span>
                </div>
            </div>
         
        
        <div class="table lens_t1">
                <div class="caption">Lens1</div>
                <div class="row">
                    <span class="cell"><label for="l_brand0">Company</label></span>
                    <span class="cell1"><div class="ui-widget"><input name="l_brand0" class="regular-text" id="l_brand0"
					           value="<?php echo esc_attr( $l_brand0 ); ?>"/></div></span>
                    <span class="cell"><label for="l_model0"><?php self::label( 'l_model0' ); ?></label></span>
                    <span class="cell1"><input name="l_model0" class="regular-text" id="lens0_model" oninput="myFunction()" 
					           value="<?php echo esc_attr( $l_model0 ); ?>"/></span>    
					
					<span class="cell"><label for="l_rate0">Rate</label></span>
                    <span class="cell1"><div class="rate l_rate0"><select name="l_rate0" class="small-text onfocus" id="vlrate0" />
                             <option  value="<?php echo $l_rate0;  ?>"><?php echo $l_rate0 ?></option>
                             </select></div>
					</span>
                </div>
                <div class="row">

                    <span class="cell"><label for="r0"><?php self::label( 'r0' ); ?></label></span>
                    <span class="cell1"><input name="r0" class="regular-text"
					           value="<?php echo esc_attr( $r0 ); ?>"/></span>

                    <span class="cell"><label for="r_size0"><?php self::label( 'r_size0' ); ?></label></span>
                    <span class="cell1"><input name="r_size0" class="regular-text"
					           value="<?php echo esc_attr( $r_size0 ); ?>"/></span>

                    <span class="cell"><label for="r_tint0"><?php self::label( 'r_tint0' ); ?></label></span>
                    <span class="cell1"><input name="r_tint0" class="regular-text"
					           value="<?php echo esc_attr( $r_tint0 ); ?>"/></span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="l0"><?php self::label( 'l0' ); ?></label></span>
                    <span class="cell1"><input name="l0" class="regular-text"
					           value="<?php echo esc_attr( $l0 ); ?>"/></span>

                    <span class="cell"><label for="l_size0"><?php self::label( 'l_size0' ); ?></label></span>
                    <span class="cell1"><input name="l_size0" class="regular-text"
					           value="<?php echo esc_attr( $l_size0 ); ?>"/></span>

                    <span class="cell"><label for="l_tint0"><?php self::label( 'l_tint0' ); ?></label></span>
                    <span class="cell1"><input name="l_tint0" class="regular-text"
					           value="<?php echo esc_attr( $l_tint0 ); ?>"/></span>
                </div>
                
                <div class="row">

                                        <span class="cell"><label for="spec0"><?php self::label( 'spec0' ); ?></label></span>
                    <span class="cell1"><input name="spec0" class="regular-text"
					           value="<?php echo esc_attr( $spec0 ); ?>"/></span>

                                        <span class="cell"><label for="note0"><?php self::label( 'note0' ); ?></label></span>
                    <span class="cell1"><input name="note0" class="regular-text"
					           value="<?php echo esc_attr( $note0 ); ?>"/></span>
                </div>
                
               
            </div>
        
        <div class="table lens_t2">
                <div class="caption">Lens2</div>
                <div class="row">
                    <span class="cell"><label for="l_brand1">Company</label></span>
                    <span class="cell1"><div class="ui-widget"><input name="l_brand1" class="regular-text" id="l_brand1"
					           value="<?php echo esc_attr( $l_brand1 ); ?>"/></div></span>
                    <span class="cell"><label for="l_model1"><?php self::label( 'l_model1' ); ?></label></span>
                    <span class="cell1"><input name="l_model1" class="regular-text" id="lens1_model" oninput="myFunction()" 
					           value="<?php echo esc_attr( $l_model1 ); ?>"/></span>    
					
					<span class="cell"><label for="l_rate1">Rate</label></span>
                    <span class="cell1"><div class="rate l_rate1"><select name="l_rate1" class="small-text onfocus" id="vlrate1" />
                             <option  value="<?php echo $l_rate1;  ?>"><?php echo $l_rate1 ?></option>
                             </select></div>
					</span>
                </div>               
                <div class="row">

                    <span class="cell"><label for="r1"><?php self::label( 'r1' ); ?></label></span>
                    <span class="cell1"><input name="r1" class="regular-text"
					           value="<?php echo esc_attr( $r1 ); ?>"/></span>

                    <span class="cell"><label for="r_size1"><?php self::label( 'r_size1' ); ?></label></span>
                    <span class="cell1"><input name="r_size1" class="regular-text"
					           value="<?php echo esc_attr( $r_size1 ); ?>"/></span>

                    <span class="cell"><label for="r_tint1"><?php self::label( 'r_tint1' ); ?></label></span>
                    <span class="cell1"><input name="r_tint1" class="regular-text"
					           value="<?php echo esc_attr( $r_tint1 ); ?>"/></span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="l1"><?php self::label( 'l1' ); ?></label></span>
                    <span class="cell1"><input name="l1" class="regular-text"
					           value="<?php echo esc_attr( $l1 ); ?>"/></span>

                    <span class="cell"><label for="l_size1"><?php self::label( 'l_size1' ); ?></label></span>
                    <span class="cell1"><input name="l_size1" class="regular-text"
					           value="<?php echo esc_attr( $l_size1 ); ?>"/></span>

                    <span class="cell"><label for="l_tint1"><?php self::label( 'l_tint1' ); ?></label></span>
                    <span class="cell1"><input name="l_tint1" class="regular-text"
					           value="<?php echo esc_attr( $l_tint1 ); ?>"/></span>
                </div>
                
                <div class="row">

                                        <span class="cell"><label for="spec"><?php self::label( 'spec1' ); ?></label></span>
                    <span class="cell1"><input name="spec1" class="regular-text"
					           value="<?php echo esc_attr( $spec1 ); ?>"/></span>

                                        <span class="cell"><label for="note"><?php self::label( 'note1' ); ?></label></span>
                    <span class="cell1"><input name="note1" class="regular-text"
					           value="<?php echo esc_attr( $note1 ); ?>"/></span>
                </div>
                
               
            </div>
        

        <div class="table lens_t3">
                <div class="caption">Lens3</div>
                <div class="row">
                    <span class="cell"><label for="l_brand2">Company</label></span>
                    <span class="cell1"><div class="ui-widget"><input name="l_brand2" class="regular-text" id="l_brand2"
					           value="<?php echo esc_attr( $l_brand2 ); ?>"/></div></span>
                    <span class="cell"><label for="l_model2"><?php self::label( 'l_model2' ); ?></label></span>
                    <span class="cell1"><input name="l_model2" class="regular-text" id="lens2_model" oninput="myFunction()" 
					           value="<?php echo esc_attr( $l_model2 ); ?>"/></span>    
					
					<span class="cell"><label for="l_rate2">Rate</label></span>
                    <span class="cell1"><div class="rate l_rate2"><select name="l_rate2" class="small-text onfocus" id="vlrate2" />
                             <option  value="<?php echo $l_rate2;  ?>"><?php echo $l_rate2 ?></option>
                             </select></div>
					</span>
                </div>               
                                <div class="row">

                    <span class="cell"><label for="r2"><?php self::label( 'r2' ); ?></label></span>
                    <span class="cell1"><input name="r2" class="regular-text"
					           value="<?php echo esc_attr( $r2 ); ?>"/></span>

                    <span class="cell"><label for="r_size2"><?php self::label( 'r_size2' ); ?></label></span>
                    <span class="cell1"><input name="r_size2" class="regular-text"
					           value="<?php echo esc_attr( $r_size2 ); ?>"/></span>

                    <span class="cell"><label for="r_tint2"><?php self::label( 'r_tint2' ); ?></label></span>
                    <span class="cell1"><input name="r_tint2" class="regular-text"
					           value="<?php echo esc_attr( $r_tint2 ); ?>"/></span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="l2"><?php self::label( 'l2' ); ?></label></span>
                    <span class="cell1"><input name="l2" class="regular-text"
					           value="<?php echo esc_attr( $l2 ); ?>"/></span>

                    <span class="cell"><label for="l_size2"><?php self::label( 'l_size2' ); ?></label></span>
                    <span class="cell1"><input name="l_size2" class="regular-text"
					           value="<?php echo esc_attr( $l_size2 ); ?>"/></span>

                    <span class="cell"><label for="l_tint2"><?php self::label( 'l_tint2' ); ?></label></span>
                    <span class="cell1"><input name="l_tint2" class="regular-text"
					           value="<?php echo esc_attr( $l_tint2 ); ?>"/></span>
                </div>
                
                <div class="row">

                                        <span class="cell"><label for="spec"><?php self::label( 'spec2' ); ?></label></span>
                    <span class="cell1"><input name="spec2" class="regular-text"
					           value="<?php echo esc_attr( $spec2 ); ?>"/></span>

                                        <span class="cell"><label for="note"><?php self::label( 'note2' ); ?></label></span>
                    <span class="cell1"><input name="note2" class="regular-text"
					           value="<?php echo esc_attr( $note2 ); ?>"/></span>
                </div>
                
               
            </div>
        

        <div class="table lens_t4">
                <div class="caption">Lens4</div>
                <div class="row">
                    <span class="cell"><label for="l_brand3">Company</label></span>
                    <span class="cell1"><div class="ui-widget"><input name="l_brand3" class="regular-text" id="l_brand3"
					           value="<?php echo esc_attr( $l_brand3 ); ?>"/></div></span>
                    <span class="cell"><label for="l_model3"><?php self::label( 'l_model3' ); ?></label></span>
                    <span class="cell1"><input name="l_model3" class="regular-text" id="lens3_model" oninput="myFunction()" 
					           value="<?php echo esc_attr( $l_model3 ); ?>"/></span>    
					
					<span class="cell"><label for="l_rate3">Rate</label></span>
                    <span class="cell1"><div class="rate l_rate3"><select name="l_rate3" class="small-text onfocus" id="vlrate3" />
                             <option  value="<?php echo $l_rate3;  ?>"><?php echo $l_rate3 ?></option>
                             </select></div>
					</span>
                </div>               
                                <div class="row">

                    <span class="cell"><label for="r3"><?php self::label( 'r3' ); ?></label></span>
                    <span class="cell1"><input name="r3" class="regular-text"
					           value="<?php echo esc_attr( $r3 ); ?>"/></span>

                    <span class="cell"><label for="r_size3"><?php self::label( 'r_size3' ); ?></label></span>
                    <span class="cell1"><input name="r_size3" class="regular-text"
					           value="<?php echo esc_attr( $r_size3 ); ?>"/></span>

                    <span class="cell"><label for="r_tint3"><?php self::label( 'r_tint3' ); ?></label></span>
                    <span class="cell1"><input name="r_tint3" class="regular-text"
					           value="<?php echo esc_attr( $r_tint3 ); ?>"/></span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="l3"><?php self::label( 'l3' ); ?></label></span>
                    <span class="cell1"><input name="l3" class="regular-text"
					           value="<?php echo esc_attr( $l3 ); ?>"/></span>

                    <span class="cell"><label for="l_size3"><?php self::label( 'l_size3' ); ?></label></span>
                    <span class="cell1"><input name="l_size3" class="regular-text"
					           value="<?php echo esc_attr( $l_size3 ); ?>"/></span>

                    <span class="cell"><label for="l_tint3"><?php self::label( 'l_tint3' ); ?></label></span>
                    <span class="cell1"><input name="l_tint3" class="regular-text"
					           value="<?php echo esc_attr( $l_tint3 ); ?>"/></span>
                </div>
                
                <div class="row">

                                        <span class="cell"><label for="spec"><?php self::label( 'spec3' ); ?></label></span>
                    <span class="cell1"><input name="spec3" class="regular-text"
					           value="<?php echo esc_attr( $spec3 ); ?>"/></span>

                                        <span class="cell"><label for="note"><?php self::label( 'note3' ); ?></label></span>
                    <span class="cell1"><input name="note3" class="regular-text"
					           value="<?php echo esc_attr( $note3 ); ?>"/></span>
                </div>
                
               
            </div>
        
 
        <div class="table lens_t5">
                <div class="caption">Lens5</div>
                <div class="row">
                    <span class="cell"><label for="l_brand4">Company</label></span>
                    <span class="cell1"><div class="ui-widget"><input name="l_brand4" class="regular-text" id="l_brand4"
					           value="<?php echo esc_attr( $l_brand4 ); ?>"/></div></span>
                    <span class="cell"><label for="l_model4"><?php self::label( 'l_model4' ); ?></label></span>
                    <span class="cell1"><input name="l_model4" class="regular-text" id="lens4_model" oninput="myFunction()" 
					           value="<?php echo esc_attr( $l_model4 ); ?>"/></span>    
					
					<span class="cell"><label for="l_rate4">Rate</label></span>
                    <span class="cell1"><div class="rate l_rate4"><select name="l_rate4" class="small-text onfocus" id="vlrate4" />
                             <option  value="<?php echo $l_rate4;  ?>"><?php echo $l_rate4 ?></option>
                             </select></div>
					</span>
                </div>               
                                <div class="row">

                    <span class="cell"><label for="r4"><?php self::label( 'r4' ); ?></label></span>
                    <span class="cell1"><input name="r4" class="regular-text"
					           value="<?php echo esc_attr( $r4 ); ?>"/></span>

                    <span class="cell"><label for="r_size4"><?php self::label( 'r_size4' ); ?></label></span>
                    <span class="cell1"><input name="r_size4" class="regular-text"
					           value="<?php echo esc_attr( $r_size4 ); ?>"/></span>

                    <span class="cell"><label for="r_tint4"><?php self::label( 'r_tint4' ); ?></label></span>
                    <span class="cell1"><input name="r_tint4" class="regular-text"
					           value="<?php echo esc_attr( $r_tint4 ); ?>"/></span>
                </div>
                
                <div class="row">
                    <span class="cell"><label for="l4"><?php self::label( 'l4' ); ?></label></span>
                    <span class="cell1"><input name="l4" class="regular-text"
					           value="<?php echo esc_attr( $l4 ); ?>"/></span>

                    <span class="cell"><label for="l_size4"><?php self::label( 'l_size4' ); ?></label></span>
                    <span class="cell1"><input name="l_size4" class="regular-text"
					           value="<?php echo esc_attr( $l_size4 ); ?>"/></span>

                    <span class="cell"><label for="l_tint4"><?php self::label( 'l_tint4' ); ?></label></span>
                    <span class="cell1"><input name="l_tint4" class="regular-text"
					           value="<?php echo esc_attr( $l_tint4 ); ?>"/></span>
                </div>
                
                <div class="row">

                                        <span class="cell"><label for="spec"><?php self::label( 'spec4' ); ?></label></span>
                    <span class="cell1"><input name="spec4" class="regular-text"
					           value="<?php echo esc_attr( $spec4 ); ?>"/></span>

                                        <span class="cell"><label for="note"><?php self::label( 'note4' ); ?></label></span>
                    <span class="cell1"><input name="note4" class="regular-text"
					           value="<?php echo esc_attr( $note4 ); ?>"/></span>
                </div>
                
               
            </table>
        </section>
    
    <div class="accordion">Account Profile</div>
    <section id="accounts" class="tabcontent">
        <div class="table">
           
                <div class="row">
                    <span class="cell"><label for="f_sp0"><?php self::label( 'f_sp0' ); ?></label></span>
                    <span class="cell1"><input name="f_sp0" id="f_sp0" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $f_sp0 ); ?>"/></span>

                <span class="cell"><label for="l_sp0"><?php self::label( 'l_sp0' ); ?></label></span>
                    <span class="cell1"><input name="l_sp0" id="l_sp0" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $l_sp0 ); ?>"/></span>
                </div>
                <div class="acc_f2">
                    <span class="cell"><label for="f_sp1"><?php self::label( 'f_sp1' ); ?></label></span>
                    <span class="cell1"><input name="f_sp1" id="f_sp1" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $f_sp1 ); ?>"/></span>

                <span class="cell"><label for="l_sp1"><?php self::label( 'l_sp1' ); ?></label></span>
                    <span class="cell1"><input name="l_sp1" id="l_sp1" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $l_sp1 ); ?>"/></span>                   
                </div>
                <div class="acc_f3">
                    <span class="cell"><label for="f_sp2"><?php self::label( 'f_sp2' ); ?></label></span>
                    <span class="cell1"><input name="f_sp2" id="f_sp2" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $f_sp2 ); ?>"/></span>

                <span class="cell"><label for="l_sp2"><?php self::label( 'l_sp2' ); ?></label></span>
                    <span class="cell1"><input name="l_sp2" id="l_sp2" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $l_sp2 ); ?>"/></span>                   
                </div>
                <div class="acc_f4">
                    <span class="cell"><label for="f_sp3"><?php self::label( 'f_sp3' ); ?></label></span>
                    <span class="cell1"><input name="f_sp3" id="f_sp3" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $f_sp3 ); ?>"/></span>

                <span class="cell"><label for="l_sp3"><?php self::label( 'l_sp3' ); ?></label></span>
                    <span class="cell1"><input name="l_sp3" id="l_sp3" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $l_sp3 ); ?>"/></span>                  
                </div>
                <div class="acc_f5">
                    <span class="cell"><label for="f_sp4"><?php self::label( 'f_sp4' ); ?></label></span>
                    <span class="cell1"><input name="f_sp4" id="f_sp4" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $f_sp4 ); ?>"/></span>

                <span class="cell"><label for="l_sp4"><?php self::label( 'l_sp4' ); ?></label></span>
                    <span class="cell1"><input name="l_sp4" id="l_sp4" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $l_sp4 ); ?>"/></span>                  
                </div>
                
                <div class="row">

                    <span class="cell"><label for="others"><?php self::label( 'others' ); ?></label></span>
                    <span class="cell1"><input name="others" id="others" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $others ); ?>"/></span>

                    <span class="cell"><label for="adj"><?php self::label( 'adj' ); ?></label></span>
                    <span class="cell1"><input name="adj" id="adj" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $adj ); ?>"/></span>
                </div>
                <div class="row">
                    <span class="cell"><label for="total"><?php self::label( 'total' ); ?></label></span>
                    <span class="cell1"><input name="total" id="total" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $total ); ?>"/></span>
                    <span class="cell"><label for="adv"><?php self::label( 'adv' ); ?></label></span>
                    <span class="cell1"><input name="adv" id="adv" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $adv ); ?>"/></span>
                </div> 
                <div class="row">
                    <span class="cell"><label for="bal"><?php self::label( 'bal' ); ?></label></span>
                    <span class="cell1"><input name="bal" id="bal" class="regular-text" oninput="calCustData()"
					           value="<?php echo esc_attr( $bal ); ?>"/></span>

                    <span class="cell"><label for="status"><?php self::label( 'status' ); ?></label></span>
		        <span class="cell1"><select name="status">
                                <option <?php if(esc_attr( $status )=="Pending"){echo 'selected'; } ?> value="Pending">Pending</option>
                                <option <?php if(esc_attr( $status )=="Processing"){echo 'selected'; } ?>  value="Processing">Processing</option>
                                <option <?php if(esc_attr( $status )=="Delivered"){echo 'selected'; } ?> value="Delivered">Delivered</option>
                                <option <?php if(esc_attr( $status )=="Canceled"){echo 'selected'; } ?> value="Canceled">Canceled</option>
                            </select>
                        </span>
                </div>                
        </div>
    </section>
    
    <div class="accordion">Specification</div>
    <section id="printableArea" class="tabcontent">
	
	<div class="caption">Optics Specification RIGHT</div>
    <table class="nbm_spec" style="width:100%">
                                      
                                        <!--<tr class="nbm_spec">
                                          <th class="nbm_spec" colspan="5">RIGHT</th>-->
										  
                                        <tr class="nbm_spec">
                                          <td class="nbm_spec"></td>
                                          <td class="nbm_spec">SPH</td>
                                          <td class="nbm_spec">CYC</td>
                                          <td class="nbm_spec">AXIS</td>
                                          <td class="nbm_spec">VN</td>
                                          <td class="nbm_spec">ADD</td>
                                        </tr>
                                        <tr class="nbm_spec">
                                          <td class="nbm_spec">DIST</td>
                                          <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'r_d_sph' ) ) { ?>
                                               <input name="r_d_sph" class="small-text" value="<?php echo esc_attr( $r_d_sph ); ?>"/>                         
                                              <?php } ?>
                                         </td>		
                                         <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'r_d_cyl' ) ) { ?>
                                               <input name="r_d_cyl" class="small-text" value="<?php echo esc_attr( $r_d_cyl ); ?>"/>                         
                                              <?php } ?>                                             
                                         </td>
                                         <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'r_d_axis' ) ) { ?>
                                               <input name="r_d_axis" class="small-text" value="<?php echo esc_attr( $r_d_axis ); ?>"/>                         
                                              <?php } ?>                                               
                                         </td>
										 <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'r_d_add' ) ) { ?>
                                               <input name="r_d_add" class="small-text" value="<?php echo esc_attr( $r_d_add ); ?>"/>                         
                                              <?php } ?>                                             
                                         </td>
                                         <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'r_d_va' ) ) { ?>
                                               <input name="r_d_va" class="small-text" value="<?php echo esc_attr( $r_d_va ); ?>"/>                         
                                              <?php } ?>                                             
                                         </td>
                                        </tr>
                                        <tr class="nbm_spec">
                                          <td class="nbm_spec">NEAR</td>
                                          <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'r_n_sph' ) ) { ?>
                                               <input name="r_n_sph" class="small-text" value="<?php echo esc_attr( $r_n_sph ); ?>"/>                         
                                              <?php } ?>                                              
                                          </td>		
                                          <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'r_n_cyl' ) ) { ?>
                                               <input name="r_n_cyl" class="small-text" value="<?php echo esc_attr( $r_n_cyl ); ?>"/>                         
                                              <?php } ?>                                              
                                          </td>
                                          <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'r_n_axis' ) ) { ?>
                                               <input name="r_n_axis" class="small-text" value="<?php echo esc_attr( $r_n_axis ); ?>"/>                         
                                              <?php } ?>                                              
                                          </td>	
                                          <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'r_n_add' ) ) { ?>
                                               <input name="r_n_add" class="small-text" value="<?php echo esc_attr( $r_n_add ); ?>"/>                         
                                              <?php } ?>                                              
                                          </td>										  
                                          <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'r_n_va' ) ) { ?>
                                               <input name="r_n_va" class="small-text" value="<?php echo esc_attr( $r_n_va ); ?>"/>                         
                                              <?php } ?>                                              
                                          </td>
                                        </tr>   
                       </table>
        
		<div class="caption">Optics Specification LEFT</div>
        <table class="nbm_spec" style="width:100%">

                                        <!--<tr class="nbm_spec">
                                          <th class="nbm_spec" colspan="5">LEFT</th>-->
                                        <tr class="nbm_spec">
                                          <td class="nbm_spec"></td>
                                          <td class="nbm_spec">SPH</td>
                                          <td class="nbm_spec">CYC</td>
                                          <td class="nbm_spec">AXIS</td>
                                          <td class="nbm_spec">VN</td>
                                          <td class="nbm_spec">ADD</td>
                                        </tr>
                                        <tr class="nbm_spec">
                                          <td class="nbm_spec">DIST</td>
                                          <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'l_d_sph' ) ) { ?>
                                               <input name="l_d_sph" class="small-text" value="<?php echo esc_attr( $l_d_sph ); ?>"/>                         
                                              <?php } ?>
                                         </td>		
                                         <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'l_d_cyl' ) ) { ?>
                                               <input name="l_d_cyl" class="small-text" value="<?php echo esc_attr( $l_d_cyl ); ?>"/>                         
                                              <?php } ?>                                             
                                         </td>
                                         <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'l_d_axis' ) ) { ?>
                                               <input name="l_d_axis" class="small-text" value="<?php echo esc_attr( $l_d_axis ); ?>"/>                         
                                              <?php } ?>                                               
                                         </td>
										 <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'l_d_add' ) ) { ?>
                                               <input name="l_d_add" class="small-text" value="<?php echo esc_attr( $l_d_add ); ?>"/>                         
                                              <?php } ?>                                             
                                         </td>
                                         <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'l_d_va' ) ) { ?>
                                               <input name="l_d_va" class="small-text" value="<?php echo esc_attr( $l_d_va ); ?>"/>                         
                                              <?php } ?>                                             
                                         </td>
                                        </tr>
                                        <tr class="nbm_spec">
                                          <td class="nbm_spec">NEAR</td>
                                          <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'l_n_sph' ) ) { ?>
                                               <input name="l_n_sph" class="small-text" value="<?php echo esc_attr( $l_n_sph ); ?>"/>                         
                                              <?php } ?>                                              
                                          </td>		
                                          <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'l_n_cyl' ) ) { ?>
                                               <input name="l_n_cyl" class="small-text" value="<?php echo esc_attr( $l_n_cyl ); ?>"/>                         
                                              <?php } ?>                                              
                                          </td>
                                          <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'l_n_axis' ) ) { ?>
                                               <input name="l_n_axis" class="small-text" value="<?php echo esc_attr( $l_n_axis ); ?>"/>                         
                                              <?php } ?>                                              
                                          </td>	
										<td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'l_n_add' ) ) { ?>
                                               <input name="l_n_add" class="small-text" value="<?php echo esc_attr( $l_n_add ); ?>"/>                         
                                              <?php } ?>                                              
                                          </td>
                                          <td class="nbm_spec">
                                              <?php
                                              if ( self::label_is_on( 'l_n_va' ) ) { ?>
                                               <input name="l_n_va" class="small-text" value="<?php echo esc_attr( $l_n_va ); ?>"/>                         
                                              <?php } ?>                                              
                                          </td>
                                        </tr>     
                                        
                                        <tr class="nbm_spec">
                                            <td>Ref By</td>
                                            <td colspan="2"><input name="ref_by" class="regular-text" value="<?php echo esc_attr( $ref_by ); ?>"/> </td>
                                        </tr>
							</table>
				</section>
				
	<div class="accordion">Imges or Media</div>
	<section id="images-media" class="tabcontent">
						   <table class="images_media" style="width:100%">
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
	</section>
 
                    <script type="text/javascript">   
						var acc = document.getElementsByClassName("accordion");
						var i;
						for (i = 0; i < acc.length; i++) {
							acc[i].onclick = function(){
								this.classList.toggle("active");
								this.nextElementSibling.classList.toggle("show");
							}
						}
					
						function openTab(evt, tabName) {
							// Declare all variables
							var i, tabcontent, tablinks;

							// Get all elements with class="tabcontent" and hide them
							tabcontent = document.getElementsByClassName("tabcontent");
							for (i = 0; i < tabcontent.length; i++) {
								tabcontent[i].style.display = "none";
							}

							// Get all elements with class="tablinks" and remove the class "active"
							tablinks = document.getElementsByClassName("tablinks");
							for (i = 0; i < tabcontent.length; i++) {
								tablinks[i].classList.remove("active");
							}

							// Show the current tab, and add an "active" class to the link that opened the tab
							document.getElementById(tabName).style.display = "block";
							evt.currentTarget.classList.add("active");
							
							
						}
						
                        function combo() {
                            var val= document.getElementById("thelist").value;
                            document.getElementById("theinput").value = val;
                        }
                        
                        
                        </script>
                        <?php $file= plugins_url( 'search/search.php' , dirname(__FILE__) ); 
                              $file2= plugins_url( 'search/searchBrand.php' , dirname(__FILE__) );
                              $file3= plugins_url( 'search/searchallstock.php' , dirname(__FILE__) );
                              $fileC_CITY= plugins_url( 'search/searchC_CITY.php' , dirname(__FILE__) );
                              $fileC_EMAIL= plugins_url( 'search/searchC_EMAIL.php' , dirname(__FILE__) );
                              $fileC_FNAME= plugins_url( 'search/searchC_FNAME.php' , dirname(__FILE__) );
                              $fileC_LNAME= plugins_url( 'search/searchC_LNAME.php' , dirname(__FILE__) );
                              $fileC_NO= plugins_url( 'search/searchC_NO.php' , dirname(__FILE__) );
                              $fileC_CITY_PIN= plugins_url( 'search/searchC_CITY_PIN.php' , dirname(__FILE__) );
                              $file4= plugins_url( 'search/searchallcustomer.php' , dirname(__FILE__) );
                              $file5= plugins_url( 'search/searchVrate.php' , dirname(__FILE__) );
                              $file6= plugins_url( 'search/searchVLrate.php' , dirname(__FILE__) );
                              $file7= plugins_url( 'search/searchLensBrand.php' , dirname(__FILE__) );                              
                              $file8= plugins_url( 'search/searchLensModel.php' , dirname(__FILE__) ); 
                        // $output = "<script>console.log( 'Debug Objects: " . $file . "' );</script>";
                       //  echo $output;
                        
                        ?>
                        <script>
                            jQuery(function($) {
                                //vrate
                                
                                $("#f_brand0").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#skills").val()+"&brand="+$("#f_brand0").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file5; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vrate").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vrate").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });

                                $("#f_brand1").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#frame2_model").val()+"&brand="+$("#f_brand1").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file5; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vrate1").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vrate1").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });
                                
                                $("#f_brand2").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#frame3_model").val()+"&brand="+$("#f_brand2").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file5; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vrate2").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vrate2").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });
                                    
                                $("#f_brand3").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#frame4_model").val()+"&brand="+$("#f_brand3").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file5; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vrate3").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vrate3").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });
                                    
                                $("#f_brand4").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#frame5_model").val()+"&brand="+$("#f_brand4").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file5; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vrate4").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vrate4").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });
                                    
                                $("#l_brand0").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#lens0_model").val()+"&brand="+$("#l_brand0").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file6; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vlrate0").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vlrate0").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });  
                                    
                                $("#l_brand1").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#lens1_model").val()+"&brand="+$("#l_brand1").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file6; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vlrate1").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vlrate1").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });
                                    
                                $("#l_brand2").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#lens2_model").val()+"&brand="+$("#l_brand2").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file6; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vlrate2").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vlrate2").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });
                                    
                                $("#l_brand3").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#lens3_model").val()+"&brand="+$("#l_brand3").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file6; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vlrate3").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vlrate3").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });
                                    
                                $("#l_brand4").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#lens4_model").val()+"&brand="+$("#l_brand4").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file6; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vlrate4").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vlrate4").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });                                    
                                   
                                    
                                $("#skills").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#skills").val()+"&brand="+$("#f_brand0").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file5; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vrate").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                                
                                                    $("#vrate").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                              
                                            }
                                          
                                          }
                                         }
                                      });
                                      return false;                                

                                });
                                
                                $("#frame2_model").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#frame2_model").val()+"&brand="+$("#f_brand1").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file5; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vrate1").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                                
                                                    $("#vrate1").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                              
                                            }
                                          
                                          }
                                         }
                                      });
                                      return false;                                

                                });
                                
                                $("#frame3_model").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#frame3_model").val()+"&brand="+$("#f_brand2").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file5; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vrate2").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                                
                                                    $("#vrate2").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                              
                                            }
                                          
                                          }
                                         }
                                      });
                                      return false;                                

                                }); 
                                
                                $("#frame4_model").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#frame4_model").val()+"&brand="+$("#f_brand3").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file5; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vrate3").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                                
                                                    $("#vrate3").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                              
                                            }
                                          
                                          }
                                         }
                                      });
                                      return false;                                

                                });
                                
                                $("#frame5_model").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#frame5_model").val()+"&brand="+$("#f_brand4").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file5; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vrate4").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                                
                                                    $("#vrate4").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                              
                                            }
                                          
                                          }
                                         }
                                      });
                                      return false;                                

                                });
                                
                                $("#lens0_model").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#lens0_model").val()+"&brand="+$("#l_brand0").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file6; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vlrate0").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vlrate0").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });                                 
                                
                                $("#lens1_model").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#lens1_model").val()+"&brand="+$("#l_brand1").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file6; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vlrate1").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vlrate1").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });
                                    
                                $("#lens2_model").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#lens2_model").val()+"&brand="+$("#l_brand2").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file6; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vlrate2").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vlrate2").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });
                                    
                                $("#lens3_model").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#lens3_model").val()+"&brand="+$("#l_brand3").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file6; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vlrate3").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vlrate3").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });
                                    
                                $("#lens4_model").focusout(function(){
                                   
                                    var data;
                                    var i;
                                      data= "model="+$("#lens4_model").val()+"&brand="+$("#l_brand4").val();
                                  
                                    $.ajax({
                                      url: '<?php echo $file6; ?>',  
                                      type: "POST",
                                      dataType: "json",
                                      data: data,
                                      success: function(data) {
                                          if(data!=""){
                                             //we need to inject a dropdown here ... to be continued
                                             document.getElementById("vlrate4").innerHTML = "";
                                             for(i=0; i<data.length; i++){
                                               
                                                    $("#vlrate4").append("<option value='"+data[i]+"'>"+data[i]+"</option>");
                                               
                                            }
                                            
                                          }
                                         }
                                      });
                                      return false;
                                    });                                    
                                    
                              /*  $( "#skills" ).autocomplete({
                                 source: '<?php //echo $file; ?>'
                                }); */
                            $( "#f_brand0" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file2; ?>', {
                                                term: $('#f_brand0').val(),
                                                model: $('#skills').val()
                                              }, response );
                                            }
                            });
                            $( "#f_brand1" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file2; ?>', {
                                                term: $('#f_brand1').val(),
                                                model: $('#frame2_model').val()
                                              }, response );
                                            }
                            });
                            $( "#f_brand2" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file2; ?>', {
                                                term: $('#f_brand2').val(),
                                                model: $('#frame3_model').val()
                                              }, response );
                                            }
                            });
                            $( "#f_brand3" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file2; ?>', {
                                                term: $('#f_brand3').val(),
                                                model: $('#frame4_model').val()
                                              }, response );
                                            }
                            });
                            $( "#f_brand4" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file2; ?>', {
                                                term: $('#f_brand4').val(),
                                                model: $('#frame5_model').val()
                                              }, response );
                                            }
                            });
                            
                            
                            $( "#skills" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file; ?>', {
                                                term: $('#skills').val(),
                                                f_brand: $('#f_brand0').val()
                                              }, response );
                                            }
                            });
                            $( "#frame2_model" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file; ?>', {
                                                term: $('#frame2_model').val(),
                                                f_brand: $('#f_brand1').val()
                                              }, response );
                                            }
                            });
                            $( "#frame3_model" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file; ?>', {
                                                term: $('#frame3_model').val(),
                                                f_brand: $('#f_brand2').val()
                                              }, response );
                                            }
                            });
                            $( "#frame4_model" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file; ?>', {
                                                term: $('#frame4_model').val(),
                                                f_brand: $('#f_brand3').val()
                                              }, response );
                                            }
                            });
                            $( "#frame5_model" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file; ?>', {
                                                term: $('#frame5_model').val(),
                                                f_brand: $('#f_brand4').val()
                                              }, response );
                                            }
                            });
                            
                            $( "#l_brand0" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file7; ?>', {
                                                term: $('#l_brand0').val(),
                                                model: $('#lens0_model').val()
                                              }, response );
                                            }
                            });
                            $( "#l_brand1" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file7; ?>', {
                                                term: $('#l_brand1').val(),
                                                model: $('#lens1_model').val()
                                              }, response );
                                            }
                            });
                            $( "#l_brand2" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file7; ?>', {
                                                term: $('#l_brand2').val(),
                                                model: $('#lens2_model').val()
                                              }, response );
                                            }
                            });
                            $( "#l_brand3" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file7; ?>', {
                                                term: $('#l_brand3').val(),
                                                model: $('#lens3_model').val()
                                              }, response );
                                            }
                            });
                            $( "#l_brand4" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file7; ?>', {
                                                term: $('#l_brand4').val(),
                                                model: $('#lens4_model').val()
                                              }, response );
                                            }
                            });
                            
                            
                            $( "#lens0_model" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file8; ?>', {
                                                term: $('#lens0_model').val(),
                                                l_brand: $('#l_brand0').val()
                                              }, response );
                                            }
                            });
                            $( "#lens1_model" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file8; ?>', {
                                                term: $('#lens1_model').val(),
                                                l_brand: $('#l_brand1').val()
                                              }, response );
                                            }
                            });
                            $( "#lens2_model" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file8; ?>', {
                                                term: $('#lens2_model').val(),
                                                l_brand: $('#l_brand2').val()
                                              }, response );
                                            }
                            });
                            $( "#lens3_model" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file8; ?>', {
                                                term: $('#lens3_model').val(),
                                                l_brand: $('#l_brand3').val()
                                              }, response );
                                            }
                            });
                            $( "#lens4_model" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $file8; ?>', {
                                                term: $('#lens4_model').val(),
                                                l_brand: $('#l_brand4').val()
                                              }, response );
                                            }
                            });                            
                            
                            
                                $( "#c_no" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $fileC_NO; ?>', {
                                                c_no: $('#c_no').val(),
                                                c_lname: $('#c_lname').val(),
                                                c_fname: $('#c_fname').val(),
                                                c_city: $('#c_city').val(),
                                                c_city_pin: $('#c_city_pin').val(),
                                                c_email: $('#c_email').val()
                                              }, response );
                                            }
                            });
                            
                            $( "#c_lname" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $fileC_LNAME; ?>', {
                                                c_no: $('#c_no').val(),
                                                c_lname: $('#c_lname').val(),
                                                c_fname: $('#c_fname').val(),
                                                c_city: $('#c_city').val(),
                                                c_city_pin: $('#c_city_pin').val(),
                                                c_email: $('#c_email').val()
                                              }, response );
                                            }
                            });
                            
                            $( "#c_fname" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $fileC_FNAME; ?>', {
                                                c_lname: $('#c_lname').val(),
                                                c_fname: $('#c_fname').val(),
                                                c_no: $('#c_no').val(),
                                                c_city: $('#c_city').val(),
                                                c_city_pin: $('#c_city_pin').val(),
                                                c_email: $('#c_email').val()
                                              }, response );
                                            }
                            });
                            
                            $( "#c_city" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $fileC_CITY; ?>', {
                                                c_lname: $('#c_lname').val(),
                                                c_city: $('#c_city').val(),
                                                c_fname: $('#fname').val(),
                                                c_no: $('#c_no').val(),
                                                c_city_pin: $('#c_city_pin').val(),
                                                c_email: $('#c_email').val()
                                              }, response );
                                            }
                            });
                            

                        
                        
                        $( "#c_city_pin" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $fileC_CITY_PIN; ?>', {
                                                c_lname: $('#c_lname').val(),
                                                c_city_pin: $('#c_city_pin').val(),
                                                c_fname: $('#fname').val(),
                                                c_city: $('#c_city').val(),
                                                c_no: $('#c_no').val(),
                                                c_email: $('#c_email').val()
                                              }, response );
                                            }
                            });
                            
                            $( "#c_email" ).autocomplete({
                                            source: function( request, response ) {
                                              $.getJSON( '<?php echo $fileC_EMAIL; ?>', {
                                                c_lname: $('#c_lname').val(),
                                                c_fname: $('#fname').val(),
                                                c_city: $('#c_city').val(),
                                                c_city_pin: $('#c_city_pin').val(),
                                                c_email: $('#c_email').val(),
                                                c_no: $('#c_no').val()
                                              }, response );
                                            }
                            });
                       });//jquery ends
                        //json for filling as per retrival of data
                            jQuery(function($){
                             $(".onfocus").focusout(function(){
                                var data;
                                  data= "model="+$("#skills").val()+"&brand="+$("#f_brand0").val();

                               $.ajax({
                                 url: '<?php echo $file3; ?>',  
                                 type: "POST",
                                 dataType: "json",
                                 data: data,
                                 success: function(data) {
                                     if(data!=""){
                                        if($('#skills').val(data[1])!=""){ $('#skills').val(data[1]); }
                                        if($('#f_brand0').val(data[0])!=""){  $('#f_brand0').val(data[0]); }
                                    }
                                 }
                               });
                               return false;
                             });
                           });
                        //below is the query for customer   
                        jQuery(function($){
                             $(".onfocusCS").focusout(function(){
                                var data;
                              data= "c_no="+$("#c_no").val()+"&c_fname="+$("#c_fname").val()+
                                      "&c_lname="+$("#c_lname").val()+"&c_city="+$("#c_city").val()+
                                      "&c_city_pin="+$("#c_city_pin").val()+"&c_email="+$("#c_email").val();

                               $.ajax({
                                 url: '<?php echo $file4; ?>',  
                                 type: "POST",
                                 dataType: "json",
                                 data: data,
                                 success: function(data) {
                                     if(data!=""){
                                         $('#c_no').val(data[0]); 
                                         $('#c_fname').val(data[1]);
                                         $('#c_lname').val(data[2]); 
                                         $('#c_city').val(data[3]); 
                                         $('#c_city_pin').val(data[4]); 
                                         $('#c_email').val(data[5]);
                                         $('#c_add').val(data[6]);
                                    }
                                 }
                               });
                               return false;
                             });
                           });
                           
                        </script>
                        
   <!-- This script is optimized as per data edit or entry -->
   
   <?php// if($inventory_id==null){ ?>
                        <script>
                         
                            function myFunction() {
                                var x2 = document.getElementById("frame2_model").value;
                                var x3 = document.getElementById("frame3_model").value;
                                var x4 = document.getElementById("frame4_model").value;
                                var x5 = document.getElementById("frame5_model").value;


                                //var y2 = document.getElementById("lens2_r").value;
                                //var y3 = document.getElementById("lens3_r").value;
                               // var y4 = document.getElementById("lens4_r").value;
                               // var y5 = document.getElementById("lens5_r").value;

                                //var z2 = document.getElementById("lens2_l").value;
                               // var z3 = document.getElementById("lens3_l").value;
                               // var z4 = document.getElementById("lens4_l").value;
                                //var z5 = document.getElementById("lens5_l").value;

                                if(x2!=""){  jQuery(".acc_f2").show(); } else{  jQuery(".acc_f2").hide(); }
                                if(x3!=""){  jQuery(".acc_f3").show(); } else{  jQuery(".acc_f3").hide(); }
                                if(x4!=""){  jQuery(".acc_f4").show(); } else{  jQuery(".acc_f4").hide(); }
                                if(x5!=""){ jQuery(".acc_f5").show(); } else{  jQuery(".acc_f5").hide(); }

                                   // if(y2!=null && z2!=null ){ $(".acc_l2").show(); } else{ $(".acc_l2").hide(); }
                                  //  if(y3!=null && z3!=null ){ $(".acc_l3").show(); } else{ $(".acc_l3").hide(); }
                                   // if(y4!=null && z4!=null ){ $(".acc_l4").show(); } else{ $(".acc_l4").hide(); }
                                   // if(y5!=null && z5!=null ){ $(".acc_l5").show(); } else{ $(".acc_l5").hide(); }







                            }


                            jQuery(function($){

                                <?php if($f_model1==null){ ?>
                                $(".frame_t2").hide();
                                $(".lens_t2").hide();
                                $(".acc_f2").hide();
                                <?php } ?>
                                
                                <?php if($f_model2==null){ ?>
                                $(".frame_t3").hide();
                                $(".lens_t3").hide();
                                $(".acc_f3").hide();
                                <?php } ?>
                                    
                                <?php if($f_model3==null){ ?>
                                $(".frame_t4").hide();
                                $(".lens_t4").hide();
                                $(".acc_f4").hide();
                                <?php } ?>
                                
                                <?php if($f_model4==null){ ?>
                                $(".frame_t5").hide();
                                $(".lens_t5").hide();
                                $(".acc_f5").hide();
                                <?php } ?>                                      

                            });

                            jQuery(document).ready(function() {
                                jQuery('.MyDate').datepicker({
                                    dateFormat : 'yy-mm-dd'
                                });
                            });


                            function myFunctionFrame() {
                            var x = document.getElementById("nofFrame").value;
                                if (x == "1") 
                                {
                                        jQuery(".frame_t1").show();
                                        jQuery(".frame_t2").hide();
                                        jQuery(".frame_t3").hide();
                                        jQuery(".frame_t4").hide();
                                        jQuery(".frame_t5").hide();
                                        jQuery(".lens_t1").show();
                                        jQuery(".lens_t2").hide();
                                        jQuery(".lens_t3").hide();
                                        jQuery(".lens_t4").hide();
                                        jQuery(".lens_t5").hide();
                                }else if (x == "2")
                                    {
                                        jQuery(".frame_t1").show();
                                        jQuery(".frame_t2").show();
                                        jQuery(".frame_t3").hide();
                                        jQuery(".frame_t4").hide();
                                        jQuery(".frame_t5").hide();
                                        jQuery(".lens_t1").show();
                                        jQuery(".lens_t2").show();
                                        jQuery(".lens_t3").hide();
                                        jQuery(".lens_t4").hide();
                                        jQuery(".lens_t5").hide();
                                    }
                                  else if (x == "3")
                                    {
                                        jQuery(".frame_t1").show();
                                        jQuery(".frame_t2").show();
                                        jQuery(".frame_t3").show();
                                        jQuery(".frame_t4").hide();
                                        jQuery(".frame_t5").hide();
                                        jQuery(".lens_t1").show();
                                        jQuery(".lens_t2").show();
                                        jQuery(".lens_t3").show();
                                        jQuery(".lens_t4").hide();
                                        jQuery(".lens_t5").hide();
                                    }
                                  else if(x == "4")
                                    {
                                        jQuery(".frame_t1").show();
                                        jQuery(".frame_t2").show();
                                        jQuery(".frame_t3").show();
                                        jQuery(".frame_t4").show();
                                        jQuery(".frame_t5").hide();
                                        jQuery(".lens_t1").show();
                                        jQuery(".lens_t2").show();
                                        jQuery(".lens_t3").show();
                                        jQuery(".lens_t4").show();
                                        jQuery(".lens_t5").hide();
                                    }
                                  else if (x == "5")
                                    {
                                        jQuery(".frame_t1").show();
                                        jQuery(".frame_t2").show();
                                        jQuery(".frame_t3").show();
                                        jQuery(".frame_t4").show();
                                        jQuery(".frame_t5").show();
                                        jQuery(".lens_t1").show();
                                        jQuery(".lens_t2").show();
                                        jQuery(".lens_t3").show();
                                        jQuery(".lens_t4").show();
                                        jQuery(".lens_t5").show();
                                    }
                                else if (x == "")
                                    {
                                        jQuery(".frame_t1").show();
                                        jQuery(".frame_t2").hide();
                                        jQuery(".frame_t3").hide();
                                        jQuery(".frame_t4").hide();
                                        jQuery(".frame_t5").hide();
                                        jQuery(".lens_t1").show();
                                        jQuery(".lens_t2").hide();
                                        jQuery(".lens_t3").hide();
                                        jQuery(".lens_t4").hide();
                                        jQuery(".lens_t5").hide();
                                    }

                                }                         

                         </script>
   <?php// } ?>                     
			<?php
			do_action( 'wpim_edit_item', $inventory_id ); ?>
			<input type="hidden" name="action" value="save"/>
			<input type="hidden" name="inventory_item_id" value="<?php echo $inventory_id; ?>"/>
			<?php wp_nonce_field( self::NONCE_ACTION, 'nonce' ); ?>
			<p class="submit">
				<a href="<?php echo self::$self_url; ?>" class="button"><?php self::_e( 'Cancel' ); ?></a>
				<?php if ( self::check_permission( 'save_item', $inventory_id ) ) { ?>
					<input type="submit" name="save" class="button button-primary"
					       value="<?php self::_e( 'Save Item' ); ?>"/>
				<?php if($inventory_id!=null){ ?>
                                         <!--<a href="#"> <button type="button" onclick="printDiv('printableArea')" >Print Bill</button></a> -->
                                <?php }} ?>
			</p>
		</form>
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
		echo '<a href="media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=673" data-count="' . $count . '" id="inventory-link-' . $count . '" class="wpinventory-upload">' . $word . ' ' . self::__( 'Image' ) . '</a>';
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
		echo '<a href="media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=673" data-count="' . $count . '" id="inventory-link-' . $count . '" class="button wpinventory-upload">' . self::__( 'Add Media' ) . '</a>';
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
	 * Then loads all the labels that are configured (and can be extended via filter 'wpim_default_labels') and loads from _$POST
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
//my code i have commented this for now
                /*
		if ( ! $inventory_number && ! $inventory_name ) {
			self::$error = self::__( 'Either ' ) .
			               self::get_label( 'inventory_name' ) .
			               ' or ' . self::get_label( 'inventory_number' ) .
			               ' ' . self::__( 'is required.' );
		}
*/
		if ( ! self::$error ) {
               global $current_user;
                global $wpdb;
                
                $sirus=$current_user->user_login;
                $gid = $wpdb->get_var( "SELECT gid FROM $wpdb->users where user_login='$sirus'" );
              // $message = "Frame model " . $f_model[0];
              //  echo "<script type='text/javascript'>alert('$message');</script>";
			$data = array(
                        'inventory_id'              =>  $inventory_id,
			'order_no'                  =>  $order_no,
                        'c_no'                      => $c_no,
                        'date'                      => $date,     
                        'd_date'                    => $d_date,
                        'c_fname'                   => $c_fname,
                        'c_lname'                   => $c_lname,
                        'c_gender'                  => $c_gender,
                        'c_add'                     => $c_add,
                        'c_city'                    => $c_city,
                        'c_city_pin'                => $c_city_pin,
                        'c_email'                   => $c_email,
                        'c_birth'                   => $c_birth,
                        'c_anni'                    => $c_anni,
                        'f_count'                   => $f_count,
                        'l_count'                   => $l_count,
                        'f_price'                   => $f_price,
                        'l_price'                   => $l_price,
                        'others'                    => $others,
                        'adj'                       => $adj,
                        'total'                     => $total,
                        'adv'                       => $adv,
                        'bal'                       => $bal,
                        'status'                    => $status,
                        'r_d_sph'                   => $r_d_sph,
                        'r_d_cyl'                   => $r_d_cyl,
                        'r_d_axis'                  => $r_d_axis,
                        'r_d_add'                   => $r_d_add,
                        'r_d_va'                    => $r_d_va,
                        'r_n_sph'                   => $r_n_sph,
                        'r_n_cyl'                   => $r_n_cyl,
                        'r_n_axis'                  => $r_n_axis, 
                        'r_n_add'                   => $r_n_add,
                        'r_n_va'                    => $r_n_va,                   
                        'l_d_sph'                   => $l_d_sph,
                        'l_d_cyl'                   => $l_d_cyl,
                        'l_d_axis'                  => $l_d_axis,
                        'l_d_add'                   => $l_d_add,
                        'l_d_va'                    => $l_d_va,
                        'l_n_sph'                   => $l_n_sph,
                        'l_n_cyl'                   => $l_n_cyl,
                        'l_n_axis'                  => $l_n_axis,
                        'l_n_add'                   => $l_n_add,
                        'l_n_va'                    => $l_n_va,
                        'r_lpd'                     => $r_lpd,
                        'l_lpd'                     => $l_lpd,    
                        'o_desc'                    => $o_desc,
                        'ref_by'                    => $ref_by,
			'inventory_slug'            => $inventory_slug,	
			'category_id'               => $category_id,
			'user_id'                   => $user_id,
                        'inventory_date_added'      => $inventory_date_added,   
			'inventory_date_updated'    => $inventory_date_updated,
			'gid'                       => $gid,
                        'f_brand0'                  => $f_brand0,
                        'f_model0'                   => $f_model0,
                        'f_color0'                   => $f_color0,                            
                        'f_size0'                    => $f_size0,
                        'f_sp0'                      => $f_sp0,
                        'f_rate0'                      => $f_rate0,
                        'f_brand1'                  => $f_brand1,
                        'f_model1'                   => $f_model1,
                        'f_size1'                    => $f_size1,
                        'f_color1'                   => $f_color1,
                        'f_sp1'                      => $f_sp1,
                        'f_rate1'                      => $f_rate1,
                        'f_brand2'                  => $f_brand2,
                        'f_model2'                   => $f_model2,
                        'f_size2'                    => $f_size2,
                        'f_color2'                   => $f_color2,
                        'f_sp2'                      => $f_sp2,
                        'f_rate2'                      => $f_rate2,
                        'f_brand3'                  => $f_brand3,
                        'f_model3'                   => $f_model3,
                        'f_size3'                    => $f_size3,
                        'f_color3'                   => $f_color3,
                        'f_sp3'                      => $f_sp3,
                        'f_rate3'                      => $f_rate3,
                        'f_brand4'                  => $f_brand4,    
                        'f_model4'                   => $f_model4,
                        'f_size4'                    => $f_size4,
                        'f_color4'                   => $f_color4,
                        'f_sp4'                      => $f_sp4,
                        'f_rate4'                      => $f_rate4,
                        'l_brand0'                   => $l_brand0,
                        'l_model0'                   => $l_model0,
                        'l_rate0'                    => $l_rate0,                            
                        'r0'                         => $r0,
                        'r_size0'                    => $r_size0,
                        'r_tint0'                    => $r_tint0,
                        'l0'                         => $l0,
                        'l_size0'                    => $l_size0,
                        'l_tint0'                    => $l_tint0,
                        'spec0'                      => $spec0,
                        'note0'                      => $note0,
                        'l_sp0'                      => $l_sp0,
                        'l_nos0'                     => $l_nos0,
                        'l_brand1'                   => $l_brand1,
                        'l_model1'                   => $l_model1,
                        'l_rate1'                    => $l_rate1,                            
                        'r1'                         => $r1,
                        'r_size1'                    => $r_size1,
                        'r_tint1'                    => $r_tint1,
                        'l1'                         => $l1,
                        'l_size1'                    => $l_size1,
                        'l_tint1'                    => $l_tint1,
                        'spec1'                      => $spec1,
                        'note1'                      => $note1,
                        'l_sp1'                      => $l_sp1,
                        'l_nos1'                     => $l_nos1,
                        'l_brand2'                   => $l_brand2,
                        'l_model2'                   => $l_model2,
                        'l_rate2'                    => $l_rate2,                            
                        'r2'                         => $r2,
                        'r_size2'                    => $r_size2,
                        'r_tint2'                    => $r_tint2,
                        'l2'                         => $l2,
                        'l_size2'                    => $l_size2,
                        'l_tint2'                    => $l_tint2,
                        'spec2'                      => $spec2,
                        'note2'                      => $note2,
                        'l_sp2'                      => $l_sp2,
                        'l_nos2'                     => $l_nos2,
                        'l_brand3'                   => $l_brand3,
                        'l_model3'                   => $l_model3,
                        'l_rate3'                    => $l_rate3,                            
                        'r3'                         => $r3,
                        'r_size3'                    => $r_size3,
                        'r_tint3'                    => $r_tint3,
                        'l3'                         => $l3,
                        'l_size3'                    => $l_size3,
                        'l_tint3'                    => $l_tint3,
                        'spec3'                      => $spec3,
                        'note3'                      => $note3,
                        'l_sp3'                      => $l_sp3,
                        'l_nos3'                     => $l_nos3,
                        'l_brand4'                   => $l_brand4,
                        'l_model4'                   => $l_model4,
                        'l_rate4'                    => $l_rate4,                            
                        'r4'                         => $r4,
                        'r_size4'                    => $r_size4,
                        'r_tint4'                    => $r_tint4,
                        'l4'                         => $l4,
                        'l_size4'                    => $l_size4,
                        'l_tint4'                    => $l_tint4,
                        'spec4'                      => $spec4,
                        'note4'                      => $note4,
                        'l_sp4'                      => $l_sp4,
                        'l_nos4'                     => $l_nos4
 );

			if ( $inventory_id = self::$item->save( $data ) ) {
				$imagesort = explode( ',', self::request( 'imagesort' ) );
				$mediasort = explode( ',', self::request( 'mediasort' ) );

				self::$item->save_images( $inventory_id, self::request( 'image' ), $imagesort );

				do_action( 'wpim_save_item', $inventory_id, $data );

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
	public static function manage_categories() {

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
	public static function manage_labels() {

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

	public static function manage_display() {
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
					<li style="display: none !important; data-field-id="
					">Shiv for jQuery to insert before</li>
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

	public static function manage_settings() {
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
						echo self::dropdown_array( "theme", $settings['theme'], $themes, 'wpinventory_themes' );
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
			<?php do_action( 'wpim_edit_settings' ); ?>
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
							$placeholder = wpinventory_get_placeholder_image( 'all' );
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
				<?php do_action('wpim_edit_settings_script'); ?>
			});
		</script>
	<?php
	}

	private static function save_settings() {
		$settings = self::getOptions();

		$placeholder_image = self::request( 'placeholder_image' );
		if ( $placeholder_image ) {
			$item = new WPIMItem();
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

		do_action( 'wpim_save_settings' );

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
				WPIMAPI::activate( $data, $reg_key );
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
                        if($column['title']=='P/L Stats'){}else{
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
                           }
			// TODO: Get sort images!
			if ( self::$sortby == $sortfield ) {
				$alt = ( self::$sortdir == 'ASC' ) ? '&uarr;' : '&darr;';
				// $content.= '<img src="' . $this->url . '/images/sort_' . strtolower(self::$sortdir) . '.gif" alt="' . $alt . '" />';
				$content .= '<strong>' . $alt . '</strong>';
			}
		}
               //mark

		$content .= ( $action == NULL ) ? '<th class="actions">' . self::__( 'Actions' ) . '</th>' : '';
		$content .= '</tr>';                 


		$content = apply_filters('wpim_grid_columns', $content, $columns, $self);

		return $content;
	}

	public static function grid_reports_columns( $columns, $self, $default = 'name', $action = FALSE ) {
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
                        //if($column['title']=='P/L Stats'){}else{
                        $content .= $column['title'];//}
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
               //mark

		//$content .= ( $action == NULL ) ? '<th class="actions">' . self::__( 'Actions' ) . '</th>' : '';
		$content .= '</tr>';                 


		$content = apply_filters('wpim_grid_columns', $content, $columns, $self);

		return $content;
	}
        
	private static function get_item( $inventory_id ) {
		return self::$item->get( $inventory_id );
	}
        //my code
        	private static function get_fitem( $order_no ) {
                        $output = "<script>console.log( 'Order ID inside get_fitem: " . $order_no . "' );</script>";
                        echo $output;
		return self::$item->getf( $order_no );
	}
        	private static function get_litem( $order_no ) {
                    $output = "<script>console.log( 'Order ID inside get_litem: " . $order_no . "' );</script>";
                        echo $output;
		return self::$item->getl( $order_no );
	}
                	
        private static function get_order() {
		return self::$item->geto();
	
        }
        //my code ends

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

		self::admin_heading( self::__( 'WP Inventory Add Ons' ) );

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
		echo '<h2>Netra Order Management<span class="version">Netra Order Management ' . self::__( 'Version' ) . ' ' . self::VERSION . '</span></h2>' . PHP_EOL;
		echo '<h3>' . $subtitle . '</h3>' . PHP_EOL;
		echo self::output_errors();
		echo self::output_messages();
		//echo self::donate_button();
	}

	private static function admin_footer() {
		echo '</div>' . PHP_EOL;
	}
}
