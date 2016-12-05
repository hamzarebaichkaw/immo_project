<?php

class BYT_Theme_Filters extends BYT_BaseSingleton {
	
	protected function __construct() {
	
        // our parent class might contain shared code in its constructor
        parent::__construct();
		
    }
	
    public function init() {
		add_filter( 'wp_title', array($this, 'custom_wp_title'), 10, 2 );
		add_filter('wp_dropdown_users', array( $this, 'custom_switch_post_author' ) );
	}
	
	function custom_switch_post_author($output)
	{
		global $post;
		
		if (isset($post)) {
			//global $post is available here, hence you can check for the post type here
			$users = get_users('role=byt_frontend_contributor');

			$output = "<select id=\"post_author_override\" name=\"post_author_override\" class=\"\">";

			//Leave the admin in the list
			$output .= "<option value=\"1\">Admin</option>";
			foreach($users as $user)
			{
				$sel = ($post->post_author == $user->ID)?"selected='selected'":'';
				$output .= '<option value="'.$user->ID.'"'.$sel.'>'.$user->user_login.'</option>';
			}
			$output .= "</select>";
		}

		return $output;
	}
	
	function custom_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$blog_name = get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$blog_name .= " $sep $site_description";
		}
		
		$title = $blog_name . " " . $title;

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );
		}

		return $title;
	}


}

// store the instance in a variable to be retrieved later and call init
$byt_theme_filters = BYT_Theme_Filters::get_instance();
$byt_theme_filters->init();