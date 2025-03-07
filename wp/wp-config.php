<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_database' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'password' );

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
define( 'AUTH_KEY',         '#oFE0lYaI0RynC[bq05`q)MFa.d zh#Fd2Fr^&^aN||cgTT+2o[3B.-T,[S7@N%)' );
define( 'SECURE_AUTH_KEY',  '=;,i)<.^~)QvO2Rl4U-Q~GR6-56>Z0#.L_DfBcC6MnZdJ1@W}iC5CW?xjO:mVpx+' );
define( 'LOGGED_IN_KEY',    'k{.dD74s5FzxlBCkNMuex| &1Nx58^a[++]MDn)G50,5]EW.? 4%yxxRj[dFGE$5' );
define( 'NONCE_KEY',        'PpPxwmcA/G+!Lq?w#%314}%!~&9|6Z%!R!DF6^7_snv;!@.7x56$wdO!^-Q0_GIg' );
define( 'AUTH_SALT',        '#ig<NaD>xzH ^slo33K[wUT,l0?T&*PXFr)u5xA~iS&A~,B{I.J|$,2Ghps_u}u}' );
define( 'SECURE_AUTH_SALT', '4[zKu3/BdlGck;dDucGY7#}.!7P^Kt ,PxY-.re/Egt3C:d&@i/TX%+Y<)Y3v0j[' );
define( 'LOGGED_IN_SALT',   'fMq$2a>LL|!?Opve%A>->n8VLqe&AU,Yj;PS~e^X)ow$BKs84}Oxlt7;Kix[TPi^' );
define( 'NONCE_SALT',       '69?vV%Ykaj(el;5RVx%Aj7ylB;*zHF(`A!eisWG66?me~hw3.ixaIyv*=m[?z0C*' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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

define('FS_METHOD', 'direct');
