<?php

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * Class for locating templates and returning the proper template.
 * NETRAstock supports template overrides.
 * 
 * To override a template / view, simply copy the appropriate template
 * from the plugin /view directory into your WP Theme directory inside a folder titled netrastock/
 * 
 * @author Alpha Channel Group
 *
 */
class NSMTemplate extends NSMDB {
	
	
	private static $instance;
	
	private static $args;
	
	/**
	 * Constructor magic method.
	 * Private because this class should not be called on its own.
	 */
	public function __construct() {
		parent::__construct();
	}
	
	public static function getInstance() {
		if ( ! self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/**
	 * This is here purely to prevent someone from cloning the class
	 */
	private function __clone() {
	}
	
	/**
	 * Loads theme files in appropriate hierarchy: 
	 *    1) child theme and
	 *    2) parent template will look in the netrastock/views directory
	 *    3) views/ directory in the plugin
	 *
	 * @param string $template template file to search for
	 * @return template path
	 **/
	public static function get($template) {
		// append .php to file name
		if ( substr( $template, -4 ) != '.php' ) {
			$template .= '.php';
		}
	
		$file = FALSE;
	
		// check if there are overrides at all
		if (locate_template(array(self::VIEWFOLDER))) {
			$overrides_exist = TRUE;
		} else {
			$overrides_exist = FALSE;
		}
	
		if ($overrides_exist) {
			// check the theme for specific file requested
			$file = locate_template( array(self::VIEWFOLDER . $template ), FALSE, FALSE);
		}
	
		// if the theme file wasn't found, check our plugins views dirs
		if ( ! $file) {
			$file = self::$path . 'views/' . $template;
		}
		
		if ( ! file_exists($file)) {
			$file = FALSE;
		}

		return apply_filters('netrastock_template_' . $template, $file);
	}
}

