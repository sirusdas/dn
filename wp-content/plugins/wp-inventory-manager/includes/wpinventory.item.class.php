<?php

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * Class for accessing inventory items
 * @author - Alpha Channel Group
 * @package - WPInventory
 * @copyright 2013 - Alpha Channel Group
 */
class WPIMItem extends WPIMDB {
	
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
				'page_size'			=> WPIMCore::$config->get('page_size'),  // Unlimited
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

		$args = apply_filters('wpim_query_item_args', $args);
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
                        //echo "<script type='text/javascript'>alert('Search1  is called');</script>";
                        //$where = $this->append_where($where, $this->parse_date_search($search2,$search1));
		}
                
                if ($search1||$search2) {
                               // echo "<script type='text/javascript'>alert('Search1  is called');</script>";
			$where = $this->append_where($where, $this->parse_date_search($search2,$search1));
		}                 
                
		
		//$order = ($order) ? ' ORDER BY ' . $order : ' ';
		$order = ' ORDER BY order_no DESC';
                
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
		return apply_filters('wpim_get_items', $items);
	} 
	
	/**
	 * Get an array containing all of the inventory item fields in the database
	 * @return array
	 */
	public static function get_fields() {
		return array(
			'inventory_id',
			'order_no',
                        'c_no',
                        'date',
                        'd_date',
                        'c_fname',
                        'c_lname',
                        'c_gender',
                        'c_add',
                        'c_city',
                        'c_city_pin',
                        'c_email',
                        'c_birth',
                        'c_anni',
                        'f_count',
                        'l_count',
                        'f_price',
                        'l_price',
                        'others',
                        'adj',
                        'total',
                        'adv',
                        'bal',
                        'status',
                        'plstats',
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
        
        public static function get_date_searchable_fields() {
		$no_search = array(
			'inventory_id',
			'inventory_slug',
			'inventory_sort_order',
			'category_id',
			'user_id',
		);
		
		$fields = self::get_fields();
		
		foreach($fields AS $key=>$field) {
			if (in_array($field, $no_search)) {
				unset($fields[$key]);
			}
		}
		
		return $fields;
	}
	/////////////////////////////////////////////////////////////mycode ends///////////////////////////////search by date ends///////////////////////////////////////////        
	        
	
	/**
	 * Get specific inventory item
	 * @param integer $inventory_id
	 * @return object
	 */
	public function get($inventory_id) {
		return $this->parseRowFromDb($this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->inventory_table . ' WHERE inventory_id = %d', $inventory_id)));
	}
        	public function getf($order_no) {
                global $current_user;
                global $wpdb;
                $sirus=$current_user->user_login;
                $gid = $wpdb->get_var( "SELECT gid FROM $wpdb->users where user_login='$sirus'" );
                
		//return $this->parseFromDb($this->wpdb->get_results($this->wpdb->prepare('SELECT * FROM ' . $this->frame_table . ' WHERE order_no= %d', $order_no . ' AND gid = %d', $gid)));
	
                
               $output=$this->wpdb->get_results($this->wpdb->prepare('SELECT * FROM ' . $this->frame_table . ' WHERE order_no = %d', $order_no . ' AND gid = %d', $gid));
                        //var_dump($output);
                        return $output;
                }
        	public function getl($order_no) {
                global $current_user;
                global $wpdb;
                    $sirus=$current_user->user_login;
                $gid = $wpdb->get_var( "SELECT gid FROM $wpdb->users where user_login='$sirus'" );
                    //$output = "<script>console.log( 'Order ID inside getl: " . $order_no . "' );</script>";
                      //  echo $output;
                        $output=$this->wpdb->get_results($this->wpdb->prepare('SELECT * FROM ' . $this->lens_table . ' WHERE order_no = %d', $order_no . ' AND gid = %d', $gid));
                        //var_dump($output);
                        return $output;
	}
        
        public function geto() {
                global $current_user;
                global $wpdb;
                    $sirus=$current_user->user_login;
                $gid = $wpdb->get_var( "SELECT gid FROM $wpdb->users where user_login='$sirus'" );
                    //$output = "<script>console.log( 'Order ID inside getl: " . $order_no . "' );</script>";
                      //  echo $output;
                        $output=$this->wpdb->get_var($this->wpdb->prepare('SELECT MAX(order_no) FROM ' . $this->inventory_table . ' where gid = %d', $gid));
                        //var_dump($output);
                        $output1 = "<script>console.log( 'geto executed " . $output . "' );</script>";
                        echo $output1;                        
                        return $output;

	}
        //my code 
          
        //my code ends
	
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
        
        public function getdbStatus($order_no){
          return $this->wpdb->get_var("select status from wp_wpinventory_item where order_no=".$order_no);  
        }
        
        public function getdbDatas($order_no,$f_nos){
           return $this->wpdb->get_results("select Distinct f_brand, f_model, f_rate, f_sp from wp_wpinventory_frame where order_no=".$order_no . " AND f_nos=".$f_nos);
        }
        
        public function getdbLensDatas($order_no,$l_nos){
           return $this->wpdb->get_results("select Distinct l_brand, l_model, l_rate, l_sp from wp_wpinventory_lens where order_no=".$order_no . " AND l_nos=".$l_nos);
        }        
        
        public function getstockQty($s_name,$s_m_no,$s_rate,$c_id) {
            return $this->wpdb->get_var( "Select s_qty from wp_netrastock_item where stock_name='".$s_name."' AND s_model_no='".$s_m_no."' AND s_rate=".$s_rate."' AND category_id=".$c_id);
        }
        
        public function getupdateStockQuery($qty,$s_name,$s_m_no,$s_rate,$c_id) {
          
                $stQuery = "Update ". $this->wpdb->prepare(" wp_netrastock_item SET s_qty = %d",$qty);
                $stQuery=$stQuery . $this->wpdb->prepare(' WHERE stock_name=%s AND s_model_no=%s AND s_rate=%d AND category_id=%d ', $s_name, $s_m_no, $s_rate, $c_id );
                
                return $stQuery;
        }
        
        public function getFrameQuery($order_no,$f_brand1,$f_model1,$f_size1,$f_color1,$f_sp1,$f_rate1,$f_nos,$gid) {
            
        $query1 = $this->wpdb->prepare(" " . $this->frame_table . " SET
			order_no = %d,
                        f_brand = %s,
                        f_model = %s,
                        f_size = %d,
                        f_color = %s,
			f_sp = %d,
                        f_rate = %d,
                        f_nos = %d,
                        gid = %d",
	$order_no,$f_brand1,$f_model1,$f_size1,$f_color1,$f_sp1,$f_rate1,$f_nos,$gid);
        
        return $query1;
            
        }
        
        public function getLensQuery($order_no,$l_brand1,$l_model1,$l_rate1,$r1,$r_size1,$r_tint1,$l1,$l_size1,$l_tint1,$spec1,$note1,$l_sp1,$l_nos,$gid) {
            
                $queryL1 = $this->wpdb->prepare(" " . $this->lens_table . " SET
			order_no = %d,
                        l_brand = %s,
                        l_model = %s,
                        l_rate = %d,
                        r = %d,
                        r_size = %d,
                        r_tint = %d,
                        l = %d,
			l_size = %d,
                        l_tint = %d,
                        spec = %s,
                        note = %s,
                        l_sp = %d,
                        l_nos = %d,
                        gid = %d",
			$order_no,$l_brand1,$l_model1,$l_rate1,$r1,$r_size1,$r_tint1,$l1,$l_size1,$l_tint1,$spec1,$note1,$l_sp1,$l_nos,$gid);   
                return $queryL1;
            
        }
        
        public function updateDB($stQuery) {
            
            $this->wpdb->query($stQuery); 
            
        }
        
        public function displayAlert($param) {
            echo $param;    
        }
        
	public function save($data) {
        
		extract($data);
		$output = "<script>console.log( 'Sirus Objects: " . extract($data). "' );</script>";
                        $this->displayAlert($output);
		$now = $this->date_to_mysql(current_time('timestamp'), TRUE);
		$plstats=0;
		$inventory_slug = $this->validate_slug('inventory', $inventory_slug, $c_fname, $inventory_id);
		$query = $this->wpdb->prepare(" " . $this->inventory_table . " SET
			order_no = %d,
                        c_no = %d,
                        date = %s,
                        d_date = %s,
                        c_fname = %s,
                        c_lname = %s,
                        c_gender = %s,
                        c_add = %s,
                        c_city = %s,
                        c_city_pin = %d,
                        c_email = %s,
                        c_birth = %s,
                        c_anni = %s,
                        f_count = %d,
                        l_count = %d,
                        f_price = %d,
                        l_price = %d,
                        others = %d,
                        adj = %d,
                        total = %d,
                        adv = %d,
                        bal = %d,
                        status = %s,
                        r_d_sph = %d,
                        r_d_cyl = %d,
                        r_d_axis = %d,
                        r_d_add = %d,
                        r_d_va = %d,
                        r_n_sph = %d,
                        r_n_cyl = %d,
                        r_n_axis = %d,
                        r_n_add = %d,
                        r_n_va = %d,                   
                        l_d_sph = %d,
                        l_d_cyl = %d,
                        l_d_axis = %d,
                        l_d_add = %d,
                        l_d_va = %d,
                        l_n_sph = %d,
                        l_n_cyl = %d,
                        l_n_axis = %d,
                        l_n_add = %d,
                        l_n_va = %d,
                        r_lpd = %d,
                        l_lpd = %d,
                        o_desc = %s,
                        ref_by = %s,
			category_id = %d,                        
			inventory_slug = %s,
			inventory_sort_order = %d,	
			inventory_date_updated = %s,
			gid = %d",
			$order_no, $c_no, $date, $d_date, $c_fname,
			$c_lname, $c_gender, $c_add, $c_city, $c_city_pin, $c_email,
			$c_birth, $c_anni, $f_count, $l_count, $f_price,
			$l_price, $others, $adj, $total, $adv, $bal, $status, $r_d_sph,
			$r_d_cyl, $r_d_axis, $r_d_add ,$r_d_va, $r_n_sph, $r_n_cyl,
			$r_n_axis, $r_n_add, $r_n_va, $l_d_sph, $l_d_cyl, $l_d_axis, $l_d_add, $l_d_va, $l_n_sph,
			$l_n_cyl, $l_n_axis, $l_n_add, $l_n_va ,$r_lpd, $l_lpd, $o_desc, $ref_by, $category_id,
			$inventory_slug, $inventory_sort_order, $now, $gid);
                   
		
		if ($inventory_id) {
			//$query = 'UPDATE ' . $query . $this->wpdb->prepare(' WHERE inventory_id=%d', $inventory_id);

                if($f_model0!=""){
                 $f_nos=0;$c_id=1;
                 
                 $query0 = $this->getFrameQuery($order_no, $f_brand0, $f_model0, $f_size0, $f_color0, $f_sp0, $f_rate0, $f_nos, $gid);
                 $plstats= $total - $f_rate0;
//                 $query0 = $this->wpdb->prepare(" " . $this->frame_table . " SET
//			order_no = %d,
//                        f_brand = %s,
//                        f_model = %s,
//                        f_size = %d,
//                        f_color = %s,
//			f_sp = %d,
//                        f_rate = %d,
//                        f_nos = %d,
//                        gid = %d",
//	$order_no,$f_brand0,$f_model0,$f_size0,$f_color0,$f_sp0,$f_rate0,$f_nos,$gid);
                 
                 

               //check the current status and also the database status if exists.
                 //$getdbstatus = $this->wpdb->get_var("select status from wp_wpinventory_item where order_no=".$order_no);
                
                 $getdbstatus = $this->getdbStatus($order_no);
                 
                 //mark
                 if($status=="Canceled" && $getdbstatus=="Delivered"){
                            $plstats=0;
                            $getdbdatas = $this->getdbDatas($order_no,$f_nos);
                            foreach($getdbdatas as $getdbdata){
                                $dbdata1[]=$getdbdata->f_brand;
                                $dbdata1[]=$getdbdata->f_model;
                                $dbdata1[]=$getdbdata->f_rate;
                                $dbdata1[]=$getdbdata->f_sp;
                                $output = "<script>alert( 'The database brand=".$getdbdata->f_brand."' );</script>";
                                $this->displayAlert($output);
                            }                     
                     
                             $dbs_qty1 = $this->getstockQty($dbdata1[0],$dbdata1[1],$dbdata1[2],$c_id);
                             
                             $output ="<script>alert( 'Previous db qty= '".$dbs_qty1." );</script>";
                             $this->displayAlert($output);
                                 $dbs_qty1=$dbs_qty1+1;
                                 //now lets update it
                                 
                                 $stQuery1 = $this->getupdateStockQuery($dbs_qty1,$dbdata1[0],$dbdata1[1],$dbdata1[2],$c_id);

                                 $this->updateDB($stQuery1);                 
                     
                 }
                 else{
                    if($status=="Delivered" || $getdbstatus=="Delivered"){
                          $output = "<script>alert( 'Status: Delivered' );</script>";
                          $this->displayAlert($output);
                        //this is update part so we also need to check if the model no and product name matches with the previous
                        //if not then add back the stock if it was deducted and delete from the current stock
                        
                        $output = "<script>alert( 'The database Status=".$getdbstatus." and the current Status=".$status."' );</script>";
                        $this->displayAlert($output);
                        //now check if is delievered
                        if($getdbstatus=="Delivered"){
                            //time to check for the model no and name
                            $output ="<script>alert( 'Database Status: Delivered' );</script>";
                            $this->displayAlert($output);
                           // $getdbdatas = $this->wpdb->get_results("select Distinct f_brand, f_model, f_rate from wp_wpinventory_frame where order_no=".$order_no . " AND f_nos=0");
                            $getdbdatas = $this->getdbDatas($order_no,$f_nos);
                            foreach($getdbdatas as $getdbdata){
                                $dbdata1[]=$getdbdata->f_brand;
                                $dbdata1[]=$getdbdata->f_model;
                                $dbdata1[]=$getdbdata->f_rate;
                                $dbdata1[]=$getdbdata->f_sp;
                                $output = "<script>alert( 'The database brand=".$getdbdata->f_brand."' );</script>";
                                $this->displayAlert($output);
                            }
                            //now its time to check if the input data matches with the data in db
                            $output = "<script>alert( 'Previous Data found name=".$dbdata1[0]." model=".$dbdata1[1]."' );</script>";
                            $this->displayAlert($output);
                            if($dbdata1[0]==$f_brand0 && $dbdata1[1]==$f_model0 && $dbdata1[2]==$f_rate0 ){
                                //everything looks good lets update the stock
/*
     //need to also update the other database lets try that
                             //now fisrst get the stock qty
                             $dbs_qty =$this->wpdb->get_var( "Select s_qty from wp_netrastock_item where stock_name='".$f_brand0."' AND s_model_no='".$f_model0."'" ); 
                             $output = "<script>alert( 'Entering dbs_qty: " . $dbs_qty . "' );</script>";
                             $this->displayAlert($output);
                             if($dbs_qty!==null && ($dbs_qty-1)>0){
                                 $dbs_qty=$dbs_qty-1;
                                 //now lets update it
                                 $stQuery = "Update ". $this->wpdb->prepare(" wp_netrastock_item SET
                                     s_qty = %d",
                                     $dbs_qty);
                                 $stQuery=$stQuery . $this->wpdb->prepare(' WHERE stock_name=%s AND s_model_no=%s ', $f_brand0, $f_model0);
                                $this->wpdb->query($stQuery); 

                             } */
                         }//delivered

                         else{// long precess ..... product was  delievered and now the model or product name is changed.....
                            //time to check for the model no and name
                             
                             
                                                
                               //we first add the deleted stock ... this is used when the product is delivered and now the model or product name is changed
                             //$dbs_qty1 =$this->wpdb->get_var( "Select s_qty from wp_netrastock_item where stock_name='".$dbdata1[0]."' AND s_model_no='".$dbdata1[1]."' AND s_rate=".$dbdata1[2] ); 
                             
                             $dbs_qty1 = $this->getstockQty($dbdata1[0],$dbdata1[1],$dbdata1[2],$c_id);
                             
                             $output ="<script>alert( 'Previous db qty= '".$dbs_qty1." );</script>";
                             $this->displayAlert($output);
                                 $dbs_qty1=$dbs_qty1+1;
                                 //now lets update it
                                 
                                 $stQuery1 = $this->getupdateStockQuery($dbs_qty1,$dbdata1[0],$dbdata1[1],$dbdata1[2],$c_id);
                                 
//                                 $stQuery1 = "Update ". $this->wpdb->prepare(" wp_netrastock_item SET
//                                     s_qty = %d",
//                                     $dbs_qty1);
//                                 $stQuery1=$stQuery1 . $this->wpdb->prepare(' WHERE stock_name=%s AND s_model_no=%s AND s_rate=%d ', $dbdata1[0], $dbdata1[1], $dbdata1[2] );
                                
                                 $this->updateDB($stQuery1);
                                 //$this->wpdb->query($stQuery1); 
                                //once the data is added back its now time to update the current stock
                                //checking if current status is showing delivered
                                if($status=="Delivered"){
                                    //$dbs_qty2 =$this->wpdb->get_var( "Select s_qty from wp_netrastock_item where stock_name='".$f_brand0."' AND s_model_no='".$f_model0."' AND s_rate=".$f_rate0 ); 
                                    $dbs_qty2 = $this->getstockQty($f_brand0, $f_model0, $f_rate0, $c_id);
                                    $output = "<script>alert( 'Entering dbs_qty: " . $dbs_qty2 . "' );</script>";
                                    
                                    $this->displayAlert($output);
                                    if($dbs_qty2!==null && ($dbs_qty2-1)>=0){
                                        $dbs_qty2=$dbs_qty2-1;
                                        //now lets update it
                                        
                                        $stQuery2 = $this->getupdateStockQuery($dbs_qty2, $f_brand0, $f_model0, $f_rate0, $c_id);
                                        
//                                        $stQuery2 = "Update ". $this->wpdb->prepare(" wp_netrastock_item SET
//                                            s_qty = %d",
//                                            $dbs_qty2);
//                                        $stQuery2=$stQuery2 . $this->wpdb->prepare(' WHERE stock_name=%s AND s_model_no=%s AND s_rate=%d ', $f_brand0, $f_model0, $f_rate0);
                                       $this->updateDB($stQuery2);
                                        //$this->wpdb->query($stQuery2); 

                                    }                                
                                }// if ends 
                             }//else ends
                         }
                         else{
                                //need to also update the other database lets try that
                                //now fisrst get the stock qty
                               // $dbs_qty =$this->wpdb->get_var( "Select s_qty from wp_netrastock_item where stock_name='".$f_brand0."' AND s_model_no='".$f_model0."' AND s_rate=".$f_rate0  ); 
                                
                                $dbs_qty =$this->getstockQty($f_brand0, $f_model0, $f_rate0, $c_id);
                                
                                $output = "<script>alert( 'Frame0 Entering dbs_qty: " . $dbs_qty . "' );</script>";
                                $this->displayAlert($output);
                                if($dbs_qty!==null && ($dbs_qty-1)>0){
                                    $dbs_qty=$dbs_qty-1;
                                    //now lets update it
//                                    $stQuery = "Update ". $this->wpdb->prepare(" wp_netrastock_item SET
//                                        s_qty = %d",
//                                        $dbs_qty);
//                                    $stQuery=$stQuery . $this->wpdb->prepare(' WHERE stock_name=%s AND s_model_no=%s AND s_rate=%d ', $f_brand0, $f_model0, $f_rate0);
//                                   
                                    $stQuery = $this->getupdateStockQuery($dbs_qty, $f_brand0, $f_model0, $f_rate0, $c_id);
                                    
                                    $this->updateDB($stQuery);
                                   // $this->wpdb->query($stQuery); 

                                }                             
                             
                             }
                     } 
                     
                        $query0 = 'UPDATE '. $query0 . $this->wpdb->prepare(' WHERE order_no=%d AND f_nos=%d AND gid=%d ', $order_no, $f_nos, $gid);
                        
                       $this->updateDB($query0);
                       // $this->wpdb->query($query0);
                        
                        $output = "<script>console.log( 'Updating Frame0: " . $f_model0 . "' );</script>";
                        $this->displayAlert($output);
                    
                }}
               
                if($f_model1!=""){
                 $f_nos=1;$c_id=1;
                 
                 $query1 = $this->getFrameQuery($order_no,$f_brand1,$f_model1,$f_size1,$f_color1,$f_sp1,$f_rate1,$f_nos,$gid);
                 $plstats= $plstats - $f_rate1;
                 $getdbstatus = $this->getdbStatus($order_no);
                 
                 if($status=="Canceled" && $getdbstatus=="Delivered"){
                            $plstats=0;
                            $getdbdatas = $this->getdbDatas($order_no,$f_nos);
                            foreach($getdbdatas as $getdbdata){
                                $dbdata2[]=$getdbdata->f_brand;
                                $dbdata2[]=$getdbdata->f_model;
                                $dbdata2[]=$getdbdata->f_rate;
                                $dbdata2[]=$getdbdata->f_sp;
                                $output = "<script>alert( 'The database brand=".$getdbdata->f_brand."' );</script>";
                                $this->displayAlert($output);
                            }                     
                     
                             $dbs_qty2 = $this->getstockQty($dbdata2[0],$dbdata2[1],$dbdata2[2],$c_id);
                             
                             $output ="<script>alert( 'Previous db qty= '".$dbs_qty2." );</script>";
                             $this->displayAlert($output);
                                 $dbs_qty2=$dbs_qty2+1;
                                 //now lets update it
                                 
                                 $stQuery2 = $this->getupdateStockQuery($dbs_qty2,$dbdata2[0],$dbdata2[1],$dbdata2[2],$c_id);

                                 $this->updateDB($stQuery2);                 
                     
                 }                 
                 else{
                    if($status=="Delivered" || $getdbstatus=="Delivered"){
                        if($getdbstatus=="Delivered"){ 
                            $getdbdatas1 = $this->getdbDatas($order_no,$f_nos);
                            foreach($getdbdatas1 as $getdbdata1){
                                $dbdata2[]=$getdbdata1->f_brand; $dbdata2[]=$getdbdata1->f_model; $dbdata2[]=$getdbdata1->f_rate;
                            }
                            if($dbdata2[0]==$f_brand1 && $dbdata2[1]==$f_model1 && $dbdata2[2]==$f_rate1 ){}//delivered
                            //or may be a new update
                         else{
                            if($dbdata2[0]!=""){ 
                                $output = "<script>alert( 'Frame 1 Records found and brand name=".$dbdata2[0]."' );</script>";
                                $this->displayAlert($output);
                             $dbs_qty1 = $this->getstockQty($dbdata2[0],$dbdata2[1],$dbdata2[2],$c_id);                          
                             $output = "<script>alert( 'Previous db qty= '".$dbs_qty1." );</script>";
                             $this->displayAlert($output);
                                 $dbs_qty1=$dbs_qty1+1;
                                 $stQuery1 = $this->getupdateStockQuery($dbs_qty1,$dbdata2[0],$dbdata2[1],$dbdata2[2],$c_id);
                                 $this->updateDB($stQuery1);
                            }
                            else{//new data entry must be done mark  
                                $output = "<script>alert( 'Frame 1 No Records found So inserting new data' );</script>";
                                $this->displayAlert($output);
                                    $f_nos=1; 
                                    $queryN = $this->getFrameQuery($order_no, $f_brand1, $f_model1, $f_size1, $f_color1, $f_sp1, $f_rate1, $f_nos, $gid);
                                    $queryN = 'INSERT INTO' . $queryN;
                                    $this->updateDB($queryN); 
                                }
                                if($status=="Delivered"){
                                    $dbs_qty2 = $this->getstockQty($f_brand1, $f_model1, $f_rate1, $c_id);
                                    $output = "<script>alert( 'Frame 1 Entering dbs_qty: " . $dbs_qty2 . "' );</script>";
                                    $this->displayAlert($output);
                                    if($dbs_qty2!==null && ($dbs_qty2-1)>=0){
                                        $dbs_qty2=$dbs_qty2-1; 
                                        $stQuery2 = $this->getupdateStockQuery($dbs_qty2, $f_brand1, $f_model1, $f_rate1, $c_id);
                                         $this->updateDB($stQuery2);
                                    } } 
                                    
                                    }//else ends
                         }
                         else{
                                $dbs_qty =$this->getstockQty($f_brand1, $f_model1, $f_rate1, $c_id);                              
                                $output = "<script>alert( 'Frame 1(ELSE) Entering dbs_qty: " . $dbs_qty . "' );</script>";
                                $this->displayAlert($output);
                                if($dbs_qty!==null && ($dbs_qty-1)>0){
                                    $dbs_qty=$dbs_qty-1;       
                                    $stQuery = $this->getupdateStockQuery($dbs_qty, $f_brand1, $f_model1, $f_rate1, $c_id);       
                                    $this->updateDB($stQuery);
                                }                             
                             }
                     }                     
                        $query1 = 'UPDATE '. $query1 . $this->wpdb->prepare(' WHERE order_no=%d AND f_nos=%d AND gid=%d ', $order_no, $f_nos, $gid);                  
                       $this->updateDB($query1);    
                        $output = "<script>console.log( 'Updating Frame1: " . $f_model1 . "' );</script>";
                        $this->displayAlert($output);               
                }}
                
                if($f_model2!=""){
                 $f_nos=2;$c_id=1;
                 
                 $query2 = $this->getFrameQuery($order_no,$f_brand2,$f_model2,$f_size2,$f_color2,$f_sp2,$f_rate2,$f_nos,$gid);
                 $plstats= $plstats - $f_rate2;
                 $getdbstatus = $this->getdbStatus($order_no);
                 if($status=="Canceled" && $getdbstatus=="Delivered"){
                            $plstats=0;
                            $getdbdatas = $this->getdbDatas($order_no,$f_nos);
                            foreach($getdbdatas as $getdbdata){
                                $dbdata3[]=$getdbdata->f_brand;
                                $dbdata3[]=$getdbdata->f_model;
                                $dbdata3[]=$getdbdata->f_rate;
                                $dbdata3[]=$getdbdata->f_sp;
                                $output = "<script>alert( 'The database brand=".$getdbdata->f_brand."' );</script>";
                                $this->displayAlert($output);
                            }                     
                     
                             $dbs_qty3 = $this->getstockQty($dbdata3[0],$dbdata3[1],$dbdata3[2],$c_id);
                             
                             $output ="<script>alert( 'Previous db qty= '".$dbs_qty3." );</script>";
                             $this->displayAlert($output);
                                 $dbs_qty3=$dbs_qty3+1;
                                 //now lets update it
                                 
                                 $stQuery3 = $this->getupdateStockQuery($dbs_qty3,$dbdata3[0],$dbdata3[1],$dbdata3[2],$c_id);

                                 $this->updateDB($stQuery3);                 
                     
                 }   
                 else{
                    if($status=="Delivered" || $getdbstatus=="Delivered"){
                        if($getdbstatus=="Delivered"){ 
                            $getdbdatas2 = $this->getdbDatas($order_no,$f_nos);
                            foreach($getdbdatas2 as $getdbdata2){
                                $dbdata3[]=$getdbdata2->f_brand; $dbdata3[]=$getdbdata2->f_model; $dbdata3[]=$getdbdata2->f_rate;
                            }
                            if($dbdata3[0]==$f_brand2 && $dbdata3[1]==$f_model2 && $dbdata3[2]==$f_rate2 ){}//delivered
                            //or may be a new update
                         else{
                            if($dbdata3[0]!=""){ 
                                $output = "<script>alert( 'Frame 1 Records found and brand name=".$dbdata3[0]."' );</script>";
                                $this->displayAlert($output);
                             $dbs_qty2 = $this->getstockQty($dbdata3[0],$dbdata3[1],$dbdata3[2], $c_id);                          
                             $output = "<script>alert( 'Previous db qty= '".$dbs_qty2." );</script>";
                             $this->displayAlert($output);
                                 $dbs_qty2=$dbs_qty2+1;
                                 $stQuery2 = $this->getupdateStockQuery($dbs_qty2,$dbdata3[0],$dbdata3[1],$dbdata3[2], $c_id);
                                 $this->updateDB($stQuery2);
                            }
                            else{//new data entry must be done mark  
                                $output = "<script>alert( 'Frame 2 No Records found So inserting new data' );</script>";
                                $this->displayAlert($output);
                                    $f_nos=2; 
                                    $queryN2 = $this->getFrameQuery($order_no, $f_brand2, $f_model2, $f_size2, $f_color2, $f_sp2, $f_rate2, $f_nos, $gid);
                                    $queryN2 = 'INSERT INTO' . $queryN2;
                                    $this->updateDB($queryN2); 
                                }
                                if($status=="Delivered"){
                                    $dbs_qty2 = $this->getstockQty($f_brand2, $f_model2, $f_rate2, $c_id);
                                    $output = "<script>alert( 'Frame 1 Entering dbs_qty: " . $dbs_qty2 . "' );</script>";
                                    $this->displayAlert($output);
                                    if($dbs_qty2!==null && ($dbs_qty2-1)>=0){
                                        $dbs_qty2=$dbs_qty2-1; 
                                        $stQuery2 = $this->getupdateStockQuery($dbs_qty2, $f_brand2, $f_model2, $f_rate2, $c_id);
                                         $this->updateDB($stQuery2);
                                    } } 
                                    
                                    }//else ends
                         }
                         else{
                                $dbs_qty =$this->getstockQty($f_brand2, $f_model2, $f_rate2, $c_id);                              
                                $output = "<script>alert( 'Frame 1(ELSE) Entering dbs_qty: " . $dbs_qty . "' );</script>";
                                $this->displayAlert($output);
                                if($dbs_qty!==null && ($dbs_qty-1)>0){
                                    $dbs_qty=$dbs_qty-1;       
                                    $stQuery = $this->getupdateStockQuery($dbs_qty, $f_brand2, $f_model2, $f_rate2, $c_id);       
                                    $this->updateDB($stQuery);
                                }                             
                             }
                     }                     
                        $query2 = 'UPDATE '. $query2 . $this->wpdb->prepare(' WHERE order_no=%d AND f_nos=%d AND gid=%d ', $order_no, $f_nos, $gid);                  
                       $this->updateDB($query2);    
                        $output = "<script>console.log( 'Updating Frame1: " . $f_model2 . "' );</script>";
                        $this->displayAlert($output);
                }}
                
                if($f_model3!=""){
                 $f_nos=3;$c_id=1;
                 
                 $query3 = $this->getFrameQuery($order_no,$f_brand3,$f_model3,$f_size3,$f_color3,$f_sp3,$f_rate3,$f_nos,$gid);
                 $plstats= $plstats - $f_rate1;
                 $getdbstatus = $this->getdbStatus($order_no);
                 if($status=="Canceled" && $getdbstatus=="Delivered"){
                            $plstats=0;
                            $getdbdatas = $this->getdbDatas($order_no,$f_nos);
                            foreach($getdbdatas as $getdbdata){
                                $dbdata4[]=$getdbdata->f_brand;
                                $dbdata4[]=$getdbdata->f_model;
                                $dbdata4[]=$getdbdata->f_rate;
                                $dbdata4[]=$getdbdata->f_sp;
                                $output = "<script>alert( 'The database brand=".$getdbdata->f_brand."' );</script>";
                                $this->displayAlert($output);
                            }                     
                     
                             $dbs_qty4 = $this->getstockQty($dbdata4[0],$dbdata4[1],$dbdata4[2],$c_id);
                             
                             $output ="<script>alert( 'Previous db qty= '".$dbs_qty4." );</script>";
                             $this->displayAlert($output);
                                 $dbs_qty4=$dbs_qty4+1;
                                 //now lets update it
                                 
                                 $stQuery4 = $this->getupdateStockQuery($dbs_qty4,$dbdata4[0],$dbdata4[1],$dbdata4[2],$c_id);

                                 $this->updateDB($stQuery4);                 
                     
                 }  
                 else{
                    if($status=="Delivered" || $getdbstatus=="Delivered"){
                        if($getdbstatus=="Delivered"){ 
                            $getdbdatas2 = $this->getdbDatas($order_no,$f_nos);
                            foreach($getdbdatas2 as $getdbdata2){
                                $dbdata3[]=$getdbdata2->f_brand; $dbdata3[]=$getdbdata2->f_model; $dbdata3[]=$getdbdata2->f_rate;
                            }
                            if($dbdata3[0]==$f_brand3 && $dbdata3[1]==$f_model3 && $dbdata3[2]==$f_rate3 ){}//delivered
                            //or may be a new update
                         else{
                            if($dbdata3[0]!=""){ 
                                $output = "<script>alert( 'Frame 1 Records found and brand name=".$dbdata3[0]."' );</script>";
                                $this->displayAlert($output);
                             $dbs_qty3 = $this->getstockQty($dbdata3[0],$dbdata3[1],$dbdata3[2], $c_id);                          
                             $output = "<script>alert( 'Previous db qty= '".$dbs_qty3." );</script>";
                             $this->displayAlert($output);
                                 $dbs_qty3=$dbs_qty3+1;
                                 $stQuery3 = $this->getupdateStockQuery($dbs_qty3,$dbdata3[0],$dbdata3[1],$dbdata3[2], $c_id);
                                 $this->updateDB($stQuery3);
                            }
                            else{//new data entry must be done mark  
                                $output = "<script>alert( 'Frame 3 No Records found So inserting new data' );</script>";
                                $this->displayAlert($output);
                                    $f_nos=3; 
                                    $queryN3 = $this->getFrameQuery($order_no, $f_brand3, $f_model3, $f_size3, $f_color3, $f_sp3, $f_rate3, $f_nos, $gid);
                                    $queryN3 = 'INSERT INTO' . $queryN3;
                                    $this->updateDB($queryN3); 
                                }
                                if($status=="Delivered"){
                                    $dbs_qty3 = $this->getstockQty($f_brand3, $f_model3, $f_rate3, $c_id);
                                    $output = "<script>alert( 'Frame 3 Entering dbs_qty: " . $dbs_qty3 . "' );</script>";
                                    $this->displayAlert($output);
                                    if($dbs_qty3!==null && ($dbs_qty3-1)>=0){
                                        $dbs_qty3=$dbs_qty3-1; 
                                        $stQuery3 = $this->getupdateStockQuery($dbs_qty3, $f_brand3, $f_model3, $f_rate3, $c_id);
                                         $this->updateDB($stQuery3);
                                    } } 
                                    
                                    }//else ends
                         }
                         else{
                                $dbs_qty =$this->getstockQty($f_brand3, $f_model3, $f_rate3, $c_id);                              
                                $output = "<script>alert( 'Frame 3(ELSE) Entering dbs_qty: " . $dbs_qty . "' );</script>";
                                $this->displayAlert($output);
                                if($dbs_qty!==null && ($dbs_qty-1)>0){
                                    $dbs_qty=$dbs_qty-1;       
                                    $stQuery = $this->getupdateStockQuery($dbs_qty, $f_brand3, $f_model3, $f_rate3, $c_id);       
                                    $this->updateDB($stQuery);
                                }                             
                             }
                     }                     
                        $query3 = 'UPDATE '. $query3 . $this->wpdb->prepare(' WHERE order_no=%d AND f_nos=%d AND gid=%d ', $order_no, $f_nos, $gid);                  
                       $this->updateDB($query3);    
                        $output = "<script>console.log( 'Updating Frame3: " . $f_model3 . "' );</script>";
                        $this->displayAlert($output);
                }}
                
                if($f_model4!=""){
                 $f_nos=4;$c_id=1;
                 
                 $query4 = $this->getFrameQuery($order_no,$f_brand4,$f_model4,$f_size3,$f_color4,$f_sp4,$f_rate4,$f_nos,$gid);
                 $plstats= $plstats - $f_rate4;
                 $getdbstatus = $this->getdbStatus($order_no);
                 if($status=="Canceled" && $getdbstatus=="Delivered"){
                            $plstats=0;
                            $getdbdatas = $this->getdbDatas($order_no,$f_nos);
                            foreach($getdbdatas as $getdbdata){
                                $dbdata5[]=$getdbdata->f_brand;
                                $dbdata5[]=$getdbdata->f_model;
                                $dbdata5[]=$getdbdata->f_rate;
                                $dbdata5[]=$getdbdata->f_sp;
                                $output = "<script>alert( 'The database brand=".$getdbdata->f_brand."' );</script>";
                                $this->displayAlert($output);
                            }                     
                     
                             $dbs_qty5 = $this->getstockQty($dbdata5[0],$dbdata5[1],$dbdata5[2],$c_id);
                             
                             $output ="<script>alert( 'Previous db qty= '".$dbs_qty5." );</script>";
                             $this->displayAlert($output);
                                 $dbs_qty5=$dbs_qty5+1;
                                 //now lets update it
                                 
                                 $stQuery5 = $this->getupdateStockQuery($dbs_qty5,$dbdata5[0],$dbdata5[1],$dbdata5[2],$c_id);

                                 $this->updateDB($stQuery5);                 
                     
                 } 
                 else{
                    if($status=="Delivered" || $getdbstatus=="Delivered"){
                        if($getdbstatus=="Delivered"){ 
                            $getdbdatas3 = $this->getdbDatas($order_no,$f_nos);
                            foreach($getdbdatas3 as $getdbdata3){
                                $dbdata4[]=$getdbdata3->f_brand; $dbdata4[]=$getdbdata3->f_model; $dbdata4[]=$getdbdata3->f_rate;
                            }
                            if($dbdata4[0]==$f_brand4 && $dbdata4[1]==$f_model4 && $dbdata4[2]==$f_rate4 ){}//delivered
                            //or may be a new update
                         else{
                            if($dbdata4[0]!=""){ 
                                $output = "<script>alert( 'Frame 1 Records found and brand name=".$dbdata4[0]."' );</script>";
                                $this->displayAlert($output);
                             $dbs_qty4 = $this->getstockQty($dbdata4[0],$dbdata4[1],$dbdata4[2], $c_id);                          
                             $output = "<script>alert( 'Previous db qty= '".$dbs_qty4." );</script>";
                             $this->displayAlert($output);
                                 $dbs_qty4=$dbs_qty4+1;
                                 $stQuery4 = $this->getupdateStockQuery($dbs_qty4,$dbdata4[0],$dbdata4[1],$dbdata4[2], $c_id);
                                 $this->updateDB($stQuery4);
                            }
                            else{//new data entry must be done mark  
                                $output = "<script>alert( 'Frame 4 No Records found So inserting new data' );</script>";
                                $this->displayAlert($output);
                                    $f_nos=4; 
                                    $queryN4 = $this->getFrameQuery($order_no, $f_brand4, $f_model4, $f_size3, $f_color4, $f_sp4, $f_rate4, $f_nos, $gid);
                                    $queryN4 = 'INSERT INTO' . $queryN4;
                                    $this->updateDB($queryN4); 
                                }
                                if($status=="Delivered"){
                                    $dbs_qty4 = $this->getstockQty($f_brand4, $f_model4, $f_rate4, $c_id);
                                    $output = "<script>alert( 'Frame 4 Entering dbs_qty: " . $dbs_qty4 . "' );</script>";
                                    $this->displayAlert($output);
                                    if($dbs_qty4!==null && ($dbs_qty4-1)>=0){
                                        $dbs_qty4=$dbs_qty4-1; 
                                        $stQuery4 = $this->getupdateStockQuery($dbs_qty4, $f_brand4, $f_model4, $f_rate4, $c_id);
                                         $this->updateDB($stQuery4);
                                    } } 
                                    
                                    }//else ends
                         }
                         else{
                                $dbs_qty =$this->getstockQty($f_brand4, $f_model4, $f_rate4, $c_id);                              
                                $output = "<script>alert( 'Frame 4(ELSE) Entering dbs_qty: " . $dbs_qty . "' );</script>";
                                $this->displayAlert($output);
                                if($dbs_qty!==null && ($dbs_qty-1)>0){
                                    $dbs_qty=$dbs_qty-1;       
                                    $stQuery = $this->getupdateStockQuery($dbs_qty, $f_brand4, $f_model4, $f_rate4, $c_id);       
                                    $this->updateDB($stQuery);
                                }                             
                             }
                     }                     
                        $query4 = 'UPDATE '. $query4 . $this->wpdb->prepare(' WHERE order_no=%d AND f_nos=%d AND gid=%d ', $order_no, $f_nos, $gid);                  
                       $this->updateDB($query4);    
                        $output = "<script>console.log( 'Updating Frame4: " . $f_model4 . "' );</script>";
                        $this->displayAlert($output);  
                }}
     //lens
               $output = "<script>console.log( 'Values for Lens1 R: " . $r0 . " L: " .$l0 . " ' );</script>";
                        $this->displayAlert($output);
                if($r0!="" && $l0!=""){
                 $l_nos=0;$c_id=2;
                 
                 $l_query1 = $this->getLensQuery($order_no,$l_brand0,$l_model0,$l_rate0,$r0,$r_size0,$r_tint0,$l0,$l_size0,$l_tint0,$spec0,$note0,$l_sp0,$l_nos,$gid); 
                 $plstats= $plstats - $l_rate0;
                 $getdbstatus = $this->getdbStatus($order_no);
                 if($status=="Canceled" && $getdbstatus=="Delivered"){
                            $plstats=0;
                            $getdbdatas0 = $this->getdbLensDatas($order_no,$l_nos);
                            foreach($getdbdatas0 as $getdbdata0){
                                $l_dbdata0[]=$getdbdata0->l_brand; $l_dbdata0[]=$getdbdata0->l_model; $l_dbdata0[]=$getdbdata0->l_rate;
                            }

                            if($l_dbdata0[0]!=""){ 
                                $output= "<script>alert( 'Lens0 Records found and brand name=".$l_dbdata0[0]."' );</script>";
								$this->displayAlert($output);
                             $l_dbs_qty0 = $this->getstockQty($l_dbdata0[0],$l_dbdata0[1],$l_dbdata0[2], $c_id);                          
                             $output= "<script>alert( 'Previous db qty= '".$l_dbs_qty0." );</script>";
							 $this->displayAlert($output);
                                 $l_dbs_qty0=$l_dbs_qty0+1;
                                 $l_stQuery0 = $this->getupdateStockQuery($l_dbs_qty0,$l_dbdata0[0],$l_dbdata0[1],$l_dbdata0[2], $c_id);
                                 $this->updateDB($l_stQuery0);
                            }                     
                 }
                 else{
                    if($status=="Delivered" || $getdbstatus=="Delivered"){
                        if($getdbstatus=="Delivered"){ 
                            $getdbdatas0 = $this->getdbLensDatas($order_no,$l_nos);
                            foreach($getdbdatas0 as $getdbdata0){
                                $l_dbdata0[]=$getdbdata0->l_brand; $l_dbdata0[]=$getdbdata0->l_model; $l_dbdata0[]=$getdbdata0->l_rate;
                            }
                            if($l_dbdata0[0]==$l_brand0 && $l_dbdata0[1]==$l_model0 && $l_dbdata0[2]==$l_rate0 ){}//delivered
                            //or may be a new update
                         else{
                            if($l_dbdata0[0]!=""){ 
                                $output= "<script>alert( 'Lens0 Records found and brand name=".$l_dbdata0[0]."' );</script>";
								$this->displayAlert($output);
                             $l_dbs_qty0 = $this->getstockQty($l_dbdata0[0],$l_dbdata0[1],$l_dbdata0[2], $c_id);                          
                             $output= "<script>alert( 'Previous db qty= '".$l_dbs_qty0." );</script>";
							 $this->displayAlert($output);
                                 $l_dbs_qty0=$l_dbs_qty0+1;
                                 $l_stQuery0 = $this->getupdateStockQuery($l_dbs_qty0,$l_dbdata0[0],$l_dbdata0[1],$l_dbdata0[2], $c_id);
                                 $this->updateDB($l_stQuery0);
                            }
                            else{//new data entry must be enetered in db
                                echo "<script>alert( 'Lens 0 No Records found So inserting new data' );</script>";
                                    $l_nos=0; 
                                    $l_queryN = $this->getLensQuery($order_no,$l_brand0,$l_model0,$l_rate0,$r0,$r_size0,$r_tint0,$l0,$l_size0,$l_tint0,$spec0,$note0,$l_sp0,$l_nos,$gid); 
                                    $l_queryN = 'INSERT INTO' . $l_queryN;
                                    $this->updateDB($l_queryN); 
                                }
                                if($status=="Delivered"){
                                    $l_dbs_qty2 = $this->getstockQty($l_brand0, $l_model0, $l_rate0, $c_id);
                                    $output = "<script>alert( 'Lens 0 Entering dbs_qty: " . $l_dbs_qty2 . "' );</script>";
                                    $this->displayAlert($output);
                                    if($l_dbs_qty2!==null && ($l_dbs_qty2-1)>=0){
                                        $l_dbs_qty2=$l_dbs_qty2-1; 
                                        $l_stQuery2 = $this->getupdateStockQuery($l_dbs_qty2, $l_brand0, $l_model0, $l_rate0, $c_id);
                                         $this->updateDB($l_stQuery2);
                                    } } 
                                    
                                    }//else ends
                         }
                         else{
                                $l_dbs_qty =$this->getstockQty($l_brand0, $l_model0, $l_rate0, $c_id);                              
                                $output = "<script>alert( 'Lens 0(ELSE) Entering l_dbs_qty: " . $l_dbs_qty . "' );</script>";
                                $this->displayAlert($output);
                                if($l_dbs_qty!==null && ($l_dbs_qty-1)>0){
                                    $l_dbs_qty=$l_dbs_qty-1;       
                                    $l_stQuery = $this->getupdateStockQuery($l_dbs_qty, $l_brand0, $l_model0, $l_rate0, $c_id);       
                                    $this->updateDB($l_stQuery);
                                }                             
                             }
                     }                     
                        $l_query0 = 'UPDATE '. $l_query0 . $this->wpdb->prepare(' WHERE order_no=%d AND l_nos=%d AND gid=%d ', $order_no, $l_nos, $gid);                  
                       $this->updateDB($l_query0);    
                        $output = "<script>console.log( 'Updating Lens0: " . $l_model0 . "' );</script>";
                        $this->displayAlert($output);  
                }}
                
                if($r1!="" && $l1!=""){
                 $l_nos=1;$c_id=2;
                 
                 $l_query1 = $this->getLensQuery($order_no,$l_brand1,$l_model1,$l_rate1,$r1,$r_size1,$r_tint1,$l1,$l_size1,$l_tint1,$spec1,$note1,$l_sp1,$l_nos,$gid); 
                 $plstats= $plstats - $l_rate1;
                 $getdbstatus = $this->getdbStatus($order_no);
                 if($status=="Canceled" && $getdbstatus=="Delivered"){
                            $plstats=0;
                            $getdbdatas1 = $this->getdbLensDatas($order_no,$l_nos);
                            foreach($getdbdatas1 as $getdbdata1){
                                $l_dbdata1[]=$getdbdata1->l_brand; $l_dbdata1[]=$getdbdata1->l_model; $l_dbdata1[]=$getdbdata1->l_rate;
                            }

                            if($l_dbdata1[0]!=""){ 
                                $output= "<script>alert( 'Lens1 Records found and brand name=".$l_dbdata1[1]."' );</script>";
								$this->displayAlert($output);
                             $l_dbs_qty1 = $this->getstockQty($l_dbdata1[0],$l_dbdata1[1],$l_dbdata1[2], $c_id);                          
                             $output= "<script>alert( 'Previous db qty= '".$l_dbs_qty1." );</script>";
							 $this->displayAlert($output);
                                 $l_dbs_qty1=$l_dbs_qty1+1;
                                 $l_stQuery1 = $this->getupdateStockQuery($l_dbs_qty1,$l_dbdata1[0],$l_dbdata1[1],$l_dbdata1[2], $c_id);
                                 $this->updateDB($l_stQuery1);
                            }                     
                 }  
                 else{
                    if($status=="Delivered" || $getdbstatus=="Delivered"){
                        if($getdbstatus=="Delivered"){ 
                            $getdbdatas1 = $this->getdbLensDatas($order_no,$l_nos);
                            foreach($getdbdatas1 as $getdbdata1){
                                $l_dbdata1[]=$getdbdata1->l_brand; $l_dbdata1[]=$getdbdata1->l_model; $l_dbdata1[]=$getdbdata1->l_rate;
                            }
                            if($l_dbdata1[0]==$l_brand1 && $l_dbdata1[1]==$l_model1 && $l_dbdata1[2]==$l_rate1 ){}//delivered
                            //or may be a new update
                         else{
                            if($l_dbdata1[0]!=""){ 
                                $output= "<script>alert( 'Lens1 Records found and brand name=".$l_dbdata1[0]."' );</script>";
								$this->displayAlert($output);
                             $l_dbs_qty1 = $this->getstockQty($l_dbdata1[0],$l_dbdata1[1],$l_dbdata1[2], $c_id);                          
                             $output= "<script>alert( 'Previous db qty= '".$l_dbs_qty1." );</script>";
							 $this->displayAlert($output);
                                 $l_dbs_qty1=$l_dbs_qty1+1;
                                 $l_stQuery1 = $this->getupdateStockQuery($l_dbs_qty1,$l_dbdata1[0],$l_dbdata1[1],$l_dbdata1[2], $c_id);
                                 $this->updateDB($l_stQuery1);
                            }
                            else{//new data entry must be enetered in db
                                $output = "<script>alert( 'Lens 1 No Records found So inserting new data' );</script>";
                                $this->displayAlert($output);
                                    $l_nos=1; 
                                    $l_queryN = $this->getLensQuery($order_no,$l_brand0,$l_model0,$l_rate0,$r0,$r_size0,$r_tint0,$l0,$l_size0,$l_tint0,$spec0,$note0,$l_sp0,$l_nos,$gid); 
                                    $l_queryN = 'INSERT INTO' . $l_queryN;
                                    $this->updateDB($l_queryN); 
                                }
                                if($status=="Delivered"){
                                    $l_dbs_qty2 = $this->getstockQty($l_brand1, $l_model1, $l_rate1, $c_id);
                                    $output = "<script>alert( 'Lens 1 Entering dbs_qty: " . $l_dbs_qty2 . "' );</script>";
                                    $this->displayAlert($output);
                                    if($l_dbs_qty2!==null && ($l_dbs_qty2-1)>=0){
                                        $l_dbs_qty2=$l_dbs_qty2-1; 
                                        $l_stQuery2 = $this->getupdateStockQuery($l_dbs_qty2, $l_brand1, $l_model1, $l_rate1, $c_id);
                                         $this->updateDB($l_stQuery2);
                                    } } 
                                    
                                    }//else ends
                         }
                         else{
                                $l_dbs_qty =$this->getstockQty($l_brand1, $l_model1, $l_rate1, $c_id);                              
                                $output = "<script>alert( 'Lens 1(ELSE) Entering dbs_qty: " . $l_dbs_qty . "' );</script>";
                                $this->displayAlert($output);
                                if($l_dbs_qty!==null && ($l_dbs_qty-1)>0){
                                    $l_dbs_qty=$l_dbs_qty-1;       
                                    $l_stQuery = $this->getupdateStockQuery($l_dbs_qty, $l_brand1, $l_model1, $l_rate1, $c_id);       
                                    $this->updateDB($l_stQuery);
                                }                             
                             }
                     }                     
                        $l_query1 = 'UPDATE '. $l_query1 . $this->wpdb->prepare(' WHERE order_no=%d AND l_nos=%d AND gid=%d ', $order_no, $l_nos, $gid);                  
                       $this->updateDB($l_query1);    
                        $output = "<script>console.log( 'Updating Lens1: " . $l_model1 . "' );</script>";
                        $this->displayAlert($output);  
                }}
                
                if($r2!="" && $l2!=""){
                 $l_nos=2;$c_id=2;
                 
                 $l_query1 = $this->getLensQuery($order_no,$l_brand2,$l_model2,$l_rate2,$r2,$r_size2,$r_tint2,$l2,$l_size2,$l_tint2,$spec2,$note2,$l_sp2,$l_nos,$gid); 
                 $plstats= $plstats - $l_rate2;
                 $getdbstatus = $this->getdbStatus($order_no);
                 if($status=="Canceled" && $getdbstatus=="Delivered"){
                            $plstats=0;
                            $getdbdatas2 = $this->getdbLensDatas($order_no,$l_nos);
                            foreach($getdbdatas2 as $getdbdata2){
                                $l_dbdata2[]=$getdbdata2->l_brand; $l_dbdata2[]=$getdbdata2->l_model; $l_dbdata2[]=$getdbdata2->l_rate;
                            }

                            if($l_dbdata2[0]!=""){ 
                                $output= "<script>alert( 'Lens2 Records found and brand name=".$l_dbdata2[2]."' );</script>";
								$this->displayAlert($output);
                             $l_dbs_qty2 = $this->getstockQty($l_dbdata2[0],$l_dbdata2[1],$l_dbdata2[2], $c_id);                          
                             $output= "<script>alert( 'Previous db qty= '".$l_dbs_qty2." );</script>";
							 $this->displayAlert($output);
                                 $l_dbs_qty2=$l_dbs_qty2+1;
                                 $l_stQuery2 = $this->getupdateStockQuery($l_dbs_qty2,$l_dbdata2[0],$l_dbdata2[1],$l_dbdata2[2], $c_id);
                                 $this->updateDB($l_stQuery2);
                            }                     
                 } 
                 else{                 
                    if($status=="Delivered" || $getdbstatus=="Delivered"){
                        if($getdbstatus=="Delivered"){ 
                            $getdbdatas2 = $this->getdbLensDatas($order_no,$l_nos);
                            foreach($getdbdatas2 as $getdbdata2){
                                $l_dbdata2[]=$getdbdata2->l_brand; $l_dbdata2[]=$getdbdata2->l_model; $l_dbdata2[]=$getdbdata2->l_rate;
                            }
                            if($l_dbdata2[0]==$l_brand2 && $l_dbdata2[1]==$l_model2 && $l_dbdata2[2]==$l_rate2 ){}//delivered
                            //or may be a new update
                         else{
                            if($l_dbdata2[0]!=""){ 
                                $output= "<script>alert( 'Lens2 Records found and brand name=".$l_dbdata2[0]."' );</script>";
								$this->displayAlert($output);
                             $l_dbs_qty2 = $this->getstockQty($l_dbdata2[0],$l_dbdata2[1],$l_dbdata2[2], $c_id);                          
                             $output= "<script>alert( 'Previous db qty= '".$l_dbs_qty2." );</script>";
							 $this->displayAlert($output);
                                 $l_dbs_qty2=$l_dbs_qty2+1;
                                 $l_stQuery2 = $this->getupdateStockQuery($l_dbs_qty2,$l_dbdata2[0],$l_dbdata2[1],$l_dbdata2[2], $c_id);
                                 $this->updateDB($l_stQuery2);
                            }
                            else{//new data entry must be enetered in db
                                $output = "<script>alert( 'Lens 2 No Records found So inserting new data' );</script>";
                                $this->displayAlert($output);
                                    $l_nos=2; 
                                    $l_queryN = $this->getLensQuery($order_no,$l_brand2,$l_model2,$l_rate2,$r2,$r_size2,$r_tint2,$l2,$l_size2,$l_tint2,$spec2,$note2,$l_sp2,$l_nos,$gid); 
                                    $l_queryN = 'INSERT INTO' . $l_queryN;
                                    $this->updateDB($l_queryN); 
                                }
                                if($status=="Delivered"){
                                    $l_dbs_qty2 = $this->getstockQty($l_brand2, $l_model2, $l_rate2, $c_id);
                                    $output = "<script>alert( 'Lens 2 Entering dbs_qty: " . $l_dbs_qty2 . "' );</script>";
                                    $this->displayAlert($output);
                                    if($l_dbs_qty2!==null && ($l_dbs_qty2-1)>=0){
                                        $l_dbs_qty2=$l_dbs_qty2-1; 
                                        $l_stQuery2 = $this->getupdateStockQuery($l_dbs_qty2, $l_brand2, $l_model2, $l_rate2, $c_id);
                                         $this->updateDB($l_stQuery2);
                                    } } 
                                    
                                    }//else ends
                         }
                         else{
                                $l_dbs_qty =$this->getstockQty($l_brand2, $l_model2, $l_rate2, $c_id);                              
                                $output = "<script>alert( 'Lens 2(ELSE) Entering l_dbs_qty: " . $l_dbs_qty . "' );</script>";
                                $this->displayAlert($output);
                                if($l_dbs_qty!==null && ($l_dbs_qty-1)>0){
                                    $l_dbs_qty=$l_dbs_qty-1;       
                                    $l_stQuery = $this->getupdateStockQuery($l_dbs_qty, $l_brand2, $l_model2, $l_rate2, $c_id);       
                                    $this->updateDB($l_stQuery);
                                }                             
                             }
                     }                     
                        $l_query2 = 'UPDATE '. $l_query2 . $this->wpdb->prepare(' WHERE order_no=%d AND l_nos=%d AND gid=%d ', $order_no, $l_nos, $gid);                  
                       $this->updateDB($l_query2);    
                        $output = "<script>console.log( 'Updating Lens2: " . $l_model2 . "' );</script>";
                        $this->displayAlert($output);  
                }}
                
                if($r3!="" && $l3!=""){
                 $l_nos=3;$c_id=2;
                 
                 $l_query1 = $this->getLensQuery($order_no,$l_brand3,$l_model3,$l_rate3,$r3,$r_size3,$r_tint3,$l3,$l_size3,$l_tint3,$spec3,$note3,$l_sp3,$l_nos,$gid); 
                 $plstats= $plstats - $l_rate3;
                 $getdbstatus = $this->getdbStatus($order_no);
                 if($status=="Canceled" && $getdbstatus=="Delivered"){
                            $plstats=0;
                            $getdbdatas3 = $this->getdbLensDatas($order_no,$l_nos);
                            foreach($getdbdatas3 as $getdbdata3){
                                $l_dbdata3[]=$getdbdata3->l_brand; $l_dbdata3[]=$getdbdata3->l_model; $l_dbdata3[]=$getdbdata3->l_rate;
                            }

                            if($l_dbdata3[0]!=""){ 
                                $output= "<script>alert( 'Lens3 Records found and brand name=".$l_dbdata3[3]."' );</script>";
								$this->displayAlert($output);
                             $l_dbs_qty3 = $this->getstockQty($l_dbdata3[0],$l_dbdata3[1],$l_dbdata3[2], $c_id);                          
                             $output= "<script>alert( 'Previous db qty= '".$l_dbs_qty3." );</script>";
							 $this->displayAlert($output);
                                 $l_dbs_qty3=$l_dbs_qty3+1;
                                 $l_stQuery3 = $this->getupdateStockQuery($l_dbs_qty3,$l_dbdata3[0],$l_dbdata3[1],$l_dbdata3[2], $c_id);
                                 $this->updateDB($l_stQuery3);
                            }                     
                 } 
                 else{
                    if($status=="Delivered" || $getdbstatus=="Delivered"){
                        if($getdbstatus=="Delivered"){ 
                            $getdbdatas3 = $this->getdbLensDatas($order_no,$l_nos);
                            foreach($getdbdatas3 as $getdbdata3){
                                $l_dbdata3[]=$getdbdata3->l_brand; $l_dbdata3[]=$getdbdata3->l_model; $l_dbdata3[]=$getdbdata3->l_rate;
                            }
                            if($l_dbdata3[0]==$l_brand3 && $l_dbdata3[1]==$l_model3 && $l_dbdata3[2]==$l_rate3 ){}//delivered
                            //or may be a new update
                         else{
                            if($l_dbdata3[0]!=""){ 
                                $output= "<script>alert( 'Lens3 Records found and brand name=".$l_dbdata3[0]."' );</script>";
								$this->displayAlert($output);
                             $l_dbs_qty3 = $this->getstockQty($l_dbdata3[0],$l_dbdata3[1],$l_dbdata3[2], $c_id);                          
                             $output= "<script>alert( 'Previous db qty= '".$l_dbs_qty3." );</script>";
							 $this->displayAlert($output);
                                 $l_dbs_qty3=$l_dbs_qty3+1;
                                 $l_stQuery3 = $this->getupdateStockQuery($l_dbs_qty3,$l_dbdata3[0],$l_dbdata3[1],$l_dbdata3[2], $c_id);
                                 $this->updateDB($l_stQuery3);
                            }
                            else{//new data entry must be enetered in db
                                $output = "<script>alert( 'Lens 3 No Records found So inserting new data' );</script>";
                                $this->displayAlert($output);
                                    $l_nos=3; 
                                    $l_queryN = $this->getLensQuery($order_no,$l_brand3,$l_model3,$l_rate3,$r3,$r_size3,$r_tint3,$l3,$l_size3,$l_tint3,$spec3,$note3,$l_sp3,$l_nos,$gid); 
                                    $l_queryN = 'INSERT INTO' . $l_queryN;
                                    $this->updateDB($l_queryN); 
                                }
                                if($status=="Delivered"){
                                    $l_dbs_qty3 = $this->getstockQty($l_brand3, $l_model3, $l_rate3, $c_id);
                                    $output = "<script>alert( 'Lens 3 Entering dbs_qty: " . $l_dbs_qty3 . "' );</script>";
                                    $this->displayAlert($output);
                                    if($l_dbs_qty3!==null && ($l_dbs_qty3-1)>=0){
                                        $l_dbs_qty3=$l_dbs_qty3-1; 
                                        $l_stQuery3 = $this->getupdateStockQuery($l_dbs_qty3, $l_brand3, $l_model3, $l_rate3, $c_id);
                                         $this->updateDB($l_stQuery3);
                                    } } 
                                    
                                    }//else ends
                         }
                         else{
                                $l_dbs_qty =$this->getstockQty($l_brand3, $l_model3, $l_rate3, $c_id);                              
                                $output = "<script>alert( 'Lens 3(ELSE) Entering l_dbs_qty: " . $l_dbs_qty . "' );</script>";
                                $this->displayAlert($output);
                                if($l_dbs_qty!==null && ($l_dbs_qty-1)>0){
                                    $l_dbs_qty=$l_dbs_qty-1;       
                                    $l_stQuery = $this->getupdateStockQuery($l_dbs_qty, $l_brand3, $l_model3, $l_rate3, $c_id);       
                                    $this->updateDB($l_stQuery);
                                }                             
                             }
                     }                     
                        $l_query3 = 'UPDATE '. $l_query3 . $this->wpdb->prepare(' WHERE order_no=%d AND l_nos=%d AND gid=%d ', $order_no, $l_nos, $gid);                  
                       $this->updateDB($l_query3);    
                        $output = "<script>console.log( 'Updating Lens3: " . $l_model3 . "' );</script>";
                        $this->displayAlert($output);  
                }}
                
                if($r4!="" && $l4!=""){
                 $l_nos=4;$c_id=2;
                 
                 $l_query1 = $this->getLensQuery($order_no,$l_brand4,$l_model4,$l_rate4,$r4,$r_size4,$r_tint4,$l4,$l_size4,$l_tint4,$spec4,$note4,$l_sp4,$l_nos,$gid); 
                 $plstats= $plstats - $l_rate4;
                 $getdbstatus = $this->getdbStatus($order_no);
                 if($status=="Canceled" && $getdbstatus=="Delivered"){
                            $plstats=0;
                            $getdbdatas4 = $this->getdbLensDatas($order_no,$l_nos);
                            foreach($getdbdatas4 as $getdbdata4){
                                $l_dbdata4[]=$getdbdata4->l_brand; $l_dbdata4[]=$getdbdata4->l_model; $l_dbdata4[]=$getdbdata4->l_rate;
                            }

                            if($l_dbdata4[0]!=""){ 
                                $output= "<script>alert( 'Lens4 Records found and brand name=".$l_dbdata4[4]."' );</script>";
								$this->displayAlert($output);
                             $l_dbs_qty4 = $this->getstockQty($l_dbdata4[0],$l_dbdata4[1],$l_dbdata4[2], $c_id);                          
                             $output= "<script>alert( 'Previous db qty= '".$l_dbs_qty4." );</script>";
							 $this->displayAlert($output);
                                 $l_dbs_qty4=$l_dbs_qty4+1;
                                 $l_stQuery4 = $this->getupdateStockQuery($l_dbs_qty4,$l_dbdata4[0],$l_dbdata4[1],$l_dbdata4[2], $c_id);
                                 $this->updateDB($l_stQuery4);
                            }                     
                 }   
                 else{
                    if($status=="Delivered" || $getdbstatus=="Delivered"){
                        if($getdbstatus=="Delivered"){ 
                            $getdbdatas4 = $this->getdbLensDatas($order_no,$l_nos);
                            foreach($getdbdatas4 as $getdbdata4){
                                $l_dbdata4[]=$getdbdata4->l_brand; $l_dbdata4[]=$getdbdata4->l_model; $l_dbdata4[]=$getdbdata4->l_rate;
                            }
                            if($l_dbdata4[0]==$l_brand4 && $l_dbdata4[1]==$l_model4 && $l_dbdata4[2]==$l_rate4 ){}//delivered
                            //or may be a new update
                         else{
                            if($l_dbdata4[0]!=""){ 
                                $output= "<script>alert( 'Lens4 Records found and brand name=".$l_dbdata4[0]."' );</script>";
								$this->displayAlert($output);
                             $l_dbs_qty4 = $this->getstockQty($l_dbdata4[0],$l_dbdata4[1],$l_dbdata4[2], $c_id);                          
                             $output= "<script>alert( 'Previous db qty= '".$l_dbs_qty4." );</script>";
							 $this->displayAlert($output);
                                 $l_dbs_qty4=$l_dbs_qty4+1;
                                 $l_stQuery4 = $this->getupdateStockQuery($l_dbs_qty4,$l_dbdata4[0],$l_dbdata4[1],$l_dbdata4[2], $c_id);
                                 $this->updateDB($l_stQuery4);
                            }
                            else{//new data entry must be enetered in db
                                $output = "<script>alert( 'Lens 4 No Records found So inserting new data' );</script>";
                                $this->displayAlert($output);
                                    $l_nos=4; 
                                    $l_queryN = $this->getLensQuery($order_no,$l_brand4,$l_model4,$l_rate4,$r4,$r_size4,$r_tint4,$l4,$l_size4,$l_tint4,$spec4,$note4,$l_sp4,$l_nos,$gid); 
                                    $l_queryN = 'INSERT INTO' . $l_queryN;
                                    $this->updateDB($l_queryN); 
                                }
                                if($status=="Delivered"){
                                    $l_dbs_qty4 = $this->getstockQty($l_brand4, $l_model4, $l_rate4, $c_id);
                                    $output = "<script>alert( 'Lens 4 Entering dbs_qty: " . $l_dbs_qty4 . "' );</script>";
                                    $this->displayAlert($output);
                                    if($l_dbs_qty4!==null && ($l_dbs_qty4-1)>=0){
                                        $l_dbs_qty4=$l_dbs_qty4-1; 
                                        $l_stQuery4 = $this->getupdateStockQuery($l_dbs_qty4, $l_brand4, $l_model4, $l_rate4, $c_id);
                                         $this->updateDB($l_stQuery4);
                                    } } 
                                    
                                    }//else ends
                         }
                         else{
                                $l_dbs_qty =$this->getstockQty($l_brand4, $l_model4, $l_rate4, $c_id);                              
                                $output = "<script>alert( 'Lens 4(ELSE) Entering l_dbs_qty: " . $l_dbs_qty . "' );</script>";
                                $this->displayAlert($output);
                                if($l_dbs_qty!==null && ($l_dbs_qty-1)>0){
                                    $l_dbs_qty=$l_dbs_qty-1;       
                                    $l_stQuery = $this->getupdateStockQuery($l_dbs_qty, $l_brand4, $l_model4, $l_rate4, $c_id);       
                                    $this->updateDB($l_stQuery);
                                }                             
                             }
                     }                     
                        $l_query4 = 'UPDATE '. $l_query4 . $this->wpdb->prepare(' WHERE order_no=%d AND l_nos=%d AND gid=%d ', $order_no, $l_nos, $gid);                  
                       $this->updateDB($l_query4);    
                        $output = "<script>console.log( 'Updating Lens4: " . $l_model4 . "' );</script>";
                        $this->displayAlert($output);
                }  }

                $query = 'UPDATE ' . $query . $this->wpdb->prepare(', plstats=%d WHERE inventory_id=%d', $plstats, $inventory_id);
                        
		} else {
			$user_id = get_current_user_id();
			

             

//frame
                    $output = "<script>alert( 'Frame f_model: " . $f_model0 . "' );</script>";
                        $this->displayAlert($output);
           
            if($f_model0!=""){
                     $f_nos=0;$c_id=1;
                     
                     $query0 =$this->getFrameQuery($order_no, $f_brand0, $f_model0, $f_size0, $f_color0, $f_sp0, $f_rate0, $f_nos, $gid);
                     $query0 = 'INSERT INTO' . $query0;
//                 $query0 = $this->wpdb->prepare("INSERT INTO " . $this->frame_table . " SET
//			order_no = %d,
//                        f_brand = %s,
//                        f_model = %s,
//                        f_size = %d,
//                        f_color = %s,
//			f_sp = %d,
//                        f_rate = %d,
//                        f_nos = %d,
//                        gid = %d",
//			$order_no,$f_brand0,$f_model0,$f_size0,$f_color0,$f_sp0,$f_rate0,$f_nos,$gid);
             
             $this->updateDB($query0); 
//$this->wpdb->query($query0);
                       $plstats=$total-$f_rate0;
                       $output = "<script>alert( 'Frame0 updated " . $f_model0 . "' );</script>";
                        $this->displayAlert($output);
             if($status=="Delivered"){
//need to also update the other database lets try that
                        //now fisrst get the stock qty
                       // $dbs_qty =$this->wpdb->get_var( "Select s_qty from wp_netrastock_item where stock_name='".$f_brand0."' AND s_model_no='".$f_model0."' AND s_rate=".$f_rate0  ); 
                        
                        $dbs_qty =  $this->getstockQty($f_brand0, $f_model0, $f_rate0, $c_id);
                        
                        $output = "<script>alert( 'Entering dbs_qty: " . $dbs_qty . "' );</script>";
                        $this->displayAlert($output);
                        if($dbs_qty!==null && ($dbs_qty-1)>0){
                            $dbs_qty=$dbs_qty-1;
                            //now lets update it
//                            $stQuery = "Update ". $this->wpdb->prepare(" wp_netrastock_item SET
//                                s_qty = %d",
//                                $dbs_qty);
//                            $stQuery=$stQuery . $this->wpdb->prepare(' WHERE stock_name=%s AND s_model_no=%s AND s_rate=%d', $f_brand0, $f_model0, $f_rate0);
//                          
                            $stQuery =  $this->getupdateStockQuery($dbs_qty, $f_brand0, $f_model0, $f_rate0, $c_id);
                            
                           $this->updateDB($stQuery); 
// $this->wpdb->query($stQuery); 
                                                        
                        }
                    }//delivered  
                }
                
                        
                 if($f_model1!=""){
                     $f_nos=1;$c_id=1;
                     
                     $query1 =$this->getFrameQuery($order_no, $f_brand1, $f_model1, $f_size1, $f_color1, $f_sp1, $f_rate1, $f_nos, $gid);
                     $query1 = 'INSERT INTO' . $query1;
                     $this->updateDB($query1); 
                     $plstats=$plstats-$f_rate1;
                       $output = "<script>alert( 'Frame1 updated " . $f_model1 . "' );</script>";
                        $this->displayAlert($output);
             if($status=="Delivered"){
                        $dbs_qty1 =  $this->getstockQty($f_brand1, $f_model1, $f_rate1, $c_id);                   
                        $output = "<script>alert( 'Entering dbs_qty1: " . $dbs_qty1 . "' );</script>";
                        $this->displayAlert($output);
                        if($dbs_qty1!==null && ($dbs_qty1-1)>0){
                            $dbs_qty1=$dbs_qty1-1;                        
                            $stQuery1 =  $this->getupdateStockQuery($dbs_qty1, $f_brand1, $f_model1, $f_rate1, $c_id);                   
                           $this->updateDB($stQuery1);                                       
                        }
                    }//delivered           
                }
                
                if($f_model2!=""){
                     $f_nos=2;$c_id=1;
                     
                     $query2 =$this->getFrameQuery($order_no, $f_brand2, $f_model2, $f_size2, $f_color2, $f_sp2, $f_rate2, $f_nos, $gid);
                     $query2 = 'INSERT INTO' . $query2;
                     $this->updateDB($query2); 
                     $plstats=$plstats-$f_rate2;
                       $output = "<script>alert( 'Frame2 updated " . $f_model2 . "' );</script>";
                        $this->displayAlert($output);
             if($status=="Delivered"){
                        $dbs_qty2 =  $this->getstockQty($f_brand2, $f_model2, $f_rate2, $c_id);                   
                        $output = "<script>alert( 'Entering dbs_qty2: " . $dbs_qty2 . "' );</script>";
                        $this->displayAlert($output);
                        if($dbs_qty2!==null && ($dbs_qty2-1)>0){
                            $dbs_qty2=$dbs_qty2-1;                        
                            $stQuery2 =  $this->getupdateStockQuery($dbs_qty2, $f_brand2, $f_model2, $f_rate2, $c_id);                   
                           $this->updateDB($stQuery2);                                       
                        }
                    }//delivered   
                }
                
                if($f_model3!=""){
                     $f_nos=3;$c_id=1;
                     
                     $query3 =$this->getFrameQuery($order_no, $f_brand3, $f_model3, $f_size3, $f_color3, $f_sp3, $f_rate3, $f_nos, $gid);
                     $query3 = 'INSERT INTO' . $query3;
                     $this->updateDB($query3);
                     $plstats=$plstats-$f_rate3;
                       $output = "<script>alert( 'Frame3 updated " . $f_model3 . "' );</script>";
                        $this->displayAlert($output);
             if($status=="Delivered"){
                        $dbs_qty3 =  $this->getstockQty($f_brand3, $f_model3, $f_rate3, $c_id);                   
                        $output = "<script>alert( 'Entering dbs_qty3: " . $dbs_qty3 . "' );</script>";
                        $this->displayAlert($output);
                        if($dbs_qty3!==null && ($dbs_qty3-1)>0){
                            $dbs_qty3=$dbs_qty3-1;                        
                            $stQuery3 =  $this->getupdateStockQuery($dbs_qty3, $f_brand3, $f_model3, $f_rate3, $c_id);                   
                           $this->updateDB($stQuery3);                                       
                        }
                    }//delivered   
                }
                
                if($f_model4!=""){
                     $f_nos=4;$c_id=1;
                     
                     $query4 =$this->getFrameQuery($order_no, $f_brand4, $f_model4, $f_size4, $f_color4, $f_sp4, $f_rate4, $f_nos, $gid);
                     $query4 = 'INSERT INTO' . $query4;
                     $this->updateDB($query4); 
                     $plstats=$plstats-$f_rate4;
                       $output = "<script>alert( 'Frame4 updated " . $f_model4 . "' );</script>";
                        $this->displayAlert($output);
             if($status=="Delivered"){
                        $dbs_qty4 =  $this->getstockQty($f_brand4, $f_model4, $f_rate4, $c_id);                   
                        $output = "<script>alert( 'Entering dbs_qty4: " . $dbs_qty4 . "' );</script>";
                        $this->displayAlert($output);
                        if($dbs_qty4!==null && ($dbs_qty4-1)>0){
                            $dbs_qty4=$dbs_qty4-1;                        
                            $stQuery4 =  $this->getupdateStockQuery($dbs_qty4, $f_brand4, $f_model4, $f_rate4, $c_id);                   
                           $this->updateDB($stQuery4);                                       
                        }
                    }//delivered   
                }
                
 //lens        
              $output = "<script>console.log( 'Entering1 R: " . $r0 . " L: " .$l0 . " ' );</script>";
                        $this->displayAlert($output);
               if($r0!="" && $l0!=""){
                   $l_nos=0;$c_id=2;
                    $queryL0= $this->getLensQuery($order_no,$l_brand0,$l_model0,$l_rate0,$r0,$r_size0,$r_tint0,$l0,$l_size0,$l_tint0,$spec0,$note0,$l_sp0,$l_nos,$gid); 
                    $queryL0 = 'INSERT INTO' . $queryL0;
                    $this->updateDB($queryL0); 
                     $plstats=$plstats-$l_rate0;
                       $output = "<script>alert( 'Lens0 updated " . $l_model0 . "' );</script>";
                        $this->displayAlert($output);
             if($status=="Delivered"){
                        $l_dbs_qty0 =  $this->getstockQty($l_brand0, $l_model0, $l_rate0, $c_id);                   
                        $output = "<script>alert( 'Entering l_dbs_qty0: " . $l_dbs_qty0 . "' );</script>";
                        $this->displayAlert($output);
                        if($l_dbs_qty0!==null && ($l_dbs_qty0-1)>0){
                            $l_dbs_qty0=$l_dbs_qty0-1;                        
                            $l_stQuery0 =  $this->getupdateStockQuery($l_dbs_qty0, $l_brand0, $l_model0, $l_rate0, $c_id);                   
                           $this->updateDB($l_stQuery0);                                       
                        }
                    }//delivered     
                }
                
               if($r1!="" && $l1!=""){
                   $l_nos=1;$c_id=2;
                    $queryL1= $this->getLensQuery($order_no,$l_brand1,$l_model1,$l_rate1,$r1,$r_size1,$r_tint1,$l1,$l_size1,$l_tint1,$spec1,$note1,$l_sp1,$l_nos,$gid); 
                    $queryL1 = 'INSERT INTO' . $queryL1;
                    $this->updateDB($queryL1); 
                     $plstats=$plstats-$l_rate1;
                       $output = "<script>alert( 'Lens1 updated " . $l_model1 . "' );</script>";
                        $this->displayAlert($output);
             if($status=="Delivered"){
                        $l_dbs_qty1 =  $this->getstockQty($l_brand1, $l_model1, $l_rate1, $c_id);                   
                        $output = "<script>alert( 'Entering l_dbs_qty1: " . $l_dbs_qty1 . "' );</script>";
                        $this->displayAlert($output);
                        if($l_dbs_qty1!==null && ($l_dbs_qty1-1)>1){
                            $l_dbs_qty1=$l_dbs_qty1-1;                        
                            $l_stQuery1 =  $this->getupdateStockQuery($l_dbs_qty1, $l_brand1, $l_model1, $l_rate1, $c_id);                   
                           $this->updateDB($l_stQuery1);                                       
                        }
                    }//delivered     
                }
                
               if($r2!="" && $l2!=""){
                   $l_nos=2;$c_id=2;
                   $queryL2 = 'INSERT INTO' . $queryL2;
                    $queryL2= $this->getLensQuery($order_no,$l_brand2,$l_model2,$l_rate2,$r2,$r_size2,$r_tint2,$l2,$l_size2,$l_tint2,$spec2,$note2,$l_sp2,$l_nos,$gid); 
                    $this->updateDB($queryL2); 
                     $plstats=$plstats-$l_rate2;
                       $output = "<script>alert( 'Lens2 updated " . $l_model2 . "' );</script>";
                        $this->displayAlert($output);
             if($status=="Delivered"){
                        $l_dbs_qty2 =  $this->getstockQty($l_brand2, $l_model2, $l_rate2, $c_id);                   
                        $output = "<script>alert( 'Entering l_dbs_qty2: " . $l_dbs_qty2 . "' );</script>";
                        $this->displayAlert($output);
                        if($l_dbs_qty2!==null && ($l_dbs_qty2-1)>2){
                            $l_dbs_qty2=$l_dbs_qty2-1;                        
                            $l_stQuery2 =  $this->getupdateStockQuery($l_dbs_qty2, $l_brand2, $l_model2, $l_rate2, $c_id);                   
                           $this->updateDB($l_stQuery2);                                       
                        }
                    }//delivered     
                }
                
              if($r3!="" && $l3!=""){
                   $l_nos=3;$c_id=2;
                   $queryL3 = 'INSERT INTO' . $queryL3;
                    $queryL3= $this->getLensQuery($order_no,$l_brand3,$l_model3,$l_rate3,$r3,$r_size3,$r_tint3,$l3,$l_size3,$l_tint3,$spec3,$note3,$l_sp3,$l_nos,$gid); 
                    $this->updateDB($queryL3); 
                     $plstats=$plstats-$l_rate3;
                       $output = "<script>alert( 'Lens3 updated " . $l_model3 . "' );</script>";
                        $this->displayAlert($output);
             if($status=="Delivered"){
                        $l_dbs_qty3 =  $this->getstockQty($l_brand3, $l_model3, $l_rate3, $c_id);                   
                        $output = "<script>alert( 'Entering l_dbs_qty3: " . $l_dbs_qty3 . "' );</script>";
                        $this->displayAlert($output);
                        if($l_dbs_qty3!==null && ($l_dbs_qty3-1)>3){
                            $l_dbs_qty3=$l_dbs_qty3-1;                        
                            $l_stQuery3 =  $this->getupdateStockQuery($l_dbs_qty3, $l_brand3, $l_model3, $l_rate3, $c_id);                   
                           $this->updateDB($l_stQuery3);                                       
                        }
                    }//delivered     
                }
                
               if($r4!="" && $l4!=""){
                   $l_nos=4;$c_id=2;
                   $queryL4 = 'INSERT INTO' . $queryL4;
                    $queryL4= $this->getLensQuery($order_no,$l_brand4,$l_model4,$l_rate4,$r4,$r_size4,$r_tint4,$l4,$l_size4,$l_tint4,$spec4,$note4,$l_sp4,$l_nos,$gid); 
                    $this->updateDB($queryL4); 
                     $plstats=$plstats-$l_rate4;
                       $output = "<script>alert( 'Lens4 updated " . $l_model4 . "' );</script>";
                        $this->displayAlert($output);
             if($status=="Delivered"){
                        $l_dbs_qty4 =  $this->getstockQty($l_brand4, $l_model4, $l_rate4, $c_id);                   
                        $output = "<script>alert( 'Entering l_dbs_qty4: " . $l_dbs_qty4 . "' );</script>";
                        $this->displayAlert($output);
                        if($l_dbs_qty4!==null && ($l_dbs_qty4-1)>4){
                            $l_dbs_qty4=$l_dbs_qty4-1;                        
                            $l_stQuery4 =  $this->getupdateStockQuery($l_dbs_qty4, $l_brand4, $l_model4, $l_rate4, $c_id);                   
                           $this->updateDB($l_stQuery4);                                       
                        }
                    }//delivered     
                }
        
			$query = 'INSERT INTO' . $query . $this->wpdb->prepare(", user_id = %d, plstats = %d,
				inventory_date_added = %s",
				$user_id, $plstats, $now );                
                
                
		}


 

                        

                	$this->updateDB($query); 	
//$this->wpdb->query($query);

                
            
		
		if ( ! $inventory_id) {
			$inventory_id = $this->wpdb->insert_id;
		}
                
                
                $output = "<script>console.log( 'Sirus Objects: " . $this->wpdb->last_error . "' );</script>";
                        $this->displayAlert($output);
		
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
		
		do_action('wpim_save_reserve', $inventory_id, $quantity);
		
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
