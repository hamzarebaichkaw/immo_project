<?php

class BYT_Theme_Globals extends BYT_BaseSingleton {

	protected function __construct() {
        // our parent class might
        // contain shared code in its constructor
        parent::__construct();
    }

    public function init() {
	
    }
	
	public function get_current_language_code() {
		return substr(get_locale(), 0, 2);
	}
	
	public function show_self_catered_count_in_location_items() {
		return (int)of_get_option('show_self_catered_count_in_location_items', 1);
	}
	
	public function show_hotel_count_in_location_items() {
		return (int)of_get_option('show_hotel_count_in_location_items', 1);
	}
	
	public function show_cruise_count_in_location_items() {
		return (int)of_get_option('show_cruise_count_in_location_items', 0);
	}
	
	public function show_tour_count_in_location_items() {
		return (int)of_get_option('show_tour_count_in_location_items', 0);
	}

	public function show_car_rental_count_in_location_items() {
		return (int)of_get_option('show_car_rental_count_in_location_items', 0);
	}
	
	public function get_search_results_default_view() {
		return (int)of_get_option('search_results_default_view', 0);
	}
	
	public function enable_rtl() {
		return of_get_option('enable_rtl', false);
	}

	public function enable_accommodations() {
		return of_get_option('enable_accommodations', true);
	}
	
	public function enable_reviews() {
		return of_get_option('enable_reviews', 1);
	}
	
	public function enable_tours() {
		return of_get_option('enable_tours', true);
	}
	
	public function enable_cruises() {
		return of_get_option('enable_cruises', true);
	}
	
	public function enable_car_rentals() {
		return of_get_option('enable_car_rentals', true);
	}
	
	public function get_location_tabs() {
	
		global $default_location_tabs;
		$location_tabs = of_get_option('location_tabs');
		if (!is_array($location_tabs) || count($location_tabs) == 0 || count($location_tabs) < count($default_location_tabs))
			$location_tabs = $default_location_tabs;
		return $location_tabs;
		
	}
	
	public function get_tour_list_page_id() {

		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'page-tour-list.php'
		));

		$page = null;
		if (count($pages) > 0) {
			$page = $pages[0];
		}
		
		return isset($page) ? $page->ID : 0;
	}
	
	public function get_car_rental_list_page_id() {

		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'page-car_rental-list.php'
		));

		$page = null;
		if (count($pages) > 0) {
			$page = $pages[0];
		}
		
		return isset($page) ? $page->ID : 0;
	}
	
	public function get_cruise_list_page_id() {

		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'page-cruise-list.php'
		));

		$page = null;
		if (count($pages) > 0) {
			$page = $pages[0];
		}
		
		return isset($page) ? $page->ID : 0;
	}
	
	public function get_location_list_page_id() {

		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'page-location-list.php'
		));

		$page = null;
		if (count($pages) > 0) {
			$page = $pages[0];
		}
		
		return isset($page) ? $page->ID : 0;
	}
	
	public function get_accommodation_tabs() {
	
		global $default_accommodation_tabs;
		$accommodation_tabs = of_get_option('accommodation_tabs');
		if (!is_array($accommodation_tabs) || count($accommodation_tabs) == 0 || count($accommodation_tabs) < count($default_accommodation_tabs))
			$accommodation_tabs = $default_accommodation_tabs;
		return $accommodation_tabs;
		
	}
	
	public function get_tour_tabs() {
	
		global $default_tour_tabs;
		$tour_tabs = of_get_option('tour_tabs');
		if (!is_array($tour_tabs) || count($tour_tabs) == 0 || count($tour_tabs) < count($default_tour_tabs))
			$tour_tabs = $default_tour_tabs;
		return $tour_tabs;
		
	}
	
	public function get_cruise_tabs() {
	
		global $default_cruise_tabs;
		$cruise_tabs = of_get_option('cruise_tabs');
		if (!is_array($cruise_tabs) || count($cruise_tabs) == 0 || count($cruise_tabs) < count($default_cruise_tabs))
			$cruise_tabs = $default_cruise_tabs;
		return $cruise_tabs;
		
	}
	
	public function get_car_rental_tabs() {
	
		global $default_car_rental_tabs;
		$car_rental_tabs = of_get_option('car_rental_tabs');
		if (!is_array($car_rental_tabs) || count($car_rental_tabs) == 0 || count($car_rental_tabs) < count($default_car_rental_tabs))
			$car_rental_tabs = $default_car_rental_tabs;
		return $car_rental_tabs;
		
	}
	
	public function get_location_extra_fields() {
			
		global $default_location_extra_fields;
		$location_extra_fields = of_get_option('location_extra_fields');
		if (!is_array($location_extra_fields) || count($location_extra_fields) == 0)
			$location_extra_fields = $default_location_extra_fields;
	
		return $location_extra_fields;
	}
	
	public function get_accommodation_extra_fields() {
		
		global $default_accommodation_extra_fields;
		$accommodation_extra_fields = of_get_option('accommodation_extra_fields');
		if (!is_array($accommodation_extra_fields) || count($accommodation_extra_fields) == 0)
			$accommodation_extra_fields = $default_accommodation_extra_fields;
			
		return $accommodation_extra_fields;
	}
	
	public function get_tour_extra_fields() {
		
		global $default_tour_extra_fields;
		$tour_extra_fields = of_get_option('tour_extra_fields');
		if (!is_array($tour_extra_fields) || count($tour_extra_fields) == 0)
			$tour_extra_fields = $default_tour_extra_fields;
			
		return $tour_extra_fields;
	}
	
	public function get_cruise_extra_fields() {
		
		global $default_cruise_extra_fields;
		$cruise_extra_fields = of_get_option('cruise_extra_fields');
		if (!is_array($cruise_extra_fields) || count($cruise_extra_fields) == 0)
			$cruise_extra_fields = $default_cruise_extra_fields;
			
		return $cruise_extra_fields;
	}
	
	public function get_car_rental_extra_fields() {
		
		global $default_car_rental_extra_fields;
		$car_rental_extra_fields = of_get_option('car_rental_extra_fields');
		if (!is_array($car_rental_extra_fields) || count($car_rental_extra_fields) == 0)
			$car_rental_extra_fields = $default_car_rental_extra_fields;
			
		return $car_rental_extra_fields;
	}
	
	public function get_copyright_footer() {
		return of_get_option('copyright_footer', '');
	}
	
	public function get_price_decimal_places() {
		return (int)of_get_option('price_decimal_places', 0);
	}

	public function get_default_currency_symbol() {
		return of_get_option('default_currency_symbol', '$');
	}
	
	public function show_currency_symbol_after() {
		return (int)of_get_option('show_currency_symbol_after', 0);
	}	
	
	public function get_color_scheme_style_sheet() {
		return of_get_option('color_scheme_select', 'style');
	}
	
	public function get_accommodations_permalink_slug() {
		return of_get_option('accommodations_permalink_slug', 'hotels');
	}
	
	public function get_car_rentals_permalink_slug() {
		return of_get_option('car_rentals_permalink_slug', 'car-rentals');
	}
	
	public function get_cruises_permalink_slug() {
		return of_get_option('cruises_permalink_slug', 'cruises');
	}
	
	public function get_tours_permalink_slug() {
		return of_get_option('tours_permalink_slug', 'tours');
	}
	
	public function get_locations_permalink_slug() {
		return of_get_option('locations_permalink_slug', 'locations');
	}
	
	public function get_accommodations_archive_posts_per_page() {
		return of_get_option('accommodations_archive_posts_per_page', 12);
	}

	public function get_tours_archive_posts_per_page() {
		return of_get_option('tours_archive_posts_per_page', 12);
	}
	
	public function get_cruises_archive_posts_per_page() {
		return of_get_option('cruises_archive_posts_per_page', 12);
	}
	
	public function get_car_rentals_archive_posts_per_page() {
		return of_get_option('car_rentals_archive_posts_per_page', 12);
	}
	
	public function get_locations_archive_posts_per_page() {
		return of_get_option('locations_archive_posts_per_page', 12);
	}
	
	public function get_business_address_latitude() {
		return of_get_option('business_address_latitude', '');
	}
	
	public function get_business_address_longitude() {
		return of_get_option('business_address_longitude', '');
	}
	
	public function get_contact_phone_number() {
		return of_get_option('contact_phone_number', '');
	}
	
	public function get_contact_email() {
		return of_get_option('contact_email', '');
	}
	
	public function get_enc_key() {
		return preg_replace('{/$}', '', $_SERVER['SERVER_NAME']);
	}
	
	public function add_captcha_to_forms() {
		return (int)of_get_option('add_captcha_to_forms', 1);
	}
	
	public function get_theme_logo_src() {
	
		$logo_src = of_get_option( 'website_logo_upload', '' );

		if (empty($logo_src)) {
			if (empty($color_scheme_style_sheet)) 
				$logo_src = BYT_Theme_Utils::get_file_uri('/images/txt/logo.png');
			else if ($color_scheme_style_sheet == 'theme-strawberry')
				$logo_src = BYT_Theme_Utils::get_file_uri('/images/themes/strawberry/txt/logo.png');
			else if ($color_scheme_style_sheet == 'theme-black')
				$logo_src = BYT_Theme_Utils::get_file_uri('/images/themes/black/txt/logo.png');
			else if ($color_scheme_style_sheet == 'theme-blue')
				$logo_src = BYT_Theme_Utils::get_file_uri('/images/themes/blue/txt/logo.png');
			else if ($color_scheme_style_sheet == 'theme-orange')
				$logo_src = BYT_Theme_Utils::get_file_uri('/images/themes/orange/txt/logo.png');
			else if ($color_scheme_style_sheet == 'theme-pink')
				$logo_src = BYT_Theme_Utils::get_file_uri('/images/themes/pink/txt/logo.png');
			else if ($color_scheme_style_sheet == 'theme-yellow')
				$logo_src = BYT_Theme_Utils::get_file_uri('/images/themes/yellow/txt/logo.png');
			else if ($color_scheme_style_sheet == 'theme-navy')
				$logo_src = BYT_Theme_Utils::get_file_uri('/images/themes/navy/txt/logo.png');
			else 
				$logo_src = BYT_Theme_Utils::get_file_uri('/images/txt/logo.png');
		}
		
		return $logo_src;
	}
	
	public function get_site_url() {
		return site_url();
	}
	
	public function hide_header_ribbon () {
		return (int)of_get_option('hide_header_ribbon', 0);
	}

	public function override_wp_login() {
		return of_get_option('override_wp_login', 0);
	}
	
	public function get_price_range_bottom() {
		return of_get_option('price_range_bottom', '0');
	}
	
	public function get_price_range_increment() {
		return of_get_option('price_range_increment', '50');
	}

	public function get_price_range_count() {
		return of_get_option('price_range_count', '50');
	}
	
	public function search_only_available_properties() {
		return of_get_option('search_only_available_properties', '0');
	}
		
	public function get_search_results_posts_per_page() {
		return of_get_option('search_results_posts_per_page', 12);
	}
	
	public function get_custom_search_results_page_url() {
		$custom_search_results_page_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('redirect_to_search_results', ''));
		return get_permalink($custom_search_results_page_id);
	}
	public function get_contact_page_url() {
		$contact_page_url_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('contact_page_url', ''));
		return get_permalink($contact_page_url_id);
	}
	
	public function get_login_page_url() {
		$login_page_url_id = BYT_Theme_Utils::get_current_language_page_id( of_get_option('login_page_url', '') );
		return get_permalink($login_page_url_id);
	}
	
	public function get_redirect_to_after_login_page_url() {
		$redirect_to_after_login_id = BYT_Theme_Utils::get_current_language_page_id( of_get_option('redirect_to_after_login', '') );
		return get_permalink($redirect_to_after_login_id);
	}
	
	public function let_users_set_pass() {
		return of_get_option('let_users_set_pass', 0);
	}
	
	public function get_my_account_page_url() {
		$my_account_page_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('my_account_page', ''));
		return get_permalink($my_account_page_id);
	}

	public function get_redirect_to_after_logout_url() {
		$redirect_to_after_logout_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('redirect_to_after_logout', ''));
		return get_permalink($redirect_to_after_logout_id);
	}
	
	public function get_terms_page_url() {
		$terms_page_url_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('terms_page_url', ''));
		return get_permalink($terms_page_url_id);
	}
		
	public function get_register_page_url() {
		$register_page_url_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('register_page_url', ''));
		$register_page_url = get_permalink($register_page_url_id);		
		$override_wp_login = $this->override_wp_login();
		if (empty($register_page_url) || !$override_wp_login)
			$register_page_url = get_home_url() . '/wp-login.php?action=register';
		return $register_page_url;
	}
	
	public function get_reset_password_page_url() {
		$reset_password_page_url_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('reset_password_page_url', ''));
		$reset_password_page_url = get_permalink($reset_password_page_url_id);
		$override_wp_login = $this->override_wp_login();
		if (empty($reset_password_page_url) || !$override_wp_login)
			$reset_password_page_url = get_home_url() . '/wp-login.php?action=lostpassword';
		return $reset_password_page_url;
	}
	
	public function get_blog_posts_root_url() {
		return get_permalink( get_option( 'page_for_posts' ) );
	}
	
	public function get_submit_room_types_url() {
		$submit_room_types_url_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('submit_room_types_url', ''));
		return get_permalink($submit_room_types_url_id);	
	}
	
	public function get_submit_accommodations_url() {
		$submit_accommodations_url_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('submit_accommodations_url', ''));
		return get_permalink($submit_accommodations_url_id);
	}
	
	public function get_submit_accommodation_vacancies_url() {
		$submit_accommodation_vacancies_url_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('submit_accommodation_vacancies_url', ''));
		return get_permalink($submit_accommodation_vacancies_url_id);
	}
	
	public function get_list_user_room_types_url() {
		$list_user_room_types_url_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('list_user_room_types_url', ''));
		return get_permalink($list_user_room_types_url_id);
	}
	
	public function get_list_user_accommodations_url() {
		$list_user_accommodations_url_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('list_user_accommodations_url', ''));
		return get_permalink($list_user_accommodations_url_id);
	}
	
	public function get_list_user_accommodation_vacancies_url() {
		$list_user_accommodation_vacancies_url_id = BYT_Theme_Utils::get_current_language_page_id(of_get_option('list_user_accommodation_vacancies_url', ''));
		return get_permalink($list_user_accommodation_vacancies_url_id);
	}
	
	public function use_woocommerce_for_checkout() {
		$use_woocommerce_for_checkout = of_get_option('use_woocommerce_for_checkout', 0);
		$use_woocommerce_for_checkout = $use_woocommerce_for_checkout ? 1 : 0;
		return $use_woocommerce_for_checkout;
	}
	
	public function get_cart_page_url() {
	
		$cart_page_url = '';
		if (function_exists('wc_get_page_id') && BYT_Theme_Utils::is_woocommerce_active()) {
			$cart_page_id = wc_get_page_id( 'cart' );
			$cart_page_id = BYT_Theme_Utils::get_current_language_page_id($cart_page_id);
			$cart_page_url = get_permalink($cart_page_id);
		}
	
		return $cart_page_url;
	}
	
	public function frontpage_show_slider() {
		return of_get_option('frontpage_show_slider', '1');
	}
	
	public function get_homepage_slider() {
		return of_get_option('homepage_slider', '-1');
	}
}

global $byt_theme_globals;
// store the instance in a variable to be retrieved later and call init
$byt_theme_globals = BYT_Theme_Globals::get_instance();
$byt_theme_globals->init();