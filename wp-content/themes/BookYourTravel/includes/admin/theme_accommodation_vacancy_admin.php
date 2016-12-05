<?php
/*
*******************************************************************************
************************** LOAD THE BASE CLASS ********************************
*******************************************************************************
* The WP_List_Table class isn't automatically available to plugins, 
* so we need to check if it's available and load it if necessary.
*******************************************************************************
*/ 
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class BYT_Accommodation_Vacancies_Admin extends BYT_BaseSingleton {
	
	private $enable_accommodations;
	
	protected function __construct() {
	
		global $byt_theme_globals;
		
		$this->enable_accommodations = $byt_theme_globals->enable_accommodations();

        // our parent class might
        // contain shared code in its constructor
        parent::__construct();
	}

    public function init() {

		if ($this->enable_accommodations) {	

			add_action( 'admin_menu' , array( $this, 'vacancies_admin_page' ) );
			add_filter( 'set-screen-option', array( $this, 'vacancies_set_screen_options' ), 10, 3);
			add_action( 'admin_head', array( $this, 'vacancies_admin_head' ) );
		}
	}
	
	function vacancies_admin_page() {
	
		$hook = add_submenu_page('edit.php?post_type=accommodation', __('BYT Accommodation Vacancies', 'bookyourtravel'), __('Vacancies', 'bookyourtravel'), 'edit_posts', basename(__FILE__), array($this, 'vacancies_admin_display' ));
		add_action( "load-$hook", array($this,  'vacancies_add_screen_options' ));
	}	

	function vacancies_add_screen_options() {
	
		global $wp_accommodation_vacancy_table;
		
		$option = 'per_page';
		$args = array('label' => 'Vacancies','default' => 50,'option' => 'accommodation_vacancies_per_page');
		add_screen_option( $option, $args );
		
		$wp_accommodation_vacancy_table = new Accommodation_Vacancy_Admin_List_Table();
	}

	function vacancies_admin_display() {
	
		global $byt_accommodations_post_type, $byt_room_types_post_type, $wp_accommodation_vacancy_table;
		echo '<div class="wrap">';
		echo __('<h2>BYT Accommodation vacancies</h2>', 'bookyourtravel');
		
		$wp_accommodation_vacancy_table->handle_form_submit();
		
		if (isset($_GET['action']) && $_GET['action'] == 'delete_all_vacancies') {

			$byt_accommodations_post_type->delete_all_accommodation_vacancies();
			
			echo '<div class="error" id="message" onclick="this.parentNode.removeChild(this)">';
			echo '<p>' . __('Successfully deleted all accommodation vacancies.', 'bookyourtravel') . '</p>';
			echo '</div>';
			
		} else if (isset($_GET['sub']) && $_GET['sub'] == 'manage') {
		
			$wp_accommodation_vacancy_table->render_entry_form(); 
		} else {
		
			$accommodation_id = isset($_GET['accommodation_id']) ? intval($_GET['accommodation_id']) : 0;
			$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
			$is_self_catered = get_post_meta( $accommodation_id, 'accommodation_is_self_catered', true );
			$room_type_id = isset($_GET['room_type_id']) ? intval($_GET['room_type_id']) : 0;
		
			$accommodations_filter = '<select id="accommodations_filter" name="accommodations_filter" onchange="accommodationFilterRedirect(this.value, ' . $room_type_id . ')">';
			$accommodations_filter .= '<option value="">' . __('Filter by accommodation', 'bookyourtravel') . '</option>';
						
			$author_id = null;
			if (!is_super_admin()) {
				$author_id = get_current_user_id();
			}
				
			$accommodation_results = $byt_accommodations_post_type->list_accommodations(0, -1, 'title', 'ASC', 0, array(), array(), array(), false, null, $author_id);
			if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) {
				foreach ($accommodation_results['results'] as $accommodation_result) {
					global $post;				
					$post = $accommodation_result;
					setup_postdata( $post ); 				
					$accommodations_filter .= '<option value="' . $post->ID . '" ' . ($post->ID == $accommodation_id ? 'selected' : '') . '>' . $post->post_title . '</option>';
				}
			}
			$accommodations_filter .= '</select>';
			
			$room_type_filter = '<select id="room_type_filter" name="room_type_filter" onchange="accommodationFilterRedirect(' . $accommodation_id . ', this.value)">';
			$room_type_filter .= '<option value="">' . __('Filter by room type', 'bookyourtravel') . '</option>';
			$room_type_query = $byt_room_types_post_type->list_room_types($author_id);
			if ($room_type_query->have_posts()) {
				while ($room_type_query->have_posts()) {
					$room_type_query->the_post();
					global $post;				
					$room_type_filter .= '<option value="' . $post->ID . '" ' . ($post->ID == $room_type_id ? 'selected' : '') . '>' . $post->post_title . '</option>';
				}
			}
			$room_type_filter .= '</select>';
			wp_reset_postdata();
		
			echo '<p>';
			echo __('Filter by accommodation: ', 'bookyourtravel') . $accommodations_filter;
			
			if (!$accommodation_id || !$is_self_catered)
				echo ' ' . __('Filter by room type: ', 'bookyourtravel') . $room_type_filter;
				
			echo '</p>';
			
			$wp_accommodation_vacancy_table->prepare_items(); 
			$wp_accommodation_vacancy_table->display();		
	?>
		<div class="tablenav bottom">	
			<div class="alignleft actions">
				<a href="edit.php?post_type=accommodation&page=theme_accommodation_vacancy_admin.php&sub=manage" class="button-secondary action" ><?php _e('Add Vacancy', 'bookyourtravel') ?></a>
			</div>
		</div>
		<?php
		} 
	}

	function vacancies_set_screen_options($status, $option, $value) {
		if ( 'accommodation_vacancies_per_page' == $option ) 
			return $value;
	}

	function vacancies_admin_head() {
		$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if( 'theme_accommodation_vacancy_admin.php' != $page )
			return;

		$this->vacancies_admin_styles();
	}
		
	function vacancies_admin_styles() {

		echo '<style type="text/css">';
		echo '	.wp-list-table .column-Id { width: 100px; }';
		echo '	.wp-list-table .column-AccommodationName { width: 250px; }';
		echo '	.wp-list-table .column-RoomType { width: 150px; }';
		echo '	.wp-list-table .column-StartDate { width: 150px; }';
		echo '	.wp-list-table .column-EndDate { width: 150px; }';
		echo '	.wp-list-table .column-Rooms { width: 150px; }';
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
}

global $accommodation_vacancies_admin;
$accommodation_vacancies_admin = BYT_Accommodation_Vacancies_Admin::get_instance();

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
class Accommodation_Vacancy_Admin_List_Table extends WP_List_Table {

	private $options;
	private $lastInsertedID;
	private $date_format;
	
	/**
	* Constructor, we override the parent to pass our own arguments.
	* We use the parent reference to set some default configs.
	*/
	function __construct() {
		global $status, $page;	
		
		$this->date_format = get_option('date_format');
	
		 parent::__construct( array(
			'singular'=> 'vacancy', // Singular label
			'plural' => 'vacancies', // plural label, also this well be one of the table css class
			'ajax'	=> false // We won't support Ajax for this table
		) );
		
	}	

	function column_default( $item, $column_name ) {
		return $item->$column_name;
	}	
	
	function extra_tablenav( $which ) {
		if ( $which == "top" ){	
			//The code that goes before the table is here
			$accommodation_id = isset($_GET['accommodation_id']) ? intval($_GET['accommodation_id']) : 0;
			$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
			
			$accommodation_title = '';
			if ($accommodation_id > 0)
				$accommodation_title = get_the_title($accommodation_id);
			
			echo "<div class='alignleft'>";
			
			echo '</p>';
			echo '<p class="alignleft actions">';
			echo " <a class='button-secondary action alignleft' href='edit.php?post_type=accommodation&page=theme_accommodation_vacancy_admin.php'>Show all vacancies for all accommodations</a>";
			echo '</p>';
			echo '</div>';?>
		<div class="tablenav top">	
			<div class="alignleft actions">
				<a href="edit.php?post_type=accommodation&page=theme_accommodation_vacancy_admin.php&sub=manage" class="button-secondary action" ><?php _e('Add Vacancy', 'bookyourtravel') ?></a>
			</div>
		</div>
		<?php
		}
		if ( $which == "bottom" ){
			//The code that goes after the table is there
		}
	}		
	
	function column_SeasonName($item) {
		return $item->season_name;	
	}
	
	function column_AccommodationName($item) {
		return $item->accommodation_name;	
	}
	
	function column_RoomType($item) {
		if ($item->room_type && !$item->accommodation_is_self_catered)
			return $item->room_type;	
		else
			return __('N/A', 'bookyourtravel');
	}
	
	function column_RoomCount($item) {
		if ($item->room_count && !$item->accommodation_is_self_catered)
			return $item->room_count;	
		else
			return __('N/A', 'bookyourtravel');
	}
	
	function column_PricePerDay($item) {
		if ($item->accommodation_is_per_person)
			return $item->price_per_day . ' / ' . $item->price_per_day_child;	
		else
			return $item->price_per_day;
	}

	function column_StartDate($item) {
		return date($this->date_format, strtotime($item->start_date));	
	}
	
	function column_EndDate($item) {
		return date($this->date_format, strtotime($item->end_date));	
	}
	
	function column_Action($item) {
	
		$accommodation_id = isset($_GET['accommodation_id']) ? intval($_GET['accommodation_id']) : 0;
		$room_type_id = isset($_GET['room_type_id']) ? intval($_GET['room_type_id']) : 0;
		
		$url_part = '';
		if ($accommodation_id > 0)
			$url_part .= "&accommodation_id=$accommodation_id";
		if ($room_type_id > 0)
			$url_part .= "&room_type_id=$room_type_id";
	
		$action = "<form method='post' name='delete_vacancy_" . $item->Id . "' id='delete_vacancy_" . $item->Id . "' style='display:inline;'>
					<input type='hidden' name='delete_vacancy' id='delete_vacancy' value='" . $item->Id . "' />
					<a href='javascript: void(0);' onclick='confirmDelete(\"#delete_vacancy_" . $item->Id . "\", \"" . __('Are you sure?', 'bookyourtravel') . "\");'>" . __('Delete', 'bookyourtravel') . "</a>
				</form>";
		$action .= ' | 	<a href="edit.php?post_type=accommodation&page=theme_accommodation_vacancy_admin.php&sub=manage&edit=' . $item->Id . $url_part . '">' . __('Edit', 'bookyourtravel') . '</a>';
		return $action;
	}	
	
	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
		return $columns= array(
			'Id'=>__('Id', 'bookyourtravel'),
			'SeasonName'=>__('Season Name', 'bookyourtravel'),
			'AccommodationName'=>__('Accommodation Name', 'bookyourtravel'),
			'StartDate'=>__('Start Date', 'bookyourtravel'),
			'EndDate'=>__('End Date', 'bookyourtravel'),
			'RoomType'=>__('Room Type', 'bookyourtravel'),
			'RoomCount'=>__('Rooms', 'bookyourtravel'),
			'PricePerDay'=>__('Price per day', 'bookyourtravel'),
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
			'SeasonName'=> array( 'season_name', true ),
			'AccommodationName'=> array( 'accommodations.post_title', true ),
			'RoomType'=> array( 'room_types.post_title', true ),
			'StartDate'=> array( 'start_date', true ),
			'EndDate'=> array( 'end_date', true ),
			'RoomCount'=> array( 'room_count', true ),
			'PricePerDay'=> array( 'price_per_day', true ),
		);
		return $sortable_columns;
	}	
	
	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
	
		global $_wp_column_headers;
		global $byt_accommodations_post_type, $byt_room_types_post_type;
		
		$accommodation_id = isset($_GET['accommodation_id']) ? intval($_GET['accommodation_id']) : 0;
		$accommodation_id = BYT_Theme_Utils::get_default_language_post_id($accommodation_id, 'accommodation');
		
		$room_type_id = isset($_GET['room_type_id']) ? intval($_GET['room_type_id']) : 0;
		$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type');
		
		$screen = get_current_screen();
		$user = get_current_user_id();
		$option = $screen->get_option('per_page', 'option');
		$per_page = get_user_meta($user, $option, true);
		if ( empty ( $per_page) || $per_page < 1 ) {
			$per_page = $screen->get_option( 'per_page', 'default' );
		}	

		$columns = $this->get_columns(); 
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);		
		
		/* -- Ordering parameters -- */
		//Parameters that are going to be used to order the result
		$orderby = !empty($_GET["orderby"]) ? wp_kses($_GET["orderby"], '') : 'Id';
		$order = !empty($_GET["order"]) ? wp_kses($_GET["order"], '') : 'ASC';

		/* -- Pagination parameters -- */
		//How many to display per page?
		//Which page is this?
		$paged = !empty($_GET["paged"]) ? wp_kses($_GET["paged"], '') : '';
		//Page Number
		if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }

		$author_id = null;
		if (!is_super_admin()) {
			$author_id = get_current_user_id();
		}
		
		$accommodation_vacancy_results = $byt_accommodations_post_type->list_all_accommodation_vacancies($accommodation_id, $room_type_id, $orderby, $order, $paged, $per_page, $author_id);
		
		//Number of elements in your table?
		$totalitems = $accommodation_vacancy_results['total']; //return the total number of affected rows

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
		$this->items = $accommodation_vacancy_results['results'];
	}
	
	function handle_form_submit() {
	
		global $byt_accommodations_post_type, $byt_room_types_post_type;
	
		if ((isset($_POST['insert']) || isset($_POST['update'])) && check_admin_referer('accommodation_vacancy_nonce')) {
		
			$accommodation_id = wp_kses($_POST['accommodations_select'], '');
			$accommodation_obj = new byt_accommodation(intval($accommodation_id));
			$accommodation_id = $accommodation_obj->get_base_id();
			
			$room_type_id = isset($_POST['room_types_select']) ? wp_kses($_POST['room_types_select'], '') : 0;
			$room_type_obj = new byt_room_type(intval($room_type_id));
			$room_type_id = $room_type_obj->get_base_id();
			
			$is_self_catered = $accommodation_obj->get_is_self_catered();
			$is_price_per_person = $accommodation_obj->get_is_price_per_person();

			$season_name =  wp_kses($_POST['season_name'], '');
			$room_count = isset($_POST['room_count']) ? intval(wp_kses($_POST['room_count'], '')) : 1;
			$price_per_day = floatval(wp_kses($_POST['price_per_day'], ''));
			$price_per_day_child = isset($_POST['price_per_day_child']) ? floatval(wp_kses($_POST['price_per_day_child'], '')) : 0;

			$date_from =  wp_kses($_POST['date_from'], '');
			$start_date = $date_from;

			$date_to =  wp_kses($_POST['date_to'], '');
			$end_date = $date_to;
			
			if (isset($_POST['insert'])) {
				
				$error = '';
				
				if(empty($accommodation_id)) {
					$error = __('You must select an accommodation', 'bookyourtravel');
				} else if(!$is_self_catered && $room_type_id <= 0) {
					$error = __('You must select a room type', 'bookyourtravel');
				} else if(empty($date_from)) {
					$error = __('You must select a from date', 'bookyourtravel');
				} else if(empty($date_to)) {
					$error = __('You must select a to date', 'bookyourtravel');
				} else if(empty($price_per_day) || $price_per_day === 0) {
					$error = __('You must provide a valid price per day', 'bookyourtravel');
				}
				
				if (!empty($error)) {
					  echo '<div class="error" id="message" onclick="this.parentNode.removeChild(this)">';
					  echo '<p>' . $error . '</p>';
					  echo '</div>';
				} else {
					
					$byt_accommodations_post_type->create_accommodation_vacancy($season_name, $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child);
					
					echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
					echo '<p>' . __('Successfully inserted new vacancy!', 'bookyourtravel') . '</p>';
					echo '</div>';
				}
			} else if (isset($_POST['update'])) {

				$error = '';
				
				if(empty($accommodation_id)) {
					$error = __('You must select an accommodation', 'bookyourtravel');
				} else if(!$is_self_catered && empty($room_type_id)) {
					$error = __('You must select a room type', 'bookyourtravel');
				} else if (!$is_self_catered && (empty($room_count) || $room_count === 0)) {
					$error = __('You must provide a valid room count', 'bookyourtravel');
				} else if(empty($date_from)) {
					$error = __('You must select a from date', 'bookyourtravel');
				} else if(empty($date_to)) {
					$error = __('You must select a to date', 'bookyourtravel');
				} else if(empty($price_per_day) || $price_per_day === 0) {
					$error = __('You must provide a valid price per day', 'bookyourtravel');
				}
				
				if (!empty($error)) {
				
					  echo '<div class="error" id="message" onclick="this.parentNode.removeChild(this)">';
					  echo '<p>' . $error . '</p>';
					  echo '</div>';
				} else {
					
					$vacancy_id = absint($_POST['vacancy_id']);
					
					$byt_accommodations_post_type->update_accommodation_vacancy($vacancy_id, $season_name, $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child);
					
					echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
					echo '<p>' . sprintf(__('Successfully updated vacancy (id=%d)!', 'bookyourtravel'), $vacancy_id) . '</p>';
					echo '</div>';
				}
			} 
		} else if (isset($_POST['delete_vacancy'])) {
		
			$vacancy_id = absint($_POST['delete_vacancy']);
			
			$byt_accommodations_post_type->delete_accommodation_vacancy($vacancy_id);	
			
			echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
			echo '<p>' . __('Successfully deleted vacancy!', 'bookyourtravel') . '</p>';
			echo '</div>';
		}
		
	}
	
	function render_entry_form() {

		global $byt_accommodations_post_type, $byt_room_types_post_type;
	
		$accommodation_id = 0;
		$is_self_catered = 0;
		$vacancy_object = null;
		$accommodation_obj = null;
		$is_price_per_person = 0;
		
		$edit = isset($_GET['edit']) ? absint($_GET['edit']) : "";
		
		if (!empty($edit)) {
			$vacancy_object = $byt_accommodations_post_type->get_accommodation_vacancy($edit);
		}
		
		if (isset($_POST['accommodations_select'])) {
			$accommodation_id = wp_kses($_POST['accommodations_select'], '');
		} else if ($vacancy_object) {
			$accommodation_id = $vacancy_object->accommodation_id;
		}
		
		if ($accommodation_id) {
			$accommodation_obj = new byt_accommodation(intval($accommodation_id));
			$accommodation_id = $accommodation_obj->get_base_id();
			$is_self_catered = $accommodation_obj->get_is_self_catered();
			$is_price_per_person = $accommodation_obj->get_is_price_per_person();
		}
		
		$room_type_id = 0;
		if (isset($_POST['room_types_select'])) {
			$room_type_id = wp_kses($_POST['room_types_select'], '');
		} else if ($vacancy_object) {
			$room_type_id = $vacancy_object->room_type_id;
		}
		
		if (!empty($room_type_id)) {
			$room_type_id = BYT_Theme_Utils::get_default_language_post_id($room_type_id, 'room_type');
		}		
		
		$accommodations_select = '<select id="accommodations_select" name="accommodations_select">';
		$accommodations_select .= '<option value="">' . __('Select accommodation', 'bookyourtravel') . '</option>';

		$author_id = null;
		if (!is_super_admin()) {
			$author_id = get_current_user_id();
		}
		
		$accommodation_results = $byt_accommodations_post_type->list_accommodations(0, -1, 'title', 'ASC', 0, array(), array(), array(), false, null, $author_id);
		if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) {
			foreach ($accommodation_results['results'] as $accommodation_result) {
				global $post;				
				$post = $accommodation_result;
				setup_postdata( $post ); 			
				$accommodations_select .= '<option value="' . $post->ID . '" ' . ($post->ID == $accommodation_id ? 'selected' : '') . '>' . $post->post_title . '</option>';
			}
		}
		$accommodations_select .= '</select>';
		
		$room_types_select = '';
		if (!$is_self_catered) {
			$room_types_select = '<select class="normal" id="room_types_select" name="room_types_select">';
			$room_types_select .= '<option value="">' . __('Select room type', 'bookyourtravel') . '</option>';
			
			if ($accommodation_obj) { 				
				$room_type_ids = $accommodation_obj->get_room_types();				
				if ($room_type_ids && count($room_type_ids) > 0) {
					for ( $i = 0; $i < count($room_type_ids); $i++ ) {
						$temp_id = $room_type_ids[$i];
						$room_type_obj = new byt_room_type(intval($temp_id));
						$room_types_select .= '<option value="' . $temp_id . '" ' . ($temp_id == $room_type_id ? 'selected' : '') . '>' . $room_type_obj->get_title() . '</option>';
					}
				}
			}
			
			$room_types_select .= '</select>';
		}
		
		wp_reset_postdata();
		
		$date_from = null;
		if (isset($_POST['date_from'])) {
			$date_from =  wp_kses($_POST['date_from'], '');
		} else if ($vacancy_object) {
			$date_from = $vacancy_object->start_date;
		}
		if (isset($date_from))
			$date_from = date( $this->date_format, strtotime( $date_from ) );
			
		$date_to = null;
		if (isset($_POST['date_to'])) {
			$date_to =  wp_kses($_POST['date_to'], '');
		} else if ($vacancy_object) {
			$date_to = $vacancy_object->end_date;
		}
		if (isset($date_to))
			$date_to = date( $this->date_format, strtotime( $date_to ) );

		$room_count = 1;
		if (isset($_POST['room_count'])) {
			$room_count = intval(wp_kses($_POST['room_count'], ''));
		} else if ($vacancy_object && isset($vacancy_object->room_count)) {
			$room_count = $vacancy_object->room_count;
		}
		if ($room_count == 0) 
			$room_count = 1;

		$price_per_day = 0;
		if (isset($_POST['price_per_day'])) {
			$price_per_day = floatval(wp_kses($_POST['price_per_day'], ''));
		} else if ($vacancy_object) {
			$price_per_day = $vacancy_object->price_per_day;
		}

		$price_per_day_child = 0;
		if (isset($_POST['price_per_day_child'])) {
			$price_per_day_child = floatval(wp_kses($_POST['price_per_day_child'], ''));
		} else if ($vacancy_object) {
			$price_per_day_child = $vacancy_object->price_per_day_child;
		}
		
		$season_name = '';
		if (isset($_POST['season_name'])) {
			$season_name = stripslashes(wp_kses($_POST['season_name'], ''));
		} else if ($vacancy_object) {
			$season_name = stripslashes($vacancy_object->season_name);
		}
		
		if ($vacancy_object)
			echo '<h3>' . __('Update Vacancy', 'bookyourtravel') . '</h3>';
		else
			echo '<h3>' . __('Add Vacancy', 'bookyourtravel') . '</h3>';
		
		echo '<form id="accommodation_vacancy_form" method="post" action="' . esc_url($_SERVER['REQUEST_URI']) . '" style="clear: both;">';
		
		echo wp_nonce_field('accommodation_vacancy_nonce');	
				
		echo '<table cellpadding="3" class="form-table"><tbody>';
				
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Season name', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="season_name" id="season_name" value="' . $season_name . '" /></td>';
		echo '</tr>';
				
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Select accommodation', 'bookyourtravel') . '</th>';
		echo '	<td>' . $accommodations_select . '</td>';
		echo '</tr>';
		
		if (!$is_self_catered) {
			echo '<tr id="room_types_row">';
			echo '	<th scope="row" valign="top">' . __('Select room type', 'bookyourtravel') . '</th>';
			echo '	<td>' . $room_types_select . '</td>';
			echo '</tr>';
			echo '<tr id="room_count_row">';
			echo '	<th scope="row" valign="top">' . __('Number of rooms', 'bookyourtravel') . '</th>';
			echo '	<td><input type="text" name="room_count" id="room_count" value="' . $room_count . '" /></td>';
			echo '</tr>';
		}
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Date from', 'bookyourtravel') . '</th>';
		echo '	<td>';
		echo '		<script>';
		echo '			window.datepickerDateFromValue = "' . $date_from . '";';
		echo '  	</script>';				
		echo '  	<input class="datepicker" type="text" name="datepicker_date_from" id="datepicker_date_from" />';
		echo '		<input type="hidden" name="date_from" id="date_from" />';
		echo '	</td>';	
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Date to', 'bookyourtravel') . '</th>';
		echo '	<td>';
		echo '		<script>';
		echo '			window.datepickerDateToValue = "' . $date_to . '";';
		echo '  	</script>';				
		echo '  	<input class="datepicker" type="text" name="datepicker_date_to" id="datepicker_date_to" />';
		echo '		<input type="hidden" name="date_to" id="date_to" />';
		echo '	</td>';	
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Price per day', 'bookyourtravel') . ' <span class="per_person" ' . ($is_price_per_person ? '' : 'style="display:none"') . '>' . __('(adult)', 'bookyourtravel') . '</span></th>';
		echo '	<td><input type="text" name="price_per_day" id="price_per_day" value="' . $price_per_day . '" /></td>';
		echo '</tr>';
	
		echo '<tr class="per_person" ' . ($is_price_per_person ? '' : 'style="display:none"') . '>';
		echo '	<th scope="row" valign="top">' . __('Price per day (child)', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="price_per_day_child" id="price_per_day_child" value="' . $price_per_day_child . '" /></td>';
		echo '</tr>';

		echo '</table>';
		echo '<p>';
		echo '<a href="edit.php?post_type=accommodation&page=theme_accommodation_vacancy_admin.php" class="button-secondary">' . __('Cancel', 'bookyourtravel') . '</a>&nbsp;';

		if ($vacancy_object) {
			echo '<input id="vacancy_id" name="vacancy_id" value="' . $edit . '" type="hidden" />';
			echo '<input class="button-primary" type="submit" name="update" value="' . __('Update Vacancy', 'bookyourtravel') . '"/>';
		} else {
			echo '<input class="button-primary" type="submit" name="insert" value="' . __('Add Vacancy', 'bookyourtravel') . '"/>';
		}
		echo '</p>';
		echo '</form>';
	}
	
}