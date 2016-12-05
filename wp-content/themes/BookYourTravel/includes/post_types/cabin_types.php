<?php

class BYT_Cabin_Types_Post_Type extends BYT_BaseSingleton {

	private $enable_cruises;
	private $cabin_type_custom_meta_fields;

	protected function __construct() {
	
		global $byt_theme_globals;
		
		$this->enable_cruises = $byt_theme_globals->enable_cruises();
		
		if ($this->enable_cruises) {
					
			$this->cabin_type_custom_meta_fields = array(
				array(
					'label'	=> __('Max adult count', 'bookyourtravel'),
					'desc'	=> __('How many adults are allowed in the cabin?', 'bookyourtravel'),
					'id'	=> 'cabin_type_max_count',
					'type'	=> 'slider',
					'min'	=> '1',
					'max'	=> '10',
					'step'	=> '1'
				),
				array(
					'label'	=> __('Max child count', 'bookyourtravel'),
					'desc'	=> __('How many children are allowed in the cabin?', 'bookyourtravel'),
					'id'	=> 'cabin_type_max_child_count',
					'type'	=> 'slider',
					'min'	=> '1',
					'max'	=> '10',
					'step'	=> '1'
				),
				array(
					'label'	=> __('Bed size', 'bookyourtravel'),
					'desc'	=> __('How big is/are the beds?', 'bookyourtravel'),
					'id'	=> 'cabin_type_bed_size',
					'type'	=> 'text'
				),
				array(
					'label'	=> __('Cabin size', 'bookyourtravel'),
					'desc'	=> __('What is the cabin size (m2)?', 'bookyourtravel'),
					'id'	=> 'cabin_type_room_size',
					'type'	=> 'text'
				),
				array(
					'label'	=> __('Cabin meta information', 'bookyourtravel'),
					'desc'	=> __('What other information applies to this specific cabin type?', 'bookyourtravel'),
					'id'	=> 'cabin_type_meta',
					'type'	=> 'text'
				),
				array( // Taxonomy Select box
					'label'	=> __('Facilities', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'facility', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'tax_checkboxes' // type of field
				),
			);
		}
		
        // our parent class might
        // contain shared code in its constructor
        parent::__construct();
		
    }
	
    public function init() {
		
		if ($this->enable_cruises) {
			add_action( 'byt_initialize_post_types', array( $this, 'initialize_post_type' ));
			add_action( 'admin_init', array( $this, 'cabin_type_admin_init' ) );
		}
	}

	function initialize_post_type() {

		$this->register_cabin_type_post_type();
	}
	
	function cabin_type_admin_init() {
		new custom_add_meta_box( 'cabin_type_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->cabin_type_custom_meta_fields, 'cabin_type' );
	}
		
	function register_cabin_type_post_type() {
		
		$labels = array(
			'name'                => _x( 'Cabin types', 'Post Type General Name', 'bookyourtravel' ),
			'singular_name'       => _x( 'Cabin type', 'Post Type Singular Name', 'bookyourtravel' ),
			'menu_name'           => __( 'Cabin types', 'bookyourtravel' ),
			'all_items'           => __( 'Cabin types', 'bookyourtravel' ),
			'view_item'           => __( 'View Cabin type', 'bookyourtravel' ),
			'add_new_item'        => __( 'Add New Cabin type', 'bookyourtravel' ),
			'add_new'             => __( 'New Cabin type', 'bookyourtravel' ),
			'edit_item'           => __( 'Edit Cabin type', 'bookyourtravel' ),
			'update_item'         => __( 'Update Cabin type', 'bookyourtravel' ),
			'search_items'        => __( 'Search Cabin types', 'bookyourtravel' ),
			'not_found'           => __( 'No Cabin types found', 'bookyourtravel' ),
			'not_found_in_trash'  => __( 'No Cabin types found in Trash', 'bookyourtravel' ),
		);
		$args = array(
			'label'               => __( 'cabin type', 'bookyourtravel' ),
			'description'         => __( 'Cabin type information pages', 'bookyourtravel' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'author' ),
			'taxonomies'          => array( ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=cruise',
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_icon'           => '',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'rewrite' => false,
		);
		register_post_type( 'cabin_type', $args );	
	}
	
	function list_cabin_types( $author_id = null, $statuses = array('publish'), $cruise_id = null ) {

		$args = array(
		   'post_type' => 'cabin_type',
		   'post_status' => $statuses,
		   'posts_per_page' => -1,
		   'suppress_filters' => 0,
		   'orderby' => 'title',
		   'order' => 'ASC'
		);

		if (isset($author_id) && $author_id > 0) {
			$args['author'] = intval($author_id);
		}
		
		$meta_query = array('relation' => 'AND');

		if (isset($cruise_id) && $cruise_id > 0) {
			$meta_query[] = array(
				'key'       => 'cabin_type_cruise_post_ids',
				'value'     => serialize((string)$cruise_id),
				'compare'   => 'LIKE'
			);	
		}
		
		$args['meta_query'] = $meta_query;
		
		$query = new WP_Query($args);

		return $query;
	}
}

global $byt_cabin_types_post_type;
// store the instance in a variable to be retrieved later and call init
$byt_cabin_types_post_type = BYT_Cabin_Types_Post_Type::get_instance();
$byt_cabin_types_post_type->init();