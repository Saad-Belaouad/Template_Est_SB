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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'test_word' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'v8F3l:JkC6se=F1n*FN{7k44 <5/ (@G(G=CmQcPZ[{1TAg]AkXTCX<%n+ )|650' );
define( 'SECURE_AUTH_KEY',  ';jO{czs@n2:bhXsDb~Ma?7fGv= w+N,GSr7NNX+`uBNURHbB+#lT<r&OIuahtEDM' );
define( 'LOGGED_IN_KEY',    'MgWwk~5d*m@+mfad&hQRnfPeId{2O<1&c$5C>I8gp~|yTtVi.fPEB<4R^xu[>peW' );
define( 'NONCE_KEY',        'lV<<g1b@e>id5z_yE?0!TDu8D/C}tL )bJrx;wAP4.%p-KN_HfDT;77gtjRqM|AF' );
define( 'AUTH_SALT',        '$TM28x0(&4Ey.9A.(&X5/pt~>rgzwK.V |9N_(yuLX-[N~*tbUpDFI(E5Lu?&FvE' );
define( 'SECURE_AUTH_SALT', 'rB{d*5Z~]H=[&yOpN=gQrH,sjRi5/F;{TLT$oLu#_Q`-RiF:&y9fs|1U!PmY3vDO' );
define( 'LOGGED_IN_SALT',   '=3z>sVgDY4-itjLuww>u=d&V5@]RGt)<%9fr*;WMO+%wi,N#`aY0D1y|*nn6p^l0' );
define( 'NONCE_SALT',       'cojr_UMOWC>~Qa6Z3RV/p.p#sv/3Hrm0(C!W^1N49jCHGxjz8&^h@sz9}92_xhnw' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
