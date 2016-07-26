<?php
/**
 * TA Meghna functions and definitions
 *
 * @package TA Meghna
 */

/*
 * Make theme available for translation.
 * Translations can be filed in the /languages/ directory.
 * If you're building a theme based on TA Meghna, use a find and replace
 * to change 'ta-meghna' to the name of your theme in all the template files
 */
load_theme_textdomain( 'ta-meghna', get_template_directory() . '/languages' );

 /**
 * Include the Redux theme options Framework.
 */
if ( !class_exists( 'ReduxFramework' ) ) {
	require_once( get_template_directory() . '/redux/framework.php' );
}

/**
 * Register all the theme options.
 */
require_once( get_template_directory() . '/inc/redux-config.php' );

/**
 * Theme options functions.
 */
require_once( get_template_directory() . '/inc/ta-option.php' );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 750; /* pixels */
}

/**
 * Set the content width for full width pages with no sidebar.
 */
function full_width_page() {
	if ( is_page_template( 'template-full-width-page.php' ) ) {
		global $content_width;
		$content_width = 1140; /* pixels */
	}
}
add_action('template_redirect', 'full_width_page');

if ( ! function_exists( 'ta_meghna_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function ta_meghna_setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'ta-meghna' ),
		'secondary' => __( 'Secondary Menu', 'ta-meghna' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'gallery', 'audio', 'video',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'ta_meghna_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // ta_meghna_setup
add_action( 'after_setup_theme', 'ta_meghna_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function ta_meghna_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'ta-meghna' ),
		'id'            => 'sidebar-right',
		'description'   => __( 'Main sidebar that appears on the right.', 'ta-meghna' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeIn">',
		'after_widget'  => '</aside>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	) );

	register_widget( 'ta_post_tabs_widget' );
	register_widget( 'ta_mailchimp_widget' );
}
add_action( 'widgets_init', 'ta_meghna_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function ta_meghna_scripts() {
	wp_enqueue_style( 'ta-meghna-font-awesome-style', get_template_directory_uri() . '/layouts/font-awesome.min.css', array(), '4.3.0', 'all' );

	wp_enqueue_style( 'ta-meghna-bootstrap-style', get_template_directory_uri() . '/layouts/bootstrap.min.css', array(), '3.3.4', 'all' );

	wp_enqueue_style( 'ta-meghna-animate-style', get_template_directory_uri() . '/layouts/animate.min.css', array(), '', 'all' );

	wp_enqueue_style( 'ta-meghna-owl-carousel-style', get_template_directory_uri() . '/layouts/owl.carousel.css', array(), '1.3.3', 'all' );

	wp_enqueue_style( 'ta-meghna-grid-component-style', get_template_directory_uri() . '/layouts/grid-component.css', array(), '', 'all' );

	wp_enqueue_style( 'ta-meghna-slit-slider-style', get_template_directory_uri() . '/layouts/slit-slider.css', array(), '', 'all' );

	wp_enqueue_style( 'ta-meghna-style', get_stylesheet_uri() );

	wp_enqueue_style( 'ta-meghna-responsive-style', get_template_directory_uri() . '/layouts/responsive.css', array(), '', 'all' );

	wp_enqueue_style( 'GoogleFonts-Oswald', 'http://fonts.googleapis.com/css?family=Oswald:400,300', array(), '', 'all' );

	wp_enqueue_style( 'GoogleFonts-Ubuntu', 'http://fonts.googleapis.com/css?family=Ubuntu:400,300', array(), '', 'all' );

	wp_enqueue_script( 'ta-meghna-modernizr', get_template_directory_uri() . '/js/modernizr.min.js', array(), '2.8.3', true );

	wp_enqueue_script( 'ta-meghna-bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '3.3.4', true );

	wp_enqueue_script( 'ta-meghna-slitslider', get_template_directory_uri() . '/js/jquery.slitslider.js', array('jquery'), '1.1.0', true );

	wp_enqueue_script( 'ta-meghna-ba-cond', get_template_directory_uri() . '/js/jquery.ba-cond.min.js', array('jquery'), '0.1', true );

	wp_enqueue_script( 'ta-meghna-parallax', get_template_directory_uri() . '/js/jquery.parallax.js', array('jquery'), '1.1.3', true );

	wp_enqueue_script( 'ta-meghna-owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array('jquery'), '1.3.3', true );

	wp_enqueue_script( 'ta-meghna-mixitup', get_template_directory_uri() . '/js/jquery.mixitup.min.js', array('jquery'), '2.1.7', true );

	wp_enqueue_script( 'ta-meghna-nicescroll', get_template_directory_uri() . '/js/jquery.nicescroll.min.js', array('jquery'), '3.6.0', true );

	wp_enqueue_script( 'ta-meghna-appear', get_template_directory_uri() . '/js/jquery.appear.js', array('jquery'), '', true );

	wp_enqueue_script( 'ta-meghna-easypiechart', get_template_directory_uri() . '/js/jquery.easypiechart.min.js', array('jquery'), '2.1.6', true );

	wp_enqueue_script( 'ta-meghna-easing', get_template_directory_uri() . '/js/jquery.easing.js', array('jquery'), '1.3', true );

	wp_enqueue_script( 'ta-meghna-tweetie', get_template_directory_uri() . '/js/tweetie.min.js', array('jquery'), '', true );

	wp_enqueue_script( 'ta-meghna-nav', get_template_directory_uri() . '/js/jquery.nav.js', array('jquery'), '3.0.0', true );

	wp_enqueue_script( 'ta-meghna-sticky', get_template_directory_uri() . '/js/jquery.sticky.js', array('jquery'), '1.0.0', true );

	wp_enqueue_script( 'ta-meghna-countTo', get_template_directory_uri() . '/js/jquery.countTo.js', array('jquery'), '', true );

	wp_enqueue_script( 'ta-meghna-wow', get_template_directory_uri() . '/js/wow.min.js', array(), '1.0.3', true );

	wp_enqueue_script( 'ta-meghna-fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), '1.1', true );

	wp_enqueue_script( 'ta-meghna-grid', get_template_directory_uri() . '/js/grid.js', array('jquery'), '', true );

	wp_enqueue_script( 'ta-meghna-app', get_template_directory_uri() . '/js/app.js', array('jquery'), '', true );
	$translation_array = array( 'templateUrl' => get_stylesheet_directory_uri(), 'lat' => ta_option( 'google_map_lat' ), 'lon' => ta_option( 'google_map_lon' ) );
	wp_localize_script( 'ta-meghna-app', 'ta_script_vars', $translation_array );

	wp_enqueue_script( 'ta-meghna-google-map', 'http://maps.google.com/maps/api/js?sensor=false', array(), '', true );

	wp_enqueue_script( 'ta-meghna-google-map-customization', get_template_directory_uri() . '/js/google-map.js', array('jquery'), '', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ta_meghna_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Register Custom Navigation Walker.
 */
require_once get_template_directory() . '/inc/wp_bootstrap_navwalker.php';

/**
 * Custom Post Types.
 */
require_once get_template_directory() . '/inc/post-types/CPT.php';

/**
 * Portfolio Custom Post Type.
 */
require_once get_template_directory() . '/inc/post-types/register-portfolio.php';

/**
 * Comments Callback.
 */
require_once get_template_directory() . '/inc/comments-callback.php';

/**
 * Add Author Meta.
 */
require_once get_template_directory() . '/inc/author-meta.php';

/**
 * Add Custom Meta Boxes.
 */
require_once get_template_directory() . '/inc/custom-metaboxes/CMB.php';

/**
 * Add custom CSS.
 */
require_once get_template_directory() . '/inc/custom-css.php';

/**
 * Add Theme Widgets.
 */
require_once ( get_template_directory() . '/widgets/widget-post-tabs.php' );
require_once ( get_template_directory() . '/widgets/widget-mailchimp.php' );