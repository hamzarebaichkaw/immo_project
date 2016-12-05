<?php

/*-----------------------------------------------------------------------------------

	Plugin Name: BYT Home Feature

-----------------------------------------------------------------------------------*/


// Add function to widgets_init that'll load our widget.
add_action( 'widgets_init', 'byt_home_feature_widgets' );

// Register widget.
function byt_home_feature_widgets() {
	register_widget( 'byt_Home_Feature_Widget' );
}

// Widget class.
class byt_Home_Feature_Widget extends WP_Widget {


/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/
	
	function __construct() {
	
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'byt_home_feature_widget', 'description' => __('BookYourTravel: Home Feature Widget', 'bookyourtravel') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 550, 'id_base' => 'byt_home_feature_widget' );

		/* Create the widget. */
		parent::__construct( 'byt_home_feature_widget', __('BookYourTravel: Home Feature Widget', 'bookyourtravel'), $widget_ops, $control_ops );
	}


/*-----------------------------------------------------------------------------------*/
/*	Display Widget
/*-----------------------------------------------------------------------------------*/
	
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$home_feature_text = $instance['home_feature_text'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display Widget */
		?>
			<article class="home_features clearfix">
				<h2><?php echo $title; ?></h2>
				<p><?php echo $home_feature_text; ?></p>

			</article>
		<?php

		/* After widget (defined by themes). */
		echo $after_widget;
	}


/*-----------------------------------------------------------------------------------*/
/*	Update Widget
/*-----------------------------------------------------------------------------------*/
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['home_feature_text'] = strip_tags( $new_instance['home_feature_text']);

		return $instance;
	}
	

/*-----------------------------------------------------------------------------------*/
/*	Widget Settings
/*-----------------------------------------------------------------------------------*/
	 
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
		'title' => __('Handpicked Accommodations', 'bookyourtravel'),
		'home_feature_text' => __('All Book Your Travel Accommodations fulfil strict selection criteria. Each accommodation is chosen individually and inclusion cannot be bought.', 'bookyourtravel'),
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' )); ?>"><?php _e('Title:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'home_feature_text' ) ); ?>"><?php _e('Feature text:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'home_feature_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'home_feature_text' ) ); ?>" value="<?php echo esc_attr( $instance['home_feature_text'] ); ?>" />
		</p>
		
	<?php
	}
}