<?php

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * 
 * Activate license:
 * http://YOURSITE.com/?edd_action=activate_license
 * 		&item_name=EDD+Product+Name
 * 		&license=cc22c1ec86304b36883440e2e84cddff
 * 		&url=http://licensedsite.com
 * 
 * Responses:
VALID:
{
	"license": "valid",
	"item_name": "EDD Product name",
	"expires": "2014-10-23 00:00:00",
	"payment_id": 54224,
	"customer_name": "John Doe",
	"customer_email": "john@sample.com"
}

INVALID:

{
	"license": "invalid",
	"item_name": "EDD Product name",
	"expires": "2014-10-23 00:00:00",
	"payment_id": 54224,
	"customer_name": "John Doe",
	"customer_email": "john@sample.com"
}

 * 
 * Check license: 
 * http://YOURSITE.com/?edd_action=check_license
 * 			&item_name=EDD+Product+Name
 * 			&license=cc22c1ec86304b36883440e2e84cddff
 * 			&url=http://licensedsite.com
 * 
 * Responses:
VALID:
{
	"license": "valid",
	"item_name": "EDD Product name",
	"expires": "2014-10-23 00:00:00",
	"payment_id": 54224,
	"customer_name": "John Doe",
	"customer_email": "john@sample.com"
}

INVALID:
{
	"license": "invalid",
	"item_name": "EDD Product name",
	"expires": "2014-10-23 00:00:00",
	"payment_id": 54224,
	"customer_name": "John Doe",
	"customer_email": "john@sample.com"
}

 * @author Cale
 *
 */

class NCMAPI {
	
	private static $instance;
	
	private static $config;
	
	private $error;
	
	const API_URL = 'http://www.netracustomer.com/license_api/'; // 'http://netracustomer.mrwpress.com'; //
	
	const REG_ITEM_NAME = 'NETRA Customer Manager';
	
	public function __construct() {
		self::$config = NCMConfig::getInstance();
	}
	
	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
	
		return self::$instance;
	}
	
	public static function activate($data, $key) {

		// data to send in our API request
		if ( ! $data) {
			$item_name = self::REG_ITEM_NAME;
			$item_key = 'core';
		} else {
			$item_name = $data->item_name;
			$item_key = $data->key;
		}

		$api_params = array( 
			'edd_action'=> 'activate_license', 
			'license' 	=> $key,
			'item_name' => urlencode( $item_name ), // the name of our product in EDD
			'url'       => home_url()
		);

		$response = wp_remote_post( self::API_URL, array('timeout' => 15, 'sslverify' => FALSE, 'body' => $api_params ) );

		if ( is_wp_error( $response ) ) {
			// Try again with post
			$response = wp_remote_get(add_query_arg($api_params, self::API_URL), array('timeout' => 15, 'sslverify' => FALSE));

			if ( is_wp_error( $response) ) {
				echo '<div class="error"><p>' . NCMCore::__( 'When attempting to activate license, could not reach license site.' ) . '</p></div>';

				return FALSE;
			}
		}

		if ( ! $response) {
			echo '<div class="error"><p>' . NCMCore::__('When attempting to activate license, no response was received.') . '</p></div>';
		} else {
			if (wp_remote_retrieve_response_code($response) != 200) {
				echo '<div class="error"><p>' . sprintf(NCMCore::__('When attempting to activate license, a response code of %d was returned.'), wp_remote_retrieve_response_code($response)) . '</p>';
				echo '<p><strong>Debugging Information: Please provide the information below to support.</strong></p>';
				var_dump($response);
				echo '</div>';
			} else {
				$response = json_decode( wp_remote_retrieve_body( $response ) );
				if ( $response->license != 'valid' ) {
					echo '<div class="error"><p>' . sprintf(NCMCore::__( 'The license entered for %s is invalid.' ), $item_name) . '</p></div>';
					$key = array(
						'key'     => '',
						'expires' => NULL,
						'valid'   => FALSE
					);

				} else {
					echo '<div class="updated"><p>' . sprintf(NCMCore::__( 'Congratulations! The %s license entered is valid.' ), $item_name);
					if ($item_key != 'core') {
						echo ' <a href="' . admin_url('admin.php?page=manage_settings') . '">' . sprintf(NCMCore::__('(Click to refresh if %s is not visible.)'), $item_name) . '</a>';
					}
					echo '</p></div>';
					$key = array(
						'key'     => $key,
						'expires' => strtotime( $response->expires ),
						'valid'   => TRUE
					);
				}
			}

			$all_reg_info = NCMCore::get_reg_info();
			$all_reg_info[$item_key] = $key;
			update_option('ncm_license', $all_reg_info);
		}		
	}

	// This method is used for checking add-on status, etc.
	public static function make_call($method) {
		$params = array('api_call'  => json_encode(array('method'   => 'ncm_' . $method)));
		$response = '';
		$results = wp_remote_get(add_query_arg($params, self::API_URL), array('timeout' => 15, 'sslverify' => FALSE));
		if (wp_remote_retrieve_response_code($results) == 200) {
			$response = wp_remote_retrieve_body($results);
			$response = json_decode($response);
			if ( ! $response) {
				echo 'INVALID RESPONSE JSON';
				$response = '';
			} else {
				$response = $response->data;
			}
		} else {
//			echo "API CALL FAILED";
		}

		return $response;
	}
	
	public function get_error() {
		return $this->error;	
	}
}