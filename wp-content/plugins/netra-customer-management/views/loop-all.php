<?php


/**
 * DEVELOPERS:
 * This file is the default view, and is designed to utilize the "Display" settings from the dashboard.
 * 
 * This file is loaded when the display setting "Display Listing as Table" is set to "No".
 * 
 * You can absolutely override this utilizing NETRA Customer's Override functionality.
 * Look at the file "loop-all-sample.php" for an example of how to modify these files.
 * 
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netracustomer/views/loop-shortcode.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality
 * */

if (netracustomer_is_single()) {
	netracustomer_get_template_part('single-item');
	return;
}

netracustomer_get_items();
echo netracustomer_filter_form('filter=true&sort=true');

global $inventory_display;
$inventory_display = netracustomer_get_display_settings('listing');

global $display_labels;
$display_labels = netracustomer_get_config('display_listing_labels');

if (netracustomer_have_items()) { ?>
	<div class="netracustomer_loop netra_customer_loop_all netracustomer_loop_all_div">
		<?php while (netracustomer_have_items()) {
				netracustomer_the_item();
				netracustomer_get_template_part('single-loop-all');
			} ?>
	</div>
<?php 
	echo netracustomer_pagination();
	} else { ?>
	<p class="netracustomer_warning"><?php NCMCore::_e('No Inventory Items'); ?></p>
<?php } ?>