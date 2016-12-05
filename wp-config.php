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
define('DB_NAME', 'immo_project');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         ']q.j|F%LvxE/rKau7%raSxf;qI@7P=O/?OdqI/@XD,#C16rT<&[DCmwnok*C=B t');
define('SECURE_AUTH_KEY',  '-lBn:Ck4XmRY9jyp5Zi81SskB441|ibf !OU,TV p1pSD^-b5q{Bid+|aodLx[BK');
define('LOGGED_IN_KEY',    ';sJ4quYZ~EfO%}YLBh6z4.mE yd7DqY#*ed:9B/<lqfE`pcWj91Ynja5N#t&lwO{');
define('NONCE_KEY',        'O_F+iB0!MLG>a1yMT{sT~2gNuZPCg:YwB-[7D|F:gPNp!=mQI9Nu3Pp_/j>?M29,');
define('AUTH_SALT',        'qwb!z56n(*@o#m>u[d|rjf|R@t=VZ%?:X_i;ltZ`Nzrz%ze.3p<ogC(UF&40s5gy');
define('SECURE_AUTH_SALT', 'p8 amhiOhns~<8=wJYy/Z`,JF},1z,ctsZR@]4m}k@6y4EE]~.tO!xum!5/@-]RC');
define('LOGGED_IN_SALT',   'Q#PCmc _J?aQKOU.>WfE2!wt+,|h%&%A-)|hC*}wf+4jj|%7dM0,52_7-IK<K@56');
define('NONCE_SALT',       '*-EP=yK|aT$D=^|y@N)u>]Loe&{Q/:wQ]iAl0V(e:Eu~NjCU@>B&lK&O]7e|/9)R');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpio_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
