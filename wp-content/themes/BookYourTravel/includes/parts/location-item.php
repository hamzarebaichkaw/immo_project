<?php
	global $post, $location_class, $display_mode, $byt_theme_globals, $byt_locations_post_type, $byt_accommodations_post_type, $byt_tours_post_type, $byt_cruises_post_type, $byt_car_rentals_post_type;

	$enable_accommodations = $byt_theme_globals->enable_accommodations();
	$enable_cruises = $byt_theme_globals->enable_cruises();
	$enable_tours = $byt_theme_globals->enable_tours();
	$enable_car_rentals = $byt_theme_globals->enable_car_rentals();
	$price_decimal_places = $byt_theme_globals->get_price_decimal_places();
	$default_currency_symbol = $byt_theme_globals->get_default_currency_symbol();
	$show_currency_symbol_after = $byt_theme_globals->show_currency_symbol_after();
	
	$show_self_catered_count_in_location_items = $byt_theme_globals->show_self_catered_count_in_location_items();
	$show_hotel_count_in_location_items = $byt_theme_globals->show_hotel_count_in_location_items();
	$show_cruise_count_in_location_items = $byt_theme_globals->show_cruise_count_in_location_items();
	$show_tour_count_in_location_items = $byt_theme_globals->show_tour_count_in_location_items();
	$show_car_rental_count_in_location_items = $byt_theme_globals->show_car_rental_count_in_location_items();
	
	$location_id = $post->ID;
	$location_obj = new byt_location($post);
	$base_id = $location_obj->get_base_id();

	$location_image = $location_obj->get_main_image();	
	if (empty($location_image)) {
		$location_image = BYT_Theme_Utils::get_file_uri('/images/uploads/img.jpg');
	}
	
	$hotel_count = $self_catered_count = $cruise_count = $tour_count = $car_rental_count = 0;
	
	if ($show_hotel_count_in_location_items)
		$hotel_count = (int)$byt_accommodations_post_type->list_accommodations_count ( 0, -1, 'post_title', 'ASC', $location_id, array(), array(), array(), false, false);
	
	if ($show_self_catered_count_in_location_items)
		$self_catered_count = (int)$byt_accommodations_post_type->list_accommodations_count ( 0, -1, 'post_title', 'ASC', $location_id, array(), array(), array(), false, true);
	
	if ($show_cruise_count_in_location_items)
		$cruise_count = (int)$byt_cruises_post_type->list_cruises_count ( 0, -1, 'post_title', 'ASC', $location_id);
	
	if ($show_tour_count_in_location_items)
		$tour_count = (int)$byt_tours_post_type->list_tours_count ( 0, -1, 'post_title', 'ASC', $location_id);

	if ($show_car_rental_count_in_location_items)
		$car_rental_count = (int)$byt_car_rentals_post_type->list_car_rentals_count ( 0, -1, 'post_title', 'ASC', $location_id);
		
	$accommodation_min_price = $byt_accommodations_post_type->get_accommodation_min_price(0, 0, $location_id);

	if (empty($display_mode) || $display_mode == 'card') {
?>
	<!--location item-->
	<article class="location_item <?php echo $location_class; ?>">
		<div>
			<figure>
				<a href="<?php  echo esc_url($location_obj->get_permalink()); ?>" title="<?php echo esc_attr($location_obj->get_title()); ?>">
					<img src="<?php echo esc_url($location_image); ?>" alt="<?php echo esc_attr($location_obj->get_title()); ?>" />
				</a>
			</figure>
			<div class="details">
				<?php 
				echo "<div class='actions'>";
				BYT_Theme_Utils::render_link_button($location_obj->get_permalink(), "gradient-button", "", __('View all', 'bookyourtravel')); 
				echo "</div>";
				?>				
				<h3><?php echo $location_obj->get_title(); ?></h3>
				<?php
				// display hotel and self-catered counts
				if ($enable_accommodations) {
					if ($show_hotel_count_in_location_items)
						BYT_Theme_Utils::render_field("", "count", $hotel_count . ' ' . __('Hotels', 'bookyourtravel'), '', '', false);
					if ($show_self_catered_count_in_location_items)
						BYT_Theme_Utils::render_field("", "count", $self_catered_count . ' ' . __('Self-catered', 'bookyourtravel'), '', '', false);
				}
				if ($enable_tours && $show_tour_count_in_location_items) {
					BYT_Theme_Utils::render_field("", "count", $tour_count . ' ' . __('Tours', 'bookyourtravel'), '', '', false);
				}
				if ($enable_cruises && $show_cruise_count_in_location_items) {
					BYT_Theme_Utils::render_field("", "count", $cruise_count . ' ' . __('Cruises', 'bookyourtravel'), '', '', false);
				}
				if ($enable_car_rentals && $show_car_rental_count_in_location_items) {
					BYT_Theme_Utils::render_field("", "count", $car_rental_count . ' ' . __('Car rentals', 'bookyourtravel'), '', '', false);
				}				
				if ($accommodation_min_price > 0 && ($hotel_count || $self_catered_count)) { ?>
				<div class="ribbon">
					<div class="half hotel">
						<a href="<?php echo esc_url($location_obj->get_permalink()); ?>#hotels" title="<?php esc_attr_e('View all', 'bookyourtravel'); ?>">
							<span class="small"><?php _e('from', 'bookyourtravel'); ?></span>
							<div class="price">
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
						</a>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</article>
	<!--//location item-->
	<?php 
	} else {
?>
	<li>
		<a href="<?php echo esc_url($location_obj->get_permalink()); ?>">
			<figure>
				<img src="<?php echo esc_url($location_image); ?>" alt="<?php echo esc_attr($location_obj->get_title()); ?>" />
			</figure>
			<h3><?php echo $location_obj->get_title(); ?> <?php if ($location_obj->get_status() == 'private') echo '<span class="private">' . __('Pending', 'bookyourtravel') . '</span>'; ?>
			</h3>			
		</a>
	</li>
<?php }