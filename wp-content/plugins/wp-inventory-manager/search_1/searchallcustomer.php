<?php
//get search term
    require_once('C:\xampp\apps\wordpress\htdocs\wp-config.php' );
    require_once('C:\xampp\apps\wordpress\htdocs\wp-includes\wp-db.php' );

    $c_no = $_POST['c_no'];
    $c_fname = $_POST['c_fname'];
    $c_city=$_POST['c_city'];
    $c_city_pin=$_POST['c_city_pin'];
    $c_email=$_POST['c_email'];
    $c_lname=$_POST['c_lname'];
                        //$output = "<script>console.log( 'Debug Objects Brand: " . $searchBrand . "' );</script>";
                       // echo $output;
    //get matched data from skills table
    
    if($c_no!=""){
        $c_no = esc_sql( $c_no);
        // We are using this in a LIKE statement, so escape it for that as well.
        $c_no = like_escape( $c_no );
              //$output = "<script>console.log( 'Search Term: " . $searchTerm . " Brand name: " . $model . "' );</script>";
       //echo $output;
       $where=" c_no='".$c_no."'";    
       
    }
    
    if($c_fname!=""){
        $c_fname = esc_sql( $c_fname );
        // We are using this in a LIKE statement, so escape it for that as well.
        $c_fname = like_escape( $c_fname );
              //$output = "<script>console.log( 'Search Term: " . $searchTerm . " Brand name: " . $model . "' );</script>";
       //echo $output;
        if($c_no!=""){
       $where.=" AND c_fname='".$c_fname."'";
        }
        else{ $where=" c_fname='".$c_fname."'"; }
    }
    
    if($c_lname!=""){
        $c_lname = esc_sql( $c_lname );
        // We are using this in a LIKE statement, so escape it for that as well.
        $c_lname = like_escape( $c_lname );
              //$output = "<script>console.log( 'Search Term: " . $searchTerm . " Brand name: " . $model . "' );</script>";
       //echo $output;
        if($c_no!="" || $c_fname!=""){
       $where.=" AND c_lname='".$c_lname."'";
        }
        else{ $where=" c_lname='".$c_lname."'"; }
    }
    
    if($c_city!=""){
        $c_city = esc_sql( $c_city );
        // We are using this in a LIKE statement, so escape it for that as well.
        $c_city = like_escape( $c_city );
              //$output = "<script>console.log( 'Search Term: " . $searchTerm . " Brand name: " . $model . "' );</script>";
       //echo $output;
        if($c_no!="" || $c_fname!="" ||  $c_lname!=""){
       $where.=" AND c_city='".$c_city."'";
        }
        else{ $where=" c_city='".$c_city."'"; }
    }
    
    if($c_city_pin!=""){
        $c_city_pin = esc_sql( $c_city_pin );
        // We are using this in a LIKE statement, so escape it for that as well.
        $c_city_pin = like_escape( $c_city_pin );
              //$output = "<script>console.log( 'Search Term: " . $searchTerm . " Brand name: " . $model . "' );</script>";
       //echo $output;
        if($c_no!="" || $c_fname!="" ||  $c_lname!="" ||  $c_city!=""){
       $where.=" AND c_city_pin='".$c_city_pin."'";
        }
        else{ $where=" c_city_pin='".$c_city_pin."'"; }
    }
    
    if($c_email!=""){
        $c_email = esc_sql( $c_email );
        // We are using this in a LIKE statement, so escape it for that as well.
        $c_email = like_escape( $c_email );
              //$output = "<script>console.log( 'Search Term: " . $searchTerm . " Brand name: " . $model . "' );</script>";
       //echo $output;
        if($c_no!="" || $c_fname!="" ||  $c_lname!="" ||  $c_city!="" ||  $c_city_pin!=""){
         $where.=" AND c_email='".$c_email."'";
        }
        else{ $where=" c_email='".$c_email."'"; }
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
    $categories = $wpdb->get_results('SELECT  * FROM wp_wpinventory_item WHERE ' . $where. ' AND gid=1 ');
foreach($categories AS $category) {
			$data[]= $category->c_no;
                        $data[]=$category->c_fname;
                        $data[]=$category->c_lname;
                        $data[]=$category->c_city;
                        $data[]=$category->c_city_pin;
                        $data[]=$category->c_email;
                        $data[]=$category->c_add;
                           // $output = "<script>console.log( 'Debug Objects: " . $category->model . "' );</script>";
                        //echo $output;
                        //echo ''.$category->model;
                        
}



    //return json data
    if(count($data)<=7){
    echo json_encode($data);
    }

?>

