<?php

/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary.
 */
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class BYT_Tour_Schedule_Admin extends BYT_BaseSingleton {
	
	private $enable_tours;
	
	protected function __construct() {
	
		global $byt_theme_globals;
		
		$this->enable_tours = $byt_theme_globals->enable_tours();

        // our parent class might
        // contain shared code in its constructor
        parent::__construct();
	}

    public function init() {

		if ($this->enable_tours) {	

			add_action( 'admin_menu' , array( $this, 'tour_schedule_admin_page' ) );
			add_filter( 'set-screen-option', array( $this, 'tour_schedule_set_screen_options' ), 10, 3);
			add_action( 'admin_head', array( $this, 'tour_schedule_admin_head' ) );
		}
	}

	function tour_schedule_admin_page() {
		$hook = add_submenu_page('edit.php?post_type=tour', __('BYT Tour Schedules', 'bookyourtravel'), __('Schedule', 'bookyourtravel'), 'edit_posts', basename(__FILE__), array($this, 'tour_schedule_admin_display'));
		add_action( "load-$hook", array($this, 'tour_schedule_add_screen_options'));
	}

	function tour_schedule_set_screen_options($status, $option, $value) {
		if ( 'tour_schedule_per_page' == $option ) 
			return $value;
	}

	function tour_schedule_admin_head() {
		$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if( 'theme_tour_schedule_admin.php' != $page )
			return;

		$this->tour_schedule_admin_styles();
	}

	function tour_schedule_admin_styles() {

		if (isset($_POST['start_date'])) 
			$start_date =  wp_kses($_POST['start_date'], '');

		echo '<style type="text/css">';
		echo '	.wp-list-table .column-Id { width: 100px; }';
		echo '	.wp-list-table .column-TourName { width: 150px; }';
		echo '	.wp-list-table .column-TourType { width: 150px; }';
		echo '	.wp-list-table .column-StartDate { width: 150px; }';
		echo '	.wp-list-table .column-EndDate { width: 150px; }';
		echo '	.wp-list-table .column-MaxPeople { width: 100px; }';
		echo '	.wp-list-table .column-DurationDays { width: 100px; }';
		echo '	.wp-list-table .column-Action { width: 100px; }';
		echo '  table.calendar { width:60%; }
				table.calendar th { text-align:center; }
				table.calendar td { border:none;text-align:center;height:30px;line-height:30px;vertical-align:middle; }
				table.calendar td.sel a { color:#fff;padding:10px;background:#b1b1b1; }
				table.calendar td.cur a { color:#fff;padding:10px;background:#ededed; }';
		echo "</style>";
		
		echo '<script>';
		echo 'window.adminAjaxUrl = "' . admin_url('admin-ajax.php') . '";';
		echo 'window.datepickerDateFormat = "' . BYT_Theme_Utils::dateformat_PHP_to_jQueryUI(get_option('date_format')) . '";';
		echo 'window.datepickerAltFormat = "' . BOOKYOURTRAVEL_ALT_DATE_FORMAT . '";';
		echo '</script>';
	}

	function tour_schedule_add_screen_options() {
		global $wp_tour_schedule_table;
		$option = 'per_page';
		$args = array('label' => 'Schedule','default' => 50,'option' => 'tour_schedule_per_page');
		add_screen_option( $option, $args );
		$wp_tour_schedule_table = new Tour_Schedule_Admin_List_Table();
	}

	function tour_schedule_admin_display() {
	
		global $byt_tours_post_type;
		echo '<div class="wrap">'; 
		echo __('<h2>BYT Tour schedule</h2>', 'bookyourtravel');
		
		global $wp_tour_schedule_table;
		$wp_tour_schedule_table->handle_form_submit();
		
		if (isset($_GET['action']) && $_GET['action'] == 'delete_all_scheduled_entries') {
		
			$byt_tours_post_type->delete_all_tour_schedules();
			
			echo '<div class="error" id="message" onclick="this.parentNode.removeChild(this)">';
			echo '<p>' . __('Successfully deleted all tour scheduled entries.', 'bookyourtravel') . '</p>';
			echo '</div>';
		
		} else if (isset($_GET['sub']) && $_GET['sub'] == 'manage') {
		
			$wp_tour_schedule_table->render_entry_form(); 
		} else {
		
			$year = isset($_GET['year']) ? intval($_GET['year']) : intval(date("Y"));
			$month = isset($_GET['month']) ? intval($_GET['month']) : intval(date("m"));
			$current_day = ($year == intval(date("Y")) && $month  == intval(date("m"))) ? intval(date("j")) : 0;
			$tour_id = isset($_GET['tour_id']) ? intval($_GET['tour_id']) : 0;
		
			$tours_filter = '<select id="tours_filter" name="tours_filter" onchange="tourFilterRedirect(this.value, ' . $year . ', ' . $month . ')">';
			$tours_filter .= '<option value="">' . __('Filter by tour', 'bookyourtravel') . '</option>';

			$author_id = null;
			if (!is_super_admin()) {
				$author_id = get_current_user_id();
			}
			
			$tour_results = $byt_tours_post_type->list_tours(0, -1, 'title', 'ASC', 0, array(), array(), array(), false, $author_id);
			if ( count($tour_results) > 0 && $tour_results['total'] > 0 ) {
				foreach ($tour_results['results'] as $tour_result) {
					global $post;				
					$post = $tour_result;
					setup_postdata( $post ); 
					$tours_filter .= '<option value="' . $post->ID . '" ' . ($post->ID == $tour_id ? 'selected' : '') . '>' . $post->post_title . '</option>';
				}
			}
			$tours_filter .= '</select>';
		
			echo '<p>' . __('Filter by date: ', 'bookyourtravel') . '</p>';
			
			$wp_tour_schedule_table->render_admin_calendar($current_day, $month, $year, $tour_id); 
		
			echo '<p>' . __('Filter by tour: ', 'bookyourtravel') . $tours_filter . '</p>';
			
			$wp_tour_schedule_table->prepare_items(); 
			$wp_tour_schedule_table->display();

	?>
		<div class="tablenav bottom">	
			<div class="alignleft actions">
				<a href="edit.php?post_type=tour&page=theme_tour_schedule_admin.php&sub=manage" class="button-secondary action" ><?php _e('Add schedule', 'bookyourtravel') ?></a>
			</div>
		</div>
		<?php
		} 
	}
	
}

global $tour_schedule_admin;
$tour_schedule_admin = BYT_Tour_Schedule_Admin::get_instance();

/************************** CREATE A PACKAGE CLASS *****************************
 *******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 * 
 * To display this on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 */
class Tour_Schedule_Admin_List_Table extends WP_List_Table {

	private $options;
	private $lastInsertedID;
	private $date_format;
	
	/**
	* Constructor, we override the parent to pass our own arguments.
	* We use the parent reference to set some default configs.
	*/
	function __construct() {
		global $status, $page;	
	
		 parent::__construct( array(
			'singular'=> 'schedule', // Singular label
			'plural' => 'schedule', // plural label, also this well be one of the table css class
			'ajax'	=> false // We won't support Ajax for this table
		) );
		
		$this->date_format = get_option('date_format');		
	}	

	function column_default( $item, $column_name ) {
		return $item->$column_name;
	}	
	
	function extra_tablenav( $which ) {
		if ( $which == "top" ){	
			//The code that goes before the table is here
			$year = isset($_GET['year']) ? intval($_GET['year']) : 0;
			$month = isset($_GET['month']) ? intval($_GET['month']) : 0;
			$day = isset($_GET['day']) ? intval($_GET['day']) : 1;
			$tour_id = isset($_GET['tour_id']) ? intval($_GET['tour_id']) : 0;
			
			$tour_title = '';
			if ($tour_id > 0)
				$tour_title = get_the_title($tour_id);
			
			echo "<div class='alignleft'>";
			if ($year > 0 && $month > 0)
			{			
				echo '<p>' . __('Showing scheduled entries for ', 'bookyourtravel') . date('F Y', mktime(0,0,0, $month, $day, $year));
			} else {
				echo '<p>' . __('Showing all scheduled entries ', 'bookyourtravel');
			}
			
			if ($tour_id && !empty($tour_title)) {
				echo sprintf(__(' for tour "<strong>%s</strong>"', 'bookyourtravel'), $tour_title);
			}
			echo '</p>';
			echo '<p class="actions">';
			echo " <a class='button-secondary action alignleft' href='edit.php?post_type=tour&page=theme_tour_schedule_admin.php'>" . __("Show all scheduled entries for all tours", 'bookyourtravel') . "</a>";
			if ($tour_id && !empty($tour_title)) {
				echo " <a class='button-secondary action alignleft' href='edit.php?post_type=tour&page=theme_tour_schedule_admin.php&tour_id=$tour_id'>" . sprintf(__("Show all scheduled entries for <strong>%s</strong>", 'bookyourtravel'), $tour_title) . "</a>";
			}
			echo " <a class='button-primary action alignright' onclick='return confirmDelete(\"#delete_all_scheduled_entries\", \"" . __('Are you sure?', 'bookyourtravel') . "\");' href='edit.php?post_type=tour&page=theme_tour_schedule_admin.php&action=delete_all_scheduled_entries'>" . __("Delete all scheduled entries", "bookyourtravel") . "</a>";
			echo '</p>';
			echo '</div>';
			?>
		<div class="tablenav bottom">	
			<div class="alignleft actions">
				<a href="edit.php?post_type=tour&page=theme_tour_schedule_admin.php&sub=manage" class="button-secondary action" ><?php _e('Add schedule', 'bookyourtravel') ?></a>
			</div>
		</div>			
			<?php
		}
		if ( $which == "bottom" ){
			//The code that goes after the table is there
		}
	}		
	
	function column_TourName($item) {
		return $item->tour_name;	
	}
	
	function column_TourType($item) {
		$tour_obj = new byt_tour($item->tour_id);
		return $tour_obj->get_type_name();
	}
	
	function column_MaxPeople($item) {
		return $item->max_people;	
	}
	
	function column_DurationDays($item) {
		return $item->duration_days . ' ' . __('days', 'bookyourtravel');	
	}
	
	function column_Price($item) {
		if ($item->tour_is_price_per_group)
			return $item->price;
		return $item->price . ' / ' . $item->price_child;	
	}
	
	function column_StartDate($item) {
		return date($this->date_format, strtotime($item->start_date));	
	}
	
	function column_EndDate($item) {
		return $item->end_date != null ? date($this->date_format, strtotime($item->end_date)) : __('N/A', 'bookyourtravel');	
	}
	
	function column_Action($item) {
		if (!$item->has_bookings) {
		
			$action = "<form method='post' name='delete_schedule_" . $item->Id . "' id='delete_schedule_" . $item->Id . "' style='display:inline;'>
						<input type='hidden' name='delete_schedule' id='delete_schedule' value='" . $item->Id . "' />
						<a href='javascript: void(0);' onclick='confirmDelete(\"#delete_schedule_" . $item->Id . "\", \"" . __('Are you sure?', 'bookyourtravel') . "\");'>" . __('Delete', 'bookyourtravel') . "</a>
					</form>";
					
			$action .= ' | 	<a href="edit.php?post_type=tour&page=theme_tour_schedule_admin.php&sub=manage&edit=' . $item->Id . '">' . __('Edit', 'bookyourtravel') . '</a>';
			return $action;
		}
		return "";
	}	
	
	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
		return $columns= array(
			'Id'=>__('Id', 'bookyourtravel'),
			'StartDate'=>__('Start Date', 'bookyourtravel'),
			'EndDate'=>__('End Date', 'bookyourtravel'),
			'TourName'=>__('Tour Name', 'bookyourtravel'),
			'TourType'=>__('Tour Type', 'bookyourtravel'),
			'DurationDays'=>__('Days', 'bookyourtravel'),
			'Price'=>__('Price', 'bookyourtravel'),
			'MaxPeople'=>__('Max people', 'bookyourtravel'),
			'Action'=>__('Action', 'bookyourtravel'),				
		);
	}	
		
	/**
	 * Decide which columns to activate the sorting functionality on
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'Id'=> array( 'Id', true ),
			'TourName'=> array( 'tours.post_title', true ),
			'StartDate'=> array( 'start_date', true ),
			'EndDate'=> array( 'end_date', true ),
			'DurationDays'=> array( 'duration_days', true ),
			'Price'=> array( 'price', true ),
			'MaxPeople'=> array( 'max_people', true ),
		);
		return $sortable_columns;
	}	
	
	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
	
		global $_wp_column_headers, $byt_tours_post_type;
		
		$year = isset($_GET['year']) ? intval($_GET['year']) : 0;
		$month = isset($_GET['month']) ? intval($_GET['month']) : 0;
		$day = isset($_GET['day']) ? intval($_GET['day']) : 0;
		$tour_id = isset($_GET['tour_id']) ? intval($_GET['tour_id']) : 0;
		
		$screen = get_current_screen();
		$user = get_current_user_id();
		$option = $screen->get_option('per_page', 'option');
		$per_page = get_user_meta($user, $option, true);
		if ( empty ( $per_page) || $per_page < 1 ) {
			$per_page = $screen->get_option( 'per_page', 'default' );
		}	

		$search_term = '';
		if (!empty($_REQUEST['s'])) {
			$search_term = mysql_real_escape_string(strtolower($_REQUEST['s']));
		}

		$columns = $this->get_columns(); 
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);		
		
		/* -- Ordering parameters -- */
		//Parameters that are going to be used to order the result
		$orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'Id';
		$order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'ASC';

		/* -- Pagination parameters -- */
		//How many to display per page?
		//Which page is this?
		$paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
		//Page Number
		if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
		
		$author_id = null;
		if (!is_super_admin()) {
			$author_id = get_current_user_id();
		}
		
		$tour_schedule_results = $byt_tours_post_type->list_tour_schedules($paged, $per_page, $orderby, $order, $day, $month, $year, $tour_id, $search_term, $author_id);

		//Number of elements in your table?
		$totalitems = $tour_schedule_results['total']; //return the total number of affected rows

		//How many pages do we have in total?
		$totalpages = ceil($totalitems/$per_page);

		/* -- Register the pagination -- */
		$this->set_pagination_args( array(
			"total_items" => $totalitems,
			"total_pages" => $totalpages,
			"per_page" => $per_page,
		) );
		//The pagination links are automatically built according to those parameters

		/* -- Register the Columns -- */
		$columns = $this->get_columns();
		$_wp_column_headers[$screen->id]=$columns;

		/* -- Fetch the items -- */
		$this->items = $tour_schedule_results['results'];
	}
	
	function handle_form_submit() {
	
		global $byt_tours_post_type;
		
		if (isset($_POST['insert']) && check_admin_referer('tour_schedule_entry_form')) {
			
			$tour_id = wp_kses($_POST['tours_select'], '');			
			$tour_obj = new byt_tour(intval($tour_id));
			$tour_id = $tour_obj->get_base_id();
			$is_price_per_group = $tour_obj->get_is_price_per_group();
			$tour_type_is_repeated = $tour_obj->get_type_is_repeated();
			
			$start_date =  wp_kses($_POST['start_date'], '');
			$max_people = intval(wp_kses($_POST['max_people'], ''));
			$duration_days = intval(wp_kses($_POST['duration_days'], ''));
			$price = floatval(wp_kses($_POST['price'], ''));
			$price_child = floatval(wp_kses((isset($_POST['price_child']) ? $_POST['price_child'] : 0), ''));
			$end_date = isset($_POST['end_date']) ? wp_kses($_POST['end_date'], '') : null;
			
			$error = '';
			
			if(empty($tour_id)) {
				$error = __('You must select a tour', 'bookyourtravel');
			} else if(empty($start_date)) {
				$error = __('You must select a schedule date', 'bookyourtravel');
			} else if(empty($max_people) || $max_people === 0) {
				$error = __('You must provide a max people count', 'bookyourtravel');
			} else if(empty($duration_days) || $duration_days === 0) {
				$error = __('You must provide a duration in days', 'bookyourtravel');
			} else if(empty($price) || $price === 0) {
				$error = __('You must provide a valid price', 'bookyourtravel');
			}
			
			if (!empty($error)) {
				  echo '<div class="error" id="message" onclick="this.parentNode.removeChild(this)">';
				  echo '<p>' . $error . '</p>';
				  echo '</div>';
			} else {
				
				$byt_tours_post_type->create_tour_schedule($tour_id, $start_date, $duration_days, $price, $price_child, $max_people, $end_date);
				
				echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
				echo '<p>' . __('Successfully inserted new tour schedule entry!', 'bookyourtravel') . '</p>';
				echo '</div>';

			}
		} else if (isset($_POST['update']) && check_admin_referer('tour_schedule_entry_form')) {

			$tour_id = wp_kses($_POST['tours_select'], '');			
			$tour_obj = new byt_tour(intval($tour_id));
			$tour_id = $tour_obj->get_base_id();
			$is_price_per_group = $tour_obj->get_is_price_per_group();
			$tour_type_is_repeated = $tour_obj->get_type_is_repeated();

			$start_date =  wp_kses($_POST['start_date'], '');
			$max_people = intval(wp_kses($_POST['max_people'], ''));
			$duration_days = intval(wp_kses($_POST['duration_days'], ''));
			$price = floatval(wp_kses($_POST['price'], ''));
			$price_child = floatval(wp_kses((isset($_POST['price_child']) ? $_POST['price_child'] : 0), ''));
			$end_date = isset($_POST['end_date']) ? wp_kses($_POST['end_date'], '') : null;
			
			$error = '';
			
			if(empty($tour_id)) {
				$error = __('You must select an tour', 'bookyourtravel');
			} else if(empty($start_date)) {
				$error = __('You must select a schedule date', 'bookyourtravel');
			} else if(empty($max_people) || $max_people === 0) {
				$error = __('You must provide a max people count', 'bookyourtravel');
			} else if(empty($duration_days) || $duration_days === 0) {
				$error = __('You must provide a duration in days', 'bookyourtravel');
			} else if(empty($price) || $price === 0) {
				$error = __('You must provide a valid price', 'bookyourtravel');
			}
			
			if (!empty($error)) {
				  echo '<div class="error" id="message" onclick="this.parentNode.removeChild(this)">';
				  echo '<p>' . $error . '</p>';
				  echo '</div>';
			} else {
				
				$schedule_id = absint($_POST['schedule_id']);
				
				$byt_tours_post_type->update_tour_schedule($schedule_id, $start_date, $duration_days, $tour_id, $price, $price_child, $max_people, $end_date);
				
				echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
				echo '<p>' . __('Successfully updated tour schedule entry!', 'bookyourtravel') . '</p>';
				echo '</div>';

			}
		
		} else if (isset($_POST['delete_schedule'])) {
			$schedule_id = absint($_POST['delete_schedule']);
			
			$byt_tours_post_type->delete_tour_schedule($schedule_id);
			
			echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
			echo '<p>' . __('Successfully deleted tour schedule entry!', 'bookyourtravel') . '</p>';
			echo '</div>';
		}
		
	}
	
	function render_entry_form() {

		global $byt_tours_post_type;
		$tour_id = 0;		
		$schedule_object = null;
		$tour_obj = null;
		$is_price_per_group = 0;
		$tour_type_is_repeated = 0; // on-off tour by default
		
		$edit = isset($_GET['edit']) ? absint($_GET['edit']) : "";
		
		if (!empty($edit)) {
			$schedule_object = $byt_tours_post_type->get_tour_schedule($edit);
		}
		
		if (isset($_POST['tours_select'])) {
			$tour_id = wp_kses($_POST['tours_select'], '');
		} else if ($schedule_object != null) {
			$tour_id = $schedule_object->tour_id;
		}
		
		if ($tour_id) {
			$tour_obj = new byt_tour(intval($tour_id));
			$tour_id = $tour_obj->get_base_id();
			$is_price_per_group = $tour_obj->get_is_price_per_group();
			$tour_type_is_repeated = $tour_obj->get_type_is_repeated();
		}
				
		$tours_select = '<select id="tours_select" name="tours_select">';
		$tours_select .= '<option value="">' . __('Select tour', 'bookyourtravel') . '</option>';

		$author_id = null;
		if (!is_super_admin()) {
			$author_id = get_current_user_id();
		}
		
		$tour_results = $byt_tours_post_type->list_tours(0, -1, 'title', 'ASC', 0, array(), array(), array(), false, $author_id);
		if ( count($tour_results) > 0 && $tour_results['total'] > 0 ) {
			foreach ($tour_results['results'] as $tour_result) {
				global $post;				
				$post = $tour_result;
				setup_postdata( $post ); 
				$tours_select .= '<option value="' . $post->ID . '" ' . ($post->ID == $tour_id ? 'selected' : '') . '>' . $post->post_title . '</option>';
			}
		}
		$tours_select .= '</select>';
		
		$start_date = null;
		if (isset($_POST['start_date']))
			$start_date =  wp_kses($_POST['start_date'], '');
		else if ($schedule_object != null) {
			$start_date = $schedule_object->start_date;
		}
		if (isset($start_date))
			$start_date = date( $this->date_format, strtotime( $start_date ) );
		
		$max_people = 0;
		if (isset($_POST['max_people']))
			$max_people = intval(wp_kses($_POST['max_people'], '0'));
		else if ($schedule_object != null) {
			$max_people = $schedule_object->max_people;
		}
		
		$duration_days = 0;
		if (isset($_POST['duration_days']))
			$duration_days = intval(wp_kses($_POST['duration_days'], '0'));
		else if ($schedule_object != null) {
			$duration_days = $schedule_object->duration_days;
		}			
			
		$price = 0;
		if (isset($_POST['price']))
			$price = floatval(wp_kses($_POST['price'], '2'));
		else if ($schedule_object != null) {
			$price = $schedule_object->price;
		}
		
		$price_child = 0;
		if (!$is_price_per_group) {
			if (isset($_POST['price_child']))
				$price_child = floatval(wp_kses($_POST['price_child'], '2'));
			else if ($schedule_object != null) {
				$price_child = $schedule_object->price_child;
			}
		}
		
		$end_date = null;
		if (isset($_POST['end_date'])) {
			$end_date =  wp_kses($_POST['end_date'], '');
		} else if ($schedule_object != null) {
			$end_date = $schedule_object->end_date;
		}
		if (isset($end_date))
			$end_date = date( $this->date_format, strtotime( $end_date ) );		
		
		if ($schedule_object)
			echo '<h3>' . __('Update Tour Schedule Entry', 'bookyourtravel') . '</h3>';
		else
			echo '<h3>' . __('Add Tour Schedule Entry', 'bookyourtravel') . '</h3>';

		echo '<form id="tour_schedule_entry_form" method="post" action="' . esc_url($_SERVER['REQUEST_URI']) . '" style="clear: both;">';
		echo wp_nonce_field('tour_schedule_entry_form');	
		echo '<table cellpadding="3" class="form-table"><tbody>';
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Select tour', 'bookyourtravel') . '</th>';
		echo '	<td>' . $tours_select . '</td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Max number of people', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="max_people" id="max_people" value="' . $max_people . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Duration (days)', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="duration_days" id="duration_days" value="' . $duration_days . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Start date', 'bookyourtravel') . '</th>';
		echo '	<td>';
		echo '		<script>';
		echo '			window.datepickerStartDateValue = "' . $start_date . '";';
		echo '  	</script>';				
		echo '  	<input class="datepicker" type="text" name="datepicker_start_date" id="datepicker_start_date" />';
		echo '		<input type="hidden" name="start_date" id="start_date" />';
		echo '	</td>';	
		echo '</tr>';

		echo '<tr class="is_repeated" ' . ($tour_type_is_repeated ? '' : 'style="display:none"') . '>';
		echo '	<th scope="row" valign="top">' . __('End date', 'bookyourtravel') . '</th>';
		echo '	<td>';
		echo '		<script>';
		echo '			window.datepickerEndDateValue = "' . $end_date . '";';
		echo '  	</script>';				
		echo '  	<input class="datepicker" type="text" name="datepicker_end_date" id="datepicker_end_date" />';
		echo '		<input type="hidden" name="end_date" id="end_date" />';
		echo '	</td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Price', 'bookyourtravel') . ' <span class="per_person" ' . (!$is_price_per_group ? '' : 'style="display:none"') . '>' . __('per adult', 'bookyourtravel') . '</span> <span class="per_group" ' . ($is_price_per_group ? '' : 'style="display:none"') . '>' . __('per group', 'bookyourtravel') . '</span></th>';
		echo '	<td><input type="text" name="price" id="price" value="' . $price . '" /></td>';
		echo '</tr>';

		echo '<tr class="per_person" ' . (!$is_price_per_group ? '' : 'style="display:none"') . '>';		
		echo '	<th scope="row" valign="top">' . __('Price per child', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="price_child" id="price_child" value="' . $price_child . '" /></td>';
		echo '</tr>';
		
		echo '</table>';
		echo '<p>';
		echo '<a href="edit.php?post_type=tour&page=theme_tour_schedule_admin.php" class="button-secondary">' . __('Cancel', 'bookyourtravel') . '</a>&nbsp;';
		if ($schedule_object) {
			echo '<input id="schedule_id" name="schedule_id" value="' . $edit . '" type="hidden" />';
			echo '<input class="button-primary" type="submit" name="update" value="' . __('Update Tour Schedule Entry', 'bookyourtravel') . '"/>';
		} else {
			echo '<input class="button-primary" type="submit" name="insert" value="' . __('Add Tour Schedule Entry', 'bookyourtravel') . '"/>';
		}
		
		echo '</p>';
		
		echo '</form>';
	}

	function render_admin_calendar($current_day, $month, $year, $tour_id=0){

		/* draw table */
		$calendar = '<table cellpadding="0" cellspacing="0" class="wp-list-table widefat fixed calendar">';

		/* table headings */
		$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$calendar.= '<thead>';
		$effectiveDate = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
		$nextDate = date('Y-m-d', strtotime("+1 months", strtotime($effectiveDate)));
		$prevDate = date('Y-m-d', strtotime("-1 months", strtotime($effectiveDate)));
		$current_date_text = date('F Y', mktime(0, 0, 0, $month, 1, $year));
		$next_link = "edit.php?post_type=tour&page=theme_tour_schedule_admin.php&year=" . date('Y', strtotime($nextDate)) . "&month=" . date('m', strtotime($nextDate));
		$prev_link = "edit.php?post_type=tour&page=theme_tour_schedule_admin.php&year=" . date('Y', strtotime($prevDate)) . "&month=" . date('m', strtotime($prevDate));
		
		if ($tour_id > 0) {
			$next_link .= "&tour_id=" . $tour_id;
			$prev_link .= "&tour_id=" . $tour_id;
		}		
		
		$calendar.= '<tr><th><a class="alignleft" href="' . $prev_link . '">&laquo;</a></th><th class="aligncenter" colspan="5">' . $current_date_text . '</th><th><a class="alignright" href="' . $next_link . '">&raquo;</a></th></tr>';
		$calendar.= '<tr><th>'.implode('</th><th>',$headings).'</th></tr></thead>';

		/* days and weeks vars now ... */
		$running_day = date('w',mktime(0,0,0,$month,1,$year));
		$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = array();

		/* row for week one */
		$calendar.= '<tbody><tr>';

		/* print "blank" days until the first of the current week */
		for($x = 0; $x < $running_day; $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
			$days_in_this_week++;
		endfor;

		$request_day = isset($_GET['day']) ? intval($_GET['day']) : 0;
		
		/* keep going with days.... */
		for($list_day = 1; $list_day <= $days_in_month; $list_day++):
			
			$td_class = '';
			if ($list_day == $request_day) 
				$td_class = 'sel';
			if ($list_day == $current_day) 
				$td_class = ' cur';
			$calendar.= '<td class="calendar-day ' . $td_class . '">';
				/* add in the day number */
				$calendar.= '<div class="day-number">';
				$calendar.= "<a href='edit.php?post_type=tour&page=theme_tour_schedule_admin.php&year=$year&month=$month&day=$list_day";
				
				if ($tour_id > 0) 
					$calendar .= '&tour_id=' . $tour_id;
				$calendar .= "'>";				
				
				if ($list_day == $request_day || $list_day == $current_day ) 
					$calendar .= "<strong>";
				$calendar.= $list_day;
				if ($list_day == $request_day || $list_day == $current_day) 
					$calendar .= "</strong>";
				$calendar.= "</a>";
				$calendar.= '</div>';

				/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
				$calendar.= str_repeat('<p> </p>',2);
				
			$calendar.= '</td>';
			if($running_day == 6):
				$calendar.= '</tr>';
				if(($day_counter+1) != $days_in_month):
					$calendar.= '<tr>';
				endif;
				$running_day = -1;
				$days_in_this_week = 0;
			endif;
			$days_in_this_week++; $running_day++; $day_counter++;
		endfor;

		/* finish the rest of the days in the week */
		if($days_in_this_week > 1 && $days_in_this_week < 8):
			for($x = 1; $x <= (8 - $days_in_this_week); $x++):
				$calendar.= '<td class="calendar-day-np"> </td>';
			endfor;
		endif;

		/* final row */
		$calendar.= '</tr>';

		/* end the table */
		$calendar.= '</tbody></table>';
		
		/* all done, return result */
		echo $calendar;
	}	
}