<?php

/**
 * PixReviews.
 * @package   PixReviewsPlugin
 * @author    Pixelgrade <contact@pixelgrade.com>
 * @license   GPL-2.0+
 * @link      http://pixelgrade.com
 * @copyright 2014 Pixelgrade
 */
class PixReviewsPlugin {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 * @since   1.0.0
	 * @const   string
	 */
	protected $version = '1.1.5';
	/**
	 * Unique identifier for your plugin.
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_slug = 'comments-ratings';

	/**
	 * Instance of this class.
	 * @since    1.0.0
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Path to the plugin.
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_basepath = null;
	protected $plugin_baseurl = null;

	protected static $config;

	public static $plugin_settings;

	protected static $localized = array();

	protected static $default_ratings = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 * @since     1.0.0
	 */
	protected function __construct() {
		$this->plugin_basepath = self::get_base_path();
		$this->plugin_baseurl  = self::get_base_url();
		self::$config          = self::get_config();
		self::$plugin_settings = get_option( 'pixreviews_settings' );


		// Load plugin text domain

		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . 'pixreviews.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'comment_form_logged_in_after', array( $this, 'output_review_fields' ) ); // Logged in
		add_action( 'comment_form_after_fields', array( $this, 'output_review_fields' ) ); // Guest

		add_action( 'comment_form_field_comment', array( $this, 'filter_comment_form' ) );
		add_action( 'comment_form_defaults', array( $this, 'filter_submit_comment_button' ) );


		add_action( 'comment_post', array( $this, 'save_comment' ) );
		add_action( 'comment_text', array( $this, 'display_rating' ) );

		// now the admin part
		add_filter( 'comment_edit_redirect', array( $this, 'save_comment_backend' ), 10, 2 );
		add_action( 'add_meta_boxes', array( $this, 'add_custom_backend_box' ) );

	}

	/**
	 * Return an instance of this class.
	 * @since     1.0.0
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

	}

	/**
	 * Fired when the plugin is deactivated.
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here
	}

	protected function set_rating_values() {
		$ratings = array(
			esc_html__( 'Terrible', 'comments-ratings' ),
			esc_html__( 'Poor', 'comments-ratings' ),
			esc_html__( 'Average', 'comments-ratings' ),
			esc_html__( 'Very Good', 'comments-ratings' ),
			esc_html__( 'Exceptional', 'comments-ratings' ),
		);

		return $ratings;
	}

	/**
	 * Settings page scripts
	 */
	function enqueue_admin_scripts( $hook_suffix ) {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
			wp_localize_script( $this->plugin_slug . '-admin-script', 'locals', array(
				'ajax_url' => admin_url( 'admin-ajax.php' )
			) );
		} elseif ( 'comment.php' === $hook_suffix || 'edit-comments.php' === $hook_suffix ) {
			wp_enqueue_script( 'jquery-raty', $this->plugin_baseurl . 'js/jquery.raty.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_style( 'jquery-raty-style', $this->plugin_baseurl . 'css/jquery.raty.css', array(), $this->version, false );
			wp_enqueue_script( 'reviews-scripts', $this->plugin_baseurl . 'js/reviews.js', array( 'jquery-raty' ), $this->version, true );

			wp_localize_script( 'reviews-scripts', 'pixreviews', array(
					'hints' => $this->set_rating_values(),
				)
			);
		}
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( ! $this->is_visible_on_this_post() ) {
			return;
		}
		// add assets here
		wp_enqueue_script( 'jquery-raty', $this->plugin_baseurl . 'js/jquery.raty.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_style( 'jquery-raty-style', $this->plugin_baseurl . 'css/jquery.raty.css', array(), $this->version, false );
		wp_enqueue_script( 'reviews-scripts', $this->plugin_baseurl . 'js/reviews.js', array( 'jquery-raty' ), $this->version, true );

		wp_localize_script( 'reviews-scripts', 'pixreviews', array(
				'hints' => $this->set_rating_values(),
			)
		);
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 */
	function add_plugin_admin_menu() {
		$this->plugin_screen_hook_suffix = add_options_page( esc_html__( 'Comments Ratings', 'comments-ratings' ), esc_html__( 'Comments Ratings', 'comments-ratings' ), 'edit_plugins', 'comments-ratings', array(
			$this,
			'display_plugin_admin_page'
		) );
	}

	/**
	 * Render the settings page for this plugin.
	 */
	function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 */
	function add_action_links( $links ) {
		return array_merge( array( 'settings' => '<a href="' . admin_url( 'options-general.php?page=pixreviews' ) . '">' . esc_html__( 'Settings', 'comments-ratings' ) . '</a>' ), $links );
	}

	function save_comment( $commentID ) {
		//some sanity check
		if ( ! is_numeric( $commentID ) ) {
			return;
		}

		//bail if shouldn't show ratings for the current post
		if ( ! $this->is_visible_on_this_post() ) {
			return;
		}

		// Save the rating
		if ( isset( $_POST['pixrating'] ) && is_numeric( $_POST['pixrating'] ) ) {
			update_comment_meta( $commentID, 'pixrating', $_POST['pixrating'], true );
		}

		//Save the rating title
		if ( isset( $_POST['pixrating_title'] ) && is_string( $_POST['pixrating_title'] ) ) {
			update_comment_meta( $commentID, 'pixrating_title', sanitize_text_field( $_POST['pixrating_title'] ), true );
		}
	}

	function display_rating( $comment ) {
		//bail if shouldn't show ratings for the current post
		if ( ! $this->is_visible_on_this_post() ) {
			return $comment;
		}

		//bail if we don't have a valid current comment ID
		if ( ! get_comment_ID() ) {
			return $comment;
		}

		//get the rating
		$rating          = get_comment_meta( get_comment_ID(), 'pixrating', true );
		//get the rating title
		$pixrating_title = get_comment_meta( get_comment_ID(), 'pixrating_title', true );

		//add the rating stars to the comment
		if ( ! empty( $rating ) ) {
			$comment = '<div class="review_rate" data-pixrating="' . $rating . '"></div>' . $comment;
		}

		//add the rating title
		if ( ! empty( $pixrating_title ) ) {
			$comment = '<h3 class="pixrating_title">' . $pixrating_title . '</h3>' . $comment;
		}

		return $comment;
	}

	function output_review_fields() {
		//bail if shouldn't show ratings for the current post
		if ( ! $this->is_visible_on_this_post() ) {
			return;
		} ?>

		<div id="add_comment_rating_wrap">
			<label for="add_post_rating"><?php echo $this->get_plugin_option( 'review_rating_label' ); ?></label>

			<div id="add_post_rating" data-pixrating="<?php echo esc_attr( $this->get_plugin_option( 'default_rating' ) ); ?>" data-assets_path="<?php echo esc_attr(  $this->plugin_baseurl . '/images' ); ?>"></div>
		</div>
		<p class="review-title-form">
			<label for="pixrating_title"><?php echo $this->get_plugin_option( 'review_title_label' ); ?></label>
			<input type='text' id='pixrating_title' name='pixrating_title' value="" placeholder="<?php echo esc_attr( $this->get_plugin_option( 'review_title_placeholder' ) ) ?>" size='25'/>
		</p>

		<?php
	}

	function filter_comment_form( $html ) {
		//bail if shouldn't show ratings for the current post
		if ( ! $this->is_visible_on_this_post() ) {
			return $html;
		}
		$label              = $this->get_plugin_option( 'review_label' );
		$review_placeholder = $this->get_plugin_option( 'review_placeholder' );

		return '<p class="comment-form-comment"><label for="comment">' . $label . '</label> <textarea id="comment" name="comment" cols="45" rows="8"  aria-required="true" required="required" placeholder="' . $review_placeholder . '"></textarea></p>';
	}

	function filter_submit_comment_button( $args ) {
		//bail if shouldn't show ratings for the current post
		if ( ! $this->is_visible_on_this_post() ) {
			return $args;
		}
		$label                = $this->get_plugin_option( 'review_submit_button' );
		$args['label_submit'] = $label;

		return $args;
	}

	/**
	 * Save Custom Comment Field
	 * This hook deals with the redirect after saving, we are only taking advantage of it
	 */
	function save_comment_backend( $location, $comment_id ) {
		// Not allowed, return regular value without updating meta
		if ( ! wp_verify_nonce( $_POST['noncename_wpse_82317'], plugin_basename( __FILE__ ) )
		     && ! isset( $_POST['pixrating_title'] ) && ! isset( $_POST['pixrating'] ) ) {
			return $location;
		}

		// Update meta
		update_comment_meta(
			$comment_id,
			'pixrating_title',
			sanitize_text_field( $_POST['pixrating_title'] )
		);

		// Save rating to the comment if we actually have a numeric value here - just to stay safe
		if ( intval( $_POST['pixrating'] ) ) {
			update_comment_meta( $comment_id, 'pixrating', intval( $_POST['pixrating'] ) );
		}

		// Return regular value after updating
		return $location;
	}

	/**
	 * Add Comment meta box
	 */
	function add_custom_backend_box() {
		add_meta_box(
			'section_id_wpse_82317',
			esc_html__( 'Review Fields', 'comments-ratings' ),
			array( $this, 'inner_custom_backend_box' ),
			'comment',
			'normal'
		);
	}

	/**
	 * Render meta box with Custom Field
	 */
	function inner_custom_backend_box( $comment ) {
		//some sanity check
		if ( empty( $comment ) ) {
			return;
		}

		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'noncename_wpse_82317' );

		$pixrating_title = get_comment_meta( $comment->comment_ID, 'pixrating_title', true );
		$current_rating  = get_comment_meta( $comment->comment_ID, 'pixrating', true ); ?>

		<fieldset>
			<label for="pixrating_title"><?php _e( 'Review Title', 'comments-ratings' ); ?></label>
			<input type='text' id='pixrating_title' name='pixrating_title' value="<?php echo esc_attr( $pixrating_title ) ?>" size='25'/>
		</fieldset>

		<?php // if there is a value, display it
		$data = '';
		if ( ! empty( $current_rating ) ) {
			$data .= 'data-pixrating="' . $current_rating . '"';
		} ?>

		<fieldset id="add_comment_rating_wrap">
			<?php ?>
			<label for="add_post_rating"><?php _e( 'Rating:', 'comments-ratings' ) ?></label>

			<div id="add_post_rating" <?php echo $data; ?> data-assets_path="<?php echo $this->plugin_baseurl . '/images'; ?>"></div>
		</fieldset>

	<?php }

	function get_average_rating( $post_id = null, $decimals = 2 ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$comments = get_comments( array(
			'post_id'  => $post_id,
			'meta_key' => 'pixrating',
			'status'   => 'approve'
		) );

		if ( empty( $comments ) ) {
			return false;
		}

		$total = 0;
		foreach ( $comments as $comment ) {
			$current_rating  = get_comment_meta( $comment->comment_ID, 'pixrating', true );
			$total = $total + (double) $current_rating;
		}

		$average = $total / count( $comments );

		return number_format( $average, $decimals );
	}

	function is_visible_on_this_post( $post_id = null) {
		$is_selective = $this->get_plugin_option( 'enable_selective_ratings' );

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		//if by some chance we fail to get a valid post ID then just return the default value - true
		if ( empty( $post_id) ) {
			return true;
		}

		if ( $is_selective ) {
			$post_types = $this->get_plugin_option( 'display_on_post_types' );
			$post_type  = get_post_type( $post_id );

			if ( $post_type && is_array( $post_types ) ) {
				return array_key_exists( $post_type, $post_types );
			}

			return false;
		}

		// by default the rating is visible everywhere
		return true;
	}

	protected static function get_config() {
		// @TODO maybe check this
		return include 'plugin-config.php';
	}

	/**
	 * Get an option's value from the config file
	 *
	 * @param $option
	 * @param null $default
	 *
	 * @return bool|null
	 */
	public static function get_config_option( $option, $default = null ) {

		if ( isset( self::$config[ $option ] ) ) {
			return self::$config[ $option ];
		} elseif ( $default !== null ) {
			return $default;
		}

		return false;
	}

	static function get_plugin_option( $option, $default = null ) {

		if ( isset( self::$plugin_settings[ $option ] ) ) {
			return self::$plugin_settings[ $option ];
		} elseif ( $default !== null ) {
			return $default;
		}

		return false;
	}

	static function get_base_path() {
		return plugin_dir_path( __FILE__ );
	}

	static function get_base_url() {
		return plugin_dir_url( __FILE__ );
	}
}