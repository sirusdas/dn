<?php 

/**
Plugin Name: 	NETRA Billing Management Solutions
Plugin URI: 	http://webxarc.in
Description: 	Manage billing problems.
Version: 	1.2.0
Author: 	WEBXARC Developers
Author URI: 	http://webxarc.in/
Text Domain:    netrabilling

------------------------------------------------------------------------
Copyright 2016

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}

require_once "includes/netrabilling.class.php";
require_once "includes/netrabilling.config.class.php";
require_once "includes/netrabilling.api.class.php";
require_once "includes/netrabilling.db.class.php";
require_once "includes/netrabilling.item.class.php";
require_once "includes/netrabilling.category.class.php";
require_once "includes/netrabilling.status.class.php";
require_once "includes/netrabilling.label.class.php";
require_once "includes/netrabilling.admin.class.php";
require_once "includes/netrabilling.template.class.php";
require_once "includes/netrabilling.shortcode.class.php";
require_once "includes/netrabilling.widgets.class.php";
require_once "includes/netrabilling.loop.class.php";
require_once "includes/netrabilling.functions.php";
require_once "includes/netrabilling.updater.php";
require_once "views/nbm_billing_view.php";


/**
 * This is the class that takes care of all the WordPress hooks and actions.
 * The real management takes place in the NBM
 * @author WEBXARC Developers
 */
class NETRABillingInit extends NBMCore {

	public static function initialize() {
		self::$url = plugins_url('', __FILE__);
		self::$path = plugin_dir_path( __FILE__ );

		self::plugins_loaded();
		self::add_actions();

		// Dependency Injection.  Singleton pattern.
		self::$config = NBMConfig::getInstance();
		self::$api = NBMAPI::getInstance();
		
		self::plugin_updater();
                
                add_shortcode('billing', 'billing_view');
	}

	/**
	 * Set up all the wordpress hooks
	 */
	private static function add_actions() {
		$actions = array('init', 'widgets_init', 'admin_notices', 'admin_init', 'admin_menu', 'admin_enqueue_scripts', 'wp_enqueue_scripts', 'admin_print_footer_scripts');
		foreach ($actions as $action) {
			if (method_exists(__CLASS__, $action)) {
				add_action($action, array(__CLASS__, $action));
			}
		}
	}

	/**
	 * WordPress plugins_loaded action callback.  We use this to initialize the loading of any NETRA Billing add-ons
	 */
	public static function plugins_loaded() {
		do_action('nbm_load_add_ons');
	}

	/**
	 * WordPress admin_init action callback function
	 */
	public static function init() {
		if( ! session_id()) {
			session_start();
		}
		add_shortcode(self::SHORTCODE, array(__CLASS__, 'shortcode'));
		// Enable internationalization
		if( ! load_plugin_textdomain('netrabilling', false, '/wp-content/languages/')) {
			load_plugin_textdomain('netrabilling', false, basename(dirname(__FILE__)) . "/languages/");
		}

		self::setup_seo_endpoint();
	}

	/**
	 * WordPress widgets_init action callback function
	 */
	public static function widgets_init() {
		register_widget('NETRABilling_Categories_Widget');
		register_widget('NETRABilling_Latest_Items_Widget');
	}

	/**
	 * WordPress admin_notices action callback
	 
	public static function admin_notices() {
		if ( ! self::is_honest_user() && ! isset($_POST['license_key'])) {
			echo '<div class="error">';
			echo '<p>';
			echo self::__('NETRA Billing Manager is unlicensed.  Get automatic updates and support by getting a license.');
			echo ' <a href="admin.php?page=manage_settings">' . self::__('Enter your license key now.') . '</a>';
			echo '</p>';
			echo '</div>';
		}
	}
	*/
	/**
	 * WordPress admin_init action callback function
	 */
	public static function admin_init() {
		register_setting(self::SETTINGS_GROUP, self::SETTINGS);
		self::$options = get_option(self::SETTINGS);
		//wp_enqueue_style('inventory-admin-style', self::$url . '/css/style-admin.css');
	}

	/**
	 * WordPress admin_menu action callback function my code
	 */
	public static function admin_menu() {
		$lowest_role = self::$config->get('permissions_lowest_role');
		add_menu_page(self::__('Billing'), self::__('Billing'), $lowest_role, self::MENU, array(__CLASS__, 'instructions'), self::$url . '/images/admin-menu-icon.png');
		self::add_submenu('Billing Items', $lowest_role);
		self::add_submenu('Billing Categories');
		self::add_submenu('Billing Labels');
		self::add_submenu('Billing Display');
		self::add_submenu('Billing Settings');
		do_action('nbm_admin_menu');
		self::add_submenu('Add Ons');
		self::$pages = apply_filters('nbm_admin_pages', self::$pages);
	}

	/**
	 * Utility function to simplify adding submenus
	 */
	private static function add_submenu($title, $role = 'manage_options') {
		$slug = strtolower(str_replace(" ", "_", $title));
		switch(strtolower($title)) {
			case 'inventory items':
				$title = self::__('Billing Items');
				break;
			case 'categories':
				$title = self::__('Billing Categories');
				break;
			case 'labels':
				$title = self::__('Billing Labels');
				break;
			case 'display':
				$title = self::__('Billing Display');
				break;
			case 'settings':
				$title = self::__('Billing Settings');
				break;
			case 'add ons':
				$title = self::__('Add Ons');
				break;
		}
		
		add_submenu_page(self::MENU, $title, $title, $role, 'manage_' . $slug, array(__CLASS__, 'admin_' . $slug));
		self::$pages[] = 'manage_' . $slug;
	}

	public static function admin_print_footer_scripts() {
		$themes = self::load_available_themes();
?>
<script>var netrabilling_themes = <?php echo json_encode($themes); ?>;
jQuery(function($) {
	if ($('select.netrabilling_themes').length) {
		$('select.netrabilling_themes').change(
			function() {
				var theme_name = $(this).val();
				console.log(theme_name);
				var screenshot = netrabilling_themes[theme_name]['screenshot'];
				if (typeof screenshot != 'undefined') {
					$('<img src="' + screenshot + '">').load(
						function() {
							$('.theme_screenshot').empty().append($(this));
						}
					)
				}
			}
		).trigger('change');
	}
});</script>
<?php
	}

	public static function shortcode($args) {
		self::$shortcode = NBMShortcode::getInstance();
		return self::$shortcode->get($args);
	}

	public static function instructions() {
		self::admin_call("instructions");
	}

	public static function admin_billing_items() {
		self::admin_call("manage_billing_items");
	}

	public static function admin_billing_categories() {
		self::admin_call("manage_billing_categories");
	}

	public static function admin_billing_labels() {
		self::admin_call("manage_billing_labels");
	}

	public static function admin_billing_display() {
		self::admin_call("manage_billing_display");
	}

	public static function admin_billing_settings() {
		self::admin_call("manage_billing_settings");
	}

	public static function admin_add_ons() {
		self::admin_call("manage_add_ons");
	}

	public static function admin_call($method) {
		self::$admin = NBMAdmin::getInstance();
		self::$admin->{$method}();
	}

	public static function setup_seo_endpoint() {
		// Add the query var filter
		add_filter('query_vars', array(__CLASS__, 'rewrite_variables'));

		$seo_urls = (int)self::$config->get("seo_urls");
		$seo_endpoint = self::$config->get("seo_endpoint", 'inventory');

		// add item as a possible "tail" item
		if ($seo_urls) {
			add_rewrite_endpoint($seo_endpoint, EP_PAGES);
		}
	}

	// add seo rewrite endpoint as an allowed query var
	public static function rewrite_variables($public_query_vars) {
		// add item as a possible "tail" item
		if (self::$config->get('seo_urls', FALSE)) {
			$seo_endpoint = self::$config->get('seo_endpoint', 'netrabilling');
			$public_query_vars[] = $seo_endpoint;
		}

		return $public_query_vars;
	}

	/**
	 * WordPress admin_enqueue_scripts action callback function
	 */
	public static function admin_enqueue_scripts() {
		$page = (isset($_GET["page"])) ? $_GET["page"] : '';

		if (in_array($page, self::$pages)) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-datepicker');
				
			// Check WP version to get the best version of media upload
			$wp_version = get_bloginfo('version');
			if ((float)$wp_version >= 3.5) {
				wp_enqueue_media();
			} else {
				wp_enqueue_script('media-upload');
				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
			}
				
			wp_register_script('netrabilling-admin', self::$url . '/js/netrabilling-admin.js');
			wp_localize_script('netrabilling-admin', 'netrabilling', array(
					'pluginUrl'		=> self::$url,
					'ajaxUrl'		=> admin_url('admin-ajax.php'),
					'nonce'			=> wp_create_nonce(self::NONCE_ACTION),
					'image_label'	=> self::__('Images'),
					'media_label'	=> self::__('Media'),
					'url_label'		=> self::__('URL'),
					'title_label'	=> self::__('Title'),
					'delete_prompt'	=> self::__('Are you sure you want to delete'),
					'delete_general'=> self::__('this item'),
					'delete_named'	=> self::__('the item'),
					'prompt_qm'		=> self::__('?')
			)
			);
			wp_enqueue_script('netrabilling-admin');

			wp_enqueue_style('netrabilling', self::$url . '/css/style-admin.css');
		}
	}
	
	/**
	 * Wordpress enqueue scripts for the frontend
	 */
	public static function wp_enqueue_scripts() {
		$theme = self::$config->get('theme');
		if ($theme) {
			$theme = self::get_theme_url($theme);
			wp_enqueue_style('netrabilling-theme', $theme);
		} else {
			echo '<!-- ' . self::__('NETRA Billing styles not loaded due to settings in dashboard.') . '-->' . PHP_EOL;
		}

		wp_register_script('netrabilling-common', self::$url . '/js/netrabilling.js', array('jquery'), NBMAdmin::VERSION, TRUE);
		wp_localize_script('netrabilling-common', 'netrabilling', array(
				'ajaxUrl'	=> admin_url('admin-ajax.php')
		)
		);
		wp_enqueue_script('netrabilling-common');
	}
	
	private static function plugin_updater() {
		// retrieve our license key from the DB
		$reg_info = self::get_reg_info();
		$reg_key = ( ! empty($reg_info['key'])) ? $reg_info['key'] : '';
		
		// setup the updater
		$updater = new NBMUpdater( NBMAPI::API_URL, __FILE__, array(
				'version' 	=> self::VERSION, // current version number
				'license' 	=> $reg_key, // license key (used get_option above to retrieve from DB)
				'item_name' => NBMAPI::REG_ITEM_NAME, // name of this plugin
				'author' 	=> 'NETRA Billing', // author of this plugin
			)
		);
	}
}

// Instantiate the class
add_action('plugins_loaded', array('NETRABillingInit', 'initialize'));