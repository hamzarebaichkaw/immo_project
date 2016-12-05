<?php

if ( ! defined( 'BOOKYOURTRAVEL_WOO_PRODUCT_CAT_ACCOMMODATIONS' ) )
    define( 'BOOKYOURTRAVEL_WOO_PRODUCT_CAT_ACCOMMODATIONS', 'accommodations_woo' );
if ( ! defined( 'BOOKYOURTRAVEL_WOO_PRODUCT_CAT_TOURS' ) )
    define( 'BOOKYOURTRAVEL_WOO_PRODUCT_CAT_TOURS', 'tours_woo' );
if ( ! defined( 'BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CAR_RENTALS' ) )
    define( 'BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CAR_RENTALS', 'car_rentals_woo' );
if ( ! defined( 'BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CRUISES' ) )
    define( 'BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CRUISES', 'cruises_woo' );
	
class BYT_Theme_WooCommerce extends BYT_BaseSingleton {

	protected function __construct() {
	
        // our parent class might contain shared code in its constructor
        parent::__construct();
		
    }

    public function init() {
	
		if (BYT_Theme_Utils::is_woocommerce_active()) {

			add_action('init', array( $this, 'woocommerce_init'));
		}	
	}
		
	function woocommerce_init() {
		
		// create our Accommodation, Tour and Car Rental woocommerce categories
		if (!term_exists(BOOKYOURTRAVEL_WOO_PRODUCT_CAT_ACCOMMODATIONS, 'product_cat')) {
			$this->woocommerce_create_product_category(BOOKYOURTRAVEL_WOO_PRODUCT_CAT_ACCOMMODATIONS, __('Accommodations', 'bookyourtravel'), __('Accommodations category', 'bookyourtravel'));
		}
		if (!term_exists(BOOKYOURTRAVEL_WOO_PRODUCT_CAT_TOURS, 'product_cat')) {
			$this->woocommerce_create_product_category(BOOKYOURTRAVEL_WOO_PRODUCT_CAT_TOURS, __('Tours', 'bookyourtravel'), __('Tours Category', 'bookyourtravel'));
		}
		if (!term_exists(BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CAR_RENTALS, 'product_cat')) {
			$this->woocommerce_create_product_category(BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CAR_RENTALS, __('Car rentals', 'bookyourtravel'), __('Car Rentals Category', 'bookyourtravel'));
		}
		if (!term_exists(BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CRUISES, 'product_cat')) {
			$this->woocommerce_create_product_category(BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CRUISES, __('Cruises', 'bookyourtravel'), __('Cruises Category', 'bookyourtravel'));
		}
		
		// modify cart item name and link
		add_filter('woocommerce_cart_item_name', array( $this, 'woocommerce_modify_cart_product_title'), 20, 3);
		add_filter('woocommerce_order_item_name', array( $this, 'woocommerce_modify_order_product_title'), 20, 2);
		
		// prefill checkout form
		add_filter('woocommerce_checkout_get_value', array( $this, 'woocommerce_checkout_get_value'), 20, 2);

		add_action('woocommerce_order_status_completed', array( $this, 'woocommerce_handle_woo_payment') ); // Add reservetions to Booking System after payment has been completed.
		add_action('woocommerce_order_status_pending_to_processing', array( $this, 'woocommerce_handle_woo_payment' )); // Add reservetions to Booking System after payment has been completed.
		add_action('woocommerce_order_status_pending_to_on-hold', array( $this, 'woocommerce_handle_woo_payment') ); // Add reservetions to Booking System after payment has been completed.
		add_action('woocommerce_order_status_failed_to_processing', array( $this, 'woocommerce_handle_woo_payment' ) ); // Add reservetions to Booking System after payment has been completed.

		// remove woocommerce breadcrumbs since BYT has it's own
		remove_action( 'woocommerce_before_main_content', array( $this, 'woocommerce_breadcrumb' ), 20);
		
		// override woocommerce template to not show 
		// single-product.php, taxonomy-product_cat.php, taxonomy-product_tag.php, archive-product.php
		// as we don't need them for byt
		add_filter( 'template_include', array($this, 'woocommerce_template_include' ));
	}
		
	function woocommerce_add_to_cart_action($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data){

		global $woocommerce;
		global $wpdb;

		// Check each item in the cart
		foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
			$product_id = $values['product_id'];
			$_product = $values['data'];

			$attributes = $_product->get_attributes();
			if (isset($attributes['byt-booking-id'])) {
				$booking_id = $attributes['byt-booking-id']['value'];			
				$product_terms = wp_get_object_terms($product_id, 'product_cat');
				if ($product_terms && count($product_terms) > 0) {
					if( !is_wp_error( $product_terms ) ){
						foreach($product_terms as $term){
							if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_ACCOMMODATIONS) {
								$data = array( 'cart_key' => $cart_item_key, 'currency_code' => get_woocommerce_currency() );							
								$wpdb->update(BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE, $data, array('Id' => $booking_id));
							} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_TOURS) {
								$data = array( 'cart_key' => $cart_item_key, 'currency_code' => get_woocommerce_currency() );
								$wpdb->update(BOOKYOURTRAVEL_TOUR_BOOKING_TABLE, $data, array('Id' => $booking_id));
							} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CAR_RENTALS) {
								$data = array( 'cart_key' => $cart_item_key, 'currency_code' => get_woocommerce_currency() );
								$wpdb->update(BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE, $data, array('Id' => $booking_id));
							} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CRUISES) {
								$data = array( 'cart_key' => $cart_item_key, 'currency_code' => get_woocommerce_currency() );
								$wpdb->update(BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CRUISES, $data, array('Id' => $booking_id));
							}
						}
					}
				}
			}

		}
	}

	function woocommerce_handle_woo_cart_delete_item(){ // Delete item from database when is deleted from cart.
		global $wpdb;
		
		if (isset($_REQUEST['remove_item'])){
			$cart_key = $_REQUEST['remove_item'];
			
			$sql = $wpdb->prepare('SELECT * FROM ' . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . ' WHERE cart_key = %s', $cart_key); 
			$booking = $wpdb->get_row($sql);
			if ($booking) {
				$wpdb->delete(BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE, array('cart_key' => $cart_key));
				return;
			}

			$sql = $wpdb->prepare('SELECT * FROM ' . BOOKYOURTRAVEL_TOUR_BOOKING_TABLE . ' WHERE cart_key = %s', $cart_key); 
			$booking = $wpdb->get_row($sql);
			if ($booking) {
				$wpdb->delete(BOOKYOURTRAVEL_TOUR_BOOKING_TABLE, array('cart_key' => $cart_key));
				return; 
			}
			
			$sql = $wpdb->prepare('SELECT * FROM ' . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . ' WHERE cart_key = %s', $cart_key); 
			$booking = $wpdb->get_row($sql);
			if ($booking) {
				$wpdb->delete(BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE, array('cart_key' => $cart_key));
				return;
			}
			
			$sql = $wpdb->prepare('SELECT * FROM ' . BOOKYOURTRAVEL_CRUISE_BOOKINGS_TABLE . ' WHERE cart_key = %s', $cart_key); 
			$booking = $wpdb->get_row($sql);
			if ($booking) {
				$wpdb->delete(BOOKYOURTRAVEL_CRUISE_BOOKINGS_TABLE, array('cart_key' => $cart_key));
				return;
			}
		}
	}

	function woocommerce_handle_woo_payment($order_id) {

		global $wpdb;
		global $woocommerce;
		
		$order = new WC_Order( $order_id );
		$items = $order->get_items();

		foreach ( $items as $item ) {
			$product_name = $item['name'];
			$product_id = $item['product_id'];
			
			$_pf = new WC_Product_Factory();  
			$_product = $_pf->get_product($product_id);
			
			$attributes = $_product->get_attributes();
			if (isset($attributes['byt-booking-id'])) {
				$booking_id = $attributes['byt-booking-id']['value'];
				
				$product_terms = wp_get_object_terms($product_id, 'product_cat');
				if ($product_terms && count($product_terms) > 0) {
					if( !is_wp_error( $product_terms ) ){
						foreach($product_terms as $term){
							$datasec = array('woo_order_id' => $order_id);
							if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_ACCOMMODATIONS) {
								$wpdb->update(BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE, $datasec, array('Id' => $booking_id));
							} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_TOURS) {
								$wpdb->update(BOOKYOURTRAVEL_TOUR_BOOKING_TABLE, $datasec, array('Id' => $booking_id));
							} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CAR_RENTALS) {
								$wpdb->update(BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE, $datasec, array('Id' => $booking_id));
							} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CRUISES) {
								$wpdb->update(BOOKYOURTRAVEL_CRUISE_BOOKINGS_TABLE, $datasec, array('Id' => $booking_id));
							}
						}
					}
				}
			}
		}
	}

	function woocommerce_checkout_get_value($x, $input) {
		
		$value = null;
		global $byt_accommodations_post_type, $byt_tours_post_type, $byt_car_rentals_post_type, $byt_cruises_post_type;
		
		if ($input == 'billing_first_name' ||
			$input == 'billing_last_name' ||
			$input == 'billing_address_1' ||
			$input == 'billing_city' ||
			$input == 'billing_phone' ||
			$input == 'billing_email' ||
			$input == 'billing_postcode') {
			
			if (count(WC()->cart->get_cart()) > 0) {
				$cart = (WC()->cart->get_cart());
				reset($cart);
				$first_key = key($cart);
				$first_product = $cart[$first_key]['data'];
				$first_product_id = $cart[$first_key]['product_id'];
				
				$attributes = $first_product->get_attributes();
				if (isset($attributes['byt-booking-id'])) {
					$booking_id = $attributes['byt-booking-id']['value'];
					
					$product_terms = wp_get_object_terms($first_product_id, 'product_cat');
					if ($product_terms && count($product_terms) > 0) {
						if( !is_wp_error( $product_terms ) ){
							foreach($product_terms as $term){
								if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_ACCOMMODATIONS) {
								
									$booking = $byt_accommodations_post_type->get_accommodation_booking($booking_id);
								} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_TOURS) {
								
									$booking = $byt_tours_post_type->get_tour_booking($booking_id);
								} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CAR_RENTALS) {
								
									$booking = $byt_car_rentals_post_type->get_car_rental_booking($booking_id);
								} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CRUISES) {
								
									$booking = $byt_cruises_post_type->get_cruise_booking($booking_id);
								}
								if ($booking) {
									if ($input == 'billing_first_name')
										$value = $booking->first_name;							
									else if ($input == 'billing_last_name')
										$value = $booking->last_name;	
									else if ($input == 'billing_address_1')
										$value = $booking->address;	
									else if ($input == 'billing_city')
										$value = $booking->town;	
									else if ($input == 'billing_phone')
										$value = $booking->phone;	
									else if ($input == 'billing_email')
										$value = $booking->email;	
									else if ($input == 'billing_postcode')
										$value = $booking->zip;	
									break;
								}
							}
						}
					}
				}
			}
		}
		
		return $value;
	}

	function woocommerce_create_product_category($term_slug, $term_name, $term_description)
	{
		wp_insert_term(
			$term_name,
			'product_cat', // the taxonomy
			array(
				'description'=> $term_description,
				'slug' => $term_slug,
			)
		);
	}

	function woocommerce_template_include( $template ) {

		$find = array( );
		$file = '';

		if ( is_single() && get_post_type() == 'product' ) {
			$file 	= '404.php';
			$find[] = $file;
		} elseif ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
			$file 	= '404.php';
			$find[] = $file;
		} elseif ( is_post_type_archive( 'product' ) ) {
			$file 	= '404.php';
			$find[] = $file;
		} else if (function_exists('wc_get_page_id') && is_page( wc_get_page_id( 'shop' ) ) ) {
			$file 	= '404.php';
			$find[] = $file;
		}

		if ( $file ) {
			$template = locate_template( $find );
		}
		
		return $template;
	}

	function woocommerce_create_product($product_name, $product_content, $sku_prefix, $booking_id, $booking_price, $product_category_slug){
		
		$booking_product = array(
			'post_title' => $product_name,
			'post_content' => $product_content,
			'post_status' => 'publish',
			'post_type' => 'product',
			'comment_status' => 'closed'
		);
		$booking_product_post_id = wp_insert_post($booking_product);

		$default_attributes = array();
		
		$skuu = $this->woocommerce_random_sku($sku_prefix, 6);
		
		update_post_meta( $booking_product_post_id, '_sku', $skuu );
		update_post_meta( $booking_product_post_id, '_stock_status', 'instock');
		update_post_meta( $booking_product_post_id, '_visibility', 'visible');
		update_post_meta( $booking_product_post_id, '_downloadable', 'no');
		update_post_meta( $booking_product_post_id, '_virtual', 'no');
		update_post_meta( $booking_product_post_id, '_featured', 'no');
		update_post_meta( $booking_product_post_id, '_sold_individually', 'yes');
		update_post_meta( $booking_product_post_id, '_default_attributes', $default_attributes);
		update_post_meta( $booking_product_post_id, '_manage_stock', 'no');
		update_post_meta( $booking_product_post_id, '_backorders', 'no');
		update_post_meta( $booking_product_post_id, '_regular_price', $booking_price);
		update_post_meta( $booking_product_post_id, '_price', $booking_price);
		
		wp_set_object_terms ($booking_product_post_id, 'simple', 'product_type' );
		wp_set_object_terms ($booking_product_post_id, $product_category_slug, 'product_cat' );
		
		$product_attributes = array(
			'byt-booking-id'=> array(
				'name' => 'BYT Booking Id',
				'value' => $booking_id,
				'position' => '0',
				'is_visible' => '1',
				'is_variation' => '0',
				'is_taxonomy' => '0'
			)
		);
		
		update_post_meta( $booking_product_post_id, '_product_attributes', $product_attributes);
		
		$this->woocommerce_add_product_to_cart($booking_product_post_id);
		
		return $booking_product_post_id;
	}

	function woocommerce_modify_order_product_title($product_title, $item) {

		global $byt_accommodations_post_type, $byt_tours_post_type, $byt_car_rentals_post_type, $byt_cruises_post_type;
		$date_format = get_option('date_format');
		
		global $order_id;
		$order = new WC_Order( $order_id );
		$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
		$product_id   = $item['product_id'];

		$attributes = $_product->get_attributes();
		if (isset($attributes['byt-booking-id'])) {
			$booking_id = $attributes['byt-booking-id']['value'];
			$is_accommodation = false;
			$is_tour = false;
			$is_car_rental = false;
			$is_cruise = false;
			
			$product_terms = wp_get_object_terms($product_id, 'product_cat');
			if ($product_terms && count($product_terms) > 0) {
				if( !is_wp_error( $product_terms ) ){
					foreach($product_terms as $term){
						if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_ACCOMMODATIONS) {
							$is_accommodation = true;
							break;
						} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_TOURS) {
							$is_tour = true;
							break;					
						} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CAR_RENTALS) {
							$is_car_rental = true;
							break;					
						} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CRUISES) {
							$is_cruise = true;
							break;					
						}
					}
				}
			}
			
			if ($is_accommodation) {
				$booking = $byt_accommodations_post_type->get_accommodation_booking($booking_id);
				$date_from = date($date_format, strtotime($booking->date_from));
				$date_to = date($date_format, strtotime($booking->date_to));
				$product_title = 	'<strong>' . $booking->accommodation_name . '</strong>' . 
									'<br />' . $booking->room_type . 
									'<br />' . sprintf(__('%d adults', 'bookyourtravel'), $booking->adults) . ', ' . sprintf(__('%d children', 'bookyourtravel'), $booking->children) .
									'<br />' . $date_from .  
									__(' to ', 'bookyourtravel') . $date_to;			
			} else if ($is_tour) {
				$booking = $byt_tours_post_type->get_tour_booking($booking_id);
				$tour_date = date($date_format, strtotime($booking->tour_date));
				$product_title = '<strong>' . $booking->tour_name . '</strong>' . 
								'<br />' . __('Tour date:', 'bookyourtravel') . ' ' . $tour_date . 			
								'<br />' . sprintf(__('Tour duration: %d days', 'bookyourtravel'), $booking->duration_days);			
			} else if ($is_car_rental) {
				$booking = $byt_car_rentals_post_type->get_car_rental_booking($booking_id);
				$from_day = date($date_format, strtotime($booking->from_day));
				$to_day = date($date_format, strtotime($booking->to_day));
				$product_title = '<strong>' . $booking->car_rental_name . '</strong>' . '<br />' . $from_day . __(' to ', 'bookyourtravel') . $to_day;			
			} else if ($is_cruise) {
				$booking = $byt_cruises_post_type->get_cruise_booking($booking_id);
				$cruise_date = date($date_format, strtotime($booking->cruise_date));
				$product_title = '<strong>' . $booking->cruise_name . '</strong>' . 
								'<br />' . $booking->cabin_type . 
								'<br />' . __('Cruise date:', 'bookyourtravel') . ' ' . $cruise_date . 			
								'<br />' . sprintf(__('Cruise duration: %d days', 'bookyourtravel'), $booking->duration_days);			
			}
		}
		
		return $product_title;
	}

	function woocommerce_modify_cart_product_title($product_title, $cart_item, $cart_item_key) {

		global $byt_accommodations_post_type, $byt_tours_post_type, $byt_car_rentals_post_type, $byt_cruises_post_type;
		$date_format = get_option('date_format');

		$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

		$attributes = $_product->get_attributes();
		if (isset($attributes['byt-booking-id'])) {
			$booking_id = $attributes['byt-booking-id']['value'];
			
			$is_accommodation = false;
			$is_tour = false;
			$is_car_rental = false;
			$is_cruise = false;
			
			$product_terms = wp_get_object_terms($product_id, 'product_cat');
			if ($product_terms && count($product_terms) > 0) {
				if( !is_wp_error( $product_terms ) ){
					foreach($product_terms as $term){
						if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_ACCOMMODATIONS) {
							$is_accommodation = true;
							break;
						} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_TOURS) {
							$is_tour = true;
							break;					
						} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CAR_RENTALS) {
							$is_car_rental = true;
							break;					
						} else if ($term->slug == BOOKYOURTRAVEL_WOO_PRODUCT_CAT_CRUISES) {
							$is_cruise = true;
							break;					
						}
					}
				}
			}
			
			if ($is_accommodation) {
				$booking = $byt_accommodations_post_type->get_accommodation_booking($booking_id);
				if ($booking) {
					$date_from = date($date_format, strtotime($booking->date_from));
					$date_to = date($date_format, strtotime($booking->date_to));
					$product_title = 	'<strong>' . $booking->accommodation_name . '</strong>' . 
										'<br />' . $booking->room_type . 
										'<br />' . sprintf(__('%d adults', 'bookyourtravel'), $booking->adults) . ', ' . sprintf(__('%d children', 'bookyourtravel'), $booking->children) .
										'<br />' . $date_from .  
										__(' to ', 'bookyourtravel') . $date_to;			
				}
			} else if ($is_tour) {
				$booking = $byt_tours_post_type->get_tour_booking($booking_id);
				if (isset($booking)) {
					$tour_date = date($date_format, strtotime($booking->tour_date));
					$product_title = '<strong>' . $booking->tour_name . '</strong>' . 
									'<br />' . __('Tour date:', 'bookyourtravel') . ' ' . $tour_date . 			
									'<br />' . sprintf(__('Tour duration: %d days', 'bookyourtravel'), $booking->duration_days) .
									'<br />' . sprintf(__('Adults: %d', 'bookyourtravel'), $booking->adults) .
									'<br />' . sprintf(__('Children: %d', 'bookyourtravel'), $booking->children);
				}
			} else if ($is_car_rental) {
				$booking = $byt_car_rentals_post_type->get_car_rental_booking($booking_id);
				if (isset($booking)) {
					$from_day = date($date_format, strtotime($booking->from_day));
					$to_day = date($date_format, strtotime($booking->to_day));
					$product_title = 	'<strong>' . $booking->car_rental_name . '</strong>' . 
										'<br />' . $from_day . __(' to ', 'bookyourtravel') . $to_day .
										'<br />' . __('Pickup at:', 'bookyourtravel') . ' ' . $booking->pick_up .
										'<br />' . __('Drop-off at:', 'bookyourtravel') . ' ' . $booking->drop_off;			
				}
			} else if ($is_cruise) {
				$booking = $byt_cruises_post_type->get_cruise_booking($booking_id);
				if (isset($booking)) {
					$cruise_date = date($date_format, strtotime($booking->cruise_date));
					$product_title = '<strong>' . $booking->cruise_name . '</strong>' .
									'<br />' . $booking->cabin_type . 			
									'<br />' . __('Cruise date:', 'bookyourtravel') . ' ' . $cruise_date . 			
									'<br />' . sprintf(__('Cruise duration: %d days', 'bookyourtravel'), $booking->duration_days);
				}
			}
		}
		
		return $product_title;
	}

	function woocommerce_add_product_to_cart($product_id) {
		if ( ! is_admin() ) {
			global $woocommerce;
			$found = false;
			//check if product already in cart
			if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
				foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
					$_product = $values['data'];
					if ( $_product->id == $product_id )
						$found = true;
				}
				// if product not found, add it
				if ( ! $found )
					$woocommerce->cart->add_to_cart( $product_id );
			} else {
				// if no products in cart, add it
				$woocommerce->cart->add_to_cart( $product_id );
			}
		}
	}

	function woocommerce_random_sku($prefix, $len = 6) {
		$str = '';
		for ($i = 0; $i < $len; $i++) {
			$str .= substr('0123456789', mt_rand(0, strlen('0123456789') - 1), 1);
		}
		return $prefix . $str; 
	}

}

// store the instance in a variable to be retrieved later and call init
$byt_theme_woocommerce = BYT_Theme_WooCommerce::get_instance();
$byt_theme_woocommerce->init();