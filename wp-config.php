<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
define('WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME'] . '/wordpress');
define('WP_HOME',    'http://' . $_SERVER['SERVER_NAME']);
define('WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/wp-content');
define('WP_CONTENT_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp-content');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wplearning');

/** MySQL database username */
define('DB_USER', 'ediss');

/** MySQL database password */
define('DB_PASSWORD', 'kahva');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'I*.}fZ5z/|S<tVUKha!o]JRdMkGcLYYZx#9>Fq4d23&S:E)wjQbGBQT/#C/Yc.Qq');
define('SECURE_AUTH_KEY',  'N`b.*N9FzUv>~L-K)C2UFN~F<O.Qky2}rX~#4-3kSnYI0-89wN0LY$#^?Gw{!PeL');
define('LOGGED_IN_KEY',    '3kC;0--9j70h_cSE7~o?N/W)F|^(L$38&`x&HWBIRF;Dbfa5SaTEX7]yA8{ `6K;');
define('NONCE_KEY',        ',u5R+H]2rNAij zU(!CvtTm[tKB|&@N,L(M~cA@ %p;lkQ6WL1;@#}Qq2&WN5CCb');
define('AUTH_SALT',        '5b~5y>^}m&IDdyzDl[<Ly%<+Xu{Q*e~d~TY*Vp<l#-+f=u{,Vrzwa#v_VsxMU2M$');
define('SECURE_AUTH_SALT', 'WT?1;bn|lKDV>XJUw.>48@0n64*b7^3)!AG$v2DOb[AtCqfm+pt2$OjzjB?#$<X-');
define('LOGGED_IN_SALT',   'H}dM8b<eSZ6:hkS^ChOh?J &6x4ctgq*%2$[{ri=+lz<x6!kX5XNDv1~4[|cl_^5');
define('NONCE_SALT',       '+bJtfvA6a!<Kc>{&0YYt.uZ1+]Kph^R4q1*zeZ`e0rh_h{,I~XE>1zp{O7u!/|H}');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
