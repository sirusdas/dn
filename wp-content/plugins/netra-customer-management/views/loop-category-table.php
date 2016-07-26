<?php

/**
 * DEVELOPERS:
 * This file is the default view, and is designed to utilize the "Display" settings from the dashboard.
 *
 * This file is loaded when the display setting "Display Listing as Table" is set to "Yes".
 *
 * You can absolutely override this utilizing NETRA Customer's Override functionality.
 * Look at the file "loop-all-sample.php" for an example of how to modify these files.
 *
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netracustomer/views/loop-shortcode.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality
 * */

if ( netracustomer_is_single() ) {
	netracustomer_get_template_part( 'single-item' );

	return;
}

netracustomer_get_items();
echo netracustomer_filter_form( 'filter=true&sort=true' );

global $inventory_display;
$inventory_display = netracustomer_get_display_settings( 'listing' );

if ( netracustomer_have_items() ) { ?>
	<table
		class="netracustomer_loop netracustomer_loop_category netracustomer_loop_category-<?php echo netracustomer_get_the_category_ID(); ?>">
		<thead>
		<tr>
			<?php if ( netracustomer_get_config( 'display_listing_labels' ) ) {
				foreach ( $inventory_display AS $sort => $field ) { ?>
					<th class="<?php echo netracustomer_label_class( $field ); ?>"><?php netracustomer_the_label( $field ); ?></th>
				<?php }
			} ?>
		</tr>
		</thead>
		<tbody>
		<?php while ( netracustomer_have_items() ) {
			netracustomer_the_item();
			netracustomer_get_template_part( 'single-loop-category', 'table' );
		} ?>
		</tbody>
	</table>
	<?php
	echo netracustomer_pagination();
} else { ?>
	<p class="netracustomer_warning"><?php NCMCore::_e( 'No Inventory Items' ); ?></p>
<?php } ?>