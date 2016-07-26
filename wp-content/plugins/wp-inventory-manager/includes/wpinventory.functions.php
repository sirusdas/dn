<?php

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * This file contains simple functions that wrap the WPInventory class methods for
 * easy access in template files.
 */

global $wpinventory_item;

/**
 * Includes a template part, similar to the WP get template part, but looks
 * in the correct directories for WPInventory templates
 *
 * @param string $slug
 * @param null|string $name
 *
 * @uses WPIMTemplates::get
 * @author Alpha Channel Group
 * @since 0.1
 **/
function wpinventory_get_template_part( $slug, $name = NULL, $echo = TRUE, $args = NULL ) {
	// Execute code for this part
	do_action( 'wpim_pre_get_template_part_' . $slug, $slug, $name );
	// Setup possible parts
	$templates = array( $slug . '.php' );
	if ( isset( $name ) ) {
		array_unshift( $templates, $slug . '-' . $name . '.php' );
	}

	// Allow template parts to be filtered
	$templates = apply_filters( 'wpim_get_template_part_templates', $templates, $slug, $name );

	$found = FALSE;
	// loop through templates, return first one found.
	foreach ( $templates as $template ) {
		$file = WPIMTemplate::get( $template );
		$file = apply_filters( 'wpim_get_template_part_path', $file, $template, $slug, $name );
		$file = apply_filters( 'wpim_get_template_part_path_' . $template, $file, $slug, $name );
		if ( file_exists( $file ) ) {
			$found = TRUE;
			ob_start();
			do_action( 'wpim_before_get_template_part', $template, $file, $template, $slug, $name );
			include( $file );
			do_action( 'wpim_after_get_template_part', $template, $file, $slug, $name );
			$html = ob_get_clean();
			echo apply_filters( 'wpim_get_template_part_content', $html, $template, $file, $slug, $name );
			break;
		}
	}

	if ( ! $found ) {
		echo '<!-- Could not find template ' . $slug . ' ' . $name . '-->';
	}
	do_action( 'wpim_post_get_template_part_' . $slug, $slug, $name );
}

global $WPIMLoop;
/**
 * Utility function to get the $WPIM variable, and set it if it's not yet set
 */
function wpinventory_get_wpim() {
	global $WPIMLoop;
	if ( ! $WPIMLoop ) {
		$WPIMLoop = new WPIMLoop();
	}

	return $WPIMLoop;
}

function wpinventory_set_loop( $loop ) {
	global $WPIMLoop;
	$WPIMLoop = $loop;
}

/**
 * Similar to WP query_posts, loads the inventory items
 *
 * @param array $args
 */
function wpinventory_get_items( $args = NULL ) {
	$WPIMLoop = wpinventory_get_wpim();
	$WPIMLoop->load_items( $args );
}

/**
 * Similar to WP have_posts, checks to see if there are any items loaded
 */
function wpinventory_have_items() {
	$WPIMLoop = wpinventory_get_wpim();

	return $WPIMLoop->have_items();
}

/**
 * Similiar to WP the_post, prepares the item for access
 */
function wpinventory_the_item() {
	global $wpinventory_item;
	$WPIMLoop = wpinventory_get_wpim();
	$WPIMLoop->the_item();
}

function wpinventory_is_single() {
	$WPIMLoop = wpinventory_get_wpim();

	return $WPIMLoop->is_single();
}

function wpinventory_get_the_label( $field ) {
	$labels = WPIMLabel::getInstance();
	$label  = $labels->get_label( $field );

	if ( $label ) {
		return $label;
	} else {
		return $field;
	}
}

function wpinventory_the_label( $field ) {
	echo wpinventory_get_the_label( $field );
}

function wpinventory_get_all_labels() {
	$labels = WPIMLAbel::getInstance();

	return $labels->get_all();
}

/**
 * To be utilized similar to WP the_content, the_title, etc - however, there's enough fields
 * that we want to not be tied down to individual functions.  Further, if the user passes in
 * a custom field label, we still want to be able to get it.
 *
 * @param string $field
 */
function wpinventory_get_field( $field ) {
	$context = wpinventory_is_single() ? 'detail' : 'listing';
	$size    = wpinventory_get_config( 'display_' . $context . '_image_size' );
	if ( $field == 'inventory_image' ) {
		return wpinventory_image_tags( wpinventory_get_the_featured_image( $size ) );
	}
	if ( $field == 'inventory_images' ) {
		$images = wpinventory_get_the_images( $size );
		$imgs   = '';
		foreach ( (array) $images AS $image ) {
			$imgs .= wpinventory_get_image_tags( $image );
		}

		return $imgs;
	}

	if ( $field == 'inventory_media' ) {
		$medias = wpinventory_get_the_media();
		$media  = '';
		foreach ( (array) $medias AS $m ) {
			$media .= wpinventory_get_media_tags( $m );
		}

		return $media;
	}

	if ( $field == 'category_id' ) {
		$field = 'inventory_category';
	}

	$WPIMLoop = wpinventory_get_wpim();
	$value    = $WPIMLoop->get_field( $field );

	if ( in_array( $field, array( 'description', 'inventory_description' ) ) ) {
		return apply_filters( 'the_content', $value );
	}

	return $value;
}

function wpinventory_the_field( $field ) {
	echo wpinventory_get_field( $field );
}

function wpinventory_get_the_ID() {
	return wpinventory_get_field( "inventory_id" );
}

function wpinventory_the_ID() {
	echo wpinventory_get_the_ID();
}


function wpinventory_get_the_name() {
	return wpinventory_get_field( "inventory_name" );
}

function wpinventory_the_name() {
	echo wpinventory_get_the_name();
}

function wpinventory_get_the_number() {
	return wpinventory_get_field( "inventory_number" );
}

function wpinventory_the_number() {
	echo wpinventory_get_the_number();
}


function wpinventory_get_the_description() {
	return wpinventory_get_field( "inventory_description" );
}

function wpinventory_the_description() {
	echo wpinventory_get_the_description();
}


function wpinventory_get_the_size() {
	return wpinventory_get_field( "inventory_size" );
}

function wpinventory_the_size() {
	echo wpinventory_get_the_size();
}


function wpinventory_get_the_manufacturer() {
	return wpinventory_get_field( "inventory_manufacturer" );
}

function wpinventory_the_manufacturer() {
	echo wpinventory_get_the_manufacturer();
}


function wpinventory_get_the_make() {
	return wpinventory_get_field( "inventory_make" );
}

function wpinventory_the_make() {
	echo wpinventory_get_the_make();
}


function wpinventory_get_the_model() {
	return wpinventory_get_field( "inventory_model" );
}

function wpinventory_the_model() {
	echo wpinventory_get_the_model();
}


function wpinventory_get_the_year() {
	return wpinventory_get_field( "inventory_year" );
}

function wpinventory_the_year() {
	echo wpinventory_get_the_year();
}


function wpinventory_get_the_serial() {
	return wpinventory_get_field( "inventory_serial" );
}

function wpinventory_the_serial() {
	echo wpinventory_get_the_serial();
}


function wpinventory_get_the_fob() {
	return wpinventory_get_field( "inventory_fob" );
}

function wpinventory_the_fob() {
	echo wpinventory_get_the_fob();
}


function wpinventory_get_the_quantity() {
	return wpinventory_get_field( "inventory_quantity" );
}

function wpinventory_the_quantity() {
	echo wpinventory_get_the_quantity();
}


function wpinventory_get_the_reserved() {
	return wpinventory_get_field( "inventory_quantity_reserved" );
}

function wpinventory_the_reserved() {
	echo wpinventory_get_the_reserved();
}


function wpinventory_get_the_price() {
	$WPIMLoop = wpinventory_get_wpim();
	$price    = wpinventory_get_field( "inventory_price" );

	return $WPIMLoop->format_currency( $price );
}

function wpinventory_the_price() {
	echo wpinventory_get_the_price();
}


function wpinventory_get_the_status() {
	return wpinventory_get_field( "inventory_status" );
}

// TODO: Get the REAL status
function wpinventory_the_status() {
	echo wpinventory_get_the_status();
}

function wpinventory_get_the_category() {
	return wpinventory_get_field( 'inventory_category' );
}

function wpinventory_the_category() {
	echo wpinventory_get_the_category();
}

function wpinventory_get_the_category_ID() {
	return wpinventory_get_field( 'category_id' );
}

function wpinventory_the_category_ID() {
	echo wpinventory_get_the_category_ID();
}


function wpinventory_get_the_date() {
	$WPIMLoop = wpinventory_get_wpim();
	$date     = wpinventory_get_field( "inventory_date_added" );

	return $WPIMLoop->format_date( $date );
}

function wpinventory_the_date() {
	echo wpinventory_get_the_date();
}

function wpinventory_get_config( $key, $default = NULL ) {
	$config = WPIMConfig::getInstance();

	return $config->get( $key, $default );
}

function wpinventory_get_display_settings( $type = 'listing' ) {
	$display = wpinventory_get_config( 'display_' . $type );

	$display = array_filter( explode( ',', $display ) );

	return apply_filters("wpim_display_{$type}_settings", $display);
}

function wpinventory_get_the_date_updated() {
	$WPIMLoop = wpinventory_get_wpim();
	$date     = wpinventory_get_field( "inventory_date_updated" );

	return $WPIMLoop->format_date( $date );
}

function wpinventory_the_date_updated() {
	echo wpinventory_get_the_date_updated();
}

function wpinventory_get_permalink() {
	$WPIMLoop = wpinventory_get_wpim();

	return $WPIMLoop->get_permalink();
}

function wpinventory_the_permalink() {
	echo wpinventory_get_permalink();
}

function wpinventory_get_backlink( $anchor = 'Back' ) {
	global $post;
	$args    = WPIMCore::get_page_state();
	$post_id = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : $post->ID;
	unset( $args['post_id'] );
	$url  = http_build_query( $args );
	$back = get_permalink( $post_id );
	if ( $url ) {
		$back .= ( stripos( $back, '?' ) !== FALSE ) ? '&' : '?';
	}
	$back .= $url;
	$back = '<a href="' . $back . '" class="wpinventory_back">' . $anchor . '</a>';

	return $back;
}

function wpinventory_backlink() {
	echo wpinventory_get_backlink();
}

function wpinventory_get_category_name() {
	$WPIMLoop = wpinventory_get_wpim();
	$category = $WPIMLoop->get_category();

	return ( $category && ! empty( $category->category_name ) ) ? $category->category_name : '';
}

/**
 * Retreive the current item's image sources
 *
 * @param string $size - thumbnail | medium | large | full
 * @param integer $limit - use 0 for no limit
 */
function wpinventory_get_the_images( $size = "thumbnail", $limit = 0 ) {
	$WPIMLoop = wpinventory_get_wpim();

	$images = $WPIMLoop->get_images( $size, $limit );

	if ( ! $images) {
		$placeholder = wpinventory_get_placeholder_image($size);
		if ( $placeholder ) {
			$images = (array)$placeholder;
		}
	}

	return $images;
}

/**
 * Retreive and echo the current item's image in full image tag
 *
 * @param string $size - thumbnail | medium | large | full
 * @param integer $limit - use 0 for no limit
 */
function wpinventory_the_images( $size = "thumbnail", $limit = 0 ) {
	$images = wpinventory_get_the_images( $size, $limit );
	foreach ( (array) $images AS $image ) {
		echo wpinventory_get_image_tags( $image );
	}
}

function wpinventory_get_the_featured_image( $size = 'thumbnail' ) {
	$images = wpinventory_get_the_images( $size, 1 );

	if (is_array($images) && ! empty( $images[0] ) ) {
		return $images[0];
	}

	// Should never get here.  wpinventory_get_the_images should get / return placeholder if appropriate
	$placeholder = wpinventory_get_placeholder_image($size);

	return $placeholder;
}

function wpinventory_the_featured_image( $size = 'thumbnail' ) {
	$image = wpinventory_get_the_featured_image( $size );

	wpinventory_image_tags( $image );
}

/**
 * Loads the placeholder image set in configuration.
 * @param string $size thumbnail|medium|large|full|all (returns an object)
 *
 * @since version 1.1.1
 *
 * @return string
 */
function wpinventory_get_placeholder_image($size = 'thumbnail') {
	$placeholder_image = wpinventory_get_config( 'placeholder_image');
	if ($placeholder_image) {
		$placeholder_image = (array)json_decode($placeholder_image);
		if ($size == 'all') {
			return (object)$placeholder_image;
		}

		if (isset($placeholder_image[$size])) {
			return $placeholder_image[$size];
		}

		if (is_array($placeholder_image)) {
			return array_pop($placeholder_image);
		}

		if (is_string($placeholder_image)) {
			return $placeholder_image;
		}
	}

	return '';
}

function wpinventory_get_image_tags( $image ) {
	if ( ! $image ) {
		return;
	}

	return '<p class="image"><img title="' . wpinventory_get_the_name() . '" alt="' . wpinventory_get_the_name() . '" src="' . $image . '"></p>';
}

function wpinventory_image_tags( $image ) {
	echo wpinventory_get_image_tags( $image );
}

/**
 * Retrieve the current item's media sources
 *
 * @param integer $limit - use 0 for no limit
 */
function wpinventory_get_the_media( $limit = 0 ) {
	$WPIMLoop = wpinventory_get_wpim();

	return $WPIMLoop->get_media( $limit );
}

function wpinventory_the_media ( $limit = 0, $new_window = TRUE ) {
	$media = wpinventory_get_the_media ( $limit );
	if ($media) {
		foreach($media AS $item) {
			wpinventory_media_tags( $item, $new_window );
		}
	}
}

function wpinventory_get_media_tags( $media, $new_window = TRUE ) {
	if ( ! $media || empty( $media->media ) ) {
		return;
	}

	$title = ( $media->media_title ) ? $media->media_title : $media->media;

	$parts = pathinfo( $media->media );

	$class = '';
	if ( ! empty( $parts['extension'] ) ) {
		$class .= ' media-' . $parts['extension'];
	}

	if ( $new_window ) {
		$new_window = ' target="_blank"';
	}

	return '<p class="media' . $class . '"><a title="' . $title . '" href="' . $media->media . '"' . $new_window . '>' . $title . '</a></p>';
}

function wpinventory_media_tags( $media, $new_window = TRUE ) {
	echo wpinventory_get_media_tags( $media, $new_window );
}

/**
 * Returns the configuration defined for the reserve form labels / display
 *
 * Display variables accept:
 * FALSE or 0    => Do not display (not required)
 * TRUE  or 1    => Display (not required)
 * 2             => Display (and required)
 *
 * @return mixed|void
 */
function wpinventory_get_reserve_config($args = array()) {
	$WPIMLoop = wpinventory_get_wpim();

	$default = array(
		'form_title'       => WPIMCore::__( 'Reserve This Item' ),
		'display_name'     => (int) wpinventory_get_config( 'reserve_require_name' ),
		'name_label'       => WPIMCore::__( 'Name' ),
		'name'             => ( isset( $_POST['wpinventory_reserve_name'] ) ) ? $_POST['wpinventory_reserve_name'] : '',
		'display_address'  => (int) wpinventory_get_config( 'reserve_require_address' ),
		'address_label'    => WPIMCore::__( 'Address' ),
		'address'          => ( isset( $_POST['wpinventory_reserve_address'] ) ) ? $_POST['wpinventory_reserve_address'] : '',
		'display_city'     => (int) wpinventory_get_config( 'reserve_require_city' ),
		'city_label'       => WPIMCore::__( 'City' ),
		'city'             => ( isset( $_POST['wpinventory_reserve_city'] ) ) ? $_POST['wpinventory_reserve_city'] : '',
		'display_state'    => (int) wpinventory_get_config( 'reserve_require_state' ),
		'state_label'      => WPIMCore::__( 'State' ),
		'state'            => ( isset( $_POST['wpinventory_reserve_state'] ) ) ? $_POST['wpinventory_reserve_state'] : '',
		'display_zip'      => (int) wpinventory_get_config( 'reserve_require_zip' ),
		'zip_label'        => WPIMCore::__( 'Postal Code' ),
		'zip'              => ( isset( $_POST['wpinventory_reserve_zip'] ) ) ? $_POST['wpinventory_reserve_zip'] : '',
		'display_phone'    => (int) wpinventory_get_config( 'reserve_require_phone' ),
		'phone_label'      => WPIMCore::__( 'Phone' ),
		'phone'            => ( isset( $_POST['wpinventory_reserve_phone'] ) ) ? $_POST['wpinventory_reserve_phone'] : '',
		'display_email'    => (int) wpinventory_get_config( 'reserve_require_email' ),
		'email_label'      => WPIMCore::__( 'Email' ),
		'email'            => ( isset( $_POST['wpinventory_reserve_email'] ) ) ? $_POST['wpinventory_reserve_email'] : '',
		'display_quantity' => ( (int) wpinventory_get_config( 'reserve_quantity' ) ) ? 2 : FALSE,
		'quantity_label'   => WPIMCore::__( 'Quantity to Reserve' ),
		'quantity'         => ( isset( $_POST['wpinventory_reserve_quantity'] ) ) ? $_POST['wpinventory_reserve_quantity'] : '',
		'display_message'  => (int) wpinventory_get_config( 'reserve_require_message' ),
		'message_label'    => WPIMCore::__( 'Message' ),
		'message'          => ( isset( $_POST['wpinventory_reserve_message'] ) ) ? $_POST['wpinventory_reserve_message'] : '',
		'submit_label'     => 'Reserve',
		'inventory_id'     => $WPIMLoop->single_id()
	);

	$args = wp_parse_args( $args, $default );
	return apply_filters('wpim_reserve_config', $args);
}


function wpinventory_reserve_form( $args = NULL ) {
	if ( ! (int) wpinventory_get_config( 'reserve_allow' ) ) {
		return '<!-- Reserve form disabled in admin dashboard -->';
	}

	$args = wpinventory_get_reserve_config($args);

	$error   = '';
	$message = '';
	$display = TRUE;

	if ( isset( $_POST['wpinventory_reserve_submit'] ) ) {
		$data = array();
		foreach ( $args AS $field => $required ) {
			if ( stripos( $field, 'display_' ) === 0 ) {
				$field = str_replace( 'display_', '', $field );
				if ( $field ) {
					$data[ $field ] = array(
						'value' => WPIMCore::request( 'wpinventory_reserve_' . $field ),
						'label' => $args[ $field . '_label' ]
					);
					if ( stripos( $field, 'quantity' ) !== FALSE ) {
						$data[ $field ]['value'] = (int) $data[ $field ]['value'];
						if ( $data[ $field ]['value'] < 0 ) {
							$data[ $field ]['value'] = 0;
						}
					}
					if ( ! trim( $data[ $field ]['value'] ) && $required === 2 ) {
						$error .= $args[ $field . '_label' ] . ' ' . WPIMCore::__( 'is required.' ) . '<br />';
					}
				}
			}
		}

		if ( ! $error && (int) wpinventory_get_config( 'reserve_decrement' ) ) {
			$wpim_item = new WPIMItem();
			$item      = $wpim_item->get( $args['inventory_id'] );

			if ( $item ) {
				$on_hand = $item->inventory_quantity;
				if ( $data['quantity']['value'] > $on_hand ) {
					$error = WPIMCore::__( 'There are not enough of this item to reserve' ) . ' ' . $data['quantity']['value'] . '<br>';
				}
			}
		}

		if ( ! $error ) {
			$data['inventory_id'] = $args['inventory_id'];
			$success              = wpinventory_process_reserve( $data );
			if ( $success === TRUE ) {
				$display = FALSE;
				$message = WPIMCore::__( 'Thank you.  Your reservation has been submitted.' );
			} else {
				$error = $success;
			}
		}
	}

	$args['error'] = $error;

	if ( $display ) {
		return wpinventory_get_template_part( 'reserve-form', '', FALSE, $args );
	} elseif ( $message ) {
		return '<a id="wpim_reserve" name="wpim_reserve"></a><div class="wpinventory_message">' . $message . '</div>';
	}
}

function wpinventory_reserve_add_field($args, $field, $display, $label, $insert_before = '') {
	$new_array = array();
	$inserted = FALSE;
	if (is_string($display)) {
		// 0 / FALSE wouldn't make sense if we're adding, so assume either required / optional
		$display = (stripos($display, 'req') !== FALSE) ? 2 : 1;
	}

	if ($insert_before && stripos($insert_before, 'display') === FALSE) {
		$insert_before = 'display_' . $insert_before;
	}

	$new_args = array(
		'display_' . $field => $display,
		$field . '_label'    => $label,
		$field              => (isset( $_POST['wpinventory_reserve_' . $field] ) ) ? $_POST['wpinventory_reserve_' . $field] : ''
	);

	foreach ( $args as $key => $value ) {
		if ( $key === $insert_before  ) {

			foreach($new_args AS $insert_key => $insert_value) {
				$inserted                 = TRUE;
				$new_array[ $insert_key ] = $insert_value;
			}
		}

		$new_array[ $key ] = $value;

	}

	// Append if wasn't found / inserted
	if ( ! $inserted ) {
		foreach($new_args AS $insert_key => $insert_value) {
			$new_array[ $insert_key ] = $insert_value;
		}
	}

	return $new_array;
}

function wpinventory_process_reserve( $data ) {
	$to_email = wpinventory_get_config( 'reserve_email' );
	if ( ! $to_email ) {
		$to_email = get_option( 'admin_email' );
	}

	$subject = WPIMCore::__( 'An item has been reserved from' ) . ' ' . get_bloginfo( 'site_name' );
	$message = '';

	$fields = array(
		'inventory_number',
		'inventory_serial',
		'inventory_name',
	);

	$fields = apply_filters( 'wpim_reserve_item_fields', $fields );

	$item_title = WPIMCore::__( 'Item Details' );
	$item_title = apply_filters( 'wpim_reserve_title_item_details', $item_title );

	$message .= PHP_EOL . $item_title;

	$inventory_display = wpinventory_get_display_settings('detail');

	if ( ! empty( $data['inventory_id'] ) ) {
		$loop = new WPIMLoop( array( 'inventory_id' => $data['inventory_id'] ) );
		while ( $loop->have_items() ) {
			$loop->the_item();
			foreach ( $inventory_display AS $field ) {
				$message .= PHP_EOL . $loop->get_label( $field ) . ': ' . $loop->get_field( $field );
			}
		}
	}

	$reservation_title = WPIMCore::__( 'Reservation Details' );
	$reservation_title = apply_filters( 'wpim_reserve_title_reservation_details', $reservation_title );

	$message .= PHP_EOL . PHP_EOL . $reservation_title;

	$exclude = array( 'inventory_id' );

	$exclude = apply_filters( 'wpim_reserve_exclude_form_fields', $exclude );

	$args = wpinventory_get_reserve_config();

	foreach ( $data AS $field => $d ) {
		if ( ! in_array( $field, $exclude ) && $args['display_' . $field] ) {
			$message .= PHP_EOL . $d['label'] . ': ' . $d['value'];
		}
	}

	$subject = apply_filters( 'wpim_reserve_email_subject', $subject );
	$message = apply_filters( 'wpim_reserve_email_message', $message );

	$status = FALSE;
	$test_mode = FALSE;

	if ($test_mode) {
		echo '<br>== E-Mail output (in test mode) ==<br>';
		echo '<pre>';
		echo 'To: ' . $to_email . PHP_EOL;
		echo 'Subject: ' . $subject . PHP_EOL;
		echo 'Message:' . PHP_EOL;
		echo $message;
		echo '</pre>';
	}

	$success = wp_mail( $to_email, $subject, $message );
	if ( ! $success ) {
		return WPIMCore::__( 'There was an issue sending your e-mail.  Please try again later.' );
	} else {
		if ( wpinventory_get_config( 'reserve_decrement' ) ) {
			$wpim_item = new WPIMItem();
			$wpim_item->save_reserve( $data['inventory_id'], $data['quantity']['value'] );

			do_action( 'wpim_reserve_sent', $data['inventory_id'], $data, $subject, $message );
			$status = TRUE;
		}
	}

	$send_confirmation = wpinventory_get_config('reserve_confirmation');

	if ($send_confirmation) {
		// Grab e-mail from the form
		$confirm_email =  $data['email']['value'];

		// If the user is logged in, use that e-mail
		if (is_user_logged_in()) {
			$current_user = wp_get_current_user();
			$confirm_email = $current_user->user_email;
		}

		$subject = apply_filters( 'wpim_reserve_confirmation_email_subject', $subject );
		$message = apply_filters( 'wpim_reserve_confirmation_email_message', $message );

		if ($test_mode) {
			echo '<br>== E-Mail Confirmation output (in test mode) ==<br>';
			echo '<pre>';
			echo 'To: ' . $confirm_email . PHP_EOL;
			echo 'Subject: ' . $subject . PHP_EOL;
			echo 'Message:' . PHP_EOL;
			echo $message;
			echo '</pre>';
		}

		$success = wp_mail( $confirm_email, $subject, $message);
		if ( ! $success) {
			return WPIMCore::__('There was an issue sending the confirmation e-mail.  Please try again later.');
		} else {
			do_action('wpim_reserve_confirmation_sent', $data['inventory_id'], $data, $subject, $message);
			$status = TRUE;
		}
	}

	return $status;
}

function wpinventory_filter_form_admin( $args = NULL ) {
	$args['caller'] = '_admin';

	return wpinventory_filter_form( $args );
}

function wpinventory_filter_form_reports_admin( $args = NULL ) {
	$args['caller'] = '_admin';

	return wpinventory_filter_form_reports( $args );
}

function wpinventory_get_filter_criteria( $args = array() ) {
	$WPIMLoop = wpinventory_get_wpim();

	$query_args = $WPIMLoop->get_query_args();

	if ( ! empty( $args ) && is_string( $args ) && stripos( $args, "&" ) != FALSE ) {
		$args = explode( '&', $args );
	}

	// Override.  If the shortcode contains a category id, do not show
	if ( ! empty( $query_args['category_id'] ) && ! WPIMCore::request( 'inventory_category_id' ) ) {
		$args['categories'] = FALSE;
	}

	$default = array(
		"search"       => TRUE,
		"sort"         => TRUE,
		"sort_label"   => $WPIMLoop->__( "Sort By" ),
		"categories"   => TRUE,
		"button"       => $WPIMLoop->__( "Search" ),
		"search_label" => $WPIMLoop->__( "Search For" ),
		"caller"       => ""
	);

	$args = wp_parse_args( $args, $default );

	if ( empty( $query_args['sort_by'] ) ) {
		$query_args['sort_by'] = 'inventory_name';
	}

	$args['inventory_search']      = $WPIMLoop->request( "inventory_search" );
        $args['inventory_search1']      = $WPIMLoop->request( "inventory_search1" );
        $args['inventory_search_from'] = $WPIMLoop->request( "search_from_date" );
	$args['inventory_sort_by']     = $WPIMLoop->request( "inventory_sort_by", $query_args['order'] );
	$args['inventory_category_id'] = $WPIMLoop->request( "inventory_category_id", $query_args['category_id'] );

	return $args;
}

function wpinventory_get_filter_reports_criteria( $args = array() ) {
	$WPIMLoop = wpinventory_get_wpim();

	$query_args = $WPIMLoop->get_query_args();

	if ( ! empty( $args ) && is_string( $args ) && stripos( $args, "&" ) != FALSE ) {
		$args = explode( '&', $args );
	}

	// Override.  If the shortcode contains a category id, do not show
	if ( ! empty( $query_args['category_id'] ) && ! WPIMCore::request( 'inventory_category_id' ) ) {
		$args['categories'] = FALSE;
	}

	$default = array(
		"search"       => TRUE,
		"sort"         => FALSE,
		"sort_label"   => $WPIMLoop->__( "Sort By" ),
		"categories"   => TRUE,
		"button"       => $WPIMLoop->__( "Search" ),
		"search_label" => $WPIMLoop->__( "Search For" ),
		"caller"       => ""
	);

	$args = wp_parse_args( $args, $default );

	if ( empty( $query_args['sort_by'] ) ) {
		$query_args['sort_by'] = 'inventory_name';
	}

        $args['inventory_search1']      = $WPIMLoop->request( "inventory_search1" );
        $args['inventory_search2'] =       $WPIMLoop->request( "inventory_search2" );

	return $args;
}

/**
 * Render the filter form at the top.
 *
 * @param mixed $args - array / url of parameters
 *                        boolean search - true (default) | false - show search input
 *                        boolean sort - true (default) | false - show sort drop-down
 *                        boolean categories - true (default) | false - show categories dropdown
 */
function wpinventory_filter_form( $args = NULL ) {

	global $post;
	$WPIMLoop = wpinventory_get_wpim();

	$args = wpinventory_get_filter_criteria();
	extract($args);

	$form = '';

	if ( $search ) {
		$form .= '<span class="search">' . PHP_EOL;
		$form .= ( $search_label ) ? '<label for="inventory_search">' . $search_label . '</label>' : '';
		$form .= '<input type="text" name="inventory_search" value="' . $inventory_search . '" />';
		$form .= '</span>' . PHP_EOL;
	}

	if ( $sort ) {

		$fields = $WPIMLoop->get_labels();
		$fields = apply_filters( 'wpim_filter_sort_by_options' . $caller, $fields );

		$form .= '<span class="sort">';
		$form .= ( $sort_label ) ? '<label for="inventory_sort">' . $sort_label . '</label>' : '';
		$form .= '<select name="inventory_sort_by">' . PHP_EOL;
		$form .= ( ! $sort_label ) ? '<option value="">' . $WPIMLoop->__( 'Sort By...' ) . '</option>' . PHP_EOL : '';

		foreach ( $fields AS $field => $label ) {
			if ( $label['is_used'] ) {
				$form .= '<option value="' . $field . '"';
				$form .= ( $field == $inventory_sort_by ) ? ' selected' : '';
				$form .= '>' . $label['label'] . '</option>' . PHP_EOL;
			}
		}

		$form .= '</select></span>' . PHP_EOL;
	}

	if ( $categories ) {
		$categories = wpinventory_get_categories();
		$categories = apply_filters('wpim_filter_categories_options', $categories);
		$form .= '<span class="categories"><select name="inventory_category_id">' . PHP_EOL;
		$form .= '<option value="">' . sprintf($WPIMLoop->__( 'Choose %s...' ), wpinventory_get_the_label('category_id')) . '</option>' . PHP_EOL;

		foreach ( $categories AS $category ) {
			$form .= '<option value="' . $category->category_id . '"';
			$form .= ( $category->category_id == $inventory_category_id ) ? ' selected' : '';
			$form .= '>' . $category->category_name . '</option>' . PHP_EOL;
		}

		$form .= '</select></span>' . PHP_EOL;
	}

	$url = ( empty( $post ) ) ? 'admin.php?page=' . $_GET['page'] : get_permalink( $post->ID );

	if ( $form ) {
		$form .= '<input type="submit" name="inventory_filter" value="' . $WPIMLoop->__( 'Go' ) . '" />' . PHP_EOL;
		$form = '<form class="wpinventory_filter" name="wpinventory_filter" method="post" id="inventory_search" action="' . $url . '#inventory_filter">' . PHP_EOL . $form . '</form>' . PHP_EOL;
	}

	return $form;
}

function wpinventory_filter_form_reports( $args = NULL ) {

	global $post;
	$WPIMLoop = wpinventory_get_wpim();

	$args = wpinventory_get_filter_reports_criteria();
	extract($args);

	$form = '';

	if ( $search ) {
        	$form .= '<span class="search">' . PHP_EOL;
		$form .= '<label for="inventory_search"> From Date: </label>';
		$form .= '<input type="text" class="MyDate" name="inventory_search2" value="' . $inventory_search2 . '" />';
                $form .= '</span>' . PHP_EOL;
                
                $form .= '<span class="search">' . PHP_EOL;
		$form .= '<label for="inventory_search"> To Date: </label>';                
		$form .= '<input type="text" class="MyDate" name="inventory_search1" value="' . $inventory_search1 . '" />';                
		$form.= '</span>' . PHP_EOL;  
	}

	$url = ( empty( $post ) ) ? 'admin.php?page=' . $_GET['page'] : get_permalink( $post->ID );

	if ( $form ) {
		$form .= '<input type="submit" name="inventory_filter" value="' . $WPIMLoop->__( 'Go' ) . '" />' . PHP_EOL;
		$form = '<form class="wpinventory_filter" name="wpinventory_filter" method="post" id="inventory_search" action="' . $url . '#inventory_filter">' . PHP_EOL . $form . '</form>' . PHP_EOL;
	}

	return $form;
}

function wpinventory_get_categories( $args = NULL ) {
	$category = new WPIMCategory();

	return $category->get_all( $args );
}

function wpinventory_pagination( $url = NULL, $pages = NULL ) {

	$showing    = '';
	$pagination = '';

	$WPIMLoop = wpinventory_get_wpim();

	if ( ! $pages ) {
		$pages = $WPIMLoop->get_pages();
	}

	extract( $pages );

	$showing = $WPIMLoop->__( 'Showing [start] - [end] of [count] items' );

	$start = ( $page * $page_size ) + 1;
	$end   = $start + $page_size - 1;
	if ( $end > $item_count ) {
		$end = $item_count;
	}

	$showing = str_replace( '[start]', $start, $showing );
	$showing = str_replace( '[end]', $end, $showing );
	$showing = str_replace( '[count]', $item_count, $showing );

	$showing = '<span class="wpinventory_showing">' . $showing . '</span>';

	if ( ! $url ) {
		global $post;
		$url = get_permalink( $post->ID );
	}

	if ( $page > 0 ) {
		if ( $page > 1 ) {
			$pagination .= '<a href="' . $WPIMLoop->get_pagination_permalink( $url, 0 ) . '" class="page page_first">' . $WPIMLoop->__( '&lt;&lt;' ) . '</a>';
		}
		$pagination .= '<a href="' . $WPIMLoop->get_pagination_permalink( $url, ( $page - 1 ) ) . '" class="page page_prev">' . $WPIMLoop->__( '&lt;' ) . '</a>';
	}
	$paginate = max( $page - 7, 0 );
	if ( $paginate > $pages - 14 && $pages > 14 ) {
		$paginate = $pages - 14;
	}
	if ( $paginate > 0 ) {
		$pagination .= '<span class="ellipses">...</span>';
	}
	$pcount = 0;
	while ( ( $paginate < $pages ) && $pcount < 14 ) {
		$pcount ++;
		$class = ( $paginate == $page ) ? ' page_current' : '';
		$pagination .= '<a href="' . $WPIMLoop->get_pagination_permalink( $url, $paginate ) . '" class="page page_' . $paginate . $class . '">' . ( ++ $paginate ) . '</a>';
	}

	if ( $paginate < ( $item_count / $page_size ) ) {
		$pagination .= '<span class="ellipses">...</span>';
	}

	if ( ( $page + 1 ) < $pages ) {
		$pagination .= '<a href="' . $WPIMLoop->get_pagination_permalink( $url, ( $page + 1 ) ) . '" class="page page_next">' . $WPIMLoop->__( '&gt;' ) . '</a>';
		if ( ( $page + 2 ) < $pages ) {
			$pagination .= '<a href="' . $WPIMLoop->get_pagination_permalink( $url, ( $pages - 1 ) ) . '" class="page page_last">' . $WPIMLoop->__( '&gt;&gt;' ) . '</a>';
		}
	}

	return '<div class="wpinventory_pagination">' . $showing . $pagination . '</div>';

}

// TODO: How to make this respond to different loops?
// TODO: Query args is available - no need to pass!
function wpinventory_get_pages() {
	$WPIMLoop = wpinventory_get_wpim();

	return $WPIMLoop->get_pages();
}

function wpinventory_class() {
	$WPIMLoop = wpinventory_get_wpim();
	$class    = 'wpinventory_item';
	$class .= ' wpinventory_item' . $WPIMLoop->get_even_or_odd();
	$class .= ' wpinventoryitem-' . wpinventory_get_the_ID();
	$class .= ' wpinventoryitem-category-' . wpinventory_get_the_category_ID();
	echo $class;
}

function wpinventory_label_class( $label ) {
	$class = 'wpinventory_label';
	$class .= ' wpinventory_title ';
	$class .= preg_replace( "/\W|_/", "_", $label );
	echo $class;
}

/**
 * Available filters:
 *
 * wpim_filter_sort_by_options        - the list of sort by fields that goes into the sort by dropdown
 * wpim_filter_sort_by_options_admin - same as above, but for admin page
 * wpim_filter_categories_options    - the list of categories that goes into the categories dropdown
 */