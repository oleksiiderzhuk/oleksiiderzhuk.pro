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
define( 'DB_NAME', 'bodydbl' );

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
define( 'AUTH_KEY',         '0vnj<[S$B@t$Kt.%V.KTpE`b+^p7T5%+Nix0] NWO%TZ&U%3VxHiu^,Pp1;8b*q(' );
define( 'SECURE_AUTH_KEY',  '=Aa,q5u3u.mLH{[^9%~68-p7R`BeOW_/gXAIPBZ_E)t_#lir@&y0!,4<7z%t3%yb' );
define( 'LOGGED_IN_KEY',    '&u:Li }wY@zf>9]V$]h}?[b%HKnp+y5u,SXJ{_v+G#rp2l($wuU_5DeF21c4pQ<m' );
define( 'NONCE_KEY',        'z+^])e[,BY&+m*U&caPYQFaa_p/!+u5%`2X&89$Ai3Fpt`ZnGDX]Id&.UBb/aUkj' );
define( 'AUTH_SALT',        'TlinFvyadK2c=DuY&{V4*8j[.[*jgRHR)s#-p]TY%PGv5e#tK-Km4?~neSy98g6n' );
define( 'SECURE_AUTH_SALT', '!J67d*:13$MNS&2$FdOK4O]u?b7g3v|*gSl?giV2n3Wv=$WPwJsCGoqiOG7q.vLX' );
define( 'LOGGED_IN_SALT',   '3Ma0)ZE3m&U@wwwR_FX#($Su^|OdZ(Ddqbz8aQ*>x_A0dm @MnrKNpB{L3B{v;I+' );
define( 'NONCE_SALT',       'pwIcQTh]c15%k8u9&^G^xmno+Uo!>CaXRubem[AtsQVI$ZP@nhtOl lbm,z@L@K4' );

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
