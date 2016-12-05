<?php

class BYT_Facilities_Taxonomy extends BYT_BaseSingleton {
	
	private $enable_accommodations;
	private $enable_cruises;
	
	protected function __construct() {
	
		global $byt_theme_globals;
		$this->enable_accommodations = $byt_theme_globals->enable_accommodations();
		$this->enable_cruises = $byt_theme_globals->enable_cruises();

        // our parent class might
        // contain shared code in its constructor
        parent::__construct();
    }

    public function init() {
			
		add_action( 'byt_initialize_post_types', array( $this, 'initialize_taxonomy' ));
		add_action( 'admin_init', array($this, 'remove_unnecessary_meta_boxes') );

		if ($this->enable_accommodations || $this->enable_cruises) {
			add_filter('manage_edit-accommodation_columns', array( $this, 'manage_edit_accommodation_columns'), 10, 1);	
		}
		
	}

	function initialize_taxonomy() {
		$this->register_facility_taxonomy();
	}	

	function register_facility_taxonomy(){
	
		$labels = array(
				'name'              		 => _x( 'Facilities', 'taxonomy general name', 'bookyourtravel' ),
				'singular_name'     		 => _x( 'Facility', 'taxonomy singular name', 'bookyourtravel' ),
				'search_items'      		 => __( 'Search Facilities', 'bookyourtravel' ),
				'all_items'         		 => __( 'All Facilities', 'bookyourtravel' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'         		 => __( 'Edit Facility', 'bookyourtravel' ),
				'update_item'       		 => __( 'Update Facility', 'bookyourtravel' ),
				'add_new_item'      		 => __( 'Add New Facility', 'bookyourtravel' ),
				'new_item_name'     		 => __( 'New Facility Name', 'bookyourtravel' ),
				'separate_items_with_commas' => __( 'Separate facilities with commas', 'bookyourtravel' ),
				'add_or_remove_items'        => __( 'Add or remove facilities', 'bookyourtravel' ),
				'choose_from_most_used'      => __( 'Choose from the most used facilities', 'bookyourtravel' ),
				'not_found'                  => __( 'No facilities found.', 'bookyourtravel' ),
				'menu_name'         		 => __( 'Facilities', 'bookyourtravel' ),
			);
			
		$args = array(
				'hierarchical'      		 => false,
				'labels'            		 => $labels,
				'show_ui'           		 => true,
				'show_admin_column' 		 => true,
				'query_var'         		 => true,
				'update_count_callback' 	 => '_update_post_term_count',
				'rewrite'           		 => null
			);
		
		$types_for_facility = array();

		if ($this->enable_accommodations) {
			$types_for_facility[] = 'accommodation';
			$types_for_facility[] = 'room_type';
		}
		if ($this->enable_cruises) {
			$types_for_facility[] = 'cruise';
			$types_for_facility[] = 'cabin_type';
		}
		
		
		if (count($types_for_facility) > 0)
			register_taxonomy( 'facility', $types_for_facility, $args );
	}
	
	function manage_edit_accommodation_columns($columns) {
	
		unset($columns['taxonomy-facility']);

		return $columns;
	}

	function remove_unnecessary_meta_boxes() {
		
		if ($this->enable_accommodations) {
			remove_meta_box('tagsdiv-facility', 'accommodation', 'side');
			remove_meta_box('tagsdiv-facility', 'room_type', 'side');
		}
		if ($this->enable_cruises) {
			remove_meta_box('tagsdiv-facility', 'cruise', 'side');
			remove_meta_box('tagsdiv-facility', 'cabin_type', 'side');
		}
		
	}
}

global $byt_facilities_taxonomy;
// store the instance in a variable to be retrieved later and call init
$byt_facilities_taxonomy = BYT_Facilities_Taxonomy::get_instance();
$byt_facilities_taxonomy->init();