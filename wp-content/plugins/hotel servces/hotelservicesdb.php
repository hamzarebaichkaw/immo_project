<?php

/**
* 

*/




function updating_services_wifi()
{
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_wifi"];
if(isset($prix_service_wifi))	
{	$table_name ="wpio_service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'prix' => $prix_service_wifi, 
			
			
		) ,
		 array('id_services' =>2,
		 	    'id_booker'=>$id ,


		  )
	);
}
} 
add_action( 'plugins_loaded', 'updating_services_wifi' );
function updating_services_Paniers_Repas(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_panier"];
if(isset($prix_service_wifi))	
{	$table_name ="wpio_service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'prix' => $prix_service_wifi, 
			
			
		) ,
		 array('id_services' =>3,
		 	    'id_booker'=>$id ,


		  )
	);}
} 
add_action( 'plugins_loaded', 'updating_services_Paniers_Repas' );//prix_Transfert
function updating_services_Transfert_Aéroport(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_Transfert"];
if(isset($prix_service_wifi))	
{	$table_name ="wpio_service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			'prix' => $prix_service_wifi, 	
		) ,
		 array('id_services' =>4,
		 	    'id_booker'=>$id ,
		  )
	);}
}
add_action( 'plugins_loaded', 'updating_services_Transfert_Aéroport' );

function updating_services_ménage(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_menage"];
if(isset($prix_service_wifi))	
{	$table_name ="wpio_service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			'prix' => $prix_service_wifi, 
		) ,
		 array('id_services' =>5,
		 	    'id_booker'=>$id ,
		  )
	);}
} 
add_action( 'plugins_loaded', 'updating_services_ménage' );

function updating_services_Smartphone_local(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_Smartphone"];
if(isset($prix_service_wifi))	
{	$table_name ="service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'prix' => $prix_service_wifi, 
	) ,
		 array('id_services' =>6,
		 	    'id_booker'=>$id ,  )
	);
}
} 
add_action( 'plugins_loaded', 'updating_services_Smartphone_local' );

function updating_services_Bons_plans(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_Bons_plans"];
if(isset($prix_service_wifi))	
{	$table_name ="wpio_service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'prix' => $prix_service_wifi, 
			
			
		) ,
		 array('id_services' =>7,
		 	    'id_booker'=>$id ,


		  )
	);
}
} 
add_action( 'plugins_loaded', 'updating_services_Bons_plans' );

function updating_services_Articles_Toilettes(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_Articles_Toilettes"];
if(isset($prix_service_wifi))	
{	$table_name ="wpio_service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'prix' => $prix_service_wifi, 
			
			
		) ,
		 array('id_services' =>8,
		 	    'id_booker'=>$id ,


		  )
	);
}
} 
add_action( 'plugins_loaded', 'updating_services_Articles_Toilettes' );

function updating_services_Blanchisserie_Nettoyage(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_Blanchisserie_Nettoyage"];
if(isset($prix_service_wifi))	
{	$table_name ="wpio_service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'prix' => $prix_service_wifi, 
			
			
		) ,
		 array('id_services' =>9,
		 	    'id_booker'=>$id ,


		  )
	);
}
} 

add_action( 'plugins_loaded', 'updating_services_Blanchisserie_Nettoyage' );
//Adaptateur
function updating_services_Adaptateur(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_Adaptateur"];
if(isset($prix_service_wifi))	
{	$table_name ="wpio_service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'prix' => $prix_service_wifi, 
			
			
		) ,
		 array('id_services' =>10,
		 	    'id_booker'=>$id ,


		  )
	);
}
} 

add_action( 'plugins_loaded', 'updating_services_Adaptateur' );
//Chargeur_Smartphone
function updating_services_Chargeur_Smartphone(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_Chargeur_Smartphone"];
if(isset($prix_service_wifi))	
{	$table_name ="wpio_service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'prix' => $prix_service_wifi, 
			
			
		) ,
		 array('id_services' =>11,
		 	    'id_booker'=>$id ,


		  )
	);
}
} 

add_action( 'plugins_loaded', 'updating_services_Chargeur_Smartphone' );
//Machine_Nespresso
function updating_services_Machine_Nespresso(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_Machine_Nespresso"];
if(isset($prix_service_wifi))	
{	$table_name ="wpio_service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'prix' => $prix_service_wifi, 
			
			
		) ,
		 array('id_services' =>12,
		 	    'id_booker'=>$id ,


		  )
	);
}
} 

add_action( 'plugins_loaded', 'updating_services_Machine_Nespresso' );
//Hôtesse Dédiée
function updating_services_Hôtesse_Dédiée(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_Hôtesse_Dédiée"];
if(isset($prix_service_wifi))	
{	$table_name ="wpio_service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'prix' => $prix_service_wifi, 
			
			
		) ,
		 array('id_services' =>13,
		 	    'id_booker'=>$id ,


		  )
	);
}
} 

add_action( 'plugins_loaded', 'updating_services_Hôtesse_Dédiée' );
//Carte_RestoPass
function updating_services_Carte_RestoPass(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_Carte_RestoPass"];
if(isset($prix_service_wifi))	
{	$table_name ="wpio_service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'prix' => $prix_service_wifi, 
			
			
		) ,
		 array('id_services' =>14,
		 	    'id_booker'=>$id ,


		  )
	);
}
} 

add_action( 'plugins_loaded', 'updating_services_Carte_RestoPass' );
//Literie_hôtel
function updating_services_Literie_hôtel(){
global $wpdb;
$id=get_current_user_id();

$prix_service_wifi=$_POST["prix_Literie_hôtel"];
if(isset($prix_service_wifi))	
{	$table_name ="service_booker";
	
	$wpdb->update( 
		$table_name, 
		array( 
			
			'prix' => $prix_service_wifi, 
			
			
		) ,
		 array('id_services' =>15,
		 	    'id_booker'=>$id ,


		  )
	);
}
} 

add_action( 'plugins_loaded', 'updating_services_Literie_hôtel' );

?>