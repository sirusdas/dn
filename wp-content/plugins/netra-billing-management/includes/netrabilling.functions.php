<?php

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * This file contains simple functions that wrap the NETRABilling class methods for
 * easy access in template files.
 */

global $netrabilling_item;

/**
 * Includes a template part, similar to the WP get template part, but looks
 * in the correct directories for NETRABilling templates
 *
 * @param string $slug
 * @param null|string $name
 *
 * @uses NBMTemplates::get
 * @author WEBXARC Developers
 * @since 0.1
 **/
function netrabilling_get_template_part( $slug, $name = NULL, $echo = TRUE, $args = NULL ) {
	// Execute code for this part
	do_action( 'nbm_pre_get_template_part_' . $slug, $slug, $name );
	// Setup possible parts
	$templates = array( $slug . '.php' );
	if ( isset( $name ) ) {
		array_unshift( $templates, $slug . '-' . $name . '.php' );
	}

	// Allow template parts to be filtered
	$templates = apply_filters( 'nbm_get_template_part_templates', $templates, $slug, $name );

	$found = FALSE;
	// loop through templates, return first one found.
	foreach ( $templates as $template ) {
		$file = NBMTemplate::get( $template );
		$file = apply_filters( 'nbm_get_template_part_path', $file, $template, $slug, $name );
		$file = apply_filters( 'nbm_get_template_part_path_' . $template, $file, $slug, $name );
		if ( file_exists( $file ) ) {
			$found = TRUE;
			ob_start();
			do_action( 'nbm_before_get_template_part', $template, $file, $template, $slug, $name );
			include( $file );
			do_action( 'nbm_after_get_template_part', $template, $file, $slug, $name );
			$html = ob_get_clean();
			echo apply_filters( 'nbm_get_template_part_content', $html, $template, $file, $slug, $name );
			break;
		}
	}

	if ( ! $found ) {
		echo '<!-- Could not find template ' . $slug . ' ' . $name . '-->';
	}
	do_action( 'nbm_post_get_template_part_' . $slug, $slug, $name );
}

global $NBMLoop;
/**
 * Utility function to get the $NBM variable, and set it if it's not yet set
 */
function netrabilling_get_nbm() {
	global $NBMLoop;
	if ( ! $NBMLoop ) {
		$NBMLoop = new NBMLoop();
	}

	return $NBMLoop;
}

function netrabilling_set_loop( $loop ) {
	global $NBMLoop;
	$NBMLoop = $loop;
}

/**
 * Similar to WP query_posts, loads the inventory items
 *
 * @param array $args
 */
function netrabilling_get_items( $args = NULL ) {
	$NBMLoop = netrabilling_get_nbm();
	$NBMLoop->load_items( $args );
}

/**
 * Similar to WP have_posts, checks to see if there are any items loaded
 */
function netrabilling_have_items() {
	$NBMLoop = netrabilling_get_nbm();

	return $NBMLoop->have_items();
}

/**
 * Similiar to WP the_post, prepares the item for access
 */
function netrabilling_the_item() {
	global $netrabilling_item;
	$NBMLoop = netrabilling_get_nbm();
	$NBMLoop->the_item();
}

function netrabilling_is_single() {
	$NBMLoop = netrabilling_get_nbm();

	return $NBMLoop->is_single();
}

function netrabilling_get_the_label( $field ) {
	$labels = NBMLabel::getInstance();
	$label  = $labels->get_label( $field );

	if ( $label ) {
		return $label;
	} else {
		return $field;
	}
}

function netrabilling_the_label( $field ) {
	echo netrabilling_get_the_label( $field );
}

function netrabilling_get_all_labels() {
	$labels = NBMLAbel::getInstance();

	return $labels->get_all();
}

/**
 * To be utilized similar to WP the_content, the_title, etc - however, there's enough fields
 * that we want to not be tied down to individual functions.  Further, if the user passes in
 * a custom field label, we still want to be able to get it.
 *
 * @param string $field
 */
function netrabilling_get_field( $field ) {
	$context = netrabilling_is_single() ? 'detail' : 'listing';
	$size    = netrabilling_get_config( 'display_' . $context . '_image_size' );
	if ( $field == 'inventory_image' ) {
		return netrabilling_image_tags( netrabilling_get_the_featured_image( $size ) );
	}
	if ( $field == 'inventory_images' ) {
		$images = netrabilling_get_the_images( $size );
		$imgs   = '';
		foreach ( (array) $images AS $image ) {
			$imgs .= netrabilling_get_image_tags( $image );
		}

		return $imgs;
	}

	if ( $field == 'inventory_media' ) {
		$medias = netrabilling_get_the_media();
		$media  = '';
		foreach ( (array) $medias AS $m ) {
			$media .= netrabilling_get_media_tags( $m );
		}

		return $media;
	}

	if ( $field == 'category_id' ) {
		$field = 'inventory_category';
	}

	$NBMLoop = netrabilling_get_nbm();
	$value    = $NBMLoop->get_field( $field );

	if ( in_array( $field, array( 'description', 'inventory_description' ) ) ) {
		return apply_filters( 'the_content', $value );
	}

	return $value;
}
//my code i have edited this from echo to return
function netrabilling_the_field( $field ) {
    return netrabilling_get_field( $field );
}

function netrabilling_get_the_ID() {
	return netrabilling_get_field( "inventory_id" );
}

function netrabilling_the_ID() {
	echo netrabilling_get_the_ID();
}


function netrabilling_get_the_name() {
	return netrabilling_get_field( "inventory_name" );
}

function netrabilling_the_name() {
	echo netrabilling_get_the_name();
}

function netrabilling_get_the_number() {
	return netrabilling_get_field( "inventory_number" );
}

function netrabilling_the_number() {
	echo netrabilling_get_the_number();
}


function netrabilling_get_the_description() {
	return netrabilling_get_field( "inventory_description" );
}

function netrabilling_the_description() {
	echo netrabilling_get_the_description();
}


function netrabilling_get_the_size() {
	return netrabilling_get_field( "inventory_size" );
}

function netrabilling_the_size() {
	echo netrabilling_get_the_size();
}


function netrabilling_get_the_manufacturer() {
	return netrabilling_get_field( "inventory_manufacturer" );
}

function netrabilling_the_manufacturer() {
	echo netrabilling_get_the_manufacturer();
}


function netrabilling_get_the_make() {
	return netrabilling_get_field( "inventory_make" );
}

function netrabilling_the_make() {
	echo netrabilling_get_the_make();
}


function netrabilling_get_the_model() {
	return netrabilling_get_field( "inventory_model" );
}

function netrabilling_the_model() {
	echo netrabilling_get_the_model();
}


function netrabilling_get_the_year() {
	return netrabilling_get_field( "inventory_year" );
}

function netrabilling_the_year() {
	echo netrabilling_get_the_year();
}


function netrabilling_get_the_serial() {
	return netrabilling_get_field( "inventory_serial" );
}

function netrabilling_the_serial() {
	echo netrabilling_get_the_serial();
}


function netrabilling_get_the_fob() {
	return netrabilling_get_field( "inventory_fob" );
}

function netrabilling_the_fob() {
	echo netrabilling_get_the_fob();
}


function netrabilling_get_the_quantity() {
	return netrabilling_get_field( "inventory_quantity" );
}

function netrabilling_the_quantity() {
	echo netrabilling_get_the_quantity();
}


function netrabilling_get_the_reserved() {
	return netrabilling_get_field( "inventory_quantity_reserved" );
}

function netrabilling_the_reserved() {
	echo netrabilling_get_the_reserved();
}


function netrabilling_get_the_price() {
	$NBMLoop = netrabilling_get_nbm();
	$price    = netrabilling_get_field( "inventory_price" );

	return $NBMLoop->format_currency( $price );
}

function netrabilling_the_price() {
	echo netrabilling_get_the_price();
}


function netrabilling_get_the_status() {
	return netrabilling_get_field( "inventory_status" );
}

// TODO: Get the REAL status
function netrabilling_the_status() {
	echo netrabilling_get_the_status();
}

function netrabilling_get_the_category() {
	return netrabilling_get_field( 'inventory_category' );
}

function netrabilling_the_category() {
	echo netrabilling_get_the_category();
}

function netrabilling_get_the_category_ID() {
	return netrabilling_get_field( 'category_id' );
}

function netrabilling_the_category_ID() {
	echo netrabilling_get_the_category_ID();
}


function netrabilling_get_the_date() {
	$NBMLoop = netrabilling_get_nbm();
	$date     = netrabilling_get_field( "inventory_date_added" );

	return $NBMLoop->format_date( $date );
}

function netrabilling_the_date() {
	echo netrabilling_get_the_date();
}

function netrabilling_get_config( $key, $default = NULL ) {
	$config = NBMConfig::getInstance();

	return $config->get( $key, $default );
}

function netrabilling_get_display_settings( $type = 'listing' ) {
	$display = netrabilling_get_config( 'display_' . $type );

	$display = array_filter( explode( ',', $display ) );

	return apply_filters("nbm_display_{$type}_settings", $display);
}

function netrabilling_get_the_date_updated() {
	$NBMLoop = netrabilling_get_nbm();
	$date     = netrabilling_get_field( "inventory_date_updated" );

	return $NBMLoop->format_date( $date );
}

function netrabilling_the_date_updated() {
	echo netrabilling_get_the_date_updated();
}

function netrabilling_get_permalink() {
	$NBMLoop = netrabilling_get_nbm();

	return $NBMLoop->get_permalink();
}

function netrabilling_the_permalink() {
	echo netrabilling_get_permalink();
}

function netrabilling_get_backlink( $anchor = 'Back' ) {
	global $post;
	$args    = NBMCore::get_page_state();
	$post_id = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : $post->ID;
	unset( $args['post_id'] );
	$url  = http_build_query( $args );
	$back = get_permalink( $post_id );
	if ( $url ) {
		$back .= ( stripos( $back, '?' ) !== FALSE ) ? '&' : '?';
	}
	$back .= $url;
	$back = '<a href="' . $back . '" class="netrabilling_back">' . $anchor . '</a>';

	return $back;
}

function netrabilling_backlink() {
	echo netrabilling_get_backlink();
}

function netrabilling_get_category_name() {
	$NBMLoop = netrabilling_get_nbm();
	$category = $NBMLoop->get_category();

	return ( $category && ! empty( $category->category_name ) ) ? $category->category_name : '';
}

/**
 * Retreive the current item's image sources
 *
 * @param string $size - thumbnail | medium | large | full
 * @param integer $limit - use 0 for no limit
 */
function netrabilling_get_the_images( $size = "thumbnail", $limit = 0 ) {
	$NBMLoop = netrabilling_get_nbm();

	$images = $NBMLoop->get_images( $size, $limit );

	if ( ! $images) {
		$placeholder = netrabilling_get_placeholder_image($size);
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
function netrabilling_the_images( $size = "thumbnail", $limit = 0 ) {
	$images = netrabilling_get_the_images( $size, $limit );
	foreach ( (array) $images AS $image ) {
		echo netrabilling_get_image_tags( $image );
	}
}

function netrabilling_get_the_featured_image( $size = 'thumbnail' ) {
	$images = netrabilling_get_the_images( $size, 1 );

	if (is_array($images) && ! empty( $images[0] ) ) {
		return $images[0];
	}

	// Should never get here.  netrabilling_get_the_images should get / return placeholder if appropriate
	$placeholder = netrabilling_get_placeholder_image($size);

	return $placeholder;
}

function netrabilling_the_featured_image( $size = 'thumbnail' ) {
	$image = netrabilling_get_the_featured_image( $size );

	netrabilling_image_tags( $image );
}

/**
 * Loads the placeholder image set in configuration.
 * @param string $size thumbnail|medium|large|full|all (returns an object)
 *
 * @since version 1.1.1
 *
 * @return string
 */
function netrabilling_get_placeholder_image($size = 'thumbnail') {
	$placeholder_image = netrabilling_get_config( 'placeholder_image');
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

function netrabilling_get_image_tags( $image ) {
	if ( ! $image ) {
		return;
	}

	return '<p class="image"><img title="' . netrabilling_get_the_name() . '" alt="' . netrabilling_get_the_name() . '" src="' . $image . '"></p>';
}

function netrabilling_image_tags( $image ) {
	echo netrabilling_get_image_tags( $image );
}

/**
 * Retrieve the current item's media sources
 *
 * @param integer $limit - use 0 for no limit
 */
function netrabilling_get_the_media( $limit = 0 ) {
	$NBMLoop = netrabilling_get_nbm();

	return $NBMLoop->get_media( $limit );
}

function netrabilling_the_media ( $limit = 0, $new_window = TRUE ) {
	$media = netrabilling_get_the_media ( $limit );
	if ($media) {
		foreach($media AS $item) {
			netrabilling_media_tags( $item, $new_window );
		}
	}
}

function netrabilling_get_media_tags( $media, $new_window = TRUE ) {
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

function netrabilling_media_tags( $media, $new_window = TRUE ) {
	echo netrabilling_get_media_tags( $media, $new_window );
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
function netrabilling_get_reserve_config($args = array()) {
	$NBMLoop = netrabilling_get_nbm();

	$default = array(
		'form_title'       => NBMCore::__( 'Reserve This Item' ),
		'display_name'     => (int) netrabilling_get_config( 'reserve_require_name' ),
		'name_label'       => NBMCore::__( 'Name' ),
		'name'             => ( isset( $_POST['netrabilling_reserve_name'] ) ) ? $_POST['netrabilling_reserve_name'] : '',
		'display_address'  => (int) netrabilling_get_config( 'reserve_require_address' ),
		'address_label'    => NBMCore::__( 'Address' ),
		'address'          => ( isset( $_POST['netrabilling_reserve_address'] ) ) ? $_POST['netrabilling_reserve_address'] : '',
		'display_city'     => (int) netrabilling_get_config( 'reserve_require_city' ),
		'city_label'       => NBMCore::__( 'City' ),
		'city'             => ( isset( $_POST['netrabilling_reserve_city'] ) ) ? $_POST['netrabilling_reserve_city'] : '',
		'display_state'    => (int) netrabilling_get_config( 'reserve_require_state' ),
		'state_label'      => NBMCore::__( 'State' ),
		'state'            => ( isset( $_POST['netrabilling_reserve_state'] ) ) ? $_POST['netrabilling_reserve_state'] : '',
		'display_zip'      => (int) netrabilling_get_config( 'reserve_require_zip' ),
		'zip_label'        => NBMCore::__( 'Postal Code' ),
		'zip'              => ( isset( $_POST['netrabilling_reserve_zip'] ) ) ? $_POST['netrabilling_reserve_zip'] : '',
		'display_phone'    => (int) netrabilling_get_config( 'reserve_require_phone' ),
		'phone_label'      => NBMCore::__( 'Phone' ),
		'phone'            => ( isset( $_POST['netrabilling_reserve_phone'] ) ) ? $_POST['netrabilling_reserve_phone'] : '',
		'display_email'    => (int) netrabilling_get_config( 'reserve_require_email' ),
		'email_label'      => NBMCore::__( 'Email' ),
		'email'            => ( isset( $_POST['netrabilling_reserve_email'] ) ) ? $_POST['netrabilling_reserve_email'] : '',
		'display_quantity' => ( (int) netrabilling_get_config( 'reserve_quantity' ) ) ? 2 : FALSE,
		'quantity_label'   => NBMCore::__( 'Quantity to Reserve' ),
		'quantity'         => ( isset( $_POST['netrabilling_reserve_quantity'] ) ) ? $_POST['netrabilling_reserve_quantity'] : '',
		'display_message'  => (int) netrabilling_get_config( 'reserve_require_message' ),
		'message_label'    => NBMCore::__( 'Message' ),
		'message'          => ( isset( $_POST['netrabilling_reserve_message'] ) ) ? $_POST['netrabilling_reserve_message'] : '',
		'submit_label'     => 'Reserve',
		'inventory_id'     => $NBMLoop->single_id()
	);

	$args = wp_parse_args( $args, $default );
	return apply_filters('nbm_reserve_config', $args);
}


function netrabilling_reserve_form( $args = NULL ) {
	if ( ! (int) netrabilling_get_config( 'reserve_allow' ) ) {
		return '<!-- Reserve form disabled in admin dashboard -->';
	}

	$args = netrabilling_get_reserve_config($args);

	$error   = '';
	$message = '';
	$display = TRUE;

	if ( isset( $_POST['netrabilling_reserve_submit'] ) ) {
		$data = array();
		foreach ( $args AS $field => $required ) {
			if ( stripos( $field, 'display_' ) === 0 ) {
				$field = str_replace( 'display_', '', $field );
				if ( $field ) {
					$data[ $field ] = array(
						'value' => NBMCore::request( 'netrabilling_reserve_' . $field ),
						'label' => $args[ $field . '_label' ]
					);
					if ( stripos( $field, 'quantity' ) !== FALSE ) {
						$data[ $field ]['value'] = (int) $data[ $field ]['value'];
						if ( $data[ $field ]['value'] < 0 ) {
							$data[ $field ]['value'] = 0;
						}
					}
					if ( ! trim( $data[ $field ]['value'] ) && $required === 2 ) {
						$error .= $args[ $field . '_label' ] . ' ' . NBMCore::__( 'is required.' ) . '<br />';
					}
				}
			}
		}

		if ( ! $error && (int) netrabilling_get_config( 'reserve_decrement' ) ) {
			$nbm_item = new NBMItem();
			$item      = $nbm_item->get( $args['inventory_id'] );

			if ( $item ) {
				$on_hand = $item->inventory_quantity;
				if ( $data['quantity']['value'] > $on_hand ) {
					$error = NBMCore::__( 'There are not enough of this item to reserve' ) . ' ' . $data['quantity']['value'] . '<br>';
				}
			}
		}

		if ( ! $error ) {
			$data['inventory_id'] = $args['inventory_id'];
			$success              = netrabilling_process_reserve( $data );
			if ( $success === TRUE ) {
				$display = FALSE;
				$message = NBMCore::__( 'Thank you.  Your reservation has been submitted.' );
			} else {
				$error = $success;
			}
		}
	}

	$args['error'] = $error;

	if ( $display ) {
		return netrabilling_get_template_part( 'reserve-form', '', FALSE, $args );
	} elseif ( $message ) {
		return '<a id="nbm_reserve" name="nbm_reserve"></a><div class="netrabilling_message">' . $message . '</div>';
	}
}

function netrabilling_reserve_add_field($args, $field, $display, $label, $insert_before = '') {
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
		$field              => (isset( $_POST['netrabilling_reserve_' . $field] ) ) ? $_POST['netrabilling_reserve_' . $field] : ''
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

function netrabilling_process_reserve( $data ) {
	$to_email = netrabilling_get_config( 'reserve_email' );
	if ( ! $to_email ) {
		$to_email = get_option( 'admin_email' );
	}

	$subject = NBMCore::__( 'An item has been reserved from' ) . ' ' . get_bloginfo( 'site_name' );
	$message = '';

	$fields = array(
		'inventory_number',
		'inventory_serial',
		'inventory_name',
	);

	$fields = apply_filters( 'nbm_reserve_item_fields', $fields );

	$item_title = NBMCore::__( 'Item Details' );
	$item_title = apply_filters( 'nbm_reserve_title_item_details', $item_title );

	$message .= PHP_EOL . $item_title;

	$inventory_display = netrabilling_get_display_settings('detail');

	if ( ! empty( $data['inventory_id'] ) ) {
		$loop = new NBMLoop( array( 'inventory_id' => $data['inventory_id'] ) );
		while ( $loop->have_items() ) {
			$loop->the_item();
			foreach ( $inventory_display AS $field ) {
				$message .= PHP_EOL . $loop->get_label( $field ) . ': ' . $loop->get_field( $field );
			}
		}
               
	}

	$reservation_title = NBMCore::__( 'Reservation Details' );
	$reservation_title = apply_filters( 'nbm_reserve_title_reservation_details', $reservation_title );

	$message .= PHP_EOL . PHP_EOL . $reservation_title;

	$exclude = array( 'inventory_id' );

	$exclude = apply_filters( 'nbm_reserve_exclude_form_fields', $exclude );

	$args = netrabilling_get_reserve_config();

	foreach ( $data AS $field => $d ) {
		if ( ! in_array( $field, $exclude ) && $args['display_' . $field] ) {
			$message .= PHP_EOL . $d['label'] . ': ' . $d['value'];
		}
	}

	$subject = apply_filters( 'nbm_reserve_email_subject', $subject );
	$message = apply_filters( 'nbm_reserve_email_message', $message );

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
		return NBMCore::__( 'There was an issue sending your e-mail.  Please try again later.' );
	} else {
		if ( netrabilling_get_config( 'reserve_decrement' ) ) {
			$nbm_item = new NBMItem();
			$nbm_item->save_reserve( $data['inventory_id'], $data['quantity']['value'] );

			do_action( 'nbm_reserve_sent', $data['inventory_id'], $data, $subject, $message );
			$status = TRUE;
		}
	}

	$send_confirmation = netrabilling_get_config('reserve_confirmation');

	if ($send_confirmation) {
		// Grab e-mail from the form
		$confirm_email =  $data['email']['value'];

		// If the user is logged in, use that e-mail
		if (is_user_logged_in()) {
			$current_user = wp_get_current_user();
			$confirm_email = $current_user->user_email;
		}

		$subject = apply_filters( 'nbm_reserve_confirmation_email_subject', $subject );
		$message = apply_filters( 'nbm_reserve_confirmation_email_message', $message );

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
			return NBMCore::__('There was an issue sending the confirmation e-mail.  Please try again later.');
		} else {
			do_action('nbm_reserve_confirmation_sent', $data['inventory_id'], $data, $subject, $message);
			$status = TRUE;
		}
	}

	return $status;
}

function netrabilling_filter_form_admin( $args = NULL ) {
	$args['caller'] = '_admin';

	return netrabilling_filter_form( $args );
}

function netrabilling_get_filter_criteria( $args = array() ) {
	$NBMLoop = netrabilling_get_nbm();

	$query_args = $NBMLoop->get_query_args();

	if ( ! empty( $args ) && is_string( $args ) && stripos( $args, "&" ) != FALSE ) {
		$args = explode( '&', $args );
	}

	// Override.  If the shortcode contains a category id, do not show
	if ( ! empty( $query_args['category_id'] ) && ! NBMCore::request( 'inventory_category_id' ) ) {
		$args['categories'] = FALSE;
	}

	$default = array(
		"search"       => TRUE,
		"sort"         => TRUE,
		"sort_label"   => $NBMLoop->__( "Sort By" ),
		"categories"   => TRUE,
		"button"       => $NBMLoop->__( "Search" ),
		"search_label" => $NBMLoop->__( "Search For" ),
		"caller"       => ""
	);

	$args = wp_parse_args( $args, $default );

	if ( empty( $query_args['sort_by'] ) ) {
		$query_args['sort_by'] = 'inventory_name';
	}

	$args['inventory_search']      = $NBMLoop->request( "inventory_search" );
	$args['inventory_search1']      = $NBMLoop->request( "inventory_search1" );
        $args['search_from_date']      = $NBMLoop->request( "search_from_date" );
	$args['inventory_sort_by']     = $NBMLoop->request( "inventory_sort_by", $query_args['order'] );
	$args['inventory_category_id'] = $NBMLoop->request( "inventory_category_id", $query_args['category_id'] );

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
function netrabilling_filter_form( $args = NULL ) {

	global $post;
	$NBMLoop = netrabilling_get_nbm();

	$args = netrabilling_get_filter_criteria();
	extract($args);

	$form = '';

	if ( $search ) {
		$form .= '<span class="search">' . PHP_EOL;
		$form .= ( $search_label ) ? '<label for="inventory_search">' . $search_label . '</label>' : '';
		$form .= '<input type="text" class="MyDate" name="inventory_search" value="' . $inventory_search . '" />';
                $form .= '</span>' . PHP_EOL;

	}

   
	
	if ( $sort ) {

		$fields = $NBMLoop->get_labels();
		$fields = apply_filters( 'nbm_filter_sort_by_options' . $caller, $fields );

		$form .= '<span class="sort">';
		$form .= ( $sort_label ) ? '<label for="inventory_sort">' . $sort_label . '</label>' : '';
		$form .= '<select name="inventory_sort_by">' . PHP_EOL;
		$form .= ( ! $sort_label ) ? '<option value="">' . $NBMLoop->__( 'Sort By...' ) . '</option>' . PHP_EOL : '';

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
		$categories = netrabilling_get_categories();
		$categories = apply_filters('nbm_filter_categories_options', $categories);
		$form .= '<span class="categories"><select name="inventory_category_id">' . PHP_EOL;
		$form .= '<option value="">' . sprintf($NBMLoop->__( 'Choose %s...' ), netrabilling_get_the_label('category_id')) . '</option>' . PHP_EOL;

		foreach ( $categories AS $category ) {
			$form .= '<option value="' . $category->category_id . '"';
			$form .= ( $category->category_id == $inventory_category_id ) ? ' selected' : '';
			$form .= '>' . $category->category_name . '</option>' . PHP_EOL;
		}

		$form .= '</select></span>' . PHP_EOL;
	}

	$url = ( empty( $post ) ) ? 'admin.php?page=' . $_GET['page'] : get_permalink( $post->ID );

	if ( $form ) {
		$form .= '<input type="submit" name="inventory_filter" value="' . $NBMLoop->__( 'Go' ) . '" />' . PHP_EOL;
		$form = '<form class="netrabilling_filter" name="netrabilling_filter" method="post" id="inventory_search" action="' . $url . '#inventory_filter">' . PHP_EOL . $form . '</form>' . PHP_EOL;
	}

	return $form;
}

//my code for filtering as per the date////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function netrabilling_get_filter_date_criteria( $args = array() ) {
	$NBMLoop = netrabilling_get_nbm();

	$query_args = $NBMLoop->get_query_args();

	if ( ! empty( $args ) && is_string( $args ) && stripos( $args, "&" ) != FALSE ) {
		$args = explode( '&', $args );
	}

	// Override.  If the shortcode contains a category id, do not show
	if ( ! empty( $query_args['category_id'] ) && ! NBMCore::request( 'inventory_category_id' ) ) {
		$args['categories'] = FALSE;
	}

	$default = array(
		"search"       => TRUE,
		"sort"         => FASE,
		"sort_label"   => $NBMLoop->__( "Sort By" ),
		"categories"   => TRUE,
		"button"       => $NBMLoop->__( "Search" ),
		"search_label" => $NBMLoop->__( "Search For" ),
		"caller"       => ""
	);

	$args = wp_parse_args( $args, $default );

	if ( empty( $query_args['sort_by'] ) ) {
		$query_args['sort_by'] = 'inventory_name';
	}

	$args['inventory_search1']      = $NBMLoop->request( "inventory_search1" );
        $args['search_from_date']      = $NBMLoop->request( "search_from_date" );

	return $args;
}

function netrabilling_filter_dates( $args = NULL ) {

	global $post;
	$NBMLoop = netrabilling_get_nbm();

	$args = netrabilling_get_filter_date_criteria();
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

	

	$url = ( empty( $post ) ) ? 'admin.php?page=' . $_GET['page'] : get_permalink( $post->ID );

	if ( $form ) {
		$form .= '<input type="submit" name="inventory_filter" value="' . $NBMLoop->__( 'Go' ) . '" />' . PHP_EOL;
		$form = '<form class="netrabilling_filter" name="netrabilling_filter" method="post" id="inventory_search" action="' . $url . '?tr=1#inventory_filter">' . PHP_EOL . $form . '</form>' . PHP_EOL;
	}

	return $form;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



function netrabilling_get_categories( $args = NULL ) {
	$category = new NBMCategory();

	return $category->get_all( $args );
}

function netrabilling_pagination( $url = NULL, $pages = NULL ) {

	$showing    = '';
	$pagination = '';

	$NBMLoop = netrabilling_get_nbm();

	if ( ! $pages ) {
		$pages = $NBMLoop->get_pages();
	}

	extract( $pages );

	$showing = $NBMLoop->__( 'Showing [start] - [end] of [count] items' );

	$start = ( $page * $page_size ) + 1;
	$end   = $start + $page_size - 1;
	if ( $end > $item_count ) {
		$end = $item_count;
	}

	$showing = str_replace( '[start]', $start, $showing );
	$showing = str_replace( '[end]', $end, $showing );
	$showing = str_replace( '[count]', $item_count, $showing );

	$showing = '<span class="netrabilling_showing">' . $showing . '</span>';

	if ( ! $url ) {
		global $post;
		$url = get_permalink( $post->ID );
	}

	if ( $page > 0 ) {
		if ( $page > 1 ) {
			$pagination .= '<a href="' . $NBMLoop->get_pagination_permalink( $url, 0 ) . '" class="page page_first">' . $NBMLoop->__( '&lt;&lt;' ) . '</a>';
		}
		$pagination .= '<a href="' . $NBMLoop->get_pagination_permalink( $url, ( $page - 1 ) ) . '" class="page page_prev">' . $NBMLoop->__( '&lt;' ) . '</a>';
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
		$pagination .= '<a href="' . $NBMLoop->get_pagination_permalink( $url, $paginate ) . '" class="page page_' . $paginate . $class . '">' . ( ++ $paginate ) . '</a>';
	}

	if ( $paginate < ( $item_count / $page_size ) ) {
		$pagination .= '<span class="ellipses">...</span>';
	}

	if ( ( $page + 1 ) < $pages ) {
		$pagination .= '<a href="' . $NBMLoop->get_pagination_permalink( $url, ( $page + 1 ) ) . '" class="page page_next">' . $NBMLoop->__( '&gt;' ) . '</a>';
		if ( ( $page + 2 ) < $pages ) {
			$pagination .= '<a href="' . $NBMLoop->get_pagination_permalink( $url, ( $pages - 1 ) ) . '" class="page page_last">' . $NBMLoop->__( '&gt;&gt;' ) . '</a>';
		}
	}

	return '<div class="netrabilling_pagination">' . $showing . $pagination . '</div>';

}

// TODO: How to make this respond to different loops?
// TODO: Query args is available - no need to pass!
function netrabilling_get_pages() {
	$NBMLoop = netrabilling_get_nbm();

	return $NBMLoop->get_pages();
}

function netrabilling_class() {
	$NBMLoop = netrabilling_get_nbm();
	$class    = 'netrabilling_item';
	$class .= ' netrabilling_item' . $NBMLoop->get_even_or_odd();
	$class .= ' netrabillingitem-' . netrabilling_get_the_ID();
	$class .= ' netrabillingitem-category-' . netrabilling_get_the_category_ID();
	echo $class;
}

function netrabilling_label_class( $label ) {
	$class = 'netrabilling_label';
	$class .= ' netrabilling_title ';
	$class .= preg_replace( "/\W|_/", "_", $label );
	echo $class;
}

/**
 * Available filters:
 *
 * nbm_filter_sort_by_options        - the list of sort by fields that goes into the sort by dropdown
 * nbm_filter_sort_by_options_admin - same as above, but for admin page
 * nbm_filter_categories_options    - the list of categories that goes into the categories dropdown
 */