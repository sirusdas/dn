<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrastock/views/single-loop-all.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * 
 * NOTICE:
 * This is a sample file that demonstrates some of the functions that you can call, in order to give you
 * the capability to structure a view however you like.
 * 
 * To use this, you would copy it to your overrides folder, and rename it either "single-loop-all.php", 
 * or "single-loop-all-table.php" (depending on your Display settings in the dashboard)
 * */
?>
<tr class="<?php netrastock_class(); ?>">
	<td><a href="<?php netrastock_the_permalink(); ?>"><?php netrastock_the_number(); ?></a></td>
	<td><?php netrastock_the_name(); ?></td>
	<td><?php netrastock_the_description(); ?></td>
	<td><?php netrastock_the_featured_image(); ?></td>
	<td><?php netrastock_the_size(); ?></td>
	<td><?php netrastock_the_manufacturer(); ?></td>
	<td><?php netrastock_the_make(); ?></td>
	<td><?php netrastock_the_model(); ?></td>
	<td><?php netrastock_the_year(); ?></td>
	<td><?php netrastock_the_serial(); ?></td>
	<td><?php netrastock_the_quantity(); ?></td>
	<td><?php netrastock_the_reserved(); ?></td>
	<td><?php netrastock_the_price(); ?></td>
	<td><?php netrastock_the_category(); ?></td>
	<td><?php netrastock_the_date(); ?></td>
	<td><?php netrastock_the_date_updated(); ?></td>
</tr>