<?php
	global $post, $car_rental_class, $display_mode, $byt_theme_globals, $byt_car_rentals_post_type;
	
	$car_rental_id = $post->ID;
	$car_rental_obj = new byt_car_rental($post);

	$car_rental_image = $car_rental_obj->get_main_image();	
	if (empty($car_rental_image)) {
		$car_rental_image = BYT_Theme_Utils::get_file_uri('/images/uploads/img.jpg');
	}
	
	$price_decimal_places = $byt_theme_globals->get_price_decimal_places();
	$default_currency_symbol = $byt_theme_globals->get_default_currency_symbol();
	$show_currency_symbol_after = $byt_theme_globals->show_currency_symbol_after();
	
	$price_per_day = $car_rental_obj->get_custom_field('price_per_day');
	
	if (empty($display_mode) || $display_mode == 'card') {
?>
<!--car rental-->
<article class="car_rental_item <?php echo $car_rental_class; ?>">
	<div>
		<figure>
			<a href="<?php echo esc_url($car_rental_obj->get_permalink()); ?>" title="<?php echo esc_attr($car_rental_obj->get_title()); ?>">
				<img src="<?php echo esc_url($car_rental_image); ?>" alt="<?php echo esc_attr($car_rental_obj->get_title()); ?>" />
			</a>
		</figure>
		<div class="details cars">
			<h2><?php echo $car_rental_obj->get_title(); ?></h2>
			<?php if ($price_per_day > 0) { ?>
			<div class="price">
				<?php _e('Price per day ', 'bookyourtravel'); ?>
				<em>
				<?php if (!$show_currency_symbol_after) { ?>
				<span class="curr"><?php echo $default_currency_symbol; ?></span>
				<span class="amount"><?php echo number_format_i18n( $price_per_day, $price_decimal_places ); ?></span>
				<?php } else { ?>
				<span class="amount"><?php echo number_format_i18n( $price_per_day, $price_decimal_places ); ?></span>
				<span class="curr"><?php echo $default_currency_symbol; ?></span>
				<?php } ?>
				</em>
			</div>
			<?php } ?>
			<div class="description clearfix ">
				<?php BYT_Theme_Utils::render_field("car_type", "", __('Car type', 'bookyourtravel'), $car_rental_obj->get_type_name(), '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("max_people", "", __('Max people', 'bookyourtravel'), $car_rental_obj->get_custom_field('max_count'), '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("door_count", "", __('Door count', 'bookyourtravel'), $car_rental_obj->get_custom_field('number_of_doors'), '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("min_age", "", __('Minimum driver age', 'bookyourtravel'), $car_rental_obj->get_custom_field('min_age'), '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("transmission", "", __('Transmission', 'bookyourtravel'), ($car_rental_obj->get_custom_field('transmission_type') == 'manual' ? __('Manual', 'bookyourtravel') : __('Automatic', 'bookyourtravel')), '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("air_conditioned", "", __('Air-conditioned?', 'bookyourtravel'), ($car_rental_obj->get_custom_field('is_air_conditioned') ? __('Yes', 'bookyourtravel') : __('No', 'bookyourtravel')), '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("unlimited_mileage", "", __('Unlimited mileage?', 'bookyourtravel'), ($car_rental_obj->get_custom_field('is_unlimited_mileage') ? __('Yes', 'bookyourtravel') : __('No', 'bookyourtravel')), '', false, true); ?>
			</div>
			<?php 
			echo "<div class='actions'>";
			BYT_Theme_Utils::render_link_button($car_rental_obj->get_permalink(), "gradient-button", "", __('Book now', 'bookyourtravel')); 
			echo "</div>";
			?>
		</div>
	</div>
</article>
<!--//car rental item-->
<?php 
	} else {
?>
	<li>
		<a href="<?php echo esc_url($car_rental_obj->get_permalink()); ?>">
			<h3><?php echo $car_rental_obj->get_title(); ?> <?php if ($car_rental_obj->get_status() == 'private') echo '<span class="private">' . __('Pending', 'bookyourtravel') . '</span>'; ?>
			</h3>
			<?php if ($price_per_day > 0) { ?>
			<p>
				<?php 
				$price_string = '';
				if (!$show_currency_symbol_after) { 
					$price_string = '<span class="curr">' . $default_currency_symbol . '</span>';
					$price_string .= '<span class="amount">' . number_format_i18n( $price_per_day, $price_decimal_places ) . '</span>';
				} else { 
					$price_string = '<span class="amount">' . number_format_i18n( $price_per_day, $price_decimal_places ) . '</span>';
					$price_string .= '<span class="curr">' . $default_currency_symbol . '</span>';
				}
				echo sprintf(__('From %s per day', 'bookyourtravel'), $price_string);
				?>
			</p>
			<?php } ?>
		</a>
	</li>
<?php }