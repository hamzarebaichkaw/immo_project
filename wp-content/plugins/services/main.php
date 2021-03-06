<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: services booking

Description: services booking Plugin – is a great easy-to-use and easy-to-manage  service providers who think about their customers. The plugin supports a wide range of services provided by business and individuals who offer reservations through websites. Set up any reservation quickly.
Version: 1.0
Author: hamza rebai

*/

if ( version_compare( PHP_VERSION, '5.3.7', '<' ) ) {
    function bookly_php_outdated()
    {
        add_action( is_network_admin() ? 'network_admin_notices' : 'admin_notices', create_function( '', 'echo \'<div class="updated"><h3>Bookly</h3><p>To install the plugin - <strong>PHP 5.3.7</strong> or higher is required.</p></div>\';' ) );
    }
    add_action( 'init', 'bookly_php_outdated' );
} else {
    include 'autoload.php';

    // Fix possible errors (appearing if "Nextgen Gallery" Plugin is installed) when Bookly is being updated.
    add_filter( 'http_request_args', create_function( '$args', '$args[\'reject_unsafe_urls\'] = false; return $args;' ) );

    call_user_func( array( '\BooklyLite\Lib\Plugin', 'run' ) );
    $app = is_admin() ? '\BooklyLite\Backend\Backend' : '\BooklyLite\Frontend\Frontend';
    new $app();
}