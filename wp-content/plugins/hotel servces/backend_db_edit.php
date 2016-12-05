<?php

	
function updating_services_wifi(){
global $wpdb;
$titre_wifi ='WIFI gratuit';
$info_wifi=$_POST["info"];
$prix_service_wifi=$_POST["prix"];
if(isset($prix_service_wifi))	
{	$table_name = $wpdb->prefix . 'services_global';
	
	$wpdb->update( 
		$table_name, 
		array( 
			'VISIBLITY' => $_POST["visi"], 
			'prix_services' => $prix_service_wifi, 
			'info' => $info_wifi,
			
		) ,
		 array('id' =>2 )
	);
}
} 
add_action( 'plugins_loaded', 'updating_services_wifi' );


?>