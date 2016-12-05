<?php 

get_header();  
BYT_Theme_Utils::breadcrumbs();
get_sidebar('under-header');

global $post, $byt_theme_globals, $current_user, $car_rental_obj, $entity_obj, $default_car_rental_tabs, $score_out_of_10, $byt_car_rentals_post_type, $byt_theme_of_custom;

$enable_reviews = $byt_theme_globals->enable_reviews();
$enable_cruises = $byt_theme_globals->enable_cruises();
$car_rental_extra_fields = $byt_theme_globals->get_car_rental_extra_fields();
$tab_array = $byt_theme_globals->get_car_rental_tabs();
$price_decimal_places = $byt_theme_globals->get_price_decimal_places();
$default_currency_symbol = $byt_theme_globals->get_default_currency_symbol();
$show_currency_symbol_after = $byt_theme_globals->show_currency_symbol_after();
	
if ( have_posts() ) {

	the_post();
	$car_rental_obj = new byt_car_rental($post);
	$entity_obj = $car_rental_obj;
	
	$price_per_day = $car_rental_obj->get_custom_field('price_per_day');
	
	$car_rental_location = $car_rental_obj->get_location();
	$pick_up_location_title = '';
	if ($car_rental_location)
		$pick_up_location_title = $car_rental_location->get_title();
?>
	<script>
		window.postType = 'car_rental';
	</script>
<?php		
	get_template_part('includes/parts/inquiry', 'form');
?>
	<!--car rental three-fourth content-->
	<section class="three-fourth">
		<?php	
		get_template_part('includes/parts/car_rental', 'booking-form');
		get_template_part('includes/parts/car_rental', 'confirmation-form');	
		?>	
		<script>	
			window.carRentalId = <?php echo $car_rental_obj->get_id(); ?>;
			window.formSingleError = <?php echo json_encode(__('You failed to provide 1 field. It has been highlighted below.', 'bookyourtravel')); ?>;
			window.formMultipleError = <?php echo json_encode(__('You failed to provide {0} fields. They have been highlighted below.', 'bookyourtravel'));  ?>;
			window.carRentalPrice = <?php echo $price_per_day; ?>;
			window.carRentalTitle = <?php echo json_encode($car_rental_obj->get_title()); ?>;
			window.carRentalCarType = <?php echo json_encode($car_rental_obj->get_type_name()); ?>;
			window.carRentalPickUp = <?php echo json_encode($pick_up_location_title); ?>;
			window.currentMonth = <?php echo date('n'); ?>;
			window.currentYear = <?php echo date('Y'); ?>;
		</script>
		<?php $car_rental_obj->render_image_gallery(); ?>
		<!--inner navigation-->
		<nav class="inner-nav">
			<ul>
				<?php do_action( 'byt_show_single_car_rental_tab_items_before' ); ?>
				<?php
				$first_display_tab = '';			
				$i = 0;
				if (is_array($tab_array) && count($tab_array) > 0) {
					foreach ($tab_array as $tab) {
					
						if (!isset($tab['hide']) || $tab['hide'] != '1') {
					
							$tab_label = '';
							if (isset($tab['label'])) {
								$tab_label = $tab['label'];
								$tab_label = $byt_theme_of_custom->get_translated_dynamic_string($byt_theme_of_custom->get_option_id_context('car_rental_tabs') . ' ' . $tab['label'], $tab_label);
							}
						
							if($i==0)
								$first_display_tab = $tab['id'];
								
							BYT_Theme_Utils::render_tab('car_rental', $tab['id'], '',  '<a href="#' . $tab['id'] . '" title="' . $tab_label . '">' . $tab_label . '</a>');

							$i++;
						}
					}
				} 	
				?>
				<?php do_action( 'byt_show_single_car_rental_tab_items_after' ); ?>
			</ul>
		</nav>
		<!--//inner navigation-->
		<?php do_action( 'byt_show_single_car_rental_tab_content_before' ); ?>
		<!--description-->
		<section id="description" class="tab-content <?php echo $first_display_tab == 'description' ? 'initial' : ''; ?>">
			<article>
				<h1><?php echo $car_rental_obj->get_title(); ?></h1>
				<?php BYT_Theme_Utils::render_field("text-wrap", "", "", $car_rental_obj->get_description()); ?>
				<div class="text-wrap">
				<?php BYT_Theme_Utils::render_field("location", "", __('Location', 'bookyourtravel'), $pick_up_location_title, '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("car_type", "", __('Car type', 'bookyourtravel'), $car_rental_obj->get_type_name(), '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("max_people", "", __('Max people', 'bookyourtravel'), $car_rental_obj->get_custom_field('max_count'), '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("door_count", "", __('Door count', 'bookyourtravel'), $car_rental_obj->get_custom_field('number_of_doors'), '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("min_age", "", __('Minimum driver age', 'bookyourtravel'), $car_rental_obj->get_custom_field('min_age'), '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("transmission", "", __('Transmission', 'bookyourtravel'), ($car_rental_obj->get_custom_field('transmission_type') == 'manual' ? __('Manual', 'bookyourtravel') : __('Automatic', 'bookyourtravel')), '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("air_conditioned", "", __('Air-conditioned?', 'bookyourtravel'), ($car_rental_obj->get_custom_field('is_air_conditioned') ? __('Yes', 'bookyourtravel') : __('No', 'bookyourtravel')), '', false, true); ?>
				<?php BYT_Theme_Utils::render_field("unlimited_mileage", "", __('Unlimited mileage?', 'bookyourtravel'), ($car_rental_obj->get_custom_field('is_unlimited_mileage') ? __('Yes', 'bookyourtravel') : __('No', 'bookyourtravel')), '', false, true); ?>
				<?php BYT_Theme_Utils::render_tab_extra_fields('car_rental_extra_fields', $car_rental_extra_fields, 'description', $car_rental_obj, '', false, true); ?>
				</div>
				<?php BYT_Theme_Utils::render_link_button("#", "clearfix gradient-button book_car_rental", "", __('Book now', 'bookyourtravel')); ?>				
			</article>
		</section>
		<!--//description-->
		<?php
		foreach ($tab_array as $tab) {
			if (count(BYT_Theme_Utils::custom_array_search($default_car_rental_tabs, 'id', $tab['id'])) == 0) {
			
				$all_empty_fields = BYT_Theme_Utils::are_tab_fields_empty('car_rental_extra_fields', $car_rental_extra_fields, $tab['id'], $car_rental_obj);
				
				if (!$all_empty_fields) {

			?>
				<section id="<?php echo esc_attr($tab['id']); ?>" class="tab-content <?php echo ($first_display_tab == $tab['id'] ? 'initial' : ''); ?>">
					<article>
						<?php do_action( 'byt_show_single_car_rental_' . $tab['id'] . '_before' ); ?>
						<?php BYT_Theme_Utils::render_tab_extra_fields('car_rental_extra_fields', $car_rental_extra_fields, $tab['id'], $car_rental_obj); ?>
						<?php do_action( 'byt_show_single_car_rental_' . $tab['id'] . '_after' ); ?>
					</article>
				</section>
			<?php
				}
			}
		}	
		?>
		<?php do_action( 'byt_show_single_car_rental_tab_content_after' ); ?>
	</section>
	<!--//car rental content-->	
<?php
	get_sidebar('right-car_rental'); 
} // end if
get_footer(); 