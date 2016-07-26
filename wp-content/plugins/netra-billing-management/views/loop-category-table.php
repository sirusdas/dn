<?php

/**
 * DEVELOPERS:
 * This file is the default view, and is designed to utilize the "Display" settings from the dashboard.
 *
 * This file is loaded when the display setting "Display Listing as Table" is set to "Yes".
 *
 * You can absolutely override this utilizing NETRA Billing's Override functionality.
 * Look at the file "loop-all-sample.php" for an example of how to modify these files.
 *
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrabilling/views/loop-shortcode.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality
 * */

if ( netrabilling_is_single() ) {
	netrabilling_get_template_part( 'single-item' );

	return;
}

netrabilling_get_items();
echo netrabilling_filter_form( 'filter=true&sort=true' );

global $inventory_display;
$inventory_display = netrabilling_get_display_settings( 'listing' );

if ( netrabilling_have_items() ) { ?>
	<table
		class="netrabilling_loop netrabilling_loop_category netrabilling_loop_category-<?php echo netrabilling_get_the_category_ID(); ?>">
		<thead>
		<tr>
			<?php if ( netrabilling_get_config( 'display_listing_labels' ) ) {
				foreach ( $inventory_display AS $sort => $field ) { ?>
					<th class="<?php echo netrabilling_label_class( $field ); ?>"><?php netrabilling_the_label( $field ); ?></th>
				<?php }
			} ?>
		</tr>
		</thead>
		<tbody>
		<?php while ( netrabilling_have_items() ) {
			netrabilling_the_item();
			netrabilling_get_template_part( 'single-loop-category', 'table' );
		} ?>
		</tbody>
	</table>
	<?php
	echo netrabilling_pagination();
} else { ?>
	<p class="netrabilling_warning"><?php NBMCore::_e( 'No Inventory Items' ); ?></p>
<?php } ?>