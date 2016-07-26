<?php 

/**
Plugin Name: 	NETRA stock Management Solutions
Plugin URI: 	http://webxarc.in
Description: 	Manage various stock and their details.
Version: 		1.2.0
Author: 		WEBXARC Developers
Author URI: 	http://webxarc.in
Text Domain:    netrastock

------------------------------------------------------------------------
Copyright 2009-2016 


 */

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}

require_once "includes/netrastock.class.php";
require_once "includes/netrastock.config.class.php";
require_once "includes/netrastock.api.class.php";
require_once "includes/netrastock.db.class.php";
require_once "includes/netrastock.item.class.php";
require_once "includes/netrastock.category.class.php";
require_once "includes/netrastock.status.class.php";
require_once "includes/netrastock.label.class.php";
require_once "includes/netrastock.admin.class.php";
require_once "includes/netrastock.template.class.php";
require_once "includes/netrastock.shortcode.class.php";
require_once "includes/netrastock.widgets.class.php";
require_once "includes/netrastock.loop.class.php";
require_once "includes/netrastock.functions.php";
require_once "includes/netrastock.updater.php";
require_once "includes/netrastock.bill.php";


/**
 * This is the class that takes care of all the WordPress hooks and actions.
 * The real management takes place in the Netrastock Class
 * @author Alpha Channel Group
 */
class NETRAstockInit extends NSMCore {

	public static function initialize() {
		self::$url = plugins_url('', __FILE__);
		self::$path = plugin_dir_path( __FILE__ );

		self::plugins_loaded();
		self::add_actions();

		// Dependency Injection.  Singleton pattern.
		self::$config = NSMConfig::getInstance();
		self::$api = NSMAPI::getInstance();
		
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
	 * WordPress plugins_loaded action callback.  We use this to initialize the loading of any NETRA Billing add-ons
	 */
	public static function plugins_loaded() {
		do_action('NSM_load_add_ons');
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
		if( ! load_plugin_textdomain('netrastock', false, '/wp-content/languages/')) {
			load_plugin_textdomain('netrastock', false, basename(dirname(__FILE__)) . "/languages/");
		}

		self::setup_seo_endpoint();
	}

	/**
	 * WordPress widgets_init action callback function
	 */
	public static function widgets_init() {
		register_widget('NETRAstock_Categories_Widget');
		register_widget('NETRAstock_Latest_Items_Widget');
	}

	/**
	 * WordPress admin_notices action callback
	 
	public static function admin_notices() {
		if ( ! self::is_honest_user() && ! isset($_POST['license_key'])) {
			echo '<div class="error">';
			echo '<p>';
			echo self::__('NETRA stock Manager is unlicensed.  Get automatic updates and support by getting a license.');
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
	}

	/**
	 * WordPress admin_menu action callback function my code
	 */
	public static function admin_menu() {
		$lowest_role = self::$config->get('permissions_lowest_role');
		add_menu_page(self::__('Stock'), self::__('Stock Details'), $lowest_role, self::MENU, array(__CLASS__, 'manage_stock_details'), self::$url . '/images/admin-menu-icon.png');
		//self::add_submenu('Stock Details', $lowest_role);
                self::add_submenu('Stock Report', $lowest_role);
                self::add_submenu('Bill', $lowest_role);
		self::add_submenu('Edit Stock Type');
		self::add_submenu('Edit Stock Labels');
		self::add_submenu('Edit Stock Display');
		self::add_submenu('Edit Stock Settings');
		do_action('NSM_admin_menu');
		self::add_submenu('Add Ons');
		self::$pages = apply_filters('NSM_admin_pages', self::$pages);
	}

	/**
	 * Utility function to simplify adding submenus
	 */
	private static function add_submenu($title, $role = 'manage_options') {
		$slug = strtolower(str_replace(" ", "_", $title));
		switch(strtolower($title)) {
                        case 'bill':
				$title = self::__('Bill');
				break;
                        case 'stock report':
				$title = self::__('Stock Report');
				break;
			case 'stock details':
				$title = self::__('Stock Details');
				break;
			case 'edit bill type':
				$title = self::__('Edit Bill Type');
				break;
			case 'edit labels':
				$title = self::__('Edit Bill Labels');
				break;
			case 'edit stock display':
				$title = self::__('Edit Stock Display');
				break;
			case 'edit stock settings':
				$title = self::__('Edit Stock Settings');
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
<script>var netrastock_themes = <?php echo json_encode($themes); ?>;
jQuery(function($) {
	if ($('select.netrastock_themes').length) {
		$('select.netrastock_themes').change(
			function() {
				var theme_name = $(this).val();
				console.log(theme_name);
				var screenshot = netrastock_themes[theme_name]['screenshot'];
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
		self::$shortcode = NSMShortcode::getInstance();
		return self::$shortcode->get($args);
	}

	public static function instructions() {
		self::admin_call("instructions");
	}

	public static function manage_stock_details() {
		self::admin_call("manage_stock_details");
	}
        
	public static function admin_stock_details() {
		self::admin_call("manage_stock_details");
	}
        
        public static function admin_bill(){
            self::admin_call("manage_bill");
        }

	public static function admin_stock_report() {
		self::admin_call("manage_stock_stats_details");
	}
        
	public static function admin_edit_stock_type() {
		self::admin_call("manage_edit_type");
	}

	public static function admin_edit_stock_labels() {
		self::admin_call("manage_edit_labels");
	}

	public static function admin_edit_stock_display() {
		self::admin_call("manage_edit_stock_display");
	}

	public static function admin_edit_stock_settings() {
		self::admin_call("manage_edit_settings");
	}

	public static function admin_add_ons() {
		self::admin_call("manage_add_ons");
	}

	public static function admin_call($method) {
		self::$admin = NSMAdmin::getInstance();
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
			$seo_endpoint = self::$config->get('seo_endpoint', 'netrastock');
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
				
			wp_register_script('netrastock-admin', self::$url . '/js/netrastock-admin.js');
			wp_localize_script('netrastock-admin', 'netrastock', array(
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
			wp_enqueue_script('netrastock-admin');

			wp_enqueue_style('netrastock', self::$url . '/css/style-admin.css');
                        
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
			wp_enqueue_style('netrastock-theme', $theme);
		} else {
			echo '<!-- ' . self::__('Netra stock Management styles not loaded due to settings in dashboard.') . '-->' . PHP_EOL;
		}

		wp_register_script('netrastock-common', self::$url . '/js/netrastock.js', array('jquery'), NSMAdmin::VERSION, TRUE);
		wp_localize_script('netrastock-common', 'netrastock', array(
				'ajaxUrl'	=> admin_url('admin-ajax.php')
		)
		);
		wp_enqueue_script('netrastock-common');
	}
	
	private static function plugin_updater() {
		// retrieve our license key from the DB
		$reg_info = self::get_reg_info();
		$reg_key = ( ! empty($reg_info['key'])) ? $reg_info['key'] : '';
		
		// setup the updater
		$updater = new NSMUpdater( NSMAPI::API_URL, __FILE__, array(
				'version' 	=> self::VERSION, // current version number
				'license' 	=> $reg_key, // license key (used get_option above to retrieve from DB)
				'item_name' => NSMAPI::REG_ITEM_NAME, // name of this plugin
				'author' 	=> 'Netra stock Management', // author of this plugin
			)
		);
	}
}

// Instantiate the class
add_action('plugins_loaded', array('NETRAstockInit', 'initialize'));