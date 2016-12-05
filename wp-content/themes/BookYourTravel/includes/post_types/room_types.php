<?php

class BYT_Room_Types_Post_Type extends BYT_BaseSingleton {

	private $enable_accommodations;
	private $room_type_custom_meta_fields;
	
	protected function __construct() {
	
		global $byt_theme_globals;
		
		$this->enable_accommodations = $byt_theme_globals->enable_accommodations();
		
		if ($this->enable_accommodations) {
		
			$this->room_type_custom_meta_fields = array(
				array(
					'label'	=> __('Max adult count', 'bookyourtravel'),
					'desc'	=> __('How many adults are allowed in the room?', 'bookyourtravel'),
					'id'	=> 'room_type_max_count',
					'type'	=> 'slider',
					'min'	=> '1',
					'max'	=> '10',
					'step'	=> '1'
				),
				array(
					'label'	=> __('Max child count', 'bookyourtravel'),
					'desc'	=> __('How many children are allowed in the room?', 'bookyourtravel'),
					'id'	=> 'room_type_max_child_count',
					'type'	=> 'slider',
					'min'	=> '1',
					'max'	=> '10',
					'step'	=> '1'
				),
				array(
					'label'	=> __('Bed size', 'bookyourtravel'),
					'desc'	=> __('How big is/are the beds?', 'bookyourtravel'),
					'id'	=> 'room_type_bed_size',
					'type'	=> 'text'
				),
				array(
					'label'	=> __('Room size', 'bookyourtravel'),
					'desc'	=> __('What is the room size (m2)?', 'bookyourtravel'),
					'id'	=> 'room_type_room_size',
					'type'	=> 'text'
				),
				array(
					'label'	=> __('Room meta information', 'bookyourtravel'),
					'desc'	=> __('What other information applies to this specific room type?', 'bookyourtravel'),
					'id'	=> 'room_type_meta',
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
		
		if ($this->enable_accommodations) {		
		
			add_action( 'byt_initialize_post_types', array( $this, 'initialize_post_type' ));
			add_action( 'admin_init', array( $this, 'room_type_admin_init' ) );
		}	
		
	}

	function initialize_post_type() {

		$this->register_room_type_post_type();
	}
	
	function room_type_admin_init() {
		
		new custom_add_meta_box( 'room_type_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->room_type_custom_meta_fields, 'room_type' );
	}
	
	function register_room_type_post_type() {
		
		$labels = array(
			'name'                => _x( 'Room types', 'Post Type General Name', 'bookyourtravel' ),
			'singular_name'       => _x( 'Room type', 'Post Type Singular Name', 'bookyourtravel' ),
			'menu_name'           => __( 'Room types', 'bookyourtravel' ),
			'all_items'           => __( 'Room types', 'bookyourtravel' ),
			'view_item'           => __( 'View Room type', 'bookyourtravel' ),
			'add_new_item'        => __( 'Add New Room type', 'bookyourtravel' ),
			'add_new'             => __( 'New Room type', 'bookyourtravel' ),
			'edit_item'           => __( 'Edit Room type', 'bookyourtravel' ),
			'update_item'         => __( 'Update Room type', 'bookyourtravel' ),
			'search_items'        => __( 'Search room_types', 'bookyourtravel' ),
			'not_found'           => __( 'No room types found', 'bookyourtravel' ),
			'not_found_in_trash'  => __( 'No room types found in Trash', 'bookyourtravel' ),
		);
		$args = array(
			'label'               => __( 'room type', 'bookyourtravel' ),
			'description'         => __( 'Room type information pages', 'bookyourtravel' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'author' ),
			'taxonomies'          => array( ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=accommodation',
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
		register_post_type( 'room_type', $args );	
	}
	
	function list_room_types( $author_id = null, $statuses = array('publish') ) {

		$args = array(
		   'post_type' => 'room_type',
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
		
		$args['meta_query'] = $meta_query;
		
		$query = new WP_Query($args);

		return $query;
	}

}

global $byt_room_types_post_type;
// store the instance in a variable to be retrieved later and call init
$byt_room_types_post_type = BYT_Room_Types_Post_Type::get_instance();
$byt_room_types_post_type->init();