<?php
/**
 * The sidebar containing the home content widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
if ( is_active_sidebar( 'home-content' ) ) { ?>
	<section class="home-content-sidebar">
		<ul>
		<?php dynamic_sidebar( 'home-content' ); ?>
		</ul>
	</section><!-- #secondary -->
<?php } else { ?>
	<section class="home-content-sidebar">
		<?php 
		global $byt_theme_globals;
		echo '<ul>';
		$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>' );
		the_widget('byt_Search_Widget', null, $widget_args); 
		echo '</ul>';
		echo '<ul>';
		$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>' );
		the_widget('byt_Post_List_Widget', null, $widget_args); 
		echo '</ul>';
		if ($byt_theme_globals->enable_accommodations()) {
			echo '<ul>';
			$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>' );
			the_widget('byt_Accommodation_List_Widget', null, $widget_args); 
			echo '</ul>';
		}
		if ($byt_theme_globals->enable_tours()) {
			echo '<ul>';
			$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>' );
			the_widget('byt_Tour_List_Widget', null, $widget_args); 
			echo '</ul>';
		}
		if ($byt_theme_globals->enable_cruises()) {
			echo '<ul>';
			$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>' );
			the_widget('byt_Cruise_List_Widget', null, $widget_args); 
			echo '</ul>';
		}
		if ($byt_theme_globals->enable_car_rentals()) {
			echo '<ul>';
			$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>' );
			the_widget('byt_Car_Rental_List_Widget', null, $widget_args); 
			echo '</ul>';
		}
		echo '<ul>';
		$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>' );
		the_widget('byt_Location_List_Widget', null, $widget_args); 
		echo '<ul>';
		?>
	</section>
<?php } 