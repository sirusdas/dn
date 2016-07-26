<?php 

/**
Plugin Name: 	WP Inventory
Plugin URI: 	http://www.wpinventory.com
Description: 	Manage and display your products just like a shopping cart, but without the cart.
Version: 		1.2.0
Author: 		WP Inventory Manager
Author URI: 	http://www.wpinventory.com/
Text Domain:    wpinventory

------------------------------------------------------------------------
Copyright 2009-2014 Alpha Channel Group Corporation

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

require_once "includes/wpinventory.class.php";
require_once "includes/wpinventory.config.class.php";
require_once "includes/wpinventory.api.class.php";
require_once "includes/wpinventory.db.class.php";
require_once "includes/wpinventory.item.class.php";
require_once "includes/wpinventory.category.class.php";
require_once "includes/wpinventory.status.class.php";
require_once "includes/wpinventory.label.class.php";
require_once "includes/wpinventory.admin.class.php";
require_once "includes/wpinventory.template.class.php";
require_once "includes/wpinventory.shortcode.class.php";
require_once "includes/wpinventory.widgets.class.php";
require_once "includes/wpinventory.loop.class.php";
require_once "includes/wpinventory.functions.php";
require_once "includes/wpinventory.updater.php";


/**
 * This is the class that takes care of all the WordPress hooks and actions.
 * The real management takes place in the WPInventory Class
 * @author Alpha Channel Group
 */
class WPInventoryInit extends WPIMCore {

	public static function initialize() {
		self::$url = plugins_url('', __FILE__);
		self::$path = plugin_dir_path( __FILE__ );

		self::plugins_loaded();
		self::add_actions();

		// Dependency Injection.  Singleton pattern.
		self::$config = WPIMConfig::getInstance();
		self::$api = WPIMAPI::getInstance();
		
		self::plugin_updater();            

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
	 * WordPress plugins_loaded action callback.  We use this to initialize the loading of any WP Inventory add-ons
	 */
	public static function plugins_loaded() {
		do_action('wpim_load_add_ons');
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
		if( ! load_plugin_textdomain('wpinventory', false, '/wp-content/languages/')) {
			load_plugin_textdomain('wpinventory', false, basename(dirname(__FILE__)) . "/languages/");
		}

		self::setup_seo_endpoint();
                
	}

	/**
	 * WordPress widgets_init action callback function
	 */
	public static function widgets_init() {
		register_widget('WPInventory_Categories_Widget');
		register_widget('WPInventory_Latest_Items_Widget');
	}

	/**
	 * WordPress admin_notices action callback
	 
	public static function admin_notices() {
		if ( ! self::is_honest_user() && ! isset($_POST['license_key'])) {
			echo '<div class="error">';
			echo '<p>';
			echo self::__('WP Inventory Manager is unlicensed.  Get automatic updates and support by getting a license.');
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
		wp_enqueue_style('inventory-admin-style', self::$url . '/css/style-admin.css');
                wp_enqueue_style('font-awesome', self::$url . '/css/font-awesome-4.5.0/css/font-awesome.min.css');

	}

	/**
	 * WordPress admin_menu action callback function my code
	 */
	public static function admin_menu() {
		$lowest_role = self::$config->get('permissions_lowest_role');
		add_menu_page(self::__('Orders'), self::__('Add Orders'), $lowest_role, self::MENU, array(__CLASS__, 'manage_inventory_items'), self::$url . '/images/admin-menu-icon.png');
		//self::add_submenu('Add Order', $lowest_role);
        self::add_submenu('Order Reports', $lowest_role);
		self::add_submenu('Categories');
		self::add_submenu('Labels');
		self::add_submenu('Display');
		self::add_submenu('Settings');
		do_action('wpim_admin_menu');
		self::add_submenu('Add Ons');
		self::$pages = apply_filters('wpim_admin_pages', self::$pages);
	}

	/**
	 * Utility function to simplify adding submenus
	 */
	private static function add_submenu($title, $role = 'manage_options') {
		$slug = strtolower(str_replace(" ", "_", $title));
		switch(strtolower($title)) {
			case 'add order':
				$title = self::__('Add Order');
				break;
			case 'order reports':
				$title = self::__('Order Reports');
				break;                        
			case 'categories':
				$title = self::__('Categories');
				break;
			case 'labels':
				$title = self::__('Labels');
				break;
			case 'display':
				$title = self::__('Display');
				break;
			case 'settings':
				$title = self::__('Settings');
				break;
			case 'add ons':
				$title = self::__('Add Ons');
				break;
		}
		
		add_submenu_page(self::MENU, $title, $title, $role, 'manage_' . $slug, array(__CLASS__, 'admin_' . $slug));
		//self::$pages[] = 'manage_' . $slug; 
		self::$pages[] = $slug;//modified
	}

	public static function admin_print_footer_scripts() {
		$themes = self::load_available_themes();
?>
<script>var wpinventory_themes = <?php echo json_encode($themes); ?>;
jQuery(function($) {
	if ($('select.wpinventory_themes').length) {
		$('select.wpinventory_themes').change(
			function() {
				var theme_name = $(this).val();
				console.log(theme_name);
				var screenshot = wpinventory_themes[theme_name]['screenshot'];
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
		self::$shortcode = WPIMShortcode::getInstance();
		return self::$shortcode->get($args);
	}

	public static function instructions() {
		self::admin_call("instructions");
	}
        
	public static function manage_inventory_items() {
		self::admin_call("orders");
	}        

	public static function admin_add_order() {
		self::admin_call("manage_inventory_items");
	}
        
	public static function admin_order_reports() {
		self::admin_call("manage_inventory_reports");
	}        

	public static function admin_categories() {
		self::admin_call("manage_categories");
	}

	public static function admin_labels() {
		self::admin_call("manage_labels");
	}

	public static function admin_display() {
		self::admin_call("manage_display");
	}

	public static function admin_settings() {
		self::admin_call("manage_settings");
	}

	public static function admin_add_ons() {
		self::admin_call("manage_add_ons");
	}

	public static function admin_call($method) {
		self::$admin = WPIMAdmin::getInstance();
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
			$seo_endpoint = self::$config->get('seo_endpoint', 'wpinventory');
			$public_query_vars[] = $seo_endpoint;
		}

		return $public_query_vars;
	}

	/**
	 * WordPress admin_enqueue_scripts action callback function
	 */
	public static function admin_enqueue_scripts() {
		$page = (isset($_GET["page"])) ? $_GET["page"] : '';
		if (in_array($page, self::$pages) || $page == "orders") {
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
				
			wp_register_script('wpinventory-admin', self::$url . '/js/wpinventory-admin.js');
            wp_register_script('wpinventory-ui', self::$url . '/js/wpjquery-ui.js');
            wp_register_script('wpnetra-orders', self::$url . '/js/wpnetra-orders.js');
			wp_localize_script('wpinventory-admin', 'wpinventory', array(
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
			wp_enqueue_script('wpinventory-admin');
                        wp_enqueue_script('wpinventory-ui');
                        //my code
                        wp_enqueue_script('wpnetra-orders');
			wp_enqueue_style('wpinventory', self::$url . '/css/style-admin.css');
                        // my code adding the jquery ui css
//        wp_enqueue_style('jquery-style', get_template_directory_uri() . '/css/jquery-ui.css');
        wp_enqueue_style('jquery-style','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');        
        
        // and the script following it
        wp_enqueue_script('jquery-ui-datepicker');
		}
		
	}
	
	/**
	 * Wordpress enqueue scripts for the frontend
	 */
	public static function wp_enqueue_scripts() {
		$theme = self::$config->get('theme');
		if ($theme) {
			$theme = self::get_theme_url($theme);
			wp_enqueue_style('wpinventory-theme', $theme);
		} else {
			echo '<!-- ' . self::__('WP Inventory styles not loaded due to settings in dashboard.') . '-->' . PHP_EOL;
		}

		wp_register_script('wpinventory-common', self::$url . '/js/wpinventory.js', array('jquery'), WPIMAdmin::VERSION, TRUE);
		wp_localize_script('wpinventory-common', 'wpinventory', array(
				'ajaxUrl'	=> admin_url('admin-ajax.php')
		)
		);
		wp_enqueue_script('wpinventory-common');
	}
	
	private static function plugin_updater() {
		// retrieve our license key from the DB
		$reg_info = self::get_reg_info();
		$reg_key = ( ! empty($reg_info['key'])) ? $reg_info['key'] : '';
		
		// setup the updater
		$updater = new WPIMUpdater( WPIMAPI::API_URL, __FILE__, array(
				'version' 	=> self::VERSION, // current version number
				'license' 	=> $reg_key, // license key (used get_option above to retrieve from DB)
				'item_name' => WPIMAPI::REG_ITEM_NAME, // name of this plugin
				'author' 	=> 'WP Inventory', // author of this plugin
			)
		);
	}
}

// Instantiate the class
add_action('plugins_loaded', array('WPInventoryInit', 'initialize'));

function load_custom_wp_admin_style() {
        wp_register_script('wp-common', get_template_directory_uri() . '/js/wpnetra.js', array('jquery'),'1.0', TRUE);
        wp_enqueue_script('wp-common');
}