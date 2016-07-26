<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrastock/views/single-item.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * */

echo "<br><br><br>HERE";

netrastock_get_items();
if (netrastock_have_items()) {
		while(netrastock_have_items()) {
		netrastock_the_item(); ?>
			<div class="<?php netrastock_class(); ?>">
				<p><?php netrastock_the_number(); ?></p>
				<p><?php netrastock_the_name(); ?></p>
				<p><?php netrastock_the_description(); ?></p>
				
				<p><?php netrastock_the_size(); ?></p>
				<p><?php netrastock_the_manufacturer(); ?></p>
				<p><?php netrastock_the_make(); ?></p>
				<p><?php netrastock_the_model(); ?></p>
				<p><?php netrastock_the_year(); ?></p>
				<p><?php netrastock_the_serial(); ?></p>
				<p><?php netrastock_the_quantity(); ?></p>
				<p><?php netrastock_the_reserved(); ?></p>
				<p><?php netrastock_the_price(); ?></p>
				<p><?php netrastock_the_status(); ?></p>
				<p><?php netrastock_the_category(); ?></p>
				<p><?php netrastock_the_date(); ?></p>
				<p><?php netrastock_the_date_updated(); ?></p>
				<div class="images">
					<?php netrastock_the_images('medium'); ?>
				</div>
			</div>	
		<?php }
}

?>