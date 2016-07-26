<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package TA Meghna
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function ta_meghna_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'ta_meghna_body_classes' );

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function ta_meghna_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'ta-meghna' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'ta_meghna_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function ta_meghna_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'ta_meghna_render_title' );
endif;

/**
 * Get images attached to post.
 */
if ( !function_exists( 'ta_post_images' ) ) {
	function ta_post_images( $args=array() ) {
		global $post;

		$defaults = array(
			'numberposts'		=> -1,
			'order'				=> 'ASC',
			'orderby'			=> 'menu_order',
			'post_mime_type'	=> 'image',
			'post_parent'		=>  $post->ID,
			'post_type'			=> 'attachment',
		);
		$args = wp_parse_args( $args, $defaults );

		return get_posts( $args );
	}
}

/**
 * Trims a string of words to a specified number of characters.
 */
function trim_characters( $text, $length = 150, $append = '&hellip;' ) {

	$length = (int)$length;
	$text = trim( strip_tags( strip_shortcodes($text) ) );
	$text = preg_replace( '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $text );

	if ( strlen( $text ) > $length ) {
		$text = substr( $text, 0, $length + 1 );
		$words = preg_split( "/[\s]|&nbsp;/", $text, -1, PREG_SPLIT_NO_EMPTY );
		preg_match( "/[\s]|&nbsp;/", $text, $lastchar, 0, $length );
		if ( empty( $lastchar ) )
			array_pop( $words );

		$text = implode( ' ', $words ) . $append;
	}

	return $text;
}

/**
 * Posts Page Custom Template.
 */
function posts_page_custom_template( $template) {
	global $wp_query;

	if( true == ( $posts_per_page_id = get_option( 'page_for_posts' ) ) ){
		$page_id = $wp_query->get_queried_object_id();
		if( $page_id == $posts_per_page_id ){
			$theme_directory = get_stylesheet_directory() ."/";
			$page_template   = get_post_meta( $page_id, '_wp_page_template', true );
			if( $page_template != 'default' ){
				if( is_child_theme() && !file_exists( $theme_directory . $page_template ) ){
					$theme_directory = get_template_directory();
				}
				return $theme_directory . $page_template;
			}
		}
	}

	return $template;
}
add_filter( 'template_include', 'posts_page_custom_template' );

/**
 * Customize Tag Cloud Widget font size.
 */
function custom_tag_cloud_widget( $args ) {
	$args['largest'] = 1; //largest tag
	$args['smallest'] = 1; //smallest tag
	$args['unit'] = 'em'; //tag font unit
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'custom_tag_cloud_widget' );

/**
 * Add numeric pagination.
 */
function ta_pagination( $pages = '', $range = 4 ) {  
     $showitems = ( $range * 2 )+1;

     global $paged;
     if( empty( $paged ) ) $paged = 1;

     if( $pages == '' ) {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if( !$pages ) {
			$pages = 1;
		}
     }  

     if( 1 != $pages ) {
		echo '<nav class="post-pagination wow fadeIn"  data-wow-duration="1000ms" data-wow-delay="300ms">';
		echo "<ul>";
		if( $paged > 2 && $paged > $range+1 && $showitems < $pages ) echo "<li><a href='".get_pagenum_link(1)."'>&laquo;</a></li>";
		if( $paged > 1 && $showitems < $pages ) echo "<li><a href='".get_pagenum_link( $paged - 1 )."'>&lsaquo;</a></li>";

		for ( $i=1; $i <= $pages; $i++ ) {
			if ( 1 != $pages && ( !( $i >= $paged+$range+1 || $i <= $paged-$range-1 ) || $pages <= $showitems ) ) {
				echo ( $paged == $i ) ? "<li class=\"active\"><a href='".get_pagenum_link($i)."'>".$i."</a></li>" : "<li><a href='".get_pagenum_link($i)."'>".$i."</a></li>";
			}
		}

		if ( $paged < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link( $paged + 1 )."'>&rsaquo;</a></li>";  
		if ( $paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages ) echo "<li><a href='".get_pagenum_link( $pages )."'>&raquo;</a></li>";
		echo "</ul>";
		echo "</nav>";
	}
}