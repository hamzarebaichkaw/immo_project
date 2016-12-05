<?php 
/* Template Name: Login Page
 * The template for displaying the Login page.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
if ( is_user_logged_in() ) {
	wp_redirect( get_home_url() );
	exit;
}

global $byt_theme_globals;

$login_page_url = $byt_theme_globals->get_login_page_url();
$override_wp_login = $byt_theme_globals->override_wp_login();
$register_page_url = $byt_theme_globals->get_register_page_url();
$reset_password_page_url = $byt_theme_globals->get_reset_password_page_url();
$terms_page_url = $byt_theme_globals->get_terms_page_url();
$redirect_to_after_login_url = $byt_theme_globals->get_redirect_to_after_login_page_url();
if (!$redirect_to_after_login_url)
	$redirect_to_after_login_url = get_home_url();
	
$login = null;

if( isset( $_POST['log'] ) && isset($_POST['bookyourtravel_login_form_nonce']) && wp_verify_nonce( $_POST['bookyourtravel_login_form_nonce'], 'bookyourtravel_login_form' ) ){

	$login = wp_signon(

		array(
			'user_login' => $_POST['log'],
			'user_password' => $_POST['pwd'],
			'remember' =>( ( isset( $_POST['rememberme'] ) && $_POST['rememberme'] ) ? true : false )
		),
		false
		);
	
	if ( !is_wp_error( $login ) ) {
		wp_redirect( $redirect_to_after_login_url );
		exit;
	}
} 

get_header();  
BYT_Theme_Utils::breadcrumbs();
get_sidebar('under-header');

global $post;

$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id);

$page_sidebar_positioning = null;
if (isset($page_custom_fields['page_sidebar_positioning'])) {
	$page_sidebar_positioning = $page_custom_fields['page_sidebar_positioning'][0];
	$page_sidebar_positioning = empty($page_sidebar_positioning) ? '' : $page_sidebar_positioning;
}

$section_class = 'three-fourth';
if ($page_sidebar_positioning == 'both')
	$section_class = 'one-half';
else if ($page_sidebar_positioning == 'left' || $page_sidebar_positioning == 'right') 
	$section_class = 'three-fourth';

if ($page_sidebar_positioning == 'both' || $page_sidebar_positioning == 'left')
	get_sidebar('left');
?>
<section class="<?php echo esc_attr($section_class); ?>">
		<form id="login_form" method="post" action="<?php echo esc_url(get_permalink()); ?>" class="booking">
			<fieldset>
				<h3><?php _e('Login', 'bookyourtravel'); ?></h3>
				<p class="">
					<?php _e('Don\'t have an account yet?', 'bookyourtravel'); ?> <a href="<?php echo esc_url($register_page_url); ?>"><?php _e('Sign up', 'bookyourtravel'); ?></a>. <?php _e('Forgotten your password?', 'bookyourtravel'); ?> <a href="<?php echo esc_url($reset_password_page_url); ?>"><?php _e('Reset it here', 'bookyourtravel'); ?></a>.
				</p>
				<?php if( is_wp_error( $login ) ){ 
					echo '<p class="error">' . __('Incorrect username or password. Please try again.', 'bookyourtravel') . '</p>';
				} 
				?>
				<div class="row twins">
					<div class="f-item">
						<label for="log"><?php _e('Username', 'bookyourtravel'); ?></label>
						<input type="text" name="log" id="log" value="" />
					</div>
					<div class="f-item">
						<label for="pwd"><?php _e('Password', 'bookyourtravel'); ?></label>
						<input type="password" name="pwd" id="pwd" value="" />
					</div>
				</div>
				<div class="row">					
					<div class="f-item checkbox">
						<input type="checkbox" name="rememberme" name="rememberme">
						<label for="rememberme"><?php _e( 'Remember Me', 'bookyourtravel' ); ?> </label>
					</div>
				</div>
				<?php wp_nonce_field( 'bookyourtravel_login_form', 'bookyourtravel_login_form_nonce' ) ?>
				<input type="hidden" name="redirect_to" value="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" />
				<input type="submit" id="login" name="login" value="<?php esc_attr_e('Login', 'bookyourtravel'); ?>" class="gradient-button"/>
			</fieldset>
		</form>
	</section>	
<?php
if ($page_sidebar_positioning == 'both' || $page_sidebar_positioning == 'right')
	get_sidebar('right');

get_footer();