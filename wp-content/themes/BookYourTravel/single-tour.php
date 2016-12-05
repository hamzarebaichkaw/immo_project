<?php 

get_header();  
BYT_Theme_Utils::breadcrumbs();
get_sidebar('under-header');	

global $post, $byt_theme_globals, $tour_date_from, $current_user, $tour_obj, $entity_obj, $default_tour_tabs, $score_out_of_10, $byt_tours_post_type, $byt_theme_of_custom;

$enable_reviews = $byt_theme_globals->enable_reviews();
$enable_tours = $byt_theme_globals->enable_tours();
$price_decimal_places = $byt_theme_globals->get_price_decimal_places();
$tour_extra_fields = $byt_theme_globals->get_tour_extra_fields();
$tab_array = $byt_theme_globals->get_tour_tabs();

$price_decimal_places = $byt_theme_globals->get_price_decimal_places();
$default_currency_symbol = $byt_theme_globals->get_default_currency_symbol();
$show_currency_symbol_after = $byt_theme_globals->show_currency_symbol_after();

if ( have_posts() ) {

	the_post();
	
	$tour_obj = new byt_tour($post);
	$entity_obj = $tour_obj;
	$tour_map_code = $tour_obj->get_custom_field( 'map_code' );
	$tour_location = $tour_obj->get_location();
	$tour_location_title = '';
	if ($tour_location)
		$tour_location_title = $tour_location->get_title();

	$tour_is_reservation_only = $tour_obj->get_is_reservation_only();
		
	$tour_date_from = date('Y-m-d', strtotime("+0 day", time()));
	$tour_date_from_year = date('Y', strtotime("+0 day", time()));
	$tour_date_from_month = date('n', strtotime("+0 day", time()));
?>
	<script>
		window.postType = 'tour';
	</script>
<?php	
	if ($enable_reviews) {
		get_template_part('includes/parts/review', 'form'); 
	}
	get_template_part('includes/parts/inquiry', 'form');
?>
<!--tour three-fourth content-->
<section class="three-fourth">
	<?php
	get_template_part('includes/parts/tour', 'booking-form');
	get_template_part('includes/parts/tour', 'confirmation-form');
	?>	
	<script>
		window.bookingFormStartDateError = <?php echo json_encode(__('Please select a valid start date!', 'bookyourtravel')); ?>;
		window.startDate = null;
		window.formSingleError = <?php echo json_encode(__('You failed to provide 1 field. It has been highlighted below.', 'bookyourtravel')); ?>;
		window.formMultipleError = <?php echo json_encode(__('You failed to provide {0} fields. They have been highlighted below.', 'bookyourtravel'));  ?>;
		window.tourId = <?php echo $tour_obj->get_id(); ?>;
		window.tourIsPricePerGroup = <?php echo $tour_obj->get_is_price_per_group(); ?>;
		window.tourDateFrom = <?php echo json_encode($tour_date_from); ?>;
		window.tourTitle = <?php echo json_encode($tour_obj->get_title()); ?>;
		window.currentMonth = <?php echo date('n'); ?>;
		window.currentYear = <?php echo date('Y'); ?>;
		window.currentDay = <?php echo date('j'); ?>;
		window.tourIsReservationOnly = <?php echo $tour_is_reservation_only; ?>;
	</script>
	<?php $tour_obj->render_image_gallery(); ?>
	<!--inner navigation-->
	<nav class="inner-nav">
		<ul>
			<?php
			do_action( 'byt_show_single_tour_tab_items_before' );
			$first_display_tab = '';			
			$i = 0;
			if (is_array($tab_array) && count($tab_array) > 0) {
				foreach ($tab_array as $tab) {
					if (!isset($tab['hide']) || $tab['hide'] != '1') {
					
						$tab_label = '';
						if (isset($tab['label'])) {
							$tab_label = $tab['label'];
							$tab_label = $byt_theme_of_custom->get_translated_dynamic_string($byt_theme_of_custom->get_option_id_context('tour_tabs') . ' ' . $tab['label'], $tab_label);
						}
					
						if($i==0)
							$first_display_tab = $tab['id'];
						if ($tab['id'] == 'reviews' && $enable_reviews) {
							BYT_Theme_Utils::render_tab("tour", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab_label . '">' . $tab_label . '</a>');
						} elseif ($tab['id'] == 'location' && !empty($tour_map_code)) {
							BYT_Theme_Utils::render_tab("tour", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab_label . '">' . $tab_label . '</a>');
						} elseif ($tab['id'] == 'description' || $tab['id'] == 'availability') {
							BYT_Theme_Utils::render_tab("tour", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab_label . '">' . $tab_label . '</a>');
						} else {
							$all_empty_fields = BYT_Theme_Utils::are_tab_fields_empty('tour_extra_fields', $tour_extra_fields, $tab['id'], $tour_obj);
							
							if (!$all_empty_fields) {
								BYT_Theme_Utils::render_tab("tour", $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab_label . '">' . $tab_label . '</a>');
							}
						}
						
						$i++;
					}
				}
			} 				
			do_action( 'byt_show_single_tour_tab_items_after' ); 
			?>
		</ul>
	</nav>
	<!--//inner navigation-->
	<?php do_action( 'byt_show_single_tour_tab_content_before' ); ?>
	<!--description-->
	<section id="description" class="tab-content <?php echo $first_display_tab == 'description' ? 'initial' : ''; ?>">
		<article>
			<?php do_action( 'byt_show_single_tour_description_before' ); ?>
			<?php BYT_Theme_Utils::render_field("text-wrap", "", "", $tour_obj->get_description(), __('General', 'bookyourtravel')); ?>
			<?php BYT_Theme_Utils::render_tab_extra_fields('tour_extra_fields', $tour_extra_fields, 'description', $tour_obj); ?>
			<?php do_action( 'byt_show_single_tour_description_after' ); ?>
		</article>
	</section>
	<!--//description-->
	<!--availability-->
	<section id="availability" class="tab-content <?php echo $first_display_tab == 'availability' ? 'initial' : ''; ?>">
		<article>
			<?php do_action( 'byt_show_single_tour_availability_before' ); ?>
			<h1><?php _e('Available departures', 'bookyourtravel'); ?></h1>
			<?php BYT_Theme_Utils::render_field("text-wrap", "", "", $tour_obj->get_custom_field('availability_text'), '', false, true); ?>
			<form id="launch-tour-booking" action="#" method="POST">
				<div class="text-wrap">
					<?php 
					
					if ($tour_obj->get_type_is_repeated() == 1) {
						echo __('<p>This is a daily tour.</p>', 'bookyourtravel'); 
					} else if ($tour_obj->get_type_is_repeated() == 2) {
						echo __('<p>This tour is repeated every weekday (working day).</p>', 'bookyourtravel'); 
					} else if ($tour_obj->get_type_is_repeated() == 3) {
						echo sprintf(__('<p>This tour is repeated every week on a %s.</p>', 'bookyourtravel'), $tour_obj->get_type_day_of_week_day()); 
					}
					
					$type_day_of_week_index = $tour_obj->get_type_day_of_week_index();
					$schedule_entries = $byt_tours_post_type->list_available_tour_schedule_entries($tour_obj->get_id(), $tour_date_from, 0, 0, $tour_obj->get_type_is_repeated(), $type_day_of_week_index);
					if (count($schedule_entries) > 0) { ?>
						<div class="step1_controls">
						
							<div class="row calendar">
								<div class="f-item">
									<label><?php _e('Select tour date', 'bookyourtravel') ?></label>
									<div class="tour_schedule_datepicker"></div>
								</div>
							</div>
							<div class="row loading" id="datepicker_loading" style="display:none">
								<div class="ball"></div>
								<div class="ball1"></div>
							</div>	
							<div class="row dates_row" style="display:none">
								<div class="f-item">
									<label><?php _e('Tour date', 'bookyourtravel') ?></label>
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
						
						</div>
					<?php 
						echo '<div class="booking-commands">';
						BYT_Theme_Utils::render_link_button("#", "gradient-button book-tour-reset", "book-tour-rest", __('Reset', 'bookyourtravel'));
						BYT_Theme_Utils::render_link_button("#", "clearfix gradient-button book-tour-proceed", "book-tour", __('Proceed', 'bookyourtravel'));
						echo '</div>';
					} else { 
						echo __('Unfortunately, no places are available on this tour at the moment', 'bookyourtravel');			
					}
					?>
				</div>
			</form>
			<?php BYT_Theme_Utils::render_tab_extra_fields('tour_extra_fields', $tour_extra_fields, 'availability', $tour_obj); ?>
			<?php do_action( 'byt_show_single_tour_availability_after' ); ?>
		</article>
	</section>
	<!--//availability-->
		
	<?php if (!empty($tour_map_code)) { ?>
	<!--location-->
	<section id="location" class="tab-content <?php echo $first_display_tab == 'location' ? 'initial' : ''; ?>">
		<article>
			<?php do_action( 'byt_show_single_tour_map_before' ); ?>
			<!--map-->
			<div class="gmap"><?php echo $tour_map_code; ?></div>
			<!--//map-->
			<?php BYT_Theme_Utils::render_tab_extra_fields('tour_extra_fields', $tour_extra_fields, 'location', $tour_obj); ?>
			<?php do_action( 'byt_show_single_tour_map_after' ); ?>
		</article>
	</section>
	<!--//location-->
	<?php } // endif (!empty($tour_map_code)) ?>
	<?php if ($enable_reviews) { ?>
	<!--reviews-->
	<section id="reviews" class="tab-content <?php echo $first_display_tab == 'review' ? 'initial' : ''; ?>">
		<?php 
		do_action( 'byt_show_single_tour_reviews_before' );
		get_template_part('includes/parts/review', 'item'); 
		BYT_Theme_Utils::render_tab_extra_fields('tour_extra_fields', $tour_extra_fields, 'reviews', $tour_obj); 
		do_action( 'byt_show_single_tour_reviews_after' ); 
		?>
	</section>
	<!--//reviews-->
	<?php } // if ($enable_reviews) ?>
	<?php
	foreach ($tab_array as $tab) {
		if (count(BYT_Theme_Utils::custom_array_search($default_tour_tabs, 'id', $tab['id'])) == 0) {
			$all_empty_fields = BYT_Theme_Utils::are_tab_fields_empty('tour_extra_fields', $tour_extra_fields, $tab['id'], $tour_obj);
			
			if (!$all_empty_fields) {
		?>
			<section id="<?php echo esc_attr($tab['id']); ?>" class="tab-content <?php echo ($first_display_tab == $tab['id'] ? 'initial' : ''); ?>">
				<article>
					<?php do_action( 'byt_show_single_tour_' . $tab['id'] . '_before' ); ?>
					<?php BYT_Theme_Utils::render_tab_extra_fields('tour_extra_fields', $tour_extra_fields, $tab['id'], $tour_obj); ?>
					<?php do_action( 'byt_show_single_tour_' . $tab['id'] . '_after' ); ?>
				</article>
			</section>
		<?php
			}
		}
	}	
	do_action( 'byt_show_single_tour_tab_content_after' ); ?>
</section>
<!--//tour content-->	
<?php
	get_sidebar('right-tour'); 
} // end if
get_footer(); 