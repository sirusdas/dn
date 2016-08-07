<?php
/*if ( ! defined('ABSPATH')) {
	exit;
}*/
/**
 * Abstract Database class for accessing inventory data
 * @author WEBXARC Developers
 * @package NETRACustomer
 * @copyright 2016
 */
require('G:\Workspace\EclipsePhpNeon\dn\wp-load.php');
//require_once "includes/netracustomer.class.php";
include_once('netracustomer.db.class.php');

      

            
               global $current_user;
                global $wpdb;
                
                $sirus=$current_user->user_login;
                $gid = $wpdb->get_var( "SELECT gid FROM $wpdb->users where user_login='$sirus'" );
                $message = "The User_id is ".$current_user->user_login." The gid is ".$gid;
                echo "<script type='text/javascript'>alert('User id= $message');</script>";
// process.php


$errors         = array();      // array to hold validation errors
$data           = array();      // array to pass back data

// validate the variables ======================================================
    // if any of these variables don't exist, add an error to our $errors array
    
    if (empty($_POST['iid'])){
        }else{  $inventory_id=$_POST['iid'];        
        echo "<script type='text/javascript'>alert('iid= $inventory_id');</script>";
        }
    
    
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
        
                    if (empty($_POST['p_total'])){
        $errors['p_total'] = 'Product Rate is required.';}else{  $p_total=$_POST['p_total'];}        
                    
                    if (empty($_POST['p_bal'])){
        $errors['p_bal'] = 'Product Rate is required.';}else{  $p_bal=$_POST['p_bal'];} 
        
                    if (empty($_POST['p_adv'])){
        $errors['p_adv'] = 'Product Rate is required.';}else{  $p_adv=$_POST['p_adv'];} 
        
           /*         if (empty($_POST['p_details'])){
        $errors['p_details'] = 'Product Details is required.';}else{    $p_details=$_POST['p_details'];}*/
        
        if (empty($_POST['category_id'])){
        $errors['category_id'] = 'Product Category is required.';}else{    $category_id=$_POST['category_id'];}
        
          $p_ddate=$_POST['p_duedate'];
          
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
        //just check if any data is present of the same invoice no
        if($inventory_id){
                $p_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM wp_netracustomer_data where inventory_id=%d and gid=%d",$inventory_id,$gid ));
        }else{
                $p_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM wp_netracustomer_data where invoice_no=%s and gid=%d",$invoice_no,$gid ));
        }
        
        if(p_count==null){
            $p_count=1;
        }else{
            if($inventory_id==null){
                $p_count=$p_count+1;
            }
        }
        
// no errors so lets save the data

        $s_qty=$p_qty;
		$now = date_to_mysql(current_time('timestamp'), TRUE);
		$query = $wpdb->prepare(" " . "wp_netracustomer_data" . " SET 
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
                        gid = %d,
                        p_count = %d",
			$invoice_no,
                        $vendor_name, $vendor_address,
                        $p_name, $p_model_no, $p_qty, $p_rate, $p_total, $p_adv, $p_bal, $p_ddate, 
			$p_details, $category_id, $gid, $p_count);
                

                   
		
		if ($inventory_id) {
                    echo "<script type='text/javascript'>alert('Updating Data');</script>";
			$query = 'UPDATE ' . $query . $wpdb->prepare(' WHERE inventory_id=%d', $inventory_id);
                        //while updating the stock might also get reduced so lets check it
                        //we also need to delete the complete stock data if the model name rate or no is changed
                        $db_p_row=$wpdb->get_row($wpdb->prepare('SELECT * FROM ' . "wp_netracustomer_data" . ' WHERE  inventory_id = %d', $inventory_id));
                        //getting stock qty
                        $db_st_qty=$wpdb->get_var($wpdb->prepare('SELECT s_qty FROM ' . "wp_netrastock_item" . ' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $db_p_row->p_model_no, $db_p_row->p_name, $db_p_row->p_rate, $gid));
                       //gettting stock data
                        $db_s_row=$wpdb->get_row($wpdb->prepare('SELECT * FROM ' . "wp_netrastock_item" . ' WHERE  s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $p_model_no, $p_name, $p_rate, $gid));
                        if($db_st_qty==null){
                            $db_st_qty=0;
                        }
                        echo "<script type='text/javascript'>alert('Database Stock Quantity=$db_st_qty');</script>";
                        
                        if($p_model_no!=$db_p_row->p_model_no || $p_name!=$db_p_row->p_name || $p_rate!=$db_p_row->p_rate){
                            
                             echo "<script type='text/javascript'>alert('New data is found... creating new stock with qty=$p_qty');</script>"; 
                            
                            
                            
                             //check if db has a similar data if not then a new stock will be added
                            if($p_model_no!=$db_s_row->s_model_no || $p_name!=$db_s_row->stock_name || $p_rate!=$db_s_row->s_rate){ 
                                echo "<script type='text/javascript'>alert('NOT Matching withh data in stock so creating a new stock');</script>"; 
                                            //the stock query  
                $user_id = get_current_user_id();
                   $siquery = $wpdb->prepare(" " . "wp_netrastock_item" . " SET 
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
                             $siquery = 'INSERT INTO' . $siquery . $wpdb->prepare(", user_id = %d,
				inventory_date_added = %s",
				$user_id, $now);
                             
                             $wpdb->query($siquery);
                            }
                            else{
                                
                                 $s_qty = $db_s_row->s_qty + $p_qty;
                                 
                   $siquery = $wpdb->prepare(" " . "wp_netrastock_item" . " SET 
			s_model_no = %s,
			stock_name = %s,
			s_rate = %d,                        
			s_qty = %d,
                        category_id= %d,
                        inventory_date_updated = %s, 
                        gid = %d",
                        $p_model_no, $p_name, $p_rate, $s_qty, 
			$category_id, $p_ddate, $gid); 
                                       
                               $siquery = 'UPDATE ' . $siquery . $wpdb->prepare(' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $p_model_no, $p_name, $p_rate, $gid);    
                               $wpdb->query($siquery);                                 
                            }
                            $s_qty= $db_st_qty - $db_p_row->p_qty;
                                                //the stock query  
                
                   $squery = $wpdb->prepare(" " . "wp_netrastock_item" . " SET 
			s_model_no = %s,
			stock_name = %s,
			s_rate = %d,                        
			s_qty = %d,
                        category_id= %d,
                        inventory_date_updated = %s, 
                        gid = %d",
                        $db_p_row->p_model_no, $db_p_row->p_name, $db_p_row->p_rate, $s_qty, 
			$db_p_row->category_id, $db_p_row->p_ddate, $gid);
                             
                            $squery = 'UPDATE ' . $squery . $wpdb->prepare(' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $db_p_row->p_model_no, $db_p_row->p_name, $db_p_row->p_rate, $gid);
                      
                            }
                        else{
                            if($p_qty < $db_p_row->p_qty){
                                
                                echo "<script type='text/javascript'>alert('Product quantity is less discovered. Taking necessary steps');</script>";
                                //so now the stock qty has to be deducted
                                $s_qty=$db_st_qty - ($db_p_row->p_qty - $p_qty);
                                
                                                //the stock query  
                
                   $squery = $wpdb->prepare(" " . "wp_netrastock_item" . " SET 
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
                                    
                                $squery = 'UPDATE ' . $squery . $wpdb->prepare(' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $p_model_no, $p_name, $p_rate, $gid);
                            }

                            if($p_qty > $db_p_row->p_qty){
                                
                                 echo "<script type='text/javascript'>alert('Product quantity more discovered. Taking necessary steps');</script>";
                                //so now the stock qty has to be deducted
                                $s_qty=$db_st_qty + ($p_qty - $db_p_row->p_qty) ;
                                
                                                //the stock query  
                
                   $squery = $wpdb->prepare(" " . "wp_netrastock_item" . " SET 
			s_model_no = %s,
			stock_name = %s,
			s_rate = %d,                        
			s_qty = %d,
                        category_id= %d,
                        inventory_date_updated = %s, 
                        gid = %d",
                        $p_model_no, $p_name, $p_rate, $s_qty, 
			$category_id, $p_ddate, $gid);
                                
                   
                    echo "<script type='text/javascript'>alert('Stock Quantity=$s_qty=$db_st_qty + $p_qty - $db_p_row->p_qty and model no= $p_model_no and model name=$p_name and rate=$p_rate and gid=$gid');</script>";
                    
                                $squery = 'UPDATE ' . $squery . $wpdb->prepare(' WHERE s_model_no = "%s" AND stock_name = "%s" AND s_rate = %d AND gid = %d', $p_model_no, $p_name, $p_rate, $gid);
                                echo ''.$squery;
                            }                            
                        }
                        
		} else {
                     echo '<script>alert("into NetraCustomerData Db"); </script>';
                    $user_id = get_current_user_id();
                        //first we search for similar product in database then add if so or else a new entry
                          $db_st_qty=$wpdb->get_var($wpdb->prepare('SELECT s_qty FROM ' . "wp_netrastock_item" . ' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $p_model_no, $p_name, $p_rate, $gid));
                           
                          if($db_st_qty!=null){
                                                $s_qty = $db_st_qty + $s_qty;//updated qunantity
                                                                //the stock query  
                                                                echo 'Updaing Stock with new data with qty='.$s_qty;
                   $squery = $wpdb->prepare(" " . "wp_netrastock_item" . " SET 
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
                        $squery = 'UPDATE ' . $squery . $wpdb->prepare(' WHERE s_model_no = %s AND stock_name = %s AND s_rate = %d AND gid = %d', $p_model_no, $p_name, $p_rate, $gid);
                                           }
                                           else{
                                                               //the stock query  
                echo "Creating a new stock with stock qty=".$s_qty;
                   $squery = $wpdb->prepare(" " . "wp_netrastock_item" . " SET 
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
                        $squery = 'INSERT INTO' . $squery . $wpdb->prepare(", user_id = %d,
				inventory_date_added = %s",
				$user_id, $now);
                                           }
                                           
			
			
			$query = 'INSERT INTO' . $query . $wpdb->prepare(", user_id = %d,
				inventory_date_added = %s",
				$user_id, $now);
                        
		}
                echo '<script>alert("Inserting data into NetraCustomerData"); </script>';
		
		$wpdb->query($query);
                $wpdb->query($squery);
		
		if ( ! $inventory_id) {
			$inventory_id = $wpdb->insert_id;
		}
		
		return ( ! $wpdb->last_error) ? $inventory_id : FALSE;
        
        
        // if there are no errors process our form, then return a message

        // DO ALL YOUR FORM PROCESSING HERE
        // THIS CAN BE WHATEVER YOU WANT TO DO (LOGIN, SAVE, UPDATE, WHATEVER)

        // show a message of success and provide a true success variable
        $data['success'] = true;
        $data['message'] = 'Success!';
    }
	
	
	//$success=array('Name' => $_POST['name'], 'Email' => $_POST['email'], 'Super Hero' => $_POST['superheroAlias']);
	//$data['message']=$success;

    // return all our data to an AJAX call
    echo json_encode($data);
    

        

    
function date_to_mysql( $date, $time = FALSE ) {
		if ( ! is_numeric( $date ) ) {
			$date = strtotime( $date );
		}
		$format = ( $time ) ? 'Y-m-d H:i:s' : 'Y-m-d';

		return date( $format, $date );
	}
