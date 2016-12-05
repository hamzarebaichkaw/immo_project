<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */
 
function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$byt_settings = get_option( 'optionsframework' );
	$byt_settings['id'] = $themename;
	update_option( 'optionsframework', $byt_settings );
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'bookyourtravel'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */
 
function optionsframework_options() {

	$color_scheme_array = array(
		'' => __('Default', 'bookyourtravel'),
		'theme-black' => __('Black', 'bookyourtravel'),
		'theme-blue' => __('Blue', 'bookyourtravel'),
		'theme-orange' => __('Orange', 'bookyourtravel'),
		'theme-pink' => __('Pink', 'bookyourtravel'),
		'theme-purple' => __('Purple', 'bookyourtravel'),
		'theme-strawberry' => __('Strawberry', 'bookyourtravel'),
		'theme-yellow' => __('Yellow', 'bookyourtravel'),
		'theme-navy' => __('Navy', 'bookyourtravel'),
	);
		
	$pages = get_pages(); 
	$pages_array = array();
	$pages_array[0] = __('Select page', 'bookyourtravel');
	foreach ( $pages as $page ) {
		$pages_array[$page->ID] = $page->post_title;
	}
	
	$price_decimals_array = array(
		'0' => __('Zero (e.g. $200)', 'bookyourtravel'),
		'1' => __('One  (e.g. $200.0)', 'bookyourtravel'),
		'2' => __('Two (e.g. $200.00)', 'bookyourtravel'),
	);
	
	$search_results_view_array = array(
		'0' => __('Grid view', 'bookyourtravel'),
		'1' => __('List view', 'bookyourtravel'),
	);	
	
	$options = array();

	$options[] = array(
		'name' => __('General Settings', 'bookyourtravel'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('Website logo', 'bookyourtravel'),
		'desc' => __('Upload your website logo to go in place of default theme logo.', 'bookyourtravel'),
		'id' => 'website_logo_upload',
		'type' => 'upload');
		
	$options[] = array(
		'name' => __('Select color scheme', 'bookyourtravel'),
		'desc' => __('Select website color scheme.', 'bookyourtravel'),
		'id' => 'color_scheme_select',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $color_scheme_array);
		
	$options[] = array(
		'name' => __('Company name', 'bookyourtravel'),
		'desc' => __('Company name displayed on the contact us page.', 'bookyourtravel'),
		'id' => 'contact_company_name',
		'std' => 'Book Your Travel LLC',
		'class' => 'mini',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Contact phone number', 'bookyourtravel'),
		'desc' => __('Contact phone number displayed on the site.', 'bookyourtravel'),
		'id' => 'contact_phone_number',
		'std' => '1- 555 - 555 - 555',
		'class' => 'mini',
		'type' => 'text');

	$options[] = array(
		'name' => __('Contact address street', 'bookyourtravel'),
		'desc' => __('Contact address street displayed on the contact us page.', 'bookyourtravel'),
		'id' => 'contact_address_street',
		'std' => '1400 Pennsylvania Ave',
		'class' => 'mini',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Contact address city', 'bookyourtravel'),
		'desc' => __('Contact address city displayed on the contact us page.', 'bookyourtravel'),
		'id' => 'contact_address_city',
		'std' => 'Washington DC',
		'class' => 'mini',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Contact address country', 'bookyourtravel'),
		'desc' => __('Contact address country displayed on the contact us page.', 'bookyourtravel'),
		'id' => 'contact_address_country',
		'std' => 'USA',
		'class' => 'mini',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Contact email', 'bookyourtravel'),
		'desc' => __('Contact email displayed on the contact us page.', 'bookyourtravel'),
		'id' => 'contact_email',
		'std' => 'info at bookyourtravel',
		'class' => 'mini',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Business address latitude', 'bookyourtravel'),
		'desc' => __('Enter your business address latitude to use for contact form map', 'bookyourtravel'),
		'id' => 'business_address_latitude',
		'std' => '49.47216',
		'class' => 'mini',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Business address longitude', 'bookyourtravel'),
		'desc' => __('Enter your business address longitude to use for contact form map', 'bookyourtravel'),
		'id' => 'business_address_longitude',
		'std' => '-123.76307',
		'class' => 'mini',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Footer copyright notice', 'bookyourtravel'),
		'desc' => __('Copyright notice in footer.', 'bookyourtravel'),
		'id' => 'copyright_footer',
		'std' => '&copy; bookyourtravel.com 2013. All rights reserved.',
		'type' => 'text');

	$options[] = array(
		'name' => __('Configuration Settings', 'bookyourtravel'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('Price decimal places', 'bookyourtravel'),
		'desc' => __('Number of decimal places to show for prices', 'bookyourtravel'),
		'id' => 'price_decimal_places',
		'std' => '0',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $price_decimals_array);
		
	$options[] = array(
		'name' => __('Default currency symbol', 'bookyourtravel'),
		'desc' => __('What is your default currency symbol', 'bookyourtravel'),
		'id' => 'default_currency_symbol',
		'std' => '$',
		'class' => 'mini', //mini, tiny, small
		'type' => 'text');

	$options[] = array(
		'name' => __('Show currency symbol after price?', 'bookyourtravel'),
		'desc' => __('If this option is checked, currency symbol will show up after the price, instead of before (e.g. 150 $ instead of $150).', 'bookyourtravel'),
		'id' => 'show_currency_symbol_after',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('Hide header ribbon', 'bookyourtravel'),
		'desc' => __('Hide the header ribbon (with my account, currencies, languages etc)', 'bookyourtravel'),
		'id' => 'hide_header_ribbon',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('Enable RTL', 'bookyourtravel'),
		'desc' => __('Enable right-to-left support', 'bookyourtravel'),
		'id' => 'enable_rtl',
		'std' => '0',
		'type' => 'checkbox');
		
	if (BYT_Theme_Utils::is_woocommerce_active()) {
		$options[] = array(
			'name' => __('Use WooCommerce for checkout', 'bookyourtravel'),
			'desc' => __('Use WooCommerce to enable payment after booking', 'bookyourtravel'),
			'id' => 'use_woocommerce_for_checkout',
			'std' => '0',
			'type' => 'checkbox');	
	}
	
	$options[] = array(
		'name' => __('Add captcha to forms', 'bookyourtravel'),
		'desc' => __('Add simple captcha implemented inside BookYourTravel theme to forms (login, register, book, inquire, contact etc)', 'bookyourtravel'),
		'id' => 'add_captcha_to_forms',
		'std' => '1',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('Override wp-login.php', 'bookyourtravel'),
		'desc' => __('Override wp-login.php and use custom login, register, forgot password pages', 'bookyourtravel'),
		'id' => 'override_wp_login',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('Users specify password', 'bookyourtravel'),
		'desc' => __('Let users specify their password when registering', 'bookyourtravel'),
		'id' => 'let_users_set_pass',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('Page Settings', 'bookyourtravel'),
		'type' => 'heading');

	$sliders_array = array();
	if (class_exists ('RevSlider')) {
	
		$options[] = array(
			'name' => __('Show slider', 'bookyourtravel'),
			'desc' => __('Show slider on home page', 'bookyourtravel'),
			'id' => 'frontpage_show_slider',
			'std' => '0',
			'type' => 'checkbox');

		try {
			$slider = new RevSlider();
			$sliders_array = $slider->getAllSliderAliases();
		} catch(Exception $e){}
		
		if (count($sliders_array) > 0) {
			$options[] = array(
				'name' => __('Homepage slider', 'bookyourtravel'),
				'desc' => __('Select homepage slider from existing sliders', 'bookyourtravel'),
				'id' => 'homepage_slider',
				'std' => '',
				'type' => 'select',
				'class' => 'mini', //mini, tiny, small
				'options' => $sliders_array);
		}
	}
	
	$options[] = array(
		'name' => __('My account dashboard page', 'bookyourtravel'),
		'desc' => __('Page that displays settings, bookings and reviews of logged in user', 'bookyourtravel'),
		'id' => 'my_account_page',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
	
	$options[] = array(
		'name' => __('Redirect to after login', 'bookyourtravel'),
		'desc' => __('Page to redirect to after login if "Override wp-login.php" is checked above', 'bookyourtravel'),
		'id' => 'redirect_to_after_login',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => __('Redirect to after logout', 'bookyourtravel'),
		'desc' => __('Page to redirect to after logout if "Override wp-login.php" is checked above', 'bookyourtravel'),
		'id' => 'redirect_to_after_logout',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);

	$options[] = array(
		'name' => __('Login page url', 'bookyourtravel'),
		'desc' => __('Login page if "Override wp-login.php" is checked above', 'bookyourtravel'),
		'id' => 'login_page_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => __('Register page url', 'bookyourtravel'),
		'desc' => __('Register page if "Override wp-login.php" is checked above', 'bookyourtravel'),
		'id' => 'register_page_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => __('Reset password page url', 'bookyourtravel'),
		'desc' => __('Reset password page if "Override wp-login.php" is checked above', 'bookyourtravel'),
		'id' => 'reset_password_page_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => __('Terms &amp; conditions page url', 'bookyourtravel'),
		'desc' => __('Terms &amp; conditions page url', 'bookyourtravel'),
		'id' => 'terms_page_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => __('Contact Us page url', 'bookyourtravel'),
		'desc' => __('Contact Us page url', 'bookyourtravel'),
		'id' => 'contact_page_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => __('Submit room types page url', 'bookyourtravel'),
		'desc' => __('Submit room types page url', 'bookyourtravel'),
		'id' => 'submit_room_types_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => __('Submit accommodations page url', 'bookyourtravel'),
		'desc' => __('Submit accommodations page url', 'bookyourtravel'),
		'id' => 'submit_accommodations_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);

	$options[] = array(
		'name' => __('Submit accommodation vacancies page url', 'bookyourtravel'),
		'desc' => __('Submit accommodation vacancies page url', 'bookyourtravel'),
		'id' => 'submit_accommodation_vacancies_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => __('List user room types page url', 'bookyourtravel'),
		'desc' => __('List user room types page url', 'bookyourtravel'),
		'id' => 'list_user_room_types_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => __('List user accommodations page url', 'bookyourtravel'),
		'desc' => __('List user accommodations page url', 'bookyourtravel'),
		'id' => 'list_user_accommodations_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);

	$options[] = array(
		'name' => __('List user accommodation vacancies page url', 'bookyourtravel'),
		'desc' => __('List user accommodation vacancies page url', 'bookyourtravel'),
		'id' => 'list_user_accommodation_vacancies_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => __('Search Settings', 'bookyourtravel'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('Search only available properties', 'bookyourtravel'),
		'desc' => __('Search displays only properties with valid vacancies/schedules etc', 'bookyourtravel'),
		'id' => 'search_only_available_properties',
		'std' => '1',
		'type' => 'checkbox');	
		
	$options[] = array(
		'name' => __('Custom search results page', 'bookyourtravel'),
		'desc' => __('Page to redirect to for custom search results', 'bookyourtravel'),
		'id' => 'redirect_to_search_results',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);

	$options[] = array(
		'name' => __('Custom search results default view', 'bookyourtravel'),
		'desc' => __('Custom search results default view (grid or list view)', 'bookyourtravel'),
		'id' => 'search_results_default_view',
		'std' => '0',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $search_results_view_array);
		
	$options[] = array(
		'name' => __('Search results posts per page', 'bookyourtravel'),
		'desc' => __('Number of results to display on custom search page', 'bookyourtravel'),
		'id' => 'search_results_posts_per_page',
		'std' => '12',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Price range bottom', 'bookyourtravel'),
		'desc' => __('Bottom value of price range used in search form (usually 0)', 'bookyourtravel'),
		'id' => 'price_range_bottom',
		'std' => '0',
		'type' => 'text',
		'class' => 'mini');

	$options[] = array(
		'name' => __('Price range increment', 'bookyourtravel'),
		'desc' => __('Increment value of price range used in search form (default 50)', 'bookyourtravel'),
		'id' => 'price_range_increment',
		'std' => '50',
		'type' => 'text',
		'class' => 'mini');

	$options[] = array(
		'name' => __('Price range increment count', 'bookyourtravel'),
		'desc' => __('Increment count of price range used in search form (default 5)', 'bookyourtravel'),
		'id' => 'price_range_count',
		'std' => '5',
		'type' => 'text',
		'class' => 'mini');
		
	
	$options[] = array(
		'name' => __('Frontend Submissions', 'bookyourtravel'),
		'type' => 'heading');		
		
	$options[] = array(
		'name' => __('Publish frontend submitted content immediately?', 'bookyourtravel'),
		'desc' => __('When users submit content via frontend, do you publish it immediately or do you leave it for admin to review?', 'bookyourtravel'),
		'id' => 'publish_frontend_submissions_immediately',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('Locations', 'bookyourtravel'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Show  self-catered count in location items', 'bookyourtravel'),
		'desc' => __('Show  self-catered count in location items shown location list page', 'bookyourtravel'),
		'id' => 'show_self_catered_count_in_location_items',
		'std' => '1',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('Show hotel count in location items', 'bookyourtravel'),
		'desc' => __('Show hotel count in location items shown location list page', 'bookyourtravel'),
		'id' => 'show_hotel_count_in_location_items',
		'std' => '1',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('Show cruise count in location items', 'bookyourtravel'),
		'desc' => __('Show cruise count in location items shown location list page', 'bookyourtravel'),
		'id' => 'show_cruise_count_in_location_items',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('Show tour count in location items', 'bookyourtravel'),
		'desc' => __('Show tour count in location items shown location list page', 'bookyourtravel'),
		'id' => 'show_tour_count_in_location_items',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('Show car rental count in location items', 'bookyourtravel'),
		'desc' => __('Show car rental count in location items shown location list page', 'bookyourtravel'),
		'id' => 'show_car_rental_count_in_location_items',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => __('Single location permalink slug', 'bookyourtravel'),
		'desc' => __('The permalink slug used for single locations (by default it is set to "location". <br /><strong>Note:</strong> Please make sure you flush your rewrite rules after changing this setting. You can do so by navigating to <a href="/wp-admin/options-permalink.php">Settings->Permalinks</a> and clicking "Save Changes".', 'bookyourtravel'),
		'id' => 'locations_permalink_slug',
		'std' => 'location',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Locations archive posts per page', 'bookyourtravel'),
		'desc' => __('Number of locations to display on locations archive page', 'bookyourtravel'),
		'id' => 'locations_archive_posts_per_page',
		'std' => '12',
		'type' => 'text');		
		
	$options[] = array(
		'name' => __('Tabs displayed on single location page.', 'bookyourtravel'),
		'desc' => __('Use drag&drop to change order of tabs.', 'bookyourtravel'),
		'id' => 'location_tabs',
		'std' => 'Tab name',
		'type' => 'repeat_tab');
		
	$options[] = array(
		'name' => __('Extra fields displayed on single location page.', 'bookyourtravel'),
		'desc' => __('Select the tab your field is displayed on from the tab dropdown.', 'bookyourtravel'),
		'id' => 'location_extra_fields',
		'std' => 'Default field label',
		'type' => 'repeat_extra_field');
		
	$options[] = array(
		'name' => __('Accommodations', 'bookyourtravel'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('Enable Accommodations', 'bookyourtravel'),
		'desc' => __('Enable "Accommodations" data-type', 'bookyourtravel'),
		'id' => 'enable_accommodations',
		'std' => '1',
		'type' => 'checkbox');			

	$options[] = array(
		'name' => __('Single accommodation permalink slug', 'bookyourtravel'),
		'desc' => __('The permalink slug used for creating single accommodations (by default it is set to "hotel". <br /><strong>Note:</strong> Please make sure you flush your rewrite rules after changing this setting. You can do so by navigating to <a href="/wp-admin/options-permalink.php">Settings->Permalinks</a> and clicking "Save Changes".', 'bookyourtravel'),
		'id' => 'accommodations_permalink_slug',
		'std' => 'hotel',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Accommodations archive posts per page', 'bookyourtravel'),
		'desc' => __('Number of accommodations to display on accommodations archive page', 'bookyourtravel'),
		'id' => 'accommodations_archive_posts_per_page',
		'std' => '12',
		'type' => 'text');		
		
	$options[] = array(
		'name' => __('Tabs displayed on single accommodation page.', 'bookyourtravel'),
		'desc' => __('Use drag&drop to change order of tabs.', 'bookyourtravel'),
		'id' => 'accommodation_tabs',
		'std' => 'Tab name',
		'type' => 'repeat_tab');
		
	$options[] = array(
		'name' => __('Extra fields displayed on single accommodation page.', 'bookyourtravel'),
		'desc' => __('Select the tab your field is displayed on from the tab dropdown.', 'bookyourtravel'),
		'id' => 'accommodation_extra_fields',
		'std' => 'Default field label',
		'type' => 'repeat_extra_field');
		
	$options[] = array(
		'name' => __('Tours', 'bookyourtravel'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('Enable Tours', 'bookyourtravel'),
		'desc' => __('Enable "Tours" data-type', 'bookyourtravel'),
		'id' => 'enable_tours',
		'std' => '1',
		'type' => 'checkbox');	
		
	$options[] = array(
		'name' => __('Tours archive posts per page', 'bookyourtravel'),
		'desc' => __('Number of tours to display on tours archive page', 'bookyourtravel'),
		'id' => 'tours_archive_posts_per_page',
		'std' => '12',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Single tour permalink slug', 'bookyourtravel'),
		'desc' => __('The permalink slug used for single tours (by default it is set to "tour". <br /><strong>Note:</strong> Please make sure you flush your rewrite rules after changing this setting. You can do so by navigating to <a href="/wp-admin/options-permalink.php">Settings->Permalinks</a> and clicking "Save Changes".', 'bookyourtravel'),
		'id' => 'tours_permalink_slug',
		'std' => 'tours',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Tabs displayed on single tour page.', 'bookyourtravel'),
		'desc' => __('Use drag&drop to change order of tabs.', 'bookyourtravel'),
		'id' => 'tour_tabs',
		'std' => 'Tab name',
		'type' => 'repeat_tab');
		
	$options[] = array(
		'name' => __('Extra fields displayed on single tour page.', 'bookyourtravel'),
		'desc' => __('Select the tab your field is displayed on from the tab dropdown.', 'bookyourtravel'),
		'id' => 'tour_extra_fields',
		'std' => 'Default field label',
		'type' => 'repeat_extra_field');
		
	$options[] = array(
		'name' => __('Car Rentals', 'bookyourtravel'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Enable Car rentals', 'bookyourtravel'),
		'desc' => __('Enable "Car rentals" data-type', 'bookyourtravel'),
		'id' => 'enable_car_rentals',
		'std' => '1',
		'type' => 'checkbox');	
		
	$options[] = array(
		'name' => __('Car rentals archive posts per page', 'bookyourtravel'),
		'desc' => __('Number of car rentals to display on car rentals archive page', 'bookyourtravel'),
		'id' => 'car_rentals_archive_posts_per_page',
		'std' => '12',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Single car rental permalink slug', 'bookyourtravel'),
		'desc' => __('The permalink slug used for single car rentals (by default it is set to "car-rental". <br /><strong>Note:</strong> Please make sure you flush your rewrite rules after changing this setting. You can do so by navigating to <a href="/wp-admin/options-permalink.php">Settings->Permalinks</a> and clicking "Save Changes".', 'bookyourtravel'),
		'id' => 'car_rentals_permalink_slug',
		'std' => 'car-rentals',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Tabs displayed on single car rental page.', 'bookyourtravel'),
		'desc' => __('Use drag&drop to change order of tabs.', 'bookyourtravel'),
		'id' => 'car_rental_tabs',
		'std' => 'Tab name',
		'type' => 'repeat_tab');
		
	$options[] = array(
		'name' => __('Extra fields displayed on single car rental page.', 'bookyourtravel'),
		'desc' => __('Select the tab your field is displayed on from the tab dropdown.', 'bookyourtravel'),
		'id' => 'car_rental_extra_fields',
		'std' => 'Default field label',
		'type' => 'repeat_extra_field');
		
	$options[] = array(
		'name' => __('Cruises', 'bookyourtravel'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('Enable Cruises', 'bookyourtravel'),
		'desc' => __('Enable "Cruises" data-type', 'bookyourtravel'),
		'id' => 'enable_cruises',
		'std' => '1',
		'type' => 'checkbox');	
		
	$options[] = array(
		'name' => __('Cruises archive posts per page', 'bookyourtravel'),
		'desc' => __('Number of cruises to display on cruises archive page', 'bookyourtravel'),
		'id' => 'cruises_archive_posts_per_page',
		'std' => '12',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Single cruise permalink slug', 'bookyourtravel'),
		'desc' => __('The permalink slug used for single cruises (by default it is set to "cruise". <br /><strong>Note:</strong> Please make sure you flush your rewrite rules after changing this setting. You can do so by navigating to <a href="/wp-admin/options-permalink.php">Settings->Permalinks</a> and clicking "Save Changes".', 'bookyourtravel'),
		'id' => 'cruises_permalink_slug',
		'std' => 'cruises',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Tabs displayed on single cruise page.', 'bookyourtravel'),
		'desc' => __('Use drag&drop to change order of tabs.', 'bookyourtravel'),
		'id' => 'cruise_tabs',
		'std' => 'Tab name',
		'type' => 'repeat_tab');
		
	$options[] = array(
		'name' => __('Extra fields displayed on single cruise page.', 'bookyourtravel'),
		'desc' => __('Select the tab your field is displayed on from the tab dropdown.', 'bookyourtravel'),
		'id' => 'cruise_extra_fields',
		'std' => 'Default field label',
		'type' => 'repeat_extra_field');

	$options[] = array(
		'name' => __('Reviews', 'bookyourtravel'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Enable Reviews', 'bookyourtravel'),
		'desc' => __('Enable "Reviews" data-type', 'bookyourtravel'),
		'id' => 'enable_reviews',
		'std' => '1',
		'type' => 'checkbox');
		
	$options[] = array(
		'text' => __('Synchronise reviews', 'bookyourtravel'),
		'name' => __('Synchronise review totals', 'bookyourtravel'),
		'desc' => __('Click this button to synchronise review totals if your review totals are out of sync', 'bookyourtravel'),
		'id' => 'synchronise_reviews',
		'std' => 'Default',
		'type' => 'link_button_field');
		
	$options[] = array(
		'name' => __('Accommodation review fields', 'bookyourtravel'),
		'desc' => __('Review fields for single accommodation', 'bookyourtravel'),
		'id' => 'accommodation_review_fields',
		'std' => 'Default review field label',
		'type' => 'repeat_review_field');
		
	$options[] = array(
		'name' => __('Tour review fields', 'bookyourtravel'),
		'desc' => __('Review fields for single tour.', 'bookyourtravel'),
		'id' => 'tour_review_fields',
		'std' => 'Default review field label',
		'type' => 'repeat_review_field');
		
	$options[] = array(
		'name' => __('Cruise review fields', 'bookyourtravel'),
		'desc' => __('Review fields for single cruise.', 'bookyourtravel'),
		'id' => 'cruise_review_fields',
		'std' => 'Default review field label',
		'type' => 'repeat_review_field');
		

	$byt_needs_update = get_option( '_byt_needs_update', 0 );
	
	if ($byt_needs_update) {
		$options[] = array(
			'name' => __('Upgrades', 'bookyourtravel'),
			'type' => 'heading');

		global $byt_accommodations_post_type;
		
		$count_bookings_with_unfixed_dates = $byt_accommodations_post_type->get_count_bookings_with_unfixed_dates();
		
		$byt_version_before_update = get_option('_byt_version_before_update', 0);
		global $byt_installed_version;
		
		if ( version_compare( $byt_version_before_update, $byt_installed_version, '<' ) && null !== $byt_installed_version ) {
						
			$options[] = array(
				'text' => __('Click here to upgrade', 'bookyourtravel'),
				'name' => __('Your Book Your Travel database needs an upgrade!', 'bookyourtravel'),
				'desc' => sprintf(__('Your current database version is <strong>%s</strong>, while the current theme version is <strong>%s</strong>.', 'bookyourtravel'), $byt_version_before_update, $byt_installed_version),
				'id' => 'upgrade_byt_database',
				'std' => 'Default',
				'type' => 'link_button_field');
				
		}

		if ($count_bookings_with_unfixed_dates > 0) {	
		
			$options[] = array(
				'name' => __('Fix partial booking issue', 'bookyourtravel'),
				'desc' => __('Click this button to fix partial booking issue', 'bookyourtravel'),
				'id' => 'fix_partial_booking_issue',
				'std' => 'Default',
				'type' => 'link_button_field');			
		}
	}
		
	return $options;
}