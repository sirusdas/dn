<?php
/**
 * Send Mail
 *
 * @package TA Meghna
 */

	define( 'WP_USE_THEMES', false );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

    $email_to =   ta_option( 'contact_email' ); //the address to which the email will be sent
    $name     =   $_POST['name'];  
    $email    =   $_POST['email'];
    $subject  =   $_POST['subject'];
    $message  =   $_POST['message'];
    
	/*the $header variable is for the additional headers in the mail function,
	 we are assigning 2 values, first one is FROM and the second one is REPLY-TO.
	 That way when we want to reply the email Gmail(or Yahoo or Hotmail...) will know
	 who are we replying to. */
    $headers  = "From: $email\r\n";
   // $headers .= "Reply-To: $email\r\n";

    if( wp_mail( $email_to, $subject, $message, $headers ) ){
        echo 'sent'; // we are sending this text to the Ajax request telling it that the mail is sent.
    } else {
        echo 'failed'; // ... or this one to tell it that it wasn't sent.
    }
?>