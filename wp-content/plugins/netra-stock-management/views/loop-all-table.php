<?php

/**
 * DEVELOPERS:
 * This file is the default view, and is designed to utilize the "Display" settings from the dashboard.
 * 
 * This file is loaded when the display setting "Display Listing as Table" is set to "Yes".
 * 
 * You can absolutely override this utilizing NETRA stock's Override functionality.
 * Look at the file "loop-all-sample.php" for an example of how to modify these files.
 * 
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrastock/views/loop-shortcode.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality
 * */

if (netrastock_is_single()) {
	netrastock_get_template_part('single-item');
	return;
}

netrastock_get_items();
echo netrastock_filter_form('filter=true&sort=true');

global $inventory_display;
$inventory_display = netrastock_get_display_settings('listing');

if (netrastock_have_items()) { ?>
	<table class="netrastock_loop netrastock_loop_all netrastock_loop_all_table">
		<thead>
			<tr>
			<?php if (netrastock_get_config('display_listing_labels')) {
					foreach($inventory_display AS $sort => $field) { ?>
						<th class="<?php echo netrastock_label_class($field); ?>"><?php netrastock_the_label($field); ?></th>
			<?php 	}
				} ?>
			</tr>
		</thead>
		<tbody>
		<?php while (netrastock_have_items()) {
				netrastock_the_item();
				netrastock_get_template_part('single-loop-all', 'table');
			} ?>
		</tbody>
	</table>
<?php 
	echo netrastock_pagination();
	} else { ?>
	<p class="netrastock_warning"><?php NSMCore::_e('No Inventory Items'); ?></p>
<?php } ?>