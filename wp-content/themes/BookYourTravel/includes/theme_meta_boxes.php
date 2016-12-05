<?php

class BYT_Theme_Meta_Boxes extends BYT_BaseSingleton {

	private $enabled_frontend_content_types;
	private $enable_accommodations;
	
	private $user_register_custom_meta_fields;
	private $user_register_meta_box;

	private $frontend_submit_custom_meta_fields;
	private $frontend_submit_meta_box;
	
	private $page_sidebars_custom_meta_fields;
	private $page_sidebars_meta_box;
	
	protected function __construct() {
	
		global $byt_theme_globals;
		
		$this->enable_accommodations = $byt_theme_globals->enable_accommodations();	
		$this->enabled_frontend_content_types = array();

        // our parent class might
        // contain shared code in its constructor
        parent::__construct();
    }

    public function init() {
	
		add_action( 'admin_init', array($this, 'pages_meta_box_admin_init' ) );	
    }
	
	function pages_meta_box_admin_init() {	

		if ($this->enable_accommodations) {
			$this->enabled_frontend_content_types[] = array('value' => 'accommodation', 'label' => __('Accommodation', 'bookyourtravel'));
			$this->enabled_frontend_content_types[] = array('value' => 'room_type', 'label' => __('Room type', 'bookyourtravel'));
			$this->enabled_frontend_content_types[] = array('value' => 'vacancy', 'label' => __('Vacancy', 'bookyourtravel'));
		}
		
		$page_sidebars = array();	
		$page_sidebars[] = array('value' => '', 'label' => __('No sidebar', 'bookyourtravel'));
		$page_sidebars[] = array('value' => 'left', 'label' => __('Left sidebar', 'bookyourtravel'));
		$page_sidebars[] = array('value' => 'right', 'label' => __('Right sidebar', 'bookyourtravel'));
		$page_sidebars[] = array('value' => 'both', 'label' => __('Left and right sidebars', 'bookyourtravel'));
		
		$this->page_sidebars_custom_meta_fields = array(
			array( // Taxonomy Select box
				'label'	=> __('Select sidebar positioning', 'bookyourtravel'), // <label>
				// the description is created in the callback function with a link to Manage the taxonomy terms
				'id'	=> 'page_sidebar_positioning', // field id and name, needs to be the exact name of the taxonomy
				'type'	=> 'select', // type of field
				'options' => $page_sidebars
			)
		);
		
		$this->user_register_custom_meta_fields = array(
			array( // Post ID select box
				'label'	=> __('Users can front-end submit?', 'bookyourtravel'), // <label>
				'desc'	=> __('Check this box if users registering through this form can use the frontend submit pages to submit content.', 'bookyourtravel'), // description
				'id'	=> 'user_register_can_frontend_submit', // field id and name
				'type'	=> 'checkbox', // type of field
			)
		);
				
		$this->frontend_submit_custom_meta_fields = array(
			array( // Taxonomy Select box
				'label'	=> __('Content type', 'bookyourtravel'), // <label>
				// the description is created in the callback function with a link to Manage the taxonomy terms
				'id'	=> 'frontend_submit_content_type', // field id and name, needs to be the exact name of the taxonomy
				'type'	=> 'select', // type of field
				'options' => $this->enabled_frontend_content_types
			)
		);
	
		$this->user_register_meta_box = new custom_add_meta_box( 'user_register_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->user_register_custom_meta_fields, 'page' );		
		remove_action( 'add_meta_boxes', array( $this->user_register_meta_box, 'add_box' ) );
		add_action('add_meta_boxes', array( $this, 'user_register_add_meta_boxes') );
	
		$this->frontend_submit_meta_box = new custom_add_meta_box( 'frontend_submit_custom_meta_fields', __('Extra information', 'bookyourtravel'), $this->frontend_submit_custom_meta_fields, 'page' );		
		remove_action( 'add_meta_boxes', array( $this->frontend_submit_meta_box, 'add_box' ) );
		add_action('add_meta_boxes', array( $this, 'frontend_submit_add_meta_boxes' ) );

		$this->page_sidebars_meta_box = new custom_add_meta_box( 'page_sidebars_custom_meta_fields', __('Sidebar selection', 'bookyourtravel'), $this->page_sidebars_custom_meta_fields, 'page' );		
		remove_action( 'add_meta_boxes', array( $this->page_sidebars_meta_box, 'add_box' ) );
		add_action('add_meta_boxes', array( $this, 'page_sidebar_add_meta_boxes' ) );		
	}
		
	function page_sidebar_add_meta_boxes() {
		global $post;
		$template_file = get_post_meta($post->ID,'_wp_page_template',true);
		if ($template_file != 'page-contact.php' && 
			$template_file != 'page-user-register.php' && 
			$template_file != 'page-user-login.php' && 
			$template_file != 'page-user-forgot-pass.php' &&
			$template_file != 'page-contact-form-7.php') {
			add_meta_box( $this->page_sidebars_meta_box->id, $this->page_sidebars_meta_box->title, array( $this->page_sidebars_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
		}
	}
		
	function user_register_add_meta_boxes() {
		global $post;
		$template_file = get_post_meta($post->ID,'_wp_page_template',true);
		if ($template_file == 'page-user-register.php') {
			add_meta_box( $this->user_register_meta_box->id, $this->user_register_meta_box->title, array( $this->user_register_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
		}
	}
		
	function frontend_submit_add_meta_boxes() {
		global $post;
		$template_file = get_post_meta($post->ID,'_wp_page_template',true);
		if ($template_file == 'page-user-submit-content.php') {
			add_meta_box( $this->frontend_submit_meta_box->id, $this->frontend_submit_meta_box->title, array( $this->frontend_submit_meta_box, 'meta_box_callback' ), 'page', 'normal', 'high' );
		}
	}
	
}

global $byt_theme_meta_boxes;
// store the instance in a variable to be retrieved later and call init
$byt_theme_meta_boxes = BYT_Theme_Meta_Boxes::get_instance();
$byt_theme_meta_boxes->init();