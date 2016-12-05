<?php
	global $post, $accommodation_class, $display_mode, $current_url, $byt_theme_globals, $byt_reviews_post_type, $byt_accommodations_post_type;
	
	$list_user_accommodations_url = $byt_theme_globals->get_list_user_accommodations_url();
	$submit_accommodations_url = $byt_theme_globals->get_submit_accommodations_url();
	$price_decimal_places = $byt_theme_globals->get_price_decimal_places();
	$default_currency_symbol = $byt_theme_globals->get_default_currency_symbol();
	$show_currency_symbol_after = $byt_theme_globals->show_currency_symbol_after();
	
	$accommodation_id = $post->ID;
	$accommodation_obj = new byt_accommodation($post);
	$base_id = $accommodation_obj->get_base_id();
	$reviews_total = $byt_reviews_post_type->get_reviews_count($base_id);
	
	$accommodation_image = $accommodation_obj->get_main_image();	
	if (empty($accommodation_image)) {
		$accommodation_image = BYT_Theme_Utils::get_file_uri('/images/uploads/img.jpg');
	}
	
	$score_out_of_10 = 0;
	if ($reviews_total > 0) {
		$review_score = $accommodation_obj->get_custom_field('review_score', false);
		$score_out_of_10 = floor($review_score * 10);
	}
	$accommodation_location = $accommodation_obj->get_location();
	$accommodation_min_price = number_format($byt_accommodations_post_type->get_accommodation_min_price($accommodation_id), $price_decimal_places, ".", "");
	$accommodation_description_html = BYT_Theme_Utils::strip_tags_and_shorten($accommodation_obj->get_description(), 100) . '<a href="' . esc_url($accommodation_obj->get_permalink()) . '">' . __('More info', 'bookyourtravel') . '</a>';
	
	if (empty($display_mode) || $display_mode == 'card') {
?><!--accommodation item-->
	<article class="accommodation_item <?php echo esc_attr($accommodation_class); ?>">
		<div>
			<figure>
				<a href="<?php echo esc_url($accommodation_obj->get_permalink()); ?>" title="<?php echo esc_attr($accommodation_obj->get_title()); ?>">
					<img src="<?php echo esc_url($accommodation_image); ?>" alt="<?php echo esc_attr($accommodation_obj->get_title()); ?>" />
				</a>
			</figure>
			<div class="details">
				<h2>
					<?php echo $accommodation_obj->get_title(); ?> <?php if ($accommodation_obj->get_status() == 'private') echo '<span class="private">' . __('Pending', 'bookyourtravel') . '</span>'; ?>
					<span class="stars">
					<?php
					for ( $i = 0; $i < $accommodation_obj->get_custom_field('star_count'); $i++ ) { ?>
						<img src="<?php echo esc_url(BYT_Theme_Utils::get_file_uri('/images/ico/star.png')); ?>" alt="" />
					<?php } ?>
					</span>
				</h2>
				<?php 
				
				// display accommodation address
				$accommodation_address = $accommodation_obj->get_custom_field('address');
				$accommodation_address .= isset($accommodation_location) && is_object($accommodation_location) ? ', ' . $accommodation_location->get_title() : '';
				BYT_Theme_Utils::render_field("", "address", $accommodation_address, '', '', false, false);

				if ($score_out_of_10 > 0) {
					// display score out of 10
					BYT_Theme_Utils::render_field("", "rating", $score_out_of_10 . ' / 10', "", '', false, false);
				}			
				if ($accommodation_min_price > 0) { ?>
				<div class="price">
					<?php _e('Price per night from ', 'bookyourtravel'); ?>
					<em>
					<?php if (!$show_currency_symbol_after) { ?>
					<span class="curr"><?php echo $default_currency_symbol; ?></span>
					<span class="amount"><?php echo number_format_i18n( $accommodation_min_price, $price_decimal_places ); ?></span>
					<?php } else { ?>
					<span class="amount"><?php echo number_format_i18n( $accommodation_min_price, $price_decimal_places ); ?></span>
					<span class="curr"><?php echo $default_currency_symbol; ?></span>
					<?php } ?>
					</em>
				</div>
				<?php } ?>
				<?php 
					BYT_Theme_Utils::render_field("description clearfix", "", "", $accommodation_description_html, '', false, true);
					echo "<div class='actions'>";
					if (!empty($current_url) && $current_url == $list_user_accommodations_url)
						BYT_Theme_Utils::render_link_button($submit_accommodations_url . '?fesid=' . $accommodation_id, "gradient-button clearfix", "", __('Edit', 'bookyourtravel')); 
					else 
						BYT_Theme_Utils::render_link_button($accommodation_obj->get_permalink(), "gradient-button clearfix", "", __('Book now', 'bookyourtravel')); 
					echo "</div>";
				?>
			</div>
		</div>
	</article>
	<!--//accommodation item-->
<?php 
	} else {
?>
	<li>
		<a href="<?php echo esc_url($accommodation_obj->get_permalink()); ?>">
			<h3><?php echo $accommodation_obj->get_title(); ?> <?php if ($accommodation_obj->get_status() == 'private') echo '<span class="private">' . __('Pending', 'bookyourtravel') . '</span>'; ?>
				<span class="stars">
				<?php
				for ( $i = 0; $i < $accommodation_obj->get_custom_field('star_count'); $i++ ) { ?>
					<img src="<?php echo esc_url(BYT_Theme_Utils::get_file_uri('/images/ico/star.png')); ?>" alt="" />
				<?php } ?>
				</span>
			</h3>
			<?php if ($accommodation_min_price > 0) { ?>
			<p>
				<?php 
				$price_string = '';
				if (!$show_currency_symbol_after) { 
					$price_string = '<span class="curr">' . $default_currency_symbol . '</span>';
					$price_string .= '<span class="amount">' . number_format_i18n( $accommodation_min_price, $price_decimal_places ) . '</span>';
				} else { 
					$price_string = '<span class="amount">' . number_format_i18n( $accommodation_min_price, $price_decimal_places ) . '</span>';
					$price_string .= '<span class="curr">' . $default_currency_symbol . '</span>';
				}
				echo sprintf(__('From %s per night', 'bookyourtravel'), $price_string);
				?>
			</p>
			<?php } ?>
			<?php
			if ($score_out_of_10 > 0) {
				// display score out of 10
				BYT_Theme_Utils::render_field("", "rating", $score_out_of_10 . ' / 10', "", '', false, false);
			}	
			?>
		</a>
	</li>
<?php }