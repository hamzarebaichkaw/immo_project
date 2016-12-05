<?php
/**
 * The sidebar containing the car rental widget area.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */

global $post, $current_user, $car_rental_obj;

$base_tour_id = $car_rental_obj->get_base_id();
$car_rental_location = $car_rental_obj->get_location();
$pick_up_location_title = '';
if ($car_rental_location)
	$pick_up_location_title = $car_rental_location->get_title();
?>
<aside id="secondary" class="right-sidebar widget-area" role="complementary">
	<ul>
		<li>
			<article class="tour-details clearfix">
				<h1><?php echo $car_rental_obj->get_title(); ?></h1>
				<span class="address"><?php echo $pick_up_location_title; ?></span>
				<?php BYT_Theme_Utils::render_field("description", "", "", BYT_Theme_Utils::strip_tags_and_shorten($car_rental_obj->get_description(), 100), "", true); ?>
				<?php		
				$tags = $car_rental_obj->get_tags();
				if (count($tags) > 0) {?>
				<div class="tags">
					<ul>
						<?php
							foreach ($tags as $tag) {
								echo '<li>' . $tag->name . '</li>';
							}
						?>						
					</ul>
				</div>
				<?php } ?>
				<?php 
				if ($car_rental_obj->get_custom_field('contact_email')) {
					BYT_Theme_Utils::render_link_button("#", "gradient-button right contact-car_rental", "", __('Send inquiry', 'bookyourtravel'));
				} ?>
			</article>				
		</li>			
	<?php 
		wp_reset_postdata(); 
		dynamic_sidebar( 'right-car_rental' ); ?>
	</ul>
</aside><!-- #secondary -->