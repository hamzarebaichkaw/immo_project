<?php


/*
Plugin Name: host_services 

Description: services Plugin – is a great easy-to-use and easy-to-manage  service providers who think about their customers. The plugin supports a wide range of services provided by business and individuals who offer reservations through websites. Set up any reservation quickly.
Version: 1.0
Author: hamza rebai

*/
function ajout_services_hostservices() {
 
    // only show the registration form to non-logged-in members
    if(!is_user_logged_in()) {
 
        global $pippin_load_css;
 
        // set this to true so the CSS is loaded
        $pippin_load_css = true;
 
        // check to make sure user registration is enabled
        $registration_enabled = get_option('users_can_register');
 
        // only show the registration form if allowed
       // if($registration_enabled) {
            $output = admin_services_form();
       // } else {
         //   $output = __('User registration is not enabled');
        //}
        return $output;
    }
}
add_shortcode('register_form', 'ajout_services_hostservices');


// registration form fields
function admin_services_form() {
     ?>  
        <?php 
        // show any error messages after form submission
        pippin_show_error_messages(); ?>
 <form  method="POST" class="service_form_filds">
      <fieldset>
          
   <div class="nom_service" >         
<?php  _e('Nom de service   :'); ?>  <input type="text" name="nom_service" id="nom_service"></div></br>  
<div class="nom_service" > <?php  _e('Type   :'); ?>  <input type="text" name="type_service" id="type_service"></div> </br>
<div class="nom_service" > <?php  _e('prix par heure :'); ?>  <input type="number" name="prix_service" id="prix"> </div></br>
<div class="nom_service" > <?php  _e('durée de service   :'); ?>  <input type="date" name="date_service" id="date_service"></div> </br>
<div class="nom_service" > <?php  _e('Info sur le service :'); ?>  <textarea name="info_service" id="info_service"></textarea> </div></br>
<div class="nom_service" > <button type="submit"> <?php  _e('  Ajouter '); register_activation_hook( __FILE__, 'add_new_service' );?> </button></div>
  </fieldset>
 </form>       
    <?php
    return ob_get_clean();
}
add_action('init', 'add_new_service');
// used for tracking error messages
function pippin_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}
// displays error messages from form submissions
function pippin_show_error_messages() {
    if($codes = pippin_errors()->get_error_codes()) {
        echo '<div class="pippin_errors">';
            // Loop error codes and display errors
           foreach($codes as $code){
                $message = pippin_errors()->get_error_message($code);
                echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
            }
        echo '</div>';
    }   
}
// register our form css
function pippin_register_css() {
    wp_register_style('pippin-form-css', plugin_dir_url( __FILE__ ) . '/css/forms.css');
}
add_action('init', 'pippin_register_css');

// load our form css
function pippin_print_css() {
    global $pippin_load_css;
 
    
    if ( ! $pippin_load_css )
        return; // this means that neither short code is present, so we get out of here
 
    wp_print_styles('pippin-form-css');
}
add_action('wp_footer', 'pippin_print_css');

include( plugin_dir_path( __FILE__ ) . 'host_tables.php');
include( plugin_dir_path( __FILE__ ) . 'gestion_services.php');
include( plugin_dir_path( __FILE__ ) . 'MySettingsPage.php');

register_activation_hook( __FILE__, 'jal_install' );
register_activation_hook( __FILE__, 'jal_install_data' );
register_activation_hook( __FILE__, 'categorie_install' );

?>