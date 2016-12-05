<?php
/*	Template Name: Contact
 * The template for displaying the contact page.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
get_header();  
BYT_Theme_Utils::breadcrumbs();
get_sidebar('under-header');	

global $byt_theme_globals;
$enc_key = $byt_theme_globals->get_enc_key();

$contact_phone_number = $byt_theme_globals->get_contact_phone_number();
$business_contact_email = $byt_theme_globals->get_contact_email();
$add_captcha_to_forms = $byt_theme_globals->add_captcha_to_forms();
$business_address_longitude = $byt_theme_globals->get_business_address_longitude();
$business_address_latitude = $byt_theme_globals->get_business_address_latitude();

$form_submitted = false;
$contact_error = '';
$contact_message = '';
$contact_email = '';
$contact_name = '';

if(isset($_POST['contact_submit'])) {
	
	$form_submitted = true;	
	if ( empty($_POST) || !wp_verify_nonce($_POST['contact_form_nonce'],'contact_form') )
	{
	   // failed to verify nonce so exit.
	   exit;
	}
	else
	{
		// process form data since nonce was verified	   
		$contact_message = wp_kses($_POST['contact_message'], '');
		$contact_email = wp_kses($_POST['contact_email'], '');
		$contact_name = wp_kses($_POST['contact_name'], '');
		
		$c_val_s = intval(wp_kses($_POST['c_val_s'], ''));
		$c_val_1 = intval(BYT_Theme_Utils::decrypt(wp_kses($_POST['c_val_1'], ''), $enc_key));
		$c_val_2 = intval(BYT_Theme_Utils::decrypt(wp_kses($_POST['c_val_2'], ''), $enc_key));
		
		if ($add_captcha_to_forms && $c_val_s != ($c_val_1 + $c_val_2)) {			
			
			$contact_error = __('Invalid captcha, please try again!', 'bookyourtravel');			
			
		} else if (!empty($contact_name) && !empty($contact_email) && !empty($contact_message)) {				
			
			$email_to = get_option('admin_email');
			
			if (!empty($business_contact_email))
				$email_to = $business_contact_email;
			
			$subject = sprintf(__('Contact form submission from %s', 'bookyourtravel'), $contact_name);
			$body = sprintf(__("Name: %s\n\nEmail: %s\n\nMessage: %s", 'bookyourtravel'), $contact_name, $contact_email, $contact_message);
			$headers = 'From: '.$contact_name.' <'.$contact_email.'>' . "\r\n" . 'Reply-To: ' . $contact_email;

			wp_mail($email_to, $subject, $body, $headers);
		} else {
			$contact_error = __('To submit contact form, please enable JavaScript', 'bookyourtravel');
		}
	}
} 

$c_val_1 = mt_rand(1, 20);
$c_val_2 = mt_rand(1, 20);

$c_val_1_str = BYT_Theme_Utils::encrypt($c_val_1, $enc_key);
$c_val_2_str = BYT_Theme_Utils::encrypt($c_val_2, $enc_key);

if ( have_posts() ) while ( have_posts() ) : the_post(); ?>	
<!--three-fourth content-->
<section class="three-fourth">
	<h1><?php the_title(); ?></h1>
	<?php if (!empty($business_address_longitude) && !empty($business_address_latitude)) { ?>
	<!--map-->
	<div class="map-wrap">
		<div class="gmap" id="map_canvas"></div>
	</div>
	<!--//map-->
	<?php } ?>
</section>	
<!--three-fourth content-->	
<!--sidebar-->
<aside class="right-sidebar lower">
	<!--contact form-->
	<article class="default">
		<h2><?php _e('Send us a message', 'bookyourtravel'); ?></h2>
		<?php 
		if ($form_submitted) { ?>
		<p>
		<?php
			if (!empty($contact_error)) {
				echo $contact_error;
			} else {
				_e('Thank you for contacting us. We will get back to you as soon as we can.', 'bookyourtravel');
			} ?>
		</p>
		<?php
		}
		if (!$form_submitted || !empty($contact_error)) { ?>
		<form action="<?php echo esc_url(BYT_Theme_Utils::get_current_page_url()); ?>" id="contact-form" method="post">
			<fieldset>
				<div class="f-item">
					<label for="contact_name"><?php _e('Your name', 'bookyourtravel'); ?></label>
					<input type="text" id="contact_name" name="contact_name" required="required" value="<?php echo esc_attr($contact_name); ?>" />
				</div>
				<div class="f-item">
					<label for="contact_email"><?php _e('Your e-mail', 'bookyourtravel'); ?></label>
					<input type="email" id="contact_email" name="contact_email" required="required" value="<?php echo esc_attr($contact_email); ?>" />
				</div>
				<div class="f-item">
					<label for="contact_message"><?php _e('Your message', 'bookyourtravel'); ?></label>
					<textarea name="contact_message" id="contact_message" rows="10" cols="10" required="required"><?php echo esc_attr($contact_message); ?></textarea>
				</div>
				<?php if ($add_captcha_to_forms) { ?>
				<div class="f-item captcha">
					<label><?php echo sprintf(__('How much is %d + %d', 'bookyourtravel'), $c_val_1, $c_val_2) ?>?</label>
					<input type="text" required="required" id="c_val_s" name="c_val_s" />
					<input type="hidden" name="c_val_1" id="c_val_1" value="<?php echo esc_attr($c_val_1_str); ?>" />
					<input type="hidden" name="c_val_2" id="c_val_2" value="<?php echo esc_attr($c_val_2_str); ?>" />
				</div>
				<?php } ?>
				<?php wp_nonce_field('contact_form','contact_form_nonce'); ?>
				<input type="submit" value="<?php esc_attr_e('Send', 'bookyourtravel'); ?>" id="contact_submit" name="contact_submit" class="gradient-button" />
			</fieldset>
		</form>
		<?php } ?>
	</article>
	<!--//contact form-->		
	<?php if (!empty($contact_phone_number) || !empty($business_contact_email)) { ?>	
	<!--contact info-->
	<article class="default">
		<h2><?php _e('Or contact us directly', 'bookyourtravel'); ?></h2>
		<?php if (!empty($contact_phone_number)) {?><p class="phone-green"><?php echo $contact_phone_number; ?></p><?php } ?>
		<?php if (!empty($business_contact_email)) {?><p class="email-green"><a href="mailto:<?php echo esc_attr($business_contact_email); ?>"><?php echo $business_contact_email; ?></a></p><?php } ?>
	</article>
	<!--//contact info-->
	<?php } ?>	
</aside>
<!--//sidebar-->	
<?php 
endwhile; 
get_footer();