<?php

/*
Plugin Name: SyncImmo

Description: SyncImmo Plugin – is a great easy-to-use and easy-to-manage  , providers who think about their customers.
Version: 1.0
Author: hamza rebai

*/

/**
 *
 */
function SyncImmo_admin_menu(){

    global $current_user;
    $services       = __( 'Sync immo calander');
    if ( $current_user->has_cap( 'administrator' )){
        add_menu_page( 'Sync Immo calander', 'Sync immo', 'read', 'calanderImmo', 'syncimmo_cal',
            plugins_url( 'resources/images/next.png', __FILE__ ), '' );
    }
}
add_action('admin_menu','SyncImmo_admin_menu');
/**
 *
 */
function syncimmo_cal()
{

?>
<h1>
</h1>

    <?php

}







include( plugin_dir_path( __FILE__ ) . 'frontend/Ical.php');





?>