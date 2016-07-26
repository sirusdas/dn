<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netracustomer/views/single-item.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * */

$inventory_display = netracustomer_get_display_settings('detail');
$display_labels = netracustomer_get_config('display_detail_labels');

netracustomer_get_items();
if (netracustomer_have_items()) {
	while(netracustomer_have_items()) {
		netracustomer_the_item(); ?>
		<div class="<?php netracustomer_class(); ?>">
		<?php foreach($inventory_display AS $sort=>$field) { ?>
			<div class="<?php echo $field; ?>">
				<?php if ($display_labels) { ?>
					<span class="netracustomer_label"><?php netracustomer_the_label($field); ?></span>
				<?php }	
				netracustomer_the_field($field); ?>
			</div>
	 <?php } ?>
	 	</div>
<?php }
	netracustomer_backlink();

	do_action('ncm_before_reserve_form');

	echo $reserve_form = netracustomer_reserve_form();

	do_action('ncm_after_reserve_form');
}