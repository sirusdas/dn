<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPIMLabel extends WPIMDB {

	private static $instance;

	private static $labels;

	/**
	 * Constructor magic method.
	 */
	public function __construct() {
		parent::__construct();
		$this->load();
	}

	public static function getInstance() {
		if ( ! self::$instance ) {
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
	 * Get a listing of all custom labels.
	 */
	public function get_all() {

		if ( ! self::$labels ) {
			$this->load();
		}

		return self::$labels;
	}

	/**
	 * Get specific label
	 *
	 * @param string $field_name
	 */
	public function get( $field_name ) {
		return ( ! empty( self::$labels[ $field_name ] ) ) ? self::$labels[ $field_name ] : $field_name;
	}

	public function get_numeric() {
		$labels = $this->get_all();
		$labels = array_filter($labels, array($this, 'is_numeric'));

		return ( ! empty($labels)) ? array_keys($labels) : array();
	}

	public function is_numeric($data) {
		return ( ! $data['is_numeric']) ? FALSE : TRUE;
	}

	/**
	 * Get the field name from a specific label
	 *
	 * @param string $label
	 */
	public function find_field( $label ) {
		if ( $label && ! empty( self::$labels ) ) {
			foreach ( self::$labels AS $field => $labels ) {
				if ( $labels['default'] == $label || $labels['label'] == $label ) {
					return $field;
				}
			}
		}
	}


	public function reset() {
		$is_used = array();
		foreach ( $this->default_labels() AS $field => $label ) {
			$is_used[ $field ] = 1;
		}
		$this->save( $this->default_labels(), $is_used );
	}

	private function load() {
		$label_data = $this->wpdb->get_results( 'SELECT * FROM ' . $this->label_table . ' AS l' );

		// Load defaults
		$labels = self::default_labels();

		foreach ( $labels AS $field => $default ) {
			$labels[ $field ] = array(
				"default"    => $default,
				"label"      => $default,
				"is_used"    => TRUE,
				"is_numeric" => FALSE
			);
		}

		// Overload any set labels
		foreach ( $label_data AS $label ) {
			$labels[ $label->label_field ]['label']      = $label->label_label;
			$labels[ $label->label_field ]['is_used']    = ( $this->is_always_on( $label->label_field ) || $label->is_used ) ? TRUE : FALSE;
			$labels[ $label->label_field ]['is_numeric'] = ( $label->is_numeric ) ? TRUE : FALSE;
		}

		// We don't want the id set up in this configuration
		if ( isset( $labels['inventory_id'] ) ) {
			//unset( $labels['inventory_id'] );
		}

		uasort( $labels, array( __CLASS__, 'sortNotUsed' ) );

		self::$labels = $labels;
	}

	private function sortNotUsed( $a, $b ) {
		if ( $a['is_used'] == $b['is_used'] ) {
			$val = ( strcasecmp( $a['label'], $b['label'] ) < 0 ) ? - 1 : 1;

			return $val;
		}

		return ( $a['is_used'] < $b['is_used'] ) ? 1 : - 1;
	}

	public static function default_labels() {
		$defaults = array(
			'inventory_id'                              => WPInventoryInit::__(  'Inventory ID' ),
			'order_no'                                 => WPInventoryInit::__(  'Order No' ),
                        'c_no'                                      => WPInventoryInit::__(  'Cusomer No' ),
                        'date'                                      => WPInventoryInit::__(  'Date' ),
                        'd_date'                                    => WPInventoryInit::__(  'Delivery Date' ),
                        'c_fname'                                   => WPInventoryInit::__(  'First Name' ),
                        'c_lname'                                   => WPInventoryInit::__(  'Last Name' ),
                        'c_gender'                                  => WPInventoryInit::__(  'Gender' ),
                        'c_add'                                     => WPInventoryInit::__(  'Address' ),
                        'c_city'                                    => WPInventoryInit::__(  'City' ),
                        'c_city_pin'                                => WPInventoryInit::__(  'Pin' ),
                        'c_email'                                   => WPInventoryInit::__(  'Email' ),
                        'c_birth'                                   => WPInventoryInit::__(  'DOB' ),
                        'c_anni'                                    => WPInventoryInit::__(  'Anniversary' ),
                        'f_count'                                   => WPInventoryInit::__(  'Total Frame' ),
                        'l_count'                                   => WPInventoryInit::__(  'Total Lens' ),
                        'f_price'                                   => WPInventoryInit::__(  'Total Frame Price' ),
                        'l_price'                                   => WPInventoryInit::__(  'Total Lens Price' ),
                        'others'                                    => WPInventoryInit::__(  'Others' ),
                        'adj'                                       => WPInventoryInit::__(  'Adjustment' ),
                        'total'                                     => WPInventoryInit::__(  'Total' ),
                        'adv'                                       => WPInventoryInit::__(  'Advance' ),
                        'bal'                                       => WPInventoryInit::__(  'Balance' ),
                        'status'                                    => WPInventoryInit::__(  'Satus' ),
                        'r_d_sph'                                   => WPInventoryInit::__(  'SPH' ),
                        'r_d_cyl'                                   => WPInventoryInit::__(  'CYL' ),
                        'r_d_axis'                                  => WPInventoryInit::__(  'AXIS' ),
                        'r_d_add'                                   => WPInventoryInit::__(  'ADD' ),
                        'r_d_va'                                    => WPInventoryInit::__(  'VA' ),
                        'r_n_sph'                                   => WPInventoryInit::__(  'SPH' ),
                        'r_n_cyl'                                   => WPInventoryInit::__(  'CYL' ),
                        'r_n_axis'                                  => WPInventoryInit::__(  'AXIS' ),
                        'r_n_add'                                   => WPInventoryInit::__(  'ADD' ),
                        'r_n_va'                                    => WPInventoryInit::__(  'VA' ),                   
                        'l_d_sph'                                   => WPInventoryInit::__(  'SPH' ),
                        'l_d_cyl'                                   => WPInventoryInit::__(  'CYL' ),
                        'l_d_axis'                                  => WPInventoryInit::__(  'AXIS' ),
                        'l_d_add'                                   => WPInventoryInit::__(  'ADD' ),
                        'l_d_va'                                    => WPInventoryInit::__(  'VA' ),
                        'l_n_sph'                                   => WPInventoryInit::__(  'SPH' ),
                        'l_n_cyl'                                   => WPInventoryInit::__(  'CYL' ),
                        'l_n_axis'                                  => WPInventoryInit::__(  'AXIS' ),
                        'l_n_add'                                   => WPInventoryInit::__(  'ADD' ),
                        'l_n_va'                                    => WPInventoryInit::__(  'VA' ),
                        'r_lpd'                                     => WPInventoryInit::__(  'LPD' ),
                        'l_lpd'                                     => WPInventoryInit::__(  'LPD' ),
                        'o_desc'                                    => WPInventoryInit::__(  'Note' ),
                        'ref_by'                                    => WPInventoryInit::__(  'Ref By' ),                        
			'inventory_slug'                            => WPInventoryInit::__(  'Slug' ),
			'inventory_sort_order'                      => WPInventoryInit::__(  'Sort Order' ),	
			'category_id'                               => WPInventoryInit::__(  'Category ID' ),
			'user_id'                                   => WPInventoryInit::__(  'User ID' ),
			'inventory_date_added'                      => WPInventoryInit::__(  'Inventory Date Added' ),
			'inventory_date_updated'                    => WPInventoryInit::__(  'Inventory Date Updated' ),
			'gid'                                       => WPInventoryInit::__(  'gid' ),
                        'l_brand0'                                       => WPInventoryInit::__(  'Company' ),                    
                        'l_model0'                                       => WPInventoryInit::__(  'Model' ),
                        'l_rate0'                                       => WPInventoryInit::__(  'Rate' ),                    
                        'r0'                                        => WPInventoryInit::__(  'R' ),
                        'r_size0'                                   => WPInventoryInit::__(  'R Size' ),
                        'r_tint0'                                   => WPInventoryInit::__(  'R TINT' ),
                        'l0'                                        => WPInventoryInit::__(  'L' ),
                        'l_size0'                                       => WPInventoryInit::__(  'L Size' ),
                        'l_tint0'                                       => WPInventoryInit::__(  'L TINT' ),
                        'spec0'                                       => WPInventoryInit::__(  'SPECIFICATIONS' ),
                        'note0'                                       => WPInventoryInit::__(  'Note' ),
                        'l_sp0'                                       => WPInventoryInit::__(  'Lens1 Price' ),
                        'f_brand0'                                       => WPInventoryInit::__(  'Company' ),
                        'f_model0'                                       => WPInventoryInit::__(  'Model' ),
                        'f_size0'                                       => WPInventoryInit::__(  'Size' ),
                        'f_color0'                                       => WPInventoryInit::__(  'Color' ),
                        'f_sp0'                                       => WPInventoryInit::__(  'Frame1 Price' ),
                        'f_rate0'                                       => WPInventoryInit::__(  'Rate' ),
                        'l_brand1'                                       => WPInventoryInit::__(  'Company' ),                    
                        'l_model1'                                       => WPInventoryInit::__(  'Model' ),
                        'l_rate1'                                       => WPInventoryInit::__(  'Rate' ),                    
                        'r1'                                       => WPInventoryInit::__(  'R' ),
                        'r_size1'                                       => WPInventoryInit::__(  'R Size' ),
                        'r_tint1'                                       => WPInventoryInit::__(  'R TINT' ),
                        'l1'                                       => WPInventoryInit::__(  'L' ),
                        'l_size1'                                       => WPInventoryInit::__(  'L Size' ),
                        'l_tint1'                                       => WPInventoryInit::__(  'L TINT' ),
                        'spec1'                                       => WPInventoryInit::__(  'SPECIFICATIONS' ),
                        'note1'                                       => WPInventoryInit::__(  'Note' ),
                        'l_sp1'                                       => WPInventoryInit::__(  'Lens2 Price' ),
                        'f_brand1'                                       => WPInventoryInit::__(  'Company' ),
                        'f_model1'                                       => WPInventoryInit::__(  'Model' ),
                        'l_model1'                                       => WPInventoryInit::__(  'Model' ),
                        'f_size1'                                       => WPInventoryInit::__(  'Size' ),
                        'f_color1'                                       => WPInventoryInit::__(  'Color' ),
                        'f_sp1'                                       => WPInventoryInit::__(  'Frame2 Price' ),
                        'f_rate1'                                       => WPInventoryInit::__(  'Rate' ),
                        'l_brand2'                                       => WPInventoryInit::__(  'Company' ),                    
                        'l_model2'                                       => WPInventoryInit::__(  'Model' ),
                        'l_rate2'                                       => WPInventoryInit::__(  'Rate' ),                    
                        'r2'                                       => WPInventoryInit::__(  'R' ),
                        'r_size2'                                       => WPInventoryInit::__(  'R Size' ),
                        'r_tint2'                                       => WPInventoryInit::__(  'R TINT' ),
                        'l2'                                       => WPInventoryInit::__(  'L' ),
                        'l_size2'                                       => WPInventoryInit::__(  'L Size' ),
                        'l_tint2'                                       => WPInventoryInit::__(  'L TINT' ),
                        'spec2'                                       => WPInventoryInit::__(  'SPECIFICATIONS' ),
                        'note2'                                       => WPInventoryInit::__(  'Note' ),
                        'l_sp2'                                       => WPInventoryInit::__(  'Lens3 Price' ),
                        'f_brand2'                                       => WPInventoryInit::__(  'Company' ),
                        'f_model2'                                       => WPInventoryInit::__(  'Model' ),
                        'l_model2'                                       => WPInventoryInit::__(  'Model' ),
                        'f_size2'                                       => WPInventoryInit::__(  'Size' ),
                        'f_color2'                                       => WPInventoryInit::__(  'Color' ),
                        'f_sp2'                                       => WPInventoryInit::__(  'Frame3 Price' ),
                        'f_rate2'                                       => WPInventoryInit::__(  'Rate' ),
                        'l_brand3'                                       => WPInventoryInit::__(  'Company' ),                    
                        'l_model3'                                       => WPInventoryInit::__(  'Model' ),
                        'l_rate3'                                       => WPInventoryInit::__(  'Rate' ),                    
                        'r3'                                       => WPInventoryInit::__(  'R' ),
                        'r_size3'                                       => WPInventoryInit::__(  'R Size' ),
                        'r_tint3'                                       => WPInventoryInit::__(  'R TINT' ),
                        'l3'                                       => WPInventoryInit::__(  'L' ),
                        'l_size3'                                       => WPInventoryInit::__(  'L Size' ),
                        'l_tint3'                                       => WPInventoryInit::__(  'L TINT' ),
                        'spec3'                                       => WPInventoryInit::__(  'SPECIFICATIONS' ),
                        'note3'                                       => WPInventoryInit::__(  'Note' ),
                        'l_sp3'                                       => WPInventoryInit::__(  'Lens4 Price' ),
                        'f_brand3'                                       => WPInventoryInit::__(  'Company' ),
                        'f_model3'                                       => WPInventoryInit::__(  'Model' ),
                        'l_model3'                                       => WPInventoryInit::__(  'Model' ),
                        'f_size3'                                       => WPInventoryInit::__(  'Size' ),
                        'f_color3'                                       => WPInventoryInit::__(  'Color' ),
                        'f_sp3'                                       => WPInventoryInit::__(  'Frame4 Price' ),
                        'f_rate3'                                       => WPInventoryInit::__(  'Rate' ),
                        'l_brand4'                                       => WPInventoryInit::__(  'Company' ),                    
                        'l_model4'                                       => WPInventoryInit::__(  'Model' ),
                        'l_rate4'                                       => WPInventoryInit::__(  'Rate' ),                    
                        'r4'                                       => WPInventoryInit::__(  'R' ),
                        'r_size4'                                       => WPInventoryInit::__(  'R Size' ),
                        'r_tint4'                                       => WPInventoryInit::__(  'R TINT' ),
                        'l4'                                       => WPInventoryInit::__(  'L' ),
                        'l_size4'                                       => WPInventoryInit::__(  'L Size' ),
                        'l_tint4'                                       => WPInventoryInit::__(  'L TINT' ),
                        'spec4'                                       => WPInventoryInit::__(  'SPECIFICATIONS' ),
                        'note4'                                       => WPInventoryInit::__(  'Note' ),
                        'l_sp4'                                       => WPInventoryInit::__(  'Lens5 Price' ),
                        'f_brand4'                                       => WPInventoryInit::__(  'Company' ),
                        'f_model4'                                       => WPInventoryInit::__(  'Model' ),
                        'l_model4'                                       => WPInventoryInit::__(  'Model' ),
                        'f_size4'                                       => WPInventoryInit::__(  'Size' ),
                        'f_color4'                                       => WPInventoryInit::__(  'Color' ),
                        'f_sp4'                                       => WPInventoryInit::__(  'Frame5 Price' ),
                        'f_rate4'                                       => WPInventoryInit::__(  'Rate' ),
                        'plstats'                                       => WPInventoryInit::__(  'P/L Stats' )

                    
                    
		);

		return apply_filters( 'wpim_default_labels', $defaults );
	}

	/**
	 * Returns the set of elements that must always be on
	 */
	public function always_on() {
		return array(
			'inventory_name',
			'inventory_number',
			'inventory_date_added',
			'inventory_date_updated',
			'inventory_sort_order'
		);
	}

	public function is_always_on( $field ) {
		return ( in_array( $field, $this->always_on() ) ) ? TRUE : FALSE;
	}

	public function save( $data, $used_data, $numeric_data ) {

		if ( ! self::$labels ) {
			self::load();
		}

		foreach ( $data AS $field => $label ) {
			$label                  = ( is_array( $label ) ) ? $label['label'] : $label;
			self::$labels[ $field ] = $label;
		}

		$this->wpdb->query( "DELETE FROM " . $this->label_table );

		$query = '';
		foreach ( self::$labels AS $field => $label ) {
			$is_used = ( ! empty( $used_data[ $field ] ) ) ? 1 : 0;
			$is_numeric = ( ! empty( $numeric_data[ $field ] ) ) ? 1 : 0;
			$query .= ( $query ) ? ',' : '';
			$query .= $this->wpdb->prepare( '(%s, %s, %d, %d)', $field, $label, $is_used, $is_numeric );
		}

		$this->wpdb->query( "INSERT INTO " . $this->label_table . " (label_field, label_label, is_used, is_numeric) VALUES " . $query );

		self::load();

		return ( ! $this->wpdb->last_error ) ? TRUE : FALSE;
	}
}
