<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'epasal' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '~:IHuKQcc%*ojam}sZZJqGqLI=yW%6B8mJ2Dx^4agJ1p4M`fiL1b./t0c+L(9!4(' );
define( 'SECURE_AUTH_KEY',  'DNTih>R-sArz?Fiw(McDd/*;DlX{1vwI=mv<dbyW]{GeSc-v[w#lB],m/?+TThq<' );
define( 'LOGGED_IN_KEY',    'eig9HlBM0mggYeYtm95)8>FunhZfyW*/o3-f?[JQzXzb_RoZ!l2Y0_O:k*XF|A&h' );
define( 'NONCE_KEY',        'il|c;,J<tB2,zz:t(u6zHwQyGZP.&.*sG2[|HMjF0!TusPs &R2*4M=@3zi+iEwt' );
define( 'AUTH_SALT',        'IN[~ 002=$VH<XH_`xM38^-YxTyymYJ|oe`^mlSGPLMIgW@by,w6V0]^I6_dd)9q' );
define( 'SECURE_AUTH_SALT', ';myom*&lZEWE3@F?L~&{1S4u&j&#W=)6!Iil<|(kt]LX04POi~rae<@`gX;e<}):' );
define( 'LOGGED_IN_SALT',   '}orn{%EXT$`PqwJuC:G%Wy|_c]Er3fVW4,N-kA!gY=H[&y@G+OEDU B.[#W}_$%W' );
define( 'NONCE_SALT',       'WR8wyx>{cq5DOq^/)ktDl.R**Mn]{}E@w85mV`eaXcm}:/Q0&%i?^Rqd@ca8LXqi' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
