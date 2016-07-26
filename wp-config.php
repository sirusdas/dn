<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'bitnami_wordpress');

/** MySQL database username */
define('DB_USER', 'bn_wordpress');

/** MySQL database password */
define('DB_PASSWORD', '77031929f1');

/** MySQL hostname */
define('DB_HOST', 'localhost:3306');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'a73f6c358a81f5d587dc3bd8e88f2661104b4ca85de7d72ec3c31eb19657ece3');
define('SECURE_AUTH_KEY',  '5a9f35832c75c441a995d1c9959ef5733eb85b1c15bbaeb7ccac87b4f85418d7');
define('LOGGED_IN_KEY',    '70eb6bc4b109bff9bf40b66a22a8f5d910e59d21dde53700d5f2bf2fc840822d');
define('NONCE_KEY',        '65a5608c9d214daca04edfa6e2dc6037b154a4300ff99b12b175b1e51a90f579');
define('AUTH_SALT',        'e5bc6ea502a5a7e6b49e7a37275011ef0d8456d8d28504833ba37110aef27ed3');
define('SECURE_AUTH_SALT', 'a3a42001d7da207e937f24e72781e185d0d57d9afc2063f1cfb5dd83fa1fbedc');
define('LOGGED_IN_SALT',   '5b2ece142b784f184b5be80c7d01d1823444957b0161139420732dcd30ae8bf7');
define('NONCE_SALT',       '39095b84f8106b9e2a3a3724500452a70941fb86bc0c554e95e3c80fb1922dce');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */
/**
 * The WP_SITEURL and WP_HOME options are configured to access from any hostname or IP address.
 * If you want to access only from an specific domain, you can modify them. For example:
 *  define('WP_HOME','http://example.com');
 *  define('WP_SITEURL','http://example.com');
 *
*/

define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/DevChakraNetra');
define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] . '/DevChakraNetra');


/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('WP_TEMP_DIR', 'C:/xampp/apps/wordpress/tmp');

