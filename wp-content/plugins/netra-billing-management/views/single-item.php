<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrabilling/views/single-item.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * */

$inventory_display = netrabilling_get_display_settings('detail');
$display_labels = netrabilling_get_config('display_detail_labels');
netrabilling_get_items();
if (netrabilling_have_items()) {
	while(netrabilling_have_items()) {
		netrabilling_the_item(); ?>
		<div class="<?php netrabilling_class(); ?>">
		<?php foreach($inventory_display AS $sort=>$field) { ?>
			<div class="<?php echo $field; ?>">
				<?php if ($display_labels) { ?>
					<span class="netrabilling_label"><?php netrabilling_the_label($field); ?></span>
				<?php }	
				netrabilling_the_field($field); ?>
			</div>
	 <?php } ?>
	 	</div>
<?php }
	netrabilling_backlink();

	do_action('nbm_before_reserve_form');

	echo $reserve_form = netrabilling_reserve_form();

	do_action('nbm_after_reserve_form');
}