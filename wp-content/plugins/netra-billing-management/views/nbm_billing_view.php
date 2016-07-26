<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrabilling/views/single-loop-all.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 *
 * NOTICE:
 * This file is designed to be "automatic", and display the fields you have selected in your Display Settings.
 * You can completely customize the file - if that's your intention, it is recommended that you refer to
 * the view file titled "single-loop-all-sample.php" for examples of functions to use for complete control.
 * My code will be added here.
 * */


 //$nbm_store=[];
?><?php 
function billing_view(){
global $inventory_display;

        foreach($inventory_display AS $sort=>$field) { 
            //echo "<script type='text/javascript'>alert('$field');</script>";
            if($field=='inventory_id'){ $nbm_store[0]=netrabilling_the_field($field);}
            if($field=='inventory_date_added'){ $nbm_store[1]=netrabilling_the_field($field);}
            if($field=='customer_name'){ $nbm_store[2]=netrabilling_the_field($field);}
            if($field=='customer_address'){ $nbm_store[3]=netrabilling_the_field($field);}
            if($field=='r'){ $nbm_store[4]=netrabilling_the_field($field);}
            if($field=='o'){ $nbm_store[5]=netrabilling_the_field($field);}
            if($field=='m'){ $nbm_store[6]=netrabilling_the_field($field);}
            if($field=='ref_by'){ $nbm_store[7]=netrabilling_the_field($field);}
            if($field=='frame_srno'){ $nbm_store[8]=netrabilling_the_field($field);}
            if($field=='frame_name'){ $nbm_store[9]=netrabilling_the_field($field);}
            if($field=='frame_qty'){ $nbm_store[10]=netrabilling_the_field($field);}
            if($field=='lens1_srno'){ $nbm_store[11]=netrabilling_the_field($field);}
            if($field=='lens1_name'){ $nbm_store[12]=netrabilling_the_field($field);}
            if($field=='lens1_qty'){ $nbm_store[13]=netrabilling_the_field($field);}
            if($field=='lens2_srno'){ $nbm_store[14]=netrabilling_the_field($field);}
            if($field=='lens2_name'){ $nbm_store[15]=netrabilling_the_field($field);}
            if($field=='lens2_qty'){ $nbm_store[16]=netrabilling_the_field($field);}
            if($field=='total'){ $nbm_store[17]=netrabilling_the_field($field);}
            if($field=='advance'){ $nbm_store[18]=netrabilling_the_field($field);}
            if($field=='bal'){ $nbm_store[19]=netrabilling_the_field($field);}
            if($field=='sph_dist'){ $nbm_store[20]=netrabilling_the_field($field);}
            if($field=='cyl_dist'){ $nbm_store[21]=netrabilling_the_field($field);}
            if($field=='axis_dist'){  $nbm_store[22]=netrabilling_the_field($field);} 
            if($field=='vn_dist'){ $nbm_store[23]=netrabilling_the_field($field);}
            if($field=='sph_near'){ $nbm_store[24]=netrabilling_the_field($field);}
            if($field=='cyl_near'){ $nbm_store[25]=netrabilling_the_field($field);}
            if($field=='axis_near'){ $nbm_store[26]=netrabilling_the_field($field);}
            if($field=='vn_near'){ $nbm_store[27]=netrabilling_the_field($field);}
            if($field=='sph_dist'){ $nbm_store[28]=netrabilling_the_field($field);}
            if($field=='cyl_dist'){ $nbm_store[29]=netrabilling_the_field($field);}
            if($field=='axis_dist'){ $nbm_store[30]=netrabilling_the_field($field);}
            if($field=='vn_dist'){ $nbm_store[31]=netrabilling_the_field($field);}
            if($field=='sph_near'){ $nbm_store[32]=netrabilling_the_field($field);}
            if($field=='cyl_near'){ $nbm_store[33]=netrabilling_the_field($field);}
            if($field=='axis_near'){ $nbm_store[34]=netrabilling_the_field($field);}
            if($field=='vn_near'){ $nbm_store[35]=netrabilling_the_field($field);}            
            if($field=='billing_description'){ $nbm_store[36]=netrabilling_the_field($field);}
            
         
            
            
        } 
//////////////////////My experimental code/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////            
            ?>
<div class="<?php  netrabilling_class(); ?>">
                <table class="form-table">
                                <?php
                                if($nbm_store[0]!=null){?>
				    <tr>       
                               
                                    <th class="label">Order No: </th>
                               
                                    <td><?php echo ''.$nbm_store[0]; ?></td>
                                        </tr>
                                <?php }  ?>                                       
				
                                
                               <?php
                               if($nbm_store[1]!=null){?>
				<tr>       
                                 
                                    <th class="label">Date </th>
                               
                                        <td><?php echo ''.$nbm_store[1]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[2]!=null){?>
				<tr>       
                             
                                    <th class="label">Customer Name </th>
                                <?php }  ?>
                                        <td><?php echo ''.$nbm_store[2]; ?></td>
                                                 
				</tr>                                
                                
                               <?php
                               if($nbm_store[3]!=null){?>
				<tr>       

                                    <th class="label">Customer Address</th>
            
                                        <td><?php echo ''.$nbm_store[3];  ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[4]!=null){?>
				<tr>       
                
                                    <th class="label">R </th>
  
                                        <td><?php echo ''.$nbm_store[4]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[5]!=null){?>
				<tr>       
                    
                                    <th class="label">O</th>
             
                                        <td><?php echo ''.$nbm_store[5]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[6]!=null){?>
				<tr>       
  
                                    <th class="label">M </th>
      
                                        <td><?php echo ''.$nbm_store[6]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[7]!=null){?>
				<tr>       
                      
                                    <th class="label">Ref By</th>
                        
                                        <td><?php echo ''.$nbm_store[7]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[8]!=null){?>
				<tr>       

                                    <th class="label">Frame No </th>
    
                                        <td><?php echo ''.$nbm_store[8]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[9]!=null){?>
				<tr>       
              
                                    <th class="label">Frame Name</th>

                                        <td><?php echo ''.$nbm_store[9]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[10]!=null){?>
				<tr>       
     
                                    <th class="label">Frame Qty</th>
                   
                                        <td><?php echo ''.$nbm_store[10]; ?></td>
                                <?php }  ?>                                       
				</tr>                                
                                
                               <?php
                               if($nbm_store[11]!=null){?>
				<tr>       
    
                                    <th class="label">lens 1 No </th>
                     
                                        <td><?php echo ''.$nbm_store[11]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[12]!=null){?>
				<tr>       
       
                                    <th class="label">Lens 1 name </th>
        
                                        <td><?php echo ''.$nbm_store[12]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[13]!=null){?>
				<tr>       
             
                                    <th class="label">Lens 1 Qty</th>
   
                                        <td><?php echo ''.$nbm_store[13]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[14]!=null){?>
				<tr>       
                    
                                    <th class="label">Lens 2 No </th>
                
                                        <td><?php echo ''.$nbm_store[14]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[15]!=null){?>
				<tr>       
          
                                    <th class="label">Lens 2 Name </th>
                   
                                        <td><?php echo ''.$nbm_store[15]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[16]!=null){?>
				<tr>       
         
                                    <th class="label">Lens 2 Qty</th>
       
                                        <td><?php echo ''.$nbm_store[16]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[17]!=null){?>
				<tr>       
           
                                    <th class="label">Total </th>
                  
                                        <td><?php echo ''.$nbm_store[17]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[18]!=null){?>
				<tr>       
         
                                    <th class="label">Adv </th>
               
                                        <td><?php echo ''.$nbm_store[18]; ?></td>
                                <?php }  ?>                                       
				</tr>
                                
                               <?php
                               if($nbm_store[19]!=null){?>
				<tr>       
            
                                    <th class="label">Bal </th>
                 
                                        <td><?php echo ''.$nbm_store[19]; ?></td>
                                <?php }  ?>                                       
				</tr>                                
			</table>
                    <br>
                    <br>
                    <br>
                    

                       <table class="nbm_spec" style="width:100%">
                                        <caption>Optics Specification RIGHT</caption>
                                        <tr class="nbm_spec">
                                          <th class="nbm_spec" colspan="5">RIGHT</th>
                                        <tr class="nbm_spec">
                                          <td class="nbm_spec"></td>
                                          <td class="nbm_spec">SPH</td>
                                          <td class="nbm_spec">CYC</td>
                                          <td class="nbm_spec">AXIS</td>
                                          <td class="nbm_spec">VN</td>
                                        </tr>
                                        <tr class="nbm_spec">
                                          <td class="nbm_spec">DIST</td>
                                          <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[20]!=null){?>
                                                         <?php echo ''.$nbm_store[20]; ?>
                                                <?php }  ?>                                            
                                         </td>		
                                         <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[21]!=null){?>
                                                         <?php echo ''.$nbm_store[21]; ?>
                                                <?php }  ?>                                             
                                         </td>
                                         <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[22]!=null){?>
                                                         <?php echo ''.$nbm_store[22]; ?>
                                                <?php }  ?>                                               
                                         </td>
                                         <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[23]!=null){?>
                                                         <?php echo ''.$nbm_store[23]; ?>
                                                <?php }  ?>                                             
                                         </td>
                                        </tr>
                                        <tr class="nbm_spec">
                                          <td class="nbm_spec">NEAR</td>
                                          <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[24]!=null){?>
                                                         <?php echo ''.$nbm_store[24]; ?>
                                                <?php }  ?>                                              
                                          </td>		
                                          <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[25]!=null){?>
                                                         <?php echo ''.$nbm_store[25]; ?>
                                                <?php }  ?>                                              
                                          </td>
                                          <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[26]!=null){?>
                                                         <?php echo ''.$nbm_store[26]; ?>
                                                <?php }  ?>		
                                          <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[27]!=null){?>
                                                         <?php echo ''.$nbm_store[27]; ?>
                                                <?php }  ?>                                              
                                          </td>
                                        </tr>   
                       </table>
                    <br>
                    <br>
                    <br>
                       <table class="nbm_spec" style="width:100%">
                                        <caption>Optics Specification LEFT</caption>
                                        <tr class="nbm_spec">
                                          <th class="nbm_spec" colspan="5">LEFT</th>
                                        <tr class="nbm_spec">
                                          <td class="nbm_spec"></td>
                                          <td class="nbm_spec">SPH</td>
                                          <td class="nbm_spec">CYC</td>
                                          <td class="nbm_spec">AXIS</td>
                                          <td class="nbm_spec">VN</td>
                                        </tr>
                                        <tr class="nbm_spec">
                                          <td class="nbm_spec">DIST</td>
                                          <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[28]!=null){?>
                                                         <?php echo ''.$nbm_store[28]; ?>
                                                <?php }  ?>                                            
                                         </td>		
                                         <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[29]!=null){?>
                                                         <?php echo ''.$nbm_store[29]; ?>
                                                <?php }  ?>                                             
                                         </td>
                                         <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[30]!=null){?>
                                                         <?php echo ''.$nbm_store[30]; ?>
                                                <?php }  ?>                                               
                                         </td>
                                         <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[31]!=null){?>
                                                         <?php echo ''.$nbm_store[31]; ?>
                                                <?php }  ?>                                             
                                         </td>
                                        </tr>
                                        <tr class="nbm_spec">
                                          <td class="nbm_spec">NEAR</td>
                                          <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[32]!=null){?>
                                                         <?php echo ''.$nbm_store[32]; ?>
                                                <?php }  ?>                                              
                                          </td>		
                                          <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[33]!=null){?>
                                                         <?php echo ''.$nbm_store[33]; ?>
                                                <?php }  ?>                                              
                                          </td>
                                          <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[34]!=null){?>
                                                         <?php echo ''.$nbm_store[34]; ?>
                                                <?php }  ?>		
                                          <td class="nbm_spec">
                                                <?php
                                                if($nbm_store[35]!=null){?>
                                                         <?php echo ''.$nbm_store[35]; ?>
                                                <?php }  ?>                                              
                                          </td>
                                        </tr>   
                       </table> 
                    <br><br><br>
                    			<?php// the description ?>	
                               <?php
                               if($nbm_store[36]!=null){?>
				       

                                    <h4 class="label">Description </h4>

                                        <h3><?php echo ''.$nbm_store[36]; ?></h3>
                                        
                                                                     
				 <?php }  ?> 
</div>    
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<?php } ?>