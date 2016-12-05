<?php
global $jal_db_version;
$jal_db_version = '1.0';

function jal_install() {
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'services_global';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		dure_debut varchar(55) DEFAULT '' NOT NULL,
		dure_fin varchar(55) DEFAULT '' NOT NULL,
		dure varchar(55) DEFAULT '' NOT NULL,
		titre tinytext NOT NULL,
		type_services tinytext NOT NULL,
		color  text  DEFAULT '' NOT NULL,
		id_host mediumint(9) NOT NULL,
		VISIBLITY varchar(55) DEFAULT '' NOT NULL,
		capacite int(50) ,
		Category varchar(55) DEFAULT '' NOT NULL,
		fournisseur varchar(55) DEFAULT '' NOT NULL,
		info text NOT NULL,
		img_services varchar(55) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}
function categorie_install() {
	global $wpdb;
	global $categorie_db_version;

	$table_name = $wpdb->prefix . 'categorie_global';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		dure_debut datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		dure_fin datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name_categorie tinytext NOT NULL,
		
		info text NOT NULL,
		img_categorie varchar(55) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'categorie_db_version', $categorie_db_version );
}

function jal_install_data() {
	global $wpdb;
	
	$welcome_name = 'wifi';
	$welcome_text = 'Cette services permeter vous de connecté vaec 50 MB ! ';
	
	$table_name = $wpdb->prefix . 'services_global';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'dure_debut' => current_time( 'mysql' ), 
			'name_services' => $welcome_name, 
			'info' => $welcome_text, 
		) 
	);
}
function add_new_service() {
    global $wpdb;
     
        $titre     = $_POST["title"];  
        $visibility     = $_POST["visibility"];
        $prix_services = $_POST["visibility"];

        $dure_debut = $_POST["visibility"];
        $dure_fin = $_POST["visibility"];
        $type_services = $_POST["visibility"];
        $info = $_POST["visibility"];
        $fournisseur = $_POST["visibility"];
        $Category = $_POST["visibility"];
        $DURE = $_POST["visibility"];
        $capacite = $_POST["visibility"];



       
        $table_name = $wpdb->prefix . 'services_global';
 
      $wpdb->insert( 
        $table_name, 
        array( 
        	
            'Titre' =>  $titre ,
            'prix_services' => 50,
            'dure_debut' => $titre ,
            'dure_fin' => $titre ,
            'type_services' => $titre ,
            'info' => $titre ,
            'fournisseur' => $titre ,
            'Category' => $titre ,
            'VISIBLITY' => $titre ,
            'DURE' => $titre ,
            'capacite' => 30
        ) 
    );   
}