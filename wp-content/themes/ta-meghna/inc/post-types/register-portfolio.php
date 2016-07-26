<?php
/**
 * WP Custom Post Type Class
 *
 * @package TA Meghna
 */

$portfolio = new CPT(
	array(
		'post_type_name' => 'portfolio',
		'singular'       => __( 'Portfolio', 'ta-meghna' ),
		'plural'         => __( 'Portfolios', 'ta-meghna' ),
		'slug'           => 'portfolio'
	),

	array(
		'supports'  => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments' ),
		'menu_icon' => 'dashicons-portfolio'
	)
);

$portfolio -> register_taxonomy(
	array(
		'taxonomy_name' => 'portfolio_tags',
		'singular'      => __( 'Portfolio Tag', 'ta-meghna' ),
		'plural'        => __( 'Portfolio Tags', 'ta-meghna' ),
		'slug'          => 'portfolio-tag'
	)
);