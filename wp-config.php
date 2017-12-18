<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

define('DB_NAME', 'nsamhaiu');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'w]|rQ8<2I;]tmF4=jGR~M&z!EcQFp%u3d8LWCq<pC:S%l=k/8_G(1w&tc*a=}{:>');
define('SECURE_AUTH_KEY',  '-OW7O4[8BN@1gwt&QdFrgd[DA?YtYXVk)BPnNA760m|/Dm(M.R|PFWORYd8A^hTF');
define('LOGGED_IN_KEY',    'VJ(33Y_/v?[RB+plv4dn*6^veWwxQ_5q;b;%~b|:V!GV/#yQ x0tR:M-vEVfwOc~');
define('NONCE_KEY',        'VUbo)>3|*xam9j^B#rK>uJGn&#_wuk`9K6.zB`3_|[/<-1)kU++Qups@:|Wdq!`U');
define('AUTH_SALT',        'GM26N$H?SBm?yq#! f)A_fO86o7iBWg]|[m5#2d?l#o=)wuRRd~a,x|/p(:eos:t');
define('SECURE_AUTH_SALT', 'qi{H6Sz>PdJ@L%2A)6~YUt^OogjXH1DU*&|x]oL|;b0`.b)@[j#|x2@}#uh.wO2U');
define('LOGGED_IN_SALT',   'H.|gDafVcDXmXw2OZ4]y/JF9P-`WnZs7vx*eB+[2WhT;s$zb];M*pbZ}`XpSMR&_');
define('NONCE_SALT',       '_57rJ%P1Yz I3oDT<E:`)qh~wT#Hjx&1h2S`gTFvlNXFhVso}r6YBSbn}j* BGO_');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'panic';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
