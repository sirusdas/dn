<?php

/**
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netracustomer/views/loop-shortcode.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality
 * */

if (netracustomer_is_single()) {
	netracustomer_get_template_part('single-item');
	return;
}

?>
<style>
	tr.netracustomer {
		border: 1px solid #ccc;
	}
	
	tr.netracustomer td {
		padding: 5px 0;
	}
	
	tr.netracustomer_even td {
		background: #eee;
	}
	
	tr.netracustomer_odd td {
		background: #ffe;
	}
</style>
<?php
netracustomer_get_items();
echo netracustomer_filter_form('filter=true&sort=true');

if (netracustomer_have_items()) { ?>
	<table>
		<thead>
			<tr>
				<th><?php netracustomer_the_label("inventory_number"); ?></th>
				<th><?php netracustomer_the_label("inventory_name"); ?></th>
				<th><?php netracustomer_the_label("inventory_description"); ?></th>
				<th>Image</th>
				<th><?php netracustomer_the_label("inventory_size"); ?></th>
				<th><?php netracustomer_the_label("inventory_manufacturer"); ?></th>
				<th><?php netracustomer_the_label("inventory_make"); ?></th>
				<th><?php netracustomer_the_label("inventory_model"); ?></th>
				<th><?php netracustomer_the_label("inventory_year"); ?></th>
				<th><?php netracustomer_the_label("inventory_serial"); ?></th>
				<th><?php netracustomer_the_label("inventory_quantity"); ?></th>
				<th><?php netracustomer_the_label("inventory_quantity_reserved"); ?></th>
				<th><?php netracustomer_the_label("inventory_price"); ?></th>
				<th><?php netracustomer_the_label("inventory_category"); ?></th>
				<th><?php netracustomer_the_label("inventory_date_added"); ?></th>
				<th><?php netracustomer_the_label("inventory_date_updated"); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php while (netracustomer_have_items()) {
				netracustomer_the_item();
				netracustomer_get_template_part('single-loop-all');
			} ?>
		</tbody>
	</table>
<?php 
	echo netracustomer_pagination();
	} else { ?>
	<p class="netracustomer_warning">No Inventory Items</p>
<?php } ?>