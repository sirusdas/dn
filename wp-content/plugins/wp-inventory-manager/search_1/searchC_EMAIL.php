<?php
//get search term
    require_once('C:\xampp\apps\wordpress\htdocs\wp-config.php' );
    require_once('C:\xampp\apps\wordpress\htdocs\wp-includes\wp-db.php' );

    $searchTerm = $_GET['c_email'];
    $c_no = $_GET['c_no'];
    $c_fname= $_GET['c_fname'];
    $c_lname = $_GET['c_lname'];
    $c_city = $_GET['c_city'];
    $c_city_pin = $_GET['c_city_pin'];
    

    
   // $where=" AND category='Frame' AND gid=1";
    if($c_no!="")
    {
        $c_no = esc_sql( $c_no );
        $c_no = like_escape( $c_no );
        $where.=" AND c_no='".$c_no."'"; 
    }
        if($c_fname!="")
    {
        $c_fname = esc_sql( $c_fname );
        $c_fname = like_escape( $c_fname );
        $where.=" AND c_fname='".$c_fname."'"; 
    }
        if($c_lname!="")
    {
        $c_lname = esc_sql( $c_lname );
        $c_lname = like_escape( $c_lname );
        $where.=" AND c_lname='".$c_lname."'"; 
    }
    
        if($c_city!="")
    {
        $c_city = esc_sql( $c_city );
        $c_city = like_escape( $c_city );
        $where.=" AND c_city='".$c_city."'"; 
    }
    
        if($c_city_pin!="")
    {
        $c_city_pin = esc_sql( $c_city_pin );
        $c_city_pin = like_escape( $c_city_pin );
        $where.=" AND c_city_pin='".$c_city_pin."'"; 
    }

    //get matched data from skills table
                        
    //var_dump($wpdb);
    //// First, escape the link for use in our SQL query.
$link = esc_sql( $searchTerm );
// We are using this in a LIKE statement, so escape it for that as well.
$link = like_escape( $link );

// Add wildcards, since we are searching within comment text.
$link = '%' . $link . '%';
                           // $output = "<script>console.log( 'Debug Objects: " . $link . "' );</script>";
                       // echo $output;
                       
	
   // $table_name = $wpdb->prefix . 'wpinventory_stock';
    $categories = $wpdb->get_results('SELECT Distinct c_email FROM wp_wpinventory_item WHERE c_email LIKE "' . $link . '"' . $where .' ORDER BY c_email ASC ');
foreach($categories AS $category) {
			$data[]= $category->c_email;
                           // $output = "<script>console.log( 'Debug Objects: " . $category->model . "' );</script>";
                        //echo $output;
                        //echo ''.$category->model;
                        
}



    //return json data
    echo json_encode($data);

?>