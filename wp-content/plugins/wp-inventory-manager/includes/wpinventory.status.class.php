<?php

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}

class WPIMStatus extends WPIMDB {
	
	private static $instance;
	
	/**
	* Constructor magic method.
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
	private function __clone() {}
		
	/**
	 * Get a listing of categories.
	 * @param array $args
	 * valid arguments:
	 * order - set the sort order
	 * per_page - set the number per page
	 * paged - set the starting page
	 */
	public function get_all($args = NULL) {
		return $this->wpdb->get_results('SELECT * FROM ' . $this->status_table . ' AS s ORDER BY status_id');
	} 
	
	public function get_fields() {
		return array(
			"status_id",
			"status_name",
			"category_description",
			"category_slug",
			"category_sort_order"	
		);
	}
	
	/**
	 * Get specific category
	 * @param integer $category_id
	 */
	public function get($category_id) {
		return $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->category_table . ' WHERE category_id = %d', $category_id));
	}
	
	public function save($data) {
		extract($data);
		$category_slug = $this->validate_slug('category', $category_slug, $category_name, $category_id);
		$query = $this->wpdb->prepare(" " . $this->category_table . " SET 
			category_name = %s, 
			category_slug = %s,
			category_description = %s,
			category_sort_order = %d", 
			$category_name, $category_slug, $category_description, $category_sort_order);
		
		if ($category_id) {
			$query = $this->wpdb->prepare('UPDATE' . $query . ' WHERE category_id=%d', $category_id);
		} else {
			$query = 'INSERT INTO' . $query;
		}
		
		$this->wpdb->query($query);
		
		return ( ! $this->wpdb->last_error) ? TRUE : FALSE;
	}
	
	public function dropdown($name, $selected, $class = '') {
		$categories = $this->get_all();
		$select = '<select name="' . $name . '"';
		$select.= ($class) ? ' class="' . $class . '"' : '';
		$select.= '>' . PHP_EOL;
		$select.= '<option value="">' . $this->__('Select Category') . '</option>' . PHP_EOL;
		foreach($categories AS $category) {
			$select.= '<option value="' . $category->category_id . '"';
			$select.= ($category->category_id == $selected) ? ' selected' : '';
			$select.= '>' . $category->category_name . '</option>' . PHP_EOL;
		}
		$select.= '</select>' . PHP_EOL;
		return $select;
	}
}
