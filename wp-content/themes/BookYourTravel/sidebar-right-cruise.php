<?php
/**
 * The sidebar containing the cruise widget area.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */

global $post, $current_user, $cruise_obj, $score_out_of_10, $byt_theme_globals, $byt_reviews_post_type;
$enable_reviews = $byt_theme_globals->enable_reviews();
?>
<aside id="secondary" class="right-sidebar widget-area" role="complementary">
	<ul>
		<li>
			<article class="cruise-details clearfix">
				<h1><?php echo $cruise_obj->get_title(); ?></h1>
				<?php if ($score_out_of_10 > 0) { ?>
				<span class="rating"><?php echo $score_out_of_10; ?> / 10</span>
				<?php } ?>
				<?php BYT_Theme_Utils::render_field("description", "", "", BYT_Theme_Utils::strip_tags_and_shorten($cruise_obj->get_description(), 100), "", true); ?>
				<?php
				$tags = $cruise_obj->get_tags();
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
					$reviews_by_current_user_query = $byt_reviews_post_type->list_reviews($cruise_obj->get_base_id(), $current_user->ID);
					if (!$reviews_by_current_user_query->have_posts() && is_user_logged_in()) {
						BYT_Theme_Utils::render_link_button("#", "gradient-button right leave-review review-cruise", "", __('Leave a review', 'bookyourtravel'));
					} 
				}
				if ($cruise_obj->get_custom_field('contact_email')) {
					BYT_Theme_Utils::render_link_button("#", "gradient-button right contact-cruise", "", __('Send inquiry', 'bookyourtravel'));
				} ?>
			</article>
			
		</li>			
		<?php if ($enable_reviews) { ?>
		<li>
			<?php 
			$all_reviews_query = $byt_reviews_post_type->list_reviews($cruise_obj->get_base_id());
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
					<?php 
					break; 
				} 
			} ?>
		</li>
		<?php 
		}
		wp_reset_postdata(); 
		dynamic_sidebar( 'right-cruise' ); ?>
	</ul>
</aside><!-- #secondary -->