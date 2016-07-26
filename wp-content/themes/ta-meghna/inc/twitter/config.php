<?php
/**
 * Your Twitter App Info
 *
 * @package TA Meghna
 */

	define( 'WP_USE_THEMES', false );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

    // Consumer Key
    define( 'CONSUMER_KEY', ta_option( 'twiiter_consumer_key' ) );
    define( 'CONSUMER_SECRET', ta_option( 'twiiter_consumer_secret' ) );

    // User Access Token
    define( 'ACCESS_TOKEN', ta_option( 'twiiter_access_token' ) );
    define( 'ACCESS_SECRET', ta_option( 'twiiter_access_token_secret') );