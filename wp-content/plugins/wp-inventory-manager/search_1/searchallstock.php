<?php
//get search term
    require_once('C:\xampp\apps\wordpress\htdocs\wp-config.php' );
    require_once('C:\xampp\apps\wordpress\htdocs\wp-includes\wp-db.php' );

    $searchModel = $_POST['model'];
    $searchBrand = $_POST['brand'];
                        //$output = "<script>console.log( 'Debug Objects Brand: " . $searchBrand . "' );</script>";
                       // echo $output;
    //get matched data from skills table
    
    if($searchModel!=""){
        $searchModel = esc_sql( $searchModel);
        // We are using this in a LIKE statement, so escape it for that as well.
        $searchModel = like_escape( $searchModel );
              //$output = "<script>console.log( 'Search Term: " . $searchTerm . " Brand name: " . $model . "' );</script>";
       //echo $output;
       $where=" model='".$searchModel."'";    
       
    }
    
    if($searchBrand!=""){
        $searchBrand = esc_sql( $searchBrand );
        // We are using this in a LIKE statement, so escape it for that as well.
        $searchBrand = like_escape( $searchBrand );
              //$output = "<script>console.log( 'Search Term: " . $searchTerm . " Brand name: " . $model . "' );</script>";
       //echo $output;
        if($searchModel!=""){
       $where.=" AND brand='".$searchBrand."'";
        }
        else{ $where=" brand='".$searchBrand."'"; }
    }
                        
    //var_dump($wpdb);
    //// First, escape the link for use in our SQL query.
$link = esc_sql( $searchTerm );
// We are using this in a LIKE statement, so escape it for that as well.
$link = like_escape( $link );

// Add wildcards, since we are searching within comment text.
//$link = '%' . $link . '%';
                           // $output = "<script>console.log( 'Debug Objects: " . $link . "' );</script>";
                       // echo $output;
                       
	
   // $table_name = $wpdb->prefix . 'wpinventory_stock';
    $categories = $wpdb->get_results('SELECT  * FROM wp_wpinventory_stock WHERE ' . $where. ' AND category="Frame" AND gid=1 ORDER BY model ASC ');
foreach($categories AS $category) {
			$data[]= $category->brand;
                        $data[]=$category->model;
                           // $output = "<script>console.log( 'Debug Objects: " . $category->model . "' );</script>";
                        //echo $output;
                        //echo ''.$category->model;
                        
}



    //return json data
    if(count($data)<=2){
    echo json_encode($data);
    }

?>

