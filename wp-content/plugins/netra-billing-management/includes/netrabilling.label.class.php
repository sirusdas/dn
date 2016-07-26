<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NBMLabel extends NBMDB {

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
			$val = ( strcasecmp( $a['label'], $b['label'] ) < 0 ) ? - 1 : 1;

			return $val;
		}

		return ( $a['is_used'] < $b['is_used'] ) ? 1 : - 1;
	}

	public static function default_labels() {
		$defaults = array(
			'inventory_id'            => NETRABillingInit::__( 'Order No' ),                        
			'mobile_no'               => NETRABillingInit::__( 'Customer Mobile Nos' ),
			'customer_name'           => NETRABillingInit::__( 'Customer Name' ),
			'customer_address'        => NETRABillingInit::__( 'Customer Address' ),                    
			'billing_description'     => NETRABillingInit::__( 'Description' ),
			'frame_srno'              => NETRABillingInit::__( 'Frame Id' ),
			'frame_name'              => NETRABillingInit::__( 'Frame Name' ),
			'frame_qty'               => NETRABillingInit::__( 'Frame Qty' ),                    
			'lens1_srno'              => NETRABillingInit::__( 'Lens 1 Serial No' ),
			'lens1_name'              => NETRABillingInit::__( 'Lens1 Name' ),                    
			'lens2_srno'              => NETRABillingInit::__( 'Lens 2 Serial No' ),
			'lens2_name'              => NETRABillingInit::__( 'Lens2 Name' ),               
			'lens1_qty'               => NETRABillingInit::__( 'Lens1 Qty' ),
                        'lens2_qty'               => NETRABillingInit::__( 'Lens2 Qty' ),
			'r'                       => NETRABillingInit::__( 'R:' ),
			'o'                       => NETRABillingInit::__( 'O:' ),
			'm'                       => NETRABillingInit::__( 'M:' ),
			'ref_by'                  => NETRABillingInit::__( 'Ref By' ),                    
			'total'                   => NETRABillingInit::__( 'Total' ),
			'advance'                 => NETRABillingInit::__( 'Adv' ),
			'bal'                     => NETRABillingInit::__( 'Bal' ),
			'sph_dist'                => NETRABillingInit::__( 'SPH' ),
			'sph_near'                => NETRABillingInit::__( 'SPH' ),
                        'cyl_dist'                => NETRABillingInit::__( 'CYL' ),
                        'cyl_near'                => NETRABillingInit::__( 'CYL' ),
                        'axis_dist'               => NETRABillingInit::__( 'AXIS' ),
                        'axis_near'               => NETRABillingInit::__( 'AXIS' ),
                        'vn_dist'                 => NETRABillingInit::__( 'VN' ),
                        'vn_near'                 => NETRABillingInit::__( 'VN' ),
			'order_status'            => NETRABillingInit::__( 'Order Status' ),
			'inventory_slug'          => NETRABillingInit::__( 'Slug' ),
			'inventory_sort_order'    => NETRABillingInit::__( 'Sort Order' ),
			'inventory_date_added'    => NETRABillingInit::__( 'Date Added' ),
			'inventory_date_updated'  => NETRABillingInit::__( 'Date Updated' ),
			'user_id'                 => NETRABillingInit::__( 'User' ),
                        'category_id'             => NETRABillingInit::__( 'Category' ), 
		);

		return apply_filters( 'nbm_default_labels', $defaults );
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
