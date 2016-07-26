<?php
//get search term and a unique values
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
       $where=" s_model_no='".$searchModel."'";    
       
    }
    
    if($searchBrand!=""){
        $searchBrand = esc_sql( $searchBrand );
        // We are using this in a LIKE statement, so escape it for that as well.
        $searchBrand = like_escape( $searchBrand );
              //$output = "<script>console.log( 'Search Term: " . $searchTerm . " Brand name: " . $model . "' );</script>";
       //echo $output;
        if($searchModel!=""){
       $where.=" AND stock_name='".$searchBrand."'";
        }
        else{ $where=" stock_name='".$searchBrand."'"; }
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
/* commecing as we are not using the multiple datavbases
 //note multiple databse areused here
    $ciDatas= $wpdb->get_results('SELECT  * FROM wp_netracustomer_item WHERE ' . $where. ' AND category_id=1 AND gid=1 ORDER BY p_model_no ASC  ');
    

        foreach($ciDatas AS $ciData){
            $data[]=$ciData->p_name;
            $data[]=$ciData->p_model_no;
            $tempdata[]=$ciData->p_name;
            $tempdata[]=$ciData->p_model_no;
        }
        
        if(count($data)<=2){
//unique data is found but yet neeeds to be confirmed
                $categories = $wpdb->get_results('SELECT  * FROM wp_netracustomer_data WHERE ' . $where. ' AND category_id=1 AND gid=1 ORDER BY p_model_no ASC  ');
            foreach($categories AS $category) {
                                      $found=0;                    
                                    //check if ciData matches and so remove the duplication

                                            for( $x=0; $x<count($tempdata); $x++){
                                                if($tempdata[$x]==$category->p_name){
                                                    $found=1;//match is found;
                                                }
                                                if($tempdata[$x]==$category->p_model_no){
                                                    $found=1;//match is found;
                                                }
                                            }


                                    if($found==1){}else{
                                        $data[]= $category->p_name;
                                        $data[]= $category->p_model_no;
                                    }
                                        //$output = "<script>console.log( 'Debug Objects: " . $category->model . "' );</script>";
                                    //echo $output;
                                    //echo ''.$category->p_model_no;

            }            

        }
    
	
   /*$table_name = $wpdb->prefix . 'wpinventory_stock';
    $categories = $wpdb->get_results('SELECT  * FROM wp_netracustomer_item WHERE ' . $where. ' AND category_id=1 AND gid=1 ORDER BY f_model_no ASC ');
foreach($categories AS $category) {
			$data[]= $category->brand;
                        $data[]=$category->model;
                           // $output = "<script>console.log( 'Debug Objects: " . $category->model . "' );</script>";
                        //echo $output;
                        //echo ''.$category->model;
                        
}
*/

     // $table_name = $wpdb->prefix . 'wpinventory_stock';
    $categories = $wpdb->get_results('SELECT  * FROM wp_netrastock_item WHERE ' . $where. ' AND category_id=1 AND gid=1 ORDER BY s_model_no ASC ');
foreach($categories AS $category) {
			$data[]= $category->stock_name;
                        $data[]=$category->s_model_no;
                           // $output = "<script>console.log( 'Debug Objects: " . $category->model . "' );</script>";
                        //echo $output;
                        //echo ''.$category->model;
                        
}


    //return json data
    if(count($data)<=2){
    echo json_encode($data);
    }

?>

