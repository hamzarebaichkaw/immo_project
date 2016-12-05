<?php


function list_client_services(){

 global $pippin_load_css;
 
        // set this to true so the CSS is loaded
        $pippin_load_css = true;
global $wpdb;
//if(isset($_POST['search_publish_posts']))
     
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services,info from wp_services_global where VISIBLITY='true' ");

?>
<div><h2>LES SERVICES DISPONIBLE </h2> </div>    
<?php
foreach($host_services_drafts as $host_servces_draft)
      {
?>


<table>
<tbody>
<tr><td>
	<div class="bloc_services">
		
			<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_admin"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<DIV class="PARGRAPHE_SERVICES_info">
<p ><?php echo" $host_servces_draft->info"; ?></p>
</DIV>

		
</div></td>

</tr>
</tbody>




<?php
}
?>


</table>


<?php
}
add_shortcode('listc_service','list_client_services');
?>