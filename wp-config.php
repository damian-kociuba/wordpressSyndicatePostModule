<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
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
define('DB_NAME', 'SyndicateWordpressModule');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'polcode');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'hyr+0QQR$J+V%tdMDvBW+mI^I~;-O~NRk&e@s;^29|R4^p!P@S /lzx^1wb:JcIA');
define('SECURE_AUTH_KEY',  '2k0YxN)~Fd:k6%-c z-g@<1P00:5d,&`m=@bsL2aCe--KM/8g]rQd5PLk7rE +sz');
define('LOGGED_IN_KEY',    'Ph8CK9C(Qr#EH0$gR`3+!$&xAh$A$tgwz0X(PcM-L>^{GENHf@:i5TbZA#O&~q+.');
define('NONCE_KEY',        'n!~+nIlQ~,[-+H-W9gQ1#9aS3C2^+$&L`;kN<Brw~76|0RFb^Sfwo}m6l#EVWt1]');
define('AUTH_SALT',        '^+YR` %Yhb?*aqE[|SF}CAR;5r|f&u=2WEX|mX`b#}{Qz~/1{0wyl8Xj5!&4cU8Y');
define('SECURE_AUTH_SALT', 'B:f#Z<.XgQV]w8M*+%%B,~hgV?1Wyt_K#_Z(yX*eSOX8]^EE} 4<NA#cyfz/&{Z+');
define('LOGGED_IN_SALT',   '(]{7Gb{ij|s=iSY`2YabzG%;_ld08K/n  tHQx&32E:zY#7=t6QMw`jR]aXxI#SU');
define('NONCE_SALT',       'e*.h-=||k<(_pAmG#+}]!KjfuD=Ghg-XgbVbxu3L+! A|Z$H$sb<(AIo$^pIItg:');

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
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
// Disable display of errors and warnings 
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors',0);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
