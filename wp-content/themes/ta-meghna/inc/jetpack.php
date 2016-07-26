<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package TA Meghna
 */

/**
 * Get post views from Jetpack.
 */
function jp_get_post_views( $postID ) {
	if ( function_exists( 'stats_get_csv' ) ) {
		$post_stats = stats_get_csv( 'postviews', array( 'days' => 365, 'limit' => -1 ) );
		foreach ( $post_stats as $p ) {
			if ( $p['post_id'] == $postID ) { ?>
				<span class="post-view"><?php echo '<i class="fa fa-eye"></i>' . number_format_i18n( $p['views'] ); ?></span>
			<?php }
		}
	} ?>
<?php }