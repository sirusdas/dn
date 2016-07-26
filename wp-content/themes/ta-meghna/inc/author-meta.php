<?php
/**
 * Get Author Meta
 *
 * @package TA Meghna
 */

function ta_modify_contact_methods( $profile_fields ) {

	// Add new fields
	$profile_fields['twitter'] = __( 'Twitter Username', 'ta-meghna' );
	$profile_fields['facebook'] = __( 'Facebook URL', 'ta-meghna' );
	$profile_fields['gplus'] = __( 'Google+ URL', 'ta-meghna' );

	return $profile_fields;
}
add_filter( 'user_contactmethods', 'ta_modify_contact_methods' );