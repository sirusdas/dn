<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrastock/views/single-item.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * */

$inventory_display = netrastock_get_display_settings('detail');
$display_labels = netrastock_get_config('display_detail_labels');

netrastock_get_items();
if (netrastock_have_items()) {
	while(netrastock_have_items()) {
		netrastock_the_item(); ?>
		<div class="<?php netrastock_class(); ?>">
		<?php foreach($inventory_display AS $sort=>$field) { ?>
			<div class="<?php echo $field; ?>">
				<?php if ($display_labels) { ?>
					<span class="netrastock_label"><?php netrastock_the_label($field); ?></span>
				<?php }	
				netrastock_the_field($field); ?>
			</div>
	 <?php } ?>
	 	</div>
<?php }
	netrastock_backlink();

	do_action('NSM_before_reserve_form');

	echo $reserve_form = netrastock_reserve_form();

	do_action('NSM_after_reserve_form');
}