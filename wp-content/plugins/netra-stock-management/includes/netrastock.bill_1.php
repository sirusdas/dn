<?php

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}
				 

/**
 * Inventory configuration class
 */
class NSMBill extends NSMDB {
    
    private static $instance;
    
    
    	public function __construct() {
            parent::__construct();
	}
        
        	private function __clone() {
	}
    
    	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
    
    function manage_stock_bill()
    {
        wp_enqueue_style('bill', get_template_directory_uri() . '/css/style-admin.css');
               global $current_user;
                global $wpdb;
                
                $sirus=$current_user->user_login;
                $gid = $wpdb->get_var( "SELECT gid FROM $wpdb->users where user_login='$sirus'" );
                
/* @var $iid type */
        $iid=  intval($_GET["inventory_id"]);
    // $fsp[]="";$lsp[]="";   
    $user_count1 = $wpdb->get_var( "SELECT c_fname FROM wp_wpinventory_item where gid=$gid AND inventory_id=$iid " );
    $user_count3 = $wpdb->get_results( "SELECT * FROM wp_wpinventory_item where gid=$gid AND inventory_id=$iid" );
    $order_no = $wpdb->get_var( "SELECT order_no FROM wp_wpinventory_item where gid=$gid AND inventory_id=$iid " );//finding order no
    $frame_sp = $wpdb->get_results( "SELECT f_sp FROM wp_wpinventory_frame where gid=$gid AND order_no=$order_no ORDER BY f_nos ASC" );
    $lens_sp = $wpdb->get_results( "SELECT l_sp FROM wp_wpinventory_lens where gid=$gid AND order_no=$order_no ORDER BY l_nos ASC" );
        echo '
<head>
 <meta charset="UTF-8"> 
 <style>
	#spec_bill{
		width: 630px;
		border: 1px solid black;
		padding: 0px 10px;
	}
	td{
		//text-decoration:underline;
		border-bottom: 1px solid #1b1b1b;
	}
	table{
		margin-bottom:15px;
		width:100%;
	}
	table, th, td{
		//border: 1px solid black;
		padding:5px;
	}
	table.spec_bill2{
		border-collapse: collapse;
	}
	.shop_address{
		width:100%;
		border-bottom: 1px solid;
	}
	.shop_address .wrapper{
		width:80%;
		margin:0 auto;
	}
	
	.shop_address .wrapper .shop_name{
		width:80%;display:inline-block;
	}
	.shop_name h2,
	.shop_name h3{
		text-align:center;
	}
	.shop_logo{
		width:20%;
		height:88px;
		background:red;
		float: right;
		margin-top: 10px;
		display:inline-block;
	}
	#cust_receipt{
		width: 496px;
		border: 1px solid black;
		padding: 0px 10px;
	}
	#cust_receipt p{
		text-align: center;
	}
</style>
</head> 


				
				
				<!-- <p>User count is '. $user_count1 .'</p> -->
				
			';	
				foreach($user_count3 as $row)
						 {
						// echo $row->c_fname." , ".$row->c_lname."<br>";
						 }
                                foreach ($frame_sp as $f_sp){
                                    $fsp[]=$f_sp->f_sp;
                                }// for frame
                                foreach ($lens_sp as $l_sp){
                                    $lsp[]=$l_sp->l_sp;
                                }// for frame                                
		echo '
			<div class="Bill_Area">
				<div id="spec_bill">
					<div class="shop_address">
						<div class="wrapper">
							<div class="shop_name">
								<h2>VISION HOST</h2>
								<h3>Optical Showroom and contact lense clinic</h3>
							</div>
							<div class="shop_logo">
							   IMG
							</div>
						</div>
					</div>
					<table class="spec_bill1" bbborder=1>
						<tr>
							<th>Order No</th>
							<td colspan="3">'. $row->order_no . '</td>                  
							<th>Date</th>
							<td>'. $row->d_date . '</td> 
							<th>Promised On</th>
							<td>'. $row->d_date . '</td> 							
						</tr>
						<tr>
							<th>Name</th>
							<td colspan=5>'. $row->c_fname."  ".$row->c_lname."<br>" . '</td>              
							<th>Frame1 </th>  <!--***************************-->
							<td>'. $fsp[0] . '</td> 							
						</tr>
						<tr>
							<th rowspan=2>Address</th>
							<td colspan=5>'. $row->c_add .' </td>                  
							<th>Lens</th>  <!--***************************-->
							<td>'. $row->d_date .' </td>  							
						</tr>
						<tr>
							<td colspan=5>'. $row->c_add .' </td>                  
							<th>others</th>  <!--***************************-->
							<td>'. $row->d_date .' </td>  							
						</tr>
						<tr>
							<th>R</th>
							<td>'. $row->c_add .' </td>                  
							<th>O</th>  <!--***************************-->
							<td>'. $row->d_date .' </td>  	
							<th>M</th>
							<td>'. $row->c_add .' </td> 
							<th>Total</th>
							<td>'. $row->c_add .' </td> 							
						</tr>
						<tr>
							<th rowspan="2">Ref By </th>
							<td colspan="5" rowspan="2">'. $row->c_add .' </td>                  
							<th>Advance</th>
							<td>'. $row->adv .' </td>  							
						</tr>
						<tr>                 
							<th>Balance</th>
							<td>'. $row->bal .' </td>  							
						</tr>
					</table>
					
					<table class="spec_bill2" border=1>
						<tr>
							<th rowspan=2></th>
							<th colspan=4>Left</th> 
							<th colspan=4>Right</th>							
						</tr>
						<tr>
							<th>SPH</th> 
							<th>CYL</th>	
							<th>AXIS</th> 
							<th>VN</th>	
							<th>SPH</th> 
							<th>CYL</th>	
							<th>AXIS</th> 
							<th>VN</th>	
						</tr>
						<tr>
							<th>DIST</th>
							<td>'. $row->r_d_sph .' </td>                  
							<td>'. $row->r_d_cyl .' </td>
  							<td>'. $row->r_d_axis .' </td>                  
							<td>'. $row->r_d_va .' </td> 
							
							<td>'. $row->l_d_sph .' </td>                  
							<td>'. $row->l_d_cyl .' </td>
  							<td>'. $row->l_d_axis .' </td>                  
							<td>'. $row->l_d_va .' </td>
						</tr>	
						<tr>
							<th>NEAR</th>
							<td>'. $row->r_n_sph .' </td>                  
							<td>'. $row->r_n_cyl .' </td>
  							<td>'. $row->r_n_axis .' </td>                  
							<td>'. $row->r_n_va .' </td> 
							
							<td>'. $row->l_n_sph .' </td>                  
							<td>'. $row->l_n_cyl .' </td>
  							<td>'. $row->l_n_axis .' </td>                  
							<td>'. $row->l_n_va .' </td>
						</tr>						
					</table>
					
					<table class="spec_bill3">
						<tr>
							<th style="width: 10%">Frames</th>
							<td></td>
							<th style="width: 10%">Lens</th>
							<td></td>
						</tr>
						<tr>
							<th>Frames</th>
							<td></td>
							<th>Lens</th>
							<td></td>
						</tr>
					</table>
				</div>
				 <a href="#"> <button type="button" onclick="printDiv(spec_bill)" >Print Bill</button></a>
				 
				 <!-- Reciept-->
				 <div id="cust_receipt">
					<div class="shop_address">
						<div class="wrapper">
							<div class="shop_name">
								<h2>VISION HOST</h2>
								<h3>Optical Showroom and contact lense clinic</h3>
							</div>
							<div class="shop_logo">
							   IMG
							</div>
						</div>
						<div>
							<p class="address">Shop No. 4, Ronik Apt., M.G. Road, Opp. Citi Financial,
								Kandiwali (W), Mumbai- 100 067.</p>	
							<p class="phone_num">98192 74604 / 98670 91882</p>
							<p class="email">visionhostoptics.com</p>
						</div>
					</div>
					<table>
						<tr>
							<th>Name</th>
							<td colspan="3">'. $row->order_no .' </td>                  							
						</tr>
						<tr>
							<th>Order No</th>
							<td>'. $row->order_no .' </td>
							<th>Amount</th>
							<td>'. $row->f_price .' </td>
						</tr>
						<tr>
							<th>Date</th>
							<td>'. $row->Date .' </td>
							<th>Advance</th>
							<td>'. $row->adv .' </td>
						</tr>
						<tr>
							<th>Pro. No.</th>
							<td>'. $row->Date .' </td>
							<th>Balance</th>
							<td>'. $row->bal .' </td>
						</tr>
					</table>
				
			</div>	';
					
    }
}