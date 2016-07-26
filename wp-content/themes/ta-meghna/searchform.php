<?php
/**
 * Search Form Template
 *
 * @package TA Meghna
 */
?>

<form id="search-form" method="get" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" class="form-control" placeholder="<?php esc_attr_e( 'Search here &hellip;', 'ta-meghna' ); ?>" autocomplete="on" name="s">
	<button type="submit" title="<?php esc_attr_e( 'Search', 'ta-meghna' ); ?>" id="search-submit">
		<i class="fa fa-search"></i>
	</button>
</form>