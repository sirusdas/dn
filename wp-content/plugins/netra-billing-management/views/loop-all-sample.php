<?php

/**
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrabilling/views/loop-shortcode.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality
 * */

if (netrabilling_is_single()) {
	netrabilling_get_template_part('single-item');
	return;
}

?>
<style>
	tr.netrabilling {
		border: 1px solid #ccc;
	}
	
	tr.netrabilling td {
		padding: 5px 0;
	}
	
	tr.netrabilling_even td {
		background: #eee;
	}
	
	tr.netrabilling_odd td {
		background: #ffe;
	}
</style>
<?php
netrabilling_get_items();
echo netrabilling_filter_form('filter=true&sort=true');

if (netrabilling_have_items()) { ?>
	<table>
		<thead>
			<tr>
				<th><?php netrabilling_the_label("inventory_number"); ?></th>
				<th><?php netrabilling_the_label("inventory_name"); ?></th>
				<th><?php netrabilling_the_label("inventory_description"); ?></th>
				<th>Image</th>
				<th><?php netrabilling_the_label("inventory_size"); ?></th>
				<th><?php netrabilling_the_label("inventory_manufacturer"); ?></th>
				<th><?php netrabilling_the_label("inventory_make"); ?></th>
				<th><?php netrabilling_the_label("inventory_model"); ?></th>
				<th><?php netrabilling_the_label("inventory_year"); ?></th>
				<th><?php netrabilling_the_label("inventory_serial"); ?></th>
				<th><?php netrabilling_the_label("inventory_quantity"); ?></th>
				<th><?php netrabilling_the_label("inventory_quantity_reserved"); ?></th>
				<th><?php netrabilling_the_label("inventory_price"); ?></th>
				<th><?php netrabilling_the_label("inventory_category"); ?></th>
				<th><?php netrabilling_the_label("inventory_date_added"); ?></th>
				<th><?php netrabilling_the_label("inventory_date_updated"); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php while (netrabilling_have_items()) {
				netrabilling_the_item();
				netrabilling_get_template_part('single-loop-all');
			} ?>
		</tbody>
	</table>
<?php 
	echo netrabilling_pagination();
	} else { ?>
	<p class="netrabilling_warning">No Inventory Items</p>
<?php } ?>