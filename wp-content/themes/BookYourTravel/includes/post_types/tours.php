<?php

class BYT_Tours_Post_Type extends BYT_BaseSingleton {

	private $enable_tours;
	private $tour_custom_meta_fields;
	private $tour_list_custom_meta_fields;
	private $tour_list_meta_box;	

	protected function __construct() {
	
		global $byt_room_types_post_type, $post, $byt_theme_globals;
		
		$this->enable_tours = $byt_theme_globals->enable_tours();
		
		if ($this->enable_tours) {

			$this->tour_custom_meta_fields = array(
				array( // Post ID select box
					'label'	=> __('Is Featured', 'bookyourtravel'), // <label>
					'desc'	=> __('Show in lists where only featured items are shown.', 'bookyourtravel'), // description
					'id'	=> 'tour_is_featured', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array( 
					'label'	=> __('Price per group?', 'bookyourtravel'), // <label>
					'desc'	=> __('Is price calculated per group? If not then calculations are done per person.', 'bookyourtravel'), // description
					'id'	=> 'tour_is_price_per_group', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array( // Post ID select box
					'label'	=> __('Location', 'bookyourtravel'), // <label>
					'desc'	=> '', // description
					'id'	=> 'tour_location_post_id', // field id and name
					'type'	=> 'post_select', // type of field
					'post_type' => array('location') // post types to display, options are prefixed with their post type
				),
				array( // Repeatable & Sortable Text inputs
					'label'	=> __('Gallery images', 'bookyourtravel'), // <label>
					'desc'	=> __('A collection of images to be used in slider/gallery on single page', 'bookyourtravel'), // description
					'id'	=> 'tour_images', // field id and name
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
					'label'	=> __('Map code', 'bookyourtravel'),
					'desc'	=> '',
					'id'	=> 'tour_map_code',
					'type'	=> 'textarea'
				),
				array( // Taxonomy Select box
					'label'	=> __('Tour type', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'tour_type', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_select' // type of field
				),
				array( // Taxonomy Checkboxes
					'label'	=> __('Tags', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'tour_tag', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_checkboxes' // type of field
				),
				array( 
					'label'	=> __('Is for reservation only?', 'bookyourtravel'), // <label>
					'desc'	=> __('If this option is checked, then this particular tour will not be processed via WooCommerce even if WooCommerce is in use.', 'bookyourtravel'), // description
					'id'	=> 'tour_is_reservation_only', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array(
					'label'	=> __('Availability extra text', 'bookyourtravel'),
					'desc'	=> __('Extra text shown on availability tab above the book now area.', 'bookyourtravel'),
					'id'	=> 'tour_availability_text',
					'type'	=> 'textarea'
				),
				array(
					'label'	=> __('Contact email addresses', 'bookyourtravel'),
					'desc'	=> __('Contact email addresses, separate each address with a semi-colon ;', 'bookyourtravel'),
					'id'	=> 'tour_contact_email',
					'type'	=> 'text'
				)
			);

			global $default_tour_extra_fields;

			$tour_extra_fields = of_get_option('tour_extra_fields');
			if (!is_array($tour_extra_fields) || count($tour_extra_fields) == 0)
				$tour_extra_fields = $default_tour_extra_fields;
				
			foreach ($tour_extra_fields as $tour_extra_field) {
				$field_is_hidden = isset($tour_extra_field['hide']) ? intval($tour_extra_field['hide']) : 0;
				
				if (!$field_is_hidden) {
					$extra_field = null;
					$field_label = isset($tour_extra_field['label']) ? $tour_extra_field['label'] : '';
					$field_id = isset($tour_extra_field['id']) ? $tour_extra_field['id'] : '';
					$field_type = isset($tour_extra_field['type']) ? $tour_extra_field['type'] :  '';
					if (!empty($field_label) && !empty($field_id) && !empty($field_type)) {
						$extra_field = array(
							'label'	=> $field_label,
							'desc'	=> '',
							'id'	=> 'tour_' . $field_id,
							'type'	=> $field_type
						);
					}

					if ($extra_field) 
						$this->tour_custom_meta_fields[] = $extra_field;
				}
			}
			
			$sort_by_columns = array();
			$sort_by_columns[] = array('value' => 'title', 'label' => __('Tour title', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'ID', 'label' => __('Tour ID', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'date', 'label' => __('Publish date', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'rand', 'label' => __('Random', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'comment_count', 'label' => __('Comment count', 'bookyourtravel'));
			
			$this->tour_list_custom_meta_fields = array(
				array( // Taxonomy Select box
					'label'	=> __('Tour type', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'tour_type', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_select' // type of field
				),
				array( // Taxonomy Select box
					'label'	=> __('Location', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'tour_list_location_post_id', // field id and name
					'type'	=> 'post_select', // type of field
					'post_type' => array('location') // post types to display, options are prefixed with their post type
				),
				array( // Taxonomy Select box
					'label'	=> __('Tour tags', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'tour_tag', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_checkboxes' // type of field
				),
				array( // Select box
					'label'	=> __('Sort by field', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'tour_list_sort_by', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'select', // type of field
					'options' => $sort_by_columns
				),
				array( // Post ID select box
					'label'	=> __('Sort descending?', 'bookyourtravel'), // <label>
					'desc'	=> __('If checked, will sort tours in descending order', 'bookyourtravel'), // description
					'id'	=> 'tour_list_sort_descending', // field id and name
					'type'	=> 'checkbox', // type of field
				),
			);
		}		
		
        // our parent class might
        // contain shared code in its constructor
        parent::__construct();	
	}

    public function init() {

		if ($this->enable_tours) {	
		
			add_action( 'admin_init', array($this, 'remove_unnecessary_meta_boxes') );
			add_filter( 'manage_edit-tour_columns', array( $this, 'manage_edit_tour_columns'), 10, 1);	
			add_action( 'byt_initialize_post_types', array( $this, 'initialize_post_type' ), 0);
			add_action( 'admin_init', array( $this, 'tour_admin_init' ) );
			
			add_action( 'edited_tour_type', array($this, 'save_tour_type_custom_meta'), 10, 2 );  
			add_action( 'create_tour_type', array($this, 'save_tour_type_custom_meta'), 10, 2 );
			add_action( 'tour_type_add_form_fields', array($this, 'tour_type_add_new_meta_fields'), 10, 2 );
			add_action( 'tour_type_edit_form_fields', array($this, 'tour_type_edit_meta_fields'), 10, 2 );
			
		}
	}
	
	function initialize_post_type() {
	
		$this->register_tour_post_type();
		$this->register_tour_tag_taxonomy();
		$this->register_tour_type_taxonomy();
		$this->create_tour_extra_tables();		
	}
	
	function remove_unnecessary_meta_boxes() {

		remove_meta_box('tagsdiv-tour_tag', 'tour', 'side');		
		remove_meta_box('tagsdiv-tour_type', 'tour', 'side');		
	}
	
	function register_tour_tag_taxonomy(){
	
		$labels = array(
				'name'              => _x( 'Tour tags', 'taxonomy general name', 'bookyourtravel' ),
				'singular_name'     => _x( 'Tour tag', 'taxonomy singular name', 'bookyourtravel' ),
				'search_items'      => __( 'Search Tour tags', 'bookyourtravel' ),
				'all_items'         => __( 'All Tour tags', 'bookyourtravel' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'         => __( 'Edit Tour tag', 'bookyourtravel' ),
				'update_item'       => __( 'Update Tour tag', 'bookyourtravel' ),
				'add_new_item'      => __( 'Add New Tour tag', 'bookyourtravel' ),
				'new_item_name'     => __( 'New Tour tag Name', 'bookyourtravel' ),
				'separate_items_with_commas' => __( 'Separate tour tags with commas', 'bookyourtravel' ),
				'add_or_remove_items'        => __( 'Add or remove tour tags', 'bookyourtravel' ),
				'choose_from_most_used'      => __( 'Choose from the most used tour tags', 'bookyourtravel' ),
				'not_found'                  => __( 'No tour tags found.', 'bookyourtravel' ),
				'menu_name'         => __( 'Tour tags', 'bookyourtravel' ),
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
			
		register_taxonomy( 'tour_tag', array( 'tour' ), $args );
	}	
	
	function manage_edit_tour_columns($columns) {
	
		// unset($columns['taxonomy-tour_type']);
		return $columns;
	}
	
	function tour_admin_init() {
		global $tour_custom_meta_fields;
		new custom_add_meta_box( 'tour_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->tour_custom_meta_fields, 'tour' );
		
		$this->tour_list_meta_box = new custom_add_meta_box( 'tour_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->tour_list_custom_meta_fields, 'page' );	
		remove_action( 'add_meta_boxes', array( $this->tour_list_meta_box, 'add_box' ) );
		add_action('add_meta_boxes', array($this, 'tour_list_add_meta_boxes'));
	}
		
	function tour_list_add_meta_boxes() {
		global $post;
		$template_file = get_post_meta($post->ID,'_wp_page_template',true);
		if ($template_file == 'page-tour-list.php') {
			add_meta_box( $this->tour_list_meta_box->id, $this->tour_list_meta_box->title, array( $this->tour_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
		}
	}

	function register_tour_post_type() {
		
		global $byt_theme_globals;
		$tours_permalink_slug = $byt_theme_globals->get_tours_permalink_slug();
		
		$tour_list_page_id = $byt_theme_globals->get_tour_list_page_id();
		
		if ($tour_list_page_id > 0) {

			add_rewrite_rule(
				"{$tours_permalink_slug}$",
				"index.php?post_type=page&page_id={$tour_list_page_id}", 'top');
		
			add_rewrite_rule(
				"{$tours_permalink_slug}/page/?([1-9][0-9]*)",
				"index.php?post_type=page&page_id={$tour_list_page_id}&paged=\$matches[1]", 'top');
		
		}
		
		add_rewrite_rule(
			"{$tours_permalink_slug}/([^/]+)/page/?([1-9][0-9]*)",
			"index.php?post_type=tour&name=\$matches[1]&paged-byt=\$matches[2]", 'top');
			
		add_rewrite_tag('%paged-byt%', '([1-9][0-9]*)');
		
		$labels = array(
			'name'                => _x( 'Tours', 'Post Type General Name', 'bookyourtravel' ),
			'singular_name'       => _x( 'Tour', 'Post Type Singular Name', 'bookyourtravel' ),
			'menu_name'           => __( 'Tours', 'bookyourtravel' ),
			'all_items'           => __( 'All Tours', 'bookyourtravel' ),
			'view_item'           => __( 'View Tour', 'bookyourtravel' ),
			'add_new_item'        => __( 'Add New Tour', 'bookyourtravel' ),
			'add_new'             => __( 'New Tour', 'bookyourtravel' ),
			'edit_item'           => __( 'Edit Tour', 'bookyourtravel' ),
			'update_item'         => __( 'Update Tour', 'bookyourtravel' ),
			'search_items'        => __( 'Search Tours', 'bookyourtravel' ),
			'not_found'           => __( 'No Tours found', 'bookyourtravel' ),
			'not_found_in_trash'  => __( 'No Tours found in Trash', 'bookyourtravel' ),
		);
		$args = array(
			'label'               => __( 'tour', 'bookyourtravel' ),
			'description'         => __( 'Tour information pages', 'bookyourtravel' ),
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
			'rewrite' => array('slug' => $tours_permalink_slug),
		);
		register_post_type( 'tour', $args );	
	}

	function register_tour_type_taxonomy(){
		$labels = array(
				'name'              => _x( 'Tour types', 'taxonomy general name', 'bookyourtravel' ),
				'singular_name'     => _x( 'Tour type', 'taxonomy singular name', 'bookyourtravel' ),
				'search_items'      => __( 'Search Tour types', 'bookyourtravel' ),
				'all_items'         => __( 'All Tour types', 'bookyourtravel' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'         => __( 'Edit Tour type', 'bookyourtravel' ),
				'update_item'       => __( 'Update Tour type', 'bookyourtravel' ),
				'add_new_item'      => __( 'Add New Tour type', 'bookyourtravel' ),
				'new_item_name'     => __( 'New Tour Type Name', 'bookyourtravel' ),
				'separate_items_with_commas' => __( 'Separate Tour types with commas', 'bookyourtravel' ),
				'add_or_remove_items'        => __( 'Add or remove Tour types', 'bookyourtravel' ),
				'choose_from_most_used'      => __( 'Choose from the most used Tour types', 'bookyourtravel' ),
				'not_found'                  => __( 'No Tour types found.', 'bookyourtravel' ),
				'menu_name'         => __( 'Tour types', 'bookyourtravel' ),
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
		
		register_taxonomy( 'tour_type', 'tour', $args );
	}

	function create_tour_extra_tables() {

		global $wpdb, $byt_installed_version;

		if ($byt_installed_version != BOOKYOURTRAVEL_VERSION) {
		
			// we do not execute sql directly
			// we are calling dbDelta which cant migrate database
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');		
			
			$table_name = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
			$sql = "CREATE TABLE " . $table_name . " (
						Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						tour_id bigint(20) NOT NULL,
						start_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
						duration_days int NOT NULL DEFAULT 0,
						price decimal(16, 2) NOT NULL DEFAULT 0,
						price_child decimal(16, 2) NOT NULL DEFAULT 0, 
						max_people int(11) NOT NULL DEFAULT 0,
						created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
						end_date datetime NULL,
						PRIMARY KEY  (Id)
					);";

			dbDelta($sql);
			
			$table_name = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
			$sql = "CREATE TABLE " . $table_name . " (
						Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						tour_schedule_id bigint(20) NOT NULL,
						tour_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, 
						first_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						last_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						email varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						phone varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						address varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						town varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						zip varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						country varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL,
						special_requirements text CHARACTER SET utf8 COLLATE utf8_bin NULL,
						adults bigint(20) NOT NULL,
						children bigint(20) NOT NULL,
						user_id bigint(20) NOT NULL DEFAULT 0,
						total_price_adults decimal(16, 2) NOT NULL,
						total_price_children decimal(16, 2) NOT NULL,
						total_price decimal(16, 2) NOT NULL,
						created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
						woo_order_id bigint(20) NULL,
						cart_key VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' NOT NULL,
						currency_code VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
						PRIMARY KEY  (Id)
					);";

			dbDelta($sql);
			
			global $EZSQL_ERROR;
			$EZSQL_ERROR = array();
			
		}
	}

	function tours_search_fields( $fields, &$wp_query ) {

		global $wpdb, $byt_multi_language_count;

		if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'tour' ) {
			
			$search_only_available = false;
			if (isset($wp_query->query_vars['search_only_available']))
				$search_only_available = $wp_query->get('search_only_available');
			
			if ($search_only_available || isset($wp_query->query_vars['byt_date_from']) || isset($wp_query->query_vars['byt_date_from'])) {
			
				$date_from = null;
				if ( isset($wp_query->query_vars['byt_date_from']) )
					$date_from = date('Y-m-d', strtotime($wp_query->get('byt_date_from')));
				
				if (isset($date_from)) {
				
					$fields .= ", ( 
									SELECT IFNULL(SUM(max_people), 0) places_available FROM " . BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE . " schedule ";
									
					if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
						$fields .= " WHERE tour_id = translations_default.element_id ";
					} else {
						$fields .= " WHERE tour_id = {$wpdb->posts}.ID ";
					}
							
					if ($date_from != null) {
						$fields .= $wpdb->prepare( " AND ( ( %s >= start_date AND DATE_ADD(start_date, INTERVAL schedule.duration_days DAY) >= %s AND (end_date IS NULL OR end_date = '0000-00-00 00:00:00') ) OR ( %s >= start_date AND %s <= end_date	) )	", $date_from, $date_from, $date_from, $date_from);
					}
					
					$fields .= " ) places_available ";
					
					$fields .= ", (
									SELECT (IFNULL(SUM(adults), 0) + IFNULL(SUM(children), 0)) places_booked 
									FROM " . BOOKYOURTRAVEL_TOUR_BOOKING_TABLE . " bookings
									INNER JOIN " . BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE . " schedule ON bookings.tour_schedule_id = schedule.Id ";
									
					if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
						$fields .= " WHERE tour_id = translations_default.element_id ";
					} else {
						$fields .= " WHERE tour_id = {$wpdb->posts}.ID ";
					}
									
					if ($date_from != null) {
						$fields .= $wpdb->prepare( " AND ( ( %s >= start_date AND DATE_ADD(start_date, INTERVAL schedule.duration_days DAY) >= %s AND (end_date IS NULL OR end_date = '0000-00-00 00:00:00') ) OR ( %s >= start_date AND %s <= end_date	) )	", $date_from, $date_from, $date_from, $date_from);
					}					
					
					$fields .= " ) places_booked ";
				}
			}
							
			if (!is_admin()) {
				$fields .= ", ( SELECT IFNULL(meta_value+0, 0) FROM {$wpdb->postmeta} price_meta WHERE price_meta.post_id = {$wpdb->posts}.ID AND meta_key='_tour_min_price' LIMIT 1) tour_price ";
			}
		}

		return $fields;
	}

	function tours_search_where( $where, &$wp_query ) {
		
		global $wpdb;
		
		if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'tour' ) {
			if ( isset($wp_query->query_vars['s']) && !empty($wp_query->query_vars['s']) && isset($wp_query->query_vars['byt_location_ids']) && isset($wp_query->query_vars['s']) ) {
				$needed_where_part = '';
				$where_array = explode('AND', $where);
				foreach ($where_array as $where_part) {
					if (strpos($where_part,"meta_key = 'tour_location_post_id'") !== false) {
						$needed_where_part = $where_part;
						break;
					}
				}
				
				if (!empty($needed_where_part)) {
					$prefix = str_replace("meta_key = 'tour_location_post_id'","",$needed_where_part);
					$prefix = str_replace(")", "", $prefix);
					$prefix = str_replace("(", "", $prefix);
					$prefix = trim($prefix);

					$location_ids = $wp_query->query_vars['byt_location_ids'];
					$location_ids_str = "'".implode("','", $location_ids)."'";				
					$location_search_param_part = "{$prefix}meta_key = 'tour_location_post_id' AND CAST({$prefix}meta_value AS CHAR) IN ($location_ids_str)";							
				
					$where = str_replace($location_search_param_part, "1=1", $where);
					
					$post_content_part = "OR ($wpdb->posts.post_content LIKE '%" . $wp_query->get('s') . "%')";
					$where = str_replace($post_content_part, $post_content_part . " OR ($location_search_param_part) ", $where);				
				}
			}
		}
		
		return $where;
	}

	function tours_search_groupby( $groupby, &$wp_query ) {

		global $wpdb;
		
		if (empty($groupby))
			$groupby = " {$wpdb->posts}.ID ";
		
		if (!is_admin()) {
			if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'tour' ) {
				
				$date_from = null;
				if ( isset($wp_query->query_vars['byt_date_from']) )
					$date_from = date('Y-m-d', strtotime($wp_query->get('byt_date_from')));
				
				$search_only_available = false;
				if (isset($wp_query->query_vars['search_only_available']))
					$search_only_available = $wp_query->get('search_only_available');
				
				$groupby .= " HAVING 1=1 ";
				
				if ($search_only_available && isset($date_from)) {				
					$groupby .= ' AND places_available > places_booked ';		
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
									$groupby .= $wpdb->prepare(" OR (tour_price >= %d AND tour_price <= %d ) ", $bottom, $top);
								} else {
									$groupby .= $wpdb->prepare(" OR (tour_price >= %d ) ", $bottom);
								}

							}
						}
						
						$groupby .= ")";

					}
				}
				
				if ($search_only_available)
					$groupby .= " AND tour_price > 0 ";
			}
		}
		
		return $groupby;
	}
	
	function tours_search_join($join) {
		global $wp_query, $wpdb, $byt_multi_language_count;
		
		if (!is_admin()) {
			if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
				$join .= " 	INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_tour' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = {$wpdb->posts}.ID
							INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_tour' AND translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND translations_default.trid = translations.trid ";
			}
		}
		
		return $join;
	}

	function list_tours_count($paged = 0, $per_page = 0, $orderby = '', $order = '', $location_id = 0, $tour_types_array = array(), $tour_tags_array = array(), $search_args = array(), $featured_only = false, $author_id = null, $include_private = false) {
		$results = $this->list_tours($paged, $per_page, $orderby, $order, $location_id, $tour_types_array, $tour_tags_array, $search_args, $featured_only, $author_id, $include_private, true);
		return $results['total'];
	}
	
	function list_tours($paged = 0, $per_page = -1, $orderby = '', $order = '', $location_id = 0, $tour_types_array = array(), $tour_tags_array = array(), $search_args = array(), $featured_only = false, $author_id = null, $include_private = false, $count_only = false ) {

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
			'post_type'         => 'tour',
			'post_status'       => array('publish'),
			'posts_per_page'    => $per_page,
			'paged' 			=> $paged, 
			'orderby'           => $orderby,
			'suppress_filters' 	=> false,
			'order'				=> $order,
			'meta_query'        => array('relation' => 'AND')
		);

		if ($orderby == 'review_score') {
			$args['meta_key'] = 'review_score';
			$args['orderby'] = 'meta_value_num';
		} else if ($orderby == 'min_price') {
			$args['meta_key'] = '_tour_min_price';
			$args['orderby'] = 'meta_value_num';
		}
		
		$guests = (isset($search_args['guests']) && isset($search_args['guests'])) ? intval($search_args['guests']) : 0;
		
		if (isset($search_args['keyword']) && strlen($search_args['keyword']) > 0) {
			$args['s'] = $search_args['keyword'];
		}
		
		if ($include_private) {
			$args['post_status'][] = 'private';
		}
		
		if ( isset($search_args['rating']) && strlen($search_args['rating']) > 0 ) {
			$rating = intval($search_args['rating']);			
			if ($rating >= 0 & $rating <=10) {
				$args['meta_query'][] = array(
					'key'       => 'review_score',
					'value'     => $rating,
					'compare'   => '>=',
					'type' => 'numeric'
				);
			}
		}
		
		if (isset($featured_only) && $featured_only) {
			$args['meta_query'][] = array(
				'key'       => 'tour_is_featured',
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
				'key'       => 'tour_location_post_id',
				'value'     => $location_ids,
				'compare'   => 'IN'
			);
			$args['byt_location_ids'] = $location_ids;
		}
		
		if (!empty($tour_types_array)) {
			$args['tax_query'][] = 	array(
					'taxonomy' => 'tour_type',
					'field' => 'id',
					'terms' => $tour_types_array,
					'operator'=> 'IN'
			);
		}
		
		if (!empty($tour_tags_array)) {
			$args['tax_query'][] = 	array(
					'taxonomy' => 'tour_tag',
					'field' => 'id',
					'terms' => $tour_tags_array,
					'operator'=> 'IN'
			);
		}
		
		$search_only_available = false;
		if ( isset($search_args['search_only_available'])) {				
			$search_only_available = $search_args['search_only_available'];
		}

		if ( isset($search_args['date_from']) )
			$args['byt_date_from'] = $search_args['date_from'];

		$args['search_only_available'] = $search_only_available;
		
		if ( isset($search_args['prices']) ) {
			$args['prices'] = $search_args['prices'];
			$args['price_range_bottom'] = $byt_theme_globals->get_price_range_bottom();
			$args['price_range_increment'] = $byt_theme_globals->get_price_range_increment();
			$args['price_range_count'] = $byt_theme_globals->get_price_range_count();
		}	
		
		add_filter('posts_where', array($this, 'tours_search_where'), 10, 2 );
		add_filter('posts_fields', array($this, 'tours_search_fields'), 10, 2 );
		add_filter('posts_groupby', array($this, 'tours_search_groupby'), 10, 2 );
		add_filter('posts_join', array($this, 'tours_search_join'), 10, 2 );
		
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
		
		remove_filter('posts_where', array($this, 'tours_search_where'));
		remove_filter('posts_fields', array($this, 'tours_search_fields' ));
		remove_filter('posts_groupby', array($this, 'tours_search_groupby'));
		remove_filter('posts_join', array($this, 'tours_search_join') );
		
		return $results;		
	}

	function list_available_tour_schedule_entries($tour_id, $from_date=null, $from_year=0, $from_month=0, $tour_type_is_repeated=0, $tour_type_day_of_week=0) {

		global $wpdb;

		$tour_id = BYT_Theme_Utils::get_default_language_post_id($tour_id, 'tour');
		
		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

		$yesterday = date('Y-m-d', strtotime("-1 days"));
		
		if ($from_date == null)
			$from_date = date('Y-m-d',time());
			
		if ($tour_type_is_repeated == 0) {
			// oneoff tours, must have start date in future in order for people to attend
			$sql = "
				SELECT *, schedule.start_date tour_date, 
				(SELECT IFNULL((SUM(adults) + SUM(children)), 0) ct FROM $table_name_bookings bookings WHERE bookings.tour_schedule_id = schedule.Id AND DATE(bookings.tour_date) = DATE(schedule.start_date)) booked_people,
				0 num
				FROM $table_name_schedule schedule 
				WHERE tour_id=%d AND start_date >= %s 
				HAVING max_people > booked_people ";
				
			$sql = $wpdb->prepare($sql, $tour_id, $from_date);
			
		} else if ($tour_type_is_repeated == 1) {		
			
			// daily tours
			$sql = $wpdb->prepare("
				SELECT schedule.Id, schedule.price, schedule.price_child, schedule.duration_days, schedule.max_people, 
				(SELECT IFNULL((SUM(adults) + SUM(children)), 0) ct FROM $table_name_bookings bookings WHERE bookings.tour_schedule_id = schedule.Id AND DATE(bookings.tour_date) = DATE(date_range.single_date)) booked_people,
				date_range.single_date tour_date, num
				FROM $table_name_schedule schedule
				LEFT JOIN 
				(
					SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date, (t1.i*10 + t0.i) num ", $yesterday);
					
			$sql .= "
					FROM
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
					HAVING 1=1 ";
					
			if ($from_year > 0)
				$sql .= $wpdb->prepare(" AND YEAR(single_date) = %d ", $from_year);

			if ($from_month > 0)
				$sql .= $wpdb->prepare(" AND MONTH(single_date) = %d ", $from_month);
					
			$sql .= $wpdb->prepare(") date_range ON date_range.single_date >= %s AND date_range.single_date >= schedule.start_date
				WHERE tour_id=%d AND ( (schedule.end_date IS NULL OR schedule.end_date = '0000-00-00 00:00:00') OR date_range.single_date < schedule.end_date )
				GROUP BY date_range.single_date
				HAVING max_people > booked_people ", $from_date, $tour_id);
			
		} else if ($tour_type_is_repeated == 2) {
		
			// weekday tours
			$sql = $wpdb->prepare("
				SELECT schedule.Id, schedule.price, schedule.price_child, schedule.duration_days, schedule.max_people, 
				(SELECT IFNULL((SUM(adults) + SUM(children)), 0) ct FROM $table_name_bookings bookings WHERE bookings.tour_schedule_id = schedule.Id AND DATE(bookings.tour_date) = DATE(date_range.single_date)) booked_people,
				date_range.single_date tour_date, num
				FROM $table_name_schedule schedule
				LEFT JOIN 
				(
					SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date, (t1.i*10 + t0.i) num ", $yesterday);
			
			$sql .= "
					FROM
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
					HAVING WEEKDAY(single_date) BETWEEN 0 AND 4 ";
					
			if ($from_year > 0)
				$sql .= $wpdb->prepare(" AND YEAR(single_date) = %d ", $from_year);

			if ($from_month > 0)
				$sql .= $wpdb->prepare(" AND MONTH(single_date) = %d ", $from_month);
					
			$sql .= $wpdb->prepare("
				) date_range ON date_range.single_date >= %s AND date_range.single_date >= schedule.start_date
				WHERE tour_id=%d AND ( (schedule.end_date IS NULL OR schedule.end_date = '0000-00-00 00:00:00') OR date_range.single_date < schedule.end_date )	
				HAVING max_people > booked_people ", $from_date, $tour_id);
			
		} else if ($tour_type_is_repeated == 3) {
			
			// weekly tours
			$sql = $wpdb->prepare("
				SELECT schedule.Id, schedule.price, schedule.price_child, schedule.duration_days, schedule.max_people, 
				(SELECT IFNULL((SUM(adults) + SUM(children)), 0) ct FROM $table_name_bookings bookings WHERE bookings.tour_schedule_id = schedule.Id AND DATE(bookings.tour_date) = DATE(date_range.single_date)) booked_people,
				date_range.single_date tour_date, num
				FROM $table_name_schedule schedule
				LEFT JOIN 
				(
					SELECT ADDDATE(%s,t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) single_date, (t1.i*10 + t0.i) num ", $yesterday);
					
			$sql .= $wpdb->prepare("
					FROM
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
					(SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
					HAVING WEEKDAY(single_date) = %d ", $tour_type_day_of_week); 
					
			if ($from_year > 0)
				$sql .= $wpdb->prepare(" AND YEAR(single_date) = %d ", $from_year);

			if ($from_month > 0)
				$sql .= $wpdb->prepare(" AND MONTH(single_date) = %d ", $from_month);

			$sql .= $wpdb->prepare(" ) date_range ON date_range.single_date >= %s AND date_range.single_date >= schedule.start_date
				WHERE tour_id=%d AND( (schedule.end_date IS NULL OR schedule.end_date = '0000-00-00 00:00:00') OR date_range.single_date < schedule.end_date ) 			
				HAVING max_people > booked_people ", $from_date, $tour_id);
		}

		return $wpdb->get_results($sql);
	}

	function get_tour_booking($booking_id) {

		global $wpdb, $byt_multi_language_count;

		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
		
		$sql = "SELECT 	DISTINCT bookings.*, 
						tours.post_title tour_name, 
						schedule.duration_days,
						bookings.total_price,
						schedule.tour_id
				FROM $table_name_bookings bookings 
				INNER JOIN $table_name_schedule schedule ON schedule.Id = bookings.tour_schedule_id ";
				
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_tour' AND translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND translations_default.element_id = schedule.tour_id ";			
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_tour' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.trid = translations_default.trid ";
		}
		
		$sql .= " INNER JOIN $wpdb->posts tours ON ";
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " tours.ID = translations.element_id ";
		} else {
			$sql .= " tours.ID = schedule.tour_id ";
		}		

		$sql .= " WHERE tours.post_status = 'publish' ";

		$sql .= " AND bookings.Id = %d ";

		$sql = $wpdb->prepare($sql, $booking_id);
		return $wpdb->get_row($sql);
	}

	function create_tour_booking($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, $total_price_adults, $total_price_children, $total_price, $start_date) {

		global $wpdb;
		
		$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

		$sql = "INSERT INTO $table_name_bookings
				(first_name, last_name, email, phone, address, town, zip, country, special_requirements, adults, children, tour_schedule_id, user_id, total_price_adults, total_price_children, total_price, tour_date)
				VALUES 
				(%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d, %f, %f, %f, %s);";
		$sql = $wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, (float)$total_price_adults, (float)$total_price_children, (float)$total_price, $start_date);
		
		$wpdb->query($sql);
		
		$booking_id = $wpdb->insert_id;
		
		$booking = $this->get_tour_booking($booking_id);

		$current_date = date('Y-m-d', time());
		$min_price = $this->get_tour_min_price ($booking->tour_id, $current_date);	
		$this->sync_tour_min_price($booking->tour_id, $min_price, true);
		
		return $booking_id;
	}

	function update_tour_booking($booking_id, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, $total_price_adults, $total_price_children, $total_price, $start_date) {
		
		global $wpdb;
		
		$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

		$sql = "UPDATE $table_name_bookings
				SET first_name = %s,
					last_name = %s, 
					email = %s, 
					phone = %s, 
					address = %s, 
					town = %s, 
					zip = %s, 
					country = %s, 
					special_requirements = %s,
					adults = %d, 
					children = %d, 
					tour_schedule_id = %d, 
					user_id = %d, 
					total_price_adults = %f, 
					total_price_children = %f, 
					total_price = %f, 
					tour_date = %s
				WHERE Id=%d";
				
		$sql = $wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $tour_schedule_id, $user_id, (float)$total_price_adults, (float)$total_price_children, (float)$total_price, $start_date, $booking_id);
		
		$wpdb->query($sql);
		
		$booking = $this->get_tour_booking($booking_id);
		$current_date = date('Y-m-d', time());
		$min_price = $this->get_tour_min_price ($booking->tour_id, $current_date);	
		$this->sync_tour_min_price($booking->tour_id, $min_price, true);
	}

	function delete_tour_booking($booking_id) {

		global $wpdb;
		
		$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
		
		$sql = "DELETE FROM $table_name_bookings
				WHERE Id = %d";
				
		$booking = $this->get_tour_booking($booking_id);
		
		$wpdb->query($wpdb->prepare($sql, $booking_id));
		
		$current_date = date('Y-m-d', time());
		$min_price = $this->get_tour_min_price ($booking->tour_id, $current_date);	
		$this->sync_tour_min_price($booking->tour_id, $min_price, true);
		
	}

	function list_tour_bookings($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $search_term = null, $user_id = 0, $author_id = null ) {

		global $wpdb, $byt_multi_language_count;
		
		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

		$sql = "SELECT 	DISTINCT bookings.*, 
						tours.post_title tour_name, 
						schedule.start_date,
						schedule.duration_days,
						bookings.total_price
				FROM $table_name_bookings bookings 
				INNER JOIN $table_name_schedule schedule ON schedule.Id = bookings.tour_schedule_id ";
				
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_tour' AND translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND translations_default.element_id = schedule.tour_id ";			
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_tour' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.trid = translations_default.trid ";
		}
		
		$sql .= " INNER JOIN $wpdb->posts tours ON ";
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " tours.ID = translations.element_id ";
		} else {
			$sql .= " tours.ID = schedule.tour_id ";
		}		
		
		$sql .= " WHERE tours.post_status = 'publish' ";
		
		if ($search_term != null && !empty($search_term)) {
			$search_term = "%" . $search_term . "%";
			$sql .= $wpdb->prepare(" AND (bookings.first_name LIKE '%s' OR bookings.last_name LIKE '%s') ", $search_term, $search_term);
		}
		
		if (isset($user_id)) {
			$sql .= $wpdb->prepare(" AND bookings.user_id=%d ", $user_id);
		}
		
		if (isset($author_id)) {
			$sql .= $wpdb->prepare(" AND tours.post_author=%d ", $author_id);
		}
		
		if(!empty($orderby) && !empty($order)){ 
			$sql.= "ORDER BY $orderby $order"; 
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

	function create_tour_schedule($tour_id, $start_date, $duration_days, $price, $price_child, $max_people, $end_date) {

		global $wpdb;
		
		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		
		$tour_id = BYT_Theme_Utils::get_default_language_post_id($tour_id, 'tour');
		
		if ($end_date == null) {
			$sql = "INSERT INTO $table_name_schedule
					(tour_id, start_date, duration_days, price, price_child, max_people)
					VALUES
					(%d, %s, %d, %f, %f, %d);";
			$sql = $wpdb->prepare($sql, $tour_id, $start_date, $duration_days, $price, $price_child, $max_people);
		} else {
			$end_date = date('Y-m-d', strtotime($end_date));
			$sql = "INSERT INTO $table_name_schedule
					(tour_id, start_date, duration_days, price, price_child, max_people, end_date)
					VALUES
					(%d, %s, %d, %f, %f, %d, %s);";
			$sql = $wpdb->prepare($sql, $tour_id, $start_date, $duration_days, $price, $price_child, $max_people, $end_date);
		}
		
		$wpdb->query($sql);
		
		$current_date = date('Y-m-d', time());
		$min_price = $this->get_tour_min_price ($tour_id, $current_date);	
		$this->sync_tour_min_price($tour_id, $min_price, true);
	}

	function update_tour_schedule($schedule_id, $start_date, $duration_days, $tour_id, $price, $price_child, $max_people, $end_date) {

		global $wpdb;
		
		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		
		$tour_id = BYT_Theme_Utils::get_default_language_post_id($tour_id, 'tour');

		if ($end_date == null) {
			$sql = "UPDATE " . BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE . "
					SET start_date=%s, duration_days=%d, tour_id=%d, price=%f, price_child=%f, max_people=%d
					WHERE Id=%d";
			$sql = $wpdb->prepare($sql, $start_date, $duration_days, $tour_id, $price, $price_child, $max_people, $schedule_id);
		} else {
			$end_date = date('Y-m-d', strtotime($end_date));
			$sql = "UPDATE " . BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE . "
					SET start_date=%s, duration_days=%d, tour_id=%d, price=%f, price_child=%f, max_people=%d, end_date=%s
					WHERE Id=%d";
			$sql = $wpdb->prepare($sql, $start_date, $duration_days, $tour_id, $price, $price_child, $max_people, $end_date, $schedule_id);
		}
		
		$current_date = date('Y-m-d', time());
		$min_price = $this->get_tour_min_price ($tour_id, $current_date);	
		$this->sync_tour_min_price($tour_id, $min_price, true);
		
		$wpdb->query($sql);	
	}

	function delete_tour_schedule($schedule_id) {

		global $wpdb;
		
		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		
		$sql = "DELETE FROM $table_name_schedule
				WHERE Id = %d";
		
		$schedule = $this->get_tour_schedule($schedule_id);
		
		$wpdb->query($wpdb->prepare($sql, $schedule_id));	
		
		$current_date = date('Y-m-d', time());
		$min_price = $this->get_tour_min_price ($schedule->tour_id, $current_date);
		
		$this->sync_tour_min_price($schedule->tour_id, $min_price, true);
	}
	
	function get_tour_schedule_max_people($tour_schedule_id, $tour_id, $date) {

		global $wpdb;
			
		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

		$tour_obj = new byt_tour(intval($tour_id));

		$tour_id = $tour_obj->get_base_id();
		
		$sql = "SELECT 	schedule.max_people, 
						(
							SELECT SUM(adults) + SUM(children) ct 
							FROM $table_name_bookings bookings 
							WHERE bookings.tour_schedule_id = schedule.Id AND bookings.tour_date = %s
						) booking_count
				FROM $table_name_schedule schedule 
				WHERE schedule.Id=%d ";
				
		if ($tour_obj->get_type_is_repeated() == 0) {
			$sql .= " AND schedule.start_date = %s ";
		} else {
			$sql .= " AND %s >= start_date AND (%s < end_date OR end_date IS NULL OR end_date = '0000-00-00 00:00:00') ";
		}
		
		$sql = $wpdb->prepare($sql, $date, $tour_schedule_id, $date, $date);

		return $wpdb->get_row($sql);	
	}

	function get_tour_schedule($tour_schedule_id) {

		global $wpdb;
			
		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
			
		$sql = "SELECT 	schedule.*, tours.post_title tour_name, 
						(
							SELECT COUNT(*) ct 
							FROM $table_name_bookings bookings 
							WHERE bookings.tour_schedule_id = schedule.Id 
						) has_bookings,
						IFNULL(tour_price_meta.meta_value, 0) tour_is_price_per_group
				FROM $table_name_schedule schedule 
				INNER JOIN $wpdb->posts tours ON tours.ID = schedule.tour_id 
				LEFT JOIN $wpdb->postmeta tour_price_meta ON tours.ID = tour_price_meta.post_id AND tour_price_meta.meta_key = 'tour_is_price_per_group'
				WHERE schedule.Id=%d ";
		
		$sql = $wpdb->prepare($sql, $tour_schedule_id);
		return $wpdb->get_row($sql);
	}

	function delete_all_tour_schedules() {

		global $wpdb;
		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		$sql = "DELETE FROM $table_name_schedule";
		$wpdb->query($sql);	
		
		delete_post_meta_by_key('_tour_min_price');
	}
	
	function tour_type_add_new_meta_fields() {
		// this will add the custom meta fields to the add new term page	
		$days_of_week = BYT_Theme_Utils::get_days_of_week();
	?>
		<div class="form-field">
			<label for="term_meta[tour_type_is_repeated]"><?php _e( 'Is tour repeated?', 'bookyourtravel' ); ?></label>
			<select id="term_meta[tour_type_is_repeated]" name="term_meta[tour_type_is_repeated]" onchange="isTourTypeRepeatedChanged('block')">
				<option value="0"><?php _e('No', 'bookyourtravel') ?></option>
				<option value="1"><?php _e('Daily', 'bookyourtravel') ?></option>
				<option value="2"><?php _e('Weekdays', 'bookyourtravel') ?></option>
				<option value="3"><?php _e('Weekly', 'bookyourtravel') ?></option>
			</select>
			<p class="description"><?php _e( 'Do tours belonging to this tour type repeat on a daily or weekly basis?','bookyourtravel' ); ?></p>
		</div>
		<div id="tr_tour_type_day_of_week" class="form-field" style="display:none">
			<label for="term_meta[tour_type_day_of_week]"><?php _e( 'Start day (if weekly)', 'bookyourtravel' ); ?></label>
			<select id="term_meta[tour_type_day_of_week]" name="term_meta[tour_type_day_of_week]">
			  <?php 
				for ($i=0; $i<count($days_of_week); $i++) { 
					$day_of_week = $days_of_week[$i]; ?>
			  <option value="<?php echo esc_attr($i); ?>"><?php echo $day_of_week; ?></option>
			  <?php } ?>
			</select>		
			<p class="description"><?php _e( 'Select a start day of the week for weekly tour','bookyourtravel' ); ?></p>
		</div>
	<?php
	}

	function tour_type_edit_meta_fields($term) {
	 
		$days_of_week = BYT_Theme_Utils::get_days_of_week();
	 
		// put the term ID into a variable
		$t_id = $term->term_id;
	 
		// retrieve the existing value(s) for this meta field. This returns an array
		$term_meta = get_option( "taxonomy_$t_id" ); ?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="term_meta[tour_type_is_repeated]"><?php _e( 'Is tour repeated?', 'bookyourtravel' ); ?></label></th>
			<td>
				<select id="term_meta[tour_type_is_repeated]" name="term_meta[tour_type_is_repeated]" onchange="isTourTypeRepeatedChanged('table-row')">
					<option <?php echo (int) $term_meta['tour_type_is_repeated'] == 0 ? 'selected' : '' ?> value="0"><?php _e('No', 'bookyourtravel') ?></option>
					<option <?php echo (int) $term_meta['tour_type_is_repeated'] == 1 ? 'selected' : '' ?> value="1"><?php _e('Daily', 'bookyourtravel') ?></option>
					<option <?php echo (int) $term_meta['tour_type_is_repeated'] == 2 ? 'selected' : '' ?> value="2"><?php _e('Weekdays', 'bookyourtravel') ?></option>
					<option <?php echo (int) $term_meta['tour_type_is_repeated'] == 3 ? 'selected' : '' ?> value="3"><?php _e('Weekly', 'bookyourtravel') ?></option>
				</select>
				<p class="description"><?php _e( 'Do tours belonging to this tour type repeat on a daily or weekly basis?','bookyourtravel' ); ?></p>
			</td>
		</tr>
		<tr id="tr_tour_type_day_of_week" class="form-field" style="<?php echo (int)$term_meta['tour_type_is_repeated'] < 3 ? 'display:none' : ''; ?>">
			<th scope="row" valign="top"><label for="term_meta[tour_type_day_of_week]"><?php _e( 'Start day (if weekly)', 'bookyourtravel' ); ?></label></th>
			<td>
				<select id="term_meta[tour_type_day_of_week]" name="term_meta[tour_type_day_of_week]">
				  <?php 
					for ($i=0; $i<count($days_of_week); $i++) { 
						$day_of_week = $days_of_week[$i]; ?>
				  <option <?php echo (int)$term_meta['tour_type_day_of_week'] == $i ? 'selected' : '' ?> value="<?php echo esc_attr($i); ?>"><?php echo $day_of_week; ?></option>
				  <?php } ?>
				</select>	
				<p class="description"><?php _e( 'Select a start day of the week for weekly tour','bookyourtravel' ); ?></p>
			</td>
		</tr>
	<?php
	}

	function save_tour_type_custom_meta( $term_id ) {
		if ( isset( $_POST['term_meta'] ) ) {
			$t_id = $term_id;
			$term_meta = get_option( "taxonomy_$t_id" );
			$cat_keys = array_keys( $_POST['term_meta'] );
			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['term_meta'][$key] ) ) {
					$term_meta[$key] = $_POST['term_meta'][$key];
				}
			}
			// Save the option array.
			update_option( "taxonomy_$t_id", $term_meta );
		}
	}

	function list_tour_schedules ($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $day = 0, $month = 0, $year = 0, $tour_id = 0, $search_term = '', $author_id=null) {

		global $wpdb;
		
		$tour_id = BYT_Theme_Utils::get_default_language_post_id($tour_id, 'tour');
		
		$filter_date = '';
		if ($day > 0 || $month > 0 || $year) { 
			$filter_date .= ' AND ( 1=1 ';
			if ($day > 0)
				$filter_date .= $wpdb->prepare(" AND DAY(start_date) = %d ", $day);			
			if ($month > 0)
				$filter_date .= $wpdb->prepare(" AND MONTH(start_date) = %d ", $month);			
			if ($year > 0)
				$filter_date .= $wpdb->prepare(" AND YEAR(start_date) = %d ", $year);			
			$filter_date .= ')';		
		}

		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;
		
		$sql = "SELECT 	schedule.*, tours.post_title tour_name, 
						(
							SELECT COUNT(*) ct 
							FROM $table_name_bookings bookings 
							WHERE bookings.tour_schedule_id = schedule.Id 
						) has_bookings,
						IFNULL(tour_price_meta.meta_value, 0) tour_is_price_per_group
				FROM $table_name_schedule schedule 
				INNER JOIN $wpdb->posts tours ON tours.ID = schedule.tour_id 
				LEFT JOIN $wpdb->postmeta tour_price_meta ON tours.ID = tour_price_meta.post_id AND tour_price_meta.meta_key = 'tour_is_price_per_group'
				WHERE tours.post_status = 'publish' ";
				
		if ($tour_id > 0) {
			$sql .= $wpdb->prepare(" AND schedule.tour_id=%d ", $tour_id);
		}

		if ($filter_date != null && !empty($filter_date)) {
			$sql .= $filter_date;
		}
		
		if (isset($author_id)) {
			$sql .= $wpdb->prepare(" AND tours.post_author=%d ", $author_id);
		}
		
		if(!empty($orderby) & !empty($order)){ 
			$sql .= $wpdb->prepare(" ORDER BY %s %s ", $orderby, $order); 
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

	function get_tour_schedule_price($schedule_id, $is_child_price) {

		global $wpdb;
		
		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;

		$sql = "SELECT " . ($is_child_price ? "schedule.price_child" : "schedule.price") . "
				FROM $table_name_schedule schedule 
				WHERE id=%d ";	
				
		$price = $wpdb->get_var($wpdb->prepare($sql, $schedule_id));
		
		return $price;
	}

	function get_tour_available_schedule_id($tour_id, $date) {

		global $wpdb;
		
		$tour_obj = new byt_tour(intval($tour_id));

		$tour_id = $tour_obj->get_base_id();
		
		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_TOUR_BOOKING_TABLE;

		$sql = "SELECT MIN(id) schedule_id
				FROM $table_name_schedule schedule 
				WHERE tour_id=%d AND schedule.max_people > (
					SELECT COUNT(*) ct 
					FROM $table_name_bookings bookings 
					WHERE bookings.tour_schedule_id = schedule.Id AND bookings.tour_date = %s
				) 
				";	
				
		if ($tour_obj->get_type_is_repeated() == 0) {
			$sql .= " AND schedule.start_date = %s ";
		} else {
			$sql .= " AND %s >= start_date AND (%s < end_date OR end_date IS NULL OR end_date = '0000-00-00 00:00:00') ";
		}

		$sql = $wpdb->prepare($sql, $tour_id, $date, $date, $date);

		$schedule_id = $wpdb->get_var($sql);
		
		return $schedule_id;
	}

	function get_tour_min_price($tour_id, $date = null) {

		global $wpdb;
		
		$tour_obj = new byt_tour(intval($tour_id));

		$tour_id = $tour_obj->get_base_id();
		
		if (!isset($date))
			$date = date('Y-m-d', time());
		
		$table_name_schedule = BOOKYOURTRAVEL_TOUR_SCHEDULE_TABLE;

		$sql = "SELECT MIN(schedule.price) 
				FROM $table_name_schedule schedule 
				WHERE tour_id=%d ";	
				
		if ($tour_obj->get_type_is_repeated() == 0) {
			// this tour is a one off and is not repeated. If start date is missed, person cannot participate.
			$sql .= $wpdb->prepare(" AND start_date > %s ", $date);
		} else {
			// daily, weekly, weekdays tours are recurring which means start date is important only in the sense that tour needs to have become valid before we can get min price.
		}

		$sql = $wpdb->prepare($sql, $tour_id);
		$min_price = $wpdb->get_var($sql);
		if (!$min_price)
			$min_price = 0;
			
		$this->sync_tour_min_price($tour_id, $min_price);
		
		return $min_price;
	}

	function sync_tour_min_price($tour_id, $min_price, $force=false) {
		
		$last_update_time = get_post_meta($tour_id, '_tour_min_price_last_update', true);
		$last_update_time = isset($last_update_time) && !empty($last_update_time)  ? $last_update_time : time();
		$time_today = time();
		
		$diff_hours = ($time_today - $last_update_time) / ( 60 * 60 );
		
		if ($diff_hours > 24 || $force) {
		
			$tour_id = BYT_Theme_Utils::get_default_language_post_id($tour_id, 'tour');
				
			$languages = BYT_Theme_Utils::get_active_languages();
			
			foreach ($languages as $language) {
			
				$language_tour_id = BYT_Theme_Utils::get_language_post_id($tour_id, 'tour', $language);
			
				update_post_meta($language_tour_id, '_tour_min_price', $min_price);
				update_post_meta($language_tour_id, '_tour_min_price_last_update', time());
			}

		}
	}
}

global $byt_tours_post_type;
// store the instance in a variable to be retrieved later and call init
$byt_tours_post_type = BYT_Tours_Post_Type::get_instance();
$byt_tours_post_type->init();