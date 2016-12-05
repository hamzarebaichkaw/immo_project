<?php

class BYT_Theme_Of_Default_Fields extends BYT_BaseSingleton {

	protected function __construct() {
	
        // our parent class might contain shared code in its constructor
        parent::__construct();
		
    }
	
    public function init() {

	}

	function get_default_tab_array($option_id) {

		global $default_accommodation_tabs, $default_tour_tabs, $default_car_rental_tabs, $default_location_tabs, $default_cruise_tabs;

		$tab_array = array();
		
		if ($option_id == 'accommodation_tabs') {
			$tab_array = $default_accommodation_tabs;
		} elseif ($option_id == 'tour_tabs') {
			$tab_array = $default_tour_tabs;
		} elseif ($option_id == 'car_rental_tabs') {
			$tab_array = $default_car_rental_tabs;
		} elseif ($option_id == 'location_tabs') {
			$tab_array = $default_location_tabs;
		} elseif ($option_id == 'cruise_tabs') {
			$tab_array = $default_cruise_tabs;
		}
		
		return $tab_array;
	}

	function get_default_review_fields_array($option_id) {
		
		global $default_accommodation_review_fields, $default_tour_review_fields, $default_cruise_review_fields;
		
		$default_values = array();
		
		if ($option_id == 'accommodation_review_fields') {
			$default_values = $default_accommodation_review_fields;
		} elseif ($option_id == 'tour_review_fields') {
			$default_values = $default_tour_review_fields;
		} elseif ($option_id == 'cruise_review_fields') {
			$default_values = $default_cruise_review_fields;
		}
		
		return $default_values;
	}
	
}

// store the instance in a variable to be retrieved later and call init
$byt_theme_of_default_fields = BYT_Theme_Of_Default_Fields::get_instance();
$byt_theme_of_default_fields->init();

global $repeatable_field_types;
$repeatable_field_types = array(
	'text' => __('Text field', 'bookyourtravel'),
	'textarea' => __('Text area field', 'bookyourtravel'),
	'image' => __('Image field', 'bookyourtravel')
);

$availability_label = __('Availability', 'bookyourtravel');
$things_to_do_label = __('Things to do', 'bookyourtravel');
$reviews_label = __('Reviews', 'bookyourtravel');
$location_label = __('Location', 'bookyourtravel');
$facilities_label = __('Facilities', 'bookyourtravel');
$description_label = __('Description', 'bookyourtravel');

// Accommodations
global $default_accommodation_tabs;
$default_accommodation_tabs = array(
	array('label' => $availability_label, 'id' => 'availability', 'hide' => 0),
	array('label' => $description_label, 'id' => 'description', 'hide' => 0),
	array('label' => $facilities_label, 'id' => 'facilities', 'hide' => 0),
	array('label' => $location_label, 'id' => 'location', 'hide' => 0),
	array('label' => $things_to_do_label, 'id' => 'things-to-do', 'hide' => 0),
	array('label' => $reviews_label, 'id' => 'reviews', 'hide' => 0)
);

$cancellation_prepayment_label = __('Cancellation / Prepayment', 'bookyourtravel');
$children_and_extra_beds_label = __('Children and extra beds', 'bookyourtravel');
$pets_label = __('Pets', 'bookyourtravel');
$accepted_credit_cards_label = __('Accepted credit cards', 'bookyourtravel');
$activities_label = __('Activities', 'bookyourtravel');
$internet_label = __('Internet', 'bookyourtravel');
$parking_label = __('Parking', 'bookyourtravel');

global $default_accommodation_extra_fields;
$default_accommodation_extra_fields = array(
	array('label' => $cancellation_prepayment_label, 'id' => 'cancellation_prepayment', 'type' => 'textarea', 'tab_id' => 'description', 'hide' => 0),
	array('label' => $children_and_extra_beds_label, 'id' => 'children_and_extra_beds', 'type' => 'textarea', 'tab_id' => 'description', 'hide' => 0),
	array('label' => $pets_label, 'id' => 'pets', 'type' => 'textarea', 'tab_id' => 'description', 'hide' => 0),
	array('label' => $accepted_credit_cards_label, 'id' => 'accepted_credit_cards', 'type' => 'textarea', 'tab_id' => 'description', 'hide' => 0),
	array('label' => $activities_label, 'id' => 'activities', 'type' => 'textarea', 'tab_id' => 'facilities', 'hide' => 0),
	array('label' => $internet_label, 'id' => 'internet', 'type' => 'textarea', 'tab_id' => 'facilities', 'hide' => 0),
	array('label' => $parking_label, 'id' => 'parking', 'type' => 'textarea', 'tab_id' => 'facilities', 'hide' => 0),
);

$description_label = __('Description', 'bookyourtravel');
$availability_label = __('Availability', 'bookyourtravel');
$location_label = __('Location', 'bookyourtravel');
$locations_label = __('Locations', 'bookyourtravel');
$reviews_label = __('Reviews', 'bookyourtravel');

// Tours
global $default_tour_tabs;
$default_tour_tabs = array(
	array('label' => $description_label, 'id' => 'description', 'hide' => 0),
	array('label' => $availability_label, 'id' => 'availability', 'hide' => 0),
	array('label' => $location_label, 'id' => 'location', 'hide' => 0),
	array('label' => $reviews_label, 'id' => 'reviews', 'hide' => 0)
);

$activities_label = __('Activities', 'bookyourtravel');

global $default_tour_extra_fields;
$default_tour_extra_fields = array(
	array('label' => $activities_label, 'id' => 'activities', 'type' => 'textarea', 'tab_id' => 'description', 'hide' => 0),
);

// Car rentals
global $default_car_rental_tabs;
$default_car_rental_tabs = array(
	array('label' => $description_label, 'id' => 'description', 'hide' => 0)
);

$co2_emission_label = __('CO2 emission', 'bookyourtravel');

global $default_car_rental_extra_fields;
$default_car_rental_extra_fields = array(
	array('label' => $co2_emission_label, 'id' => 'co2_emission', 'type' => 'text', 'tab_id' => 'description', 'hide' => 0)
);

$general_info_label = __('General information', 'bookyourtravel');
$sports_and_nature_label = __('Sports &amp; nature', 'bookyourtravel');
$nightlife_label = __('Nightlife', 'bookyourtravel');
$culture_and_history_label = __('Culture and history', 'bookyourtravel');
$hotels_label = __('Hotels', 'bookyourtravel');
$self_catered_label = __('Self catered', 'bookyourtravel');
$tours_label = __('Tours', 'bookyourtravel');
$cruises_label = __('Cruises', 'bookyourtravel');
$car_rentals_label = __('Car rentals', 'bookyourtravel');

// Locations
global $default_location_tabs;
$default_location_tabs = array(
	array('label' => $general_info_label, 'id' => 'general_info', 'hide' => 0),
	array('label' => $sports_and_nature_label, 'id' => 'sports_and_nature', 'hide' => 0),
	array('label' => $nightlife_label, 'id' => 'nightlife', 'hide' => 0),
	array('label' => $culture_and_history_label, 'id' => 'culture', 'hide' => 0),
	array('label' => $hotels_label, 'id' => 'hotels', 'hide' => 0),
	array('label' => $self_catered_label, 'id' => 'self-catered', 'hide' => 0),
	array('label' => $tours_label, 'id' => 'tours', 'hide' => 0),
	array('label' => $cruises_label, 'id' => 'cruises', 'hide' => 0),
	array('label' => $car_rentals_label, 'id' => 'car_rentals', 'hide' => 0)
);

$sports_and_nature_label = __('Sports &amp; nature', 'bookyourtravel');
$sports_and_nature_image_label = __('Sports and nature image', 'bookyourtravel');
$nightlife_info_label = __('Nightlife info', 'bookyourtravel');
$nightlife_info_image_label = __('Nightlife image', 'bookyourtravel');
$culture_and_history_info_label = __('Culture and history info', 'bookyourtravel');
$culture_and_history_image_label = __('Culture and history image', 'bookyourtravel');
$visa_requirements_label = __('Visa requirements', 'bookyourtravel');
$languages_spoken_label = __('Languages spoken', 'bookyourtravel');
$currency_used_label = __('Currency used', 'bookyourtravel');
$area_label = __('Area (km2)', 'bookyourtravel');

global $default_location_extra_fields;
$default_location_extra_fields = array(
	array('label' => $sports_and_nature_label, 'id' => 'sports_and_nature', 'type' => 'textarea', 'tab_id' => 'sports_and_nature', 'hide' => 0),
	array('label' => $sports_and_nature_image_label, 'id' => 'sports_and_nature_image', 'type' => 'image', 'tab_id' => 'sports_and_nature', 'hide' => 0),
	array('label' => $nightlife_info_label, 'id' => 'nightlife', 'type' => 'textarea', 'tab_id' => 'nightlife', 'hide' => 0),
	array('label' => $nightlife_info_image_label, 'id' => 'nightlife_image', 'type' => 'image', 'tab_id' => 'nightlife', 'hide' => 0),
	array('label' => $culture_and_history_info_label, 'id' => 'culture_and_history', 'type' => 'textarea', 'tab_id' => 'culture', 'hide' => 0),
	array('label' => $culture_and_history_image_label, 'id' => 'culture_and_history_image', 'type' => 'image', 'tab_id' => 'culture', 'hide' => 0),
	array('label' => $visa_requirements_label, 'id' => 'visa_requirements', 'type' => 'textarea', 'tab_id' => 'general_info', 'hide' => 0),
	array('label' => $languages_spoken_label, 'id' => 'languages_spoken', 'type' => 'text', 'tab_id' => 'general_info', 'hide' => 0),
	array('label' => $currency_used_label, 'id' => 'currency', 'type' => 'text', 'tab_id' => 'general_info', 'hide' => 0),
	array('label' => $area_label, 'id' => 'area', 'type' => 'text', 'tab_id' => 'general_info', 'hide' => 0),
);

// Cruises
global $default_cruise_tabs;
$default_cruise_tabs = array(
	array('label' => $description_label, 'id' => 'description', 'hide' => 0),
	array('label' => $availability_label, 'id' => 'availability', 'hide' => 0),
	array('label' => $locations_label, 'id' => 'locations', 'hide' => 0),
	array('label' => $facilities_label, 'id' => 'facilities', 'hide' => 0),
	array('label' => $reviews_label, 'id' => 'reviews', 'hide' => 0)
);

$arrival_time_label = __('Arrival time', 'bookyourtravel');
$departure_time_label = __('Departure time', 'bookyourtravel');

global $default_cruise_extra_fields;
$default_cruise_extra_fields = array(
	array('label' => $cancellation_prepayment_label, 'id' => 'cancellation_prepayment', 'type' => 'textarea', 'tab_id' => 'description', 'hide' => 0),
	array('label' => $pets_label, 'id' => 'pets', 'type' => 'textarea', 'tab_id' => 'description', 'hide' => 0),
	array('label' => $accepted_credit_cards_label, 'id' => 'accepted_credit_cards', 'type' => 'textarea', 'tab_id' => 'description', 'hide' => 0),
	array('label' => $activities_label, 'id' => 'activities', 'type' => 'textarea', 'tab_id' => 'facilities', 'hide' => 0),
	array('label' => $internet_label, 'id' => 'internet', 'type' => 'textarea', 'tab_id' => 'facilities', 'hide' => 0),
);

$cleanlines_label = __('Cleanliness', 'bookyourtravel');
$comfort_label = __('Comfort', 'bookyourtravel');
$staff_label = __('Staff', 'bookyourtravel');
$services_label = __('Services', 'bookyourtravel');
$value_for_money_label = __('Value for money', 'bookyourtravel');
$sleep_quality_label = __('Sleep quality', 'bookyourtravel');

global $default_accommodation_review_fields;
$default_accommodation_review_fields = array(
	array('label' => $cleanlines_label, 'id' => 'review_cleanliness', 'post_type' => 'accommodation', 'hide' => 0),
	array('label' => $comfort_label, 'id' => 'review_comfort', 'post_type' => 'accommodation', 'hide' => 0),
	array('label' => $location_label, 'id' => 'review_location', 'post_type' => 'accommodation', 'hide' => 0),
	array('label' => $staff_label, 'id' => 'review_staff', 'post_type' => 'accommodation', 'hide' => 0),
	array('label' => $services_label, 'id' => 'review_services', 'post_type' => 'accommodation', 'hide' => 0),
	array('label' => $value_for_money_label, 'id' => 'review_value_for_money', 'post_type' => 'accommodation', 'hide' => 0),
	array('label' => $sleep_quality_label, 'id' => 'review_sleep_quality', 'post_type' => 'accommodation', 'hide' => 0),
);

$overall_label = __('Overall', 'bookyourtravel');
$accommodation_label = __('Accommodation', 'bookyourtravel');
$transport_label = __('Transport', 'bookyourtravel');
$meals_label = __('Meals', 'bookyourtravel');
$guide_label = __('Guide', 'bookyourtravel');
$program_accuracy_label = __('Program accuracy', 'bookyourtravel');

global $default_tour_review_fields;
$default_tour_review_fields = array(
	array('label' => $overall_label, 'id' => 'review_overall', 'post_type' => 'tour', 'hide' => 0),
	array('label' => $accommodation_label, 'id' => 'review_accommodation', 'post_type' => 'tour', 'hide' => 0),
	array('label' => $transport_label, 'id' => 'review_transport', 'post_type' => 'tour', 'hide' => 0),
	array('label' => $meals_label, 'id' => 'review_meals', 'post_type' => 'tour', 'hide' => 0),
	array('label' => $guide_label, 'id' => 'review_guide', 'post_type' => 'tour', 'hide' => 0),
	array('label' => $value_for_money_label, 'id' => 'review_value_for_money', 'post_type' => 'tour', 'hide' => 0),
	array('label' => $program_accuracy_label, 'id' => 'review_program_accuracy', 'post_type' => 'tour', 'hide' => 0),
);

$entertainment_label = __('Entertainment', 'bookyourtravel');

global $default_cruise_review_fields;
$default_cruise_review_fields = array(
	array('label' => $overall_label, 'id' => 'review_overall', 'post_type' => 'cruise', 'hide' => 0),
	array('label' => $accommodation_label, 'id' => 'review_accommodation', 'post_type' => 'cruise', 'hide' => 0),
	array('label' => $transport_label, 'id' => 'review_transport', 'post_type' => 'cruise', 'hide' => 0),
	array('label' => $meals_label, 'id' => 'review_meals', 'post_type' => 'cruise', 'hide' => 0),
	array('label' => $guide_label, 'id' => 'review_guide', 'post_type' => 'cruise', 'hide' => 0),
	array('label' => $value_for_money_label, 'id' => 'review_value_for_money', 'post_type' => 'cruise', 'hide' => 0),
	array('label' => $entertainment_label, 'id' => 'review_entertainment', 'post_type' => 'cruise', 'hide' => 0),
	array('label' => $program_accuracy_label, 'id' => 'review_program_accuracy', 'post_type' => 'cruise', 'hide' => 0),
);