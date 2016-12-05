<?php
function updating_services_e_admin()
{
global $wpdb;


$titre=$_POST["titre"];
if(isset($titre))	
{	$table_name ="wp_services_global";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'Titre' => 'wifi', 
			
			
		) ,
		 array('id_services' =>2)
	);
}
}
echo $_POST["titre"]; 
add_action( 'plugins_loaded', 'updating_services_e_admin' );
?>