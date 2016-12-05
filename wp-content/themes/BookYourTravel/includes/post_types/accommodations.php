<?php

class BYT_Accommodations_Post_Type extends BYT_BaseSingleton {

	private $enable_accommodations;
	private $accommodation_custom_meta_fields;
	private $accommodation_list_custom_meta_fields;
	private $accommodation_list_meta_box;
	
	protected function __construct() {
	
		global $byt_room_types_post_type, $post;

		$post_id = 0;
		if (isset($post))
			$post_id = $post->ID;
		else if (isset($_GET['post']))
			$post_id = (int)$_GET['post'];
		
		$room_types = array();
		$room_type_query = $byt_room_types_post_type->list_room_types(null, array('publish'));
		if ($room_type_query->have_posts()) {
			while ($room_type_query->have_posts()) {
				$room_type_query->the_post();
				global $post;				
				$room_types[] = array('value' => $post->ID, 'label' => $post->post_title);
			}
		}
		wp_reset_postdata();	
	
		$check_in_out_times = array();
		$check_in_out_times[] = array('value' => '', 'label' => __('Flexible', 'bookyourtravel'));
		
		for ($i=0; $i<24;$i++)
			$check_in_out_times[] = array('value' => sprintf("%02s:00", $i), 'label' => sprintf("%02s:00", $i));
	
		global $byt_theme_globals;
		
		$this->enable_accommodations = $byt_theme_globals->enable_accommodations();
		
		if ($this->enable_accommodations) {

			$this->accommodation_custom_meta_fields = array(
				array( // Post ID select box
					'label'	=> __('Is Featured', 'bookyourtravel'), // <label>
					'desc'	=> __('Show in lists where only featured items are shown.', 'bookyourtravel'), // description
					'id'	=> 'accommodation_is_featured', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array( // Post ID select box
					'label'	=> __('Is Self Catered', 'bookyourtravel'), // <label>
					'desc'	=> __('Is the accommodation self-catered or does it provide hotel-style catering?', 'bookyourtravel'), // description
					'id'	=> 'accommodation_is_self_catered', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array(
					'label'	=> __('Min days stay', 'bookyourtravel'),
					'desc'	=> __('What is the minimum number of days accommodation can be booked for?', 'bookyourtravel'),
					'id'	=> 'accommodation_min_days_stay',
					'type'	=> 'slider',
					'min'	=> '1',
					'max'	=> '10',
					'step'	=> '1'
				),
				array(
					'label'	=> __('Max adult count', 'bookyourtravel'),
					'desc'	=> __('How many adults are allowed in the accommodation?', 'bookyourtravel'),
					'id'	=> 'accommodation_max_count',
					'type'	=> 'slider',
					'min'	=> '1',
					'max'	=> '10',
					'step'	=> '1'
				),
				array(
					'label'	=> __('Max child count', 'bookyourtravel'),
					'desc'	=> __('How many children are allowed in the accommodation?', 'bookyourtravel'),
					'id'	=> 'accommodation_max_child_count',
					'type'	=> 'slider',
					'min'	=> '1',
					'max'	=> '10',
					'step'	=> '1'
				),
				array( // Post ID select box
					'label'	=> __('Enabled room types', 'bookyourtravel'), // <label>
					'desc'	=> '', // description
					'id'	=>  'room_types', // field id and name
					'type'	=> 'checkbox_group', // type of field
					'options' => $room_types // post types to display, options are prefixed with their post type
				),
				array( 
					'label'	=> __('Price per person?', 'bookyourtravel'), // <label>
					'desc'	=> __('Is price calculated per person (adult, child)? If not then calculations are done per room / per apartment.', 'bookyourtravel'), // description
					'id'	=> 'accommodation_is_price_per_person', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array(
					'label'	=> __('Count children stay free', 'bookyourtravel'),
					'desc'	=> __('How many kids stay free before we charge a fee?', 'bookyourtravel'),
					'id'	=> 'accommodation_count_children_stay_free',
					'type'	=> 'slider',
					'min'	=> '1',
					'max'	=> '5',
					'step'	=> '1'
				),
				array( 
					'label'	=> __('Is for reservation only?', 'bookyourtravel'), // <label>
					'desc'	=> __('If this option is checked, then this particular accommodation will not be processed via WooCommerce even if WooCommerce is in use.', 'bookyourtravel'), // description
					'id'	=> 'accommodation_is_reservation_only', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array( // Taxonomy Select box
					'label'	=> __('Check-in time', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'accommodation_check_in_time', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'select', // type of field
					'options' => $check_in_out_times
				),
				array( // Taxonomy Select box
					'label'	=> __('Check-out time', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'accommodation_check_out_time', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'select', // type of field
					'options' => $check_in_out_times
				),	
				array(
					'label'	=> __('Star count', 'bookyourtravel'),
					'desc'	=> '',
					'id'	=> 'accommodation_star_count',
					'type'	=> 'slider',
					'min'	=> '1',
					'max'	=> '5',
					'step'	=> '1'
				),
				array( // Taxonomy Select box
					'label'	=> __('Facilities', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'facility', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_checkboxes' // type of field
				),
				array( // Taxonomy Select box
					'label'	=> __('Tags', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'acc_tag', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_checkboxes' // type of field
				),
				array( // Taxonomy Select box
					'label'	=> __('Accommodation type', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'accommodation_type', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_select' // type of field
				),
				array( // Post ID select box
					'label'	=> __('Location', 'bookyourtravel'), // <label>
					'desc'	=> '', // description
					'id'	=> 'accommodation_location_post_id', // field id and name
					'type'	=> 'post_select', // type of field
					'post_type' => array('location') // post types to display, options are prefixed with their post type
				),
				array( // Repeatable & Sortable Text inputs
					'label'	=> __('Gallery images', 'bookyourtravel'), // <label>
					'desc'	=> 'A collection of images to be used in slider/gallery on single page', // description
					'id'	=> 'accommodation_images', // field id and name
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
					'label'	=> __('Address', 'bookyourtravel'),
					'desc'	=> '',
					'id'	=> 'accommodation_address',
					'type'	=> 'text'
				),
				array(
					'label'	=> __('Website address', 'bookyourtravel'),
					'desc'	=> '',
					'id'	=> 'accommodation_website_address',
					'type'	=> 'text'
				),
				array(
					'label'	=> __('Availability extra text', 'bookyourtravel'),
					'desc'	=> __('Extra text shown on availability tab above the book now area.', 'bookyourtravel'),
					'id'	=> 'accommodation_availability_text',
					'type'	=> 'textarea'
				),
				array(
					'label'	=> __('Contact email addresses', 'bookyourtravel'),
					'desc'	=> __('Contact email addresses, separate each address with a semi-colon ;', 'bookyourtravel'),
					'id'	=> 'accommodation_contact_email',
					'type'	=> 'text'
				),
				array(
					'label'	=> __('Latitude coordinates', 'bookyourtravel'),
					'desc'	=> __('Latitude coordinates for use with google map (leave blank to not use)', 'bookyourtravel'),
					'id'	=> 'accommodation_latitude',
					'type'	=> 'text'
				),
				array(
					'label'	=> __('Longitude coordinates', 'bookyourtravel'),
					'desc'	=> __('Longitude coordinates for use with google map (leave blank to not use)', 'bookyourtravel'),
					'id'	=> 'accommodation_longitude',
					'type'	=> 'text'
				),	
			);
			
			global $default_accommodation_extra_fields;

			$accommodation_extra_fields = of_get_option('accommodation_extra_fields');
			if (!is_array($accommodation_extra_fields) || count($accommodation_extra_fields) == 0)
				$accommodation_extra_fields = $default_accommodation_extra_fields;
							
			foreach ($accommodation_extra_fields as $accommodation_extra_field) {
				$field_is_hidden = isset($accommodation_extra_field['hide']) ? intval($accommodation_extra_field['hide']) : 0;
				
				if (!$field_is_hidden) {
					$extra_field = null;
					$field_label = isset($accommodation_extra_field['label']) ? $accommodation_extra_field['label'] : '';
					$field_id = isset($accommodation_extra_field['id']) ? $accommodation_extra_field['id'] : '';
					$field_type = isset($accommodation_extra_field['type']) ? $accommodation_extra_field['type'] :  '';
					if (!empty($field_label) && !empty($field_id) && !empty($field_type)) {
						$extra_field = array(
							'label'	=> $field_label,
							'desc'	=> '',
							'id'	=> 'accommodation_' . $field_id,
							'type'	=> $field_type
						);
					}

					if ($extra_field) 
						$this->accommodation_custom_meta_fields[] = $extra_field;
				}
			}
			
			$catering_types = array();
			$catering_types[] = array('value' => '', 'label' => __('Any', 'bookyourtravel'));
			$catering_types[] = array('value' => 'self-catered', 'label' => __('Self-catered', 'bookyourtravel'));
			$catering_types[] = array('value' => 'hotel', 'label' => __('Hotel-style', 'bookyourtravel'));
			
			$sort_by_columns = array();
			$sort_by_columns[] = array('value' => 'title', 'label' => __('Accommodation title', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'ID', 'label' => __('Accommodation ID', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'date', 'label' => __('Publish date', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'rand', 'label' => __('Random', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'comment_count', 'label' => __('Comment count', 'bookyourtravel'));
			
			$this->accommodation_list_custom_meta_fields = array(
				array( // Taxonomy Select box
					'label'	=> __('Catering type', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'accommodation_list_catering_type', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'select', // type of field
					'options' => $catering_types
				),			
				array( // Taxonomy Select box
					'label'	=> __('Accomodation type', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'accommodation_type', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_select' // type of field
				),
				array( // Taxonomy Select box
					'label'	=> __('Accommodation tags', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'acc_tag', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_checkboxes' // type of field
				),
				array( // Taxonomy Select box
					'label'	=> __('Location', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'accommodation_list_location_post_id', // field id and name
					'type'	=> 'post_select', // type of field
					'post_type' => array('location') // post types to display, options are prefixed with their post type
				),
				array( // Select box
					'label'	=> __('Sort by field', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'accommodation_list_sort_by', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'select', // type of field
					'options' => $sort_by_columns
				),
				array( // Post ID select box
					'label'	=> __('Sort descending?', 'bookyourtravel'), // <label>
					'desc'	=> __('If checked, will sort accommodations in descending order', 'bookyourtravel'), // description
					'id'	=> 'accommodation_list_sort_descending', // field id and name
					'type'	=> 'checkbox', // type of field
				),
			);
		}
		
        // our parent class might
        // contain shared code in its constructor
        parent::__construct();		
    }

    public function init() {

		if ($this->enable_accommodations) {	
		
			add_action( 'admin_init', array($this, 'remove_unnecessary_meta_boxes') );
			add_filter('manage_edit-accommodation_columns', array( $this, 'manage_edit_accommodation_columns'), 10, 1);	
			add_action( 'byt_initialize_post_types', array( $this, 'initialize_post_type' ), 0);
			add_action( 'admin_init', array( $this, 'accommodation_admin_init' ) );
		}
	}
		
	function accommodation_admin_init() {
		
		new custom_add_meta_box( 'accommodation_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->accommodation_custom_meta_fields, 'accommodation' );

		$this->accommodation_list_meta_box = new custom_add_meta_box( 'accommodation_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->accommodation_list_custom_meta_fields, 'page' );	
		remove_action( 'add_meta_boxes', array( $this->accommodation_list_meta_box, 'add_box' ) );
		add_action('add_meta_boxes', array( $this, 'accommodation_list_add_meta_boxes' ) );
	}
	
	function accommodation_list_add_meta_boxes() {
	
		global $post;
		$template_file = get_post_meta($post->ID,'_wp_page_template',true);
		if ($template_file == 'page-accommodation-list.php') {
			add_meta_box( $this->accommodation_list_meta_box->id, $this->accommodation_list_meta_box->title, array( $this->accommodation_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
		}
	}
	
	function initialize_post_type() {
	
		$this->register_accommodation_post_type();
		$this->register_accommodation_tag_taxonomy();		
		$this->register_accommodation_type_taxonomy();
		$this->create_accommodation_extra_tables();		
	}
	
	function manage_edit_accommodation_columns($columns) {
	
		//unset($columns['taxonomy-accommodation_type']);
		return $columns;
	}

	function remove_unnecessary_meta_boxes() {

		remove_meta_box('tagsdiv-acc_tag', 'accommodation', 'side');		
		remove_meta_box('tagsdiv-accommodation_type', 'accommodation', 'side');		
	}
	
	function register_accommodation_tag_taxonomy(){
	
		$labels = array(
				'name'              => _x( 'Accommodation tags', 'taxonomy general name', 'bookyourtravel' ),
				'singular_name'     => _x( 'Accommodation tag', 'taxonomy singular name', 'bookyourtravel' ),
				'search_items'      => __( 'Search Accommodation tags', 'bookyourtravel' ),
				'all_items'         => __( 'All Accommodation tags', 'bookyourtravel' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'         => __( 'Edit Accommodation tag', 'bookyourtravel' ),
				'update_item'       => __( 'Update Accommodation tag', 'bookyourtravel' ),
				'add_new_item'      => __( 'Add New Accommodation tag', 'bookyourtravel' ),
				'new_item_name'     => __( 'New Accommodation tag Name', 'bookyourtravel' ),
				'separate_items_with_commas' => __( 'Separate accommodation tags with commas', 'bookyourtravel' ),
				'add_or_remove_items'        => __( 'Add or remove accommodation tags', 'bookyourtravel' ),
				'choose_from_most_used'      => __( 'Choose from the most used accommodation tags', 'bookyourtravel' ),
				'not_found'                  => __( 'No accommodation tags found.', 'bookyourtravel' ),
				'menu_name'         => __( 'Accommodation tags', 'bookyourtravel' ),
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
			
			register_taxonomy( 'acc_tag', array( 'accommodation' ), $args );
	}	

	function register_accommodation_type_taxonomy(){
	
		$labels = array(
				'name'              => _x( 'Accommodation types', 'taxonomy general name', 'bookyourtravel' ),
				'singular_name'     => _x( 'Accommodation type', 'taxonomy singular name', 'bookyourtravel' ),
				'search_items'      => __( 'Search Accommodation types', 'bookyourtravel' ),
				'all_items'         => __( 'All Accommodation types', 'bookyourtravel' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'         => __( 'Edit Accommodation type', 'bookyourtravel' ),
				'update_item'       => __( 'Update Accommodation type', 'bookyourtravel' ),
				'add_new_item'      => __( 'Add New Accommodation type', 'bookyourtravel' ),
				'new_item_name'     => __( 'New Accommodation type Name', 'bookyourtravel' ),
				'separate_items_with_commas' => __( 'Separate accommodation types with commas', 'bookyourtravel' ),
				'add_or_remove_items'        => __( 'Add or remove accommodation types', 'bookyourtravel' ),
				'choose_from_most_used'      => __( 'Choose from the most used accommodation types', 'bookyourtravel' ),
				'not_found'                  => __( 'No accommodation types found.', 'bookyourtravel' ),
				'menu_name'         => __( 'Accommodation types', 'bookyourtravel' ),
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
			
		register_taxonomy( 'accommodation_type', array( 'accommodation' ), $args );
	}
	
	function register_accommodation_post_type() {
		
		global $byt_theme_globals;
		
		$accommodations_permalink_slug = $byt_theme_globals->get_accommodations_permalink_slug();
			
		$labels = array(
			'name'                => _x( 'Accommodations', 'Post Type General Name', 'bookyourtravel' ),
			'singular_name'       => _x( 'Accommodation', 'Post Type Singular Name', 'bookyourtravel' ),
			'menu_name'           => __( 'Accommodations', 'bookyourtravel' ),
			'all_items'           => __( 'All Accommodations', 'bookyourtravel' ),
			'view_item'           => __( 'View Accommodation', 'bookyourtravel' ),
			'add_new_item'        => __( 'Add New Accommodation', 'bookyourtravel' ),
			'add_new'             => __( 'New Accommodation', 'bookyourtravel' ),
			'edit_item'           => __( 'Edit Accommodation', 'bookyourtravel' ),
			'update_item'         => __( 'Update Accommodation', 'bookyourtravel' ),
			'search_items'        => __( 'Search Accommodations', 'bookyourtravel' ),
			'not_found'           => __( 'No Accommodations found', 'bookyourtravel' ),
			'not_found_in_trash'  => __( 'No Accommodations found in Trash', 'bookyourtravel' ),
		);
		
		$args = array(
			'label'               => __( 'accommodation', 'bookyourtravel' ),
			'description'         => __( 'Accommodation information pages', 'bookyourtravel' ),
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
			'rewrite' => array('slug' => $accommodations_permalink_slug),
		);
		
		register_post_type( 'accommodation', $args );
	}

	function create_accommodation_extra_tables() {
	
		global $byt_installed_version;

		if ($byt_installed_version != BOOKYOURTRAVEL_VERSION) {
		
			global $wpdb;
			
			$sql = "CREATE TABLE " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " (
						Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						season_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						start_date datetime NOT NULL,
						end_date datetime NOT NULL,
						accommodation_id bigint(20) unsigned NOT NULL,
						room_type_id bigint(20) unsigned NOT NULL DEFAULT '0',
						room_count int(11) NOT NULL,
						price_per_day decimal(16,2) NOT NULL,
						price_per_day_child decimal(16,2) NOT NULL,
						PRIMARY KEY  (Id)
					);";

			// we do not execute sql directly
			// we are calling dbDelta which cant migrate database
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			
			global $EZSQL_ERROR;
			
			$EZSQL_ERROR = array();
			
			$sql = "CREATE TABLE " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . " (
						Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						first_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						last_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						email varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						phone varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						address varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						town varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						zip varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						country varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						special_requirements text CHARACTER SET utf8 COLLATE utf8_bin,
						room_count int(11) NOT NULL DEFAULT '0',
						adults int(11) NOT NULL DEFAULT '0',
						children int(11) NOT NULL DEFAULT '0',
						total_price decimal(16,2) NOT NULL DEFAULT '0.00',
						accommodation_id bigint(20) unsigned NOT NULL,
						room_type_id bigint(20) unsigned NOT NULL,
						date_from datetime NOT NULL,
						date_to datetime NOT NULL,
						user_id bigint(20) unsigned DEFAULT NULL,
						created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
						woo_order_id bigint(20) NULL,
						cart_key VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' NOT NULL,
						currency_code VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
						PRIMARY KEY  (Id)
					);";
					
			dbDelta($sql);			
			$EZSQL_ERROR = array();
		}
	}

	function build_accommodations_search_fields( $fields, &$wp_query ) {

		global $wpdb, $byt_multi_language_count;

		if (isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'accommodation' ) {

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
				
				if ((isset($date_from) || isset($date_to))) {
				
					$fields .= ", (
									SELECT IFNULL(SUM(room_count), 0) rooms_available FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE;
									
					if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
						$fields .= " WHERE accommodation_id = translations_default.element_id ";
					} else {
						$fields .= " WHERE accommodation_id = {$wpdb->posts}.ID ";
					}
									
					if ($date_from != null && $date_to != null) {
						$fields .= $wpdb->prepare(" AND (%s BETWEEN start_date AND end_date OR %s BETWEEN start_date AND end_date) ", $date_from, $date_to);
					} else if ($date_from != null) {
						$fields .= $wpdb->prepare(" AND %s BETWEEN start_date AND end_date ", $date_from);
					} else if ($date_to != null) {
						$fields .= $wpdb->prepare(" AND %s BETWEEN start_date AND end_date ", $date_to);
					}						
					
					$fields .= " ) rooms_available ";
					
					$fields .= ", (
									SELECT IFNULL(SUM(room_count), 0) rooms_booked FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE;
									
					if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
						$fields .= " WHERE accommodation_id = translations_default.element_id ";
					} else {
						$fields .= " WHERE accommodation_id = {$wpdb->posts}.ID ";
					}
									
					if ($date_from != null && $date_to != null) {
						$fields .= $wpdb->prepare(" AND (%s BETWEEN date_from AND date_to OR %s BETWEEN date_from AND date_to) ", $date_from, $date_to);
					} else if ($date_from != null) {
						$fields .= $wpdb->prepare(" AND %s BETWEEN date_from AND date_to ", $date_from);
					} else if ($date_to != null) {
						$fields .= $wpdb->prepare(" AND %s BETWEEN date_from AND date_to ", $date_to);
					}						
					
					$fields .= " ) rooms_booked ";
					
				}
			}

			if (!is_admin()) {
				$fields .= ", ( 
					SELECT IFNULL(meta_value+0, 0) 
					FROM {$wpdb->postmeta} price_meta 
					WHERE price_meta.post_id = {$wpdb->posts}.ID AND meta_key='_accommodation_min_price' 
					LIMIT 1) accommodation_price ";
			}			
		}
		
		return $fields;
	}

	function build_accommodations_search_where( $where, &$wp_query ) {
		
		global $wpdb;
		
		if (isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'accommodation' ) {
			if ( isset($wp_query->query_vars['byt_is_self_catered']) ) {
				$needed_where_part = '';
				$where_array = explode('AND', $where);
				foreach ($where_array as $where_part) {
					if (strpos($where_part,'post_id IS NULL') !== false) {
						// found where part where is_self_catered is checked for NULL
						$needed_where_part = $where_part;
						break;
					}
				}

				if (!empty($needed_where_part)) {
					$prefix = str_replace("post_id IS NULL","",$needed_where_part);
					$prefix = str_replace(")", "", $prefix);
					$prefix = str_replace("(", "", $prefix);
					$prefix = trim($prefix);
					$where = str_replace("{$prefix}post_id IS NULL", "({$prefix}post_id IS NULL OR CAST({$prefix}meta_value AS SIGNED) = '0')", $where);
				}
			}
			if (isset($wp_query->query_vars['s']) && !empty($wp_query->query_vars['s']) && isset($wp_query->query_vars['byt_location_ids']) && isset($wp_query->query_vars['s']) ) {
				$needed_where_part = '';
				$where_array = explode('AND', $where);
				foreach ($where_array as $where_part) {
					if (strpos($where_part,"meta_key = 'accommodation_location_post_id'") !== false) {
						// found where part where is_self_catered is checked for NULL
						$needed_where_part = $where_part;
						break;
					}
				}
				
				if (!empty($needed_where_part)) {
					$prefix = str_replace("meta_key = 'accommodation_location_post_id'","",$needed_where_part);
					$prefix = str_replace(")", "", $prefix);
					$prefix = str_replace("(", "", $prefix);
					$prefix = trim($prefix);

					$location_ids = $wp_query->query_vars['byt_location_ids'];
					$location_ids_str = "'".implode("','", $location_ids)."'";				
					$location_search_param_part = "{$prefix}meta_key = 'accommodation_location_post_id' AND CAST({$prefix}meta_value AS CHAR) IN ($location_ids_str)";							
				
					$where = str_replace($location_search_param_part, "1=1", $where);
					
					$post_content_part = "OR ($wpdb->posts.post_content LIKE '%" . $wp_query->get('s') . "%')";
					$where = str_replace($post_content_part, $post_content_part . " OR ($location_search_param_part) ", $where);
				}
			}
		}
		
		return $where;
	}

	function build_accommodations_search_groupby( $groupby, &$wp_query ) {

		global $wpdb;
		
		if (empty($groupby))
			$groupby = " {$wpdb->posts}.ID ";
		
		if (!is_admin()) {
			$search_only_available = false;
			if (isset($wp_query->query_vars['search_only_available']))
				$search_only_available = $wp_query->get('search_only_available');
			
			if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'accommodation' ) {
				
				$date_from = null;
				if ( isset($wp_query->query_vars['byt_date_from']) )
					$date_from = date('Y-m-d', strtotime($wp_query->get('byt_date_from')));
				
				$date_to = null;		
				if ( isset($wp_query->query_vars['byt_date_to']) )
					$date_to = date('Y-m-d', strtotime($wp_query->get('byt_date_to') . ' -1 day'));
				
				if (isset($date_from) && $date_from == $date_to)
					$date_to = date('Y-m-d', strtotime($wp_query->get('byt_date_from') . ' +7 day'));
				
				$groupby .= ' HAVING 1=1 ';
				
				if ($search_only_available && (isset($date_from) || isset($date_to))) {				
					$groupby .= ' AND rooms_available > rooms_booked ';		
					
					if (isset($wp_query->query_vars['byt_rooms'])) {
						$groupby .= $wpdb->prepare(" AND rooms_available >= %d ", $wp_query->query_vars['byt_rooms']);
					}
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
									$groupby .= $wpdb->prepare(" OR (accommodation_price >= %d AND accommodation_price <= %d ) ", $bottom, $top);
								} else {
									$groupby .= $wpdb->prepare(" OR (accommodation_price >= %d ) ", $bottom);
								}
							}
						}
						
						$groupby .= ")";

					}
				}
				
				if ($search_only_available)
					$groupby .= " AND accommodation_price > 0 ";
			}
		}
		
		return $groupby;
	}
	
	function accommodations_search_join($join) {
		global $wp_query, $wpdb, $byt_multi_language_count;

		if (!is_admin()) {
			if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
				$join .= " 	INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_accommodation' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = {$wpdb->posts}.ID
							INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_accommodation' AND translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND translations_default.trid = translations.trid ";
			}
		}
		
		return $join;
	}

	function list_accommodations_count ( $paged = 0, $per_page = -1, $orderby = '', $order = '', $location_id = 0, $accommodation_types_array = array(), $accommodation_tags_array = array(), $search_args = array(), $featured_only = false, $is_self_catered = null, $author_id = null, $include_private = false, $count_only = false ) { 
		$results = $this->list_accommodations($paged, $per_page, $orderby, $order, $location_id, $accommodation_types_array, $accommodation_tags_array, $search_args, $featured_only, $is_self_catered, $author_id, $include_private, true);
		return $results['total'];
	}
	
	function list_accommodations ( $paged = 0, $per_page = -1, $orderby = '', $order = '', $location_id = 0, $accommodation_types_array = array(), $accommodation_tags_array = array(), $search_args = array(), $featured_only = false, $is_self_catered = null, $author_id = null, $include_private = false, $count_only = false ) {

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
			'post_type'         => 'accommodation',
			'post_status'       => array('publish'),
			'posts_per_page'    => $per_page,
			'paged'				=> $paged,
			'orderby'           => $orderby,
			'suppress_filters' 	=> false,
			'order'				=> $order
		);
		
		if ($orderby == 'star_count') {
			$args['meta_key'] = 'accommodation_star_count';
			$args['orderby'] = 'meta_value_num';
		} else if ($orderby == 'review_score') {
			$args['meta_key'] = 'review_score';
			$args['orderby'] = 'meta_value_num';
		} else if ($orderby == 'min_price') {
			$args['meta_key'] = '_accommodation_min_price';
			$args['orderby'] = 'meta_value_num';
		}
		
		if (isset($search_args['keyword']) && strlen($search_args['keyword']) > 0) {
			$args['s'] = $search_args['keyword'];
		}
		
		if ($include_private) {
			$args['post_status'][] = 'private';
		}
		
		$meta_query = array('relation' => 'AND');
		
		if ( isset($search_args['stars']) && strlen($search_args['stars']) > 0 ) {
			$stars = intval($search_args['stars']);
			if ($stars > 0 & $stars <=5) {
				$meta_query[] = array(
					'key'       => 'accommodation_star_count',
					'value'     => $stars,
					'compare'   => '>=',
					'type' => 'numeric'
				);
			}
		}
		
		if ( isset($search_args['rating']) && strlen($search_args['rating']) > 0 ) {
			$rating = intval($search_args['rating']);			
			if ($rating > 0 & $rating <=10) {
				$meta_query[] = array(
					'key'       => 'review_score',
					'value'     => $rating,
					'compare'   => '>=',
					'type' => 'numeric'
				);
			}
		}

		if (isset($is_self_catered)) {
			$args['byt_is_self_catered'] = $is_self_catered;
			if ($is_self_catered) {
				$meta_query[] = array(
					'key'       => 'accommodation_is_self_catered',
					'value'     => '1',
					'compare'   => '=',
					'type' => 'numeric'
				);
			} else {
				$meta_query[] = array(
					'key'       => 'accommodation_is_self_catered',
					'compare'   => 'NOT EXISTS'
				);
			}		
		}
		
		if (isset($featured_only) && $featured_only) {
			$meta_query[] = array(
				'key'       => 'accommodation_is_featured',
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
			$meta_query[] = array(
				'key'       => 'accommodation_location_post_id',
				'value'     => $location_ids,
				'compare'   => 'IN'
			);
			$args['byt_location_ids'] = $location_ids;
		}
		
		if (!empty($accommodation_types_array)) {
			$args['tax_query'][] = 	array(
					'taxonomy' => 'accommodation_type',
					'field' => 'id',
					'terms' => $accommodation_types_array,
					'operator'=> 'IN'
			);
		}
		
		if (!empty($accommodation_tags_array)) {
			$args['tax_query'][] = 	array(
					'taxonomy' => 'acc_tag',
					'field' => 'id',
					'terms' => $accommodation_tags_array,
					'operator'=> 'IN'
			);
		}
		
		$search_only_available = false;
		if ( isset($search_args['search_only_available'])) {				
			$search_only_available = $search_args['search_only_available'];
		}

		if ( isset($search_args['date_from']) ) {
			$args['byt_date_from'] = $search_args['date_from'];
		}
		if ( isset($search_args['date_to']) ) {
			$args['byt_date_to'] =  $search_args['date_to'];
		}
		if ( isset($search_args['rooms']) ) {
			$args['byt_rooms'] = $search_args['rooms'];
		}
			
		if ( isset($search_args['prices']) ) {
			$args['prices'] = $search_args['prices'];
			$args['price_range_bottom'] = $byt_theme_globals->get_price_range_bottom();
			$args['price_range_increment'] = $byt_theme_globals->get_price_range_increment();
			$args['price_range_count'] = $byt_theme_globals->get_price_range_count();
		}
			
		$args['search_only_available'] = $search_only_available;

		add_filter('posts_where', array($this, 'build_accommodations_search_where'), 10, 2);		
		add_filter('posts_fields', array($this, 'build_accommodations_search_fields'), 10, 2 );
		add_filter('posts_groupby', array($this, 'build_accommodations_search_groupby'), 10, 2 );
		add_filter('posts_join', array($this, 'accommodations_search_join'), 10, 2 );		
		
		$args['meta_query'] = $meta_query;
		
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

		remove_filter('posts_where', array($this, 'build_accommodations_search_where' ) );		
		remove_filter('posts_fields', array($this, 'build_accommodations_search_fields' ) );
		remove_filter('posts_groupby', array($this, 'build_accommodations_search_groupby') );
		remove_filter('posts_join', array($this, 'accommodations_search_join') );		
		
		return $results;
	}

	function calculate_total_price($accommodation_id, $room_type_id, $date_from, $date_to, $room_count, $adults, $children) {

		global $wpdb;
		
		$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
		if ($room_type_id > 0)
			$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type');

		$accommodation_is_price_per_person = get_post_meta($accommodation_id, 'accommodation_is_price_per_person', true);
		$accommodation_count_children_stay_free = get_post_meta($accommodation_id, 'accommodation_count_children_stay_free', true );
		$accommodation_count_children_stay_free = isset($accommodation_count_children_stay_free) ? intval($accommodation_count_children_stay_free) : 0;
		
		$children = $children - $accommodation_count_children_stay_free;
		$children = $children > 0 ? $children : 0;

		// we are actually (in terms of db data) looking for date 1 day before the to date
		// e.g. when you look to book a room from 19.12. to 20.12 you will be staying 1 night, not 2
		$date_to = date('Y-m-d', strtotime($date_to.' -1 day'));
		
		$dates = BYT_Theme_Utils::get_dates_from_range($date_from, $date_to);

		$total_price = 0;
		
		foreach ($dates as $date) {
		
			$date = date('Y-m-d', strtotime($date));
		
			$price_per_day = $this->get_accommodation_price($date, $accommodation_id, $room_type_id, false);
			$child_price_per_day = $this->get_accommodation_price($date, $accommodation_id, $room_type_id, true);
			
			if ($accommodation_is_price_per_person) {
				$total_price += (($adults * $price_per_day) + ($children * $child_price_per_day)) * $room_count;
			} else {
				$total_price += ($price_per_day * $room_count);
			}
		}
		
		$total_price = $total_price * $room_count;

		return $total_price;
	}

	function list_accommodation_vacancies($date, $accommodation_id, $room_type_id=0, $is_child_price = false) {
		
		global $wpdb;
		
		$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
		if ($room_type_id > 0)
			$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type'); 
		
		$sql = "SELECT vacancies.price_per_day_child, vacancies.price_per_day, vacancies.room_count, 
				(
					SELECT IFNULL(SUM(bookings.room_count), 0)
					FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . " bookings
					WHERE bookings.accommodation_id=vacancies.accommodation_id ";

		if ($room_type_id > 0) 
			$sql .= $wpdb->prepare(" AND bookings.room_type_id=%d ", $room_type_id);

		$booking_compare_date = date('Y-m-d 12:00:01', strtotime($date));
		$sql .= $wpdb->prepare(" AND %s BETWEEN bookings.date_from AND bookings.date_to ", $booking_compare_date);
			
		$sql .= ") booked_rooms 
				FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " vacancies 
				WHERE 1=1 ";

		$sql .= $wpdb->prepare(" 	AND vacancies.accommodation_id=%d 
									AND (%s BETWEEN vacancies.start_date AND vacancies.end_date) ", $accommodation_id, $date);

		if ($room_type_id > 0) 
			$sql .= $wpdb->prepare(" AND vacancies.room_type_id=%d ", $room_type_id);

		$sql .= " ORDER BY " . ($is_child_price ? "vacancies.price_per_day_child" : "vacancies.price_per_day");

		return $wpdb->get_results($sql);
	}

	function list_accommodation_vacancy_start_dates($accommodation_id, $room_type_id=0, $month=0, $year=0) {

		global $wpdb;
		
		$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
		if ($room_type_id > 0)
			$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type'); 
		
		$current_date = date('Y-m-d', time());
		$yesterday = date('Y-m-d',strtotime("-1 days"));
		
		$end_date = null;
		if ($month == 0 && $year == 0)
			$end_date = date('Y-m-d', strtotime($current_date . ' + 365 days'));
		else {
			$end_date = sprintf("%d-%d-%d", $year, $month, 1);
			$end_date = date("Y-m-t", strtotime($end_date)); // last day of end date month
		}
		$end_date = date('Y-m-d', strtotime($end_date));
		
		$sql = "SELECT 	availables.single_date, availables.available_rooms, IFNULL(SUM(bookings.room_count), 0) booked_rooms
			FROM (
				SELECT DISTINCT dates.single_date, SUM(vacancies.room_count) available_rooms, date_format(DATE(dates.single_date), '%Y-%m-%d 12:00:01') as bookable_single_date ";
				
		$sql .= $wpdb->prepare(" FROM 
				(
					SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date 
					FROM
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4 
					HAVING single_date <= %s
				) dates,
				" . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " vacancies
				WHERE dates.single_date BETWEEN DATE(vacancies.start_date) AND DATE(vacancies.end_date) 
				AND vacancies.accommodation_id=%d ", $yesterday, $end_date, $accommodation_id);

		if ($room_type_id > 0) 
			$sql .= $wpdb->prepare(" AND vacancies.room_type_id=%d ", $room_type_id);
				
		$sql .= " GROUP BY dates.single_date
			) availables
			LEFT JOIN " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . " bookings ON availables.bookable_single_date BETWEEN bookings.date_from AND bookings.date_to ";
			
		$sql .= $wpdb->prepare(" AND bookings.accommodation_id=%d ", $accommodation_id);
		
		if ($room_type_id > 0) 
			$sql .= $wpdb->prepare(" AND bookings.room_type_id=%d ", $room_type_id);
		
		$sql .= " GROUP BY availables.single_date";
		
		$results = $wpdb->get_results($sql);
		
		$available_dates = array();
		
		foreach ($results as $result) {
		
			$room_count = $result->available_rooms;
			$booked_rooms = $result->booked_rooms;
			
			if ($room_count > $booked_rooms) {
				$result->single_date = date('Y-m-d', strtotime($result->single_date));
				$available_dates[] = $result;
			}
		}
		
		return $available_dates;
	}

	function list_accommodation_vacancy_end_dates($start_date, $accommodation_id, $room_type_id=0, $month=0, $year=0, $day=0) {

		global $wpdb;
		
		$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
		if ($room_type_id > 0)
			$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type'); 
		
		$end_date = null;
		if ($year > 0 && $month > 0 && $day > 0) {
			$end_date = sprintf("%d-%d-%d", $year, $month, $day);
			$end_date = date("Y-m-t", strtotime($end_date)); // last day of end date month
		} else {
			$end_date = date('Y-m-d', strtotime($start_date . ' + 365 days'));
		}	
		$end_date = date('Y-m-d', strtotime($end_date));
		$start_date = date('Y-m-d', strtotime($start_date));

		$sql = "SELECT 	availables.single_date, availables.available_rooms, IFNULL(SUM(bookings.room_count), 0) booked_rooms
			FROM (
				SELECT DISTINCT dates.single_date, SUM(vacancies.room_count) available_rooms, date_format(DATE(dates.single_date), '%Y-%m-%d 11:59:59') as bookable_single_date ";
				
		$sql .= $wpdb->prepare(" FROM 
				(
					SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date 
					FROM
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4 
					HAVING single_date > %s AND single_date <= %s
				) dates,
				" . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " vacancies
				WHERE dates.single_date BETWEEN DATE(vacancies.start_date) AND DATE(vacancies.end_date) 
				AND vacancies.accommodation_id=%d ", $start_date, $start_date, $end_date, $accommodation_id);

		if ($room_type_id > 0) 
			$sql .= $wpdb->prepare(" AND vacancies.room_type_id=%d ", $room_type_id);
				
		$sql .= " GROUP BY dates.single_date
			) availables
			LEFT JOIN " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . " bookings ON availables.bookable_single_date BETWEEN bookings.date_from AND bookings.date_to ";
			
		$sql .= $wpdb->prepare(" AND bookings.accommodation_id=%d ", $accommodation_id);
		
		if ($room_type_id > 0) 
			$sql .= $wpdb->prepare(" AND bookings.room_type_id=%d ", $room_type_id);
		
		$sql .= " GROUP BY availables.single_date";
		
		$results = $wpdb->get_results($sql);
				
		$available_dates = array();
		
		$prev_date = null;
		$next_date = null;
		foreach ($results as $result) {
		
			$new_date = date('Y-m-d', strtotime($result->single_date));
		
			if (isset($prev_date)) {
				$next_date = date('Y-m-d', strtotime($prev_date . ' +1 days'));
				
				if ($next_date != $new_date) {
					// there was a break in days so days after this one are not bookable
					break;
				}
			}

			$room_count = $result->available_rooms;
			$booked_rooms = $result->booked_rooms;
			
			if ($room_count > $booked_rooms) {
				$result->single_date = date('Y-m-d', strtotime($result->single_date));
				$available_dates[] = $result;
			} else if ($new_date == $start_date) {
				$result->single_date = date('Y-m-d', strtotime($result->single_date));
				$result->booked_rooms = $booked_rooms - 1;
				$available_dates[] = $result;
			} else {
				break;
			}
				
			$prev_date = $new_date;
		}
		
		return $available_dates;
	}

	function get_accommodation_price($search_date, $accommodation_id, $room_type_id = 0, $is_child_price = false) {
	
		global $wpdb;
		
		$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
		if ($room_type_id > 0)
			$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type'); 
		
		$search_date = date('Y-m-d', strtotime($search_date));
		
		$sql = "SELECT a.vacancy_id, a.price_per_day, a.price_per_day_child, a.room_count, a.booked_rooms, 
				(@runtot := @runtot + a.room_count) AS running_available_total
				FROM
				(
					SELECT availables.*, IFNULL(SUM(bookings.room_count), 0) booked_rooms
					FROM 
					(
					SELECT availables_inner.*, date_format(DATE(availables_inner.single_date), '%Y-%m-%d 12:00:01') as bookable_single_date ";
					
		$sql .= $wpdb->prepare("FROM
						(
							SELECT vacancies.Id vacancy_id, %s single_date, vacancies.price_per_day, vacancies.price_per_day_child, vacancies.room_count
							FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " vacancies
							WHERE %s BETWEEN vacancies.start_date AND vacancies.end_date AND vacancies.accommodation_id = %d ", $search_date, $search_date, $accommodation_id );
							
			if ($room_type_id > 0)
				$sql .= $wpdb->prepare(" AND vacancies.room_type_id = %d ", $room_type_id);
							
			$sql .= $wpdb->prepare (" 
							GROUP BY vacancy_id
						) availables_inner
					) availables
					LEFT JOIN " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . " bookings ON availables.bookable_single_date BETWEEN bookings.date_from AND bookings.date_to
					AND bookings.accommodation_id = %d ", $accommodation_id);
		
		if ($room_type_id > 0)
			$sql .= $wpdb->prepare(" AND bookings.room_type_id = %d ", $room_type_id);
					
		$sql .=		" GROUP BY availables.vacancy_id
				) a, (SELECT @runtot:=0) AS n
				GROUP BY a.vacancy_id
				HAVING running_available_total > booked_rooms
				ORDER BY price_per_day ASC 
				LIMIT 1 ";
		
		$row = $wpdb->get_row($sql);
		
		return $is_child_price ? $row->price_per_day_child : $row->price_per_day;
	}
	
	function get_accommodation_min_price($accommodation_id=0, $room_type_id=0, $location_id=0) {

		global $wpdb;
	
		if ($accommodation_id > 0)
			$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');			
		if ($room_type_id > 0)
			$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type'); 		
		if ($location_id > 0)
			$location_id = BYT_Theme_Utils::get_default_language_post_id($location_id, 'location'); 

		$accommodation_ids = array();
		if ($accommodation_id > 0) {
			$accommodation_ids[] = $accommodation_id;
		} else if ($location_id > 0) {
			$accommodation_results = $this->list_accommodations(0, -1, '', '', $location_id);
			
			if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) {
				foreach ($accommodation_results['results'] as $accommodation_result) {
					$accommodation_ids[] = $accommodation_result->ID;
				}
			}
		}

		$current_date = date('Y-m-d', time());
		$yesterday = date('Y-m-d',strtotime("-1 days"));
			
		$end_date = date('Y-m-d', strtotime($current_date . ' + 365 days'));		
		
		$sql = "SELECT MIN(price_per_day) min_price
				FROM 
				(
					SELECT vacancy_id, availables.available_rooms, IFNULL(SUM(bookings.room_count), 0) booked_rooms, price_per_day, 
						IF	(@prev_date = availables.single_date
							 ,@total := @total + availables.available_rooms
							 ,@total := availables.available_rooms) AS running_available_total
							 ,@prev_date := availables.single_date AS single_date							 
					FROM ( 
						SELECT DISTINCT dates.single_date, date_format(DATE(dates.single_date), '%Y-%m-%d 12:00:01') as bookable_single_date, SUM(vacancies.room_count) available_rooms, vacancies.Id vacancy_id, vacancies.price_per_day
						FROM ( ";
						
		$sql .= $wpdb->prepare(" SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date 
							FROM 
							(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0, 
							(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1, 
							(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2, 
							(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3, 
							(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4 
							HAVING single_date <= %s 
						) dates, " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " vacancies 
						WHERE dates.single_date BETWEEN DATE(vacancies.start_date) AND DATE(vacancies.end_date) ", $yesterday, $end_date);
				
		$accommodation_ids_string = rtrim(implode(',', $accommodation_ids), ',');
		if (count($accommodation_ids) > 0)
			$sql .= " AND vacancies.accommodation_id IN (" . $accommodation_ids_string . ") ";

		if ($room_type_id > 0)
			$sql .= $wpdb->prepare(" AND vacancies.room_type_id = %d ", $room_type_id);
					
		$sql .= "
						GROUP BY dates.single_date, vacancies.Id
						ORDER BY single_date, price_per_day ASC
					) availables
					LEFT JOIN " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . " bookings ON availables.bookable_single_date BETWEEN bookings.date_from AND bookings.date_to ";
					
		$accommodation_ids_string = rtrim(implode(',', $accommodation_ids), ',');
		if (count($accommodation_ids) > 0)
			$sql .= " AND bookings.accommodation_id IN (" . $accommodation_ids_string . ") ";
				
		if ($room_type_id > 0)
			$sql .= $wpdb->prepare(" AND bookings.room_type_id = %d ", $room_type_id);				
					
		$sql .= ", (SELECT @total:=0, @prev_date:=null) AS n
					GROUP BY availables.single_date, availables.vacancy_id
					HAVING running_available_total > booked_rooms
				) available_entries";
		
		$min_price = floatval($wpdb->get_var($sql));
		
		if ($room_type_id == 0)
			$this->sync_accommodation_min_price($accommodation_id, $min_price);
		
		return $min_price;
	}

	function delete_all_accommodation_vacancies() {

		global $wpdb;

		$sql = "DELETE FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE;
		$wpdb->query($sql);

		delete_post_meta_by_key('_accommodation_min_price');
		
	}

	function get_accommodation_vacancy($vacancy_id ) {
	
		global $wpdb;

		$sql = "SELECT vacancies.*, accommodations.post_title accommodation_name, room_types.post_title room_type
				FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " vacancies 
				INNER JOIN $wpdb->posts accommodations ON accommodations.ID = vacancies.accommodation_id 
				LEFT JOIN $wpdb->posts room_types ON room_types.ID = vacancies.room_type_id 
				WHERE vacancies.Id=%d ";

		return $wpdb->get_row($wpdb->prepare($sql, $vacancy_id));
	}

	function list_all_accommodation_vacancies($accommodation_id, $room_type_id, $orderby = 'Id', $order = 'ASC', $paged = null, $per_page = 0, $author_id = null ) {

		global $wpdb;

		$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
		if ($room_type_id > 0)
			$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type'); 
		
		$sql = "SELECT DISTINCT vacancies.*, accommodations.post_title accommodation_name, room_types.post_title room_type, IFNULL(accommodation_meta_is_per_person.meta_value, 0) accommodation_is_per_person, IFNULL(accommodation_meta_is_self_catered.meta_value, 0) accommodation_is_self_catered
				FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . " vacancies 
				INNER JOIN $wpdb->posts accommodations ON accommodations.ID = vacancies.accommodation_id 
				LEFT JOIN $wpdb->postmeta accommodation_meta_is_per_person ON accommodations.ID=accommodation_meta_is_per_person.post_id AND accommodation_meta_is_per_person.meta_key='accommodation_is_price_per_person'
				LEFT JOIN $wpdb->postmeta accommodation_meta_is_self_catered ON accommodations.ID=accommodation_meta_is_self_catered.post_id AND accommodation_meta_is_self_catered.meta_key='accommodation_is_self_catered'
				LEFT JOIN $wpdb->posts room_types ON room_types.ID = vacancies.room_type_id 
				WHERE 1=1 ";
				
		if ($accommodation_id > 0) {
			$sql .= $wpdb->prepare(" AND vacancies.accommodation_id=%d ", $accommodation_id);
		}
		
		if ($room_type_id > 0) {
			$sql .= $wpdb->prepare(" AND vacancies.room_type_id=%d ", $room_type_id);
		}
		
		if (isset($author_id)) {
			$sql .= $wpdb->prepare(" AND accommodations.post_author=%d ", $author_id);
		}

		if(!empty($orderby) & !empty($order)){ 
			$sql.=' ORDER BY ' . $orderby . ' ' . $order; 
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

	function get_accommodation_booking($booking_id) {
	
		global $wpdb, $byt_multi_language_count;

		$sql = "SELECT DISTINCT bookings.*, accommodations.post_title accommodation_name, room_types.post_title room_type
				FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . " bookings ";

		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_accommodation' AND translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND translations_default.element_id = bookings.accommodation_id ";			
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_accommodation' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.trid = translations_default.trid ";
		}
		
		$sql .= " INNER JOIN $wpdb->posts accommodations ON ";
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " accommodations.ID = translations.element_id ";
		} else {
			$sql .= " accommodations.ID = bookings.accommodation_id ";
		}
				
		$sql .= $wpdb->prepare(" LEFT JOIN $wpdb->posts room_types ON room_types.ID = bookings.room_type_id 
				WHERE accommodations.post_status = 'publish' AND (room_types.post_status IS NULL OR room_types.post_status = 'publish') 
				AND bookings.Id = %d ", $booking_id );

		return $wpdb->get_row($sql);
	}

	function list_accommodation_bookings($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $search_term = null, $user_id = 0, $author_id = null) {
	
		global $wpdb, $byt_multi_language_count;

		$sql = "SELECT DISTINCT bookings.*, accommodations.post_title accommodation_name, room_types.post_title room_type
				FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . " bookings ";
				
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_accommodation' AND translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND translations_default.element_id = bookings.accommodation_id ";			
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_accommodation' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.trid = translations_default.trid ";
		}
		
		$sql .= " INNER JOIN $wpdb->posts accommodations ON ";
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " accommodations.ID = translations.element_id ";
		} else {
			$sql .= " accommodations.ID = bookings.accommodation_id ";
		}		
				
		$sql .= " LEFT JOIN $wpdb->posts room_types ON room_types.ID = bookings.room_type_id ";
		$sql .= " WHERE accommodations.post_status = 'publish' AND (room_types.post_status IS NULL OR room_types.post_status = 'publish') ";
		
		
		if ($user_id > 0) {
			$sql .= $wpdb->prepare(" AND bookings.user_id = %d ", $user_id) ;
		}
		
		if ($search_term != null && !empty($search_term)) {
			$search_term = "%" . $search_term . "%";
			$sql .= $wpdb->prepare(" AND 1=1 AND (bookings.first_name LIKE '%s' OR bookings.last_name LIKE '%s') ", $search_term, $search_term);
		}
		
		if (isset($author_id)) {
			$sql .= $wpdb->prepare(" AND accommodations.post_author = %d ", $author_id);
		}
		
		if(!empty($orderby) & !empty($order)){ 
			$sql.=' ORDER BY '.$orderby.' '.$order; 
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

	function create_accommodation_booking($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $date_from, $date_to, $accommodation_id, $room_type_id, $user_id, $is_self_catered, $total_price, $adults, $children) {

		global $wpdb;
		
		$room_count = isset($room_count) && $room_count > 0 ? $room_count : 1;
		
		$date_from = date('Y-m-d 12:00:00',strtotime($date_from));
		$date_to = date('Y-m-d 12:00:00',strtotime($date_to));
		
		$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
		if ($room_type_id > 0)
			$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type'); 
		
		$errors = array();

		$sql = "INSERT INTO " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
				(first_name, last_name, email, phone, address, town, zip, country, special_requirements, room_count, user_id, total_price, adults, children, date_from, date_to, accommodation_id, room_type_id)
				VALUES 
				(%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d, %d, %s, %s, %d, %d);";

		$result = $wpdb->query($wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $user_id, $total_price, $adults, $children, $date_from, $date_to, $accommodation_id, $room_type_id));

		if (is_wp_error($result))
			$errors[] = $result;

		$booking_id = $wpdb->insert_id;

		$min_price = $this->get_accommodation_min_price ($accommodation_id);	
		$this->sync_accommodation_min_price($accommodation_id, $min_price, true);
		
		if (count($errors) > 0)
			return $errors;
		return $booking_id;
	}

	function update_accommodation_booking($booking_id, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $date_from, $date_to, $accommodation_id, $room_type_id, $user_id, $is_self_catered, $total_price, $adults, $children) {

		global $wpdb;
		
		$room_count = isset($room_count) && $room_count > 0 ? $room_count : 1;
		
		$date_from = date('Y-m-d 12:00:00',strtotime($date_from));
		$date_to = date('Y-m-d 12:00:00',strtotime($date_to));
		
		$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
		if ($room_type_id > 0)
			$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type'); 

		$sql = "UPDATE " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
				SET first_name = %s, last_name = %s, email = %s, phone = %s, address = %s, town = %s, zip = %s, country = %s, special_requirements = %s, room_count = %d, user_id = %d, 
					total_price = %f, adults = %d, children = %d, date_from = %s, date_to = %s, accommodation_id = %d, room_type_id = %d
				WHERE Id = %d;";

		$result = $wpdb->query($wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $user_id, $total_price, $adults, $children, $date_from, $date_to, $accommodation_id, $room_type_id, $booking_id));

		$min_price = $this->get_accommodation_min_price ($accommodation_id);	
		$this->sync_accommodation_min_price($accommodation_id, $min_price, true);
		
		return $result;
	}

	function create_accommodation_vacancy($season_name, $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child) {

		global $wpdb;
		
		$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
		if ($room_type_id > 0)
			$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type'); 
		
		$sql = "INSERT INTO " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . "
				(season_name, start_date, end_date, accommodation_id, room_type_id, room_count, price_per_day, price_per_day_child)
				VALUES
				(%s, %s, %s, %d, %d, %d, %f, %f);";
		
		$wpdb->query($wpdb->prepare($sql, $season_name, $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child));	
		
		$min_price = $this->get_accommodation_min_price ($accommodation_id);	
		$this->sync_accommodation_min_price($accommodation_id, $min_price, true);
		
		return $wpdb->insert_id;
	}

	function update_accommodation_vacancy($vacancy_id, $season_name, $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child) {

		global $wpdb;
		
		$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
		if ($room_type_id > 0)
			$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type'); 
		
		$start_date = date('Y-m-d', strtotime($start_date));
		$end_date = date('Y-m-d', strtotime($end_date));
		
		$sql = "UPDATE " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . "
				SET season_name=%s, start_date=%s, end_date=%s, accommodation_id=%d, room_type_id=%d, room_count=%d, price_per_day=%f, price_per_day_child=%f
				WHERE Id=%d";
		
		$wpdb->query($wpdb->prepare($sql, $season_name, $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child, $vacancy_id));	
		
		$min_price = $this->get_accommodation_min_price ($accommodation_id);	
		$this->sync_accommodation_min_price($accommodation_id, $min_price, true);
	}

	function delete_accommodation_vacancy($vacancy_id) {
		
		global $wpdb;
		
		$sql = "DELETE FROM " . BOOKYOURTRAVEL_ACCOMMODATION_VACANCIES_TABLE . "
				WHERE Id = %d";

		$vacancy = $this->get_accommodation_vacancy($vacancy_id);
		
		$wpdb->query($wpdb->prepare($sql, $vacancy_id));
		
		if (isset($vacancy) && isset($vacancy->accommodation_id)) {
			$accommodation_id = $vacancy->accommodation_id;
			$min_price = $this->get_accommodation_min_price ($accommodation_id);	
			$this->sync_accommodation_min_price($accommodation_id, $min_price, true);
		}		
	}

	function delete_accommodation_booking($booking_id) {
		global $wpdb;
		
		$sql = "DELETE FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
				WHERE Id = %d";
		
		$booking = $this->get_accommodation_booking($booking_id);
		$wpdb->query($wpdb->prepare($sql, $booking_id));		
		
		if (isset($booking) && isset($booking->accommodation_id)) {
			$accommodation_id = $booking->accommodation_id;
			$min_price = $this->get_accommodation_min_price ($accommodation_id, 0, 0, true);	
			$this->sync_accommodation_min_price($accommodation_id, $min_price, true);
		}
	}

	function get_count_bookings_with_unfixed_dates() {
		global $wpdb;
		
		$sql = "SELECT COUNT(*) ct 
				FROM " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
				WHERE HOUR(date_from) != 12 OR HOUR(date_to) != 12";
				
		return $wpdb->get_var($sql);
	}

	function fix_accommodation_booking_dates() {
		
		global $wpdb;
		
		$sql = "UPDATE " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
				SET date_from = DATE_ADD(DATE(date_from), INTERVAL 12 HOUR)
				WHERE HOUR(date_from) != 12;";

		$wpdb->query($sql);			
				
		$sql = "UPDATE " . BOOKYOURTRAVEL_ACCOMMODATION_BOOKINGS_TABLE . "
				SET date_to = DATE_ADD(DATE(date_to), INTERVAL 12 HOUR)
				WHERE HOUR(date_to) != 12;";

		$wpdb->query($sql);
	}
	
	function sync_accommodation_min_price($accommodation_id, $min_price, $force = false) {
		
		$last_update_time = get_post_meta($accommodation_id, '_accommodation_min_price_last_update', true);
		$last_update_time = isset($last_update_time) && !empty($last_update_time)  ? $last_update_time : time();
		$time_today = time();
		
		$diff_hours = ($time_today - $last_update_time) / ( 60 * 60 );
		
		if ($diff_hours > 24 || $force) {

			$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
				
			$languages = BYT_Theme_Utils::get_active_languages();
			
			foreach ($languages as $language) {
			
				$language_accommodation_id = BYT_Theme_Utils::get_language_post_id($accommodation_id, 'accommodation', $language);
				
				update_post_meta($language_accommodation_id, '_accommodation_min_price', $min_price);
				update_post_meta($language_accommodation_id, '_accommodation_min_price_last_update', time());
			}
		}
	}
}

global $byt_accommodations_post_type;
// store the instance in a variable to be retrieved later and call init
$byt_accommodations_post_type = BYT_Accommodations_Post_Type::get_instance();
$byt_accommodations_post_type->init();