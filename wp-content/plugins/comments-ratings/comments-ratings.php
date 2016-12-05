<?php
/*
* @package   Comments Ratings
* @author    PixelGrade <contact@pixelgrade.com>
* @license   GPL-2.0+
* @link      https://pixelgrade.com
* @copyright 2015 PixelGrade
*
* @wordpress-plugin
Plugin Name: Comments Ratings
Plugin URI:  https://wordpress.org/plugins/comments-ratings/
Description: Easily transform your comments into reviews.
Version: 1.1.5
Author: PixelGrade
Author URI: https://pixelgrade.com
Author Email: contact@pixelgrade.com
Text Domain: comments-ratings
License:     GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Domain Path: /lang
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// ensure EXT is defined
if ( ! defined('EXT')) {
	define('EXT', '.php');
}

require 'core/bootstrap'.EXT;

$config = include 'plugin-config'.EXT;

// set textdomain
load_plugin_textdomain( 'comments-ratings', false, basename( dirname( __FILE__ ) ) . '/languages/' );

// Ensure Test Data
// ----------------

$defaults = include 'plugin-defaults'.EXT;

$current_data = get_option($config['settings-key']);

if ($current_data === false) {
	add_option($config['settings-key'], $defaults);
}
else if (count(array_diff_key($defaults, $current_data)) != 0) {
	$plugindata = array_merge($defaults, $current_data);
	update_option($config['settings-key'], $plugindata);
}
# else: data is available; do nothing

// Load Callbacks
// --------------

$basepath = dirname(__FILE__).DIRECTORY_SEPARATOR;
$callbackpath = $basepath.'callbacks'.DIRECTORY_SEPARATOR;
pixreviews::require_all($callbackpath);

require_once( plugin_dir_path( __FILE__ ) . 'class-pixreviews.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'PixReviewsPlugin', 'activate' ) );
//register_deactivation_hook( __FILE__, array( 'pixreviewsPlugin', 'deactivate' ) );

function pixreviews_init_plugin() {
	global $pixreviews_plugin;
	$pixreviews_plugin = PixReviewsPlugin::get_instance();
}
add_action( 'after_setup_theme', 'pixreviews_init_plugin' );