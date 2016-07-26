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
<?php
				include_once($_SERVER['DOCUMENT_ROOT'].'/wordpress/wp-config.php' );
				 wp_enqueue_style('bill', get_template_directory_uri() . '/css/style-admin.css');
               //global $current_user;
                global $wpdb;
                

				
				
                //$sirus=$current_user->user_login;
                //$gid = $wpdb->get_var( "SELECT gid FROM $wpdb->users where user_login='$sirus'" );
				
				$user_count1 = $wpdb->get_var( "SELECT c_fname FROM wp_wpinventory_item where gid=1 AND inventory_id=54 " );
				echo "<p>User count is {$user_count1}</p>";
				
				$user_count3 = $wpdb->get_results( "SELECT * FROM wp_wpinventory_item where gid=0 AND inventory_id=60" );
				foreach($user_count3 as $row)
						 {
						 echo $row->c_fname." , ".$row->c_lname."<br>";
						 }
				?>
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
							<td colspan="3"><?php echo $row->order_no; ?></td>                  
							<th>Date</th>
							<td><?php echo $row->d_date ?></td> 
							<th>Promised On</th>
							<td><?php echo $row->d_date; ?></td> 							
						</tr>
						<tr>
							<th>Name</th>
							<td colspan=5><?php echo $row->c_fname."  ".$row->c_lname."<br>"; ?></td>                  
							<th>Frame</th>  <!--***************************-->
							<td><?php echo $row->d_date; ?></td> 							
						</tr>
						<tr>
							<th rowspan=2>Address</th>
							<td colspan=5><?php echo $row->c_add; ?></td>                  
							<th>Lens</th>  <!--***************************-->
							<td><?php echo $row->d_date; ?></td>  							
						</tr>
						<tr>
							<td colspan=5><?php echo $row->c_add; ?></td>                  
							<th>others</th>  <!--***************************-->
							<td><?php echo $row->d_date; ?></td>  							
						</tr>
						<tr>
							<th>R</th>
							<td><?php echo $row->c_add; ?></td>                  
							<th>O</th>  <!--***************************-->
							<td><?php echo $row->d_date; ?></td>  	
							<th>M</th>
							<td><?php echo $row->c_add; ?></td> 
							<th>Total</th>
							<td><?php echo $row->c_add; ?></td> 							
						</tr>
						<tr>
							<th rowspan="2">Ref By </th>
							<td colspan="5" rowspan="2"><?php echo $row->c_add; ?></td>                  
							<th>Advance</th>
							<td><?php echo $row->adv; ?></td>  							
						</tr>
						<tr>                 
							<th>Balance</th>
							<td><?php echo $row->bal; ?></td>  							
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
							<td><?php echo $row->r_d_sph; ?></td>                  
							<td><?php echo $row->r_d_cyl; ?></td>
  							<td><?php echo $row->r_d_axis; ?></td>                  
							<td><?php echo $row->r_d_va; ?></td> 
							
							<td><?php echo $row->l_d_sph; ?></td>                  
							<td><?php echo $row->l_d_cyl; ?></td>
  							<td><?php echo $row->l_d_axis; ?></td>                  
							<td><?php echo $row->l_d_va; ?></td>
						</tr>	
						<tr>
							<th>NEAR</th>
							<td><?php echo $row->r_n_sph; ?></td>                  
							<td><?php echo $row->r_n_cyl; ?></td>
  							<td><?php echo $row->r_n_axis; ?></td>                  
							<td><?php echo $row->r_n_va; ?></td> 
							
							<td><?php echo $row->l_n_sph; ?></td>                  
							<td><?php echo $row->l_n_cyl; ?></td>
  							<td><?php echo $row->l_n_axis; ?></td>                  
							<td><?php echo $row->l_n_va; ?></td>
						</tr>						
					</table>
					
					<table class="spec_bill3">
						<tr>
							<th style="width: 10%;">Frames</th>
							<td></td>
							<th style="width: 10%;">Lens</th>
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
				 <a href="#"> <button type="button" onclick="printDiv('spec_bill')" >Print Bill</button></a>
				 
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
							<td colspan="3"><?php echo $row->order_no; ?></td>                  							
						</tr>
						<tr>
							<th>Order No</th>
							<td><?php echo $row->order_no; ?></td>
							<th>Amount</th>
							<td><?php echo $row->f_price; ?></td>
						</tr>
						<tr>
							<th>Date</th>
							<td><?php echo $row->Date; ?></td>
							<th>Advance</th>
							<td><?php echo $row->adv; ?></td>
						</tr>
						<tr>
							<th>Pro. No.</th>
							<td><?php echo $row->Date; ?></td>
							<th>Balance</th>
							<td><?php echo $row->bal; ?></td>
						</tr>
					</table>
				
			</div>	
					
