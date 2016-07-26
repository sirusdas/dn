<?php

/**
 * 
 * You can absolutely override this utilizing Netra Customer's Override functionality.
 * Look at the file "loop-all-sample.php" for an example of how to modify these files.
 * 
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netracustomer/views/loop-shortcode.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality
 * */

if (netracustomer_have_items()) { ?>
	<ul>
		<?php while (netracustomer_have_items()) {
				netracustomer_the_item();
				netracustomer_get_template_part('widget-latest-items-single');
			} ?>
	</ul>
<?php 
	} else { ?>
	<p class="netracustomer_warning"><?php NCMCore::_e('No Inventory Items'); ?></p>
<?php } ?>