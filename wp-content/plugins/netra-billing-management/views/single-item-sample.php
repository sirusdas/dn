<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrabilling/views/single-item.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * */

echo "<br><br><br>HERE";

netrabilling_get_items();
if (netrabilling_have_items()) {
		while(netrabilling_have_items()) {
		netrabilling_the_item(); ?>
			<div class="<?php netrabilling_class(); ?>">
				<p><?php netrabilling_the_number(); ?></p>
				<p><?php netrabilling_the_name(); ?></p>
				<p><?php netrabilling_the_description(); ?></p>
				
				<p><?php netrabilling_the_size(); ?></p>
				<p><?php netrabilling_the_manufacturer(); ?></p>
				<p><?php netrabilling_the_make(); ?></p>
				<p><?php netrabilling_the_model(); ?></p>
				<p><?php netrabilling_the_year(); ?></p>
				<p><?php netrabilling_the_serial(); ?></p>
				<p><?php netrabilling_the_quantity(); ?></p>
				<p><?php netrabilling_the_reserved(); ?></p>
				<p><?php netrabilling_the_price(); ?></p>
				<p><?php netrabilling_the_status(); ?></p>
				<p><?php netrabilling_the_category(); ?></p>
				<p><?php netrabilling_the_date(); ?></p>
				<p><?php netrabilling_the_date_updated(); ?></p>
				<div class="images">
					<?php netrabilling_the_images('medium'); ?>
				</div>
			</div>	
		<?php }
}

?>