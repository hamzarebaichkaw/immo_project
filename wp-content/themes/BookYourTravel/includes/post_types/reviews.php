<?php

class BYT_Reviews_Post_Type extends BYT_BaseSingleton {

	private $enable_reviews;
	private $review_custom_meta_fields;
	
	protected function __construct() {
	
		global $byt_theme_globals;
		
		$this->enable_reviews = $byt_theme_globals->enable_reviews();
		
		if ($this->enable_reviews) {
					
			$this->review_custom_meta_fields = array(
			
				array(
					'label'	=> __('Likes', 'bookyourtravel'),
					'desc'	=> __('What the user likes about the accommodation', 'bookyourtravel'),
					'id'	=> 'review_likes',
					'type'	=> 'textarea'
				),
				array(
					'label'	=> __('Dislikes', 'bookyourtravel'),
					'desc'	=> __('What the user dislikes about the accommodation', 'bookyourtravel'),
					'id'	=> 'review_dislikes',
					'type'	=> 'textarea'
				),
				array( // Post ID select box
					'label'	=> __('Reviewed item', 'bookyourtravel'), // <label>
					'desc'	=> '', // description
					'id'	=>  'review_post_id', // field id and name
					'type'	=> 'post_select', // type of field
					'post_type' => array('accommodation', 'tour', 'cruise') // post types to display, options are prefixed with their post type
				),
				array('label'	=> __('Cleanliness', 'bookyourtravel'),	'desc'	=> __('Cleanliness rating', 'bookyourtravel'), 'id'	=> 'review_cleanliness', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> __('Comfort', 'bookyourtravel'),	'desc'	=> __('Comfort rating', 'bookyourtravel'), 'id'	=> 'review_comfort', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> __('Location', 'bookyourtravel'),	'desc'	=> __('Location rating', 'bookyourtravel'), 'id'	=> 'review_location', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> __('Staff', 'bookyourtravel'),	'desc'	=> __('Staff rating', 'bookyourtravel'), 'id'	=> 'review_staff', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> __('Services', 'bookyourtravel'),	'desc'	=> __('Services rating', 'bookyourtravel'), 'id'	=> 'review_services', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> __('Value for money', 'bookyourtravel'),	'desc'	=> __('Value for money rating', 'bookyourtravel'), 'id'	=> 'review_value_for_money', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> __('Sleep quality', 'bookyourtravel'),	'desc'	=> __('Sleep quality rating', 'bookyourtravel'), 'id'	=> 'review_sleep_quality', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> __('Overall', 'bookyourtravel'),	'desc'	=> __('Overall rating', 'bookyourtravel'), 'id'	=> 'review_overall', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> __('Accommodation', 'bookyourtravel'),	'desc'	=> __('Accommodation rating', 'bookyourtravel'), 'id'	=> 'review_accommodation', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> __('Transport', 'bookyourtravel'),	'desc'	=> __('Transport rating', 'bookyourtravel'), 'id'	=> 'review_transport', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> __('Meals', 'bookyourtravel'),	'desc'	=> __('Meals rating', 'bookyourtravel'), 'id'	=> 'review_meals', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> __('Guide', 'bookyourtravel'),	'desc'	=> __('Guide rating', 'bookyourtravel'), 'id'	=> 'review_guide', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' ),
				array('label'	=> __('Program accuracy', 'bookyourtravel'),	'desc'	=> __('Program accuracy rating', 'bookyourtravel'), 'id'	=> 'review_program_accuracy', 'type'	=> 'slider', 'min'	=> '1', 'max'	=> '10', 'step'	=> '1' )

			);
		
		}
		
        // our parent class might
        // contain shared code in its constructor
        parent::__construct();
    }

    public function init() {

		if ($this->enable_reviews) {
		
			add_action( 'byt_initialize_post_types', array( $this, 'initialize_post_type' ));
			add_action( 'admin_init', array( $this, 'review_admin_init' ) );
			
		}
		
	}
	
	function review_admin_init() {

		new custom_add_meta_box( 'review_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->review_custom_meta_fields, 'review' );
	}

	function initialize_post_type() {
		
		$this->register_reviews_post_type();
		
	}

	function register_reviews_post_type() {
		
		$labels = array(
			'name'                => _x( 'Reviews', 'Post Type General Name', 'bookyourtravel' ),
			'singular_name'       => _x( 'Review', 'Post Type Singular Name', 'bookyourtravel' ),
			'menu_name'           => __( 'Reviews', 'bookyourtravel' ),
			'all_items'           => __( 'Reviews', 'bookyourtravel' ),
			'view_item'           => __( 'View Review', 'bookyourtravel' ),
			'add_new_item'        => __( 'Add New Review', 'bookyourtravel' ),
			'add_new'             => __( 'New Review', 'bookyourtravel' ),
			'edit_item'           => __( 'Edit Review', 'bookyourtravel' ),
			'update_item'         => __( 'Update Review', 'bookyourtravel' ),
			'search_items'        => __( 'Search reviews', 'bookyourtravel' ),
			'not_found'           => __( 'No reviews found', 'bookyourtravel' ),
			'not_found_in_trash'  => __( 'No reviews found in Trash', 'bookyourtravel' ),
		);
		$args = array(
			'label'               => __( 'Review', 'bookyourtravel' ),
			'description'         => __( 'Review information pages', 'bookyourtravel' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'author' ),
			'taxonomies'          => array( ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'rewrite' => false,
		);
		
		register_post_type( 'review', $args );	
	}

	function recalculate_review_scores($post_type) {
		
		global $wpdb;
		
		$review_fields = $this->list_review_fields($post_type);
		$review_fields_count = count($review_fields);

		if ( $review_fields_count > 0 ) {
		
			$sql = "SELECT 	ID
					FROM 	$wpdb->posts as posts
					WHERE 	posts.post_type = '$post_type' AND 
							posts.post_status = 'publish'";
			
			$posts = $wpdb->get_results($sql);
			
			foreach ($posts as $post) {
			
				$reviewed_post_id = $post->ID;
				
				$sql = "SELECT ID
						FROM $wpdb->posts as posts
						INNER JOIN $wpdb->postmeta as meta ON posts.ID = meta.post_id AND meta.meta_key = 'review_post_id' AND meta.meta_value=%d 
						WHERE 	posts.post_type='review' AND 
								posts.post_status='publish' ";
						
				$reviews = $wpdb->get_results($wpdb->prepare($sql, $reviewed_post_id));

				$score_sum = 0;
				$review_count = 0;
				$review_score = 0;
				
				foreach ($reviews as $review) {		
				
					$review_id = $review->ID;
					
					foreach ($review_fields as $field) {
						$field_id = $field['id'];
						$field_value = get_post_meta($review_id, $field_id, true);
						$score_sum += intval($field_value);
					}
					
					$review_count += 1;
					
					$review_count .= " score_sum $score_sum ";
				}
				
				if ($review_count > 0 ) {
				
					$review_score = $score_sum / ($review_fields_count * 10 * $review_count);
					
					update_post_meta($reviewed_post_id, 'review_sum_score', $score_sum);
					update_post_meta($reviewed_post_id, 'review_score', $review_score);					
					update_post_meta($reviewed_post_id, 'review_count', $review_count);
				}
			}
		}
	}

	function list_user_reviews($user_id) {
		
		$args = array(
		   'post_type' => 'review',
		   'author' => $user_id,
		   'posts_per_page' => -1,
		);
		$query = new WP_Query($args);
		return $query;	
	}

	function list_reviews($post_id, $user_id = null) {

		$args = array(
		   'post_type' => 'review',
		   'post_status' => 'publish',
		   'posts_per_page' => -1,
		   'meta_query' => array(
			   array(
				   'key' => 'review_post_id',
				   'value' => $post_id,
				   'compare' => '=',
				   'type'    => 'CHAR',
			   ),
		   )
		);
		
		if ($user_id) {
			$args['author'] = $user_id;
		}

		return new WP_Query($args);
	}

	function get_reviews_count($post_id, $user_id = null) {
		$query = $this->list_reviews($post_id, $user_id);
		return $query->found_posts;
	}

	function list_review_fields($post_type, $visible_only = true) {

		global $default_tour_review_fields, $default_accommodation_review_fields, $default_cruise_review_fields;

		$default_review_fields = array();
		
		if ($post_type == 'accommodation')
			$default_review_fields = $default_accommodation_review_fields;
		elseif ($post_type == 'tour')
			$default_review_fields = $default_tour_review_fields;
		elseif ($post_type == 'cruise')
			$default_review_fields = $default_cruise_review_fields;

		$review_fields = of_get_option($post_type . '_review_fields');
		if (!is_array($review_fields) || count($review_fields) == 0)
			$review_fields = $default_review_fields;

		$fields = array();
		
		foreach ($review_fields as $review_field) {
			
			if (!$visible_only)
				$fields[] = $review_field;
			else {
				if (!isset($review_field['hide']) || !$review_field['hide'])
					$fields[] = $review_field;
			}
		}

		return $fields;
	}

	function sum_review_meta_values($post_id, $meta_key) {
		
		global $wpdb;

		$sql = $wpdb->prepare("SELECT sum(meta.meta_value)
			FROM $wpdb->postmeta as meta
			INNER JOIN $wpdb->postmeta as meta2 ON meta2.post_id = meta.post_id
			INNER JOIN $wpdb->posts as posts ON posts.ID = meta.post_id
			WHERE meta.meta_key = %s AND posts.post_type='review' AND posts.post_status='publish' AND meta2.meta_key = 'review_post_id' AND meta2.meta_value=%d", $meta_key, $post_id);
		
		return $wpdb->get_var($sql);	
	}

	function sum_user_review_meta_values($review_id, $user_id, $post_type) {
		
		global $wpdb, $default_tour_review_fields, $default_accommodation_review_fields;
		
		$review_fields = $this->list_review_fields($post_type);
		$review_fields_str = "";
		foreach ($review_fields as $field_key => $field) {
			$review_fields_str .= "'" . $field['id'] . "', ";
		}
		$review_fields_str = rtrim($review_fields_str, ', ');

		$sql = $wpdb->prepare("SELECT sum(meta.meta_value)
			FROM $wpdb->postmeta as meta
			INNER JOIN $wpdb->posts as posts ON posts.ID = meta.post_id
			WHERE meta.meta_key IN ($review_fields_str) AND posts.post_type='review' AND posts.post_status='publish' 
			AND posts.ID=%d AND posts.post_author=%d", $review_id, $user_id);

		return $wpdb->get_var($sql);	
	}
}

global $byt_reviews_post_type;
// store the instance in a variable to be retrieved later and call init
$byt_reviews_post_type = BYT_Reviews_Post_Type::get_instance();
$byt_reviews_post_type->init();