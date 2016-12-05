<?php
function gestion_services_hostservices()
{
	 global $pippin_load_css;
 
        // set this to true so the CSS is loaded
        $pippin_load_css = true;
 
	?>

	
	
	<table id="host_list_serviceshost">
	
	
	
	
<tbody><?php

global $wpdb;
//if(isset($_POST['search_publish_posts']))
     
$host_services_drafts = $wpdb->get_results("select prix_services,dure_debut,name_services,info from wp_services_global where id_host=1 ");


?>
<div >
<?php
foreach($host_services_drafts as $host_servces_draft)
      {
?>
<tr class="lists_services_host">
<?php
echo"<td>  Nom de service : ".$host_servces_draft->name_services."   </td>";
echo"<td>  Prix : ".$host_servces_draft->prix_services."  $</td>";
echo"<td>  description  :".$host_servces_draft->info." </td>";
?>
</tr></div>
<?php
       }

	   
?>
	</tbody>

	
<?php	

}
add_shortcode('services_hostservices','gestion_services_hostservices');
function services_register_css() {
    wp_register_style('pippin-form-css', plugin_dir_url( __FILE__ ) . '/css/forms.css');
}
add_action('init', 'services_register_css');

// load our form css
function gestion_services_print_css() {
    global $pippin_load_css;
 
    // this variable is set to TRUE if the short code is used on a page/post
    if ( ! $pippin_load_css )
        return; // this means that neither short code is present, so we get out of here
 
    wp_print_styles('pippin-form-css');
}
add_action('wp_footer', 'gestion_services_print_css');

?>



