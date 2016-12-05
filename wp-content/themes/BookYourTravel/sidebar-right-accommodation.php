<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */

global $post, $current_user, $accommodation_obj, $score_out_of_10, $byt_reviews_post_type, $byt_theme_globals;

$enable_reviews = $byt_theme_globals->enable_reviews();
$accommodation_location = $accommodation_obj->get_location(); 
?>
<aside id="secondary" class="right-sidebar widget-area" role="complementary">
	<ul>
		<li>
			<article class="accommodation-details hotel-details clearfix">
				<h1><?php echo $accommodation_obj->get_title(); ?>
					<span class="stars">
						<?php for ($i=0;$i<$accommodation_obj->get_custom_field('star_count');$i++) { ?>
						<img src="<?php echo BYT_Theme_Utils::get_file_uri('/images/ico/star.png'); ?>" alt="">
						<?php } ?>
					</span>
				</h1>
				<?php if ($accommodation_location != null) { ?>
				<span class="address"><?php echo $accommodation_obj->get_custom_field('address'); ?>, <?php echo (isset($accommodation_location) ? $accommodation_location->get_title() : ''); ?></span>
				<?php } ?>				
				<?php if ($score_out_of_10 > 0) { ?><span class="rating"><?php echo $score_out_of_10; ?> / 10</span><?php } ?>
				<?php BYT_Theme_Utils::render_field("description", "", "", BYT_Theme_Utils::strip_tags_and_shorten($accommodation_obj->get_description(), 100), "", true); ?>
				<?php
				$tags = $accommodation_obj->get_tags();
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
				if ($enable_reviews) {
					$reviews_by_current_user_query = $byt_reviews_post_type->list_reviews($accommodation_obj->get_base_id(), $current_user->ID);	
					if (!$reviews_by_current_user_query->have_posts() && is_user_logged_in()) {
						BYT_Theme_Utils::render_link_button("#", "gradient-button right leave-review review-accommodation", "", __('Leave a review', 'bookyourtravel'));
					}
					?>
					<p class="review-form-thank-you" style="display:none;">
					<?php _e('Thank you for submitting a review.', 'bookyourtravel'); ?>
					</p>
					<?php
				}
				if ($accommodation_obj->get_custom_field('contact_email')) {
					BYT_Theme_Utils::render_link_button("#", "gradient-button right contact-accommodation", "", __('Send inquiry', 'bookyourtravel'));
					?>
					<p class="inquiry-form-thank-you" style="display:none;">
					<?php _e('Thank you for submitting an inquiry. We will get back to you as soon as we can.', 'bookyourtravel'); ?>
					</p>
					<?php
				} ?>
			</article>				
		</li>
		<?php if ($enable_reviews) { ?>
		<li>
			<?php 
				$all_reviews_query = $byt_reviews_post_type->list_reviews($accommodation_obj->get_base_id());
				if ($all_reviews_query->have_posts()) { 
					while ($all_reviews_query->have_posts()) { 
					$all_reviews_query->the_post();
					global $post;	
					$likes = get_post_meta($post->ID, 'review_likes', true); 
					$author = get_the_author();
					?>
					<!--testimonials-->
					<article class="testimonials clearfix">
						<blockquote><?php echo $likes; ?></blockquote>
						<span class="name"><?php echo $author; ?></span>
					</article>
					<!--//testimonials-->
			<?php break; } } ?>
		</li>
		<?php } // $enable_reviews ?>
	<?php 
		wp_reset_postdata(); 
		dynamic_sidebar( 'right-accommodation' ); ?>
	</ul>
</aside><!-- #secondary -->