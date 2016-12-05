<?php


class BYT_Car_Rentals_Post_Type extends BYT_BaseSingleton {

	private $enable_car_rentals;
	private $car_rental_custom_meta_fields;
	private $car_rental_list_custom_meta_fields;
	private $car_rental_list_meta_box;	

	protected function __construct() {
	
		global $post, $byt_theme_globals;
		
		$this->enable_car_rentals = $byt_theme_globals->enable_car_rentals();	

		if ($this->enable_car_rentals) {
		
			$transmission_types = array();
			$transmission_types[] = array('value' => 'manual', 'label' => __('Manual transmission', 'bookyourtravel'));
			$transmission_types[] = array('value' => 'auto', 'label' => __('Auto transmission', 'bookyourtravel'));
					
			$this->car_rental_custom_meta_fields = array(
				array( // Post ID select box
					'label'	=> __('Is Featured', 'bookyourtravel'), // <label>
					'desc'	=> __('Show in lists where only featured items are shown.', 'bookyourtravel'), // description
					'id'	=> 'car_rental_is_featured', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array( // Post ID select box
					'label'	=> __('Location', 'bookyourtravel'), // <label>
					'desc'	=> '', // description
					'id'	=> 'car_rental_location_post_id', // field id and name
					'type'	=> 'post_select', // type of field
					'post_type' => array('location') // post types to display, options are prefixed with their post type
				),
				array( // Repeatable & Sortable Text inputs
					'label'	=> __('Gallery images', 'bookyourtravel'), // <label>
					'desc'	=> __('A collection of images to be used in slider/gallery on single page', 'bookyourtravel'), // description
					'id'	=> 'car_rental_images', // field id and name
					'type'	=> 'repeatable', // type of field
					'sanitizer' => array( // array of sanitizers with matching kets to next array
						'featured' => 'meta_box_santitize_boolean',
						'title' => 'sanitize_text_field',
						'desc' => 'wp_kses_data'
					),
					'repeatable_fields' => array ( // array of fields to be repeated
						array( // Image ID field
							'label'	=> __('Image', 'bookyourtravel'), // <label>
							'id'	=> 'image', // field id and name
							'type'	=> 'image' // type of field
						)
					)
				),
				array(
					'label'	=> __('Price per day', 'bookyourtravel'),
					'desc'	=> __('What is the car\'s rental price per day?', 'bookyourtravel'),
					'id'	=> 'car_rental_price_per_day',
					'type'	=> 'text'
				),
				array(
					'label'	=> __('Contact email addresses', 'bookyourtravel'),
					'desc'	=> __('Contact email addresses, separate each address with a semi-colon ;', 'bookyourtravel'),
					'id'	=> 'car_rental_contact_email',
					'type'	=> 'text'
				),
				array(
					'label'	=> __('Number of available cars', 'bookyourtravel'),
					'desc'	=> __('What number of cars are available for rent (used for admin purposes to determine availability)?', 'bookyourtravel'),
					'id'	=> 'car_rental_number_of_cars',
					'type'	=> 'text'
				),
				array(
					'label'	=> __('Max count', 'bookyourtravel'),
					'desc'	=> __('How many people are allowed in the car?', 'bookyourtravel'),
					'id'	=> 'car_rental_max_count',
					'type'	=> 'slider',
					'min'	=> '1',
					'max'	=> '10',
					'step'	=> '1'
				),
				array(
					'label'	=> __('Minimum age', 'bookyourtravel'),
					'desc'	=> __('What is the minimum age of people in the car?', 'bookyourtravel'),
					'id'	=> 'car_rental_min_age',
					'type'	=> 'slider',
					'min'	=> '18',
					'max'	=> '100',
					'step'	=> '1'
				),
				array(
					'label'	=> __('Number of doors', 'bookyourtravel'),
					'desc'	=> __('What is the number of doors the car has?', 'bookyourtravel'),
					'id'	=> 'car_rental_number_of_doors',
					'type'	=> 'slider',
					'min'	=> '1',
					'max'	=> '10',
					'step'	=> '1'
				),
				array( 
					'label'	=> __('Unlimited mileage', 'bookyourtravel'), // <label>
					'desc'	=> __('Is there no restriction on mileage covered?', 'bookyourtravel'), // description
					'id'	=> 'car_rental_is_unlimited_mileage', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array( 
					'label'	=> __('Air-conditioning', 'bookyourtravel'), // <label>
					'desc'	=> __('Is there air-conditioning?', 'bookyourtravel'), // description
					'id'	=> 'car_rental_is_air_conditioned', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array( 
					'label'	=> __('Transmission type', 'bookyourtravel'), // <label>
					'desc'	=> __('What is the car\'s transmission type?', 'bookyourtravel'), // description
					'id'	=> 'car_rental_transmission_type', // field id and name
					'type'	=> 'select', // type of field
					'options' => $transmission_types
				),
				array( // Taxonomy Select box
					'label'	=> __('Car type', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'car_type', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_select' // type of field
				),
				array( // Taxonomy Select box
					'label'	=> __('Car rental tag', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'car_rental_tag', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_checkboxes' // type of field
				),
				array( 
					'label'	=> __('Is for reservation only?', 'bookyourtravel'), // <label>
					'desc'	=> __('If this option is checked, then this particular car rental will not be processed via WooCommerce even if WooCommerce is in use.', 'bookyourtravel'), // description
					'id'	=> 'car_rental_is_reservation_only', // field id and name
					'type'	=> 'checkbox', // type of field
				)
			);

			global $default_car_rental_extra_fields;

			$car_rental_extra_fields = of_get_option('car_rental_extra_fields');
			
			if (!is_array($car_rental_extra_fields) || count($car_rental_extra_fields) == 0)
				$car_rental_extra_fields = $default_car_rental_extra_fields;
				
			foreach ($car_rental_extra_fields as $car_rental_extra_field) {
				$field_is_hidden = isset($car_rental_extra_field['hide']) ? intval($car_rental_extra_field['hide']) : 0;
				
				if (!$field_is_hidden) {
					$extra_field = null;
					$field_label = isset($car_rental_extra_field['label']) ? $car_rental_extra_field['label'] : '';
					$field_id = isset($car_rental_extra_field['id']) ? $car_rental_extra_field['id'] : '';
					$field_type = isset($car_rental_extra_field['type']) ? $car_rental_extra_field['type'] :  '';
					if (!empty($field_label) && !empty($field_id) && !empty($field_type)) {
						$extra_field = array(
							'label'	=> $field_label,
							'desc'	=> '',
							'id'	=> 'car_rental_' . $field_id,
							'type'	=> $field_type
						);
					}

					if ($extra_field) 
						$this->car_rental_custom_meta_fields[] = $extra_field;
				}
			}
			
			$sort_by_columns = array();
			$sort_by_columns[] = array('value' => 'title', 'label' => __('Car rental title', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'ID', 'label' => __('Car rental ID', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'date', 'label' => __('Publish date', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'rand', 'label' => __('Random', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'comment_count', 'label' => __('Comment count', 'bookyourtravel'));
			
			$this->car_rental_list_custom_meta_fields = array(
				array( // Taxonomy Select box
					'label'	=> __('Car type', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'car_type', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_select' // type of field
				),
				array( // Taxonomy Select box
					'label'	=> __('Location', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'car_rental_list_location_post_id', // field id and name
					'type'	=> 'post_select', // type of field
					'post_type' => array('location') // post types to display, options are prefixed with their post type
				),
				array( // Taxonomy Select box
					'label'	=> __('Car rental tags', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'car_rental_tag', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_checkboxes' // type of field
				),
				array( // Select box
					'label'	=> __('Sort by field', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'car_rental_list_sort_by', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'select', // type of field
					'options' => $sort_by_columns
				),
				array( // Post ID select box
					'label'	=> __('Sort descending?', 'bookyourtravel'), // <label>
					'desc'	=> __('If checked, will sort car rentals in descending order', 'bookyourtravel'), // description
					'id'	=> 'car_rental_list_sort_descending', // field id and name
					'type'	=> 'checkbox', // type of field
				),
			);
		
		}
		
        // our parent class might
        // contain shared code in its constructor
        parent::__construct();	
	}
	
    public function init() {

		if ($this->enable_car_rentals) {	
		
			add_action( 'admin_init', array($this, 'remove_unnecessary_meta_boxes') );
			add_filter( 'manage_edit-car_rental_columns', array( $this, 'manage_edit_car_rental_columns'), 10, 1);	
			add_action( 'byt_initialize_post_types', array( $this, 'initialize_post_type' ), 0);
			add_action( 'admin_init', array( $this, 'car_rental_admin_init' ) );
		}
	}
		
	function remove_unnecessary_meta_boxes() {

		remove_meta_box('tagsdiv-car_rental_tag', 'car_rental', 'side');		
		remove_meta_box('tagsdiv-car_type', 'car_rental', 'side');		
	}
	
	function manage_edit_car_rental_columns($columns) {
	
		//unset($columns['taxonomy-car_type']);
		return $columns;
	}
	
	function car_rental_admin_init() {
		new custom_add_meta_box( 'car_rental_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->car_rental_custom_meta_fields, 'car_rental' );
		
		$this->car_rental_list_meta_box = new custom_add_meta_box( 'car_rental_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->car_rental_list_custom_meta_fields, 'page' );	
		remove_action( 'add_meta_boxes', array( $this->car_rental_list_meta_box, 'add_box' ) );
		add_action('add_meta_boxes', array($this, 'car_rental_list_add_meta_boxes'));
	}
	
	function car_rental_list_add_meta_boxes() {
		global $post;
		$template_file = get_post_meta($post->ID,'_wp_page_template',true);
		if ($template_file == 'page-car_rental-list.php') {
			add_meta_box( $this->car_rental_list_meta_box->id, $this->car_rental_list_meta_box->title, array( $this->car_rental_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
		}
	}
			
	function initialize_post_type() {
	
		$this->register_car_rental_post_type();
		$this->register_car_rental_tag_taxonomy();
		$this->register_car_type_taxonomy();
		$this->create_car_rental_extra_tables();		
	}
	
	function register_car_rental_tag_taxonomy(){
	
		$labels = array(
				'name'              => _x( 'Car rental tags', 'taxonomy general name', 'bookyourtravel' ),
				'singular_name'     => _x( 'Car rental tag', 'taxonomy singular name', 'bookyourtravel' ),
				'search_items'      => __( 'Search Car rental tags', 'bookyourtravel' ),
				'all_items'         => __( 'All Car rental tags', 'bookyourtravel' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'         => __( 'Edit Car rental tag', 'bookyourtravel' ),
				'update_item'       => __( 'Update Car rental tag', 'bookyourtravel' ),
				'add_new_item'      => __( 'Add New Car rental tag', 'bookyourtravel' ),
				'new_item_name'     => __( 'New Car rental tag Name', 'bookyourtravel' ),
				'separate_items_with_commas' => __( 'Separate car rental tags with commas', 'bookyourtravel' ),
				'add_or_remove_items'        => __( 'Add or remove car rental tags', 'bookyourtravel' ),
				'choose_from_most_used'      => __( 'Choose from the most used car rental tags', 'bookyourtravel' ),
				'not_found'                  => __( 'No car rental tags found.', 'bookyourtravel' ),
				'menu_name'         => __( 'Car rental tags', 'bookyourtravel' ),
			);
			
		$args = array(
				'hierarchical'      => false,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'update_count_callback' => '_update_post_term_count',
				'rewrite'           => false,
			);
			
		register_taxonomy( 'car_rental_tag', array( 'car_rental' ), $args );
	}	
		
	function register_car_rental_post_type() {
			
		global $byt_theme_globals;
		$car_rentals_permalink_slug = $byt_theme_globals->get_car_rentals_permalink_slug();
		
		$car_rental_list_page_id = $byt_theme_globals->get_car_rental_list_page_id();
		
		if ($car_rental_list_page_id > 0) {

			add_rewrite_rule(
				"{$car_rentals_permalink_slug}$",
				"index.php?post_type=page&page_id={$car_rental_list_page_id}", 'top');
		
			add_rewrite_rule(
				"{$car_rentals_permalink_slug}/page/?([1-9][0-9]*)",
				"index.php?post_type=page&page_id={$car_rental_list_page_id}&paged=\$matches[1]", 'top');
		
		}
		
		add_rewrite_rule(
			"{$car_rentals_permalink_slug}/([^/]+)/page/?([1-9][0-9]*)",
			"index.php?post_type=car_rental&name=\$matches[1]&paged-byt=\$matches[2]", 'top');
			
		add_rewrite_tag('%paged-byt%', '([1-9][0-9]*)');		
		
		$labels = array(
			'name'                => _x( 'Car rentals', 'Post Type General Name', 'bookyourtravel' ),
			'singular_name'       => _x( 'Car rental', 'Post Type Singular Name', 'bookyourtravel' ),
			'menu_name'           => __( 'Car rentals', 'bookyourtravel' ),
			'all_items'           => __( 'All Car rentals', 'bookyourtravel' ),
			'view_item'           => __( 'View Car rental', 'bookyourtravel' ),
			'add_new_item'        => __( 'Add New Car rental', 'bookyourtravel' ),
			'add_new'             => __( 'New Car rental', 'bookyourtravel' ),
			'edit_item'           => __( 'Edit Car rental', 'bookyourtravel' ),
			'update_item'         => __( 'Update Car rental', 'bookyourtravel' ),
			'search_items'        => __( 'Search Car rentals', 'bookyourtravel' ),
			'not_found'           => __( 'No Car rentals found', 'bookyourtravel' ),
			'not_found_in_trash'  => __( 'No Car rentals found in Trash', 'bookyourtravel' ),
		);
		$args = array(
			'label'               => __( 'car rental', 'bookyourtravel' ),
			'description'         => __( 'Car rental information pages', 'bookyourtravel' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'author' ),
			'taxonomies'          => array( ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'rewrite' =>  array('slug' => $car_rentals_permalink_slug),
		);
		
		register_post_type( 'car_rental', $args );	
	}

	function register_car_type_taxonomy(){

		$labels = array(
				'name'              => _x( 'Car types', 'taxonomy general name', 'bookyourtravel' ),
				'singular_name'     => _x( 'Car type', 'taxonomy singular name', 'bookyourtravel' ),
				'search_items'      => __( 'Search Car types', 'bookyourtravel' ),
				'all_items'         => __( 'All Car types', 'bookyourtravel' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'         => __( 'Edit Car type', 'bookyourtravel' ),
				'update_item'       => __( 'Update Car type', 'bookyourtravel' ),
				'add_new_item'      => __( 'Add New Car type', 'bookyourtravel' ),
				'new_item_name'     => __( 'New Car type Name', 'bookyourtravel' ),
				'separate_items_with_commas' => __( 'Separate car types with commas', 'bookyourtravel' ),
				'add_or_remove_items'        => __( 'Add or remove car types', 'bookyourtravel' ),
				'choose_from_most_used'      => __( 'Choose from the most used car types', 'bookyourtravel' ),
				'not_found'                  => __( 'No car types found.', 'bookyourtravel' ),
				'menu_name'         => __( 'Car types', 'bookyourtravel' ),
			);
			
		$args = array(
				'hierarchical'      => false,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => false,
				'update_count_callback' => '_update_post_term_count',
				'rewrite'           => false,
			);
		
		register_taxonomy( 'car_type', 'car_rental', $args );
	}

	function create_car_rental_extra_tables() {

		global $wpdb, $byt_installed_version;

		if ($byt_installed_version != BOOKYOURTRAVEL_VERSION) {
			
			// we do not execute sql directly
			// we are calling dbDelta which cant migrate database
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');		
			
			$table_name = BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE;
			$sql = "CREATE TABLE " . $table_name . " (
						Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						car_rental_id bigint(20) NOT NULL,
						first_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						last_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						email varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						phone varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						address varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						town varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						zip varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						country varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						special_requirements text CHARACTER SET utf8 COLLATE utf8_bin NULL,
						drop_off bigint(10) NOT NULL DEFAULT 0,
						total_price decimal(16, 2) NOT NULL,
						user_id bigint(10) NOT NULL DEFAULT 0,
						created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
						woo_order_id bigint(20) NULL,
						cart_key VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' NOT NULL,
						currency_code VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
						PRIMARY KEY  (Id)
					);";

			dbDelta($sql);
			
			$table_name = BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE;
			$sql = "CREATE TABLE " . $table_name . " (
						Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						car_rental_booking_id bigint(20) NOT NULL,
						booking_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
						created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
						PRIMARY KEY  (Id)
					);";
			
			dbDelta($sql);
			global $EZSQL_ERROR;
			$EZSQL_ERROR = array();
					
			$wpdb->query("DROP TRIGGER IF EXISTS byt_car_rental_bookings_delete_trigger;");
			$sql = "				
				CREATE TRIGGER byt_car_rental_bookings_delete_trigger AFTER DELETE ON `" . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . "` 
				FOR EACH ROW BEGIN
					DELETE FROM `" . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . "` 
					WHERE car_rental_booking_id = OLD.Id;
				END;
			";		
			$wpdb->query($sql);	
			
		}
	}
		
	function car_rentals_search_fields( $fields, &$wp_query ) {

		global $wpdb, $byt_multi_language_count;

		if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'car_rental' ) {
			
			$search_only_available = false;
			if (isset($wp_query->query_vars['search_only_available']))
				$search_only_available = $wp_query->get('search_only_available');
			
			if ($search_only_available || isset($wp_query->query_vars['byt_date_from']) || isset($wp_query->query_vars['byt_date_from'])) {
			
				$date_from = null;
				if ( isset($wp_query->query_vars['byt_date_from']) )
					$date_from = date('Y-m-d', strtotime($wp_query->get('byt_date_from')));
				
				$date_to = null;		
				if ( isset($wp_query->query_vars['byt_date_to']) )
					$date_to = date('Y-m-d', strtotime($wp_query->get('byt_date_to') . ' -1 day'));
				
				if (isset($date_from) && $date_from == $date_to)
					$date_to = date('Y-m-d', strtotime($wp_query->get('byt_date_from') . ' +7 day'));
				
				if (isset($date_from) || isset($date_to)) {
				
					$fields .= ", (
									SELECT IFNULL(SUM(car_rentals_meta_number_of_cars.meta_value+0), 0) cars_available FROM $wpdb->postmeta car_rentals_meta_number_of_cars
									WHERE car_rentals_meta_number_of_cars.post_id = {$wpdb->posts}.ID AND car_rentals_meta_number_of_cars.meta_key='car_rental_number_of_cars' ";
					$fields .= " ) cars_available ";
					$fields .= ",
								(
									SELECT COUNT(DISTINCT car_rental_booking_id) cars_booked
									FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . " booking_days_table 
									INNER JOIN " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . " booking_table ON booking_days_table.car_rental_booking_id = booking_table.Id 
									WHERE ";
									
					if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
						$fields .= " booking_table.car_rental_id = translations_default.element_id ";
					} else {
						$fields .= " booking_table.car_rental_id = {$wpdb->posts}.ID ";
					}

					$fields .= $wpdb->prepare(" AND booking_days_table.booking_date BETWEEN %s AND %s ", $date_from, $date_to);
									
					if ($date_from != null && $date_to != null) {
						$fields .= $wpdb->prepare(" AND (booking_days_table.booking_date BETWEEN %s AND %s) ", $date_from, $date_to);
					} else if ($date_from != null) {
						$fields .= $wpdb->prepare(" AND booking_days_table.booking_date > %s ", $date_from);
					} else if ($date_to != null) {
						$fields .= $wpdb->prepare(" AND booking_days_table.booking_date < %s ", $date_to);
					}
					
					$fields .= " ) cars_booked ";
				}
			}
				
			if (!is_admin()) {
				$fields .= ", (SELECT IFNULL(price_meta2.meta_value, 0) FROM {$wpdb->postmeta} price_meta2 WHERE price_meta2.post_id={$wpdb->posts}.ID AND price_meta2.meta_key='car_rental_price_per_day' LIMIT 1) car_rental_price ";
			}
				
		}

		return $fields;
	}

	function car_rentals_search_where( $where, &$wp_query ) {
		
		global $wpdb;
		
		if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'car_rental' ) {
			if ( isset($wp_query->query_vars['s']) && !empty($wp_query->query_vars['s'])  && isset($wp_query->query_vars['byt_location_ids']) && isset($wp_query->query_vars['s']) ) {
				$needed_where_part = '';
				$where_array = explode('AND', $where);
				foreach ($where_array as $where_part) {
					if (strpos($where_part,"meta_key = 'car_rental_location_post_id'") !== false) {
						$needed_where_part = $where_part;
						break;
					}
				}
				
				if (!empty($needed_where_part)) {
					$prefix = str_replace("meta_key = 'car_rental_location_post_id'","",$needed_where_part);
					$prefix = str_replace(")", "", $prefix);
					$prefix = str_replace("(", "", $prefix);
					$prefix = trim($prefix);

					$location_ids = $wp_query->query_vars['byt_location_ids'];
					$location_ids_str = "'".implode("','", $location_ids)."'";				
					$location_search_param_part = "{$prefix}meta_key = 'car_rental_location_post_id' AND CAST({$prefix}meta_value AS CHAR) IN ($location_ids_str)";							
				
					$where = str_replace($location_search_param_part, "1=1", $where);
					
					$post_content_part = "OR ($wpdb->posts.post_content LIKE '%" . $wp_query->get('s') . "%')";
					$where = str_replace($post_content_part, $post_content_part . " OR ($location_search_param_part) ", $where);				
				}
			}
		}
		
		return $where;
	}

	function car_rentals_search_groupby( $groupby, &$wp_query ) {

		global $wpdb;
		
		if (empty($groupby))
			$groupby = " {$wpdb->posts}.ID ";
		
		if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'car_rental' ) {		
			
			$date_from = null;
			if ( isset($wp_query->query_vars['byt_date_from']) )
				$date_from = date('Y-m-d', strtotime($wp_query->get('byt_date_from')));
			
			$date_to = null;		
			if ( isset($wp_query->query_vars['byt_date_to']) )
				$date_to = date('Y-m-d', strtotime($wp_query->get('byt_date_to') . ' -1 day'));
			
			if (isset($date_from) && $date_from == $date_to)
				$date_to = date('Y-m-d', strtotime($wp_query->get('byt_date_from') . ' +7 day'));
			
			$search_only_available = false;
			if (isset($wp_query->query_vars['search_only_available']))
				$search_only_available = $wp_query->get('search_only_available');
			
			$groupby .= ' HAVING 1=1 ';
			
			if ($search_only_available && (isset($date_from) || isset($date_to))) {
					
				$groupby .= ' AND cars_available > cars_booked ';
			
			}			

			if (isset($wp_query->query_vars['prices'])) {
			
				$prices = (array)$wp_query->query_vars['prices'];				
				if (count($prices) > 0) {
				
					$price_range_bottom = $wp_query->query_vars['price_range_bottom'];
					$price_range_increment = $wp_query->query_vars['price_range_increment'];
					$price_range_count = $wp_query->query_vars['price_range_count'];
					
					$bottom = 0;
					$top = 0;
					
					$groupby .= ' AND ( 1!=1 ';
					for ( $i = 0; $i < $price_range_count; $i++ ) { 
						$bottom = ($i * $price_range_increment) + $price_range_bottom;
						$top = ( ( $i+1 ) * $price_range_increment ) + $price_range_bottom - 1;	

						if ( in_array( $i + 1, $prices ) ) {
							if ( $i < ( ($price_range_count - 1) ) ) {
									$groupby .= " OR (car_rental_price >= $bottom AND car_rental_price <= $top ) ";
							} else {
								$groupby .= " OR (car_rental_price >= $bottom ) ";
							}
						}
					}
					
					$groupby .= ")";
				}
			}
		}
		
		return $groupby;
	}
	
	function car_rentals_search_join($join) {
		global $wp_query, $wpdb, $byt_multi_language_count;
		
		if (!is_admin()) {
			if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
				$join .= " 	INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_car_rental' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = {$wpdb->posts}.ID
							INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_car_rental' AND translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND translations_default.trid = translations.trid ";
			}
		}
		
		return $join;
	}
		
	function list_car_rentals_count ( $paged = 0, $per_page = -1, $orderby = '', $order = '', $location_id = 0, $car_types_array = array(), $car_rental_tags_array = array(), $search_args = array(), $featured_only = false, $author_id = null, $include_private = false ) {
		$results = $this->list_car_rentals ($paged, $per_page, $orderby, $order, $location_id, $car_types_array, $car_rental_tags_array, $search_args, $featured_only, $author_id, $include_private, true);
		return $results['total'];
	}
		
	function list_car_rentals( $paged = 0, $per_page = -1, $orderby = '', $order = '', $location_id = 0, $car_types_array = array(), $car_rental_tags_array = array(), $search_args = array(), $featured_only = false, $author_id = null, $include_private = false, $count_only = false ) {

		global $byt_theme_globals;
		$location_ids = array();
		
		if ($location_id > 0) {
			$location_ids[] = $location_id;
			$location_descendants = BYT_Theme_Utils::get_post_descendants($location_id, 'location');
			foreach ($location_descendants as $location) {
				$location_ids[] = $location->ID;
			}
		}
		
		if (isset($search_args['keyword']) && strlen($search_args['keyword']) > 0) {
			$args = array(
						's' => $search_args['keyword'],
						'post_type' => 'location',
						'posts_per_page' => -1, 
						'post_status' => 'publish',
						'suppress_filters' => false
					);
			
			$location_posts = get_posts($args);
			foreach ($location_posts as $location) {
				$location_ids[] = $location->ID;		
			}

			$descendant_location_ids = array();		
			foreach ($location_ids as $temp_location_id) {
				$location_descendants = BYT_Theme_Utils::get_post_descendants($temp_location_id, 'location');
				foreach ($location_descendants as $location) {
					$descendant_location_ids[] = $location->ID;
				}
			}
			$location_ids = array_merge($descendant_location_ids,$location_ids);
		}
			
		$args = array(
			'post_type'         => 'car_rental',
			'post_status'       => array('publish'),
			'posts_per_page'    => $per_page,
			'paged' 			=> $paged,
			'orderby'           => $orderby,
			'suppress_filters' 	=> false,
			'order'				=> $order,
			'meta_query'        => array('relation' => 'AND')
		);	
		
		if ($orderby == 'price') {
			$args['meta_key'] = 'car_rental_price_per_day';
			$args['orderby'] = 'meta_value_num';
		}
		
		if (isset($search_args['keyword']) && strlen($search_args['keyword']) > 0) {
			$args['s'] = $search_args['keyword'];
		}
		
		if ($include_private) {
			$args['post_status'][] = 'private';
		}
		
		if (isset($featured_only) && $featured_only) {
			$args['meta_query'][] = array(
				'key'       => 'car_rental_is_featured',
				'value'     => 1,
				'compare'   => '=',
				'type' => 'numeric'
			);
		}

		if (isset($author_id)) {
			$author_id = intval($author_id);
			if ($author_id > 0) {
				$args['author'] = $author_id;
			}
		}

		if (count($location_ids) > 0) {
			$args['meta_query'][] = array(
				'key'       => 'car_rental_location_post_id',
				'value'     => $location_ids,
				'compare'   => 'IN'
			);
			$args['byt_location_ids'] = $location_ids;
		}
		
		if (!empty($car_types_array)) {
			$args['tax_query'][] = 	array(
					'taxonomy' => 'car_type',
					'field' => 'id',
					'terms' => $car_types_array,
					'operator'=> 'IN'
			);
		}
		
		if (!empty($car_rental_tags_array)) {
			$args['tax_query'][] = 	array(
					'taxonomy' => 'car_rental_tag',
					'field' => 'id',
					'terms' => $car_rental_tags_array,
					'operator'=> 'IN'
			);
		}
		
		$search_only_available = false;
		if ( isset($search_args['search_only_available'])) {				
			$search_only_available = $search_args['search_only_available'];
		}

		if ( isset($search_args['prices']) ) {
			$args['prices'] = $search_args['prices'];
			$args['price_range_bottom'] = $byt_theme_globals->get_price_range_bottom();
			$args['price_range_increment'] = $byt_theme_globals->get_price_range_increment();
			$args['price_range_count'] = $byt_theme_globals->get_price_range_count();
		}	
		
		if ( isset($search_args['date_from']) )
			$args['byt_date_from'] = $search_args['date_from'];
		if ( isset($search_args['date_to']) )
			$args['byt_date_to'] =  $search_args['date_to'];
			
		$args['search_only_available'] = $search_only_available;
				
		add_filter('posts_where', array($this, 'car_rentals_search_where'), 10, 2);				
		add_filter('posts_fields', array($this, 'car_rentals_search_fields'), 10, 2 );
		add_filter('posts_groupby', array($this, 'car_rentals_search_groupby'), 10, 2 );
		add_filter('posts_join', array($this, 'car_rentals_search_join'), 10, 2 );
		
		$posts_query = new WP_Query($args);
		
		// echo $posts_query->request;
		
		if ($count_only) {
			$results = array(
				'total' => $posts_query->found_posts,
				'results' => null
			);	
		} else {
			$results = array();
			
			if ($posts_query->have_posts() ) {
				while ( $posts_query->have_posts() ) {
					global $post;
					$posts_query->the_post(); 
					$results[] = $post;
				}
			}
		
			$results = array(
				'total' => $posts_query->found_posts,
				'results' => $results
			);
		}
		
		wp_reset_postdata();
		
		remove_filter('posts_where', array($this, 'car_rentals_search_where'));			
		remove_filter('posts_fields', array($this, 'car_rentals_search_fields' ));
		remove_filter('posts_groupby', array($this, 'car_rentals_search_groupby' ));
		remove_filter('posts_join', array($this, 'car_rentals_search_join') );
		
		return $results;
	}

	function create_car_rental_booking ( $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $date_from, $date_to, $car_rental_id,  $user_id, $total_price, $drop_off_location_id ) {
		
		global $wpdb;
		
		$car_rental_id = BYT_Theme_Utils::get_default_language_post_id($car_rental_id, 'car_rental');
		
		// We are actually (in terms of db data) looking for date 1 day before the to date.
		// E.g. when you look to book a room from 19.12. to 20.12 you will be staying 1 night, not 2. 
		// The same goes for cars.
		$date_to = date('Y-m-d', strtotime($date_to.' -1 day'));
		
		$sql = "INSERT INTO " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . "
				(first_name, last_name, email, phone, address, town, zip, country, special_requirements, car_rental_id, user_id, total_price, drop_off)
				VALUES 
				(%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d);";
				
		$wpdb->query($wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $car_rental_id, $user_id, $total_price, $drop_off_location_id));

		$booking_id = $wpdb->insert_id;

		$dates = BYT_Theme_Utils::get_dates_from_range($date_from, $date_to);	
		foreach ($dates as $date) {
			$sql = "INSERT INTO " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . "
					(car_rental_booking_id, booking_date)
					VALUES
					(%d, %s);";
			$wpdb->query($wpdb->prepare($sql, $booking_id, $date));
		}
		
		return $booking_id;	
	}

	function update_car_rental_booking ( $booking_id, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $date_from, $date_to, $car_rental_id,  $user_id, $total_price, $drop_off_location_id ) {

		global $wpdb;
		
		// We are actually (in terms of db data) looking for date 1 day before the to date.
		// E.g. when you look to book a room from 19.12. to 20.12 you will be staying 1 night, not 2. 
		// The same goes for cars.
		$date_to = date('Y-m-d', strtotime($date_to.' -1 day'));
		
		// delete previous days from table
		$sql = "DELETE FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . "
				WHERE car_rental_booking_id = %d ";
		
		$wpdb->query($wpdb->prepare($sql, $booking_id));		

		$sql = "UPDATE " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . "
				SET first_name = %s,
				last_name = %s,
				email = %s, 
				phone = %s, 
				address = %s, 
				town = %s, 
				zip = %s, 
				country = %s, 
				special_requirements = %s,
				car_rental_id = %d, 
				user_id = %d, 
				total_price = %f, 
				drop_off = %d
				WHERE Id=%d";
				
		$wpdb->query($wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $car_rental_id, $user_id, $total_price, $drop_off_location_id, $booking_id));
		
		$dates = BYT_Theme_Utils::get_dates_from_range($date_from, $date_to);	
		foreach ($dates as $date) {
			$sql = "INSERT INTO " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . "
					(car_rental_booking_id, booking_date)
					VALUES
					(%d, %s);";
			$wpdb->query($wpdb->prepare($sql, $booking_id, $date));
		}
		
		return $booking_id;
	}

	function car_rental_get_booked_days($car_rental_id, $month, $year) {

		global $wpdb;

		$car_rental_id = BYT_Theme_Utils::get_default_language_post_id($car_rental_id, 'car_rental');
		
		$sql = "	SELECT DISTINCT booking_date, (car_rentals_meta_number_of_cars.meta_value+0) number_of_cars
					FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . " days
					INNER JOIN " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . " bookings ON bookings.Id = days.car_rental_booking_id 
					INNER JOIN $wpdb->postmeta car_rentals_meta_number_of_cars ON bookings.car_rental_id=car_rentals_meta_number_of_cars.post_id AND car_rentals_meta_number_of_cars.meta_key='car_rental_number_of_cars' 
					WHERE bookings.car_rental_id=%d AND booking_date >= %s AND MONTH(booking_date) = %d AND YEAR(booking_date) = %d
					GROUP BY booking_date
					HAVING COUNT(DISTINCT car_rental_booking_id) >= number_of_cars";

		$today = date('Y-m-d H:i:s');
		
		$sql = $wpdb->prepare($sql, $car_rental_id, $today, $month, $year);
		
		return $wpdb->get_results($sql);
	}

	function list_car_rental_bookings($search_term = null, $orderby = 'Id', $order = 'ASC', $paged = null, $per_page = 0, $user_id = 0, $author_id = null ) {

		global $wpdb, $byt_multi_language_count;

		$table_name = BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE;
		$sql = "SELECT DISTINCT bookings.*, car_rentals.post_title car_rental_name,
				(
					SELECT MIN(booking_date) FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . " v2 
					WHERE v2.car_rental_booking_id = bookings.Id 
				) from_day,
				(
					SELECT MAX(booking_date) FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . " v3 
					WHERE v3.car_rental_booking_id = bookings.Id 
				) to_day, locations.post_title pick_up, locations_2.post_title drop_off
				FROM " . $table_name . " bookings ";

		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_car_rental' AND translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND translations_default.element_id = bookings.car_rental_id ";			
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_car_rental' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.trid = translations_default.trid ";
		}
		
		$sql .= " INNER JOIN $wpdb->posts car_rentals ON ";
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " car_rentals.ID = translations.element_id ";
		} else {
			$sql .= " car_rentals.ID = bookings.car_rental_id ";
		}	
							
		$sql .= "LEFT JOIN $wpdb->postmeta car_rental_meta_location ON car_rentals.ID=car_rental_meta_location.post_id AND car_rental_meta_location.meta_key='car_rental_location_post_id'
				LEFT JOIN $wpdb->posts locations ON locations.ID = car_rental_meta_location.meta_value+0
				LEFT JOIN $wpdb->posts locations_2 ON locations_2.ID = bookings.drop_off
				WHERE car_rentals.post_status = 'publish' AND locations.post_status = 'publish' AND locations_2.post_status = 'publish' ";
		
		if ($search_term != null && !empty($search_term)) {
			$search_term = "%" . $search_term . "%";
			$sql .= $wpdb->prepare(" AND (bookings.first_name LIKE '%s' OR bookings.last_name LIKE '%s') ", $search_term, $search_term);
		}
		
		if ($user_id > 0) {
			$sql .= $wpdb->prepare(" AND bookings.user_id = %d ", $user_id) ;
		}
		
		if (isset($author_id)) {
			$sql .= $wpdb->prepare(" AND car_rentals.post_author=%d ", $author_id);
		}
		
		if(!empty($orderby) & !empty($order)){ 
			$sql.= ' ORDER BY '.$orderby.' '.$order; 
		}
		
		$sql_count = $sql;
		
		if(!empty($paged) && !empty($per_page)){
			$offset=($paged-1)*$per_page;
			$sql .= $wpdb->prepare(" LIMIT %d, %d ", $offset, $per_page); 
		}

		$results = array(
			'total' => $wpdb->query($sql_count),
			'results' => $wpdb->get_results($sql)
		);
		
		return $results;
	}

	function get_car_rental_booking($booking_id) {

		global $wpdb, $byt_multi_language_count;
		
		$table_name = BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE;
		$sql = "SELECT 	DISTINCT bookings.*, 
						car_rentals.post_title car_rental_name,
						(
							SELECT MIN(booking_date) FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . " v2 
							WHERE v2.car_rental_booking_id = bookings.Id 
						) from_day,
						(
							SELECT MAX(booking_date) FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKING_DAYS_TABLE . " v3 
							WHERE v3.car_rental_booking_id = bookings.Id 
						) to_day, 
						locations.ID pick_up_location_id, 
						locations_2.ID drop_off_location_id,					
						locations.post_title pick_up, 
						locations_2.post_title drop_off
				FROM " . $table_name . " bookings ";
				
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_car_rental' AND translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND translations_default.element_id = bookings.car_rental_id ";			
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_car_rental' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.trid = translations_default.trid ";
		}
		
		$sql .= " INNER JOIN $wpdb->posts car_rentals ON ";
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " car_rentals.ID = translations.element_id ";
		} else {
			$sql .= " car_rentals.ID = bookings.car_rental_id ";
		}	
				
		$sql .= "LEFT JOIN $wpdb->postmeta car_rental_meta_location ON car_rentals.ID=car_rental_meta_location.post_id AND car_rental_meta_location.meta_key='car_rental_location_post_id'
				LEFT JOIN $wpdb->posts locations ON locations.ID = car_rental_meta_location.meta_value+0
				LEFT JOIN $wpdb->posts locations_2 ON locations_2.ID = bookings.drop_off
				WHERE car_rentals.post_status = 'publish' AND locations.post_status = 'publish' AND locations_2.post_status = 'publish' AND bookings.Id = $booking_id ";
				
		return $wpdb->get_row($sql);
	}

	function delete_car_rental_booking($booking_id) {

		global $wpdb;
		
		$sql = "DELETE FROM " . BOOKYOURTRAVEL_CAR_RENTAL_BOOKINGS_TABLE . "
				WHERE Id = %d";
		
		$wpdb->query($wpdb->prepare($sql, $booking_id));	
	}
}

global $byt_car_rentals_post_type;
// store the instance in a variable to be retrieved later and call init
$byt_car_rentals_post_type = BYT_Car_Rentals_Post_Type::get_instance();
$byt_car_rentals_post_type->init();