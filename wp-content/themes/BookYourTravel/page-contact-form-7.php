<?php
/*	Template Name: Contact Form 7 
 * Template for displaying a contact page using a contact form 7 form.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
 
get_header();  
BYT_Theme_Utils::breadcrumbs();
get_sidebar('under-header');	

global $byt_theme_globals;
 
$contact_phone_number = $byt_theme_globals->get_contact_phone_number();
$contact_email = $byt_theme_globals->get_contact_email();
$business_address_longitude = $byt_theme_globals->get_business_address_longitude();
$business_address_latitude = $byt_theme_globals->get_business_address_latitude();

?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>	
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
		<?php the_content(); ?>
	</article>
	<!--//contact form-->	
<?php if (!empty($contact_phone_number)	|| !empty($contact_email)) { ?>	
	<!--contact info-->
	<article class="default">
		<h2><?php _e('Or contact us directly', 'bookyourtravel'); ?></h2>
		<?php if (!empty($contact_phone_number)) {?><p class="phone-green"><?php echo $contact_phone_number; ?></p><?php } ?>
		<?php if (!empty($contact_email)) {?><p class="email-green"><a href="#"><?php echo $contact_email; ?></a></p><?php } ?>
	</article>
	<!--//contact info-->
<?php } ?>		
</aside>
<!--//sidebar-->	
<?php endwhile;
get_footer();