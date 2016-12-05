<?php

/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary.
 */
 
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class BYT_Car_Rental_Bookings_Admin extends BYT_BaseSingleton {
	
	private $enable_car_rentals;
	
	protected function __construct() {
	
		global $byt_theme_globals;
		
		$this->enable_car_rentals = $byt_theme_globals->enable_car_rentals();

        // our parent class might
        // contain shared code in its constructor
        parent::__construct();
	}

    public function init() {
		
		if ($this->enable_car_rentals) {	

			add_action( 'admin_menu' , array( $this, 'bookings_admin_page' ) );
			add_filter( 'set-screen-option', array( $this, 'bookings_set_screen_options' ), 10, 3);
			add_action( 'admin_head', array( $this, 'bookings_admin_head' ) );
		}
	}

	function bookings_admin_page() {
		$hook = add_submenu_page('edit.php?post_type=car_rental', __('BYT Car Rental Bookings', 'bookyourtravel'), __('Bookings', 'bookyourtravel'), 'edit_posts', basename(__FILE__), array($this, 'bookings_admin_display' ));

		add_action( "load-$hook", array($this, 'bookings_add_screen_options'));
	}

	function bookings_set_screen_options($status, $option, $value) {
		if ( 'car_rental_bookings_per_page' == $option ) 
			return $value;
	}

	function bookings_admin_head() {
		$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if( 'theme_car_rental_booking_admin.php' != $page )
			return;

		$this->bookings_admin_styles();
	}

	function bookings_admin_styles() {

		echo '<style type="text/css">';
		echo '.wp-list-table .column-Id { width: 100px; }';
		echo '.wp-list-table .column-CarRentalName { width: 250px; }';
		echo '.wp-list-table .column-PickUp { width: 150px; }';
		echo '.wp-list-table .column-DropOff { width: 150px; }';
		echo '.wp-list-table .column-UserId { width: 50px; }';
		echo '.wp-list-table .column-FromDay { width: 100px; }';
		echo '.wp-list-table .column-ToDay { width: 100px; }';
		echo '</style>';
			
		echo '<script>';
		echo 'window.adminAjaxUrl = "' . admin_url('admin-ajax.php') . '";';
		echo 'window.datepickerDateFormat = "' . BYT_Theme_Utils::dateformat_PHP_to_jQueryUI(get_option('date_format')) . '";';
		echo 'window.datepickerAltFormat = "' . BOOKYOURTRAVEL_ALT_DATE_FORMAT . '";';
		echo '</script>';
	}

	function bookings_add_screen_options() {
		global $wp_car_rental_booking_table;
		$option = 'per_page';
		$args = array('label' => 'Bookings','default' => 50,'option' => 'car_rental_bookings_per_page');
		add_screen_option( $option, $args );
		$wp_car_rental_booking_table = new Car_Rental_Booking_Admin_List_Table();
	}

	function bookings_admin_display() {
		echo '</pre><div class="wrap">';
		echo __('<h2>BYT Car Rental bookings</h2>', 'bookyourtravel');
		
		global $wp_car_rental_booking_table;
		
		$booking_id = $wp_car_rental_booking_table->handle_form_submit();
		
		if (isset($_GET['view'])) {
			$wp_car_rental_booking_table->render_view_form(); 
		} else if (isset($_GET['sub']) && $_GET['sub'] == 'manage') {
			$wp_car_rental_booking_table->render_entry_form($booking_id); 
		} else {	
			$wp_car_rental_booking_table->prepare_items(); 
			
		if (!empty($_REQUEST['s']))
			$form_uri = esc_url( add_query_arg( 's', $_REQUEST['s'], $_SERVER['REQUEST_URI'] ));
		else 
			$form_uri = esc_url($_SERVER['REQUEST_URI']);	
		?>
		
		<div class="tablenav bottom">	
			<div class="alignleft actions">
				<a href="edit.php?post_type=car_rental&page=theme_car_rental_booking_admin.php&sub=manage" class="button-secondary action" ><?php _e('Add booking', 'bookyourtravel') ?></a>
			</div>
		</div>
			<form method="get" action="<?php echo esc_url($form_uri); ?>">
				<input type="hidden" name="paged" value="1">
				<input type="hidden" name="post_type" value="car_rental">
				<input type="hidden" name="page" value="theme_car_rental_booking_admin.php">
				<?php
				$wp_car_rental_booking_table->search_box( 'search', 'search_id' );
				?>
			</form>
			
		<?php 		
			$wp_car_rental_booking_table->display();		
		?>
		<div class="tablenav bottom">	
			<div class="alignleft actions">
				<a href="edit.php?post_type=car_rental&page=theme_car_rental_booking_admin.php&sub=manage" class="button-secondary action" ><?php _e('Add booking', 'bookyourtravel') ?></a>
			</div>
		</div>
		<?php		
		} 
	}
}

global $car_rental_bookings_admin;
$car_rental_bookings_admin = BYT_Car_Rental_Bookings_Admin::get_instance();

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
class Car_Rental_Booking_Admin_List_Table extends WP_List_Table {

	private $options;
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
	
	function column_CarRentalName($item) {
		return $item->car_rental_name;	
	}
	
	function column_TotalPrice($item) {
		return $item->currency_code . ' ' . $item->total_price;	
	}
	
	function column_FromDay($item) {
		return date($this->date_format, strtotime($item->from_day));	
	}
	
	function column_ToDay($item) {
		return date($this->date_format, strtotime($item->to_day.' +1 day'));	
	}
	
	function column_Created($item) {
		return date($this->date_format, strtotime($item->created));
	}
	
	function column_PickUpDropOff($item) {
		return $item->pick_up . ' / ' . $item->drop_off;	
	}
	
	function column_Action($item) {
		return "<a href='edit.php?post_type=car_rental&page=theme_car_rental_booking_admin.php&view=" . $item->Id . "'>" . __('View', 'bookyourtravel') . "</a> | 
				<a href='edit.php?post_type=car_rental&page=theme_car_rental_booking_admin.php&sub=manage&edit=" . $item->Id . "'>" . __('Edit', 'bookyourtravel') . "</a> | 
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
			'FromDay'=>__('From', 'bookyourtravel'),
			'ToDay'=>__('To', 'bookyourtravel'),
			'PickUpDropOff'=>__('Pick Up / Drop Off', 'bookyourtravel'),
			'CarRentalName'=>__('Car Rental Name', 'bookyourtravel'),
			'TotalPrice'=>__('Total Price', 'bookyourtravel'),
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
			'CarRentalName'=> array( 'car_rental_name', true ),
			'FromDay'=> array( 'from_day', true ),
			'ToDay'=> array( 'to_day', true ),
		);
		return $sortable_columns;
	}	
	
	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
		global $_wp_column_headers, $byt_car_rentals_post_type;
		
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
		
		$car_rental_booking_results = $byt_car_rentals_post_type->list_car_rental_bookings('', $orderby, $order, $paged, $per_page, $author_id );
		//Number of elements in your table?
		$totalitems = $car_rental_booking_results['total']; //return the total number of affected rows

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
		$this->items = $car_rental_booking_results['results'];
	}
	
	function handle_form_submit() {
	
		global $byt_car_rentals_post_type;
		
		if (isset($_POST['delete_booking'])) {
			$booking_id = absint($_POST['delete_booking']);
			
			$byt_car_rentals_post_type->delete_car_rental_booking($booking_id);
			
			echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
			echo '<p>' . __('Successfully deleted booking!', 'bookyourtravel') . '</p>';
			echo '</div>';
		} else if (isset($_POST['insert']) || isset($_POST['update'])) {
		
			$booking_id = isset($_POST['booking_id']) ? wp_kses($_POST['booking_id'], '') : 0;
			$car_rental_id = wp_kses($_POST['car_rental_id'], '');		
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
			$drop_off_location_id = intval(wp_kses($_POST['drop_off_location_id'], ''));
			$total_price = floatval(wp_kses($_POST['total_price'], '2'));
			
			$from_day = wp_kses($_POST['from_day'], '');
			$from_day = date('Y-m-d', strtotime($from_day));
			
			$to_day = wp_kses($_POST['to_day'], '');
			$to_day = date('Y-m-d', strtotime($to_day));

			if (isset($_POST['insert']) && check_admin_referer('car_rental_booking_entry_form_nonce')) {
				
				$booking_id = $byt_car_rentals_post_type->create_car_rental_booking( $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $from_day, $to_day, $car_rental_id, $user_id, $total_price, $drop_off_location_id );
				
				echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
				echo '<p>' . __('Successfully inserted new car rental booking entry!', 'bookyourtravel') . '</p>';
				echo '</div>';
				
			} else if (isset($_POST['update']) && check_admin_referer('car_rental_booking_entry_form_nonce')) {
				
				$byt_car_rentals_post_type->update_car_rental_booking($booking_id,  $first_name, $last_name, $email, $phone, $address, $town, $zip, $country, $special_requirements, $from_day, $to_day, $car_rental_id, $user_id, $total_price, $drop_off_location_id );

				echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
				echo '<p>' . __('Successfully updated car rental booking entry!', 'bookyourtravel') . '</p>';
				echo '</div>';				
			}
			
			return $booking_id;		
		}		
	}
	
	function render_view_form() {
	
		global $byt_car_rentals_post_type;

		$booking_id = isset($_GET['view']) ? intval($_GET['view']) : 0;
		if ($booking_id > 0) {

			$booking = $byt_car_rentals_post_type->get_car_rental_booking($booking_id);
			
			if ($booking != null) {
			
				$car_type = null;
				$car_type_obj = wp_get_object_terms($booking->car_rental_id, 'car_type');
				if ($car_type_obj)
					$car_type = $car_type_obj[0];
					
				echo "<p><h3>" . __('View car rental booking', 'bookyourtravel') . "</h3></p>";
				echo "<table cellpadding='3' cellspacing='3' class='form-table'>";
				echo "<tr>";
				echo "<th>" . __('First name', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->first_name . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Last name', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->last_name . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Email', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->email . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Phone', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->phone . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Address', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->address . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Town', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->town . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Zip', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->zip . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Country', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->country . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Special requirements', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->special_requirements . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Car rental name', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->car_rental_name . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Car type', 'bookyourtravel') . "</th>";
				echo "<td>" . $car_type->name . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Pick Up', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->pick_up . "</td>";
				echo "</tr>";				
				echo "<tr>";
				echo "<th>" . __('Drop Off', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->drop_off . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Date from', 'bookyourtravel') . "</th>";
				echo "<td>" . date($this->date_format, strtotime($booking->from_day)) . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Date to', 'bookyourtravel') . "</th>";
				echo "<td>" . date($this->date_format, strtotime($booking->to_day)) . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Total price', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->total_price . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Created at', 'bookyourtravel') . "</th>";
				echo "<td>" . date($this->date_format, strtotime($booking->created)) . "</td>";
				echo "</tr>";
				echo "</table>";
				echo "<p><a href='edit.php?post_type=car_rental&page=theme_car_rental_booking_admin.php'>" . __('&laquo; Go back', 'bookyourtravel') . "</a></p>";
				
			}
		}
	}	

	function render_entry_form($booking_id) {
		
		global $byt_car_rentals_post_type, $byt_locations_post_type;
		$booking_object = null;
		
		$edit = isset($_GET['edit']) ? absint($_GET['edit']) : 0;
		
		if ($booking_id > 0)
			$edit = $booking_id;
			
		if (!empty($edit)) {
			$booking_object = $byt_car_rentals_post_type->get_car_rental_booking($edit);
		}
		
		$from_day = null;
		if (isset($_POST['from_day']))
			$from_day = wp_kses($_POST['from_day'], '');
		else if ($booking_object != null) {
			$from_day = $booking_object->from_day;
		}
		if (isset($from_day))
			$from_day = date( $this->date_format, strtotime( $from_day ) );
		
		$to_day = null;
		if (isset($_POST['to_day']))
			$to_day = wp_kses($_POST['to_day'], '');
		else if ($booking_object != null) {
			$to_day = $booking_object->to_day;
		}
		if (isset($to_day)) {
			if ($booking_object != null)
				$to_day = date( $this->date_format, strtotime( $to_day.' +1 day' ) );
			else
				$to_day = date( $this->date_format, strtotime( $to_day ) );
		}
		
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
		
		$total_price = 0;
		if (isset($_POST['total_price']))
			$total_price = floatval(wp_kses($_POST['total_price'], ''));
		else if ($booking_object != null) {
			$total_price = $booking_object->total_price;
		}
		
		$drop_off_location_id = 0;
		if (isset($_POST['drop_off_location_id']))
			$drop_off_location_id = intval(wp_kses($_POST['drop_off_location_id'], ''));
		else if ($booking_object != null) {
			$drop_off_location_id = $booking_object->drop_off_location_id;
		}
		
		$car_rental_id = 0;
		if (isset($_GET['car_rental_id'])) {
			$car_rental_id = absint($_GET['car_rental_id']);
		} else if (isset($_POST['car_rental_id'])) {
			$car_rental_id = intval(wp_kses($_POST['car_rental_id'], ''));
		} else if ($booking_object != null) {
			$car_rental_id = $booking_object->car_rental_id;
		}		

		if ($booking_object)
			echo '<h3>' . __('Update Car Rental Booking Entry', 'bookyourtravel') . '</h3>';
		else
			echo '<h3>' . __('Add Car Rental Booking Entry', 'bookyourtravel') . '</h3>';

		echo '<form id="car_rental_booking_entry_form" method="post" action="' . esc_url($_SERVER['REQUEST_URI']) . '" style="clear: both;">';
		
		echo wp_nonce_field('car_rental_booking_entry_form_nonce');	
		
		echo '<table cellpadding="3" class="form-table"><tbody>';
		
		$car_rentals_select = '<select id="car_rental_id" name="car_rental_id" onchange="carRentalBookingCarRentalFilterRedirect(' . $edit . ',this.value)">';
		$car_rentals_select .= '<option value="">' . __('Select car rental', 'bookyourtravel') . '</option>';

		$author_id = null;
		if (!is_super_admin()) {
			$author_id = get_current_user_id();
		}
		
		$car_rental_results = $byt_car_rentals_post_type->list_car_rentals(0, -1, 'title', 'ASC', 0, array(), array(), array(), false, $author_id);
		if ( count($car_rental_results) > 0 && $car_rental_results['total'] > 0 ) {
			foreach ($car_rental_results['results'] as $car_rental_result) {
				$car_rentals_select .= '<option value="' . $car_rental_result->ID . '" ' . ($car_rental_result->ID == $car_rental_id ? 'selected' : '') . '>' . $car_rental_result->post_title . '</option>';
			}
		}
		$car_rentals_select .= '</select>';

		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Select car rental', 'bookyourtravel') . '</th>';
		echo '	<td>' . $car_rentals_select . '</td>';
		echo '</tr>';
		
		$locations_select = '<select id="drop_off_location_id" name="drop_off_location_id">';
		$locations_select .= '<option value="">' . __('Select location', 'bookyourtravel') . '</option>';
		$location_results = $byt_locations_post_type->list_locations();
		if ( count($location_results) > 0 && $location_results['total'] > 0 ) {
			foreach ($location_results['results'] as $location_result) {
				$locations_select .= '<option value="' . $location_result->ID . '" ' . ($location_result->ID == $drop_off_location_id ? 'selected' : '') . '>' . $location_result->post_title . '</option>';
			}
		}
		$locations_select .= '</select>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('Select drop-off location', 'bookyourtravel') . '</th>';
		echo '	<td>' . $locations_select . '</td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('From date', 'bookyourtravel') . '</th>';
		echo '	<td>';
		echo '		<script>';
		echo '			window.datepickerFromDayValue = "' . $from_day . '";';
		echo '  	</script>';				
		echo '  	<input class="datepicker" type="text" name="datepicker_from_day" id="datepicker_from_day" />';
		echo '		<input type="hidden" name="from_day" id="from_day" />';
		echo '	</td>';	
		echo '</tr>';
		
		echo '<tr>';
		echo '	<th scope="row" valign="top">' . __('To date', 'bookyourtravel') . '</th>';
		echo '	<td>';
		echo '		<script>';
		echo '			window.datepickerToDayValue = "' . $to_day . '";';
		echo '  	</script>';				
		echo '  	<input class="datepicker" type="text" name="datepicker_to_day" id="datepicker_to_day" />';
		echo '		<input type="hidden" name="to_day" id="to_day" />';
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
		echo '	<th scope="row" valign="top">' . __('Total price', 'bookyourtravel') . '</th>';
		echo '	<td><input type="text" name="total_price" id="total_price" value="' . $total_price . '" /></td>';
		echo '</tr>';
		
		echo '</table>';
		echo '<p>';
		echo '<a href="edit.php?post_type=car_rental&page=theme_car_rental_booking_admin.php" class="button-secondary">' . __('Cancel', 'bookyourtravel') . '</a>&nbsp;';

		if ($booking_object) {
			echo '<input id="booking_id" name="booking_id" value="' . $edit . '" type="hidden" />';
			echo '<input class="button-primary" type="submit" name="update" value="' . __('Update Booking', 'bookyourtravel') . '"/>';
		} else {
			echo '<input class="button-primary" type="submit" name="insert" value="' . __('Add Booking', 'bookyourtravel') . '"/>';
		}
		echo '</p>';
		echo '</form>';
		
	}

}