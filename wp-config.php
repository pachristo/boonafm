<?php

define('WP_AUTO_UPDATE_CORE', 'minor');// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
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
// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'boonafm_db');
/** MySQL database username */
define( 'DB_USER', 'boonafm_user');
/** MySQL database password */
define( 'DB_PASSWORD', '34vn1QuLJsmy');
/** MySQL hostname */
define( 'DB_HOST', 'localhost' );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'Ld5QqZ0l1MKKYf!h~5[E==M8oDEi@QtEMWIKW|?)9J|iP#pgcAd8h!rgj_J@J|`+' );
define( 'SECURE_AUTH_KEY',   'ERUBG_Lo=_F@HhP)[K!Jt.bGdiC2?],^Pr*bK0?&%X4L;i7dlyGE<H.MlRR%4ABk' );
define( 'LOGGED_IN_KEY',     'k;[.,}E7A_S=Bb$`{7YTD(J ;qx7zY$2PI2?IAT%jxdIwCN%}7;$dGUCbIYr6Ho@' );
define( 'NONCE_KEY',         '_;U t@e/DCDhH`Jh^Jvr}n%8nmp.7v{~{LS1yMacFbVJ5,+q~R+<o{d>Zij]EuW!' );
define( 'AUTH_SALT',         '~W}~&*+cZLb=NnYAL:L3U{cp*60V5g|rG-Lxi{Co1_#YA(:^xRZcS`uU[U+L=[ZX' );
define( 'SECURE_AUTH_SALT',  'q8}?be/aA>J;yxCqsKhve^qFHGL2)@VXp)8^XY*3^-]3>VO#[%asV.P/_$Gk^Q9r' );
define( 'LOGGED_IN_SALT',    '0[xQ[!@/z,W5UO=AK.D^W09O#nJHN*@~kaCEKs3?TLsBuT}/r?8P?h.VT~Iv~zP0' );
define( 'NONCE_SALT',        'kCWMGs0fPD:NoUl1Q<LZb4?I:W^/(7b3bDa/V]epFW7fK#[8F%73Wo%y<RSr[DZR' );
define( 'WP_CACHE_KEY_SALT', 'qU.pY_^2a>:>C;9gvlCr535rP3W%^99$=RYH&oBL9*P3X_H?_,=v(:fK:56c=V/h' );
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
