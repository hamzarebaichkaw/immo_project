<?php

class BYT_Theme_Ajax extends BYT_BaseSingleton {
	
	protected function __construct() {
	
        // our parent class might contain shared code in its constructor
        parent::__construct();
		
    }
	
    public function init() {
		
		add_action( 'wp_ajax_accommodation_is_self_catered_ajax_request', array( $this, 'accommodation_is_self_catered_ajax_request' ) );
		add_action( 'wp_ajax_accommodation_is_price_per_person_ajax_request', array( $this, 'accommodation_is_price_per_person_ajax_request' ) );
		add_action( 'wp_ajax_accommodation_list_room_types_ajax_request', array( $this, 'accommodation_list_room_types_ajax_request' ) );
		add_action( 'wp_ajax_review_ajax_request', array( $this, 'review_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_review_ajax_request', array( $this, 'review_ajax_request' ) );
		add_action( 'wp_ajax_sync_reviews_ajax_request', array( $this, 'sync_reviews_ajax_request' ) );
		add_action( 'wp_ajax_inquiry_ajax_request', array( $this, 'inquiry_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_inquiry_ajax_request', array( $this, 'inquiry_ajax_request' ) );
		add_action( 'wp_ajax_accommodation_available_end_dates_request', array( $this, 'accommodation_available_end_dates_request' ) );
		add_action( 'wp_ajax_nopriv_accommodation_available_end_dates_request', array( $this, 'accommodation_available_end_dates_request' ) );
		add_action( 'wp_ajax_accommodation_available_start_dates_request', array( $this, 'accommodation_available_start_dates_request' ) );
		add_action( 'wp_ajax_nopriv_accommodation_available_start_dates_request', array( $this, 'accommodation_available_start_dates_request' ) );
		add_action( 'wp_ajax_accommodation_get_price_request', array( $this, 'accommodation_get_price_request' ) );
		add_action( 'wp_ajax_nopriv_accommodation_get_price_request', array( $this, 'accommodation_get_price_request' ) );
		add_action( 'wp_ajax_book_accommodation_ajax_request', array( $this, 'book_accommodation_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_book_accommodation_ajax_request', array( $this, 'book_accommodation_ajax_request' ) );		
		add_action( 'wp_ajax_tour_is_price_per_group_ajax_request', array( $this, 'tour_is_price_per_group_ajax_request' ) );
		add_action( 'wp_ajax_tour_type_is_repeated_ajax_request', array( $this, 'tour_type_is_repeated_ajax_request' ) );		
		add_action( 'wp_ajax_cruise_is_price_per_person_ajax_request', array( $this, 'cruise_is_price_per_person_ajax_request' ) );
		add_action( 'wp_ajax_cruise_list_cabin_types_ajax_request', array( $this, 'cruise_list_cabin_types_ajax_request') );
		add_action( 'wp_ajax_cruise_type_is_repeated_ajax_request', array( $this, 'cruise_type_is_repeated_ajax_request') );		
		add_action( 'wp_ajax_settings_ajax_save_password', array( $this, 'settings_ajax_save_password' ) );
		add_action( 'wp_ajax_settings_ajax_save_email', array( $this, 'settings_ajax_save_email' ) );
		add_action( 'wp_ajax_settings_ajax_save_last_name', array( $this, 'settings_ajax_save_last_name' ) );
		add_action( 'wp_ajax_settings_ajax_save_first_name', array( $this, 'settings_ajax_save_first_name' ) );		
		add_action( 'wp_ajax_upgrade_byt_database_ajax_request', array( $this, 'upgrade_byt_database' ) );		
		add_action( 'wp_ajax_book_car_rental_ajax_request', array($this, 'book_car_rental_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_book_car_rental_ajax_request', array($this, 'book_car_rental_ajax_request' ) );
		add_action( 'wp_ajax_cruise_get_price_request', array($this, 'cruise_get_price_request' ) );
		add_action( 'wp_ajax_nopriv_cruise_get_price_request', array($this, 'cruise_get_price_request') );
		add_action( 'wp_ajax_tour_get_price_request', array($this, 'tour_get_price_request' ) );
		add_action( 'wp_ajax_nopriv_tour_get_price_request', array($this, 'tour_get_price_request' ) );
		add_action( 'wp_ajax_tour_schedule_dates_request', array($this, 'tour_schedule_dates_request' ));
		add_action( 'wp_ajax_nopriv_tour_schedule_dates_request', array($this, 'tour_schedule_dates_request' ));
		add_action( 'wp_ajax_cruise_schedule_dates_request', array($this, 'cruise_schedule_dates_request' ));
		add_action( 'wp_ajax_nopriv_cruise_schedule_dates_request', array($this, 'cruise_schedule_dates_request' ));
		add_action( 'wp_ajax_tour_available_schedule_id_request', array($this, 'tour_available_schedule_id_request'));
		add_action( 'wp_ajax_nopriv_tour_available_schedule_id_request', array($this, 'tour_available_schedule_id_request'));
		add_action( 'wp_ajax_cruise_available_schedule_id_request', array($this, 'cruise_available_schedule_id_request'));
		add_action( 'wp_ajax_nopriv_cruise_available_schedule_id_request', array($this, 'cruise_available_schedule_id_request'));
		add_action( 'wp_ajax_nopriv_tour_max_people_ajax_request', array($this, 'tour_max_people_ajax_request'));
		add_action( 'wp_ajax_tour_max_people_ajax_request', array($this, 'tour_max_people_ajax_request'));
		add_action( 'wp_ajax_book_tour_ajax_request', array($this, 'book_tour_ajax_request') );
		add_action( 'wp_ajax_nopriv_book_tour_ajax_request', array($this, 'book_tour_ajax_request') );
		add_action( 'wp_ajax_book_cruise_ajax_request', array($this, 'book_cruise_ajax_request') );
		add_action( 'wp_ajax_nopriv_book_cruise_ajax_request', array($this, 'book_cruise_ajax_request') );
		add_action( 'wp_ajax_car_rental_is_reservation_only_request', array($this, 'car_rental_is_reservation_only_request'));
		add_action( 'wp_ajax_nopriv_car_rental_is_reservation_only_request', array($this, 'car_rental_is_reservation_only_request'));
		add_action( 'wp_ajax_accommodation_is_reservation_only_request', array($this, 'accommodation_is_reservation_only_request'));
		add_action( 'wp_ajax_nopriv_accommodation_is_reservation_only_request', array($this, 'accommodation_is_reservation_only_request'));
		add_action( 'wp_ajax_tour_is_reservation_only_request', array($this, 'tour_is_reservation_only_request'));
		add_action( 'wp_ajax_nopriv_tour_is_reservation_only_request', array($this, 'tour_is_reservation_only_request'));
		add_action( 'wp_ajax_cruise_is_reservation_only_request', array($this, 'cruise_is_reservation_only_request'));
		add_action( 'wp_ajax_nopriv_cruise_is_reservation_only_request', array($this, 'cruise_is_reservation_only_request'));
		add_action( 'wp_ajax_car_rental_booked_dates_request', array($this, 'car_rental_booked_dates_request' ));
		add_action( 'wp_ajax_nopriv_car_rental_booked_dates_request', array($this, 'car_rental_booked_dates_request' ));
		
	}
	
	function car_rental_booked_dates_request() {
	
		global $byt_car_rentals_post_type, $byt_theme_globals, $byt_theme_woocommerce;
		
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$car_rental_id = intval(wp_kses($_REQUEST['car_rental_id'], ''));	
				$month = intval(wp_kses($_REQUEST['month'], ''));	
				$year = intval(wp_kses($_REQUEST['year'], ''));	
			
				if ($car_rental_id > 0) {
					
					$booked_dates = $byt_car_rentals_post_type->car_rental_get_booked_days($car_rental_id, $month, $year);
					echo json_encode($booked_dates);
				}
			}
		}
		
		die();
	}

	function car_rental_is_reservation_only_request() {
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$car_rental_id = intval(wp_kses($_REQUEST['car_rental_id'], ''));	
				$is_reservation_only = get_post_meta( $car_rental_id, 'car_rental_is_reservation_only', true );
				$is_reservation_only = isset($is_reservation_only) ? (int)$is_reservation_only : 0;
				
				echo $is_reservation_only;
			} else {
				echo 'failed nonce';
			}
		}
		
		die();
	}

	function accommodation_is_reservation_only_request() {
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$accommodation_id = intval(wp_kses($_REQUEST['accommodation_id'], ''));	
				$is_reservation_only = get_post_meta( $accommodation_id, 'accommodation_is_reservation_only', true );
				$is_reservation_only = isset($is_reservation_only) ? (int)$is_reservation_only : 0;
				
				echo $is_reservation_only;
			}
		}
		
		die();
	}

	function tour_is_reservation_only_request() {
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$tour_id = intval(wp_kses($_REQUEST['tour_id'], ''));	
				$is_reservation_only = get_post_meta( $tour_id, 'tour_is_reservation_only', true );
				$is_reservation_only = isset($is_reservation_only) ? (int)$is_reservation_only : 0;
				
				echo $is_reservation_only;
			}
		}
		
		die();
	}

	function cruise_is_reservation_only_request() {
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$cruise_id = intval(wp_kses($_REQUEST['cruise_id'], ''));	
				$is_reservation_only = get_post_meta( $cruise_id, 'cruise_is_reservation_only', true );
				$is_reservation_only = isset($is_reservation_only) ? (int)$is_reservation_only : 0;
				
				echo $is_reservation_only;
			}
		}
		
		die();
	}
		
	function book_tour_ajax_request() {
		
		global $byt_tours_post_type, $byt_theme_globals, $byt_theme_woocommerce;

		$enc_key = $byt_theme_globals->get_enc_key();
		$add_captcha_to_forms = $byt_theme_globals->add_captcha_to_forms();
		
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			
				$first_name = isset($_REQUEST['first_name']) ? wp_kses($_REQUEST['first_name'], '') : '';
				$last_name = isset($_REQUEST['last_name']) ? wp_kses($_REQUEST['last_name'], '') : '';
				$email = isset($_REQUEST['email']) ? wp_kses($_REQUEST['email'], '') : '';
				$phone = isset($_REQUEST['phone']) ? wp_kses($_REQUEST['phone'], '') : '';
				$address = isset($_REQUEST['address']) ? wp_kses($_REQUEST['address'], '') : '';
				$town = isset($_REQUEST['town']) ? wp_kses($_REQUEST['town'], '') : '';
				$zip = isset($_REQUEST['zip']) ? wp_kses($_REQUEST['zip'], '') : '';
				$adults = isset($_REQUEST['adults']) ? wp_kses($_REQUEST['adults'], '') : '';
				$adults = $adults ? intval($adults) : 1;
				$children = isset($_REQUEST['children']) ? wp_kses($_REQUEST['children'], '') : '';
				$children = $children ? intval($children) : 0;
				$country = isset($_REQUEST['country']) ? wp_kses($_REQUEST['country'], '') : '';
				$special_requirements = isset($_REQUEST['requirements']) ? wp_kses($_REQUEST['requirements'], '') : '';
				$tour_start_date = isset($_REQUEST['tour_start_date']) ? wp_kses($_REQUEST['tour_start_date'], '') : null;		
				$tour_schedule_id = isset($_REQUEST['tour_schedule_id']) ? intval(wp_kses($_REQUEST['tour_schedule_id'], '')) : 0;		
			
				$c_val_s = isset($_REQUEST['c_val_s']) ? intval(wp_kses($_REQUEST['c_val_s'], '')) : -1;
				$c_val_1 = isset($_REQUEST['c_val_1']) ? intval(BYT_Theme_Utils::decrypt(wp_kses($_REQUEST['c_val_1'], ''), $enc_key)) : 0;
				$c_val_2 = isset($_REQUEST['c_val_2']) ? intval(BYT_Theme_Utils::decrypt(wp_kses($_REQUEST['c_val_2'], ''), $enc_key)) : 0;
			
				// nonce passed ok
				$tour_schedule = $byt_tours_post_type->get_tour_schedule($tour_schedule_id);
				
				if ($tour_schedule != null) {
				
					if ($add_captcha_to_forms && $c_val_s != ($c_val_1 + $c_val_2)) {
						echo 'captcha_error';
						die();
					} else {
				
						$tour_id = $tour_schedule->tour_id;
						$tour = get_post($tour_id);

						$tour_is_price_per_group = get_post_meta($tour_id, 'tour_is_price_per_group', true);
						
						$current_user = wp_get_current_user();
						
						$total_price_adults = $tour_schedule->price;
						$total_price_children = 0;
						
						if (!$tour_is_price_per_group) {
							$total_price_children = $tour_schedule->price_child * $children;
							$total_price_adults = $total_price_adults * $adults;
						}
							
						$total_price = $total_price_adults + $total_price_children;
						$start_date = date('Y-m-d', strtotime($tour_start_date));
						$tour_name = $tour->post_title;
						
						$booking_id = $byt_tours_post_type->create_tour_booking ($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $current_user->ID, $total_price_adults, $total_price_children, $total_price, $start_date);
						
						$is_reservation_only = get_post_meta( $tour_id, 'tour_is_reservation_only', true );
						
						$use_woocommerce_for_checkout = $byt_theme_globals->use_woocommerce_for_checkout();
						if (BYT_Theme_Utils::is_woocommerce_active() && !$is_reservation_only) {
							if ($use_woocommerce_for_checkout) {
								$product_id = $byt_theme_woocommerce->woocommerce_create_product($tour->post_title, '', 'ACC_' . $tour_id . '_', $booking_id, $total_price, BOOKYOURTRAVEL_WOO_PRODUCT_CAT_TOURS); 
								echo $product_id;
							}
						} else {
							echo $booking_id;
						}
						
						if (!$use_woocommerce_for_checkout || !BYT_Theme_Utils::is_woocommerce_active()) {
						
							$admin_email = get_bloginfo('admin_email');
							$admin_name = get_bloginfo('name');
							$headers = "From: $admin_name <$admin_email>\n";
							$subject = __('New tour booking', 'bookyourtravel');
							
							$message = __("New tour booking: \n\nFirst name: %s \n\nLast name: %s \n\nEmail: %s \n\nPhone: %s \n\nAddress: %s \n\nTown: %s \n\nZip: %s \n\nCountry: %s \n\nSpecial requirements: %s \n\nAdults: %d \n\nChildren: %d \n\nTour name: %s \n\nStart date: %s \n\nTotal price: %d \n", 'bookyourtravel');
							$message = sprintf($message, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_name, $start_date, $total_price);

							wp_mail($email, $subject, $message, $headers);
							
							$contact_email = get_post_meta($tour_id, 'tour_contact_email', true );
							$contact_emails = explode(';', $contact_email);
							if (empty($contact_email))
								$contact_emails = array($admin_email);	

							foreach ($contact_emails as $e) {
								if (!empty($e)) {
									wp_mail($e, $subject, $message, $headers);			
								}
							}
						}
					}
				} else {
					echo 'tour_schedule_error';
				}
			} 		
		}
		
		// Always die in functions echoing ajax content
		die();
	} 

	function book_cruise_ajax_request() {
		global $byt_cruises_post_type, $byt_theme_globals, $byt_theme_woocommerce;

		$enc_key = $byt_theme_globals->get_enc_key();
		$add_captcha_to_forms = $byt_theme_globals->add_captcha_to_forms();
		
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			
				$first_name = isset($_REQUEST['first_name']) ? wp_kses($_REQUEST['first_name'], '') : '';
				$last_name = isset($_REQUEST['last_name']) ? wp_kses($_REQUEST['last_name'], '') : '';
				$email = isset($_REQUEST['email']) ? wp_kses($_REQUEST['email'], '') : '';
				$phone = isset($_REQUEST['phone']) ? wp_kses($_REQUEST['phone'], '') : '';
				$address = isset($_REQUEST['address']) ? wp_kses($_REQUEST['address'], '') : '';
				$town = isset($_REQUEST['town']) ? wp_kses($_REQUEST['town'], '') : '';
				$zip = isset($_REQUEST['zip']) ? wp_kses($_REQUEST['zip'], '') : '';
				$adults = isset($_REQUEST['adults']) ? wp_kses($_REQUEST['adults'], '') : '';
				$adults = $adults ? intval($adults) : 1;
				$children = isset($_REQUEST['children']) ? wp_kses($_REQUEST['children'], '') : '';
				$children = $children ? intval($children) : 0;
				$country = isset($_REQUEST['country']) ? wp_kses($_REQUEST['country'], '') : '';
				$special_requirements = isset($_REQUEST['requirements']) ? wp_kses($_REQUEST['requirements'], '') : '';
				$cruise_start_date = isset($_REQUEST['cruise_start_date']) ? wp_kses($_REQUEST['cruise_start_date'], '') : null;		
				$cruise_schedule_id = isset($_REQUEST['cruise_schedule_id']) ? intval(wp_kses($_REQUEST['cruise_schedule_id'], '')) : 0;		
			
				$c_val_s = isset($_REQUEST['c_val_s']) ? intval(wp_kses($_REQUEST['c_val_s'], '')) : -1;
				$c_val_1 = isset($_REQUEST['c_val_1']) ? intval(BYT_Theme_Utils::decrypt(wp_kses($_REQUEST['c_val_1'], ''), $enc_key)) : 0;
				$c_val_2 = isset($_REQUEST['c_val_2']) ? intval(BYT_Theme_Utils::decrypt(wp_kses($_REQUEST['c_val_2'], ''), $enc_key)) : 0;
			
				// nonce passed ok
				$cruise_schedule = $byt_cruises_post_type->get_cruise_schedule($cruise_schedule_id);
				
				if ($cruise_schedule != null) {
				
					if ($add_captcha_to_forms && $c_val_s != ($c_val_1 + $c_val_2)) {
						echo 'captcha_error';
						die();
					} else {
				
						$cruise_id = $cruise_schedule->cruise_id;
						$cruise_obj = new byt_cruise(intval($cruise_id));
						$cruise = get_post($cruise_id);

						$cruise_is_price_per_person = $cruise_obj->get_is_price_per_person();
						
						$current_user = wp_get_current_user();
						
						$total_price_adults = 0;
						
						$total_price_children = 0;
						if ($cruise_is_price_per_person) {
							$total_price_children = $cruise_schedule->price_child * $children;
							$total_price_adults = $cruise_schedule->price * $adults;
						} else {
							$total_price_adults = $cruise_schedule->price;
						}
							
						$total_price = $total_price_adults + $total_price_children;
						$start_date = date('Y-m-d', strtotime($cruise_start_date));
						$cruise_name = $cruise_obj->get_title();
						
						$booking_id = $byt_cruises_post_type->create_cruise_booking ($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $cruise_schedule_id, $current_user->ID, $total_price_adults, $total_price_children, $total_price, $start_date);
						
						$is_reservation_only = get_post_meta( $cruise_id, 'cruise_is_reservation_only', true );
						
						$use_woocommerce_for_checkout = $byt_theme_globals->use_woocommerce_for_checkout();
						if (BYT_Theme_Utils::is_woocommerce_active() && !$is_reservation_only) {
							if ($use_woocommerce_for_checkout) {
								$product_id = $byt_theme_woocommerce->woocommerce_create_product($cruise_obj->get_title(), '', 'ACC_' . $cruise_id . '_', $booking_id, $total_price, BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CRUISES); 
								echo $product_id;
							}
						} else {
							echo $booking_id;
						}
						
						if (!$use_woocommerce_for_checkout || !BYT_Theme_Utils::is_woocommerce_active()) {
						
							$admin_email = get_bloginfo('admin_email');
							$admin_name = get_bloginfo('name');
							$headers = "From: $admin_name <$admin_email>\n";
							$subject = __('New cruise booking', 'bookyourtravel');
							
							$message = __("New cruise booking: \n\nFirst name: %s \n\nLast name: %s \n\nEmail: %s \n\nPhone: %s \n\nAddress: %s \n\nTown: %s \n\nZip: %s \n\nCountry: %s \n\nSpecial requirements: %s \n\nAdults: %d \n\nChildren: %d \n\nCruise name: %s \n\nStart date: %s \n\nTotal price: %d \n", 'bookyourtravel');
							$message = sprintf($message, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $cruise_name, $start_date, $total_price);

							wp_mail($email, $subject, $message, $headers);
							
							$contact_email = get_post_meta($cruise_id, 'cruise_contact_email', true );
							$contact_emails = explode(';', $contact_email);
							if (empty($contact_email))
								$contact_emails = array($admin_email);	

							foreach ($contact_emails as $e) {
								if (!empty($e)) {
									wp_mail($e, $subject, $message, $headers);			
								}
							}
						}
					}
				}
			} 		
		}
		
		// Always die in functions echoing ajax content
		die();
	}
		
	function tour_max_people_ajax_request() {

		global $byt_tours_post_type, $byt_theme_globals;
		
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$tour_schedule_id = intval(wp_kses($_REQUEST['tourScheduleId'], ''));	
				$tour_id = intval(wp_kses($_REQUEST['tourId'], ''));	
				$date_value = wp_kses($_REQUEST['dateValue'], '');	
				$date_value = date('Y-m-d', strtotime($date_value));
				
				$schedule = $byt_tours_post_type->get_tour_schedule_max_people($tour_schedule_id, $tour_id, $date_value);
		
				if (isset($schedule)) {				
					echo ($schedule->max_people - $schedule->booking_count);
				}
			}
		}
		
		die();
	}
		
	function tour_get_price_request() {

		global $byt_tours_post_type, $byt_theme_globals;
		$price_decimal_places = $byt_theme_globals->get_price_decimal_places();
		
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$tour_id = intval(wp_kses($_REQUEST['tourId'], ''));	
				$date_value = wp_kses($_REQUEST['dateValue'], '');	
				$date_value = date('Y-m-d', strtotime($date_value));
				$schedule_id = $byt_tours_post_type->get_tour_available_schedule_id($tour_id, $date_value);
		
				if ($schedule_id > 0) {				
					$price = number_format ($byt_tours_post_type->get_tour_schedule_price($schedule_id, false), $price_decimal_places, ".", "");
					$child_price = number_format ($byt_tours_post_type->get_tour_schedule_price($schedule_id, true), $price_decimal_places, ".", "");
		
					$prices = array( 
						'price' => $price, 
						'child_price' => $child_price 
					);
					
					echo json_encode($prices);
				}
			}
		}
		
		die();
	}

	function cruise_get_price_request() {

		global $byt_cruises_post_type, $byt_theme_globals;
		$price_decimal_places = $byt_theme_globals->get_price_decimal_places();

		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$cruise_id = intval(wp_kses($_REQUEST['cruiseId'], ''));	
				$cabin_type_id = intval(wp_kses($_REQUEST['cabinTypeId'], ''));	
				$date_value = wp_kses($_REQUEST['dateValue'], '');	
				$date_value = date('Y-m-d', strtotime($date_value));
				$schedule_id = $byt_cruises_post_type->get_cruise_available_schedule_id($cruise_id, $cabin_type_id, $date_value);
		
				if ($schedule_id > 0) {				
					$price = number_format ($byt_cruises_post_type->get_cruise_schedule_price($schedule_id, false), $price_decimal_places, ".", "");
					$child_price = number_format ($byt_cruises_post_type->get_cruise_schedule_price($schedule_id, true), $price_decimal_places, ".", "");
		
					$prices = array( 
						'price' => $price, 
						'child_price' => $child_price 
					);
					
					echo json_encode($prices);
				}
			}
		}
		
		die();
	}
		
	function tour_schedule_dates_request() {
	
		global $byt_tours_post_type, $byt_theme_globals;
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$tour_id = intval(wp_kses($_REQUEST['tourId'], ''));	
				$month = intval(wp_kses($_REQUEST['month'], ''));	
				$year = intval(wp_kses($_REQUEST['year'], ''));	
				$day = intval(wp_kses($_REQUEST['day'], ''));
				$hour = 0;
				$minute = 0;
				
				$date_from = date('Y-m-d', strtotime("$year-$month-$day $hour:$minute"));
			
				if ($tour_id > 0) {
					$tour_obj = new byt_tour(intval($tour_id));
					$schedule_entries = $byt_tours_post_type->list_available_tour_schedule_entries($tour_id, $date_from, $year, $month, $tour_obj->get_type_is_repeated(), $tour_obj->get_type_day_of_week_index());				
					echo json_encode($schedule_entries);
				}
			}
		}
		
		die();
	}

	function tour_available_schedule_id_request() {
		
		global $byt_tours_post_type, $byt_theme_globals;
		
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$tour_id = isset($_REQUEST['tourId']) ? intval(wp_kses($_REQUEST['tourId'], '')) : 0;
				$date_value = isset($_REQUEST['dateValue']) ? wp_kses($_REQUEST['dateValue'], '') : null;
				if ($date_value) {
					$date_value = date('Y-m-d', strtotime($date_value));
					$schedule_id = $byt_tours_post_type->get_tour_available_schedule_id($tour_id, $date_value);
					echo $schedule_id;
				} else {
					echo 0;
				}
			} else {
				echo 'nonce_error';
			}
		} else {
			echo 'empty_request';
		}
		
		die();
	}

	function cruise_available_schedule_id_request() {
		global $byt_cruises_post_type, $byt_theme_globals;
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$cruise_id = intval(wp_kses($_REQUEST['cruiseId'], ''));	
				$cabin_type_id = intval(wp_kses($_REQUEST['cabinTypeId'], ''));	
				$date_value = wp_kses($_REQUEST['dateValue'], '');	
				$date_value = date('Y-m-d', strtotime($date_value));
				$schedule_id = $byt_cruises_post_type->get_cruise_available_schedule_id($cruise_id, $cabin_type_id, $date_value);
				echo $schedule_id;
			}
		}
		
		die();
	}
	
	function cruise_schedule_dates_request() {
	
		global $byt_cruises_post_type, $byt_theme_globals;

		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$cruise_id = intval(wp_kses($_REQUEST['cruiseId'], ''));	
				$cabin_type_id = intval(wp_kses($_REQUEST['cabinTypeId'], ''));
				$month = intval(wp_kses($_REQUEST['month'], ''));	
				$year = intval(wp_kses($_REQUEST['year'], ''));	
				$day = intval(wp_kses($_REQUEST['day'], ''));
				$hour = 0;
				$minute = 0;
				
				$date_from = date('Y-m-d', strtotime("$year-$month-$day $hour:$minute"));
			
				if ($cruise_id > 0) {
					$cruise_obj = new byt_cruise(intval($cruise_id));
					$schedule_entries = $byt_cruises_post_type->list_available_cruise_schedule_entries($cruise_id, $cabin_type_id, $date_from, $year, $month, $cruise_obj->get_type_is_repeated(), $cruise_obj->get_type_day_of_week_index());				
					echo json_encode($schedule_entries);
				}
			}
		}
		
		die();
	}

	function book_car_rental_ajax_request() {

		global $byt_car_rentals_post_type, $byt_theme_globals, $byt_theme_woocommerce;

		$enc_key = $byt_theme_globals->get_enc_key();
		$add_captcha_to_forms = $byt_theme_globals->add_captcha_to_forms();
	
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			
				$first_name = isset($_REQUEST['first_name']) ? wp_kses($_REQUEST['first_name'], '') : '';
				$last_name = isset($_REQUEST['last_name']) ? wp_kses($_REQUEST['last_name'], '') : '';
				$email = isset($_REQUEST['email']) ? wp_kses($_REQUEST['email'], '') : '';
				$phone = isset($_REQUEST['phone']) ? wp_kses($_REQUEST['phone'], '') : '';
				$address = isset($_REQUEST['address']) ? wp_kses($_REQUEST['address'], '') : '';
				$town = isset($_REQUEST['town']) ? wp_kses($_REQUEST['town'], '') : '';
				$zip = isset($_REQUEST['zip']) ? wp_kses($_REQUEST['zip'], '') : '';
				$country = isset($_REQUEST['country']) ? wp_kses($_REQUEST['country'], '') : '';
				$special_requirements = isset($_REQUEST['requirements']) ? wp_kses($_REQUEST['requirements'], '') : '';
				
				$date_from = isset($_REQUEST['date_from']) ? wp_kses($_REQUEST['date_from'], '') : '';
				$date_from = date('Y-m-d', strtotime($date_from));
				$date_to = isset($_REQUEST['date_to']) ? wp_kses($_REQUEST['date_to'], '') : '';
				$date_to = date('Y-m-d', strtotime($date_to));
				
				$car_rental_id = isset($_REQUEST['car_rental_id']) ? intval(wp_kses($_REQUEST['car_rental_id'], '')) : 0;	
				$drop_off = isset($_REQUEST['drop_off']) ? intval(wp_kses($_REQUEST['drop_off'], '')) : '';	

				$c_val_s = isset($_REQUEST['c_val_s']) ? intval(wp_kses($_REQUEST['c_val_s'], '')) : 0;
				$c_val_1 = isset($_REQUEST['c_val_1']) ? intval(BYT_Theme_Utils::decrypt(wp_kses($_REQUEST['c_val_1'], ''), $enc_key)) : 0;
				$c_val_2 = isset($_REQUEST['c_val_2']) ? intval(BYT_Theme_Utils::decrypt(wp_kses($_REQUEST['c_val_2'], ''), $enc_key)) : 0;
				
				// nonce passed ok
				$car_rental_obj = new byt_car_rental($car_rental_id);			
				
				if ($car_rental_obj != null) {
				
					if ($add_captcha_to_forms && $c_val_s != ($c_val_1 + $c_val_2)) {
						echo 'captcha_error';
						die();
					} else {
					
						$drop_off_location_obj = new byt_location($drop_off);
						$drop_off_location_title = $drop_off_location_obj->get_title();
						$car_rental_location = $car_rental_obj->get_location();
						$pick_up_location_title = '';
						if ($car_rental_location)
							$pick_up_location_title = $car_rental_location->get_title();
						
						$price_per_day = floatval($car_rental_obj->get_custom_field( 'price_per_day' ));
						$datediff =  strtotime($date_to) -  strtotime($date_from);
						$days = floor($datediff/(60*60*24));
						
						$total_price = $price_per_day * $days;
						
						$current_user = wp_get_current_user();
						
						$booking_id = $byt_car_rentals_post_type->create_car_rental_booking ($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $date_from, $date_to, $car_rental_id,  $current_user->ID, $total_price, $drop_off);
						
						$is_reservation_only = get_post_meta( $car_rental_id, 'car_rental_is_reservation_only', true );

						$use_woocommerce_for_checkout = $byt_theme_globals->use_woocommerce_for_checkout();
						if (BYT_Theme_Utils::is_woocommerce_active() && !$is_reservation_only) {
							if ($use_woocommerce_for_checkout) {
								$product_id = $byt_theme_woocommerce->woocommerce_create_product($car_rental_obj->get_title(), '', 'ACC_' . $car_rental_id . '_', $booking_id, $total_price, BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CAR_RENTALS); 
								echo $product_id;
							}
						} else {
							echo $booking_id;
						}
						
						if (!$use_woocommerce_for_checkout || !BYT_Theme_Utils::is_woocommerce_active()) {
						
							// only send email if we are not proceeding to WooCommerce checkout or if woocommerce is not active at all.
							$admin_email = get_bloginfo('admin_email');
							$admin_name = get_bloginfo('name');
							
							$headers = "From: $admin_name <$admin_email>\n";
							$subject = __('New car rental booking', 'bookyourtravel');

							$message = __("New car rental booking: \n\nFirst name: %s \n\nLast name: %s \n\nEmail: %s \n\nPhone: %s \n\nAddress: %s \n\nTown: %s \n\nZip: %s \n\nCountry: %s \n\nSpecial requirements: %s \n\nDate from: %s \n\nDate to: %s \n\nPick up: %s \n\nDrop off: %s \n\nTotal price: %d \n\nCar: %s \n", 'bookyourtravel');	
							$message = sprintf($message, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $date_from, $date_to, $pick_up_location_title, $drop_off_location_title, $total_price, $car_rental_obj->get_title());
							
							wp_mail($email, $subject, $message, $headers);

							$contact_email = get_post_meta($car_rental_id, 'car_rental_contact_email', true );
							$contact_emails = explode(';', $contact_email);
							if (empty($contact_email))
								$contact_emails = array($admin_email);	

							foreach ($contact_emails as $e) {
								if (!empty($e)) {
									wp_mail($e, $subject, $message, $headers);			
								}
							}
						}
					}
				}
			} 		
		}
		
		// Always die in functions echoing ajax content
		die();
	} 
		
	function cruise_type_is_repeated_ajax_request() {
		if ( isset($_REQUEST) ) {
			$nonce = wp_kses($_REQUEST['nonce'], '');
			$cruise_id = intval(wp_kses($_REQUEST['cruiseId'], ''));
			if (wp_verify_nonce( $nonce, 'cruise_schedule_entry_form' )) {
				$cruise_obj = new byt_cruise(intval($cruise_id));
				$cruise_type_is_repeated = $cruise_obj->get_type_is_repeated();
				echo $cruise_type_is_repeated;
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}
		
	function cruise_list_cabin_types_ajax_request() {

		if ( isset($_REQUEST) ) {
			$nonce = wp_kses($_REQUEST['nonce'], '');
			$cruise_id = intval(wp_kses($_REQUEST['cruiseId'], ''));
			if (wp_verify_nonce( $nonce, 'cruise_schedule_entry_form' )) {
				$cruise_obj = new byt_cruise($cruise_id);
				$cabin_types = array();		
				$cabin_type_ids = $cruise_obj->get_cabin_types();
				if ($cruise_obj && $cabin_type_ids && count($cabin_type_ids) > 0) { 				
					for ( $i = 0; $i < count($cabin_type_ids); $i++ ) {
						$temp_id = $cabin_type_ids[$i];
						$cabin_type_obj = new byt_cabin_type(intval($temp_id));
						$cabin_type_temp = new stdClass();
						$cabin_type_temp->name = $cabin_type_obj->get_title();
						$cabin_type_temp->id = $temp_id;
						$cabin_types[] = $cabin_type_temp;					
					}
				}
				
				echo json_encode($cabin_types);
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}
		
	function cruise_is_price_per_person_ajax_request() {
		if ( isset($_REQUEST) ) {
			$nonce = wp_kses($_REQUEST['nonce'], '');
			$cruise_id = intval(wp_kses($_REQUEST['cruiseId'], ''));
			if (wp_verify_nonce( $nonce, 'cruise_schedule_entry_form' )) {
				$is_price_per_person = get_post_meta( $cruise_id, 'cruise_is_price_per_person', true );
				echo $is_price_per_person ? 1 : 0;
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}
	
	function tour_type_is_repeated_ajax_request() {
		if ( isset($_REQUEST) ) {
			$nonce = wp_kses($_REQUEST['nonce'], '');
			$tour_id = intval(wp_kses($_REQUEST['tourId'], ''));
			if (wp_verify_nonce( $nonce, 'tour_schedule_entry_form' )) {
				$tour_obj = new byt_tour(intval($tour_id));
				$tour_type_is_repeated = $tour_obj->get_type_is_repeated();
				echo $tour_type_is_repeated;
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}
		
	function tour_is_price_per_group_ajax_request() {
		if ( isset($_REQUEST) ) {
			$nonce = wp_kses($_REQUEST['nonce'], '');
			$tour_id = intval(wp_kses($_REQUEST['tourId'], ''));
			if (wp_verify_nonce( $nonce, 'tour_schedule_entry_form' )) {
				$is_price_per_group = get_post_meta( $tour_id, 'tour_is_price_per_group', true );
				echo $is_price_per_group ? 1 : 0;
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}
	
	function force_upgrade_byt_database() {
	
		global $byt_accommodations_post_type, $byt_cruises_post_type, $byt_tours_post_type, $byt_theme_globals;
			
		if ($byt_theme_globals->enable_accommodations()) {
			$accommodation_results = $byt_accommodations_post_type->list_accommodations(0, -1, '', '', 0, array(), array(), array(), false, null, null, true);
			
			if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) {
				foreach ($accommodation_results['results'] as $accommodation_result) {
					$min_price = $byt_accommodations_post_type->get_accommodation_min_price($accommodation_result->ID);
					if (isset($min_price)) {
						$byt_accommodations_post_type->sync_accommodation_min_price($accommodation_result->ID, $min_price, true);
					} else {
						$byt_accommodations_post_type->sync_accommodation_min_price($accommodation_result->ID, 0, true);
					}
				}
			}
		}
		
		if ($byt_theme_globals->enable_tours()) {
			$tour_results = $byt_tours_post_type->list_tours(0, -1);
			if ( count($tour_results) > 0 && $tour_results['total'] > 0 ) {
				foreach ($tour_results['results'] as $tour_result) {
					$min_price = $byt_tours_post_type->get_tour_min_price($tour_result->ID);
					if (isset($min_price)) {
						$byt_tours_post_type->sync_tour_min_price($tour_result->ID, $min_price, true);
					} else {
						$byt_tours_post_type->sync_tour_min_price($tour_result->ID, 0, true);
					}
				}
			}
		}
		
		if ($byt_theme_globals->enable_cruises()) {
			$cruise_results = $byt_cruises_post_type->list_cruises(0, -1);
			if ( count($cruise_results) > 0 && $cruise_results['total'] > 0 ) {
				foreach ($cruise_results['results'] as $cruise_result) {
					$min_price = $byt_cruises_post_type->get_cruise_min_price($cruise_result->ID);
					if (isset($min_price)) {
						$byt_cruises_post_type->sync_cruise_min_price($cruise_result->ID, $min_price, true);
					} else {
						$byt_cruises_post_type->sync_cruise_min_price($cruise_result->ID, 0, true);
					}
				}
			}
		}
	}
	
	function upgrade_byt_database() {
	
		global $byt_accommodations_post_type, $byt_cruises_post_type, $byt_tours_post_type, $byt_theme_globals;
		if ( isset($_REQUEST) ) {
		
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'optionsframework-options' ) ) {
			
				$this->force_upgrade_byt_database();
				
				update_option( '_byt_needs_update', 0 );
				update_option( '_byt_version_before_update', BOOKYOURTRAVEL_VERSION );
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}

	function settings_ajax_save_password() {
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
				$user_id = intval(wp_kses($_REQUEST['userId'], ''));	
				$oldPassword = wp_kses($_REQUEST['oldPassword'], '');
				$password = wp_kses($_REQUEST['password'], '');
				
				$user = get_user_by( 'id', $user_id );
				if ( $user && wp_check_password( $oldPassword, $user->data->user_pass, $user->ID) )
				{
					// ok
					echo wp_update_user( array ( 'ID' => $user_id, 'user_pass' => $password ) ) ;
				}
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}

	function settings_ajax_save_email() {
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
				$email = wp_kses($_REQUEST['email'], '');
				$user_id = intval(wp_kses($_REQUEST['userId'], ''));	
				echo wp_update_user( array ( 'ID' => $user_id, 'user_email' => $email ) ) ;
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}

	function settings_ajax_save_last_name() {
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
				$lastName = wp_kses($_REQUEST['lastName'], '');
				$user_id = intval(wp_kses($_REQUEST['userId'], ''));	
				echo wp_update_user( array ( 'ID' => $user_id, 'last_name' => $lastName ) ) ;
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}

	function settings_ajax_save_first_name() {
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
				$firstName = wp_kses($_REQUEST['firstName'], '');
				$user_id = intval(wp_kses($_REQUEST['userId'], ''));	
				echo wp_update_user( array ( 'ID' => $user_id, 'first_name' => $firstName ) ) ;
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}

	function book_accommodation_ajax_request() {

		global $byt_accommodations_post_type, $byt_theme_globals, $byt_theme_woocommerce;

		$enc_key = $byt_theme_globals->get_enc_key();
		$add_captcha_to_forms = $byt_theme_globals->add_captcha_to_forms();

		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
								
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
				$first_name = isset($_REQUEST['first_name']) ? wp_kses($_REQUEST['first_name'], '') : '';
				$last_name = isset($_REQUEST['last_name']) ? wp_kses($_REQUEST['last_name'], '') : '';
				$email = isset($_REQUEST['email']) ? wp_kses($_REQUEST['email'], '') : '';
				$phone = isset($_REQUEST['phone']) ? wp_kses($_REQUEST['phone'], '') : '';
				$address = isset($_REQUEST['address']) ? wp_kses($_REQUEST['address'], '') : '';
				$town = isset($_REQUEST['town']) ? wp_kses($_REQUEST['town'], '') : '';
				$zip = isset($_REQUEST['zip']) ? wp_kses($_REQUEST['zip'], '') : '';
				$country = isset($_REQUEST['country']) ? wp_kses($_REQUEST['country'], '') : '';
				$special_requirements = isset($_REQUEST['special_requirements']) ? wp_kses($_REQUEST['special_requirements'], '') : '';

				$date_from = isset($_REQUEST['date_from']) ? date('Y-m-d', strtotime(wp_kses($_REQUEST['date_from'], ''))) : null;
				$date_to = isset($_REQUEST['date_to']) ? date('Y-m-d', strtotime(wp_kses($_REQUEST['date_to'], ''))) : null;

				$accommodation_id = isset($_REQUEST['accommodation_id']) ? intval(wp_kses($_REQUEST['accommodation_id'], '')) : 0;
				$room_type_id = isset($_REQUEST['room_type_id']) ? intval(wp_kses($_REQUEST['room_type_id'], '')) : 0;
				$room_count = isset($_REQUEST['room_count']) ? intval(wp_kses($_REQUEST['room_count'], '')) : 1;
				$adults = isset($_REQUEST['adults']) ? intval(wp_kses($_REQUEST['adults'], '')) : 1;
				$children = isset($_REQUEST['children']) ? intval(wp_kses($_REQUEST['children'], '')) : 0;

				$c_val_s = isset($_REQUEST['c_val_s']) ? intval(wp_kses($_REQUEST['c_val_s'], '')) : 0;
				$c_val_1 = isset($_REQUEST['c_val_1']) ? intval(BYT_Theme_Utils::decrypt(wp_kses($_REQUEST['c_val_1'], ''), $enc_key)) : 0;
				$c_val_2 = isset($_REQUEST['c_val_2']) ? intval(BYT_Theme_Utils::decrypt(wp_kses($_REQUEST['c_val_2'], ''), $enc_key)) : 0;
				
				// nonce passed ok
				$accommodation = get_post($accommodation_id);
				if ($room_type_id)
					$room_type = get_post($room_type_id);
				
				if ($accommodation != null) {
				
					if ($add_captcha_to_forms && $c_val_s != ($c_val_1 + $c_val_2)) {
						echo 'captcha_error';
						die();
					} else {
						
						$is_self_catered = get_post_meta( $accommodation_id, 'accommodation_is_self_catered', true );
						$is_reservation_only = get_post_meta( $accommodation_id, 'accommodation_is_reservation_only', true );
						$current_user = wp_get_current_user();
						$total_price = $byt_accommodations_post_type->calculate_total_price($accommodation_id, $room_type_id, $date_from, $date_to, $room_count, $adults, $children);
						
						$booking_id = $byt_accommodations_post_type->create_accommodation_booking ($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $date_from, $date_to, $accommodation_id, $room_type_id, $current_user->ID, $is_self_catered, $total_price, $adults, $children);

						$use_woocommerce_for_checkout = $byt_theme_globals->use_woocommerce_for_checkout();
						if (BYT_Theme_Utils::is_woocommerce_active() && !$is_reservation_only) {
							if ($use_woocommerce_for_checkout) {
								$product_id = $byt_theme_woocommerce->woocommerce_create_product($accommodation->post_title, '', 'ACC_' . $accommodation_id . '_', $booking_id, $total_price, BOOKYOURTRAVEL_WOO_PRODUCT_CAT_ACCOMMODATIONS); 
								echo $product_id;
							}
						} else {
							echo $booking_id;
						}

						if (!$use_woocommerce_for_checkout || !BYT_Theme_Utils::is_woocommerce_active()) {
						
							// only send email if we are not proceeding to WooCommerce checkout or if woocommerce is not active at all.
						
							$admin_email = get_bloginfo('admin_email');
							$admin_name = get_bloginfo('name');
							
							$headers = "From: $admin_name <$admin_email>\n";
							$subject = __('New accommodation booking', 'bookyourtravel');
							$message = '';
							if ($is_self_catered) {
								$message = __("New self-catered booking: \n\nFirst name: %s \n\nLast name: %s \n\nEmail: %s \n\nPhone: %s \n\nAddress: %s \n\nTown: %s \n\nZip: %s \n\nCountry: %s \n\nSpecial requirements: %s \n\nAdults: %s \n\nChildren: %s \n\nDate from: %s \n\nDate to: %s \n\nTotal price: %d \n\nAccommodation: %s", 'bookyourtravel');	
								$message = sprintf($message, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $date_from, $date_to, $total_price, $accommodation->post_title);
							} else {
								$message = __("New hotel booking: \n\nFirst name: %s \n\nLast name: %s \n\nEmail: %s \n\nPhone: %s \n\nAddress: %s \n\nTown: %s \n\nZip: %s \n\nCountry: %s \n\nSpecial requirements: %s \n\nRoom count: %d \n\nAdults: %s \n\nChildren: %s \n\nDate from: %s \n\nDate to: %s \n\nTotal price: %d \n\nAccommodation: %s \n\nRoom type: %s \n", 'bookyourtravel');
								$message = sprintf($message, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $adults, $children, $date_from, $date_to, $total_price, $accommodation->post_title, $room_type->post_title);
							}
						
							wp_mail($email, $subject, $message, $headers);

							$contact_email = get_post_meta($accommodation_id, 'accommodation_contact_email', true );
							$contact_emails = explode(';', $contact_email);
							if (empty($contact_email))
								$contact_emails = array($admin_email);	

							foreach ($contact_emails as $e) {
								if (!empty($e)) {
									wp_mail($e, $subject, $message, $headers);			
								}
							}
						}
					}
				}
			} 		
		}
		
		// Always die in functions echoing ajax content
		die();
	} 

	function accommodation_get_price_request() {
	
		global $byt_accommodations_post_type, $byt_theme_globals;
		
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$accommodation_id = isset($_REQUEST['accommodationId']) ? intval(wp_kses($_REQUEST['accommodationId'], '')) : 0;	
				$room_type_id = isset($_REQUEST['roomTypeId']) ? intval(wp_kses($_REQUEST['roomTypeId'], '')) : 0;	
				$dateValue = isset($_REQUEST['dateValue']) ? wp_kses($_REQUEST['dateValue'], '') : null;	
				$dateTime = strtotime($dateValue);
				$dateValue = date('Y-m-d', $dateTime);
		
				$price_decimal_places = $byt_theme_globals->get_price_decimal_places();

				if ($accommodation_id > 0) {				
					$price_per_day = number_format ($byt_accommodations_post_type->get_accommodation_price($dateValue, $accommodation_id, $room_type_id, false), $price_decimal_places, ".", "");
					$child_price = number_format ($byt_accommodations_post_type->get_accommodation_price($dateValue, $accommodation_id, $room_type_id, true), $price_decimal_places, ".", "");
		
					$prices = array( 
						'price_per_day' => $price_per_day, 
						'child_price' => $child_price 
					);
					
					echo json_encode($prices);
				}
			}
		}
		
		die();
	}
		
	function accommodation_available_start_dates_request() {
		
		global $byt_accommodations_post_type;
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$accommodation_id = isset($_REQUEST['accommodationId']) ? intval(wp_kses($_REQUEST['accommodationId'], '')) : 0;	
				$room_type_id = isset($_REQUEST['roomTypeId']) ? intval(wp_kses($_REQUEST['roomTypeId'], '')) : 0;	
				$month = isset($_REQUEST['month']) ? intval(wp_kses($_REQUEST['month'], '')) : 0;	
				$year = isset($_REQUEST['year']) ? intval(wp_kses($_REQUEST['year'], '')) : 0;	
			
				if ($accommodation_id > 0) {
					
					$available_dates = $byt_accommodations_post_type->list_accommodation_vacancy_start_dates($accommodation_id, $room_type_id, $month, $year);
					echo json_encode($available_dates);
				}
			}
		}
		
		die();
	}

	function accommodation_available_end_dates_request() {

		global $byt_accommodations_post_type;
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {

				$accommodation_id = isset($_REQUEST['accommodationId']) ? intval(wp_kses($_REQUEST['accommodationId'], '')) : 0;	
				$room_type_id = isset($_REQUEST['roomTypeId']) ? intval(wp_kses($_REQUEST['roomTypeId'], '')) : 0;	
				$month = isset($_REQUEST['month']) ? intval(wp_kses($_REQUEST['month'], '')) : 0;	
				$year = isset($_REQUEST['year']) ? intval(wp_kses($_REQUEST['year'], '')) : 0;	
				$start_date = isset($_REQUEST['startDate']) ? wp_kses($_REQUEST['startDate'], '') : null;
				$day = isset($_REQUEST['day']) ? intval(wp_kses($_REQUEST['day'], '')) : 0;	
			
				if ($accommodation_id > 0) {				
					$available_dates = $byt_accommodations_post_type->list_accommodation_vacancy_end_dates($start_date, $accommodation_id, $room_type_id, $month, $year, $day);
					echo json_encode($available_dates);
				}
			}
		}
		
		die();
	}
	
	function inquiry_ajax_request() {
	
		global $byt_theme_globals;

		if ( isset($_REQUEST) ) {

			$enc_key = $byt_theme_globals->get_enc_key();
			$add_captcha_to_forms = $byt_theme_globals->add_captcha_to_forms();
		
			$your_name = wp_kses($_REQUEST['your_name'], '');
			$your_email = wp_kses($_REQUEST['your_email'], '');
			$your_phone = wp_kses($_REQUEST['your_phone'], '');
			$your_message = wp_kses($_REQUEST['your_message'], '');
			$postId = intval(wp_kses($_REQUEST['postId'], ''));	
			$user_id = intval(wp_kses($_REQUEST['userId'], ''));
			
			$c_val_s = intval(wp_kses($_REQUEST['c_val_s'], ''));
			$c_val_1_str = BYT_Theme_Utils::decrypt(wp_kses($_REQUEST['c_val_1'], ''), $enc_key);
			$c_val_2_str = BYT_Theme_Utils::decrypt(wp_kses($_REQUEST['c_val_2'], ''), $enc_key);
			$c_val_1 = intval($c_val_1_str);
			$c_val_2 = intval($c_val_2_str);
			
			$nonce = $_REQUEST['nonce'];
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			
				if ($add_captcha_to_forms && $c_val_s != ($c_val_1 + $c_val_2)) {
					
					echo 'captcha_error';
					die();
					
				} else {
				
					// nonce passed ok
					$post = get_post($postId);
					
					if ($post) {

						$admin_email = get_bloginfo('admin_email');
						$contact_email = get_post_meta($postId, $post->post_type . '_contact_email', true );
						$contact_emails = explode(';', $contact_email);
						if (empty($contact_email))
							$contact_emails = array($admin_email);	
					
						$subject = __('New inquiry', 'bookyourtravel');	
						
						$message = __("The following inquiry has just arrived: \n Name: %s \n Email: %s \n Phone: %s \n Message: %s \n Inquiring about: %s \n", 'bookyourtravel');
						$message = sprintf($message, $your_name, $your_email, $your_phone, $your_message, $post->post_title);

						$headers   = array();
						$headers[] = "MIME-Version: 1.0";
						$headers[] = "Content-type: text/plain; charset=utf-8";
						$headers[] = "From: " . get_bloginfo( 'name' ) . " <" . $admin_email . ">";
						$headers[] = "Reply-To: " . get_bloginfo( 'name' ) . " <" . $admin_email . ">";
						$headers[] = "X-Mailer: PHP/".phpversion();
						
						$headers_str = implode( "\r\n", $headers );
						
						foreach ($contact_emails as $email) {
							if (!empty($email)) {
								wp_mail($email, $subject, $message, $headers_str, '-f ' . $admin_email);
							}
						}
					}
				}
				
			} 
		}
		
		// Always die in functions echoing ajax content
		die();
	}
	
	function accommodation_is_self_catered_ajax_request() {
	
		if ( isset($_REQUEST) ) {
			$nonce = wp_kses($_REQUEST['nonce'], '');
			$accommodation_id = intval(wp_kses($_REQUEST['accommodationId'], ''));
			if (wp_verify_nonce( $nonce, 'accommodation_vacancy_nonce' )) {
				$is_self_catered = get_post_meta( $accommodation_id, 'accommodation_is_self_catered', true );
				echo $is_self_catered ? 1 : 0;
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}

	function accommodation_list_room_types_ajax_request() {

		if ( isset($_REQUEST) ) {
		
			$nonce = wp_kses($_REQUEST['nonce'], '');
			$accommodation_id = intval(wp_kses($_REQUEST['accommodationId'], ''));
			if (wp_verify_nonce( $nonce, 'accommodation_vacancy_nonce' )) {
			
				$accommodation_obj = new byt_accommodation((int)$accommodation_id);
				$room_types = array();			
				$room_type_ids = $accommodation_obj->get_room_types();
				if ($accommodation_obj && $room_type_ids && count($room_type_ids) > 0) { 				
					for ( $i = 0; $i < count($room_type_ids); $i++ ) {
						$temp_id = $room_type_ids[$i];
						$room_type_obj = new byt_room_type(intval($temp_id));
						$room_type_temp = new stdClass();
						$room_type_temp->name = $room_type_obj->get_title();
						$room_type_temp->id = $room_type_obj->get_id();
						$room_types[] = $room_type_temp;					
					}
				}
				
				echo json_encode($room_types);
			}
		}
		
		// Always die in functions echoing ajax content
		die();		
	}

	function accommodation_is_price_per_person_ajax_request() {
	
		if ( isset($_REQUEST) ) {
		
			$nonce = wp_kses($_REQUEST['nonce'], '');
			$accommodation_id = intval(wp_kses($_REQUEST['accommodationId'], ''));
			if (wp_verify_nonce( $nonce, 'accommodation_vacancy_nonce' )) {
				$is_price_per_person = get_post_meta( $accommodation_id, 'accommodation_is_price_per_person', true );
				echo $is_price_per_person ? 1 : 0;
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}
		
	function review_ajax_request() {

		if ( isset($_REQUEST) ) {
		
			global $byt_reviews_post_type;
		
			$likes = wp_kses($_REQUEST['likes'], '');
			$dislikes = wp_kses($_REQUEST['dislikes'], '');
			$reviewed_post_id = intval(wp_kses($_REQUEST['postId'], ''));	
			$user_id = intval(wp_kses($_REQUEST['userId'], ''));	
			$nonce = $_REQUEST['nonce'];
			
			if ( wp_verify_nonce( $nonce, 'byt-ajax-nonce' ) ) {
			
				// nonce passed ok
				$reviewed_post = get_post($reviewed_post_id);
				$review_fields = $byt_reviews_post_type->list_review_fields($reviewed_post->post_type);
				$user_info = get_userdata($user_id);
				
				if ($reviewed_post != null && $user_info != null && count($review_fields) > 0) {
				
					$reviewed_post_title = get_the_title($reviewed_post_id);
					
					$review_post = array(
						'post_title'    => sprintf(__('Review of %s by %s [%s]', 'bookyourtravel'), $reviewed_post_title, $user_info->user_nicename, $user_id),
						'post_status'   => 'publish',
						'post_author'   => $user_id,
						'post_type' 	=> 'review',
						'post_date' => date('Y-m-d H:i:s')					
					);

					// Insert the post into the database
					$review_post_id = wp_insert_post( $review_post );
					
					if( ! is_wp_error( $review_post_id ) ) {
					
						$new_score_sum = 0;
						foreach ($review_fields as $review_field) {
							$field_id = $review_field['id'];
							$field_value = isset($_REQUEST['reviewField_' . $field_id]) ? intval(wp_kses($_REQUEST['reviewField_' . $field_id], '')) : 0;
							$new_score_sum += $field_value;
							add_post_meta($review_post_id, $field_id, $field_value);
						}
						
						$review_score = floatval(get_post_meta($reviewed_post_id, 'review_score', true));
						$review_score = $review_score ? $review_score : 0;
						
						$review_sum_score = floatval(get_post_meta($reviewed_post_id, 'review_sum_score', true));
						$review_sum_score = $review_sum_score ? $review_sum_score : 0;
						
						$review_count = intval($byt_reviews_post_type->get_reviews_count($reviewed_post_id));
						$review_count = $review_count ? $review_count : 0;
						$review_count++;
						
						$review_sum_score = $review_sum_score + $new_score_sum;
						$new_review_score = $new_score_sum / (count($review_fields) * 10);
						$review_score = ($review_score + $new_review_score) / $review_count;					
						
						add_post_meta($review_post_id, 'review_likes', $likes);
						add_post_meta($review_post_id, 'review_dislikes', $dislikes);
						add_post_meta($review_post_id, 'review_post_id', $reviewed_post_id);

						update_post_meta($reviewed_post_id, 'review_sum_score', $review_sum_score);
						update_post_meta($reviewed_post_id, 'review_score', $review_score);		
						update_post_meta($reviewed_post_id, 'review_count', $review_count);	
					}
					
					echo $review_post_id;
				}
			} else { 
				echo 'nonce fail';
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}

	function sync_reviews_ajax_request() {
	
		global $byt_reviews_post_type, $byt_theme_globals;
		if ( isset($_REQUEST) ) {
			$nonce = $_REQUEST['nonce'];
			
			if ( wp_verify_nonce( $nonce, 'optionsframework-options' ) ) {
			
				$enable_accommodations = $byt_theme_globals->enable_accommodations(); 
				if ($enable_accommodations)
					$byt_reviews_post_type->recalculate_review_scores('accommodation');
				
				$enable_tours = of_get_option('enable_tours', 1); 
				if ($enable_tours)
					$byt_reviews_post_type->recalculate_review_scores('tour');
					
				$enable_cruises = of_get_option('enable_cruises', 1); 
				if ($enable_cruises)
					$byt_reviews_post_type->recalculate_review_scores('cruises');
			
				echo '1';
			} else {
				echo '0';
			}
		}
		die();
	}
}

// store the instance in a variable to be retrieved later and call init
$byt_theme_ajax = BYT_Theme_Ajax::get_instance();
$byt_theme_ajax->init();