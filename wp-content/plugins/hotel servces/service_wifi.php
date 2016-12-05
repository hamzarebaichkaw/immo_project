<?php

//edit service Wifi par host


function wifi_services_edit_host(){
             


           global $wpdb;

     
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where id=2  ");


foreach($host_services_drafts as $host_servces_draft)
      {   echo "$id" ;
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="<?php  register_activation_hook( __FILE__, 'updating_services_wifi' ); ?>" method="post">
<label>prix <input type="number" name="prix">  </label>

<label>visibiltée <select name="visi"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>

<input type="submit" name="enregister" value="enregister">

</form>

<?php 

}
add_shortcode('wifi_eservice','wifi_services_edit_host');

//edit service Paniers repas par host

function Paniers_Repas_services_edit_host(){
             

 
          global $wpdb;

     
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where id=3 and id_host=$id ");


foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_Paniers_Repas">  </label>
<label> info <textarea name="info_Paniers_Repas"> </textarea> </label>
<label>visibiltée <select name="visi_Paniers_Repas"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>


<button type="submit" > <?php  


register_activation_hook( __FILE__, 'updating_services_Paniers_Repas' ); ?> Enregistrer</button>

</form>

<?php 

}
add_shortcode('Paniers_Repas','Paniers_Repas_services_edit_host');

//edit service Paniers repas par host
function Transfert_Aéroport_services_edit_host(){
          global $wpdb;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where where id=4 and id_host=$id ");
foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_Transfert_Aéroport">  </label>
<label> info <textarea name="info_Transfert_Aéroport"> </textarea> </label>
<label>visibiltée <select name="visi_Transfert_Aéroport"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>
<button type="submit" > <?php  
register_activation_hook( __FILE__, 'updating_services_Transfert_Aéroport' ); ?> Enregistrer</button>
</form>
<?php 
}
add_shortcode('Transfert_Aéroport','Transfert_Aéroport_services_edit_host');


//edit service Paniers repas par host
function ménage_services_edit_host(){
          global $wpdb;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where Titre='Service de ménage' ");
foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_ménage">  </label>
<label> info <textarea name="info_ménage"> </textarea> </label>
<label>visibiltée <select name="visi_ménage"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>
<button type="submit" > <?php  
register_activation_hook( __FILE__, 'updating_services_ménage' ); ?> Enregistrer</button>
</form>
<?php 
}
add_shortcode('Service_ménage','ménage_services_edit_host');
//edit service Smartphone local par host
function Smartphone_locale_services_edit_host(){
          global $wpdb;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where Titre='Smartphone local' ");
foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_Smartphone_local">  </label>
<label> info <textarea name="info_Smartphone_local"> </textarea> </label>
<label>visibiltée <select name="visi_Smartphone_local"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>
<button type="submit" > <?php  
register_activation_hook( __FILE__, 'updating_services_Smartphone_local' ); ?> Enregistrer</button>
</form>
<?php 
}
add_shortcode('Smartphone_local','Smartphone_locale_services_edit_host');
//edit service Bons plans par host
function Bons_plans_services_edit_host(){
          global $wpdb;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where Titre='Bons plans' ");
foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_Bons_plans">  </label>
<label> info <textarea name="info_Bons_plans"> </textarea> </label>
<label>visibiltée <select name="visi_Bons_plans"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>
<button type="submit" > <?php  
register_activation_hook( __FILE__, 'updating_services_Bons_plans' ); ?> Enregistrer</button>
</form>
<?php 
}
add_shortcode('Bons_plans','Bons_plans_services_edit_host');
//edit service Articles de Toilettes par host 
function Articles_Toilettes_services_edit_host(){
          global $wpdb;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where Titre='Articles de Toilettes 5*' ");
foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_Articles_Toilettes">  </label>
<label> info <textarea name="info_Articles_Toilettes"> </textarea> </label>
<label>visibiltée <select name="visi_Articles_Toilettes"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>
<button type="submit" > <?php  
register_activation_hook( __FILE__, 'updating_services_Articles_Toilettess' ); ?> Enregistrer</button>
</form>
<?php 
}
add_shortcode('Articles_Toilettes','Articles_Toilettes_services_edit_host');
//Blanchisserie / Nettoyage
//edit service Blanchisserie_Nettoyage par host 
function Blanchisserie_Nettoyage_services_edit_host(){
          global $wpdb;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where Titre='Blanchisserie / Nettoyage' ");
foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_Blanchisserie_Nettoyage">  </label>
<label> info <textarea name="info_Blanchisserie_Nettoyage"> </textarea> </label>
<label>visibiltée <select name="visi_Blanchisserie_Nettoyage"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>
<button type="submit" > <?php  
register_activation_hook( __FILE__, 'updating_services_Blanchisserie_Nettoyage' ); ?> Enregistrer</button>
</form>
<?php 
}
add_shortcode('Blanchisserie_Nettoyage','Blanchisserie_Nettoyage_services_edit_host');
//Adaptateur
function Adaptateur_services_edit_host(){
          global $wpdb;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where Titre='Adaptateur' ");
foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_Adaptateur">  </label>
<label> info <textarea name="info_Adaptateur"> </textarea> </label>
<label>visibiltée <select name="visi_Adaptateur"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>
<button type="submit" > <?php  
register_activation_hook( __FILE__, 'updating_services_Adaptateur' ); ?> Enregistrer</button>
</form>
<?php 
}
add_shortcode('Adaptateur','Adaptateur_services_edit_host');
//Chargeur de Smartphone
function Chargeur_Smartphone_services_edit_host(){
          global $wpdb;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where Titre='Chargeur de Smartphone' ");
foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_Chargeur_Smartphoner">  </label>
<label> info <textarea name="info_Chargeur_Smartphone"> </textarea> </label>
<label>visibiltée <select name="visi_Chargeur_Smartphone"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>
<button type="submit" > <?php  
register_activation_hook( __FILE__, 'updating_services_Chargeur_Smartphone' ); ?> Enregistrer</button>
</form>
<?php 
}
add_shortcode('Chargeur_Smartphone','Chargeur_Smartphone_services_edit_host');
//Machine Nespresso
function Machine_Nespresso_services_edit_host(){
          global $wpdb;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where Titre='Machine Nespresso' ");
foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_Machine_Nespresso">  </label>
<label> info <textarea name="info_Machine_Nespresso"> </textarea> </label>
<label>visibiltée <select name="visi_Machine_Nespresso"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>
<button type="submit" > <?php  
register_activation_hook( __FILE__, 'updating_services_Machine_Nespressoe' ); ?> Enregistrer</button>
</form>
<?php 
}
add_shortcode('Machine_Nespresso','Machine_Nespresso_services_edit_host');
//Hôtesse Dédiée
function Hôtesse_Dédiée_services_edit_host(){
          global $wpdb;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where Titre='Hôtesse Dédiée' ");
foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_Hôtesse_Dédiée">  </label>
<label> info <textarea name="info_Hôtesse_Dédiée"> </textarea> </label>
<label>visibiltée <select name="visi_Hôtesse_Dédiée"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>
<button type="submit" > <?php  
register_activation_hook( __FILE__, 'updating_services_Hôtesse_Dédiée' ); ?> Enregistrer</button>
</form>
<?php 
}
add_shortcode('Hôtesse_Dédiée','Hôtesse_Dédiée_services_edit_host');
//Carte RestoPass
function Carte_RestoPass_services_edit_host(){
          global $wpdb;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where Titre='Carte RestoPass' ");
foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_Carte_RestoPass">  </label>
<label> info <textarea name="info_Carte_RestoPass"> </textarea> </label>
<label>visibiltée <select name="visi_Carte_RestoPass"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>
<button type="submit" > <?php  
register_activation_hook( __FILE__, 'updating_services_Carte_RestoPass' ); ?> Enregistrer</button>
</form>
<?php 
}
add_shortcode('Carte_RestoPass','Carte_RestoPass_services_edit_host');
//Literie d`hôtel
function Literie_hôtel_services_edit_host(){
          global $wpdb;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where Titre='Literie d`hôtel' ");
foreach($host_services_drafts as $host_servces_draft)
      {
?>
	<div class="icon_services_admin"><img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > </div>
	 <div class="titre_services_host"> <h3><?php echo" $host_servces_draft->Titre"; ?></h3> </div>
<?php
}
?>
<form  action="" method="post">
<label>prix <input type="number" name="prix_Literie_hôtel">  </label>
<label> info <textarea name="info_Literie_hôtel"> </textarea> </label>
<label>visibiltée <select name="visi_Literie_hôtel"> <option value="true"> oui</option><option value="false"> non</option>  </select></label>
<button type="submit" > <?php  
register_activation_hook( __FILE__, 'updating_services_Literie_hôtel' ); ?> Enregistrer</button>
</form>
<?php 
}
add_shortcode('Literie_hôtel','Literie_hôtel_services_edit_host');



?>