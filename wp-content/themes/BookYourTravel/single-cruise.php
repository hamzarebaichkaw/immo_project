<?php 

get_header();  
BYT_Theme_Utils::breadcrumbs();
get_sidebar('under-header');

global $post, $byt_theme_globals, $current_user, $cruise_obj, $entity_obj, $default_cruise_tabs, $score_out_of_10, $byt_cruises_post_type, $byt_theme_of_custom;

$enable_reviews = $byt_theme_globals->enable_reviews();
$enable_cruises = $byt_theme_globals->enable_cruises();
$cruise_extra_fields = $byt_theme_globals->get_cruise_extra_fields();
$tab_array = $byt_theme_globals->get_cruise_tabs();
$price_decimal_places = $byt_theme_globals->get_price_decimal_places();
$default_currency_symbol = $byt_theme_globals->get_default_currency_symbol();
$show_currency_symbol_after = $byt_theme_globals->show_currency_symbol_after();

if ( have_posts() ) {

	the_post();
	$cruise_obj = new byt_cruise($post);
	$cruise_id = $cruise_obj->get_id();
	$entity_obj = $cruise_obj;
	$cruise_date_from = date('Y-m-d', strtotime("+0 day", time()));
	$cruise_date_from_year = date('Y', strtotime("+0 day", time()));
	$cruise_date_from_month = date('n', strtotime("+0 day", time()));
	$cruise_is_reservation_only = $cruise_obj->get_is_reservation_only();
	$cruise_locations = $cruise_obj->get_locations();
?>
	<script>
		window.postType = 'cruise';
	</script>
<?php	
	if ($enable_reviews) {
		get_template_part('includes/parts/review', 'form'); 
	}
	get_template_part('includes/parts/inquiry', 'form');
	?>
	<!--cruise three-fourth content-->
	<section class="three-fourth">
		<?php
		get_template_part('includes/parts/cruise', 'booking-form');
		get_template_part('includes/parts/cruise', 'confirmation-form');
		?>	
		<script>
			window.startDate = null;
			window.formSingleError = <?php echo json_encode(__('You failed to provide 1 field. It has been highlighted below.', 'bookyourtravel')); ?>;
			window.formMultipleError = <?php echo json_encode(__('You failed to provide {0} fields. They have been highlighted below.', 'bookyourtravel'));  ?>;
			window.cruiseId = <?php echo $cruise_obj->get_id(); ?>;
			window.cruiseIsPricePerPerson = <?php echo $cruise_obj->get_is_price_per_person(); ?>;
			window.cruiseDateFrom = <?php echo json_encode($cruise_date_from); ?>;
			window.cruiseTitle = <?php echo json_encode($cruise_obj->get_title()); ?>;
			window.currentMonth = <?php echo date('n'); ?>;
			window.currentYear = <?php echo date('Y'); ?>;
			window.currentDay = <?php echo date('j'); ?>;
			window.cruiseIsReservationOnly = <?php echo $cruise_is_reservation_only; ?>;
		</script>
		<?php $cruise_obj->render_image_gallery(); ?>
		<!--inner navigation-->
		<nav class="inner-nav">
			<ul>
				<?php
				do_action( 'byt_show_single_cruise_tab_items_before' );
				$first_display_tab = '';			
				$i = 0;
				if (is_array($tab_array) && count($tab_array) > 0) {
					foreach ($tab_array as $tab) {
					
						if (!isset($tab['hide']) || $tab['hide'] != '1') {
					
							$tab_label = '';
							if (isset($tab['label'])) {
								$tab_label = $tab['label'];
								$tab_label = $byt_theme_of_custom->get_translated_dynamic_string($byt_theme_of_custom->get_option_id_context('cruise_tabs') . ' ' . $tab['label'], $tab_label);
							}
						
							if($i==0)
								$first_display_tab = $tab['id'];
							if ($tab['id'] == 'reviews' && $enable_reviews) {
								BYT_Theme_Utils::render_tab("cruise", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab_label . '">' . $tab_label . '</a>');
							} elseif ($tab['id'] == 'locations') {
								if ($cruise_locations && count($cruise_locations) > 0)
									BYT_Theme_Utils::render_tab("cruise", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab_label . '">' . $tab_label . '</a>');
							} elseif ($tab['id'] == 'description' || $tab['id'] == 'availability') {
								BYT_Theme_Utils::render_tab("tour", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab_label . '">' . $tab_label . '</a>');
							} else {
								$all_empty_fields = BYT_Theme_Utils::are_tab_fields_empty('cruise_extra_fields', $cruise_extra_fields, $tab['id'], $cruise_obj);
								
								if (!$all_empty_fields) {
									BYT_Theme_Utils::render_tab("cruise", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab_label . '">' . $tab_label . '</a>');
								}
							}
							$i++;
						}
					}
				} 				
				do_action( 'byt_show_single_cruise_tab_items_after' ); 
				?>
			</ul>
		</nav>
		<!--//inner navigation-->
		<?php do_action( 'byt_show_single_cruise_tab_content_before' ); ?>
		<!--description-->
		<section id="description" class="tab-content <?php echo $first_display_tab == 'description' ? 'initial' : ''; ?>">
			<article>
				<?php do_action( 'byt_show_single_cruise_description_before' ); ?>
				<?php BYT_Theme_Utils::render_field("text-wrap", "", "", $cruise_obj->get_description(), __('General', 'bookyourtravel')); ?>
				<?php BYT_Theme_Utils::render_tab_extra_fields('cruise_extra_fields', $cruise_extra_fields, 'description', $cruise_obj); ?>
				<?php do_action( 'byt_show_single_cruise_description_after' ); ?>
			</article>
		</section>
		<!--//description-->
		<!--availability-->
		<script>
			window.moreInfoText = '<?php echo __('+ more info', 'bookyourtravel'); ?>';
			window.lessInfoText = '<?php echo __('+ less info', 'bookyourtravel'); ?>';
		</script>
		<section id="availability" class="tab-content <?php echo $first_display_tab == 'availability' ? 'initial' : ''; ?>">
			<article>
				<?php do_action( 'byt_show_single_cruise_availability_before' ); ?>
				<h1><?php _e('Available departures', 'bookyourtravel'); ?></h1>
				<?php BYT_Theme_Utils::render_field("text-wrap", "", "", $cruise_obj->get_custom_field('availability_text'), '', false, true); ?>
				<form id="launch-cruise-booking" action="#" method="POST">
					<div class="text-wrap">
						<?php 
						
						if ($cruise_obj->get_type_is_repeated() == 1) {
							echo __('<p>This is a daily cruise.</p>', 'bookyourtravel'); 
						} else if ($cruise_obj->get_type_is_repeated() == 2) {
							echo __('<p>This cruise is repeated every weekday (working day).</p>', 'bookyourtravel'); 
						} else if ($cruise_obj->get_type_is_repeated() == 3) {
							echo sprintf(__('<p>This cruise is repeated every week on a %s.</p>', 'bookyourtravel'), $cruise_obj->get_type_day_of_week_day()); 
						}
						
						$cabin_type_ids = $cruise_obj->get_cabin_types();
						if ($cabin_type_ids && count($cabin_type_ids) > 0) { ?>
						<ul class="cabin-types room-types">
							<?php 
							// Loop through the items returned				
							for ( $z = 0; $z < count($cabin_type_ids); $z++ ) {
								$cabin_type_id = $cabin_type_ids[$z];
								$cabin_type_obj = new byt_cabin_type(intval($cabin_type_id));
								$cabin_type_min_price = $byt_cruises_post_type->get_cruise_min_price($cruise_id, $cabin_type_id, $cruise_date_from);
							?>
							<!--cabin_type-->
							<li id="cabin_type_<?php echo $cabin_type_id; ?>">
								<div class="row">
									<?php if ($cabin_type_obj->get_main_image('medium')) { ?>
									<figure class="left"><img src="<?php echo esc_url($cabin_type_obj->get_main_image('medium')) ?>" alt="<?php echo esc_attr($cabin_type_obj->get_title()); ?>" /><a href="<?php echo esc_url($cabin_type_obj->get_main_image()); ?>" class="image-overlay" rel="prettyPhoto[gallery1]"></a></figure>
									<?php } ?>
									<div class="meta cabin_type room_type">
										<h2><?php echo $cabin_type_obj->get_title(); ?></h2>
										<?php BYT_Theme_Utils::render_field('', '', '', $cabin_type_obj->get_custom_field('meta'), '', true, true); ?>
										<?php BYT_Theme_Utils::render_link_button("#", "more-info", "", __('+ more info', 'bookyourtravel')); ?>
									</div>
									<div class="cabin-information room-information">
										<div class="row">
											<span class="first"><?php _e('Max:', 'bookyourtravel'); ?></span>
											<span class="second">
												<?php for ( $j = 0; $j < $cabin_type_obj->get_custom_field('max_count'); $j++ ) { ?>
												<img src="<?php echo BYT_Theme_Utils::get_file_uri('/images/ico/person.png'); ?>" alt="" />
												<?php } ?>
											</span>
										</div>
										<?php if ($cabin_type_min_price > 0) { ?>
										<div class="row">
											<span class="first"><?php _e('Price from:', 'bookyourtravel'); ?></span>
											<div class="second price">
												<em>
													<?php if (!$show_currency_symbol_after) { ?>
													<span class="curr"><?php echo $default_currency_symbol; ?></span>
													<span class="amount"><?php echo number_format_i18n( $cabin_type_min_price, $price_decimal_places ); ?></span>
													<?php } else { ?>
													<span class="amount"><?php echo number_format_i18n( $cabin_type_min_price, $price_decimal_places ); ?></span>
													<span class="curr"><?php echo $default_currency_symbol; ?></span>
													<?php } ?>
												</em>
												<input type="hidden" class="max_count" value="<?php echo esc_attr($cabin_type_obj->get_custom_field('max_count')); ?>" />
												<input type="hidden" class="max_child_count" value="<?php echo esc_attr($cabin_type_obj->get_custom_field('max_child_count')); ?>" />
											</div>
										</div>
										<?php BYT_Theme_Utils::render_link_button("#", "gradient-button book-cruise-select-dates", "book-cruise-$cabin_type_id", __('Select dates', 'bookyourtravel')); ?>
										<?php } ?>
									</div>
									<div class="more-information">
										<?php BYT_Theme_Utils::render_field('', '', __('Cabin facilities:', 'bookyourtravel'), $cabin_type_obj->get_facilities_string(), '', true, true); ?>
										<?php echo $cabin_type_obj->get_description(); ?>
										<?php BYT_Theme_Utils::render_field('', '', __('Bed size:', 'bookyourtravel'), $cabin_type_obj->get_custom_field('bed_size'), '', true, true); ?>
										<?php BYT_Theme_Utils::render_field('', '', __('Cabin size:', 'bookyourtravel'), $cabin_type_obj->get_custom_field('cabin_size'), '', true, true); ?>
									</div>
								</div>
								<div class="step1_controls" style="display:none"></div>
							</li>
							<!--//cabin-->
							<?php 
							} 
							// Reset Second Loop Post Data
							wp_reset_postdata(); 
							// end while ?>
						</ul>	
						<?php 
						} else { 
							BYT_Theme_Utils::render_field('text-wrap', '', '', __('We are sorry, there are no cabins available at this cruise at the moment', 'bookyourtravel'), '', true, true);
						} 

						?>
					</div>
				</form>
				<?php BYT_Theme_Utils::render_tab_extra_fields('cruise_extra_fields', $cruise_extra_fields, 'availability', $cruise_obj); ?>
				<?php do_action( 'byt_show_single_cruise_availability_after' ); ?>

			</article>
		</section>
		<!--//availability-->
		<?php if ($cruise_locations && count($cruise_locations) > 0) { ?>		
		<!--locations-->
		<section id="locations" class="tab-content <?php echo $first_display_tab == 'locations' ? 'initial' : ''; ?>">
			<article>
				<?php do_action( 'byt_show_single_cruise_locations_before' ); ?>				
				<?php foreach ($cruise_locations as $location_id) {
					$location_obj = new byt_location((int)$location_id);
					$location_title = $location_obj->get_title();
					$location_excerpt = $location_obj->get_excerpt();
					if (!empty($location_title) && !empty($location_excerpt)) {
						BYT_Theme_Utils::render_field("", "", "", BYT_Theme_Utils::render_image('', '', $location_obj->get_main_image(), $location_title, $location_title, false) . $location_excerpt, $location_title);
						BYT_Theme_Utils::render_link_button(get_permalink($location_obj->get_id()), "gradient-button right", "", __('Read more', 'bookyourtravel'));
					}
				}?>								
				<?php BYT_Theme_Utils::render_tab_extra_fields('cruise_extra_fields', $cruise_extra_fields, 'locations', $cruise_obj); ?>
				<?php do_action( 'byt_show_single_cruise_locations_after' ); ?>
			</article>
		</section>
		<!--//locations-->
		<?php } ?>						
		<!--facilities-->
		<section id="facilities" class="tab-content <?php echo $first_display_tab == 'facilities' ? 'initial' : ''; ?>">
			<article>
				<?php do_action( 'byt_show_single_cruise_facilites_before' ); ?>
				<?php 
				$facilities = $cruise_obj->get_facilities();
				if ($facilities && count($facilities) > 0) { ?>
				<h1><?php _e('Facilities', 'bookyourtravel'); ?></h1>
				<div class="text-wrap">	
					<ul class="three-col">
					<?php
					for( $i = 0; $i < count($facilities); $i++) {
						$facility = $facilities[$i];
						echo '<li>' . $facility->name  . '</li>';
					} ?>					
					</ul>
				</div>
				<?php } // endif (!empty($facilities)) ?>			
				<?php BYT_Theme_Utils::render_tab_extra_fields('cruise_extra_fields', $cruise_extra_fields, 'facilities', $cruise_obj); ?>			
				<?php do_action( 'byt_show_single_cruise_facilites_after' ); ?>
			</article>
		</section>
		<!--//facilities-->
		<?php if ($enable_reviews) { ?>
		<!--reviews-->
		<section id="reviews" class="tab-content <?php echo $first_display_tab == 'reviews' ? 'initial' : ''; ?>">
			<?php 
			do_action( 'byt_show_single_cruise_reviews_before' );
			get_template_part('includes/parts/review', 'item'); 
			BYT_Theme_Utils::render_tab_extra_fields('cruise_extra_fields', $cruise_extra_fields, 'reviews', $cruise_obj); 
			do_action( 'byt_show_single_cruise_reviews_after' ); 
			?>
		</section>
		<!--//reviews-->
		<?php } // if ($enable_reviews) ?>
		<?php
		foreach ($tab_array as $tab) {
			if (count(BYT_Theme_Utils::custom_array_search($default_cruise_tabs, 'id', $tab['id'])) == 0) {
				$all_empty_fields = BYT_Theme_Utils::are_tab_fields_empty('cruise_extra_fields', $cruise_extra_fields, $tab['id'], $cruise_obj);
				
				if (!$all_empty_fields) {
			?>
				<section id="<?php echo esc_attr($tab['id']); ?>" class="tab-content <?php echo ($first_display_tab == $tab['id'] ? 'initial' : ''); ?>">
					<article>
						<?php do_action( 'byt_show_single_cruise_' . $tab['id'] . '_before' ); ?>
						<?php BYT_Theme_Utils::render_tab_extra_fields('cruise_extra_fields', $cruise_extra_fields, $tab['id'], $cruise_obj); ?>
						<?php do_action( 'byt_show_single_cruise_' . $tab['id'] . '_after' ); ?>
					</article>
				</section>
			<?php
				}
			}
		}	
		do_action( 'byt_show_single_cruise_tab_content_after' ); ?>
	</section>
	<!--//cruise content-->	
<?php get_sidebar('right-cruise'); ?>
<div class="step1_controls_holder" style="display:none">

	<div class="row calendar">
		<div class="f-item">
			<label><?php _e('Select cruise start date', 'bookyourtravel') ?></label>
			<div class="datepicker_holder"></div>
		</div>
	</div>
	<div class="row loading" id="datepicker_loading" style="display:none">
		<div class="ball"></div>
		<div class="ball1"></div>
	</div>			
	<div class="row dates_row">
		<div class="f-item">
			<label><?php _e('Cruise date', 'bookyourtravel') ?></label>
			<span id="start_date_span"></span>
			<input type="hidden" name="start_date" id="start_date" value="" />
		</div>
	</div>
	
	<div class="row twins price_row" style="display:none">
		<div class="f-item">
			<label for="booking_form_adults"><?php _e('Adults', 'bookyourtravel') ?></label>
			<select class="dynamic_control" id="booking_form_adults" name="booking_form_adults"></select>
		</div>
		<div class="f-item booking_form_children">
			<label for="booking_form_children"><?php _e('Children', 'bookyourtravel') ?></label>
			<select class="dynamic_control" id="booking_form_children" name="booking_form_children"></select>
		</div>
	</div>
	
	<div class="row price_row" style="display:none">
		<div class="f-item">
			<script>
				window.adultCountLabel = <?php echo json_encode(__('Adults', 'bookyourtravel')); ?>;
				window.pricePerAdultLabel = <?php echo json_encode(__('Price per adult', 'bookyourtravel')); ?>;
				window.childCountLabel = <?php echo json_encode(__('Children', 'bookyourtravel')); ?>;
				window.pricePerChildLabel = <?php echo json_encode(__('Price per child', 'bookyourtravel')); ?>;
				window.pricePerDayLabel = <?php echo json_encode(__('Total price', 'bookyourtravel')); ?>;
				<?php $total_price_label = __('Total price', 'bookyourtravel');	?>
				window.priceTotalLabel = <?php echo json_encode($total_price_label); ?>;
				window.dateLabel = <?php echo json_encode(__('Date', 'bookyourtravel')); ?>;
			</script>
			<table class="breakdown tablesorter responsive">
				<thead></thead>
				<tfoot></tfoot>
				<tbody></tbody>
			</table>
		</div>
	</div>

	<div class='booking-commands'>
	<?php
	BYT_Theme_Utils::render_link_button("#", "gradient-button book-cruise-reset", "book-cruise-rest", __('Reset', 'bookyourtravel'));
	BYT_Theme_Utils::render_link_button("#", "gradient-button book-cruise-next", "book-cruise-next", __('Proceed', 'bookyourtravel'));
	?>
	</div>
</div>
<?php
} // end if
get_footer(); 