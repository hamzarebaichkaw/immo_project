<?php 
global $byt_theme_globals;

$login_page_url = $byt_theme_globals->get_login_page_url();
$override_wp_login = $byt_theme_globals->override_wp_login();
$reset_password_page_url = $byt_theme_globals->get_reset_password_page_url();
?>	
<div class="lightbox" style="display:none;" id="login_lightbox">
	<div class="lb-wrap">
		<a onclick="toggleLightbox('login_lightbox');" href="javascript:void(0);" class="close">x</a>
		<div class="lb-content">
			<form action="<?php echo esc_url( $login_page_url ); ?>" method="post">
				<h1><?php _e('Log in', 'bookyourtravel'); ?></h1>
				<div class="f-item">
					<label for="log"><?php _e('Username', 'bookyourtravel'); ?></label>
					<input type="text" name="log" id="log" value="" />
				</div>
				<div class="f-item">
					<label for="pwd"><?php _e('Password', 'bookyourtravel'); ?></label>
					<input type="password" id="pwd" name="pwd" />
				</div>
				<div class="f-item checkbox">
					<input type="checkbox" id="rememberme" name="rememberme" checked="checked" value="forever" />
					<label for="rememberme"><?php _e('Remember me next time', 'bookyourtravel'); ?></label>
				</div>
				<p><a href="<?php echo esc_url($reset_password_page_url); ?>" title="<?php esc_attr_e('Forgot your password?', 'bookyourtravel'); ?>"><?php _e('Forgot your password?', 'bookyourtravel'); ?></a><br />
				<?php _e('Don\'t have an account yet?', 'bookyourtravel'); ?> <a onclick="toggleLightbox('register_lightbox');" href="javascript:void(0);" title="<?php esc_attr_e('Sign up', 'bookyourtravel'); ?>"><?php _e('Sign up', 'bookyourtravel'); ?>.</a></p>
				<?php wp_nonce_field( 'bookyourtravel_login_form', 'bookyourtravel_login_form_nonce' ) ?>
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" />
				<input type="submit" id="login" name="login" value="<?php esc_attr_e('Login', 'bookyourtravel'); ?>" class="gradient-button"/>
			</form>
		</div>
	</div>
</div>