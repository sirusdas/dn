<?php

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * This file contains simple functions that wrap the NETRAstock class methods for
 * easy access in template files.
 */

global $netrastock_item;

/**
 * Includes a template part, similar to the WP get template part, but looks
 * in the correct directories for NETRAstock templates
 *
 * @param string $slug
 * @param null|string $name
 *
 * @uses NSMTemplates::get
 * @author Alpha Channel Group
 * @since 0.1
 **/
function netrastock_get_template_part( $slug, $name = NULL, $echo = TRUE, $args = NULL ) {
	// Execute code for this part
	do_action( 'NSM_pre_get_template_part_' . $slug, $slug, $name );
	// Setup possible parts
	$templates = array( $slug . '.php' );
	if ( isset( $name ) ) {
		array_unshift( $templates, $slug . '-' . $name . '.php' );
	}

	// Allow template parts to be filtered
	$templates = apply_filters( 'NSM_get_template_part_templates', $templates, $slug, $name );

	$found = FALSE;
	// loop through templates, return first one found.
	foreach ( $templates as $template ) {
		$file = NSMTemplate::get( $template );
		$file = apply_filters( 'NSM_get_template_part_path', $file, $template, $slug, $name );
		$file = apply_filters( 'NSM_get_template_part_path_' . $template, $file, $slug, $name );
		if ( file_exists( $file ) ) {
			$found = TRUE;
			ob_start();
			do_action( 'NSM_before_get_template_part', $template, $file, $template, $slug, $name );
			include( $file );
			do_action( 'NSM_after_get_template_part', $template, $file, $slug, $name );
			$html = ob_get_clean();
			echo apply_filters( 'NSM_get_template_part_content', $html, $template, $file, $slug, $name );
			break;
		}
	}

	if ( ! $found ) {
		echo '<!-- Could not find template ' . $slug . ' ' . $name . '-->';
	}
	do_action( 'NSM_post_get_template_part_' . $slug, $slug, $name );
}

global $NSMLoop;
/**
 * Utility function to get the $NSM variable, and set it if it's not yet set
 */
function netrastock_get_NSM() {
	global $NSMLoop;
	if ( ! $NSMLoop ) {
		$NSMLoop = new NSMLoop();
	}

	return $NSMLoop;
}

function netrastock_set_loop( $loop ) {
	global $NSMLoop;
	$NSMLoop = $loop;
}

/**
 * Similar to WP query_posts, loads the inventory items
 *
 * @param array $args
 */
function netrastock_get_items( $args = NULL ) {
	$NSMLoop = netrastock_get_NSM();
	$NSMLoop->load_items( $args );
}

/**
 * Similar to WP have_posts, checks to see if there are any items loaded
 */
function netrastock_have_items() {
	$NSMLoop = netrastock_get_NSM();

	return $NSMLoop->have_items();
}

/**
 * Similiar to WP the_post, prepares the item for access
 */
function netrastock_the_item() {
	global $netrastock_item;
	$NSMLoop = netrastock_get_NSM();
	$NSMLoop->the_item();
}

function netrastock_is_single() {
	$NSMLoop = netrastock_get_NSM();

	return $NSMLoop->is_single();
}

function netrastock_get_the_label( $field ) {
	$labels = NSMLabel::getInstance();
	$label  = $labels->get_label( $field );

	if ( $label ) {
		return $label;
	} else {
		return $field;
	}
}

function netrastock_the_label( $field ) {
	echo netrastock_get_the_label( $field );
}

function netrastock_get_all_labels() {
	$labels = NSMLAbel::getInstance();

	return $labels->get_all();
}

/**
 * To be utilized similar to WP the_content, the_title, etc - however, there's enough fields
 * that we want to not be tied down to individual functions.  Further, if the user passes in
 * a custom field label, we still want to be able to get it.
 *
 * @param string $field
 */
function netrastock_get_field( $field ) {
	$context = netrastock_is_single() ? 'detail' : 'listing';
	$size    = netrastock_get_config( 'display_' . $context . '_image_size' );
	if ( $field == 'inventory_image' ) {
		return netrastock_image_tags( netrastock_get_the_featured_image( $size ) );
	}
	if ( $field == 'inventory_images' ) {
		$images = netrastock_get_the_images( $size );
		$imgs   = '';
		foreach ( (array) $images AS $image ) {
			$imgs .= netrastock_get_image_tags( $image );
		}

		return $imgs;
	}

	if ( $field == 'inventory_media' ) {
		$medias = netrastock_get_the_media();
		$media  = '';
		foreach ( (array) $medias AS $m ) {
			$media .= netrastock_get_media_tags( $m );
		}

		return $media;
	}

	if ( $field == 'category_id' ) {
		$field = 'inventory_category';
	}

	$NSMLoop = netrastock_get_NSM();
	$value    = $NSMLoop->get_field( $field );

	if ( in_array( $field, array( 'description', 'inventory_description' ) ) ) {
		return apply_filters( 'the_content', $value );
	}

	return $value;
}

function netrastock_the_field( $field ) {
	echo netrastock_get_field( $field );
}

function netrastock_get_the_ID() {
	return netrastock_get_field( "inventory_id" );
}

function netrastock_the_ID() {
	echo netrastock_get_the_ID();
}


function netrastock_get_the_name() {
	return netrastock_get_field( "inventory_name" );
}

function netrastock_the_name() {
	echo netrastock_get_the_name();
}

function netrastock_get_the_number() {
	return netrastock_get_field( "inventory_number" );
}

function netrastock_the_number() {
	echo netrastock_get_the_number();
}


function netrastock_get_the_description() {
	return netrastock_get_field( "inventory_description" );
}

function netrastock_the_description() {
	echo netrastock_get_the_description();
}


function netrastock_get_the_size() {
	return netrastock_get_field( "inventory_size" );
}

function netrastock_the_size() {
	echo netrastock_get_the_size();
}


function netrastock_get_the_manufacturer() {
	return netrastock_get_field( "inventory_manufacturer" );
}

function netrastock_the_manufacturer() {
	echo netrastock_get_the_manufacturer();
}


function netrastock_get_the_make() {
	return netrastock_get_field( "inventory_make" );
}

function netrastock_the_make() {
	echo netrastock_get_the_make();
}


function netrastock_get_the_model() {
	return netrastock_get_field( "inventory_model" );
}

function netrastock_the_model() {
	echo netrastock_get_the_model();
}


function netrastock_get_the_year() {
	return netrastock_get_field( "inventory_year" );
}

function netrastock_the_year() {
	echo netrastock_get_the_year();
}


function netrastock_get_the_serial() {
	return netrastock_get_field( "inventory_serial" );
}

function netrastock_the_serial() {
	echo netrastock_get_the_serial();
}


function netrastock_get_the_fob() {
	return netrastock_get_field( "inventory_fob" );
}

function netrastock_the_fob() {
	echo netrastock_get_the_fob();
}


function netrastock_get_the_quantity() {
	return netrastock_get_field( "inventory_quantity" );
}

function netrastock_the_quantity() {
	echo netrastock_get_the_quantity();
}


function netrastock_get_the_reserved() {
	return netrastock_get_field( "inventory_quantity_reserved" );
}

function netrastock_the_reserved() {
	echo netrastock_get_the_reserved();
}


function netrastock_get_the_price() {
	$NSMLoop = netrastock_get_NSM();
	$price    = netrastock_get_field( "inventory_price" );

	return $NSMLoop->format_currency( $price );
}

function netrastock_the_price() {
	echo netrastock_get_the_price();
}


function netrastock_get_the_status() {
	return netrastock_get_field( "inventory_status" );
}

// TODO: Get the REAL status
function netrastock_the_status() {
	echo netrastock_get_the_status();
}

function netrastock_get_the_category() {
	return netrastock_get_field( 'inventory_category' );
}

function netrastock_the_category() {
	echo netrastock_get_the_category();
}

function netrastock_get_the_category_ID() {
	return netrastock_get_field( 'category_id' );
}

function netrastock_the_category_ID() {
	echo netrastock_get_the_category_ID();
}


function netrastock_get_the_date() {
	$NSMLoop = netrastock_get_NSM();
	$date     = netrastock_get_field( "inventory_date_added" );

	return $NSMLoop->format_date( $date );
}

function netrastock_the_date() {
	echo netrastock_get_the_date();
}

function netrastock_get_config( $key, $default = NULL ) {
	$config = NSMConfig::getInstance();

	return $config->get( $key, $default );
}

function netrastock_get_display_settings( $type = 'listing' ) {
	$display = netrastock_get_config( 'display_' . $type );

	$display = array_filter( explode( ',', $display ) );

	return apply_filters("NSM_display_{$type}_settings", $display);
}

function netrastock_get_the_date_updated() {
	$NSMLoop = netrastock_get_NSM();
	$date     = netrastock_get_field( "inventory_date_updated" );

	return $NSMLoop->format_date( $date );
}

function netrastock_the_date_updated() {
	echo netrastock_get_the_date_updated();
}

function netrastock_get_permalink() {
	$NSMLoop = netrastock_get_NSM();

	return $NSMLoop->get_permalink();
}

function netrastock_the_permalink() {
	echo netrastock_get_permalink();
}

function netrastock_get_backlink( $anchor = 'Back' ) {
	global $post;
	$args    = NSMCore::get_page_state();
	$post_id = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : $post->ID;
	unset( $args['post_id'] );
	$url  = http_build_query( $args );
	$back = get_permalink( $post_id );
	if ( $url ) {
		$back .= ( stripos( $back, '?' ) !== FALSE ) ? '&' : '?';
	}
	$back .= $url;
	$back = '<a href="' . $back . '" class="netrastock_back">' . $anchor . '</a>';

	return $back;
}

function netrastock_backlink() {
	echo netrastock_get_backlink();
}

function netrastock_get_category_name() {
	$NSMLoop = netrastock_get_NSM();
	$category = $NSMLoop->get_category();

	return ( $category && ! empty( $category->category_name ) ) ? $category->category_name : '';
}

/**
 * Retreive the current item's image sources
 *
 * @param string $size - thumbnail | medium | large | full
 * @param integer $limit - use 0 for no limit
 */
function netrastock_get_the_images( $size = "thumbnail", $limit = 0 ) {
	$NSMLoop = netrastock_get_NSM();

	$images = $NSMLoop->get_images( $size, $limit );

	if ( ! $images) {
		$placeholder = netrastock_get_placeholder_image($size);
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
function netrastock_the_images( $size = "thumbnail", $limit = 0 ) {
	$images = netrastock_get_the_images( $size, $limit );
	foreach ( (array) $images AS $image ) {
		echo netrastock_get_image_tags( $image );
	}
}

function netrastock_get_the_featured_image( $size = 'thumbnail' ) {
	$images = netrastock_get_the_images( $size, 1 );

	if (is_array($images) && ! empty( $images[0] ) ) {
		return $images[0];
	}

	// Should never get here.  netrastock_get_the_images should get / return placeholder if appropriate
	$placeholder = netrastock_get_placeholder_image($size);

	return $placeholder;
}

function netrastock_the_featured_image( $size = 'thumbnail' ) {
	$image = netrastock_get_the_featured_image( $size );

	netrastock_image_tags( $image );
}

/**
 * Loads the placeholder image set in configuration.
 * @param string $size thumbnail|medium|large|full|all (returns an object)
 *
 * @since version 1.1.1
 *
 * @return string
 */
function netrastock_get_placeholder_image($size = 'thumbnail') {
	$placeholder_image = netrastock_get_config( 'placeholder_image');
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

function netrastock_get_image_tags( $image ) {
	if ( ! $image ) {
		return;
	}

	return '<p class="image"><img title="' . netrastock_get_the_name() . '" alt="' . netrastock_get_the_name() . '" src="' . $image . '"></p>';
}

function netrastock_image_tags( $image ) {
	echo netrastock_get_image_tags( $image );
}

/**
 * Retrieve the current item's media sources
 *
 * @param integer $limit - use 0 for no limit
 */
function netrastock_get_the_media( $limit = 0 ) {
	$NSMLoop = netrastock_get_NSM();

	return $NSMLoop->get_media( $limit );
}

function netrastock_the_media ( $limit = 0, $new_window = TRUE ) {
	$media = netrastock_get_the_media ( $limit );
	if ($media) {
		foreach($media AS $item) {
			netrastock_media_tags( $item, $new_window );
		}
	}
}

function netrastock_get_media_tags( $media, $new_window = TRUE ) {
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

function netrastock_media_tags( $media, $new_window = TRUE ) {
	echo netrastock_get_media_tags( $media, $new_window );
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
function netrastock_get_reserve_config($args = array()) {
	$NSMLoop = netrastock_get_NSM();

	$default = array(
		'form_title'       => NSMCore::__( 'Reserve This Item' ),
		'display_name'     => (int) netrastock_get_config( 'reserve_require_name' ),
		'name_label'       => NSMCore::__( 'Name' ),
		'name'             => ( isset( $_POST['netrastock_reserve_name'] ) ) ? $_POST['netrastock_reserve_name'] : '',
		'display_address'  => (int) netrastock_get_config( 'reserve_require_address' ),
		'address_label'    => NSMCore::__( 'Address' ),
		'address'          => ( isset( $_POST['netrastock_reserve_address'] ) ) ? $_POST['netrastock_reserve_address'] : '',
		'display_city'     => (int) netrastock_get_config( 'reserve_require_city' ),
		'city_label'       => NSMCore::__( 'City' ),
		'city'             => ( isset( $_POST['netrastock_reserve_city'] ) ) ? $_POST['netrastock_reserve_city'] : '',
		'display_state'    => (int) netrastock_get_config( 'reserve_require_state' ),
		'state_label'      => NSMCore::__( 'State' ),
		'state'            => ( isset( $_POST['netrastock_reserve_state'] ) ) ? $_POST['netrastock_reserve_state'] : '',
		'display_zip'      => (int) netrastock_get_config( 'reserve_require_zip' ),
		'zip_label'        => NSMCore::__( 'Postal Code' ),
		'zip'              => ( isset( $_POST['netrastock_reserve_zip'] ) ) ? $_POST['netrastock_reserve_zip'] : '',
		'display_phone'    => (int) netrastock_get_config( 'reserve_require_phone' ),
		'phone_label'      => NSMCore::__( 'Phone' ),
		'phone'            => ( isset( $_POST['netrastock_reserve_phone'] ) ) ? $_POST['netrastock_reserve_phone'] : '',
		'display_email'    => (int) netrastock_get_config( 'reserve_require_email' ),
		'email_label'      => NSMCore::__( 'Email' ),
		'email'            => ( isset( $_POST['netrastock_reserve_email'] ) ) ? $_POST['netrastock_reserve_email'] : '',
		'display_quantity' => ( (int) netrastock_get_config( 'reserve_quantity' ) ) ? 2 : FALSE,
		'quantity_label'   => NSMCore::__( 'Quantity to Reserve' ),
		'quantity'         => ( isset( $_POST['netrastock_reserve_quantity'] ) ) ? $_POST['netrastock_reserve_quantity'] : '',
		'display_message'  => (int) netrastock_get_config( 'reserve_require_message' ),
		'message_label'    => NSMCore::__( 'Message' ),
		'message'          => ( isset( $_POST['netrastock_reserve_message'] ) ) ? $_POST['netrastock_reserve_message'] : '',
		'submit_label'     => 'Reserve',
		'inventory_id'     => $NSMLoop->single_id()
	);

	$args = wp_parse_args( $args, $default );
	return apply_filters('NSM_reserve_config', $args);
}


function netrastock_reserve_form( $args = NULL ) {
	if ( ! (int) netrastock_get_config( 'reserve_allow' ) ) {
		return '<!-- Reserve form disabled in admin dashboard -->';
	}

	$args = netrastock_get_reserve_config($args);

	$error   = '';
	$message = '';
	$display = TRUE;

	if ( isset( $_POST['netrastock_reserve_submit'] ) ) {
		$data = array();
		foreach ( $args AS $field => $required ) {
			if ( stripos( $field, 'display_' ) === 0 ) {
				$field = str_replace( 'display_', '', $field );
				if ( $field ) {
					$data[ $field ] = array(
						'value' => NSMCore::request( 'netrastock_reserve_' . $field ),
						'label' => $args[ $field . '_label' ]
					);
					if ( stripos( $field, 'quantity' ) !== FALSE ) {
						$data[ $field ]['value'] = (int) $data[ $field ]['value'];
						if ( $data[ $field ]['value'] < 0 ) {
							$data[ $field ]['value'] = 0;
						}
					}
					if ( ! trim( $data[ $field ]['value'] ) && $required === 2 ) {
						$error .= $args[ $field . '_label' ] . ' ' . NSMCore::__( 'is required.' ) . '<br />';
					}
				}
			}
		}

		if ( ! $error && (int) netrastock_get_config( 'reserve_decrement' ) ) {
			$NSM_item = new NSMItem();
			$item      = $NSM_item->get( $args['inventory_id'] );

			if ( $item ) {
				$on_hand = $item->inventory_quantity;
				if ( $data['quantity']['value'] > $on_hand ) {
					$error = NSMCore::__( 'There are not enough of this item to reserve' ) . ' ' . $data['quantity']['value'] . '<br>';
				}
			}
		}

		if ( ! $error ) {
			$data['inventory_id'] = $args['inventory_id'];
			$success              = netrastock_process_reserve( $data );
			if ( $success === TRUE ) {
				$display = FALSE;
				$message = NSMCore::__( 'Thank you.  Your reservation has been submitted.' );
			} else {
				$error = $success;
			}
		}
	}

	$args['error'] = $error;

	if ( $display ) {
		return netrastock_get_template_part( 'reserve-form', '', FALSE, $args );
	} elseif ( $message ) {
		return '<a id="NSM_reserve" name="NSM_reserve"></a><div class="netrastock_message">' . $message . '</div>';
	}
}

function netrastock_reserve_add_field($args, $field, $display, $label, $insert_before = '') {
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
		$field              => (isset( $_POST['netrastock_reserve_' . $field] ) ) ? $_POST['netrastock_reserve_' . $field] : ''
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

function netrastock_process_reserve( $data ) {
	$to_email = netrastock_get_config( 'reserve_email' );
	if ( ! $to_email ) {
		$to_email = get_option( 'admin_email' );
	}

	$subject = NSMCore::__( 'An item has been reserved from' ) . ' ' . get_bloginfo( 'site_name' );
	$message = '';

	$fields = array(
		'inventory_number',
		'inventory_serial',
		'inventory_name',
	);

	$fields = apply_filters( 'NSM_reserve_item_fields', $fields );

	$item_title = NSMCore::__( 'Item Details' );
	$item_title = apply_filters( 'NSM_reserve_title_item_details', $item_title );

	$message .= PHP_EOL . $item_title;

	$inventory_display = netrastock_get_display_settings('detail');

	if ( ! empty( $data['inventory_id'] ) ) {
		$loop = new NSMLoop( array( 'inventory_id' => $data['inventory_id'] ) );
		while ( $loop->have_items() ) {
			$loop->the_item();
			foreach ( $inventory_display AS $field ) {
				$message .= PHP_EOL . $loop->get_label( $field ) . ': ' . $loop->get_field( $field );
			}
		}
	}

	$reservation_title = NSMCore::__( 'Reservation Details' );
	$reservation_title = apply_filters( 'NSM_reserve_title_reservation_details', $reservation_title );

	$message .= PHP_EOL . PHP_EOL . $reservation_title;

	$exclude = array( 'inventory_id' );

	$exclude = apply_filters( 'NSM_reserve_exclude_form_fields', $exclude );

	$args = netrastock_get_reserve_config();

	foreach ( $data AS $field => $d ) {
		if ( ! in_array( $field, $exclude ) && $args['display_' . $field] ) {
			$message .= PHP_EOL . $d['label'] . ': ' . $d['value'];
		}
	}

	$subject = apply_filters( 'NSM_reserve_email_subject', $subject );
	$message = apply_filters( 'NSM_reserve_email_message', $message );

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
		return NSMCore::__( 'There was an issue sending your e-mail.  Please try again later.' );
	} else {
		if ( netrastock_get_config( 'reserve_decrement' ) ) {
			$NSM_item = new NSMItem();
			$NSM_item->save_reserve( $data['inventory_id'], $data['quantity']['value'] );

			do_action( 'NSM_reserve_sent', $data['inventory_id'], $data, $subject, $message );
			$status = TRUE;
		}
	}

	$send_confirmation = netrastock_get_config('reserve_confirmation');

	if ($send_confirmation) {
		// Grab e-mail from the form
		$confirm_email =  $data['email']['value'];

		// If the user is logged in, use that e-mail
		if (is_user_logged_in()) {
			$current_user = wp_get_current_user();
			$confirm_email = $current_user->user_email;
		}

		$subject = apply_filters( 'NSM_reserve_confirmation_email_subject', $subject );
		$message = apply_filters( 'NSM_reserve_confirmation_email_message', $message );

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
			return NSMCore::__('There was an issue sending the confirmation e-mail.  Please try again later.');
		} else {
			do_action('NSM_reserve_confirmation_sent', $data['inventory_id'], $data, $subject, $message);
			$status = TRUE;
		}
	}

	return $status;
}

function netrastock_filter_form_admin( $args = NULL ) {
	$args['caller'] = '_admin';

	return netrastock_filter_form( $args );
}
function netrastock_stats_filter_form_admin( $args = NULL ) {
	$args['caller'] = '_admin';

	return netrastock_stats_filter_form( $args );
}


function netrastock_get_filter_criteria( $args = array() ) {
	$NSMLoop = netrastock_get_NSM();

	$query_args = $NSMLoop->get_query_args();

	if ( ! empty( $args ) && is_string( $args ) && stripos( $args, "&" ) != FALSE ) {
		$args = explode( '&', $args );
	}

	// Override.  If the shortcode contains a category id, do not show
	if ( ! empty( $query_args['category_id'] ) && ! NSMCore::request( 'inventory_category_id' ) ) {
		$args['categories'] = FALSE;
	}

	$default = array(
		"search"       => TRUE,
		"sort"         => TRUE,
		"sort_label"   => $NSMLoop->__( "Sort By" ),
		"categories"   => TRUE,
		"button"       => $NSMLoop->__( "Search" ),
		"search_label" => $NSMLoop->__( "Search For" ),
		"caller"       => ""
	);

	$args = wp_parse_args( $args, $default );

	if ( empty( $query_args['sort_by'] ) ) {
		$query_args['sort_by'] = 'inventory_name';
	}

	$args['inventory_search']      = $NSMLoop->request( "inventory_search" );
	$args['inventory_sort_by']     = $NSMLoop->request( "inventory_sort_by", $query_args['order'] );
	$args['inventory_category_id'] = $NSMLoop->request( "inventory_category_id", $query_args['category_id'] );

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
function netrastock_filter_form( $args = NULL ) {

	global $post;
	$NSMLoop = netrastock_get_NSM();

	$args = netrastock_get_filter_criteria();
	extract($args);

	$form = '';

	if ( $search ) {
		$form .= '<span class="search">' . PHP_EOL;
		$form .= ( $search_label ) ? '<label for="inventory_search">' . $search_label . '</label>' : '';
		$form .= '<input type="text" name="inventory_search" value="' . $inventory_search . '" />';
		$form .= '</span>' . PHP_EOL;
	}

	if ( $sort ) {

		$fields = $NSMLoop->get_labels();
		$fields = apply_filters( 'NSM_filter_sort_by_options' . $caller, $fields );

		$form .= '<span class="sort">';
		$form .= ( $sort_label ) ? '<label for="inventory_sort">' . $sort_label . '</label>' : '';
		$form .= '<select name="inventory_sort_by">' . PHP_EOL;
		$form .= ( ! $sort_label ) ? '<option value="">' . $NSMLoop->__( 'Sort By...' ) . '</option>' . PHP_EOL : '';

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
		$categories = netrastock_get_categories();
		$categories = apply_filters('NSM_filter_categories_options', $categories);
		$form .= '<span class="categories"><select name="inventory_category_id">' . PHP_EOL;
		$form .= '<option value="">' . sprintf($NSMLoop->__( 'Choose %s...' ), netrastock_get_the_label('category_id')) . '</option>' . PHP_EOL;

		foreach ( $categories AS $category ) {
			$form .= '<option value="' . $category->category_id . '"';
			$form .= ( $category->category_id == $inventory_category_id ) ? ' selected' : '';
			$form .= '>' . $category->category_name . '</option>' . PHP_EOL;
		}

		$form .= '</select></span>' . PHP_EOL;
	}

	$url = ( empty( $post ) ) ? 'admin.php?page=' . $_GET['page'] : get_permalink( $post->ID );

	if ( $form ) {
		$form .= '<input type="submit" name="inventory_filter" value="' . $NSMLoop->__( 'Go' ) . '" />' . PHP_EOL;
		$form = '<form class="netrastock_filter" name="netrastock_filter" method="post" id="inventory_search" action="' . $url . '#inventory_filter">' . PHP_EOL . $form . '</form>' . PHP_EOL;
	}

	return $form;
}

//Stock Statistics Form
function netrastock_stats_filter_form( $args = NULL ) {

	global $post;
	$NSMLoop = netrastock_get_NSM();

	$args = netrastock_get_filter_criteria();
	extract($args);

	$form = '';

	if ( $search ) {
        	$form .= '<span class="search">' . PHP_EOL;
		$form .= '<label for="inventory_search"> From Date: </label>';
		$form .= '<input type="text" class="MyDate" name="search_from_date" value="' . $search_from_date . '" />';
                $form .= '</span>' . PHP_EOL;
                
                $form .= '<span class="search">' . PHP_EOL;
		$form .= '<label for="inventory_search"> To Date: </label>';                
		$form .= '<input type="text" class="MyDate" name="inventory_search1" value="' . $inventory_search1 . '" />';                
		$form.= '</span>' . PHP_EOL;               
	}

	if ( $sort ) {

		$fields = $NSMLoop->get_labels();
		$fields = apply_filters( 'NSM_filter_sort_by_options' . $caller, $fields );

		$form .= '<span class="sort">';
		$form .= ( $sort_label ) ? '<label for="inventory_sort">' . $sort_label . '</label>' : '';
		$form .= '<select name="inventory_sort_by">' . PHP_EOL;
		$form .= ( ! $sort_label ) ? '<option value="">' . $NSMLoop->__( 'Sort By...' ) . '</option>' . PHP_EOL : '';

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
		$categories = netrastock_get_categories();
		$categories = apply_filters('NSM_filter_categories_options', $categories);
		$form .= '<span class="categories"><select name="inventory_category_id">' . PHP_EOL;
		$form .= '<option value="">' . sprintf($NSMLoop->__( 'Choose %s...' ), netrastock_get_the_label('category_id')) . '</option>' . PHP_EOL;

		foreach ( $categories AS $category ) {
			$form .= '<option value="' . $category->category_id . '"';
			$form .= ( $category->category_id == $inventory_category_id ) ? ' selected' : '';
			$form .= '>' . $category->category_name . '</option>' . PHP_EOL;
		}

		$form .= '</select></span>' . PHP_EOL;
	}

	$url = ( empty( $post ) ) ? 'admin.php?page=' . $_GET['page'] : get_permalink( $post->ID );

	if ( $form ) {
		$form .= '<input type="submit" name="inventory_filter" value="' . $NSMLoop->__( 'Go' ) . '" />' . PHP_EOL;
		$form = '<form class="netrastock_filter" name="netrastock_filter" method="post" id="inventory_search" action="' . $url . '#inventory_filter">' . PHP_EOL . $form . '</form>' . PHP_EOL;
	}

	return $form;
}

function netrastock_get_categories( $args = NULL ) {
	$category = new NSMCategory();

	return $category->get_all( $args );
}

function netrastock_pagination( $url = NULL, $pages = NULL ) {

	$showing    = '';
	$pagination = '';

	$NSMLoop = netrastock_get_NSM();

	if ( ! $pages ) {
		$pages = $NSMLoop->get_pages();
	}

	extract( $pages );

	$showing = $NSMLoop->__( 'Showing [start] - [end] of [count] items' );

	$start = ( $page * $page_size ) + 1;
	$end   = $start + $page_size - 1;
	if ( $end > $item_count ) {
		$end = $item_count;
	}

	$showing = str_replace( '[start]', $start, $showing );
	$showing = str_replace( '[end]', $end, $showing );
	$showing = str_replace( '[count]', $item_count, $showing );

	$showing = '<span class="netrastock_showing">' . $showing . '</span>';

	if ( ! $url ) {
		global $post;
		$url = get_permalink( $post->ID );
	}

	if ( $page > 0 ) {
		if ( $page > 1 ) {
			$pagination .= '<a href="' . $NSMLoop->get_pagination_permalink( $url, 0 ) . '" class="page page_first">' . $NSMLoop->__( '&lt;&lt;' ) . '</a>';
		}
		$pagination .= '<a href="' . $NSMLoop->get_pagination_permalink( $url, ( $page - 1 ) ) . '" class="page page_prev">' . $NSMLoop->__( '&lt;' ) . '</a>';
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
		$pagination .= '<a href="' . $NSMLoop->get_pagination_permalink( $url, $paginate ) . '" class="page page_' . $paginate . $class . '">' . ( ++ $paginate ) . '</a>';
	}

	if ( $paginate < ( $item_count / $page_size ) ) {
		$pagination .= '<span class="ellipses">...</span>';
	}

	if ( ( $page + 1 ) < $pages ) {
		$pagination .= '<a href="' . $NSMLoop->get_pagination_permalink( $url, ( $page + 1 ) ) . '" class="page page_next">' . $NSMLoop->__( '&gt;' ) . '</a>';
		if ( ( $page + 2 ) < $pages ) {
			$pagination .= '<a href="' . $NSMLoop->get_pagination_permalink( $url, ( $pages - 1 ) ) . '" class="page page_last">' . $NSMLoop->__( '&gt;&gt;' ) . '</a>';
		}
	}

	return '<div class="netrastock_pagination">' . $showing . $pagination . '</div>';

}

// TODO: How to make this respond to different loops?
// TODO: Query args is available - no need to pass!
function netrastock_get_pages() {
	$NSMLoop = netrastock_get_NSM();

	return $NSMLoop->get_pages();
}

function netrastock_class() {
	$NSMLoop = netrastock_get_NSM();
	$class    = 'netrastock_item';
	$class .= ' netrastock_item' . $NSMLoop->get_even_or_odd();
	$class .= ' netrastockitem-' . netrastock_get_the_ID();
	$class .= ' netrastockitem-category-' . netrastock_get_the_category_ID();
	echo $class;
}

function netrastock_label_class( $label ) {
	$class = 'netrastock_label';
	$class .= ' netrastock_title ';
	$class .= preg_replace( "/\W|_/", "_", $label );
	echo $class;
}

/**
 * Available filters:
 *
 * NSM_filter_sort_by_options        - the list of sort by fields that goes into the sort by dropdown
 * NSM_filter_sort_by_options_admin - same as above, but for admin page
 * NSM_filter_categories_options    - the list of categories that goes into the categories dropdown
 */