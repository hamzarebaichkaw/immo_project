<?php

/*-----------------------------------------------------------------------------------

	Plugin Name: BookYourTravel Accommodation List Widget

-----------------------------------------------------------------------------------*/

// Add function to widgets_init that'll load our widget.
add_action( 'widgets_init', 'byt_accommodation_lists_widgets' );

// Register widget.
function byt_accommodation_lists_widgets() {
	global $byt_theme_globals;
	if ($byt_theme_globals->enable_accommodations()) {
		register_widget( 'byt_Accommodation_List_Widget' );
	}
}

// Widget class.
class byt_Accommodation_List_Widget extends WP_Widget {

	/*-----------------------------------------------------------------------------------*/
	/*	Widget Setup
	/*-----------------------------------------------------------------------------------*/
	
	function __construct() {
	
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'byt_accommodation_lists_widget', 'description' => __('BookYourTravel: Accommodation List', 'bookyourtravel') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 260, 'height' => 400, 'id_base' => 'byt_accommodation_lists_widget' );

		/* Create the widget. */
		parent::__construct( 'byt_accommodation_lists_widget', __('BookYourTravel: Accommodation List', 'bookyourtravel'), $widget_ops, $control_ops );
	}


/*-----------------------------------------------------------------------------------*/
/*	Display Widget
/*-----------------------------------------------------------------------------------*/
	
	function widget( $args, $instance ) {
		
		global $sc_theme_globals, $byt_accommodations_post_type;
				
		extract( $args );
		
		$card_layout_classes = array(
			'full-width',
			'one-half',
			'one-third',
			'one-fourth',
			'one-fifth'
		);
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : __('Explore our latest accommodations', 'bookyourtravel') );
		
		$number_of_posts = isset($instance['number_of_posts']) ? (int)$instance['number_of_posts'] : 4;
		$sort_by = isset($instance['sort_by']) ? $instance['sort_by'] : 'title';
		$sort_descending = isset($instance['sort_by']) && $instance['sort_descending'] == '1';
		$order = $sort_descending ? 'DESC' : 'ASC';
		$posts_per_row = isset($instance['posts_per_row']) ? (int)$instance['posts_per_row'] : 4;
		$show_featured_only = isset($instance['show_featured_only']) && $instance['show_featured_only'] == '1';
		$accommodation_type_ids = isset($instance['accommodation_type_ids']) ? (array)$instance['accommodation_type_ids'] : array();
		$accommodation_tag_ids = isset($instance['accommodation_tag_ids']) ? (array)$instance['accommodation_tag_ids'] : array();
		
		global $display_mode;
		$display_mode = isset($instance['display_mode']) ? $instance['display_mode'] : 'card';

		echo $before_widget;
		
		if ($display_mode == 'card') {
		?><!--deals--><div class="deals clearfix full"><?php
		} else {
		?><!--deals--><ul class="small-list"><?php
		}
		
		/* Display Widget */
		
		$accommodation_results = $byt_accommodations_post_type->list_accommodations(1, $number_of_posts, $sort_by, $order, 0, $accommodation_type_ids, $accommodation_tag_ids, array(), $show_featured_only);
		
		if ($display_mode == 'card') { ?>
			<header class="s-title"><?php echo $before_title . $title . $after_title; ?></header><?php			
		} else {
			echo $before_title . $title . $after_title; 	
		}
		
		if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) {
			if ($display_mode == 'card') {
			?><div class="inner-wrap"><?php
			}
				foreach ($accommodation_results['results'] as $accommodation_result) {
					global $post;				
					$post = $accommodation_result;
					setup_postdata( $post ); 
					global $accommodation_class;
					if (isset($card_layout_classes[$posts_per_row - 1]))
						$accommodation_class = $card_layout_classes[$posts_per_row - 1];
					else
						$accommodation_class = 'one-fourth';					
					get_template_part('includes/parts/accommodation', 'item');
				}	
			if ($display_mode == 'card') {				
			?></div><?php
			}
		}
		if ($display_mode == 'card') {
		?></div><!--//deals--><?php
		} else {
		?></ul><!--//deals--><?php
		}
		
		/* After widget (defined by themes). */
		echo $after_widget;

		// set back to default since this is a global variable
		$display_mode = 'card';
	}
	

/*-----------------------------------------------------------------------------------*/
/*	Update Widget
/*-----------------------------------------------------------------------------------*/
	
	function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;

		/* Strip tags to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );		
		$instance['number_of_posts'] = strip_tags( $new_instance['number_of_posts']);
		$instance['sort_by'] = strip_tags( $new_instance['sort_by']);
		$instance['sort_descending'] = strip_tags( $new_instance['sort_descending']);
		$instance['display_mode'] = strip_tags( $new_instance['display_mode']);
		$instance['posts_per_row'] = strip_tags( $new_instance['posts_per_row']);
		$instance['show_featured_only'] = strip_tags( $new_instance['show_featured_only']);
		$instance['accommodation_type_ids'] = $new_instance['accommodation_type_ids'];
		$instance['accommodation_tag_ids'] = $new_instance['accommodation_tag_ids'];
		
		return $instance;
	}
	

/*-----------------------------------------------------------------------------------*/
/*	Widget Settings
/*-----------------------------------------------------------------------------------*/
	 
	function form( $instance ) {
			
		$cat_args = array( 
			'taxonomy'=>'accommodation_type', 
			'hide_empty'=>'0'
		);
		$accommodation_types = get_categories($cat_args);
		
		$cat_args = array( 
			'taxonomy'=>'acc_tag', 
			'hide_empty'=>'0'
		);
		$accommodation_tags = get_categories($cat_args);
			
		/* Set up some default widget settings. */
		$defaults = array(
			'title' => __('Explore our latest accommodations', 'bookyourtravel'),
			'number_of_posts' => '4',
			'sort_by' => 'title',
			'sort_descending' => '1',
			'display_mode' => 'card',
			'posts_per_row' => 4,
			'show_featured_only' => '0',
			'accommodation_type_ids' => array(),
			'accommodation_tag_ids' => array()
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e('Title:', 'bookyourtravel') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr ($instance['title']); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>"><?php _e('How many accommodations do you want to display?', 'bookyourtravel') ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_of_posts' ) ); ?>">
				<?php for ($i=1;$i<13;$i++) { ?>
				<option <?php echo ($i == $instance['number_of_posts'] ? 'selected="selected"' : ''); ?> value="<?php echo esc_attr ( $i ); ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'sort_by' ) ); ?>"><?php _e('What do you want to sort the accommodations by?', 'bookyourtravel') ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'sort_by' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'sort_by') ); ?>">
				<option <?php echo 'title' == $instance['sort_by'] ? 'selected="selected"' : ''; ?> value="title"><?php _e('Post Title', 'bookyourtravel') ?></option>
				<option <?php echo 'ID' == $instance['sort_by'] ? 'selected="selected"' : ''; ?> value="ID"><?php _e('Post ID', 'bookyourtravel') ?></option>
				<option <?php echo 'rand' == $instance['sort_by'] ? 'selected="selected"' : ''; ?> value="rand"><?php _e('Random', 'bookyourtravel') ?></option>
				<option <?php echo 'date' == $instance['sort_by'] ? 'selected="selected"' : ''; ?> value="date"><?php _e('Publish Date', 'bookyourtravel') ?></option>
				<option <?php echo 'comment_count' == $instance['sort_by'] ? 'selected="selected"' : ''; ?> value="comment_count"><?php _e('Comment Count', 'bookyourtravel') ?></option>
			</select>
		</p>		

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'sort_descending' ) ); ?>"><?php _e('Sort accommodations in descending order?', 'bookyourtravel') ?></label>
			<input type="checkbox"  <?php echo ($instance['sort_descending'] == '1' ? 'checked="checked"' : ''); ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'sort_descending' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'sort_descending') ); ?>" value="1" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'display_mode' ) ); ?>"><?php _e('Display mode?', 'bookyourtravel') ?></label>
			<select class="posts_widget_display_mode" id="<?php echo esc_attr( $this->get_field_id( 'display_mode' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_mode') ); ?>">
				<option <?php echo 'small' == $instance['display_mode'] ? 'selected="selected"' : ''; ?> value="small"><?php _e('Small (usually sidebar)', 'bookyourtravel') ?></option>
				<option <?php echo 'card' == $instance['display_mode'] ? 'selected="selected"' : ''; ?> value="card"><?php _e('Card (usually in grid view)', 'bookyourtravel') ?></option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_featured_only') ); ?>"><?php _e('Show only featured accommodations?', 'bookyourtravel') ?></label>
			<input type="checkbox"  <?php echo ( $instance['show_featured_only'] == '1' ? 'checked="checked"' : '' ); ?> class="checkbox" id="<?php echo esc_attr ( $this->get_field_id( 'show_featured_only' ) ); ?>" name="<?php echo esc_attr ( $this->get_field_name( 'show_featured_only' ) ); ?>" value="1" />
		</p>
		
		<p>
			<label><?php _e('Accommodation type (leave blank to ignore)', 'bookyourtravel') ?></label>
			<div>
				<?php for ($j=0;$j<count($accommodation_types);$j++) { 
					$type = $accommodation_types[$j];
					$checked = false;
					if (isset($instance['accommodation_type_ids'])) {
						if (in_array($type->term_id, $instance['accommodation_type_ids']))
							$checked = true;
					}
				?>
				<input <?php echo ($checked ? 'checked="checked"' : ''); ?> type="checkbox" id="<?php echo esc_attr ( $this->get_field_name( 'accommodation_type_ids' ) ); ?>_<?php echo esc_attr ($type->term_id); ?>" name="<?php echo esc_attr ( $this->get_field_name( 'accommodation_type_ids' ) ); ?>[]" value="<?php echo esc_attr ($type->term_id); ?>">
				<label for="<?php echo esc_attr ( $this->get_field_name( 'accommodation_type_ids' ) ); ?>_<?php echo esc_attr ($type->term_id); ?>"><?php echo $type->name; ?></label>
				<br />
				<?php } ?>
			</div>
		</p>
		
		<p>
			<label><?php _e('Accommodation tag (leave blank to ignore)', 'bookyourtravel') ?></label>
			<div>
				<?php for ($j=0;$j<count($accommodation_tags);$j++) { 
					$tag = $accommodation_tags[$j];
					$checked = false;
					if (isset($instance['accommodation_tag_ids'])) {
						if (in_array($tag->term_id, $instance['accommodation_tag_ids']))
							$checked = true;
					}
				?>
				<input <?php echo ($checked ? 'checked="checked"' : ''); ?> type="checkbox" id="<?php echo esc_attr ( $this->get_field_name( 'accommodation_tag_ids' ) ); ?>_<?php echo esc_attr ($tag->term_id); ?>" name="<?php echo esc_attr ( $this->get_field_name( 'accommodation_tag_ids' ) ); ?>[]" value="<?php echo esc_attr ($tag->term_id); ?>">
				<label for="<?php echo esc_attr ( $this->get_field_name( 'accommodation_tag_ids' ) ); ?>_<?php echo esc_attr ($tag->term_id); ?>"><?php echo $tag->name; ?></label>
				<br />
				<?php } ?>
			</div>
		</p>
		
		<p class="cards" <?php echo ( $instance['display_mode'] != 'card' ? 'style="display:none"' : '' ); ?>>
			<label for="<?php echo esc_attr ( $this->get_field_id( 'posts_per_row' ) ); ?>"><?php _e('How many accommodations do you want to display per row?', 'bookyourtravel') ?></label>
			<select id="<?php echo esc_attr ( $this->get_field_id( 'posts_per_row' ) ); ?>" name="<?php echo esc_attr ( $this->get_field_name( 'posts_per_row' ) ); ?>">
				<?php for ($i=1;$i<6;$i++) { ?>
				<option <?php echo ($i == $instance['posts_per_row'] ? 'selected="selected"' : ''); ?> value="<?php echo esc_attr ( $i ); ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</p>		
	<?php
	}
}