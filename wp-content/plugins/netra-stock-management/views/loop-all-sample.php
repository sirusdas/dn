<?php

/**
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrastock/views/loop-shortcode.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality
 * */

if (netrastock_is_single()) {
	netrastock_get_template_part('single-item');
	return;
}

?>
<style>
	tr.netrastock {
		border: 1px solid #ccc;
	}
	
	tr.netrastock td {
		padding: 5px 0;
	}
	
	tr.netrastock_even td {
		background: #eee;
	}
	
	tr.netrastock_odd td {
		background: #ffe;
	}
</style>
<?php
netrastock_get_items();
echo netrastock_filter_form('filter=true&sort=true');

if (netrastock_have_items()) { ?>
	<table>
		<thead>
			<tr>
				<th><?php netrastock_the_label("inventory_number"); ?></th>
				<th><?php netrastock_the_label("inventory_name"); ?></th>
				<th><?php netrastock_the_label("inventory_description"); ?></th>
				<th>Image</th>
				<th><?php netrastock_the_label("inventory_size"); ?></th>
				<th><?php netrastock_the_label("inventory_manufacturer"); ?></th>
				<th><?php netrastock_the_label("inventory_make"); ?></th>
				<th><?php netrastock_the_label("inventory_model"); ?></th>
				<th><?php netrastock_the_label("inventory_year"); ?></th>
				<th><?php netrastock_the_label("inventory_serial"); ?></th>
				<th><?php netrastock_the_label("inventory_quantity"); ?></th>
				<th><?php netrastock_the_label("inventory_quantity_reserved"); ?></th>
				<th><?php netrastock_the_label("inventory_price"); ?></th>
				<th><?php netrastock_the_label("inventory_category"); ?></th>
				<th><?php netrastock_the_label("inventory_date_added"); ?></th>
				<th><?php netrastock_the_label("inventory_date_updated"); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php while (netrastock_have_items()) {
				netrastock_the_item();
				netrastock_get_template_part('single-loop-all');
			} ?>
		</tbody>
	</table>
<?php 
	echo netrastock_pagination();
	} else { ?>
	<p class="netrastock_warning">No Inventory Items</p>
<?php } ?>