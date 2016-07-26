<?php

session_start();


/**
 * DEVELOPERS:
 * This file is the default view, and is designed to utilize the "Display" settings from the dashboard.
 * 
 * This file is loaded when the display setting "Display Listing as Table" is set to "No".
 * 
 * You can absolutely override this utilizing NETRA Billing's Override functionality.
 * Look at the file "loop-all-sample.php" for an example of how to modify these files.
 * 
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrabilling/views/loop-shortcode.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality
 * */
$_SESSION["total"] = 0;
$_SESSION["balance"] = 0;
$_SESSION["advance"] = 0;
$nbm_bill_id=$_GET['id'];
$nbm_tr=$_GET['tr'];
if (netrabilling_is_single()) {
	netrabilling_get_template_part('single-item');
	return;
}

netrabilling_get_items();

if($nbm_tr==1){
echo netrabilling_filter_dates('filter=true&sort=false');
}
else{
    if($nbm_bill_id==null){
        echo netrabilling_filter_form('filter=true&sort=true');
    }
}

global $inventory_display;
$inventory_display = netrabilling_get_display_settings('listing');

global $display_labels;
$display_labels = netrabilling_get_config('display_listing_labels');

if (netrabilling_have_items()) { ?>
	<div class="netrabilling_loop netra_billing_loop_all netrabilling_loop_all_div">
		<?php while (netrabilling_have_items()) {
				netrabilling_the_item();
				netrabilling_get_template_part('single-loop-all');
			} ?>
            <?php
            if($_SESSION["total"]!=null){  ?>
              <h4>Total Amount:  <?php echo ''.$_SESSION["total"] ?> </h4>
              <h4>Advance:  <?php echo ''.$_SESSION["advance"] ?> </h4>              
              <h4>Balance:  <?php echo ''.$_SESSION["balance"] ?> </h4>
              <?php }?>
	</div>
<?php 
    if($nbm_bill_id==null){
      echo netrabilling_pagination(); 
      
    }
	} 
        else { ?>
	<p class="netrabilling_warning"><?php NBMCore::_e('No Inventory Items'); ?></p>
<?php } ?>

<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('.MyDate').datepicker({
        dateFormat : 'yy-mm-dd'
    });
});

</script>