<?php
//get search term brand or madel name
    require_once('C:\xampp\apps\wordpress\htdocs\wp-config.php' );
    require_once('C:\xampp\apps\wordpress\htdocs\wp-includes\wp-db.php' );

    $searchTerm = $_GET['term'];
    $model = $_GET['model'];
    

    
    $where=" AND category='Frame' AND gid=1";
    if($model!="")
    {
        $model = esc_sql( $model );
        // We are using this in a LIKE statement, so escape it for that as well.
        $model = like_escape( $model );
              //$output = "<script>console.log( 'Search Term: " . $searchTerm . " Brand name: " . $model . "' );</script>";
       //echo $output;
       $where.=" AND model='".$model."'"; 
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
    $categories = $wpdb->get_results('SELECT Distinct brand FROM wp_wpinventory_stock WHERE brand LIKE "' . $link . '"' . $where .' ORDER BY brand ASC ');
foreach($categories AS $category) {
			$data[]= $category->brand;
                           // $output = "<script>console.log( 'Debug Objects: " . $category->model . "' );</script>";
                        //echo $output;
                        //echo ''.$category->model;
                        
}



    //return json data
    echo json_encode($data);

?>