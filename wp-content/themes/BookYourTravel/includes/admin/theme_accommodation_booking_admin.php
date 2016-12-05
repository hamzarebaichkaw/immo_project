<?php

/*
*******************************************************************************
************************** LOAD THE BASE CLASS *******************************
*******************************************************************************
* The WP_List_Table class isn't automatically available to plugins, 
* so we need to check if it's available and load it if necessary.
*/
 
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class BYT_Accommodation_Bookings_Admin extends BYT_BaseSingleton {
	
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

			add_action( 'admin_menu' , array( $this, 'bookings_admin_page' ) );
			add_filter( 'set-screen-option', array( $this, 'bookings_set_screen_options' ), 10, 3);
			add_action( 'admin_head', array( $this, 'bookings_admin_head' ) );
		}
	}
	
	function bookings_admin_page() {

		$hook = add_submenu_page('edit.php?post_type=accommodation', __('BYT Accommodation Bookings', 'bookyourtravel'), __('Bookings', 'bookyourtravel'), 'edit_posts', basename(__FILE__), array( $this, 'bookings_admin_display') );
		add_action( "load-$hook", array( $this, 'bookings_add_screen_options') );
	}
	
	function bookings_set_screen_options($status, $option, $value) {
		if ( 'bookings_per_page' == $option ) 
			return $value;
	}
	
	function bookings_admin_head() {
		$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if( 'theme_accommodation_booking_admin.php' != $page )
			return;

		$this->bookings_admin_styles();
	}
	
	function bookings_admin_styles() {

		echo '<style type="text/css">';
		echo '.wp-list-table .column-Id { width: 100px; }';
		echo '.wp-list-table .column-AccommodationName { width: 250px; }';
		echo '.wp-list-table .column-VacancyDay { width: 150px; }';
		echo '.wp-list-table .column-UserId { width: 50px; }';
		echo '.wp-list-table .column-RoomType { width: 150px; }';
		echo '.wp-list-table .column-Rooms { width: 70px; }';
		echo '</style>';
		
		echo '<script>';
		echo 'window.adminAjaxUrl = "' . admin_url('admin-ajax.php') . '";';
		echo 'window.datepickerDateFormat = "' . BYT_Theme_Utils::dateformat_PHP_to_jQueryUI(get_option('date_format')) . '";';
		echo 'window.datepickerAltFormat = "' . BOOKYOURTRAVEL_ALT_DATE_FORMAT . '";';
		echo '</script>';
	}
		
	function bookings_add_screen_options() {
	
		global $wp_accommodation_bookings_table;
		$option = 'per_page';
		$args = array('label' => __('Bookings', 'bookyourtravel'),'default' => 50,'option' => 'bookings_per_page');
		add_screen_option( $option, $args );
		$wp_accommodation_bookings_table = new Accommodation_Bookings_Admin_List_Table();
	}

	function bookings_admin_display() {
	
		echo '</pre><div class="wrap">';
		echo __('<h2>BYT Accommodation bookings</h2>', 'bookyourtravel');
		
		global $wp_accommodation_bookings_table;
		$booking_id = $wp_accommodation_bookings_table->handle_form_submit();
		
		if (isset($_GET['view'])) {
			$wp_accommodation_bookings_table->render_view_form(); 
		} else if (isset($_GET['sub']) && $_GET['sub'] == 'manage') {
			$wp_accommodation_bookings_table->render_entry_form($booking_id); 
		} else {	
			$wp_accommodation_bookings_table->prepare_items(); 
			
		if (!empty($_REQUEST['s']))
			$form_uri = esc_url( add_query_arg( 's', $_REQUEST['s'], $_SERVER['REQUEST_URI'] ));
		else 
			$form_uri = esc_url($_SERVER['REQUEST_URI']);	
		?>
		<div class="tablenav top">	
			<div class="alignleft actions">
				<a href="edit.php?post_type=accommodation&page=theme_accommodation_booking_admin.php&sub=manage" class="button-secondary action" ><?php _e('Add Booking', 'bookyourtravel') ?></a>
			</div>
		</div>
		<form method="get" action="<?php echo esc_url($form_uri); ?>">
			<input type="hidden" name="paged" value="1">
			<input type="hidden" name="post_type" value="accommodation">
			<input type="hidden" name="page" value="theme_accommodation_booking_admin.php">
			<?php
			$wp_accommodation_bookings_table->search_box( 'search', 'search_id' );
			?>
		</form>
		<?php $wp_accommodation_bookings_table->display(); ?>
		<div class="tablenav bottom">	
			<div class="alignleft actions">
				<a href="edit.php?post_type=accommodation&page=theme_accommodation_booking_admin.php&sub=manage" class="button-secondary action" ><?php _e('Add Booking', 'bookyourtravel') ?></a>
			</div>
		</div>
		<?php
		} 
	}
}

global $accommodation_bookings_admin;
$accommodation_bookings_admin = BYT_Accommodation_Bookings_Admin::get_instance();

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
class Accommodation_Bookings_Admin_List_Table extends WP_List_Table {

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
			'singular'=> 'booking', // Singular label
			'plural' => 'bookings', // plural label, also this well be one of the table css class
			'ajax'	=> false // We won't support Ajax for this table
		) );
		
	}	

	function column_default( $item, $column_name ) {
		return $item->$column_name;
	}	
	
	function extra_tablenav( $which ) {
		if ( $which == "top" ){	
			//The code that goes before the table is here
		}
		if ( $which == "bottom" ){
			//The code that goes after the table is there
		}
	}		
	
	function column_Customer($item) {
		return $item->first_name . ' ' . $item->last_name;	
	}
	
	function column_Accommodation($item) {
		return $item->accommodation_name . (isset($item->room_type) ? '<br />' . $item->room_type : '');	
	}
	
	function column_Details($item) {
		return	__('Adults', 'bookyourtravel') . ' ' . $item->adults . '<br />' .
				__('Children', 'bookyourtravel') . ' ' . $item->children . '<br />' . 
				__('Total price', 'bookyourtravel') . ' ' . $item->total_price . '<br />';
	}
	
	function column_DateFrom($item) {
		return date($this->date_format, strtotime($item->date_from));	
	}
	
	function column_DateTo($item) {
		return date($this->date_format, strtotime($item->date_to));	
	}
	
	function column_Created($item) {
		return date($this->date_format, strtotime($item->created) );	
	}
	
	function column_Action($item) {
		return  "<a href='edit.php?post_type=accommodation&page=theme_accommodation_booking_admin.php&sub=manage&edit=" . $item->Id . "'>" . __('Edit', 'bookyourtravel') . "</a> | 		
				<form method='post' name='delete_booking_" . $item->Id . "' id='delete_booking_" . $item->Id . "' style='display:inline;'>
					<input type='hidden' name='delete_booking' id='delete_booking' value='" . $item->Id . "' />
					<a href='javascript: void(0);' onclick='confirmDelete(\"#delete_booking_" . $item->Id . "\", \"" . __('Are you sure?', 'bookyourtravel') . "\");'>" . __('Delete', 'bookyourtravel') . "</a>
				</form>";
	}	
	
	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
		return $columns= array(
			'Id'=>__('Id', 'bookyourtravel'),
			'Customer'=>__('Customer', 'bookyourtravel'),
			'DateFrom'=>__('From', 'bookyourtravel'),
			'DateTo'=>__('To', 'bookyourtravel'),
			'Accommodation'=>__('Accommodation', 'bookyourtravel'),
			'Details'=>__('Details', 'bookyourtravel'),
			'Created'=>__('Created', 'bookyourtravel'),
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
			'Accommodation'=> array( 'accommodations.post_title', true ),
			'Details'=> array( 'total_price', true ),
			'DateFrom'=> array( 'date_from', true ),
			'DateTo'=> array( 'date_to', true ),
		);
		return $sortable_columns;
	}	
	
	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
	
		global $byt_accommodations_post_type, $byt_room_types_post_type;
		global $_wp_column_headers;
		
		$screen = get_current_screen();
		$user = get_current_user_id();
		$option = $screen->get_option('per_page', 'option');
		$per_page = get_user_meta($user, $option, true);
		if ( empty ( $per_page) || $per_page < 1 ) {
			$per_page = $screen->get_option( 'per_page', 'default' );
		}	

		$search_term = '';
		if (!empty($_REQUEST['s'])) {
			$search_term = wp_kses(strtolower($_REQUEST['s']), '');
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
		//How many pages do we have in total?

		$author_id = null;
		if (!is_super_admin()) {
			$author_id = get_current_user_id();
		}
		
		$accommodation_booking_results = $byt_accommodations_post_type->list_accommodation_bookings($paged, $per_page, $orderby, $order, $search_term, 0, $author_id);		
		//Number of elements in your table?
		$totalitems = $accommodation_booking_results['total']; //return the total number of affected rows

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
		$this->items = $accommodation_booking_results['results'];
	}
	
	function handle_form_submit() {
		
		global $byt_accommodations_post_type, $byt_room_types_post_type;
		
		if (isset($_POST['delete_booking'])) {
		
			$booking_id = absint($_POST['delete_booking']);
			
			$byt_accommodations_post_type->delete_accommodation_booking($booking_id);
			
			echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
			echo '<p>' . __('Successfully deleted booking!', 'bookyourtravel') . '</p>';
			echo '</div>';
		} else if (isset($_POST['insert']) || isset($_POST['update'])) {
		
			$booking_id = isset($_POST['booking_id']) ? wp_kses($_POST['booking_id'], '') : 0;

			$accommodation_id = isset($_POST['accommodation_id']) ? wp_kses($_POST['accommodation_id'], '') : 0;	
			$accommodation_obj = new byt_accommodation((int)$accommodation_id);
			$is_self_catered = $accommodation_obj->get_is_self_catered();
			
			$room_type_id = isset($_POST['room_type_id']) ? wp_kses($_POST['room_type_id'], '') : 0;	
			$user_id = get_current_user_id();		
			
			$first_name =  wp_kses($_POST['first_name'], '');
			$last_name =  wp_kses($_POST['last_name'], '');
			$email =  wp_kses($_POST['email'], '');
			$phone =  wp_kses($_POST['phone'], '');
			$address =  wp_kses($_POST['address'], '');
			$town =  wp_kses($_POST['town'], '');
			$zip =  wp_kses($_POST['zip'], '');
			$country =  wp_kses($_POST['country'], '');
			$special_requirements =  wp_kses($_POST['special_requirements'], '');
			
			$room_count = intval(wp_kses($_POST['room_count'], ''));
			$adults = intval(wp_kses($_POST['adults'], ''));
			$children = intval(wp_kses($_POST['children'], ''));
			$total_price = floatval(wp_kses($_POST['total_price'], ''));

			$date_from = wp_kses($_POST['date_from'], '');
			// $date_from = date($this->date_format, strtotime($date_from));
			
			$date_to = wp_kses($_POST['date_to'], '');
			// $date_to = date($this->date_format, strtotime($date_to));
			
			if (isset($_POST['insert']) && check_admin_referer('accommodation_booking_nonce')) {
				
				$booking_id = $byt_accommodations_post_type->create_accommodation_booking( $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $date_from, $date_to, $accommodation_id, $room_type_id, $user_id, $is_self_catered, $total_price, $adults, $children);
				
				echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
				echo '<p>' . __('Successfully inserted new accommodation booking entry!', 'bookyourtravel') . '</p>';
				echo '</div>';
				
			} else if (isset($_POST['update']) && check_admin_referer('accommodation_booking_nonce')) {
				
				$byt_accommodations_post_type->update_accommodation_booking ($booking_id, $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $room_count, $date_from, $date_to, $accommodation_id, $room_type_id, $user_id, $is_self_catered, $total_price, $adults, $children);

				echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
				echo '<p>' . __('Successfully updated accommodation booking entry!', 'bookyourtravel') . '</p>';
				echo '</div>';
				
			}
			
			return $booking_id;
		}		
	}
			
	function render_entry_form($booking_id) {
		
		global $byt_accommodations_post_type, $byt_room_types_post_type;
		$booking_object = null;
		
		$edit = isset($_GET['edit']) ? absint($_GET['edit']) : 0;
		if ($booking_id > 0)
			$edit = $booking_id;
		
		if (!empty($edit)) {
			$booking_object = $byt_accommodations_post_type->get_accommodation_booking($edit);
		}
		
		$date_from = null;
		if (isset($_POST['date_from']))
			$date_from = wp_kses($_POST['date_from'], '');
		else if ($booking_object != null) {
			$date_from = $booking_object->date_from;
		}
		if (isset($date_from))
			$date_from = date( $this->date_format, strtotime( $date_from ) );
		
		$date_to = null;
		if (isset($_POST['date_to']))
			$date_to = wp_kses($_POST['date_to'], '');
		else if ($booking_object != null) {
			$date_to = $booking_object->date_to;
		}
		if (isset($date_to))
			$date_to = date( $this->date_format, strtotime( $date_to ) );
		
		$first_name = '';
		if (isset($_POST['first_name']))
			$first_name = wp_kses($_POST['first_name'], '');
		else if ($booking_object != null) {
			$first_name = $booking_object->first_name;
		}
		
		$last_name = '';
		if (isset($_POST['last_name']))
			$last_name = wp_kses($_POST['last_name'], '');
		else if ($booking_object != null) {
			$last_name = $booking_object->last_name;
		}
		
		$email = '';
		if (isset($_POST['email']))
			$email = wp_kses($_POST['email'], '');
		else if ($booking_object != null) {
			$email = $booking_object->email;
		}
		
		$phone = '';
		if (isset($_POST['phone']))
			$phone = wp_kses($_POST['phone'], '');
		else if ($booking_object != null) {
			$phone = $booking_object->phone;
		}
		
		$address = '';
		if (isset($_POST['address']))
			$address = wp_kses($_POST['address'], '');
		else if ($booking_object != null) {
			$address = $booking_object->address;
		}
		
		$town = '';
		if (isset($_POST['town']))
			$town = wp_kses($_POST['town'], '');
		else if ($booking_object != null) {
			$town = $booking_object->town;
		}
		
		$zip = '';
		if (isset($_POST['zip']))
			$zip = wp_kses($_POST['zip'], '');
		else if ($booking_object != null) {
			$zip = $booking_object->zip;
		}		
		
		$country = '';
		if (isset($_POST['country']))
			$country = wp_kses($_POST['country'], '');
		else if ($booking_object != null) {
			$country = $booking_object->country;
		}
		
		$special_requirements = '';
		if (isset($_POST['special_requirements']))
			$special_requirements = wp_kses($_POST['special_requirements'], '');
		else if ($booking_object != null) {
			$special_requirements = $booking_object->special_requirements;
		}
		
		$adults = 0;
		if (isset($_POST['adults']))
			$adults = intval(wp_kses($_POST['adults'], ''));
		else if ($booking_object != null) {
			$adults = $booking_object->adults;
		}
		
		$children = 0;
		if (isset($_POST['children']))
			$children = intval(wp_kses($_POST['children'], ''));
		else if ($booking_object != null) {
			$children = $booking_object->children;
		}
		
		$total_price = 0;
		if (isset($_POST['total_price']))
			$total_price = floatval(wp_kses($_POST['total_price'], ''));
		else if ($booking_object != null) {
			$total_price = $booking_object->total_price;
		}
		
		$room_count = 0;
		if (isset($_POST['room_count']))
			$room_count = intval(wp_kses($_POST['room_count'], ''));
		else if ($booking_object != null) {
			$room_count = $booking_object->room_count;
		}
		
		$accommodation_id = 0;
		if (isset($_GET['accommodation_id'])) {
			$accommodation_id = absint($_GET['accommodation_id']);
		} else if (isset($_POST['accommodation_id'])) {
			$accommodation_id = intval(wp_kses($_POST['accommodation_id'], ''));
		} else if ($booking_object != null) {
			$accommodation_id = $booking_object->accommodation_id;
		}
		
		$room_type_id = 0;
		if (isset($_GET['room_type_id'])) {
			$room_type_id = absint($_GET['room_type_id']);
		} else if (isset($_POST['room_type_id'])) {
			$room_type_id = intval(wp_kses($_POST['room_type_id'], ''));
		} else if ($booking_object != null) {
			$room_type_id = $booking_object->room_type_id;
		}
		
		if ($booking_object)
			echo '<h3>' . __('Update Accommodation Booking', 'bookyourtravel') . '</h3>';
		else
			echo '<h3>' . __('Add Accommodation Booking', 'bookyourtravel') . '</h3>';

		echo '<form id="accommodation_booking_form" method="post" action="' . esc_url($_SERVER['REQUEST_URI']) . '" style="clear: both;">';
		
		echo wp_nonce_field('accommodation_booking_nonce');	
		
		echo '<table cellpadding="3" class="form-table"><tbody>';
		
		$accommodations_select = '<select id="accommodation_id" name="accommodation_id" onchange="accommodationBookingAccommodationFilterRedirect(' . $edit . ',this.value)">';
		$accommodations_select .= '<option value="">' . __('Select accommodation', 'bookyourtravel') . '</option>';
		
		$author_id = null;
		if (!is_super_admin()) {
			$author_id = get_current_user_id();
		}
		$accommodation_results = $byt_accommodations_post_type->list_accommodations(0, -1, 'title', 'ASC', 0, array(), array(), array(), false, null, $author_id);
		if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) {
			foreach ($accommodation_results['results'] as $accommodation_result) {
				$accommodations_select .= '<option value="' . $accommodation_result->ID . '" ' . ($accommodation_result->ID == $accommodation_id ? 'selected' : '') . '>' . $accommodation_result->post_title . '</option>';
			}
		}
		$accommodations_select .= '</select>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Select accommodation enry', 'bookyourtravel') . '</th>';
		echo '	<td>' . $accommodations_select . '</td>';
		echo '</tr>';
		
		if ($accommodation_id > 0) {
		
			$accommodation_obj = new byt_accommodation((int)$accommodation_id);
			$is_self_catered = $accommodation_obj->get_is_self_catered();
		
			if (!$is_self_catered) {
			
				$room_types_select = '<select id="room_type_id" name="room_type_id">';
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
				
				echo '<tr>';
				echo '	<th scope="row" valign="top">' . __('Select room type entry', 'bookyourtravel') . '</th>';
				echo '	<td>' . $room_types_select . '</td>';
				echo '</tr>';
				
			}
		}
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Date from', 'bookyourtravel') . '</th>';
		echo '	<td>';
		echo '		<script>';
		echo '			window.datepickerDateFromValue = "' . (isset($date_from) ? $date_from : null) . '";';
		echo '  	</script>';				
		echo '  	<input class="datepicker" type="text" name="datepicker_date_from" id="datepicker_date_from" />';
		echo '		<input type="hidden" name="date_from" id="date_from" />';
		echo '	</td>';	
		echo '</tr>';

		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Date to', 'bookyourtravel') . '</th>';
		echo '	<td>';
		echo '		<script>';
		echo '			window.datepickerDateToValue = "' . (isset($date_to) ? $date_to : null) . '";';
		echo '  	</script>';				
		echo '  	<input class="datepicker" type="text" name="datepicker_date_to" id="datepicker_date_to" />';
		echo '		<input type="hidden" name="date_to" id="date_to" />';
		echo '	</td>';	
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('First name', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="first_name" id="first_name" value="' . $first_name . '" /></td>';
		echo '</tr>';

		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Last name', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="last_name" id="last_name" value="' . $last_name . '" /></td>';
		echo '</tr>';

		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Email', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="email" id="email" value="' . $email . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Phone', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="phone" id="phone" value="' . $phone . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Address', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="address" id="address" value="' . $address . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Town', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="town" id="town" value="' . $town . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Zip', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="zip" id="zip" value="' . $zip . '" /></td>';
		echo '</tr>';

		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Country', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="country" id="country" value="' . $country . '" /></td>';
		echo '</tr>';

		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Special requirements', 'bookyourtravel') . '</th>';
		echo '	<td><textarea type="text" name="special_requirements" id="special_requirements" rows="5" cols="50">' . $special_requirements . '</textarea></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Number of adults', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="adults" id="adults" value="' . $adults . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Number of children', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="children" id="children" value="' . $children . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Number of rooms', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="room_count" id="room_count" value="' . $room_count . '" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Total price', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="total_price" id="total_price" value="' . $total_price . '" /></td>';
		echo '</tr>';
		
		echo '</table>';
		echo '<p>';
		echo '<a href="edit.php?post_type=accommodation&page=theme_accommodation_booking_admin.php" class="button-secondary">' . __('Cancel', 'bookyourtravel') . '</a>&nbsp;';

		if ($booking_object) {
			echo '<input id="booking_id" name="booking_id" value="' . $edit . '" type="hidden" />';
			echo '<input class="button-primary" type="submit" name="update" value="' . __('Update Booking', 'bookyourtravel') . '"/>';
		} else {
			if ($accommodation_id > 0) {
				echo '<input class="button-primary" type="submit" name="insert" value="' . __('Add Booking', 'bookyourtravel') . '"/>';
			}
		}
		echo '</p>';
		echo '</form>';		
	}
}