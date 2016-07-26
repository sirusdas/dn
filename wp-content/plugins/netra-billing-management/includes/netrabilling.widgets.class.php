<?php

// No direct access allowed.
if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * File for widget functionality
 * NETRABilling supports template overrides. 
 * @author Alpha Channel Group
 *
 */


class NETRABilling_Categories_Widget extends WP_Widget {
	function NETRABilling_Categories_Widget() {
		parent::__construct('NETRABilling_Categories_Widget', 'NETRA Billing Categories', array('description'=>'List Inventory categories, with link(s) to view inventory for each category'));
	}

	function widget($args, $instance) {
		extract($args);
		$page_id = ( ! empty($instance['page_id'])) ? $instance['page_id'] : NULL;

		echo $before_widget;
		if ($instance['title']) {
			echo $before_title . $instance['title'] . $after_title;
		}
		
		if ( ! $page_id) {
			echo '<!-- Page not set in widget.  Defaulting to current page / post -->';
			global $post;
			$page_id = $post->ID;		
		}
		
		$nbm_categories = new NBMCategory();
		$categories = $nbm_categories->get_all(array('order' => $instance['sort_order']));
		
		$list = ($instance['display_as'] != 'list') ? FALSE : TRUE;
		
		echo ($list) ? '<ol>' : '<select name="inventory_category_list" onchange="if (this.value) window.location.href=this.value"><option value="">' . NBMCore::__('Choose Category...') . '</option>';
		
		foreach($categories AS $category) {
			$category_link = $nbm_categories->get_category_permalink($page_id, $category->category_id, $category->category_name);
			if ($list) {
				echo '<li class="category_' . $category->category_id . ' category_' . $nbm_categories->get_class($category->category_name) . '">';
				echo '<a href="' . $category_link . '">' . $category->category_name . '</a>';
				echo '</li>';
			} else {
				echo '<option value="' . $category_link . '">' . $category->category_name . '</option>'; 
			}
		}
		
		echo ($list) ? '</ol>' : '</select>';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		foreach ($new_instance as $k=>$v) {
			$instance[$k] = $v;
		}

		return $instance;
	}

	function form($instance) {
		$default = array(
				'title'			=> NBMCore::__('Inventory Categories'),
				'page_id'		=> '',
				'sort_order'	=> '',
				'display_as'	=> 'list',
				'include_counts'=> '0'
		);
		$instance = wp_parse_args((array)$instance, $default);
		
		$display_as_select = NBMCore::dropdown_array($this->get_field_name('display_as'), $instance['display_as'], array('list'	=> NBMCore::__('List'), 'select'	=> NBMCore::__('Dropdown')));
		$sort_order_select = NBMCore::dropdown_array($this->get_field_name('sort_order'), $instance['sort_order'], array('sort_order'	=> NBMCore::__('Sort Order'), 'category_name'	=> NBMCore::__('Category Name')));
		
		echo '<p><label for="' . $this->get_field_name('title') . '">' . NBMCore::__('Widget Title') . '</label> <input type="text" class="widefat" name="' . $this->get_field_name('title') . '" value="' . $instance['title'] . '" /></p>';
		echo '<p><label for="' . $this->get_field_name('page_id') . '">' . NBMCore::__('Links to Page') . '</label> ' . wp_dropdown_pages('echo=0&name=' . $this->get_field_name('page_id') . '&selected=' . $instance['page_id'] . '&show_option_none=' . NBMCore::__('Select...')) . '</p>';
		echo '<p><label for="' . $this->get_field_name('display_as') . '">' . NBMCore::__('Display As') . '</label> ' . $display_as_select . '</p>';
		echo '<p><label for="' . $this->get_field_name('sort_order') . '">' . NBMCore::__('Sort Order') . '</label> ' . $sort_order_select . '</p>';
	}
}


class NETRABilling_Latest_Items_Widget extends WP_Widget {
	function NETRABilling_Latest_Items_Widget() {
		parent::__construct('NETRABilling_Latest_Items_Widget', 'NETRA Billing Latest Items', array('description'=>'List the latest items added to inventory.'));
	}

	function widget($args, $instance) {

		extract($args);
		$page_id = ( ! empty($instance['page_id'])) ? $instance['page_id'] : NULL;
		
		echo $before_widget;
		if ($instance['title']) {
			echo $before_title . $instance['title'] . $after_title;
		}

		if ( ! $page_id) {
			echo '<!-- Page not set in widget.  Defaulting to current page / post -->';
			global $post;
			$page_id = $post->ID;
		}
		
		$number = (int)$instance['number'];
		$number = max(1, min(10, $number));
		
		$args = array(
			'category_id'	=> $instance['category_id'],
			'page_size'		=> $number,
			'order'			=> 'inventory_date_added DESC'
		);
		
		$custom_loop = new NBMLoop();
		$custom_loop->set_single(TRUE);
		$custom_loop->load_items($args);
		netrabilling_set_loop($custom_loop);

		netrabilling_get_template_part('widget-latest-items-loop');
		
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		foreach ($new_instance as $k=>$v) {
			$instance[$k] = $v;
		}

		return $instance;
	}

	function form($instance) {
		$default = array(
				'title'			=> NBMCore::__('Latest Items'),
				'page_id'		=> '',
				'category_id'	=> '',
				'number'		=> '4'
		);
		$instance = wp_parse_args((array)$instance, $default);
		
		$NBMCategories = new NBMCategory();
		$categories = $NBMCategories->get_all(array('order' => 'sort_order'));
		
		$categories_array = array(''	=> NBMCore::__('Show All'));
		foreach($categories AS $cat) {
			$categories_array[$cat->category_id] = $cat->category_name;
		}
		
		$category_select = NBMCore::dropdown_array($this->get_field_name('category_id'), $instance['category_id'], $categories_array);
		
		echo '<p><label for="' . $this->get_field_name('title') . '">' . NBMCore::__('Widget Title') . '</label> <input type="text" class="widefat" name="' . $this->get_field_name('title') . '" value="' . $instance['title'] . '" /></p>';
		echo '<p><label for="' . $this->get_field_name('number') . '">' . NBMCore::__('Number of Items') . '</label> <input type="text" class="small-text" name="' . $this->get_field_name('number') . '" value="' . $instance['number'] . '" /></p>';
		echo '<p><label for="' . $this->get_field_name('page_id') . '">' . NBMCore::__('Links to Page') . '</label> ' . wp_dropdown_pages('echo=0&name=' . $this->get_field_name('page_id') . '&selected=' . $instance['page_id'] . '&show_option_none=' . NBMCore::__('Select...')) . '</p>';
		echo '<p><label for="' . $this->get_field_name('category_id') . '">' . NBMCore::__('Category') . '</label> ' . $category_select . '</p>';
	}
}