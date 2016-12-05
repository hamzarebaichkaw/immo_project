<?php

class BYT_Locations_Post_Type extends BYT_BaseSingleton {
	
	private $location_list_custom_meta_fields;
	private $location_custom_meta_fields;
	private $location_list_meta_box;
	
	protected function __construct() {
	
		global $byt_theme_globals;
	
		$this->location_list_custom_meta_fields = array(
			array( // Taxonomy Select box
				'label'	=> __('Location', 'bookyourtravel'), // <label>
				// the description is created in the callback function with a link to Manage the taxonomy terms
				'id'	=> 'location_list_location_post_id', // field id and name
				'type'	=> 'post_select', // type of field
				'post_type' => array('location') // post types to display, options are prefixed with their post type
			)
		);
		
		$this->location_custom_meta_fields = array(
			array( // Post ID select box
				'label'	=> __('Is Featured', 'bookyourtravel'), // <label>
				'desc'	=> __('Show in lists where only featured items are shown.', 'bookyourtravel'), // description
				'id'	=> 'location_is_featured', // field id and name
				'type'	=> 'checkbox', // type of field
			),
			array( // Post ID select box
				'label'	=> __('Display As Directory?', 'bookyourtravel'), // <label>
				'desc'	=> __('Check this option if you want to show list of descendant locations when showing this single location instead of showing what single location page usually shows. Useful for Country locations that than lists all of that country\'s cities.', 'bookyourtravel'), // description
				'id'	=> 'location_display_as_directory', // field id and name
				'type'	=> 'checkbox', // type of field
			),
			array(
				'label'	=> __('Country', 'bookyourtravel'),
				'desc'	=> __('Country name', 'bookyourtravel'),
				'id'	=> 'location_country',
				'type'	=> 'text'
			),
			array( // Repeatable & Sortable Text inputs
				'label'	=> __('Gallery images', 'bookyourtravel'), // <label>
				'desc'	=> __('A collection of images to be used in slider/gallery on single page', 'bookyourtravel'), // description
				'id'	=> 'location_images', // field id and name
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
		);
		
		$location_extra_fields = $byt_theme_globals->get_location_extra_fields();
			
		foreach ($location_extra_fields as $location_extra_field) {
			$field_is_hidden = isset($location_extra_field['hide']) ? intval($location_extra_field['hide']) : 0;
			
			if (!$field_is_hidden) {
				$extra_field = null;
				$field_label = isset($location_extra_field['label']) ? $location_extra_field['label'] : '';
				$field_id = isset($location_extra_field['id']) ? $location_extra_field['id'] : '';
				$field_type = isset($location_extra_field['type']) ? $location_extra_field['type'] :  '';
				if (!empty($field_label) && !empty($field_id) && !empty($field_type)) {
					$extra_field = array(
						'label'	=> $field_label,
						'desc'	=> '',
						'id'	=> 'location_' . $field_id,
						'type'	=> $field_type
					);
				}

				if ($extra_field) 
					$this->location_custom_meta_fields[] = $extra_field;
			}
		}
	
        // our parent class might
        // contain shared code in its constructor
        parent::__construct();
		
    }

    public function init() {
	
		add_action( 'byt_initialize_post_types', array( $this, 'initialize_post_type' ));
		add_action( 'admin_init', array( $this, 'location_admin_init' ) );

	}

	function location_admin_init() {

		new custom_add_meta_box( 'location_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->location_custom_meta_fields, 'location', true );
		
		$this->location_list_meta_box = new custom_add_meta_box( 'location_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->location_list_custom_meta_fields, 'page' );
		remove_action( 'add_meta_boxes', array( $this->location_list_meta_box, 'add_box' ) );
		add_action('add_meta_boxes', array($this, 'location_list_add_meta_boxes') );
	}
	
	function location_list_add_meta_boxes() {
		global $post;
		$template_file = get_post_meta($post->ID,'_wp_page_template',true);
		if ($template_file == 'page-location-list.php') {
			add_meta_box( $this->location_list_meta_box->id, $this->location_list_meta_box->title, array( $this->location_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
		}
	}
	
	function initialize_post_type() {

		$this->register_location_post_type();
	}

	function register_location_post_type() {
	
		global $byt_theme_globals;
		
		$locations_permalink_slug = $byt_theme_globals->get_locations_permalink_slug();

		$location_list_page_id = $byt_theme_globals->get_location_list_page_id();
		
		if ($location_list_page_id > 0) {

			add_rewrite_rule(
				"{$locations_permalink_slug}$",
				"index.php?post_type=page&page_id={$location_list_page_id}", 'top');
		
			add_rewrite_rule(
				"{$locations_permalink_slug}/page/?([1-9][0-9]*)",
				"index.php?post_type=page&page_id={$location_list_page_id}&paged=\$matches[1]", 'top');
		
		}
		
		add_rewrite_rule(
			"{$locations_permalink_slug}/([^/]+)/page/?([1-9][0-9]*)",
			"index.php?post_type=location&name=\$matches[1]&paged-byt=\$matches[2]", 'top');
			
		add_rewrite_tag('%paged-byt%', '([1-9][0-9]*)');
		
		$labels = array(
			'name'                => _x( 'Locations', 'Post Type General Name', 'bookyourtravel' ),
			'singular_name'       => _x( 'Location', 'Post Type Singular Name', 'bookyourtravel' ),
			'menu_name'           => __( 'Locations', 'bookyourtravel' ),
			'all_items'           => __( 'All Locations', 'bookyourtravel' ),
			'view_item'           => __( 'View Location', 'bookyourtravel' ),
			'add_new_item'        => __( 'Add New Location', 'bookyourtravel' ),
			'add_new'             => __( 'New Location', 'bookyourtravel' ),
			'edit_item'           => __( 'Edit Location', 'bookyourtravel' ),
			'update_item'         => __( 'Update Location', 'bookyourtravel' ),
			'search_items'        => __( 'Search locations', 'bookyourtravel' ),
			'not_found'           => __( 'No locations found', 'bookyourtravel' ),
			'not_found_in_trash'  => __( 'No locations found in Trash', 'bookyourtravel' ),
		);
		$args = array(
			'label'               => __( 'location', 'bookyourtravel' ),
			'description'         => __( 'Location information pages', 'bookyourtravel' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'author', 'page-attributes' ),
			'taxonomies'          => array( ),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'rewrite' => array('slug' => $locations_permalink_slug)
		);
		
		register_post_type( 'location', $args );

	}
		
	function list_locations($location_id = 0, $paged = 0, $per_page = -1, $orderby = '', $order = '', $featured_only = false) {

		$location_ids = array();
		
		if ($location_id > 0) {
			$location_ids[] = $location_id;
			$location_descendants = BYT_Theme_Utils::get_post_descendants($location_id, 'location');
			foreach ($location_descendants as $location) {
				$location_ids[] = $location->ID;
			}
		}
		
		$args = array(
			'post_type'         => 'location',
			'post_status'       => array('publish'),
			'posts_per_page'    => $per_page,
			'paged' 			=> $paged, 
			'orderby'           => $orderby,
			'suppress_filters' 	=> false,
			'order'				=> $order,
			'meta_query'        => array('relation' => 'AND')
		);
			
		if (count($location_ids) > 0) {
			$args['meta_query'][] = array(
				'key'       => 'location_location_post_id',
				'value'     => $location_ids,
				'compare'   => 'IN'
			);
		}
		
		if (isset($featured_only) && $featured_only) {
			$args['meta_query'][] = array(
				'key'       => 'location_is_featured',
				'value'     => 1,
				'compare'   => '=',
				'type' => 'numeric'
			);
		}

		$posts_query = new WP_Query($args);	
		$locations = array();
			
		if ($posts_query->have_posts() ) {
			while ( $posts_query->have_posts() ) {
				global $post;
				$posts_query->the_post(); 
				$locations[] = $post;
			}
		}
		
		$results = array(
			'total' => $posts_query->found_posts,
			'results' => $locations
		);
		
		wp_reset_postdata();
		
		return $results;
	}
}

global $byt_locations_post_type;
// store the instance in a variable to be retrieved later and call init
$byt_locations_post_type = BYT_Locations_Post_Type::get_instance();
$byt_locations_post_type->init();