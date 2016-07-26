<?php

/**
 * 
 * You can absolutely override this utilizing NETRA Billing's Override functionality.
 * Look at the file "loop-all-sample.php" for an example of how to modify these files.
 * 
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrabilling/views/loop-shortcode.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality
 * */

if (netrabilling_have_items()) { ?>
	<ul>
		<?php while (netrabilling_have_items()) {
				netrabilling_the_item();
				netrabilling_get_template_part('widget-latest-items-single');
			} ?>
	</ul>
<?php 
	} else { ?>
	<p class="netrabilling_warning"><?php NBMCore::_e('No Inventory Items'); ?></p>
<?php } ?>