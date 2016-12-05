<?php



function host_edit_services(){

 global $pippin_load_css;
 
        // set this to true so the CSS is loaded
        $pippin_load_css = true;
global $wpdb;
//if(isset($_POST['search_publish_posts']))
     
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where Titre='WIFI gratuit' ");

?>
<div><h2></h2> </div>    
<?php
foreach($host_services_drafts as $host_servces_draft)
      {
?>

<div class="bloc_host_edit_services">
<table>
<tbody>

	<form method="post" action="<?php  register_activation_hook( __FILE__, 'updating_service_wifi' ); ?>" >
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>


	
	      
      <div class="form_host_service_edit">Prix de service :<input type='number' name='prix' class="edit_form_input" value=" <?php$host_servces_draft->prix_services ?> ">  £   </br></div>
  <div class="form_host_service_edit"> <?php _e(' Information sur service  :');?><textarea name='info' class="edit_form_input"><?php echo $host_servces_draft->info ;?> </textarea></div>
  
<?php
}
?>      
 <div class="form_host_service_edit"> visiblitée de service  : 
 </div> <input type="checkbox" value="true" checked="true" name="sss"/>


<input type="submit" class="button_admin_services"value=" <?php  _e('  Modifier ');?>">
	</form>
	


</tbody>





</table>

</div>
<?php
}


function services_host_edit_css() {
    wp_register_style('pippin-form-css', plugin_dir_url( __FILE__ ) . '/resources/css/services_hotel_frontend.css');
}
add_action('init', 'services_host_edit_css');

function services_host_edit_print_css() {
    global $pippin_load_css;
 
    // this variable is set to TRUE if the short code is used on a page/post
    if ( ! $pippin_load_css )
        return; // this means that neither short code is present, so we get out of here
 
    wp_print_styles('pippin-form-css');
}
add_action('wp_footer', 'services_host_edit_print_css');

include( plugin_dir_path( __FILE__ ) . 'base.php');

add_shortcode('wifi_edit_service','host_edit_services');
?>



