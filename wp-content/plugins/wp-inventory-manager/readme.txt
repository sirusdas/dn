=== WP Inventory Manager ===
Contributors: wpinventory.com
Donate link: http://www.wpinventory.com/contribute/
Tags: inventory, shopping cart, inventory manager
Requires at least: 3.5.0
Tested up to: 4.3
Stable Tag: 1.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage products, equipment, and more in your WordPress website.  It’s like a shopping cart, without the cart!

== Description ==

Perfect for tracking items that you would like to display or sell on your WordPress site.

* Supports multiple categories
* Fully customizable labels
* Templating system makes customization easy
* Set permissions for who can add or edit items
* Uses separate database tables for faster database access
* Developer friendly with hooks, filters, and utility functions


= Tested on =
* Mac Firefox 	:)
* Mac Safari 	:)
* Mac Chrome	:)
* PC Safari 	:)
* PC Chrome	    :)
* PC Firefox	:)
* iPhone Safari :)
* iPad Safari 	:)
* PC ie7		:S

= Website =
http://www.wpinventory.com/

= Documentation =
* [Getting Started](http://www.wpinventory.com/documentation/)
* [Support](http://www.wpinventory.com/support/)

= Bug Submission and Forum Support =
http://www.wpinventory.com/support/

= Please Vote and Enjoy =
Your votes really make a difference! Thanks.


== Installation ==

1. Upload ‘wpinventory’ to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on the new menu item “WP Inventory” and follow the fast-start directions.


== Frequently Asked Questions ==

= Q. I have a question =
A. You’re going to want to visit our website for documentation and support: http://www.wpinventory.com/documentation/


== Screenshots ==

1. Inventory Manager menu in dashboard.

2. Inventory Manager display on front-end.

3. Customization of Inventory Labels.

4. Settings Screen.

5. Display configuration screen.


== Changelog ==

= 1.2.0 =
* 09/07/2015 - Update to WP 4.3 preferred Widget Constructor method
* 09/14/2015 - Add Reserve send Confirmation functionality

= 1.1.9 =
* 08/11/2015 - Change from category to label in category dropdown on front-end
* 08/21/2015 - Adjust language loading path

= 1.1.8 =
* 07/16/2015 - Fix bug with currency display
             - Cause reserve submit to jump down to reserve form / notice
             - Cause sorting by date to list most recent at top

= 1.1.7 =
* 06/29/2015 - Fix bug with widget

= 1.1.6 =
* 06/20/2015 - Improve licensing system.
             - Add numeric sort setting for fields
             - Fix bug with rebuilding images
             - Improve reserve e-mails

= 1.1.5 =
* 06/10/2015 - Fix bug in loop templates attempting to load single-shortcode instead of single-item

= 1.1.4 =
* 05/12/2015 - Fix date formatting for updated / added date
             - Added new filter:  apply_filters('wpim_get_config', $setting, $field);
             - usage: add_filter('wpim_get_config', 10, 2); // Two parameters, setting & field
             - added new filter: return apply_filters( 'wpim_check_permission', TRUE, $type, $inventory_item );
             - usage: add_filter('wpim_check_permission', 10, 3); // Three parameters, value, $type (edit_item or save_item), $inventory_item
             - added new filter: $args = apply_filters('wpim_query_item_args', $args);
             - usage: add_filter('wpim_query_item_args'); // The args are the only parameter

= 1.1.3 =
* 05/04/2015 - Updated code to prevent strict notices
             - Improvements to placeholder loading in various conditions

= 1.1.2 =
* 04/18/2015 - Make improvement for license validation
             - Fix issue with placeholder image not loading in admin

= 1.1.0 =
* 03/30/2015 - Fix issue where users could view (not edit) items without permissions in admin
             - Fix issue where permalink / slug field would not show in edit item screen
             - Add support for placeholder image
             - Improve reserve form extensibility and data capture
             - Add hooks / actions in various places
             - Build out "user_can_edit" public function
             - Improve comments in code
= 1.0.9 =
* 02/13/2015 - Fix minor bug with sort-by dropdown including hidden fields

= 1.0.8 =
* 02/04/2015 - Convert tables to utf8

= 1.0.7 =
* 01/26/2015 - License activation debugging output

= 1.0.6 =
* 01/26/2015 - License activation debugging output

= 1.0.5 =
* 01/19/2015 - License activation improvement

= 1.0.4 =
* 12/05/2014 - Fix bug with special chars on form inputs

= 1.0.3	=
* 10/27/2014 - Improvements to license system

= 1.0.2	=
* 11/26/2014 - Minor bug fixes - media not appearing on front-end, improvements to css classes

= 1.0.1	=
* 11/18/2014 - Implement automatic updates

= 1.0.0	=
* 11/18/2014 - Implement license system

= 0.7.9	=
* 11/11/2014 - Enhancements to sort by category

= 0.7.8	=
* 10/08/2014 - Improvements to add-on system

= 0.7.7	=
* 09/29/2014 - Added shortcode atts: category_name, category_slug, user_id

= 0.7.6	=
* 09/25/2014 - Fix bug with permalinks setting not being honored

= 0.7.5	=
* 09/23/2014 - Fix bug with category name display, add wpim_use_currency_formats filter

= 0.7.4	=
* 09/17/2014 - Improved css classes, added class function wpinventory_label_class()

= 0.7.3	=
* 09/10/2014 - Improve css classes throughout front-end views

= 0.7.2	=
* 08/29/2014 - Bug fixes (edit category, error on certain views)

= 0.7.1 =
* Extend hooks for add-ons, improve internationalization.

= 0.6.3 =
* Bug Fixes

= 0.5.0 =
* WP Inventory

== Upgrade Notice ==

= 1.0.9 =
* 02/13/2014  - Fix minor bug with sort-by dropdown including hidden fields