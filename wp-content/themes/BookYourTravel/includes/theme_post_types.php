<?php

require_once BYT_Theme_Utils::get_file_path('/includes/post_types/reviews.php');
require_once BYT_Theme_Utils::get_file_path('/includes/post_types/locations.php');
require_once BYT_Theme_Utils::get_file_path('/includes/post_types/facilities.php');
require_once BYT_Theme_Utils::get_file_path('/includes/post_types/room_types.php');
require_once BYT_Theme_Utils::get_file_path('/includes/post_types/accommodations.php');
require_once BYT_Theme_Utils::get_file_path('/includes/post_types/tours.php');
require_once BYT_Theme_Utils::get_file_path('/includes/post_types/cabin_types.php');
require_once BYT_Theme_Utils::get_file_path('/includes/post_types/cruises.php');
require_once BYT_Theme_Utils::get_file_path('/includes/post_types/car_rentals.php');
require_once BYT_Theme_Utils::get_file_path('/includes/post_types/posts.php');

class BYT_Theme_Post_Types extends BYT_BaseSingleton {

	private $user_account_custom_meta_fields;
	private $user_account_meta_box;
	private $user_content_list_custom_meta_fields;
	private $user_content_list_meta_box;

	private $enable_accommodations;

	protected function __construct() {
	
		global $byt_theme_globals;
		
		$this->enable_accommodations = $byt_theme_globals->enable_accommodations();
		
		if ($this->enable_accommodations) {
		
			$this->user_account_custom_meta_fields = array(
				array( // Post ID select box
					'label'	=> __('Is partner page?', 'bookyourtravel'), // <label>
					'desc'	=> __('If checked, will display partner (front end submit) pages and menus', 'bookyourtravel'), // description
					'id'	=> 'user_account_is_partner_page', // field id and name
					'type'	=> 'checkbox', // type of field
				)
			);
			
			$user_content_types = array();
			$user_content_types[] = array('value' => 'accommodation', 'label' => __('Accommodation', 'bookyourtravel'));
			$user_content_types[] = array('value' => 'vacancy', 'label' => __('Accommodation vacancy', 'bookyourtravel'));
			$user_content_types[] = array('value' => 'room_type', 'label' => __('Room type', 'bookyourtravel'));

			$this->user_content_list_custom_meta_fields = array(
				array( // Select box
					'label'	=> __('User content type', 'bookyourtravel'), // <label>
					// the description is created in the callback function with a link to Manage the taxonomy terms
					'id'	=> 'user_content_type', // field id and name, needs to be the exact name of the taxonomy
					'type'	=> 'select', // type of field
					'options' => $user_content_types
				),
			);
		}
	
        // our parent class might
        // contain shared code in its constructor
        parent::__construct();
    }

    public function init() {
		add_action( 'init', array($this, 'initialize_post_types' ) );
    }
	
	function initialize_post_types() {
		do_action('byt_initialize_post_types');
		
		if ($this->enable_accommodations) {	
		
			add_action( 'admin_init', array( $this, 'accommodation_admin_init' ) );
		}
	}
	
	function accommodation_admin_init() {
		
		$this->user_account_meta_box = new custom_add_meta_box( 'user_account_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->user_account_custom_meta_fields, 'page' );	
		remove_action( 'add_meta_boxes', array( $this->user_account_meta_box, 'add_box' ) );
		add_action('add_meta_boxes', array( $this, 'user_account_add_meta_boxes' ) );
		
		$this->user_content_list_meta_box = new custom_add_meta_box( 'user_content_list_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->user_content_list_custom_meta_fields, 'page' );	
		remove_action( 'add_meta_boxes', array( $this->user_content_list_meta_box, 'add_box' ) );
		add_action('add_meta_boxes', array( $this, 'user_content_list_add_meta_boxes' ) );

	}
	
	function user_account_add_meta_boxes() {
	
		global $post;
		$template_file = get_post_meta($post->ID,'_wp_page_template',true);
		if ($template_file == 'page-user-account.php') {
			add_meta_box( $this->user_account_meta_box->id, $this->user_account_meta_box->title, array( $this->user_account_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
		}
	}	
	
	function user_content_list_add_meta_boxes() {
	
		global $post;
		$template_file = get_post_meta($post->ID,'_wp_page_template',true);
		if ($template_file == 'page-user-content-list.php') {
			add_meta_box( $this->user_content_list_meta_box->id, $this->user_content_list_meta_box->title, array( $this->user_content_list_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
		}
	}	
}

// store the instance in a variable to be retrieved later and call init
$byt_theme_post_types = BYT_Theme_Post_Types::get_instance();
$byt_theme_post_types->init();