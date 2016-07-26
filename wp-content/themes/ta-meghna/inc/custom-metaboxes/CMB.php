<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category TA Meghna
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'cmb_ta_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_ta_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cmb_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['portfolio_metabox'] = array(
		'id'         => 'portfolio_metabox',
		'title'      => __( 'Portfolio Metabox', 'ta-meghna' ),
		'pages'      => array( 'portfolio' ), // Post type
		'context'    => 'side',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __( 'Portfolio URL', 'ta-meghna' ),
				'desc' => __( 'Portfolio URL', 'ta-meghna' ),
				'id'   => $prefix . 'portfolio_url',
				'type' => 'text_url',
			),
		),
	);

	$meta_boxes['audio_metabox'] = array(
		'id'         => 'audio_metabox',
		'title'      => __( 'Audio Post Metabox', 'ta-meghna' ),
		'pages'      => array( 'post' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __( 'Embed Audio Code', 'ta-meghna' ),
				'desc' => __( 'Paste audio embed code here.', 'ta-meghna' ),
				'id'   => $prefix . 'audio_code',
				'type' => 'textarea_code',
			),
		),
	);

	$meta_boxes['video_metabox'] = array(
		'id'         => 'video_metabox',
		'title'      => __( 'Video Post Metabox', 'ta-meghna' ),
		'pages'      => array( 'post' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __( 'Embed Video Code', 'ta-meghna' ),
				'desc' => __( 'Paste video embed code here.', 'ta-meghna' ),
				'id'   => $prefix . 'video_code',
				'type' => 'textarea_code',
			),
		),
	);

	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}
