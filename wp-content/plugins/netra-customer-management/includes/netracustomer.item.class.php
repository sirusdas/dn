<?php

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * Class for accessing inventory items
 * @author - WEBXARC Developers	
 * @package - NETRACustomer
 * @copyright 2016 - WEBXARC Developers
 */
class NCMItem extends NCMDB {
	
	private static $instance;
	
	protected static $message;
	
	/**
	* Constructor magic method.
	*/
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Get an instance of the class
	 * @return object
	 */
	public static function getInstance() {
		if ( ! self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/**
	 * This is here purely to prevent someone from cloning the class
	 */
	private function __clone() {}
	
	public function default_args() {
		$defaults = array(
				'order'				=> 'inventory_name',
				'page_size'			=> NCMCore::$config->get('page_size'),  // Unlimited
				'page'				=> 0,  		// Beginning
				'name'				=> '', 		// Filter by name.  Will use %LIKE%
				'include_category'	=> 1, 		// Whether to retrieve the category name
				'status'			=> 'ALL', 	// 1 = Active, 0 = Inactive, ALL = all
				'inventory_id'		=> NULL,
				'inventory_slug'	=> NULL,
				'category_id'		=> NULL,
				'category_name'		=> NULL, 	// If used, do not use category_id
				'category_slug'		=> NULL,	// If used, do not use category_id
				'user_id'			=> NULL,
				'search'			=> ''
		);
		
		return $defaults;
	}
		
	/**
	 * Get a listing of inventory items.
	 * @param array $args
	 * valid arguments:
	 * order - set the sort order
	 * per_page - set the number per page
	 * paged - set the starting page
	 * user_id - the user id to restrict the list to
	 * @return array
	 */
	public function get_all($args = NULL, $get_counts = FALSE) {
		
		$fields = '';
		$from = '';
		$where = '';
		$limit = '';
		$order = '';
		
		$defaults = $this->default_args();
		
		if ( ! is_array($args)) {
			parse_str($args, $args);
		}

		$args = apply_filters('ncm_query_item_args', $args);
		$args = wp_parse_args($args, $defaults);
		
		extract($args);
		
		$order = $this->parse_sort($order, $this->get_fields());

		if ($name) {
			$where = $this->wpdb->prepare(' WHERE i.inventory_name LIKE "%%s%"', $name);
		}
		
		// TODO: Apply REAL statuses here!
		if ($status && strtolower($status) !== 'all') {
			if (strtolower($status == 'active')) {
				$status = 1;
			}
			$where = $this->append_where($where, ' i.inventory_status=' . (int)$status);
		}
		
		if ($inventory_id) {
			$where = $this->append_where($where, $this->wpdb->prepare(' i.inventory_id = %d', $inventory_id));
		}
		
		if ($inventory_slug) {
			$where = $this->append_where($where, $this->wpdb->prepare(' i.inventory_slug = %s', $inventory_slug));
		}
		
		if ($category_name && ! $category_id) {
			// Set the flag to include the category name information
			$include_category = 1;
			// Set the where to limit by category name (Exact match, case insensitive)
			$where = $this->append_where($where, $this->wpdb->prepare('c.category_name = %s', $category_name));
		}
		
		if ($category_slug && ! $category_id) {
			// Set the flag to include the category name information
			$include_category = 1;
			// Set the where to limit by category name (Exact match, case insensitive)
			$where = $this->append_where($where, $this->wpdb->prepare('c.category_slug = %s', $category_slug));
		}
		
		if ($category_id) {
			if (is_array($category_id)) {
				$in = array_fill(0, count($category_id), '%d');
				$where = $this->append_where($where, $this->wpdb->prepare(' i.category_id IN (' . implode(',', $in) . ')', $category_id));
			} else {
				$where = $this->append_where($where, $this->wpdb->prepare(' i.category_id = %d', $category_id));
			}
		}
		
		if ( ! empty($product_id)) {
			if (is_array($product_id)) {
				$in = array_fill(0, count($product_id), '%d');
				$where = $this->append_where($where, $this->wpdb->prepare(' i.inventory_id IN (' . implode(',', $in) . ')', $product_id));
			} else {
				$where = $this->append_where($where, $this->wpdb->prepare(' i.inventory_id = %d', $product_id));
			}
		}
		
		if ( ! empty($user_id)) {
			if (is_array($user_id)) {
				$in = array_fill(0, count($user_id), '%d');
				$where = $this->append_where($where, $this->wpdb->prepare(' i.user_id IN (' . implode(',', $in) . ')', $user_id));
			} else {
				$where = $this->append_where($where, $this->wpdb->prepare(' i.user_id = %d', $user_id));
			}
		}
		
		
		if ((int)$page_size) {
			$limit = ' LIMIT ';
			$limit.= ((int)$page) ? (int)($page * $page_size) . ',' : '';
			$limit.= (int)$page_size;
		}
		
		if ((int)$include_category) {
			$from = ' LEFT JOIN ' . $this->category_table . ' AS c ON i.category_id = c.category_id ';
			$fields = 'c.category_name AS inventory_category';
		}
		
		if ($search) {
			$where = $this->append_where($where, $this->parse_search($search));
		}
		
                if ($search1||$search_from) {
                               // echo "<script type='text/javascript'>alert('Search1  is called');</script>";
			$where = $this->append_where($where, $this->parse_date_search($search_from,$search1));
		}                  
                
		$order = ($order) ? ' ORDER BY ' . $order : ' ';
		
		if ($fields) {
			$fields = ', ' . $fields;
		}
                
                //my code
                global $current_user;
                global $wpdb;
                
                $sirus=$current_user->user_login;
                $gid = $wpdb->get_var( "SELECT gid FROM $wpdb->users where user_login='$sirus'" );
                $where = $this->append_where($where, $this->wpdb->prepare(' i.gid = %d', $gid));
		////////////////////////////////////////////////my code ends/////////////////////////////////////////////////////////////////////////
		
		if ($where) {
			$where = ' WHERE ' . $where;
		}
                
                
		if ($get_counts) {
			$query = 'SELECT count(*) FROM ' . $this->inventory_table . ' AS i ' . $from . $where;
			return $this->wpdb->get_var($query);
		}
                
		$query = 'SELECT i.*' . $fields . ' FROM ' . $this->inventory_table . ' AS i ' . $from . $where . $order . $limit;
//		 echo '<br>' . $query;
                //echo "<script type='text/javascript'>alert('$where');</script>";
		$items = $this->parseFromDb($this->wpdb->get_results($query));
		return apply_filters('ncm_get_items', $items);
	} 
	
	/**
	 * Get an array containing all of the inventory item fields in the database
	 * @return array
	 */
	public static function get_fields() {
		return array(
			'inventory_id',
			'invoice_no',
			'vendor_name',
                        'vendor_address',
			'p_name',
			'p_model_no',
			'p_qty',
			'p_rate',
			'p_total',
                        'p_adv',
			'p_bal',
			'p_details',
			'inventory_slug',
                        'inventory_sort_order',
                        'category_id',
                        'user_id',
                        'inventory_date_added',
                        'inventory_date_updated',
                        'gid'
		);
	}
	
	public static function get_searchable_fields() {
		$no_search = array(
			'inventory_id',
			'inventory_slug',
			'inventory_sort_order',
			'category_id',
			'user_id',
			'inventory_date_added',
			'inventory_date_updated'
		);
		
		$fields = self::get_fields();
		
		foreach($fields AS $key=>$field) {
			if (in_array($field, $no_search)) {
				unset($fields[$key]);
			}
		}
		
		return $fields;
	}
	
	public function parse_search($search) {
		$fields = $this->get_searchable_fields();
		$where = '';
		foreach($fields AS $field) {
			if ($where) {
				$where.= ' OR';
			}
			$where.= ' `' . $field . '` LIKE ' . $this->wpdb->prepare("%s", '%' . $search . '%') . '';
		}
		return '(' . $where . ')';
	}
        
                //my code for searching by dates////////////////////////////////////////////////////////search by date///////////////////////////////////////////////////////////
	public function parse_date_search($search_from,$search1) {
           // echo "<script type='text/javascript'>alert('Search by date From= ".$search_from." to ".$search1. "');</script>";
		$fields = $this->get_date_searchable_fields();
		$where = '';
		foreach($fields AS $field) {
			if ($where) {
				$where.= ' OR';
			}
			$where.= ' inventory_date_updated between "'.$search_from.' 00:00:00.000" and "'.$search1.' 23:59:59.999"' ;
		}
		return '(' . $where . ')';
	}
	
	/**
	 * Get specific inventory item
	 * @param integer $inventory_id
	 * @return object
	 */
	public function get($inventory_id) {
		return $this->parseRowFromDb($this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->inventory_table . ' WHERE inventory_id = %d', $inventory_id)));
	}
        
	public function getInvoice() {
		return $this->wpdb->get_var('SELECT invoice_no FROM ' . $this->inventory_table . ' ORDER BY invoice_no DESC   LIMIT 1');
	}        

	public function get_pdata($iv_no) {
		return $this->wpdb->get_results($this->wpdb->prepare('SELECT * FROM wp_netracustomer_data WHERE invoice_no = %d ORDER BY  p_count ASC', $iv_no));
	}        
	/**
	 * Get all of the images associated with an inventory item
	 * @param integer $inventory_id
	 * @return array 
	 */
	public function get_images($inventory_id) {
		if ( ! $inventory_id) {
			return array();
		}
		return $this->wpdb->get_results($this->wpdb->prepare('SELECT * FROM ' . $this->image_table . ' WHERE inventory_id = %d ORDER BY image_sort_order', $inventory_id));
	}
	
	/**
	 * Get all of the media associated with an inventory item
	 * @param integer $inventory_id
	 * @return array
	 */
	public function get_media($inventory_id) {
		if ( ! $inventory_id) {
			return array();
		}
		return $this->wpdb->get_results($this->wpdb->prepare('SELECT * FROM ' . $this->media_table . ' WHERE inventory_id = %d ORDER BY media_sort_order', $inventory_id));
	}
	
	/**
	 * Save the inventory item
	 * @param array $data
	 * @return integer | boolean - inventory id on success, false on failure
	 */
	public function save($data) {
		extract($data);
                
                $s_qty=$p_qty;
                
		$now = $this->date_to_mysql(current_time('timestamp'), TRUE);
		$inventory_slug = $this->validate_slug('inventory', $inventory_slug, $inventory_name, $inventory_id); 
		$query = $this->wpdb->prepare(" " . $this->inventory_table . " SET 
			invoice_no = %d,
			vendor_name = %s,
			vendor_address = %s,
			p_name = %s,
			p_model_no = %s,
			p_qty = %d,
			p_rate = %d,
			p_total = %d,
                        p_adv = %d,
                        p_bal = %d,
                        inventory_date_updated = %s,
                        p_details = %s,
                        category_id= %d,
                        gid = %d",
			$invoice_no,
                        $vendor_name, $vendor_address,
                        $p_name, $p_model_no, $p_qty, $p_rate, $p_total, $p_adv, $p_bal, $p_ddate, 
			$p_details, $category_id, $gid);
                

                    
		
		if ($inventory_id) {
                     echo "<script type='text/javascript'>alert('Updating Data');</script>";
			$query = 'UPDATE ' . $query . $this->wpdb->prepare(' WHERE inventory_id=%d', $inventory_id);
                        //while updating the stock might also get reduced so lets check it
                        //we also need to delete the complete stock data if the model name rate or no is changed
                        $db_p_row=$this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->inventory_table . ' WHERE  inventory_id = %d', $inventory_id));
                        //getting stock qty
                        $db_st_qty=$this->wpdb->get_var($this->wpdb->prepare('SELECT s_qty FROM ' . $this->stock_table . ' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $db_p_row->p_model_no, $db_p_row->p_name, $db_p_row->p_rate, $gid));
                        //gettting stock data
                        $db_s_row=$this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->stock_table . ' WHERE  s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $p_model_no, $p_name, $p_rate, $gid));
                         echo "<script type='text/javascript'>alert('Database Stock Quantity=$db_st_qty');</script>";
                        
                         //new content
                        if($p_model_no!=$db_p_row->p_model_no || $p_name!=$db_p_row->p_name || $p_rate!=$db_p_row->p_rate){
                            echo "<script type='text/javascript'>alert('New data is found... creating new stock with qty=$p_qty');</script>"; 
                           
                            
                            //check if db has a similar data if not then a new stock will be added
                            if($p_model_no!=$db_s_row->s_model_no || $p_name!=$db_s_row->stock_name || $p_rate!=$db_s_row->s_rate){                                                           
                                   //the stock query  
                                $user_id = get_current_user_id();
                 echo "<script type='text/javascript'>alert('NOT Matching withh data in stock so creating a new stock');</script>"; 
                   $siquery = $this->wpdb->prepare(" " . $this->stock_table . " SET 
			s_model_no = %s,
			stock_name = %s,
			s_rate = %d,                        
			s_qty = %d,
                        category_id= %d,
                        inventory_date_updated = %s, 
                        gid = %d",
                        $p_model_no, $p_name, $p_rate, $p_qty, 
			$category_id, $p_ddate, $gid);
                   
                            //then a new stock must be created
                            //also execute the stock query
                            //create a new stock
                            $user_id = get_current_user_id();
                             $siquery = 'INSERT INTO' . $siquery . $this->wpdb->prepare(", user_id = %d,
				inventory_date_added = %s",
				$user_id, $now);
                             
                             $this->wpdb->query($siquery);
                            }else{
                                       $s_qty = $db_s_row->s_qty + $p_qty;
                                       
                                                                         //the stock query  
                
                   $siquery = $this->wpdb->prepare(" " . $this->stock_table . " SET 
			s_model_no = %s,
			stock_name = %s,
			s_rate = %d,                        
			s_qty = %d,
                        category_id= %d,
                        inventory_date_updated = %s, 
                        gid = %d",
                        $p_model_no, $p_name, $p_rate, $s_qty, 
			$category_id, $p_ddate, $gid); 
                                       
                               $siquery = 'UPDATE ' . $siquery . $this->wpdb->prepare(' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $p_model_no, $p_name, $p_rate, $gid);    
                               $this->wpdb->query($siquery); 
                            } 
                  
                   $s_qty=$db_st_qty - $db_p_row->p_qty;
                                      //the stock query  
                
                   $squery = $this->wpdb->prepare(" " . $this->stock_table . " SET 
			s_model_no = %s,
			stock_name = %s,
			s_rate = %d,                        
			s_qty = %d,
                        category_id= %d,
                        inventory_date_updated = %s, 
                        gid = %d",
                        $db_p_row->p_model_no, $db_p_row->p_name, $db_p_row->p_rate, $s_qty, 
			$db_p_row->category_id, $db_p_row->p_ddate, $gid);  
                             
                            $squery = 'UPDATE ' . $squery . $this->wpdb->prepare(' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $db_p_row->p_model_no, $db_p_row->p_name, $db_p_row->p_rate, $gid);
                      
                        }
                        else{
                            if($p_qty < $db_p_row->p_qty){
                                 echo "<script type='text/javascript'>alert('Product quantity is less discovered. Taking necessary steps');</script>";
                                //so now the stock qty has to be deducted
                                $s_qty=$db_st_qty-($db_p_row->p_qty - $p_qty);
                                  
                                       //the stock query  
                
                   $squery = $this->wpdb->prepare(" " . $this->stock_table . " SET 
			s_model_no = %s,
			stock_name = %s,
			s_rate = %d,                        
			s_qty = %d,
                        category_id= %d,
                        inventory_date_updated = %s, 
                        gid = %d",
                        $p_model_no, $p_name, $p_rate, $s_qty, 
			$category_id, $p_ddate, $gid);
                   
                   echo "<script type='text/javascript'>alert('Stock Quantity=$s_qty=$db_st_qty-$db_p_row->p_qty - $p_qty');</script>";
                                
                                $squery = 'UPDATE ' . $squery . $this->wpdb->prepare(' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $p_model_no, $p_name, $p_rate, $gid);
                            }

                            if($p_qty > $db_p_row->p_qty){
                                 echo "<script type='text/javascript'>alert('Product quantity more discovered. Taking necessary steps');</script>";
                                //so now the stock qty has to be deducted
                                $s_qty=$db_st_qty + ($p_qty - $db_p_row->p_qty) ;
                                
                                       //the stock query  
                
                   $squery = $this->wpdb->prepare(" " . $this->stock_table . " SET 
			s_model_no = %s,
			stock_name = %s,
			s_rate = %d,                        
			s_qty = %d,
                        category_id= %d,
                        inventory_date_updated = %s, 
                        gid = %d",
                        $p_model_no, $p_name, $p_rate, $s_qty, 
			$category_id, $p_ddate, $gid);
                                
                                 echo "<script type='text/javascript'>alert('Stock Quantity=$s_qty=$db_st_qty + $p_qty - $db_p_row->p_qty');</script>";
                                
                                $squery = 'UPDATE ' . $squery . $this->wpdb->prepare(' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $p_model_no, $p_name, $p_rate, $gid);

                            }                            
                        }
                        
		} else {
                    $user_id = get_current_user_id();
                        //first we search for similar product in database then add if so or else a new entry
                          $db_st_qty=$this->wpdb->get_var($this->wpdb->prepare('SELECT s_qty FROM ' . $this->stock_table . ' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $p_model_no, $p_name, $p_rate, $gid));
                           
                          if($db_st_qty!=null){
                                                $s_qty=$db_st_qty+$s_qty;//updated qunantity
                                                       //the stock query  
                
                   $squery = $this->wpdb->prepare(" " . $this->stock_table . " SET 
			s_model_no = %s,
			stock_name = %s,
			s_rate = %d,                        
			s_qty = %d,
                        category_id= %d,
                        inventory_date_updated = %s, 
                        gid = %d",
                        $p_model_no, $p_name, $p_rate, $s_qty, 
			$category_id, $p_ddate, $gid);
                   
                                           //also execute the stock query
                        $squery = 'UPDATE ' . $squery . $this->wpdb->prepare(' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $p_model_no, $p_name, $p_rate, $gid);
                                           }
                                           else{//new entry
                                                      //the stock query  
                
                   $squery = $this->wpdb->prepare(" " . $this->stock_table . " SET 
			s_model_no = %s,
			stock_name = %s,
			s_rate = %d,                        
			s_qty = %d,
                        category_id= %d,
                        inventory_date_updated = %s, 
                        gid = %d",
                        $p_model_no, $p_name, $p_rate, $s_qty, 
			$category_id, $p_ddate, $gid);
                   
                                           //also execute the stock query
                        $squery = 'INSERT INTO' . $squery . $this->wpdb->prepare(", user_id = %d,
				inventory_date_added = %s",
				$user_id, $now);
                                           }
                                           
			
			
			$query = 'INSERT INTO' . $query . $this->wpdb->prepare(", user_id = %d,
				inventory_date_added = %s",
				$user_id, $now);
		}
		
		$this->wpdb->query($query);
                $this->wpdb->query($squery);
		
		if ( ! $inventory_id) {
			$inventory_id = $this->wpdb->insert_id;
		}
		
		return ( ! $this->wpdb->last_error) ? $inventory_id : FALSE;
	}
	
	/**
	 * Method to save a reserved item
	 * @param integer $inventory_id
	 * @param integer $quantity (always as a positive)
	 */
	public function save_reserve($inventory_id, $quantity) {
		$query = $this->wpdb->prepare('UPDATE ' . $this->inventory_table . ' 
						SET inventory_quantity = (inventory_quantity - %d),
							 inventory_quantity_reserved = (inventory_quantity_reserved + %d) WHERE inventory_id = %d',
					$quantity, $quantity, $inventory_id
		);
		
		do_action('ncm_save_reserve', $inventory_id, $quantity);
		
		return $this->wpdb->query($query);
	}
	
	/** 
	 * Save the images associated with a given inventory item
	 * @param integer $inventory_id - id of the relevant inventory item
	 * @param array $images - array of image urls
	 * @param array $sort - array of indexes for sort order
	 */
	public function save_images($inventory_id, $images, $sort = array()) {
		// Ensure sort array has enogh entries
		if ( ! $sort) {
			$count = 0;
			foreach($images AS $i) {
				$sort[] = $count++;
			}
		}
		
		// Remove all existing images
		$this->delete_images($inventory_id);
		
		// Initialize the sort order
		$sort_order = 0;
		
		// Iterate through the images in the relevant sort order
		foreach ($sort AS $i) {
			
			// Due to the way the sort array comes over, not all items may be filled
			if (empty($images[$i])) {
				continue;	
			}
			
			$image = $images[$i];

			// Images can be id's as well...
			if ( ! is_numeric($image)) {
				// Get the attachment id
				$post_id = $this->get_attachment_id_from_url($image);
			} else {
				$post_id = (int)$image;
				$image = wp_get_attachment_url($post_id);
			}
			
			
			// Now - get large size, medium, plus thumbnail
			extract($this->get_image_urls($post_id));
			
			$query = $this->wpdb->prepare(
				'INSERT INTO ' . $this->image_table . '
					SET inventory_id = %d,
					post_id = %d,
					image = %s,
					thumbnail = %s,
					medium = %s,
					large = %s,
					image_sort_order = %d', 
				$inventory_id, $post_id, $image,
				$thumbnail, $medium, $large, $sort_order++	
			);
			
			$this->wpdb->query($query);
			
			if ($this->get_error()) {
				$this->error = $this->get_error();
			}
		}
	}
	
	/**
	 *
	 * Save the media associated with a given inventory item
	 * @param integer $inventory_id - id of the relevant inventory item
	 * @param array $media - array of media urls
	 * @param array $sort - array of indexes for sort order
	 */
	public function save_media($inventory_id, $media, $media_title, $sort = array()) {
		
		// Ensure sort array has enogh entries
		if ( ! $sort) {
			$count = 0;
			foreach($media AS $i) {
				$sort[] = $count++;
			}
		}
	
		// Remove all existing media
		$this->delete_media($inventory_id);
	
		// Initialize the sort order
		$sort_order = 0;
	
		// Iterate through the images in the relevant sort order
		foreach ($sort AS $i) {
				
			// Due to the way the sort array comes over, not all items may be filled
			if (empty($media[$i])) {
				continue;
			}
				
			$url = $media[$i];
			$title = $media_title[$i];
			
			if ( ! $title) {
				$title = $url;
			}
				
			$query = $this->wpdb->prepare(
				'INSERT INTO ' . $this->media_table . '
					SET inventory_id = %d,
					media_title = %s,
					media = %s,
					media_sort_order = %d',
				$inventory_id, $title, $url, $sort_order++
			);
			
			$this->wpdb->query($query);
		}
	}
	
	/**
	 * Delete an inventory item
	 * @param integer $inventory_id
	 */
	function delete($inventory_id) {
		// Remove item
		$success = $this->wpdb->query($this->wpdb->prepare("DELETE FROM " . $this->inventory_table . ' WHERE inventory_id = %d', $inventory_id . ' LIMIT 1'));

		if ( $success ) {
			$images_success = $this->delete_images( $inventory_id );
			$media_success  = $this->delete_media( $inventory_id );

			if ( ! $images_success ) {
				self::$message = $this->__( 'Inventory item deleted, but the images could not be deleted.' ) . '<br />';
			}

			if ( ! $media_success ) {
				self::$message = $this->__( 'Inventory item deleted, but the media could not be deleted.' ) . '<br />';
			}
		} else {
			if ( $success === 0 ) {
				self::$message = $this->__( 'The inventory item is already deleted.' );
			} else {
				self::$message = $this->__( 'Inventory item could not be deleted.' );
			}
		}
		
		return $success;
	}
	
	/**
	 * Delete all image for an inventory item
	 * @param integer $inventory_id
	 */
	function delete_images($inventory_id) {
		// Remove all existing media
		return $this->wpdb->query($this->wpdb->prepare("DELETE FROM " . $this->image_table . '  WHERE inventory_id = %d', $inventory_id));
	}
	
	/**
	 * Delete all media for an inventory item
	 * @param unknown_type $inventory_id
	 */
	function delete_media($inventory_id) {
		// Remove all existing media
		return $this->wpdb->query($this->wpdb->prepare("DELETE FROM " . $this->media_table . '  WHERE inventory_id = %d', $inventory_id));
	}
	
	/**
	 * Retrieve the various sizes (large, medium, thumbnail) in one convenient location
	 * @param integer - $post_id
	 * @return array of image urls
	 */
	public function get_image_urls($post_id = NULL, $resize = FALSE) {
		
		$thumbnail = wp_get_attachment_image_src($post_id, 'thumbnail');		
		$thumbnail = ( ! empty($thumbnail[0])) ? $thumbnail[0] : NULL;
			
		$medium = wp_get_attachment_image_src($post_id, 'medium');
		$medium = ( ! empty($medium[0])) ? $medium[0] : NULL;
			
		$large= wp_get_attachment_image_src($post_id, 'large');
		$large = ( ! empty($large[0])) ? $large[0] : NULL;
		
		if ($resize) {
			// Get upload dir info for file paths
			$upload_dir = wp_upload_dir();
			$upload_url = $upload_dir['baseurl'];
			$upload_dir = $upload_dir['basedir'];
			
			// Use the full file as the base for thumb generation
			$full = wp_get_attachment_image_src($post_id, 'full');
			$full = ( ! empty($full[0])) ? $full[0] : NULL;
			$full_file = str_replace($upload_url, $upload_dir, $full);
			
			// Generate thumbs
			$results = wp_generate_attachment_metadata($post_id, $full_file);
			
			// If success, then overload the variables
			if ( ! empty($results['sizes'])) {
				$base_url = substr($full, 0, strripos($full, '/')+1);
				$results = $results['sizes'];
				$large = ( ! empty($results['large'])) ? $base_url . $results['large']['file'] : $full;
				$medium = ( ! empty($results['medium'])) ? $base_url . $results['medium']['file'] : $full;
				$thumbnail = ( ! empty($results['thumbnail'])) ? $base_url . $results['thumbnail']['file'] : $full;	
			}
		}
		
		return array(
			'large'		=> $large,
			'medium'	=> $medium,
			'thumbnail'	=> $thumbnail
		);
	}
	
	/**
	 * Function to retrieve the post id based on the image url
	 * @param string $attachment_url
	 * @return integer $post_id - post id that the image belongs to
	 */
	public function get_attachment_id_from_url($attachment_url = NULL) {
		
		$attachment_id = NULL;
	
		// If there is no url, return.
		if ( ! $attachment_url) {
			return;
		}
	
		// Get the upload directory paths
		$upload_dir_paths = wp_upload_dir();
	
		// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
		if (strpos($attachment_url, $upload_dir_paths['baseurl']) !== FALSE ) {
	
			// If this is the URL of an auto-generated thumbnail, get the URL of the original image
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
	
			// Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
	
			// Finally, run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id = $this->wpdb->get_var( 
				$this->wpdb->prepare("SELECT p.ID 
					FROM " . $this->wpdb->posts . " AS p
					INNER JOIN " . $this->wpdb->postmeta . " AS pm ON p.ID = pm.post_id 
					WHERE pm.meta_key = '_wp_attached_file' 
						AND pm.meta_value = '%s' 
						AND p.post_type = 'attachment'", 
					$attachment_url)
			);
	
		}
	
		return $attachment_id;
	}
	
	/**
	 * Utility function to regenerate thumbnails in the event the user has changed media sizes
	 * @returns integer $count - count of images rebuilt
	 */
	public function rebuild_image_thumbs() {
		
		$images = $this->wpdb->get_results("SELECT * FROM " . $this->image_table);
		$count = 0;
	
		foreach($images as $image) {
			extract($this->get_image_urls($image->post_id, TRUE));
			$query = $this->wpdb->prepare(
				"UPDATE " . $this->image_table . " 
					SET thumbnail=%s, medium = %s, large=%s WHERE image_id=%d", $thumbnail, $medium, $large, $image->image_id);
			$count+= $this->wpdb->query($query);
		}
		
		return $count;
	}
	
	public function get_message() {
		return $this->message;
	}
}
