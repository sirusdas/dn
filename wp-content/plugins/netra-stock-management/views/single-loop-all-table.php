<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled netrastock/views/single-loop-all.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * 
 * NOTICE:
 * This file is designed to be "automatic", and display the fields you have selected in your Display Settings.
 * You can completely customize the file - if that's your intention, it is recommended that you refer to
 * the view file titled "single-loop-all-sample.php" for examples of functions to use for complete control.
 * */

global $inventory_display;

?>
<tr class="<?php netrastock_class(); ?>">
	<?php foreach($inventory_display AS $sort=>$field) { ?>
		<td class="<?php echo $field; ?>">
		<?php if ($field != 'inventory_description') { ?>
			<a href="<?php netrastock_the_permalink(); ?>"><?php netrastock_the_field($field); ?></a>
		<?php } else { ?>
			<?php netrastock_the_field($field); ?>
		<?php }
		} ?>
		</td>
</tr>