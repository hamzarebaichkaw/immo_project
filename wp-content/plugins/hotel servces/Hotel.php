<?php


/*
Plugin Name: hotel_service 

Description: hotel services Plugin – is a great easy-to-use and easy-to-manage  service providers who think about their customers. The plugin supports a wide range of services provided by business and individuals who offer reservations through websites. Set up any reservation quickly.
Version: 1.0
Author: Dot2com

*/
/*
add_action('admin_menu','services_admin_actions');
function services_admin_actions(){
    add_options_page('Services hotel','Services hotel','manage_options',_FILE_,'hotel');
}
*/
include"ADMIN_EDIT_SERVICES.php";

  function  admin_services_hotel(){
 global $current_user;
  $services       = __( 'hotel Services');
   if ( $current_user->has_cap( 'administrator' )){
   	 add_menu_page( 'hotel services', 'hotel services', 'read', 'services_backend', 'services_admin_hotel_forms',
                    plugins_url( 'resources/images/next.png', __FILE__ ), '' );
   	    }
}
add_action('admin_menu','admin_services_hotel');


function services_admin_hotel_forms(){
  global $pippin_load_css;
 
        // set this to true so the CSS is loaded
        $pippin_load_css = true;
 

global $wpdb;


?>






<div><h2>LES SERVICES DISPONIBLE </h2> </div>    

<table>
<tbody>
<tr>

<?php        $host_services_drafts = $wpdb->get_results("select Titre,info,img_services,id from wpio_services_global");
foreach($host_services_drafts as $host_servces_draft)
      {

switch ($host_servces_draft->id) {
	case 2:
		
//if ($_POST[''])

		?>  

<form method="post" action="http://localhost:8082/wordpress_project/wp-admin/admin.php?page=services_backendss" >
<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
		
		<input type="submit" class="btn_val" name="editservicewifi"  value="Editer">
	
		

</div>

	
</td>






		<?php 
		break;
		case 3:
		?>  


<td>

	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val"  name="editservicerepas" value="Editer">
</div>

	
</td>

</tr>




		<?php 
		break;
	case 4:
		?>  

<tr>
<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val" name="editservice_Transfert_Aéroport" value="Editer">
</div>

	
</td>






		<?php 
		break;
			case 5:
		?>  


<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val" name="editservice_menage" value="Editer">
</div>

	
</td>

</tr>




		<?php 
		break;
			case 6:
		?>  

<tr>
<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val" name="editservice_Smartphone_local" value="Editer">
</div>

	
</td>






		<?php 
		break;
			case 7:
		?>  


<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val" name="editservice_Bons_plans" value="Editer">
</div>

	
</td>

</tr>




		<?php 
		break;

			case 8:
		?>  

<tr>
<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val" name="editservice_Articles_Toilettes" value="Editer">
</div>

	
</td>






		<?php 
		break;
			case 9:
		?>  


<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val" name="editservice_Blanchisserie_Nettoyage" value="Editer">
</div>

	
</td>

</tr>




		<?php 
		break;
			case 10:
		?>  

<tr>
<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val" name="editservice_Adaptateur" value="Editer">
</div>

	
</td>






		<?php 
		break;
			case 11:
		?>  


<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val" name="editservice_Chargeur_Smartphone" value="Editer">
</div>

	
</td>

</tr>




		<?php 
		break;
			case 12:
		?>  

<tr>
<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val" name="editservice_Machine_Nespresso" value="Editer">
</div>

	
</td>






		<?php 
		break;
			case 13:
		?>  


<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val" name="editservice_Hôtesse_Dédiée" value="Editer">
</div>

	
</td>

</tr>




		<?php 
		break;
			case 14:
		?>  

<tr>
<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val" name="editservice_Carte_RestoPass" value="Editer">
</div>

	
</td>






		<?php 
		break;
			case 15:
		?>  


<td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services ;?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo $host_servces_draft->Titre ;   ?></h3> 

<p>  <?php echo $host_servces_draft->info ;?></p>



		</div>
<input type="submit" class="btn_val" name="editservice_Literie_hôtel" value="Editer">
</div>

	
</td>

</tr>




		<?php 
		break;
	default:
		echo "erreur inconu !";
		break;
}

       ?>



<?php	
}
?>
</tr>
</tbody>
</table>
</form>
<?php
}
/*
function services_register_css() {
    wp_register_style('pippin-form-css', plugin_dir_url( __FILE__ ) . '/resources/css/services_hotel_admin.css');
}
add_action('init', 'services_register_css');*/
// load our form for end css
function admin_css() {

$admin_handle = 'admin_css';
$admin_stylesheet = plugin_dir_url( __FILE__ ) . '/resources/css/services_hotel_admin.css';

wp_enqueue_style( $admin_handle, $admin_stylesheet );
}
add_action('admin_print_styles', 'admin_css');
// load our form for frontend css
/*
function gestion_services_print_css() {
    global $pippin_load_css;
 
    // this variable is set to TRUE if the short code is used on a page/post
    if ( ! $pippin_load_css )
        return; // this means that neither short code is present, so we get out of here
 
    wp_print_styles('pippin-form-css');
}
add_action('wp_footer', 'gestion_services_print_css');
*/




include( plugin_dir_path( __FILE__ ) . 'frontendhost.php');
include( plugin_dir_path( __FILE__ ) . 'Panier_repas.php');
include( plugin_dir_path( __FILE__ ) . 'frontendhost_edit.php');
include( plugin_dir_path( __FILE__ ) . 'BookerOauth.php');
?>

  
