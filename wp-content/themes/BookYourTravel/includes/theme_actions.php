<?php

class BYT_Theme_Actions extends BYT_BaseSingleton {
	
	protected function __construct() {
	
        // our parent class might contain shared code in its constructor
        parent::__construct();
		
    }

    public function init() {
	
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts_styles' ) );
		
		$byt_needs_update = get_option('_byt_needs_update', 0);
		if ($byt_needs_update) {	
			add_action( 'admin_notices', array( $this, 'byt_admin_notices' ) );
		}	

		add_action( 'register_form', array( $this, 'password_register_fields'), 10, 1 );
		add_action( 'login_form_login', array( $this, 'disable_wp_login') );
	}

	/**
	 * Add password fields to wordpress registration form if option for users to set their own password is enabled in Theme settings.
	 */
	function password_register_fields(){

		global $byt_theme_globals;
		
		$override_wp_login = $byt_theme_globals->override_wp_login();
		$let_users_set_pass = $byt_theme_globals->let_users_set_pass();
		
		if ($let_users_set_pass)
			echo '<div class="row twins">';
			
		if ($let_users_set_pass) {
	?>
		<div class="f-item">
			<label for="password"><?php _e('Password', 'bookyourtravel'); ?></label>
			<input id="password" class="input" type="password" tabindex="30" size="25" value="" name="password" />
		</div>
		<div class="f-item">
			<label for="repeat_password"><?php _e('Repeat password', 'bookyourtravel'); ?></label>
			<input id="repeat_password" class="input" type="password" tabindex="40" size="25" value="" name="repeat_password" />
		</div>
	<?php
		}
		
		if ($let_users_set_pass)
			echo '</div>';
	}

	/**
	 * Disable WP login if option enabled in Theme settings
	 */
	function disable_wp_login(){
		global $byt_theme_globals;
		
		$login_page_url = $byt_theme_globals->get_login_page_url();
		$override_wp_login = $byt_theme_globals->override_wp_login();
		$redirect_to_after_logout_url = $byt_theme_globals->get_redirect_to_after_logout_url();
		
		if ($override_wp_login) {				
			if (!empty($login_page_url) && !empty($redirect_to_after_logout_url)) {
				if( isset( $_GET['loggedout'] ) ){
					wp_redirect( $redirect_to_after_logout_url );
					exit;
				} else{
					wp_redirect( $login_page_url );
					exit;
				}
			}
		}
	}
	
	function byt_admin_notices() {
	
		if (is_super_admin()) {
			$screen = get_current_screen();
		
			if ($screen->id != 'appearance_page_options-framework') {
				$byt_version_before_update = get_option('_byt_version_before_update', 0);
				global $byt_installed_version;
			?>
			<div id="message" class="updated">
				<p><strong><?php _e( 'Your Book Your Travel database needs an upgrade!', 'bookyourtravel'); ?></strong></p>
				<p><?php echo sprintf(__('Your current database version is <strong>%s</strong>, while the current theme version is <strong>%s</strong>.', 'bookyourtravel'), $byt_version_before_update, $byt_installed_version); ?></p>
				<p><?php _e( 'Please click the button below to go to the upgrade screen.', 'bookyourtravel' ); ?></p>
				<p class="submit"><a href="<?php echo esc_url( admin_url( 'themes.php?page=options-framework#options-group-14' ) ); ?>" class="button-primary"><?php _e( 'Go To Upgrade Screen', 'woocommerce' ); ?></a></p>
			</div>
			<?php
			}
		}
	}
		
	 /**
	 * Sets up theme defaults and registers the various WordPress features that
	 * Book Your Travel supports.
	 *
	 * @uses load_theme_textdomain() For translation/localization support.
	 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
	 * 	custom background, and post formats.
	 * @uses register_nav_menu() To add support for navigation menus.
	 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
	 *
	 * @since Book Your Travel 1.0
	 */
	function setup() {
		/*
		 * Book Your Travel available for translation.
		 *
		 * Translations can be added to the /languages/ directory.
		 * If you're building a theme based on Book Your Travel, use a find and replace
		 * to change 'bookyourtravel' to the name of your theme in all the template files.
		 */

		load_theme_textdomain( 'bookyourtravel', get_template_directory() . '/languages' );	
		
		// This theme uses wp_nav_menu() in three locations.
		register_nav_menus( array(
			'primary-menu' => __( 'Primary Menu', 'bookyourtravel' ),
			'footer-menu' => __( 'Footer Menu', 'bookyourtravel' )
		) );	
		
		// This theme uses a custom image size for featured images, displayed on "standard" posts.
		add_theme_support( 'post-thumbnails' );
		
		// This theme is woocommerce compatible
		add_theme_support( 'woocommerce' );
		
		add_theme_support( 'automatic-feed-links' );
		
		if ( ! isset( $content_width ) ) {
			$content_width = 815;
		}
		
		set_post_thumbnail_size( 200, 200, true );
		add_image_size( 'related', 180, 120, true ); //related
		add_image_size( 'featured', 815, 459, true ); //Featured
				
		//Left Sidebar Widget area
		register_sidebar(array(
			'name'=> __('Left Sidebar', 'bookyourtravel'),
			'id'=>'left',
			'description' => __('This Widget area is used for the left sidebar', 'bookyourtravel'),
			'before_widget' => '<li class="widget widget-sidebar">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		// Right Sidebar Widget area
		register_sidebar(array(
			'name'=> __('Right Sidebar', 'bookyourtravel'),
			'id'=>'right',
			'description' => __('This Widget area is used for the right sidebar', 'bookyourtravel'),
			'before_widget' => '<li class="widget widget-sidebar">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		// Right Sidebar Widget area
		register_sidebar(array(
			'name'=> __('Right Accommodation Sidebar', 'bookyourtravel'),
			'id'=>'right-accommodation',
			'description' => __('This Widget area is used for the right sidebar for single accommodations', 'bookyourtravel'),
			'before_widget' => '<li class="widget widget-sidebar">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		// Right Sidebar Widget area
		register_sidebar(array(
			'name'=> __('Right Tour Sidebar', 'bookyourtravel'),
			'id'=>'right-tour',
			'description' => __('This Widget area is used for the right sidebar for single tours', 'bookyourtravel'),
			'before_widget' => '<li class="widget widget-sidebar">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		// Right Sidebar Widget area
		register_sidebar(array(
			'name'=> __('Right Cruise Sidebar', 'bookyourtravel'),
			'id'=>'right-cruise',
			'description' => __('This Widget area is used for the right sidebar for single cruises', 'bookyourtravel'),
			'before_widget' => '<li class="widget widget-sidebar">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		// Right Sidebar Widget area
		register_sidebar(array(
			'name'=> __('Right Car Rental Sidebar', 'bookyourtravel'),
			'id'=>'right-car_rental',
			'description' => __('This Widget area is used for the right sidebar for single car rentals', 'bookyourtravel'),
			'before_widget' => '<li class="widget widget-sidebar">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		// Header Sidebar Widget area
		register_sidebar(array(
			'name'=> __('Header Sidebar', 'bookyourtravel'),
			'id'=>'header',
			'description' => __('This Widget area is used for the header area (usually for purposes of displaying WPML language switcher widget)', 'bookyourtravel'),
			'before_widget' => '',
			'after_widget' => '',
			'class'	=> 'lang-nav',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		// Under Header Sidebar Widget area
		register_sidebar(array(
			'name'=> __('Under Header Sidebar', 'bookyourtravel'),
			'id'=>'under-header',
			'description' => __('This Widget area is placed under the website header', 'bookyourtravel'),
			'before_widget' => '<li class="widget widget-sidebar">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		// Under Header Sidebar Widget area
		register_sidebar(array(
			'name'=> __('Above Footer Sidebar', 'bookyourtravel'),
			'id'=>'above-footer',
			'description' => __('This Widget area is placed above the website footer', 'bookyourtravel'),
			'before_widget' => '<li class="widget widget-sidebar">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		
		// Footer Sidebar Widget area
		register_sidebar(array(
			'name'=> __('Footer Sidebar', 'bookyourtravel'),
			'id'=>'footer',
			'description' => __('This Widget area is used for the footer area', 'bookyourtravel'),
			'before_widget' => '<li class="widget widget-sidebar">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		// Home Footer Sidebar Widget area
		register_sidebar(array(
			'name'=> __('Home Footer Widget Area', 'bookyourtravel'),
			'id'=>'home-footer',
			'description' => __('This Widget area is used for the home page footer area above the regular footer', 'bookyourtravel'),
			'before_widget' => '<li class="widget widget-sidebar">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		register_sidebar(array(
			'name'=> __('Home Content Widget Area', 'bookyourtravel'),
			'id'=>'home-content',
			'description' => __('This Widget area is used for the home page main content area', 'bookyourtravel'),
			'before_widget' => '<li class="widget widget-sidebar">',
			'after_widget' => '</li>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		// create new frontend submit role custom to BYT if it's not already created
		$frontend_submit_role = get_role(BOOKYOURTRAVEL_FRONTEND_SUBMIT_ROLE);
		if ($frontend_submit_role == null) {
			$frontend_submit_role = add_role(
				BOOKYOURTRAVEL_FRONTEND_SUBMIT_ROLE,
				__( 'BYT Frontend Submit Role', 'bookyourtravel' ),
				array(
					'read'         => true,  // true allows this capability
				)
			);
		}
		
		$pending_role = add_role(
			'pending',
			__( 'Pending activation', 'bookyourtravel' ),
			array()
		);
		
	}
	
	/**
	 * Enqueues scripts and styles for front-end.
	 *
	 * @since Book Your Travel 1.0
	 */
	function enqueue_scripts_styles() {
	
		global $wp_styles, $byt_theme_globals;
		
		$language_code = $byt_theme_globals->get_current_language_code();

		/*
		 * Adds JavaScript to pages with the comment form to support
		 * sites with threaded comments (when in use).
		 */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );

		/*
		 * Adds JavaScript for various theme features
		 */
		 
		wp_enqueue_script('jquery');

		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-datepicker');
		
		if (BYT_Theme_Utils::does_file_exist('/js/i18n/datepicker-' . $language_code . '.js')) {
			wp_register_script(	'bookyourtravel-datepicker-' . $language_code, BYT_Theme_Utils::get_file_uri('/js/i18n/datepicker-' . $language_code . '.js'), array('jquery', 'jquery-ui-datepicker'), '1.0',true);
			wp_enqueue_script( 'bookyourtravel-datepicker-' . $language_code );
		}
		
		wp_enqueue_script('jquery-effects-core');
		
		wp_enqueue_script( 'bookyourtravel-jquery-validate', BYT_Theme_Utils::get_file_uri ('/js/jquery.validate.min.js'), array('jquery'), '1.0', true );
		wp_enqueue_script( 'bookyourtravel-extras-jquery-validate', BYT_Theme_Utils::get_file_uri ('/js/extras.jquery.validate.js'), array('bookyourtravel-jquery-validate'), '1.0', true );
		
		wp_enqueue_script( 'bookyourtravel-jquery-prettyPhoto', BYT_Theme_Utils::get_file_uri ('/js/jquery.prettyPhoto.js'), array('jquery'), '1.0', true );
		wp_enqueue_script( 'bookyourtravel-jquery-raty', BYT_Theme_Utils::get_file_uri ('/js/jquery.raty.min.js'), array('jquery'), '1.0', true );
		wp_enqueue_script( 'bookyourtravel-jquery-uniform', BYT_Theme_Utils::get_file_uri ('/js/jquery.uniform.min.js'), array('jquery'), '1.0', true );
		wp_enqueue_script( 'bookyourtravel-mediaqueries', BYT_Theme_Utils::get_file_uri ('/js/respond.js'), array('jquery'), '1.0', true );
		wp_enqueue_script( 'bookyourtravel-selectnav', BYT_Theme_Utils::get_file_uri ('/js/selectnav.js'), array('jquery', 'bookyourtravel-jquery-uniform'), '1.0', true );
		wp_enqueue_script( 'bookyourtravel-scripts', BYT_Theme_Utils::get_file_uri ('/js/scripts.js'), array('jquery', 'bookyourtravel-selectnav', 'bookyourtravel-jquery-uniform'), '1.0', true );
		
		$page_object = get_queried_object();
		$page_id     = get_queried_object_id();

		if (is_single()) {
		
			wp_enqueue_script( 'bookyourtravel-jquery-lightSlider', BYT_Theme_Utils::get_file_uri ('/includes/plugins/lightSlider/js/jquery.lightSlider.js'), 'jquery', '1.0', true	);
			wp_enqueue_style( 'bookyourtravel-lightSlider-style', BYT_Theme_Utils::get_file_uri('/includes/plugins/lightSlider/css/lightSlider.css') );
			
			wp_enqueue_script( 'bookyourtravel-jquery-responsive-tables', BYT_Theme_Utils::get_file_uri ('/js/responsive-tables.js'), 'jquery', '1.0', true	);
			wp_enqueue_style( 'bookyourtravel-responsive-tables-style', BYT_Theme_Utils::get_file_uri('/css/responsive-tables.css') );
		}
		
		if (is_page()) {
			$template_file = get_post_meta($page_id,'_wp_page_template',true);
			if ($template_file == 'page-user-account.php') {
				wp_enqueue_script( 'bookyourtravel-user-account', BYT_Theme_Utils::get_file_uri ('/js/account.js'), 'jquery', '1.0', true );
			} elseif ($template_file == 'page-user-submit-content.php') {
				wp_enqueue_script( 'bookyourtravel-frontend-submit', BYT_Theme_Utils::get_file_uri ('/includes/plugins/frontend-submit/frontend-submit.js'), array( 'jquery', 'bookyourtravel-jquery-validate' ), '1.0', true );
			}
		}
		
		if (is_single() && get_post_type() == 'accommodation') {
		
			wp_enqueue_script( 'bookyourtravel-google-maps', '//maps.google.com/maps/api/js?sensor=false', 'jquery', '1.0', true	);
			wp_enqueue_script( 'bookyourtravel-infobox', BYT_Theme_Utils::get_file_uri ('/js/infobox.js'),'jquery', '1.0', true );
			wp_enqueue_script( 'bookyourtravel-tablesorter', BYT_Theme_Utils::get_file_uri ('/js/jquery.tablesorter.min.js'), 'jquery','1.0', true );
			wp_enqueue_script( 'bookyourtravel-accommodations', BYT_Theme_Utils::get_file_uri ('/js/accommodations.js'), array('jquery', 'bookyourtravel-scripts'), '1.0', true );
			wp_enqueue_script( 'bookyourtravel-reviews', BYT_Theme_Utils::get_file_uri ('/js/reviews.js'), 'jquery', '1.0', true );
			wp_enqueue_script( 'bookyourtravel-inquiry', BYT_Theme_Utils::get_file_uri ('/js/inquiry.js'), 'jquery', '1.0', true );
			
		} else if (is_single() && get_post_type() == 'location') {	
			
			wp_enqueue_script( 'bookyourtravel-locations', BYT_Theme_Utils::get_file_uri ('/js/locations.js'), 'jquery', '1.0', true );
			
		} else if (is_single() && get_post_type() == 'tour') {

			wp_enqueue_script( 'bookyourtravel-google-maps', '//maps.google.com/maps/api/js?sensor=false', 'jquery',	'1.0', true );
			wp_enqueue_script( 'bookyourtravel-tours', BYT_Theme_Utils::get_file_uri ('/js/tours.js'),  array('jquery', 'bookyourtravel-scripts'), '1.0', true );
			wp_enqueue_script( 'bookyourtravel-reviews', BYT_Theme_Utils::get_file_uri ('/js/reviews.js'), 'jquery', '1.0', true );
			wp_enqueue_script( 'bookyourtravel-inquiry', BYT_Theme_Utils::get_file_uri ('/js/inquiry.js'), 'jquery', '1.0', true );
					
		} else if (is_single() && get_post_type() == 'cruise') {
			
			wp_enqueue_script( 'bookyourtravel-google-maps', '//maps.google.com/maps/api/js?sensor=false', 'jquery',	'1.0', true );
			wp_enqueue_script( 'bookyourtravel-cruises', BYT_Theme_Utils::get_file_uri ('/js/cruises.js'),  array('jquery', 'bookyourtravel-scripts'), '1.0', true );
			wp_enqueue_script( 'bookyourtravel-reviews', BYT_Theme_Utils::get_file_uri ('/js/reviews.js'), 'jquery', '1.0', true );
			wp_enqueue_script( 'bookyourtravel-inquiry', BYT_Theme_Utils::get_file_uri ('/js/inquiry.js'), 'jquery', '1.0', true );
			
		} else if (is_single() && get_post_type() == 'car_rental') {	
			
			wp_enqueue_script( 'bookyourtravel-car_rentals', BYT_Theme_Utils::get_file_uri ('/js/car_rentals.js'),  array('jquery', 'bookyourtravel-scripts'), '1.0', true );
			wp_enqueue_script( 'bookyourtravel-inquiry', BYT_Theme_Utils::get_file_uri ('/js/inquiry.js'), 'jquery', '1.0', true );
			
		}

		$ajaxurl = admin_url( 'admin-ajax.php' );
	
		global $sitepress;
		if ($sitepress) {
			$lang = $sitepress->get_current_language();
			$ajaxurl = admin_url( 'admin-ajax.php?lang=' . $lang );
		}
		
		wp_localize_script( 'bookyourtravel-scripts', 'BYTAjax', array( 
		   'ajaxurl' => $ajaxurl,
		   'nonce'   => wp_create_nonce('byt-ajax-nonce') 
		) );

		/*
		 * Loads our main stylesheets.
		 */
		wp_enqueue_style( 'bookyourtravel-style-main', BYT_Theme_Utils::get_file_uri('/css/style.css'), array(), '1.0', "screen,projection,print");
		wp_enqueue_style( 'bookyourtravel-style', get_stylesheet_uri() );
		
		if ($byt_theme_globals->enable_rtl()) {
			wp_enqueue_style( 'bookyourtravel-style-rtl', BYT_Theme_Utils::get_file_uri('/css/style-rtl.css'), array(), '1.0', "screen,projection,print");
		}
		
		/*
		 * Load the color scheme sheet if set in set in options.
		 */	 
		$color_scheme_style_sheet = $byt_theme_globals->get_color_scheme_style_sheet();
		if (!empty($color_scheme_style_sheet)) {
			wp_enqueue_style('bookyourtravel-style-color',  BYT_Theme_Utils::get_file_uri('/css/' . $color_scheme_style_sheet . '.css'), array(), '1.0', "screen,projection,print");
		}
		
		wp_enqueue_style('bookyourtravel-style-pp',  BYT_Theme_Utils::get_file_uri('/css/prettyPhoto.css'), array(), '1.0', "screen");
		 
	}
	

	/**
	 * Enqueues scripts and styles for admin.
	 *
	 * @since Book Your Travel 1.0
	 */
	function enqueue_admin_scripts_styles() {

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-effects-core');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-droppable');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-selectable');
		wp_enqueue_script('jquery-ui-autocomplete');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-spinner');
		
		wp_register_script('byt-admin', BYT_Theme_Utils::get_file_uri('/includes/admin/admin.js'), false, '1.0.0');
		wp_enqueue_script('byt-admin');
		
		wp_enqueue_style('byt-admin-ui-css', BYT_Theme_Utils::get_file_uri('/css/jquery-ui.min.css'), false);
		
		wp_enqueue_style('byt-admin-css', BYT_Theme_Utils::get_file_uri('/css/admin-custom.css'), false);
	}

}

// store the instance in a variable to be retrieved later and call init
$byt_theme_actions = BYT_Theme_Actions::get_instance();
$byt_theme_actions->init();