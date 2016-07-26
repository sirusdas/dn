<?php
//get search term and a unique values
    require_once('C:\xampp\apps\wordpress\htdocs\wp-config.php' );
    require_once('C:\xampp\apps\wordpress\htdocs\wp-includes\wp-db.php' );

    $searchvname = $_POST['vname'];
                        //$output = "<script>console.log( 'Debug Objects Brand: " . $searchBrand . "' );</script>";
                       // echo $output;
    //get matched data from skills table
    
    if($searchvname!=""){
        $searchvname = esc_sql( $searchvname);
        // We are using this in a LIKE statement, so escape it for that as well.
        $searchvname = like_escape( $searchvname );
              //$output = "<script>console.log( 'Search Term: " . $searchTerm . " Brand name: " . $vname . "' );</script>";
       //echo $output;
       $where=" vendor_name='".$searchvname."'";    
       
 


     // $table_name = $wpdb->prefix . 'wpinventory_stock';
    $categories = $wpdb->get_results('SELECT  * FROM wp_netracustomer_item WHERE ' . $where. ' AND category_id=1 AND gid=1 ORDER BY vendor_name ASC ');
foreach($categories AS $category) {
    
                        $data[]=$category->vendor_address;
                        
}


    //return json data
    if(count($data)<=1){
    echo json_encode($data);
    }
   }
?>

