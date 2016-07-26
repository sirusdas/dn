<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netracustomer/views/single-item.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * */

echo "<br><br><br>HERE";

netracustomer_get_items();
if (netracustomer_have_items()) {
		while(netracustomer_have_items()) {
		netracustomer_the_item(); ?>
			<div class="<?php netracustomer_class(); ?>">
				<p><?php netracustomer_the_number(); ?></p>
				<p><?php netracustomer_the_name(); ?></p>
				<p><?php netracustomer_the_description(); ?></p>
				
				<p><?php netracustomer_the_size(); ?></p>
				<p><?php netracustomer_the_manufacturer(); ?></p>
				<p><?php netracustomer_the_make(); ?></p>
				<p><?php netracustomer_the_model(); ?></p>
				<p><?php netracustomer_the_year(); ?></p>
				<p><?php netracustomer_the_serial(); ?></p>
				<p><?php netracustomer_the_quantity(); ?></p>
				<p><?php netracustomer_the_reserved(); ?></p>
				<p><?php netracustomer_the_price(); ?></p>
				<p><?php netracustomer_the_status(); ?></p>
				<p><?php netracustomer_the_category(); ?></p>
				<p><?php netracustomer_the_date(); ?></p>
				<p><?php netracustomer_the_date_updated(); ?></p>
				<div class="images">
					<?php netracustomer_the_images('medium'); ?>
				</div>
			</div>	
		<?php }
}

?>