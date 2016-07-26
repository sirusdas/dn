<?php

/*
Plugin Name: Netra optics dashboard
Plugin URI: http://webxarc.in/netra
Description: This plugin is required and please do not deactivate it.
Version: 1.0
Author: Sirus Das
Author URI: http://webxarc.in
License: GPLv2
*/


//add_action( 'init', 'netra_create_post_type' );

// remove for all but administrator
  
function netra_remove_menus_client () {
    if ( ! current_user_can('manage_options') ) {
                global $menu;
                $restricted = array(__('Dashboard'), __('Posts'), __('Media'), __('Links'), __('Pages'), __('Appearance'), __('Tools'), __('Users'), __('Settings'), __('Comments'), __('Plugins'), __('Contact'), __('Portfolios'));
                end ($menu);
                while (prev($menu)){
                        $value = explode(' ',$menu[key($menu)][0]);
                        if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
                }
        }
} 
add_action('admin_menu', 'netra_remove_menus_client');

// remove for  administrator
  
function netra_remove_menus_admin () {
    if (  current_user_can('manage_options') ) {
                global $menu;
                $restricted = array(__('acme_product'));
                end ($menu);
                while (prev($menu)){
                        $value = explode(' ',$menu[key($menu)][0]);
                        if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
                }
        }
} 
add_action('admin_menu', 'netra_remove_menus_admin');

//remove widgets

function wptutsplus_remove_dashboard_widgets() {
     if ( ! current_user_can('manage_options') ) {
            $user = wp_get_current_user();
            if ( ! $user->has_cap( 'manage_options' ) ) {
                remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
                remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
                remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
                remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
                remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
                remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
                remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
                remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
                remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
        }
     }
}
add_action( 'wp_dashboard_setup', 'wptutsplus_remove_dashboard_widgets' );





        /*sirus mycode
        global $wpdb;
        global $current_user;
        get_currentuserinfo();
$incfile = 'wp-includes/pluggable-functions.php';
$c=0;
while(!is_file($incfile))
{
$incfile = '../' . $incfile;
$c++;
if($c==30) {
echo "Could not find pluggable-functions.php.";
exit;
}
}
require_once($incfile);
       // $gid = $wpdb->get_var( "SELECT gid FROM $wpdb->wp_users where user_login=$user_ID" );
        
        
        
        // This is in the PHP file and sends a Javascript alert to the client
$message = "The User_id is ".$current_user->user_login." The gid is ".$gid;
echo "<script type='text/javascript'>alert('$message');</script>";



        ////////////////////////////////////////////////////////////////////////////////////////////////////////
  $sirus;
  $gid;
function get_user(){
		global $current_user;
                global $wpdb;
                
                $sirus=$current_user->user_login;
                $gid = $wpdb->get_var( "SELECT gid FROM $wpdb->users where user_login='$sirus'" );
                
                print_r($current_user->user_login);
                print_r($gid);
                
	}
	add_action( 'admin_head', 'get_user' );
        
        */