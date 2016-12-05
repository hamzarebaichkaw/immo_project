 <?php 


  function  admin_servicess_hotel(){

 global $current_user;
  $services       = __( 'hotel Services');
   if ( $current_user->has_cap( 'administrator' )){

    if ($_POST['editservicewifi']) {
      add_links_page( '', '', 'read', 'services_backendss', 'wifi_services_edit_ADMIN' );
     
    }
   elseif ($_POST['editservicerepas']) {
 add_links_page( '', '', 'read', 'services_backendss', 'repas_services_edit_ADMIN'
                );
     
   }elseif ($_POST['editservice_Transfert_Aéroport']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Transfert_Aéroport_services_edit_ADMIN'
                );
   }elseif ($_POST['editservice_menage']) {
    add_links_page( '', '', 'read', 'services_backendss', 'menage_services_edit_ADMIN'
                );
   }elseif ($_POST['editservice_Smartphone_local']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Smartphone_local_services_edit_ADMIN'
                );
   }elseif ($_POST['editservice_Bons_plans']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Bons_plans_services_edit_ADMIN'
                );
   }elseif ($_POST['editservice_Articles_Toilettes']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Articles_Toilettes_services_edit_ADMIN'
                );
   }elseif ($_POST['editservice_Blanchisserie_Nettoyage']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Blanchisserie_Nettoyage_services_edit_ADMIN'
                );
   }elseif ($_POST['editservice_Adaptateur']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Adaptateur_services_edit_ADMIN'
                );
   }elseif ($_POST['editservice_Chargeur_Smartphone']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Chargeur_Smartphone_services_edit_ADMIN'
                );
   }elseif ($_POST['editservice_Machine_Nespresso']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Machine_Nespresso_services_edit_ADMIN'
                );
   }elseif ($_POST['editservice_Hôtesse_Dédiée']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Hôtesse_Dédiée_services_edit_ADMIN'
                );
   }elseif ($_POST['editservice_Carte_RestoPass']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Carte_RestoPass_services_edit_ADMIN'
                );
   }elseif ($_POST['editservice_Literie_hôtel']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Literie_hôtel_services_edit_ADMIN'
                );
   }

  else{
          if ($_POST['Enregistrer_wifi']) {
           add_links_page( '', '', 'read', 'services_backendss', 'wifi_services_edit_ADMIN' );
          }elseif ($_POST['Enregistrer_repas']) {
               add_links_page( '', '', 'read', 'services_backendss', 'repas_services_edit_ADMIN');     
          }elseif ($_POST['Enregistrer_Transfert_Aéroport']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Transfert_Aéroport_services_edit_ADMIN');
          }elseif ($_POST['Enregistrer_menage']) {
    add_links_page( '', '', 'read', 'services_backendss', 'menage_services_edit_ADMIN');
          }elseif ($_POST['Enregistrer_Smartphone_local']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Smartphone_local_services_edit_ADMIN');
          }elseif ($_POST['Enregistrer_Bons_plans']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Bons_plans_services_edit_ADMIN');
          }elseif ($_POST['Enregistrer_Articles_Toilettes']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Articles_Toilettes_services_edit_ADMIN');
          }elseif ($_POST['Enregistrer_Blanchisserie_Nettoyage']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Blanchisserie_Nettoyage_services_edit_ADMIN');
          }elseif ($_POST['Enregistrer_Adaptateur']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Adaptateur_services_edit_ADMIN');
          }elseif ($_POST['Enregistrer_Chargeur_Smartphone']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Chargeur_Smartphone_services_edit_ADMIN');
          }elseif ($_POST['Enregistrer_Machine_Nespresso']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Machine_Nespresso_services_edit_ADMIN');
          }elseif ($_POST['Enregistrer_Hôtesse_Dédiée']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Hôtesse_Dédiée_services_edit_ADMIN');
          }elseif ($_POST['Enregistrer_Carte_RestoPass']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Carte_RestoPass_services_edit_ADMIN');
          }elseif ($_POST['Enregistrer_Literie_hôtel']) {
    add_links_page( '', '', 'read', 'services_backendss', 'Literie_hôtel_services_edit_ADMIN');
          }

      }

  

}
}
add_action('admin_menu','admin_servicess_hotel');
function wifi_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=2");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
	<div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon"></div><br>
	 <div class="titre_services_host">  Titre de service  : <input type="text" name="titre"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info_wifi"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_wifi" value="Enregistrer">

</div>

</div>
</form>
<?php 

$titre=$_POST["titre"];
$info_wifi=$_POST["info_wifi"];
$icon="resources/images/".$_POST["icon"];
echo $info_wifi;
if(isset($_POST["titre"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array( 
      
      'Titre' => $titre, 
      'info'=>$info_wifi,
      'img_services'=>$icon,
   
      
    ) ,
     array('id' =>2)
  );
}
}
function repas_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=3");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon_repas"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service_repas"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info_repas"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_repas" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service_repas"];
$info_wifi=$_POST["info_repas"];
$icon="resources/images/".$_POST["icon_repas"];

if(isset($_POST["titre_service_repas"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>3));
}
}
function Transfert_Aéroport_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=4");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon_repas"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service_repas"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info_repas"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_Transfert_Aéroport" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service_repas"];
$info_wifi=$_POST["info_repas"];
$icon="resources/images/".$_POST["icon_repas"];

if(isset($_POST["titre_service_repas"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>4));
}
}
///Service de ménage
function menage_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=5");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_menage" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service"];
$info_wifi=$_POST["info"];
$icon="resources/images/".$_POST["icon"];

if(isset($_POST["titre_service"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>5));
}
}
//Smartphone local
function Smartphone_local_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=6");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_Smartphone_local" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service"];
$info_wifi=$_POST["info"];
$icon="resources/images/".$_POST["icon"];

if(isset($_POST["titre_service"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>6));
}
}
//Bons_plans
function Bons_plans_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=7");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_Bons_plans" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service"];
$info_wifi=$_POST["info"];
$icon="resources/images/".$_POST["icon"];

if(isset($_POST["titre_service"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>7));
}
}
//Articles_Toilettes
function Articles_Toilettes_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=8");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_Articles_Toilettes" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service"];
$info_wifi=$_POST["info"];
$icon="resources/images/".$_POST["icon"];

if(isset($_POST["titre_service"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>8));
}
}
//Blanchisserie_Nettoyage
function Blanchisserie_Nettoyage_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wp_services_global where id=9");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_Blanchisserie_Nettoyage" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service"];
$info_wifi=$_POST["info"];
$icon="resources/images/".$_POST["icon"];

if(isset($_POST["titre_service"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>9));
}
}
//Adaptateur
function Adaptateur_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=10");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_Adaptateur" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service"];
$info_wifi=$_POST["info"];
$icon="resources/images/".$_POST["icon"];

if(isset($_POST["titre_service"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>10));
}
}
//Chargeur_Smartphone
function Chargeur_Smartphone_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=11");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_Chargeur_Smartphone" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service"];
$info_wifi=$_POST["info"];
$icon="resources/images/".$_POST["icon"];

if(isset($_POST["titre_service"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>11));
}
}
//Machine_Nespresso
function Machine_Nespresso_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=12");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_Machine_Nespresso" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service"];
$info_wifi=$_POST["info"];
$icon="resources/images/".$_POST["icon"];

if(isset($_POST["titre_service"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>12));
}
}
//Hôtesse_Dédiée
function Hôtesse_Dédiée_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=13");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_Hôtesse_Dédiée" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service"];
$info_wifi=$_POST["info"];
$icon="resources/images/".$_POST["icon"];

if(isset($_POST["titre_service"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>13));
}
}

//Carte_RestoPass
function Carte_RestoPass_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=14");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_Carte_RestoPass" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service"];
$info_wifi=$_POST["info"];
$icon="resources/images/".$_POST["icon"];

if(isset($_POST["titre_service"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>14));
}
}

//Literie_hôtel
function Literie_hôtel_services_edit_ADMIN(){ 
          global $wpdb;
       global $service_admin_load_css;
 
        // set this to true so the CSS is loaded
        $service_admin_load_css = true;
$host_services_drafts = $wpdb->get_results("select Titre,info,img_services  from wpio_services_global where id=15");



foreach($host_services_drafts as $host_servces_draft)
      {
?>
<div class="col-sm-12">

<form  action="" method="post">
  <div class="icon_services_admin"> <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" > <input type="FILE" name="icon"></div><br>
   <div class="titre_services_host">  Titre de service  : <input type="text" name="titre_service"> </div><br>

<?php

}
?>

<label class="description"> description    :<textarea class="textarea" name="info"> </textarea> </label><br>
<div >

<input type="submit" class="btn_val" name="Enregistrer_Literie_hôtel" value="Enregistrer">

</div>

</div
</form>
<?php 

$titre=$_POST["titre_service"];
$info_wifi=$_POST["info"];
$icon="resources/images/".$_POST["icon"];

if(isset($_POST["titre_service"])) 
{   
 $table_name ="wpio_services_global";
  
  $wpdb->update( 
    $table_name, 
    array('Titre' => $titre, 'info'=>$info_wifi,'img_services'=>$icon) , array('id' =>15));
}
}

function admin_service_css() {

$admin_handle = 'admin_css';
$admin_stylesheet = plugin_dir_url( __FILE__ ) . '/resources/css/admin_edit_services.css';

wp_enqueue_style( $admin_handle, $admin_stylesheet );
}
add_action('admin_print_styles', 'admin_service_css');
//include 'serviceadmindb.php';
  ?>
