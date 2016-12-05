<?php


function host_services_wifi()
 {
global $wpdb;
$id=get_current_user_id();
$prix_service_wifi=$_POST["prixs"];

 $table_name = 'service_booker';
  $wpdb->update( 
    $table_name, 
    array( 
      'prix' => '44'    
    ) ,
     array(
      'id_services' => 2)
  );

} 
add_action('plugins_loded','host_services_wifi')

?>