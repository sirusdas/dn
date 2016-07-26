<?php
/*if ( ! defined('ABSPATH')) {
	exit;
}*/
//require('C:\xampp\apps\wordpress\htdocs\wp-load.php');
class saveProduct extends NCMCore{
    	public function __construct() {
			global $wpdb;
			$this->wpdb   = $wpdb;

			self::$config = NCMConfig::getInstance();

			$this->set_table_names();
			$this->check_tables();
	

               global $current_user;
                global $wpdb;
                
                $sirus=$current_user->user_login;
                $gid = $wpdb->get_var( "SELECT gid FROM $wpdb->users where user_login='$sirus'" );
               // $message = "The User_id is ".$current_user->user_login." The gid is ".$gid;
               // echo "<script type='text/javascript'>alert('$message');</script>";
// process.php


$errors         = array();      // array to hold validation errors
$data           = array();      // array to pass back data

// validate the variables ======================================================
    // if any of these variables don't exist, add an error to our $errors array
    
    
    if (empty($_POST['i_no'])){
        $errors['i_no'] = 'Invoice No is required.';}else{  $invoice_no=$_POST['i_no'];        }

        if (empty($_POST['v_name'])){
        $errors['v_name'] = 'Vendor Name is required.';}else{   $vendor_name=$_POST['v_name']; }
        
      /*      if (empty($_POST['pdate'])){
        $errors['pdate'] = 'Date is required.';}else{   $pdate=$_POST[pdate];}*/
            
                if (empty($_POST['v_address'])){
        $errors['v_address'] = 'Vendor Address is required.';}else{ $vendor_address=$_POST['v_address'];}
                
                    if (empty($_POST['p_name'])){
        $errors['p_name'] = 'Product Name is required.';}else{  $p_name=$_POST['p_name'];}
                    
                    if (empty($_POST['p_model_no'])){
        $errors['p_model_no'] = 'Product Model no is required.';}else{  $p_model_no=$_POST['p_model_no'];}
                    
                    if (empty($_POST['p_qty'])){
        $errors['p_qty'] = 'Product Quantity is required.';}else{   $p_qty=$_POST['p_qty'];}
                    
                    if (empty($_POST['p_rate'])){
        $errors['p_rate'] = 'Product Rate is required.';}else{  $p_rate=$_POST['p_rate'];}
                    
           /*         if (empty($_POST['p_details'])){
        $errors['p_details'] = 'Product Details is required.';}else{    $p_details=$_POST['p_details'];}*/
        
        if (empty($_POST['category_id'])){
        $errors['category_id'] = 'Product Category is required.';}else{    $category_id=$_POST['category_id'];}
        
          $p_ddate=$_POST['p_ddate'];
          
          debug_to_console($invoice_no)  ;                 
                    
   function debug_to_console( $data ) {

    if ( is_array( $data ) ){
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    }
    else{
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
    echo "<script type='text/javascript'>alert('$data');</script>";
    }
}      
    

// return a response ===========================================================

    // if there are any errors in our errors array, return a success boolean of false
    if ( ! empty($errors)) {

        // if there are items in our errors array, return those errors
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {
// no errors so lets save the data

		$now = $this->date_to_mysql(current_time('timestamp'), TRUE);
		$query = $this->wpdb->prepare(" " . $this->inventory_table . " SET 
			invoice_no = %s,
			vendor_name = %s,
			vendor_address = %s,
			p_name = %s,
			p_model_no = %s,
			p_qty = %d,
			p_rate = %d,
			p_total = %s,
                        p_bal = %d,
                        inventory_date_updated = %s,
                        p_details = %s,
                        category_id= %d,
                        gid = %d",
			$invoice_no,
                        $vendor_name, $vendor_address,
                        $p_name, $p_model_no, $p_qty, $p_rate, $p_total, $p_bal, $p_ddate, 
			$p_details, $category_id, $gid);
		
		if ($inventory_id) {
			$query = 'UPDATE ' . $query . $this->wpdb->prepare(' WHERE inventory_id=%d', $inventory_id);
		} else {
			$user_id = get_current_user_id();
			
			$query = 'INSERT INTO' . $query . $this->wpdb->prepare(", user_id = %d,
				inventory_date_added = %s",
				$user_id, $now);
                        
		}
		
		$this->wpdb->query($query);
		
		if ( ! $inventory_id) {
			$inventory_id = $this->wpdb->insert_id;
		}
		
		return ( ! $this->wpdb->last_error) ? $inventory_id : FALSE;
        
        
        // if there are no errors process our form, then return a message

        // DO ALL YOUR FORM PROCESSING HERE
        // THIS CAN BE WHATEVER YOU WANT TO DO (LOGIN, SAVE, UPDATE, WHATEVER)

        // show a message of success and provide a true success variable
        $data['success'] = true;
        $data['message'] = 'Success!';
    }
	
	
	$success=array('Name' => $_POST['name'], 'Email' => $_POST['email'], 'Super Hero' => $_POST['superheroAlias']);
	$data['message']=$success;

    // return all our data to an AJAX call
    echo json_encode($data);
    

        
    }
}