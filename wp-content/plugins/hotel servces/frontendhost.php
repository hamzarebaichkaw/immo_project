<?php
//include"base.php";
function host_services(){

 global $frontend_load_css;
 
        // set this to true so the CSS is loaded
        $frontend_load_css = true;
global $wpdb;
//if(isset($_POST['search_publish_posts']))
$id=get_current_user_id();
     
$host_services_drafts = $wpdb->get_results("select f.Titre,f.info,f.img_services,b.prix ,f.id from wpio_services_global f inner join 
  wpio_service_booker b on(f.id=b.id_services and b.id_booker=$id )  ");

?>
<div class="container" >  
<?php
foreach($host_services_drafts as $host_servces_draft)
      {
?>


<table>
<tbody>
<tr><td>
	<div class="col-md-6">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<DIV class="PARGRAPHE_SERVICES_info">
<p ><?php echo" $host_servces_draft->info"; ?></p>
</DIV>

		
</div></td>
<td>
<?php 



switch($host_servces_draft->id)
{
 case 2:

 echo $host_servces_draft->prix.'£ <br>' ;
 ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_wifi' ); ?>">             
 <label>prix <input type="number" name="prix_wifi" class="frontend_prix_wifi">  </label>

<input type="submit" class="enregister_prix_services" name="enregister" value="enregister">
 </form>
<?php
  break;

 case 3:

 echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_Paniers_Repas' ); ?>">             
 <label>prix <input type="number" name="prix_panier">  </label>

<input type="submit"  class="enregister_prix_services" name="enregister" value="enregister">
 </form>
<?php

 break;

 case 4:
 
  echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_Transfert_Aéroport' ); ?>">             
 <label>prix <input type="number" name="prix_Transfert">  </label>

<input type="submit" class="enregister_prix_services" name="enregister" value="enregister">
 </form>
<?php
  break;

 case 5:

  echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_ménage' ); ?>">             
 <label>prix <input type="number" name="prix_menage"  class="frontend_prix_menage">  </label>

<input type="submit" class="enregister_prix_services"  name="enregister" value="enregister"> </form> <?php   
  break;
  case 6:
  echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_Smartphone_local' ); ?>">             
 <label>prix <input type="number" name="prix_Smartphone">  </label>

<input type="submit" class="enregister_prix_services" name="enregister" value="enregister"> </form> <?php 
 break;
  case 7:
 echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_Bons_plans' ); ?>">             
 <label>prix <input type="number" name="prix_Bons_plans">  </label>

<input type="submit" name="enregister" class="enregister_prix_services" value="enregister"> </form> <?php  
  break;
  case 8:
  echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_Articles_Toilettes' ); ?>">             
 <label>prix <input type="number" name="prix_Articles_Toilettes">  </label>

<input type="submit" name="enregister" class="enregister_prix_services" value="enregister"> </form> <?php 
  break;
  case 9:
  echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_Blanchisserie_Nettoyage' ); ?>">             
 <label>prix <input type="number" name="prix_Blanchisserie_Nettoyage"> </label>

<input type="submit" name="enregister" class="enregister_prix_services" value="enregister"> </form>   <?php
  break;
  case 10:
 echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_Adaptateur' ); ?>">             
 <label>prix <input type="number"  name="prix_Adaptateur">  </label>

<input type="submit" name="enregister" class="enregister_prix_services" value="enregister">  </form><?php
  break;
  case 11:
echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_Chargeur_Smartphone' ); ?>">             
 <label>prix <input type="number"  name="prix_Chargeur_Smartphone">  </label>

<input type="submit" name="enregister" class="enregister_prix_services" value="enregister"> </form> <?php
  break;
 case 12:
echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_Machine_Nespresso' ); ?>">             
 <label>prix <input type="number"  name="prix_Machine_Nespresso">  </label>

<input type="submit" name="enregister" class="enregister_prix_services" value="enregister">  </form><?php
  break;
   case 13:
echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_Hôtesse_Dédiée' ); ?>">             
 <label>prix <input type="number"  name="prix_Hôtesse_Dédiée">  </label>

<input type="submit" name="enregister" class="enregister_prix_services" value="enregister"> </form> <?php
  break;
   case 14:
 echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_Carte_RestoPass' ); ?>">             
 <label>prix <input type="number"  name="prix_Carte_RestoPass">  </label>

<input type="submit" class="enregister_prix_services" name="enregister" value="enregister">  </form><?php
  break;
  //Literie d`hôtel
   case 15:
echo $host_servces_draft->prix.'£<br>' ; ?>
 <form method="post" action=" <?php  register_activation_hook( __FILE__, 'updating_services_Literie_hôtel' ); ?>">             
 <label>prix <input type="number" name="prix_Literie_hôtel">  </label>

<input type="submit" class="enregister_prix_services" name="enregister" value="enregister"> </form> <?php
  break;
 default :
  echo '  ' ;
  break;
}


?>

</td>
</tr>
</tbody>




<?php
}
?>


</table>
</div>

<?php

}
function frontend_services_register_css() {
    wp_register_style('frontend-form-css', plugin_dir_url( __FILE__ ) . '/resources/css/services_hotel_frontend.css');
}
add_action('init', 'frontend_services_register_css');

function frontend_services_print_css() {
    global $frontend_load_css;
 
    // this variable is set to TRUE if the short code is used on a page/post
    if ( ! $frontend_load_css )
        return; // this means that neither short code is present, so we get out of here
 
    wp_print_styles('frontend-form-css');
}
add_action('wp_footer', 'frontend_services_print_css');

include ( plugin_dir_path( __FILE__ ) . 'service_wifi.php');
include"hotelservicesdb.php";

add_shortcode('hosts_service','host_services');



?>