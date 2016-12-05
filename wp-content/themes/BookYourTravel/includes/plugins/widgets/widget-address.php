<?php

/*-----------------------------------------------------------------------------------

	Plugin Name: BYT Company Address

-----------------------------------------------------------------------------------*/


// Add function to widgets_init that'll load our widget.
add_action( 'widgets_init', 'byt_address_widgets' );

// Register widget.
function byt_address_widgets() {
	register_widget( 'byt_Address_Widget' );
}

// Widget class.
class byt_address_widget extends WP_Widget {


/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/
	
	function __construct() {
	
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'byt_address_widget', 'description' => __('BookYourTravel: Address Widget', 'bookyourtravel') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 550, 'id_base' => 'byt_address_widget' );

		/* Create the widget. */
		parent::__construct( 'byt_address_widget', __('BookYourTravel: Address Widget', 'bookyourtravel'), $widget_ops, $control_ops );
	}


/*-----------------------------------------------------------------------------------*/
/*	Display Widget
/*-----------------------------------------------------------------------------------*/
	
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$company_name = $instance['company_name'];
		$company_address = $instance['company_address'];
		$company_phone = $instance['company_phone'];
		$company_email = $instance['company_email'];	

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display Widget */
		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		?>
			<article class="byt_address_widget one-fourth">
				<h3><?php echo $company_name; ?></h3>
				<p><?php echo $company_address; ?></p>
				<p><em>P:</em> <?php _e('24/7 customer support', 'bookyourtravel'); ?>: <?php echo $company_phone; ?></p>
				<p><em>E:</em> <a href="#" title="<?php echo esc_attr( $company_email ); ?>"><?php echo $company_email; ?></a></p>
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
		$instance['company_name'] = strip_tags( $new_instance['company_name'] );
		$instance['company_address'] = strip_tags( $new_instance['company_address'] );
		$instance['company_phone'] = strip_tags( $new_instance['company_phone'] );
		$instance['company_email'] = strip_tags( $new_instance['company_email'] );

		return $instance;
	}
	

/*-----------------------------------------------------------------------------------*/
/*	Widget Settings
/*-----------------------------------------------------------------------------------*/
	 
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
		'title' => '',
		'company_name' => 'Book Your Travel LLC',
		'company_address' => '1400 Pennsylvania Ave. Washington, DC',
		'company_phone' => '1-555-555-5555',
		'company_email' => 'info@bookyourtravel.com'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e('Title:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'company_name' ) ); ?>"><?php _e('Company name:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'company_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'company_name' ) ); ?>" value="<?php echo esc_attr( $instance['company_name']); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'company_address' ) ); ?>"><?php _e('Company address:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'company_address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'company_address' ) ); ?>" value="<?php echo esc_attr( $instance['company_address'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'company_phone' ) ); ?>"><?php _e('Company phone:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'company_phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'company_phone' ) ); ?>" value="<?php echo esc_attr( $instance['company_phone'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'company_email' ) ); ?>"><?php _e('Company email:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'company_email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'company_email' ) ); ?>" value="<?php echo esc_attr( $instance['company_email'] ); ?>" />
		</p>		
		
	<?php
	}
}
?>