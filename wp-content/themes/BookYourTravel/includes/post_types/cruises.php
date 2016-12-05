<?php

class BYT_Cruises_Post_Type extends BYT_BaseSingleton {

	private $enable_cruises;
	private $cruise_custom_meta_fields;
	private $cruise_list_custom_meta_fields;
	private $cruise_list_meta_box;

	protected function __construct() {
	
		global $byt_cabin_types_post_type, $post;

		$post_id = 0;
		if (isset($post))
			$post_id = $post->ID;
		else if (isset($_GET['post']))
			$post_id = (int)$_GET['post'];
		
		$cabin_types = array();
		
		if ($post_id > 0) {
			$cabin_type_query = $byt_cabin_types_post_type->list_cabin_types(null, array('publish'), $post_id);
			if ($cabin_type_query->have_posts()) {
				while ($cabin_type_query->have_posts()) {
					$cabin_type_query->the_post();
					global $post;				
					$cabin_types[] = array('value' => $post->ID, 'label' => $post->post_title);
				}
			}
			wp_reset_postdata();
		}
		
		if (count($cabin_types) == 0) {
			// if cruise has no associated cabin types, list all possible cabin types (for backwards compatibility)
			$cabin_type_query = $byt_cabin_types_post_type->list_cabin_types(null, array('publish'), null);
			if ($cabin_type_query->have_posts()) {
				while ($cabin_type_query->have_posts()) {
					$cabin_type_query->the_post();
					global $post;				
					$cabin_types[] = array('value' => $post->ID, 'label' => $post->post_title);
				}
			}
			wp_reset_postdata();	
		}
		
		global $byt_theme_globals;
		
		$this->enable_cruises = $byt_theme_globals->enable_cruises();
		
		if ($this->enable_cruises) {
					
			$this->cruise_custom_meta_fields = array(
				array( // Post ID select box
					'label'	=> __('Is Featured', 'bookyourtravel'), // <label>
					'desc'	=> __('Show in lists where only featured items are shown.', 'bookyourtravel'), // description
					'id'	=> 'cruise_is_featured', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array( // Post ID select box
					'label'	=> __('Cabin types', 'bookyourtravel'), // <label>
					'desc'	=> '', // description
					'id'	=> 'cabin_types', // field id and name
					'type'	=> 'post_checkboxes', // type of field
					'post_type' => array('cabin_type') // post types to display, options are prefixed with their post type
				),
				array( // Post ID select box
					'label'	=> __('Locations', 'bookyourtravel'), // <label>
					'desc'	=> '', // description
					'id'	=> 'locations', // field id and name
					'type'	=> 'post_checkboxes', // type of field
					'post_type' => array('location') // post types to display, options are prefixed with their post type
				),
				array( 
					'label'	=> __('Price per person?', 'bookyourtravel'), // <label>
					'desc'	=> __('Is price calculated per person (adult, child)? If not then calculations are done per cabin.', 'bookyourtravel'), // description
					'id'	=> 'cruise_is_price_per_person', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array(
					'label'	=> __('Count children stay free', 'bookyourtravel'),
					'desc'	=> __('How many kids stay free before we charge a fee?', 'bookyourtravel'),
					'id'	=> 'cruise_count_children_stay_free',
					'type'	=> 'slider',
					'min'	=> '1',
					'max'	=> '5',
					'step'	=> '1'
				),
				array( 
					'label'	=> __('Is for reservation only?', 'bookyourtravel'), // <label>
					'desc'	=> __('If this option is checked, then this particular cruise will not be processed via WooCommerce even if WooCommerce is in use.', 'bookyourtravel'), // description
					'id'	=> 'cruise_is_reservation_only', // field id and name
					'type'	=> 'checkbox', // type of field
				),
				array( // Taxonomy Select box
					'label'	=> __('Facilities', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'facility', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_checkboxes' // type of field
				),
				array( // Taxonomy Select box
					'label'	=> __('Cruise type', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'cruise_type', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_select' // type of field
				),
				array( // Taxonomy Checkboxes
					'label'	=> __('Tags', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'cruise_tag', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_checkboxes' // type of field
				),
				array( // Repeatable & Sortable Text inputs
					'label'	=> __('Gallery images', 'bookyourtravel'), // <label>
					'desc'	=> __('A collection of images to be used in slider/gallery on single page', 'bookyourtravel'), // description
					'id'	=> 'cruise_images', // field id and name
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
					'label'	=> __('Availability extra text', 'bookyourtravel'),
					'desc'	=> __('Extra text shown on availability tab above the book now area.', 'bookyourtravel'),
					'id'	=> 'cruise_availability_text',
					'type'	=> 'textarea'
				),
				array(
					'label'	=> __('Contact email addresses', 'bookyourtravel'),
					'desc'	=> __('Contact email addresses, separate each address with a semi-colon ;', 'bookyourtravel'),
					'id'	=> 'cruise_contact_email',
					'type'	=> 'text'
				),
			);

			global $default_cruise_extra_fields;

			$cruise_extra_fields = of_get_option('cruise_extra_fields');
			if (!is_array($cruise_extra_fields) || count($cruise_extra_fields) == 0)
				$cruise_extra_fields = $default_cruise_extra_fields;
				
			foreach ($cruise_extra_fields as $cruise_extra_field) {
				$field_is_hidden = isset($cruise_extra_field['hide']) ? intval($cruise_extra_field['hide']) : 0;
				
				if (!$field_is_hidden) {
					$extra_field = null;
					$field_label = isset($cruise_extra_field['label']) ? $cruise_extra_field['label'] : '';
					$field_id = isset($cruise_extra_field['id']) ? $cruise_extra_field['id'] : '';
					$field_type = isset($cruise_extra_field['type']) ? $cruise_extra_field['type'] :  '';
					if (!empty($field_label) && !empty($field_id) && !empty($field_type)) {
						$extra_field = array(
							'label'	=> $field_label,
							'desc'	=> '',
							'id'	=> 'cruise_' . $field_id,
							'type'	=> $field_type
						);
					}

					if ($extra_field) 
						$this->cruise_custom_meta_fields[] = $extra_field;
				}
			}
			
			$sort_by_columns = array();
			$sort_by_columns[] = array('value' => 'title', 'label' => __('Cruise title', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'ID', 'label' => __('Cruise ID', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'date', 'label' => __('Publish date', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'rand', 'label' => __('Random', 'bookyourtravel'));
			$sort_by_columns[] = array('value' => 'comment_count', 'label' => __('Comment count', 'bookyourtravel'));
			
			$this->cruise_list_custom_meta_fields = array(
				array( // Taxonomy Select box
					'label'	=> __('Cruise type', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'cruise_type', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_select' // type of field
				),
				array( // Taxonomy Select box
					'label'	=> __('Cruise tags', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'cruise_tag', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_checkboxes' // type of field
				),
				array( // Taxonomy Select box
					'label'	=> __('Location', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'cruise_list_location_post_id', // field id and name
					'type'	=> 'post_select', // type of field
					'post_type' => array('location') // post types to display, options are prefixed with their post type
				),
				array( // Select box
					'label'	=> __('Sort by field', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'cruise_list_sort_by', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'select', // type of field
					'options' => $sort_by_columns
				),
				array( // Post ID select box
					'label'	=> __('Sort descending?', 'bookyourtravel'), // <label>
					'desc'	=> __('If checked, will sort cruises in descending order', 'bookyourtravel'), // description
					'id'	=> 'cruise_list_sort_descending', // field id and name
					'type'	=> 'checkbox', // type of field
				),
			);

		
			// our parent class might
			// contain shared code in its constructor
			parent::__construct();	
		}
	}
	
    public function init() {

		if ($this->enable_cruises) {	
		
			add_action( 'admin_init', array($this, 'remove_unnecessary_meta_boxes') );
			add_filter('manage_edit-cruise_columns', array( $this, 'manage_edit_cruise_columns'), 10, 1);	
			add_action( 'byt_initialize_post_types', array( $this, 'initialize_post_type' ), 0);
			add_action( 'admin_init', array( $this, 'cruise_admin_init' ) );
			add_action( 'edited_cruise_type', array( $this, 'save_cruise_type_custom_meta' ), 10, 2 );  
			add_action( 'create_cruise_type', array( $this, 'save_cruise_type_custom_meta' ), 10, 2 );
			add_action( 'cruise_type_add_form_fields', array( $this, 'cruise_type_add_new_meta_fields' ), 10, 2 );
			add_action( 'cruise_type_edit_form_fields', array( $this, 'cruise_type_edit_meta_fields' ), 10, 2 );
		}
	}
		
	function cruise_admin_init() {

		new custom_add_meta_box( 'cruise_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->cruise_custom_meta_fields, 'cruise' );

		$this->cruise_list_meta_box = new custom_add_meta_box( 'cruise_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->cruise_list_custom_meta_fields, 'page' );		
		remove_action( 'add_meta_boxes', array( $this->cruise_list_meta_box, 'add_box' ) );
		add_action('add_meta_boxes', array( $this, 'cruise_list_add_meta_boxes') );
	}
	
	function cruise_list_add_meta_boxes() {
		global $post;
		$template_file = get_post_meta($post->ID,'_wp_page_template',true);
		if ($template_file == 'page-cruise-list.php') {
			add_meta_box( $this->cruise_list_meta_box->id, $this->cruise_list_meta_box->title, array( $this->cruise_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
		}
	}
	
	function initialize_post_type() {
	
		$this->register_cruise_post_type();
		$this->register_cruise_tag_taxonomy();		
		$this->register_cruise_type_taxonomy();
		$this->create_cruise_extra_tables();		
	}
	
	function manage_edit_cruise_columns($columns) {
	
		//unset($columns['taxonomy-cruise_type']);
		return $columns;
	}

	function remove_unnecessary_meta_boxes() {

		remove_meta_box('tagsdiv-cruise_tag', 'cruise', 'side');		
		remove_meta_box('tagsdiv-cruise_type', 'cruise', 'side');		
	}	

	function register_cruise_tag_taxonomy(){
	
		$labels = array(
				'name'              => _x( 'Cruise tags', 'taxonomy general name', 'bookyourtravel' ),
				'singular_name'     => _x( 'Cruise tag', 'taxonomy singular name', 'bookyourtravel' ),
				'search_items'      => __( 'Search Cruise tags', 'bookyourtravel' ),
				'all_items'         => __( 'All Cruise tags', 'bookyourtravel' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'         => __( 'Edit Cruise tag', 'bookyourtravel' ),
				'update_item'       => __( 'Update Cruise tag', 'bookyourtravel' ),
				'add_new_item'      => __( 'Add New Cruise tag', 'bookyourtravel' ),
				'new_item_name'     => __( 'New Cruise tag Name', 'bookyourtravel' ),
				'separate_items_with_commas' => __( 'Separate cruise tags with commas', 'bookyourtravel' ),
				'add_or_remove_items'        => __( 'Add or remove cruise tags', 'bookyourtravel' ),
				'choose_from_most_used'      => __( 'Choose from the most used cruise tags', 'bookyourtravel' ),
				'not_found'                  => __( 'No cruise tags found.', 'bookyourtravel' ),
				'menu_name'         => __( 'Cruise tags', 'bookyourtravel' ),
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
			
		register_taxonomy( 'cruise_tag', array( 'cruise' ), $args );
	}

	function register_cruise_type_taxonomy(){
		$labels = array(
				'name'              => _x( 'Cruise types', 'taxonomy general name', 'bookyourtravel' ),
				'singular_name'     => _x( 'Cruise type', 'taxonomy singular name', 'bookyourtravel' ),
				'search_items'      => __( 'Search Cruise types', 'bookyourtravel' ),
				'all_items'         => __( 'All Cruise types', 'bookyourtravel' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'         => __( 'Edit Cruise type', 'bookyourtravel' ),
				'update_item'       => __( 'Update Cruise type', 'bookyourtravel' ),
				'add_new_item'      => __( 'Add New Cruise type', 'bookyourtravel' ),
				'new_item_name'     => __( 'New Cruise Type Name', 'bookyourtravel' ),
				'separate_items_with_commas' => __( 'Separate Cruise types with commas', 'bookyourtravel' ),
				'add_or_remove_items'        => __( 'Add or remove Cruise types', 'bookyourtravel' ),
				'choose_from_most_used'      => __( 'Choose from the most used Cruise types', 'bookyourtravel' ),
				'not_found'                  => __( 'No Cruise types found.', 'bookyourtravel' ),
				'menu_name'         => __( 'Cruise types', 'bookyourtravel' ),
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
		
		register_taxonomy( 'cruise_type', 'cruise', $args );
	}

	function register_cruise_post_type() {
		
		global $byt_theme_globals;
		
		$cruises_permalink_slug = $byt_theme_globals->get_cruises_permalink_slug();
		
		$cruise_list_page_id = $byt_theme_globals->get_cruise_list_page_id();
		
		if ($cruise_list_page_id > 0) {

			add_rewrite_rule(
				"{$cruises_permalink_slug}$",
				"index.php?post_type=page&page_id={$cruise_list_page_id}", 'top');
		
			add_rewrite_rule(
				"{$cruises_permalink_slug}/page/?([1-9][0-9]*)",
				"index.php?post_type=page&page_id={$cruise_list_page_id}&paged=\$matches[1]", 'top');
		
		}
		
		add_rewrite_rule(
			"{$cruises_permalink_slug}/([^/]+)/page/?([1-9][0-9]*)",
			"index.php?post_type=cruise&name=\$matches[1]&paged-byt=\$matches[2]", 'top');
			
		add_rewrite_tag('%paged-byt%', '([1-9][0-9]*)');		
		
		$labels = array(
			'name'                => _x( 'Cruises', 'Post Type General Name', 'bookyourtravel' ),
			'singular_name'       => _x( 'Cruise', 'Post Type Singular Name', 'bookyourtravel' ),
			'menu_name'           => __( 'Cruises', 'bookyourtravel' ),
			'all_items'           => __( 'All Cruises', 'bookyourtravel' ),
			'view_item'           => __( 'View Cruise', 'bookyourtravel' ),
			'add_new_item'        => __( 'Add New Cruise', 'bookyourtravel' ),
			'add_new'             => __( 'New Cruise', 'bookyourtravel' ),
			'edit_item'           => __( 'Edit Cruise', 'bookyourtravel' ),
			'update_item'         => __( 'Update Cruise', 'bookyourtravel' ),
			'search_items'        => __( 'Search Cruises', 'bookyourtravel' ),
			'not_found'           => __( 'No Cruises found', 'bookyourtravel' ),
			'not_found_in_trash'  => __( 'No Cruises found in Trash', 'bookyourtravel' ),
		);
		$args = array(
			'label'               => __( 'cruise', 'bookyourtravel' ),
			'description'         => __( 'Cruise information pages', 'bookyourtravel' ),
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
			'rewrite' => array('slug' => $cruises_permalink_slug),
		);
		
		register_post_type( 'cruise', $args );	
	}

	function create_cruise_extra_tables() {

		global $byt_installed_version;

		if ($byt_installed_version != BOOKYOURTRAVEL_VERSION) {
			
			global $wpdb;
			
			$table_name = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
			$sql = "CREATE TABLE " . $table_name . " (
						Id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						start_date datetime NOT NULL,
						duration_days int NOT NULL DEFAULT 0,
						end_date datetime NULL,
						cruise_id bigint(20) unsigned NOT NULL,
						cabin_type_id bigint(20) unsigned NOT NULL DEFAULT '0',
						cabin_count int(11) NOT NULL,
						price decimal(16,2) NOT NULL,
						price_child decimal(16,2) NOT NULL,
						created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
						PRIMARY KEY  (Id)
					);";
					
			// we do not execute sql directly
			// we are calling dbDelta which cant migrate database
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			
			global $EZSQL_ERROR;
			$EZSQL_ERROR = array();
			
			$table_name = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
			$sql = "CREATE TABLE " . $table_name . " (
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
						adults int(11) NOT NULL DEFAULT '0',
						children int(11) NOT NULL DEFAULT '0',
						cruise_schedule_id bigint(20) NOT NULL,
						cruise_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
						user_id bigint(20) unsigned DEFAULT NULL,
						total_price_adults decimal(16, 2) NOT NULL,
						total_price_children decimal(16, 2) NOT NULL,
						total_price decimal(16, 2) NOT NULL,
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
	
	function cruise_type_add_new_meta_fields() {
		// this will add the custom meta fields to the add new term page	
		$days_of_week = BYT_Theme_Utils::get_days_of_week();
	?>
		<div class="form-field">
			<label for="term_meta[cruise_type_is_repeated]"><?php _e( 'Is cruise repeated?', 'bookyourtravel' ); ?></label>
			<select id="term_meta[cruise_type_is_repeated]" name="term_meta[cruise_type_is_repeated]" onchange="isCruiseTypeRepeatedChanged('block')">
				<option value="0"><?php _e('No', 'bookyourtravel') ?></option>
				<option value="1"><?php _e('Daily', 'bookyourtravel') ?></option>
				<option value="2"><?php _e('Weekdays', 'bookyourtravel') ?></option>
				<option value="3"><?php _e('Weekly', 'bookyourtravel') ?></option>
			</select>
			<p class="description"><?php _e( 'Do cruises belonging to this cruise type repeat on a daily, weekly, weekday or monthly basis?','bookyourtravel' ); ?></p>
		</div>
		<div id="tr_cruise_type_day_of_week" class="form-field" style="display:none">
			<label for="term_meta[cruise_type_day_of_week]"><?php _e( 'Start day (if weekly or monthly)', 'bookyourtravel' ); ?></label>
			<select id="term_meta[cruise_type_day_of_week]" name="term_meta[cruise_type_day_of_week]">
			  <?php 
				for ($i=0; $i<count($days_of_week); $i++) { 
					$day_of_week = $days_of_week[$i]; ?>
			  <option value="<?php echo esc_attr($i); ?>"><?php echo $day_of_week; ?></option>
			  <?php } ?>
			</select>		
			<p class="description"><?php _e( 'Select a start day of the week for weekly cruise','bookyourtravel' ); ?></p>
		</div>
	<?php
	}

	function cruise_type_edit_meta_fields($term) {
	 
		$days_of_week = BYT_Theme_Utils::get_days_of_week();
	 
		// put the term ID into a variable
		$t_id = $term->term_id;
	 
		// retrieve the existing value(s) for this meta field. This returns an array
		$term_meta = get_option( "taxonomy_$t_id" ); ?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="term_meta[cruise_type_is_repeated]"><?php _e( 'Is cruise repeated?', 'bookyourtravel' ); ?></label></th>
			<td>
				<select id="term_meta[cruise_type_is_repeated]" name="term_meta[cruise_type_is_repeated]" onchange="isCruiseTypeRepeatedChanged('table-row')">
					<option <?php echo (int) $term_meta['cruise_type_is_repeated'] == 0 ? 'selected' : '' ?> value="0"><?php _e('No', 'bookyourtravel') ?></option>
					<option <?php echo (int) $term_meta['cruise_type_is_repeated'] == 1 ? 'selected' : '' ?> value="1"><?php _e('Daily', 'bookyourtravel') ?></option>
					<option <?php echo (int) $term_meta['cruise_type_is_repeated'] == 2 ? 'selected' : '' ?> value="2"><?php _e('Weekdays', 'bookyourtravel') ?></option>
					<option <?php echo (int) $term_meta['cruise_type_is_repeated'] == 3 ? 'selected' : '' ?> value="3"><?php _e('Weekly', 'bookyourtravel') ?></option>
				</select>
				<p class="description"><?php _e( 'Do cruises belonging to this cruise type repeat on a daily or weekly basis?','bookyourtravel' ); ?></p>
			</td>
		</tr>
		<tr id="tr_cruise_type_day_of_week" class="form-field" style="<?php echo (int)$term_meta['cruise_type_is_repeated'] < 3 ? 'display:none' : ''; ?>">
			<th scope="row" valign="top"><label for="term_meta[cruise_type_day_of_week]"><?php _e( 'Start day (if weekly)', 'bookyourtravel' ); ?></label></th>
			<td>
				<select id="term_meta[cruise_type_day_of_week]" name="term_meta[cruise_type_day_of_week]">
				  <?php 
					for ($i=0; $i<count($days_of_week); $i++) { 
						$day_of_week = $days_of_week[$i]; ?>
				  <option <?php echo (int)$term_meta['cruise_type_day_of_week'] == $i ? 'selected' : '' ?> value="<?php echo esc_attr($i); ?>"><?php echo $day_of_week; ?></option>
				  <?php } ?>
				</select>	
				<p class="description"><?php _e( 'Select a start day of the week for weekly cruise','bookyourtravel' ); ?></p>
			</td>
		</tr>
	<?php
	}

	function save_cruise_type_custom_meta( $term_id ) {
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

	function cruises_search_fields( $fields, &$wp_query ) {

		global $wpdb, $byt_multi_language_count;

		if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'cruise' ) {
			
			$search_only_available = false;
			if (isset($wp_query->query_vars['search_only_available']))
				$search_only_available = $wp_query->get('search_only_available');
			
			if ($search_only_available || isset($wp_query->query_vars['byt_date_from']) || isset($wp_query->query_vars['byt_date_from'])) {
				
				$date_from = null;
				if ( isset($wp_query->query_vars['byt_date_from']) )
					$date_from = date('Y-m-d', strtotime($wp_query->get('byt_date_from')));
				
				if (isset($date_from)) {
				
					$fields .= ", (
									SELECT IFNULL(SUM(cabin_count), 0) cabins_available FROM " . BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE . " schedule ";
									
					if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
						$fields .= " WHERE cruise_id = translations_default.element_id ";
					} else {
						$fields .= " WHERE cruise_id = {$wpdb->posts}.ID ";
					}							
									
					if ($date_from != null) {
						$fields .= $wpdb->prepare( " AND ( ( %s >= start_date AND DATE_ADD(start_date, INTERVAL schedule.duration_days DAY) >= %s AND (end_date IS NULL OR end_date = '0000-00-00 00:00:00') ) OR ( %s >= start_date AND %s <= end_date	) )	", $date_from, $date_from, $date_from, $date_from);
					}
					
					$fields .= " ) cabins_available ";
					
					$fields .= ", (
									SELECT (IFNULL(SUM(adults), 0) + IFNULL(SUM(children), 0)) places_booked 
									FROM " . BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE . " bookings
									INNER JOIN " . BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE . " schedule ON bookings.cruise_schedule_id = schedule.Id ";
									
					if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
						$fields .= " WHERE cruise_id = translations_default.element_id ";
					} else {
						$fields .= " WHERE cruise_id = {$wpdb->posts}.ID ";
					}	
									
					if ($date_from != null) {
						$fields .= $wpdb->prepare( " AND ( ( %s >= start_date AND DATE_ADD(start_date, INTERVAL schedule.duration_days DAY) >= %s AND (end_date IS NULL OR end_date = '0000-00-00 00:00:00') ) OR ( %s >= start_date AND %s <= end_date	) )	", $date_from, $date_from, $date_from, $date_from);
					}
					
					$fields .= " ) cabins_booked ";
				}
			}
			
			if (!is_admin()) {
				$fields .= ", ( SELECT IFNULL(meta_value+0, 0) FROM {$wpdb->postmeta} price_meta WHERE price_meta.post_id = {$wpdb->posts}.ID AND meta_key='_cruise_min_price' LIMIT 1) cruise_price ";
			}	
							
		}

		return $fields;
	}
	
	function cruises_search_join($join) {
	
		global $wp_query, $wpdb, $byt_multi_language_count;
	
		if (!is_admin()) {
			if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
				$join .= " 	INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_cruise' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.element_id = {$wpdb->posts}.ID
							INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_cruise' AND translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND translations_default.trid = translations.trid ";
			}
		}
		
		return $join;
	}
	
	function cruises_search_where( $where, &$wp_query ) {
		
		global $wpdb;
		
		if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'cruise' ) {
			if ( isset($wp_query->query_vars['s']) && !empty($wp_query->query_vars['s']) && isset($wp_query->query_vars['byt_location_id']) && isset($wp_query->query_vars['s']) ) {
				$needed_where_part = '';
				$where_array = explode('AND', $where);
				foreach ($where_array as $where_part) {
					if (strpos($where_part,"meta_key = 'locations'") !== false) {
						$needed_where_part = $where_part;
						break;
					}
				}
				
				if (!empty($needed_where_part)) {
					$prefix = str_replace("meta_key = 'locations'","",$needed_where_part);
					$prefix = str_replace(")", "", $prefix);
					$prefix = str_replace("(", "", $prefix);
					$prefix = trim($prefix);

					$location_id = $wp_query->query_vars['byt_location_id'];
					$location_id_serialized = serialize((string)$location_id);			
					$location_search_param_part = "{$prefix}meta_key = 'locations' AND CAST({$prefix}meta_value AS CHAR) LIKE ($location_id_serialized)";							
				
					$where = str_replace($location_search_param_part, "1=1", $where);
					
					$post_content_part = "OR ($wpdb->posts.post_content LIKE '%" . $wp_query->get('s') . "%')";
					$where = str_replace($post_content_part, $post_content_part . " OR ($location_search_param_part) ", $where);				
				}
			}
		}
		
		return $where;
	}

	function cruises_search_groupby( $groupby, &$wp_query ) {

		global $wpdb;
		
		if (empty($groupby))
			$groupby = " {$wpdb->posts}.ID ";
		
		if (!is_admin()) {
			if ( isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'cruise' ) {
				
				$date_from = null;
				if ( isset($wp_query->query_vars['byt_date_from']) )
					$date_from = date('Y-m-d', strtotime($wp_query->get('byt_date_from')));
				
				$search_only_available = false;
				if (isset($wp_query->query_vars['search_only_available']))
					$search_only_available = $wp_query->get('search_only_available');
				
				$groupby .= " HAVING 1=1 ";
				
				if ($search_only_available && isset($date_from)) {				
					$groupby .= ' AND cabins_available > cabins_booked ';
					if (isset($wp_query->query_vars['byt_cabins'])) {
						$groupby .= $wpdb->prepare(" AND cabins_available >= %d ", $wp_query->query_vars['byt_cabins']);
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
									$groupby .= $wpdb->prepare(" OR (cruise_price >= %d AND cruise_price <= %d ) ", $bottom, $top);
								} else {
									$groupby .= $wpdb->prepare(" OR (cruise_price >= %d ) ", $bottom);
								}
							}
						}
						
						$groupby .= ")";
						

					}
				}
				
				if ($search_only_available)
					$groupby .= " AND cruise_price > 0 ";
			}
		}
		
		return $groupby;
	}

	function list_cruises_count($paged = 0, $per_page = 0, $orderby = '', $order = '', $location_id = 0, $cruise_types_array = array(), $cruise_tags_array = array(), $search_args = array(), $featured_only = false, $author_id = null, $include_private = false) {
		$results = $this->list_cruises($paged, $per_page, $orderby, $order, $location_id, $cruise_types_array, $cruise_tags_array, $search_args, $featured_only, $author_id, $include_private, true);
		return $results['total'];
	}

	function list_cruises($paged = 0, $per_page = -1, $orderby = '', $order = '', $location_id = 0, $cruise_types_array = array(), $cruise_tags_array = array(), $search_args = array(), $featured_only = false, $author_id = null, $include_private = false, $count_only = false ) {
		
		global $byt_theme_globals;
		
		$args = array(
			'post_type'         => 'cruise',
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
			$args['meta_key'] = '_cruise_min_price';
			$args['orderby'] = 'meta_value_num';
		}
			
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
				'key'       => 'cruise_is_featured',
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

		if (!empty($cruise_types_array)) {
			$args['tax_query'][] = 	array(
					'taxonomy' => 'cruise_type',
					'field' => 'id',
					'terms' => $cruise_types_array,
					'operator'=> 'IN'
			);
		}	
		
		if (!empty($cruise_tags_array)) {
			$args['tax_query'][] = 	array(
					'taxonomy' => 'cruise_tag',
					'field' => 'id',
					'terms' => $cruise_tags_array,
					'operator'=> 'IN'
			);
		}
		
		$search_only_available = false;
		if ( isset($search_args['search_only_available'])) {				
			$search_only_available = $search_args['search_only_available'];
		}
		
		if ( isset($search_args['date_from']) )
			$args['byt_date_from'] = $search_args['date_from'];

		if ( isset($search_args['cabins']) )
			$args['byt_cabins'] = $search_args['cabins'];
			
		$args['search_only_available'] = $search_only_available;

		if ( isset($search_args['prices']) ) {
			$args['prices'] = $search_args['prices'];
			$args['price_range_bottom'] = $byt_theme_globals->get_price_range_bottom();
			$args['price_range_increment'] = $byt_theme_globals->get_price_range_increment();
			$args['price_range_count'] = $byt_theme_globals->get_price_range_count();
		}
		
		if ($location_id > 0) {
			$args['meta_query'][] = array(
				'key'       => 'locations',
				'value'     => $location_id,
				'compare'   => 'LIKE'
			);
			$args['byt_location_id'] = $location_id;
		}
		
		add_filter('posts_where', array($this, 'cruises_search_where'), 10, 2);
		add_filter('posts_fields', array($this, 'cruises_search_fields'), 10, 2 );
		add_filter('posts_groupby', array($this, 'cruises_search_groupby'), 10, 2 );
		add_filter('posts_join', array($this, 'cruises_search_join'), 10, 2 );
		
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
		
		remove_filter('posts_where', array($this, 'cruises_search_where') );
		remove_filter('posts_fields', array($this, 'cruises_search_fields') );
		remove_filter('posts_groupby', array($this, 'cruises_search_groupby' ));
		remove_filter('posts_join', array($this, 'cruises_search_join') );
		
		return $results;
	}

	function list_available_cruise_schedule_entries($cruise_id, $cabin_type_id, $from_date, $from_year, $from_month, $cruise_type_is_repeated, $cruise_type_day_of_week) {

		global $wpdb;

		$cruise_id = BYT_Theme_Utils::get_default_language_post_id($cruise_id, 'cruise');
		if ($cabin_type_id > 0)
			$cabin_type_id = BYT_Theme_Utils::get_default_language_post_id($cabin_type_id, 'cabin_type');
		
		$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
		
		$yesterday = date('Y-m-d',strtotime("-1 days"));

		if ($cruise_type_is_repeated == 0) {
			// oneoff cruises, must have start date in future in order for people to attend
			$sql = "
				SELECT schedule.*, schedule.start_date cruise_date, 
 				(SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.cruise_schedule_id = schedule.Id AND DATE(bookings.cruise_date) = DATE(schedule.start_date)) booked_cabins,
				0 num
				FROM $table_name_schedule schedule 
				WHERE cruise_id=%d AND cabin_type_id=%d AND start_date >= %s 
				HAVING cabin_count > booked_cabins ";
				
			$sql = $wpdb->prepare($sql, $cruise_id, $cabin_type_id, $from_date);
		} else if ($cruise_type_is_repeated == 1) {		
			// daily cruises
			
			$sql = $wpdb->prepare("
				SELECT schedule.*, date_range.single_date cruise_date, 
				(SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.cruise_schedule_id = schedule.Id AND DATE(bookings.cruise_date) = DATE(date_range.single_date)) booked_cabins,
				num
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
					HAVING  YEAR(single_date) = %d AND MONTH(single_date) = %d
				) date_range ON date_range.single_date >= %s
				WHERE cruise_id=%d AND cabin_type_id=%d AND ( ( schedule.end_date IS NULL OR schedule.end_date = '0000-00-00 00:00:00' ) OR date_range.single_date < schedule.end_date )
				HAVING schedule.cabin_count > booked_cabins ";
			
			$sql = $wpdb->prepare($sql, $from_year, $from_month, $from_date, $cruise_id, $cabin_type_id);

		} else if ($cruise_type_is_repeated == 2) {
		
			// weekday cruises
			$sql = $wpdb->prepare("
				SELECT schedule.*, date_range.single_date cruise_date, 
				(SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.cruise_schedule_id = schedule.Id AND DATE(bookings.cruise_date) = DATE(date_range.single_date)) booked_cabins,
				num
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
					HAVING WEEKDAY(single_date) BETWEEN 0 AND 4 AND YEAR(single_date) = %d AND MONTH(single_date) = %d
				) date_range ON date_range.single_date >= %s
				WHERE cruise_id=%d AND cabin_type_id=%d AND ( ( schedule.end_date IS NULL OR schedule.end_date = '0000-00-00 00:00:00' ) OR date_range.single_date < schedule.end_date )	
				HAVING schedule.cabin_count > booked_cabins ";
			
			$sql = $wpdb->prepare($sql, $from_year, $from_month, $from_date, $cruise_id, $cabin_type_id);
		} else if ($cruise_type_is_repeated == 3) {
			
			// weekly cruises
			$sql = $wpdb->prepare("
				SELECT schedule.*, date_range.single_date cruise_date, 
				(SELECT COUNT(*) ct FROM $table_name_bookings bookings WHERE bookings.cruise_schedule_id = schedule.Id AND DATE(bookings.cruise_date) = DATE(date_range.single_date)) booked_cabins,
				num
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
					HAVING WEEKDAY(single_date) = %d AND YEAR(single_date) = %d AND MONTH(single_date) = %d
				) date_range ON date_range.single_date >= %s 
				WHERE cruise_id=%d AND cabin_type_id=%d AND ( ( schedule.end_date IS NULL OR schedule.end_date = '0000-00-00 00:00:00' ) OR date_range.single_date < schedule.end_date ) 			
				HAVING schedule.cabin_count > booked_cabins ";
			
			$sql = $wpdb->prepare($sql, $cruise_type_day_of_week, $from_year, $from_month, $from_date, $cruise_id, $cabin_type_id);		
		}

		return $wpdb->get_results($sql);
	}

	function get_cruise_booking($booking_id) {

		global $wpdb, $byt_multi_language_count;

		$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
		
		$sql = "SELECT 	DISTINCT bookings.*, 
						cruises.post_title cruise_name, 
						cabin_types.post_title cabin_type, 
						schedule.duration_days,
						bookings.total_price,
						schedule.cruise_id,
						schedule.cabin_type_id
				FROM $table_name_bookings bookings 
				INNER JOIN $table_name_schedule schedule ON schedule.Id = bookings.cruise_schedule_id ";

		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations cruise_translations_default ON cruise_translations_default.element_type = 'post_cruise' AND cruise_translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND cruise_translations_default.element_id = schedule.cruise_id ";			
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations cruise_translations ON cruise_translations.element_type = 'post_cruise' AND cruise_translations.language_code='" . ICL_LANGUAGE_CODE . "' AND cruise_translations.trid = cruise_translations_default.trid ";
		}
		
		$sql .= " INNER JOIN $wpdb->posts cruises ON ";
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " cruises.ID = cruise_translations.element_id ";
		} else {
			$sql .= " cruises.ID = schedule.cruise_id ";
		}	
				
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations cabin_translations_default ON cabin_translations_default.element_type = 'post_cabin_type' AND cabin_translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND cabin_translations_default.element_id = schedule.cabin_type_id ";			
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations cabin_translations ON cabin_translations.element_type = 'post_cabin_type' AND cabin_translations.language_code='" . ICL_LANGUAGE_CODE . "' AND cabin_translations.trid = cabin_translations_default.trid ";
		}
		
		$sql .= " INNER JOIN $wpdb->posts cabin_types ON ";
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " cabin_types.ID = cabin_translations.element_id ";
		} else {
			$sql .= " cabin_types.ID = schedule.cabin_type_id ";
		}					
		
		$sql .= " WHERE cruises.post_status = 'publish' AND cabin_types.post_status = 'publish' AND bookings.Id = %d ";

		return $wpdb->get_row($wpdb->prepare($sql, $booking_id));
	}

	function create_cruise_booking($first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $cruise_schedule_id, $user_id, $total_price_adults, $total_price_children, $total_price, $start_date) {

		global $wpdb;
		
		$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;

		$sql = "INSERT INTO $table_name_bookings
				(first_name, last_name, email, phone, address, town, zip, country, special_requirements, adults, children, cruise_schedule_id, user_id, total_price_adults, total_price_children, total_price, cruise_date)
				VALUES 
				(%s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d, %f, %f, %f, %s);";
		$sql = $wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $cruise_schedule_id, $user_id, (float)$total_price_adults, (float)$total_price_children, (float)$total_price, $start_date);
		
		$wpdb->query($sql);
		
		$booking_id = $wpdb->insert_id;
		
		$booking = $this->get_cruise_booking($booking_id);
		$current_date = date('Y-m-d', time());
		$min_price = $this->get_cruise_min_price ($booking->cruise_id, 0, $current_date);
		$this->sync_cruise_min_price($booking->cruise_id, $min_price, true);
		
		return $booking_id;
	}

	function update_cruise_booking($booking_id, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $cruise_schedule_id, $user_id, $total_price_adults, $total_price_children, $total_price, $start_date) {
		
		global $wpdb;
		
		$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;

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
					cruise_schedule_id = %d, 
					user_id = %d, 
					total_price_adults = %f, 
					total_price_children = %f, 
					total_price = %f, 
					cruise_date = %s
				WHERE Id=%d";
				
		$sql = $wpdb->prepare($sql, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $adults, $children, $cruise_schedule_id, $user_id, (float)$total_price_adults, (float)$total_price_children, (float)$total_price, $start_date, $booking_id);
		
		$wpdb->query($sql);
		
		$booking = $this->get_cruise_booking($booking_id);
		$current_date = date('Y-m-d', time());
		$min_price = $this->get_cruise_min_price ($booking->cruise_id, 0, $current_date);
		$this->sync_cruise_min_price($booking->cruise_id, $min_price, true);
	}

	function delete_cruise_booking($booking_id) {

		global $wpdb;
		
		$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
		
		$sql = "DELETE FROM $table_name_bookings
				WHERE Id = %d";
		
		$booking = $this->get_cruise_booking($booking_id);
		
		$wpdb->query($wpdb->prepare($sql, $booking_id));
		
		$current_date = date('Y-m-d', time());
		$min_price = $this->get_cruise_min_price ($booking->cruise_id, 0, $current_date);
		$this->sync_cruise_min_price($booking->cruise_id, $min_price, true);
	}

	function list_cruise_bookings($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $search_term = null, $user_id = 0, $author_id = null ) {

		global $wpdb, $byt_multi_language_count;
		
		$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;

		$sql = "SELECT 	DISTINCT bookings.*, 
						cruises.post_title cruise_name, 
						cabin_types.post_title cabin_type, 
						schedule.duration_days,
						bookings.total_price,
						schedule.cruise_id,
						schedule.cabin_type_id
				FROM $table_name_bookings bookings 
				INNER JOIN $table_name_schedule schedule ON schedule.Id = bookings.cruise_schedule_id ";

		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations cruise_translations_default ON cruise_translations_default.element_type = 'post_cruise' AND cruise_translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND cruise_translations_default.element_id = schedule.cruise_id ";			
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations cruise_translations ON cruise_translations.element_type = 'post_cruise' AND cruise_translations.language_code='" . ICL_LANGUAGE_CODE . "' AND cruise_translations.trid = cruise_translations_default.trid ";
		}
		
		$sql .= " INNER JOIN $wpdb->posts cruises ON ";
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " cruises.ID = cruise_translations.element_id ";
		} else {
			$sql .= " cruises.ID = schedule.cruise_id ";
		}	
				
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations cabin_translations_default ON cabin_translations_default.element_type = 'post_cabin_type' AND cabin_translations_default.language_code='" . BYT_Theme_Utils::get_default_language() . "' AND cabin_translations_default.element_id = schedule.cabin_type_id ";			
			$sql .= " INNER JOIN " . $wpdb->prefix . "icl_translations cabin_translations ON cabin_translations.element_type = 'post_cabin_type' AND cabin_translations.language_code='" . ICL_LANGUAGE_CODE . "' AND cabin_translations.trid = cabin_translations_default.trid ";
		}
		
		$sql .= " INNER JOIN $wpdb->posts cabin_types ON ";
		if(defined('ICL_LANGUAGE_CODE') && (BYT_Theme_Utils::get_default_language() != ICL_LANGUAGE_CODE || $byt_multi_language_count > 1)) {
			$sql .= " cabin_types.ID = cabin_translations.element_id ";
		} else {
			$sql .= " cabin_types.ID = schedule.cabin_type_id ";
		}					
		
		$sql .= " WHERE cruises.post_status = 'publish' AND cabin_types.post_status = 'publish' ";
		
		if ($search_term != null && !empty($search_term)) {
			$search_term = "%" . $search_term . "%";
			$sql .= $wpdb->prepare(" AND (bookings.first_name LIKE '%s' OR bookings.last_name LIKE '%s') ", $search_term, $search_term);
		}
		
		if (isset($user_id)) {
			$sql .= $wpdb->prepare(" AND bookings.user_id=%d ", $user_id);
		}
		
		if (isset($author_id)) {
			$sql .= $wpdb->prepare(" AND cruises.post_author=%d ", $author_id);
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

	function create_cruise_schedule($cruise_id, $cabin_type_id, $cabin_count, $start_date, $duration_days, $price, $price_child, $end_date) {

		global $wpdb;
		
		$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
		
		$cruise_id = BYT_Theme_Utils::get_default_language_post_id($cruise_id, 'cruise');
		$cabin_type_id = BYT_Theme_Utils::get_default_language_post_id($cabin_type_id, 'cabin_type');
		
		if ($end_date == null) {
			$sql = "INSERT INTO $table_name_schedule
					(cruise_id, cabin_type_id, cabin_count, start_date, duration_days, price, price_child, end_date)
					VALUES
					(%d, %d, %d, %s, %d, %f, %f, null);";
			$sql = $wpdb->prepare($sql, $cruise_id, $cabin_type_id, $cabin_count, $start_date, $duration_days, $price, $price_child);
		} else {
			$sql = "INSERT INTO $table_name_schedule
					(cruise_id, cabin_type_id, cabin_count, start_date, duration_days, price, price_child, end_date)
					VALUES
					(%d, %d, %d, %s, %d, %f, %f, %s);";
			$sql = $wpdb->prepare($sql, $cruise_id, $cabin_type_id, $cabin_count, $start_date, $duration_days, $price, $price_child, $end_date);
		}
		
		$wpdb->query($sql);
		
		$current_date = date('Y-m-d', time());
		$min_price = $this->get_cruise_min_price ($cruise_id, 0, $current_date);
		$this->sync_cruise_min_price($cruise_id, $min_price, true);
	}

	function update_cruise_schedule($schedule_id, $cruise_id, $cabin_type_id, $cabin_count, $start_date, $duration_days, $price, $price_child, $end_date) {

		global $wpdb;
		
		$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
		
		$cruise_id = BYT_Theme_Utils::get_default_language_post_id($cruise_id, 'cruise');
		$cabin_type_id = BYT_Theme_Utils::get_default_language_post_id($cabin_type_id, 'cabin_type');

		if ($end_date == null) {
			$sql = "UPDATE " . BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE . "
					SET cruise_id=%d, cabin_type_id=%d, cabin_count=%d, start_date=%s, duration_days=%d, price=%f, price_child=%f, end_date=null
					WHERE Id=%d";
			$sql = $wpdb->prepare($sql, $cruise_id, $cabin_type_id, $cabin_count, $start_date, $duration_days, $price, $price_child, $schedule_id);
		} else {
			$sql = "UPDATE " . BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE . "
					SET cruise_id=%d, cabin_type_id=%d, cabin_count=%d, start_date=%s, duration_days=%d, price=%f, price_child=%f, end_date=%s
					WHERE Id=%d";
			$sql = $wpdb->prepare($sql, $cruise_id, $cabin_type_id, $cabin_count, $start_date, $duration_days, $price, $price_child, $end_date, $schedule_id);
		}
		
		$wpdb->query($sql);	
		
		$current_date = date('Y-m-d', time());
		$min_price = $this->get_cruise_min_price ($cruise_id, 0, $current_date);
		$this->sync_cruise_min_price($cruise_id, $min_price, true);
	}

	function delete_cruise_schedule($schedule_id) {

		global $wpdb;
		
		$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
		
		$sql = "DELETE FROM $table_name_schedule
				WHERE Id = %d";
		
		$schedule = $this->get_cruise_schedule($schedule_id);
		
		$wpdb->query($wpdb->prepare($sql, $schedule_id));	
		
		$current_date = date('Y-m-d', time());
		$min_price = $this->get_cruise_min_price ($schedule->cruise_id, 0, $current_date);
		$this->sync_cruise_min_price($schedule->cruise_id, $min_price);
	}

	function get_cruise_schedule($cruise_schedule_id) {

		global $wpdb;
			
		$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
			
		$sql = "SELECT 	schedule.*, 
						cruises.post_title cruise_name, 
						cabin_types.post_title cabin_type,
						(
							SELECT COUNT(*) ct 
							FROM $table_name_bookings bookings 
							WHERE bookings.cruise_schedule_id = schedule.Id 
						) has_bookings,
						IFNULL(cruise_price_meta.meta_value, 0) cruise_is_price_per_person
				FROM $table_name_schedule schedule 
				INNER JOIN $wpdb->posts cruises ON cruises.ID = schedule.cruise_id 
				INNER JOIN $wpdb->posts cabin_types ON cabin_types.ID = schedule.cabin_type_id 
				LEFT JOIN $wpdb->postmeta cruise_price_meta ON cruises.ID = cruise_price_meta.post_id AND cruise_price_meta.meta_key = 'cruise_is_price_per_person'
				WHERE schedule.Id=%d AND cruises.post_status = 'publish' AND cabin_types.post_status = 'publish'  ";
		
		$sql = $wpdb->prepare($sql, $cruise_schedule_id);
		return $wpdb->get_row($sql);
	}

	function delete_all_cruise_schedules() {

		global $wpdb;
		$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
		$sql = "DELETE FROM $table_name_schedule";
		$wpdb->query($sql);	
		
		delete_post_meta_by_key('_cruise_min_price');
	}

	function list_cruise_schedules ($paged = null, $per_page = 0, $orderby = 'Id', $order = 'ASC', $day = 0, $month = 0, $year = 0, $cruise_id = 0, $cabin_type_id=0, $search_term = '', $author_id = null) {

		global $wpdb;
		
		$cruise_id = BYT_Theme_Utils::get_default_language_post_id($cruise_id, 'cruise');
		$cabin_type_id = BYT_Theme_Utils::get_default_language_post_id($cabin_type_id, 'cabin_type');
		
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

		$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;
		
		$sql = "SELECT 	schedule.*, 
						cruises.post_title cruise_name, 
						cabin_types.post_title cabin_type,
						(
							SELECT COUNT(*) ct 
							FROM $table_name_bookings bookings 
							WHERE bookings.cruise_schedule_id = schedule.Id 
						) has_bookings,
						IFNULL(cruise_price_meta.meta_value, 0) cruise_is_price_per_person
				FROM $table_name_schedule schedule 
				INNER JOIN $wpdb->posts cruises ON cruises.ID = schedule.cruise_id 
				INNER JOIN $wpdb->posts cabin_types ON cabin_types.ID = schedule.cabin_type_id 
				LEFT JOIN $wpdb->postmeta cruise_price_meta ON cruises.ID = cruise_price_meta.post_id AND cruise_price_meta.meta_key = 'cruise_is_price_per_person'
				WHERE cruises.post_status = 'publish' AND cabin_types.post_status = 'publish' ";
				
		if ($cruise_id > 0) {
			$sql .= $wpdb->prepare(" AND schedule.cruise_id=%d ", $cruise_id);
		}
		
		if ($cabin_type_id > 0) {
			$sql .= $wpdb->prepare(" AND schedule.cabin_type_id=%d ", $cabin_type_id);
		}
		
		if (isset($author_id)) {
			$sql .= $wpdb->prepare(" AND cruises.post_author=%d ", $author_id);
		}

		if ($filter_date != null && !empty($filter_date)) {
			$sql .= $filter_date;
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

	function get_cruise_schedule_price($schedule_id, $is_child_price) {

		global $wpdb;
		
		$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;

		$sql = "SELECT " . ($is_child_price ? "schedule.price_child" : "schedule.price") . "
				FROM $table_name_schedule schedule 
				WHERE id=%d ";	
				
		$price = $wpdb->get_var($wpdb->prepare($sql, $schedule_id));
		
		return $price;
	}

	function get_cruise_available_schedule_id($cruise_id, $cabin_type_id, $date) {

		global $wpdb;
		
		$cruise_obj = new byt_cruise(intval($cruise_id));
		$cruise_id = $cruise_obj->get_base_id();

		$cabin_type_obj = new byt_cabin_type(intval($cabin_type_id));
		$cabin_type_id = $cabin_type_obj->get_base_id();
		
		$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;
		$table_name_bookings = BOOKYOURTRAVEL_CRUISE_BOOKING_TABLE;

		$sql = "SELECT MIN(id) schedule_id
				FROM $table_name_schedule schedule 
				WHERE cruise_id=%d AND cabin_type_id=%d
				";	
				
		if ($cruise_obj->get_type_is_repeated() == 0) {
			$sql .= " AND schedule.start_date = %s ";
		}	

		$schedule_id = $wpdb->get_var($wpdb->prepare($sql, $cruise_id, $cabin_type_id, $date, $date));
		
		return $schedule_id;
	}

	function get_cruise_min_price($cruise_id, $cabin_type_id=0, $date=null) {

		global $wpdb;

		$cruise_obj = new byt_cruise(intval($cruise_id));
		$cruise_id = $cruise_obj->get_base_id();

		if ($cabin_type_id > 0) {
			$cabin_type_obj = new byt_cabin_type(intval($cabin_type_id));
			$cabin_type_id = $cabin_type_obj->get_base_id();
		}
		
		if (!isset($date))
			$date = date('Y-m-d', time());
		
		$table_name_schedule = BOOKYOURTRAVEL_CRUISE_SCHEDULE_TABLE;

		$sql = "SELECT MIN(schedule.price) 
				FROM $table_name_schedule schedule 
				WHERE cruise_id=%d ";	
				
		if ($cabin_type_id > 0) 
			$sql .= $wpdb->prepare(" AND cabin_type_id=%d ", $cabin_type_id);
				
		if ($cruise_obj->get_type_is_repeated() == 0) {
			// this cruise is a one off and is not repeated. If start date is missed, person cannot participate.
			$sql .= $wpdb->prepare(" AND start_date > %s ", $date);
		} else {
			// daily, weekly, weekdays cruises are recurring which means start date is important only in the sense that cruise needs to have become valid before we can get min price.
		}

		$sql = $wpdb->prepare($sql, $cruise_id);

		$min_price = $wpdb->get_var($sql);
		if (!$min_price)
			$min_price = 0;
		
		if ( $cabin_type_id == 0 )
			$this->sync_cruise_min_price($cruise_id, $min_price);
		
		return $min_price;
	}	

	function sync_cruise_min_price($cruise_id, $min_price, $force=false) {
		
		$last_update_time = get_post_meta($cruise_id, '_cruise_min_price_last_update', true);
		$last_update_time = isset($last_update_time) && !empty($last_update_time)  ? $last_update_time : time();
		$time_today = time();
		
		$diff_hours = ($time_today - $last_update_time) / ( 60 * 60 );
		
		if ($diff_hours > 24 || $force) {
		
			$cruise_id = BYT_Theme_Utils::get_default_language_post_id($cruise_id, 'cruise');
				
			$languages = BYT_Theme_Utils::get_active_languages();
			
			foreach ($languages as $language) {
			
				$language_cruise_id = BYT_Theme_Utils::get_language_post_id($cruise_id, 'cruise', $language);
			
				update_post_meta($language_cruise_id, '_cruise_min_price', $min_price);
				update_post_meta($language_cruise_id, '_cruise_min_price_last_update', time());
			}
		}
	}
}

global $byt_cruises_post_type;
// store the instance in a variable to be retrieved later and call init
$byt_cruises_post_type = BYT_Cruises_Post_Type::get_instance();
$byt_cruises_post_type->init();