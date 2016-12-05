<?php
/*
Class Name: Frontend Submit based on Frontend Uploader plugin
Description: Allow your visitors to upload content and moderate it.
Author: Rinat Khaziev, Daniel Bachhuber, ThemeEnergy.com
Version of Frontend Uploader: 0.8.1
Author of original plugin class URI: http://digitallyconscious.com
Author of modification: http://www.themeenergy.com

GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

define( 'FES_ROOT' , dirname( __FILE__ ) );
define( 'FES_FILE_PATH' , FES_ROOT . '/' . basename( __FILE__ ) );
define( 'FES_URL' , plugins_url( '/', __FILE__ ) );
define( 'FES_NONCE', 'frontendsubmit-form' );

require_once FES_ROOT . '/class-html-helper.php';

class Frontend_Submit {

	protected $allowed_mime_types;
	protected $has_correct_role;
	protected $_html_helper;
	protected $form_fields;
	private $entry = null;
	private $entry_id = 0;
	private $content_type = '';
	
	function __construct() {
	
		add_action( 'init', array( $this, 'action_init' ) );
	
		$this->allowed_mime_types = function_exists( 'wp_get_mime_types' ) ? wp_get_mime_types() : get_allowed_mime_types();
		$this->has_correct_role = BYT_Theme_Utils::check_user_role(BOOKYOURTRAVEL_FRONTEND_SUBMIT_ROLE, $this->get_current_user_id());
		$this->_html_helper = new Html_Helper();		
		
	}
	
	function get_current_user_id() {
		global $current_user;
		if (!isset($current_user)) {
			$current_user = wp_get_current_user();
		} 
		return $current_user->ID;		
	}
	
	public function user_has_correct_role() {
		return $this->has_correct_role || is_super_admin();
	}
	
	public function prepare_form($content_type) {

		global $byt_accommodations_post_type;
	
		$this->entry_id = 0;
		if (isset($_GET['fesid'])) {
			$this->entry_id = intval(wp_kses($_GET['fesid'],''));
		}
		
		$this->entry = null;
		$this->content_type = $content_type;
		
		if ($this->entry_id > 0) {
			if ( $this->content_type == 'accommodation' || $this->content_type == 'room_type' ) {
				$this->entry = get_post($this->entry_id);
				if ( $this->entry->post_author != $this->get_current_user_id() ) {
					$this->entry_id = 0;
					$this->entry = null;
				}
			} elseif ( $this->content_type == 'vacancy' ) {
				$this->entry = $byt_accommodations_post_type->get_accommodation_vacancy($this->entry_id);
			}
		}
	
	}
		
	function action_init() {
	
		add_action( 'wp_ajax_frontend_submit', array( $this, 'fs_upload_content' ) );
		add_action( 'wp_ajax_nopriv_frontend_submit', array( $this, 'fs_upload_content' ) );
		
		add_action( 'wp_ajax_fs_accommodation_is_self_catered_ajax_request', array( $this, 'fs_accommodation_is_self_catered_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_fs_accommodation_is_self_catered_ajax_request', array( $this, 'fs_accommodation_is_self_catered_ajax_request' ) );
		add_action( 'wp_ajax_fs_accommodation_is_price_per_person_ajax_request', array( $this, 'fs_accommodation_is_price_per_person_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_fs_accommodation_is_price_per_person_ajax_request', array( $this, 'fs_accommodation_is_price_per_person_ajax_request' ) );
		add_action( 'wp_ajax_fs_accommodation_list_room_types_ajax_request', array( $this, 'fs_accommodation_list_room_types_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_fs_accommodation_list_room_types_ajax_request', array( $this, 'fs_accommodation_list_room_types_ajax_request' ) );
	}
	
	function fs_accommodation_list_room_types_ajax_request() {

		if ( isset($_REQUEST) ) {
			$nonce = wp_kses($_REQUEST['nonce'], '');
			$accommodation_id = intval(wp_kses($_REQUEST['accommodationId'], ''));
			
			if (wp_verify_nonce( $nonce, FES_NONCE )) {

				$accommodation_obj = new byt_accommodation($accommodation_id);
				
				$room_types = array();			
				$room_type_ids = $accommodation_obj->get_room_types();
				
				if ($accommodation_obj && $room_type_ids && count($room_type_ids) > 0) { 				
					for ( $i = 0; $i < count($room_type_ids); $i++ ) {
						$temp_id = $room_type_ids[$i];
						$room_type_obj = new byt_room_type(intval($temp_id));
						$room_type_temp = new stdClass();
						$room_type_temp->name = $room_type_obj->get_title();
						$room_type_temp->id = $room_type_obj->get_id();
						$room_types[] = $room_type_temp;					
					}
				}

				echo json_encode($room_types);
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}
	
	function fs_accommodation_is_self_catered_ajax_request() {
		if ( isset($_REQUEST) ) {
			$nonce = wp_kses($_REQUEST['nonce'], '');
			$accommodation_id = wp_kses($_REQUEST['accommodationId'], '');
			if (wp_verify_nonce( $nonce, FES_NONCE )) {
				$is_self_catered = get_post_meta( $accommodation_id, 'accommodation_is_self_catered', true );
				echo $is_self_catered ? 1 : 0;
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}

	function fs_accommodation_is_price_per_person_ajax_request() {
		if ( isset($_REQUEST) ) {
			$nonce = wp_kses($_REQUEST['nonce'], '');
			$accommodation_id = wp_kses($_REQUEST['accommodationId'], '');
			if (wp_verify_nonce( $nonce, FES_NONCE )) {
				$is_price_per_person = get_post_meta( $accommodation_id, 'accommodation_is_price_per_person', true );
				echo $is_price_per_person ? 1 : 0;
			}
		}
		
		// Always die in functions echoing ajax content
		die();
	}
	
	/**
	 * Determine if we should autoapprove the submission or not
	 *
	 * @return boolean [description]
	 */
	function _is_public() {
		return of_get_option('publish_frontend_submissions_immediately') && $this->has_correct_role;
	}
	
	function _is_demo() {
		return defined('BYT_DEMO');
	}
	
	/**
	 * Handle uploading of the files
	 *
	 * @param int     $post_id Parent post id
	 * @return array Combined result of media ids and errors if any
	 */
	function _upload_files( $post_id, $input_name, $set_as_featured ) {
		
		$media_ids = $errors = array();
		// Bail if there are no files
		if ( empty( $_FILES ) || !isset($_FILES[$input_name]) )
			return false;

		// File field name could be user defined, so we just get the first file
		$files = $_FILES[$input_name];

		for ( $i = 0; $i < count( $files['name'] ); $i++ ) {
			$fields = array( 'name', 'type', 'tmp_name', 'error', 'size' );
			foreach ( $fields as $field ) {
				$k[$field] = $files[$field][$i];
			}

			$k['name'] = sanitize_file_name( $k['name'] );

			// Skip to the next file if upload went wrong
			if ( $k['tmp_name'] == "" ) {
				continue;
			}

			$typecheck = wp_check_filetype_and_ext( $k['tmp_name'], $k['name'], false );
			// Add an error message if MIME-type is not allowed
			if ( ! in_array( $typecheck['type'], (array) $this->allowed_mime_types ) ) {
				$errors['fes-disallowed-mime-type'][] = array( 'name' => $k['name'], 'mime' => $k['type'] );
				continue;
			}

			// Setup some default values
			// However, you can make additional changes on 'fes_after_upload' action
			$caption = '';

			// Try to set post caption if the field is set on request
			// Fallback to post_content if the field is not set
			if ( isset( $_POST['caption'] ) )
				$caption = sanitize_text_field( $_POST['caption'] );
			elseif ( isset( $_POST['post_content'] ) )
				$caption = sanitize_text_field( $_POST['post_content'] );

			$filename = pathinfo( $k['name'], PATHINFO_FILENAME );
			$post_overrides = array(
				'post_status' => $this->_is_public() ? 'publish' : 'private',
				'post_title' => isset( $_POST['post_title'] ) && ! empty( $_POST['post_title'] ) ? sanitize_text_field( $_POST['post_title'] ) : sanitize_text_field( $filename ),
				'post_content' => empty( $caption ) ? __( 'Unnamed', 'bookyourtravel' ) : $caption,
				'post_excerpt' => empty( $caption ) ? __( 'Unnamed', 'bookyourtravel' ) :  $caption,
			);

			// Trying to upload the file
			$upload_id = media_handle_sideload( $k, (int) $post_id, $post_overrides['post_title'], $post_overrides );
			if ( !is_wp_error( $upload_id ) ) {
				if ($set_as_featured) {
					set_post_thumbnail($post_id, $upload_id);
				}
				$media_ids[] = $upload_id;
			} else
				$errors['fes-error-media'][] = $k['name'];
		}

		/**
		 * $success determines the rest of upload flow
		 * Setting this to true if no errors were produced even if there's was no files to upload
		 */
		$success = empty( $errors ) ? true : false;

		// Allow additional setup
		// Pass array of attachment ids
		do_action( 'fes_after_upload', $media_ids, $success, $post_id );
		return array( 'success' => $success, 'media_ids' => $media_ids, 'errors' => $errors );
	}
	
	private function _save_post_meta_fields( $post_id = 0, $existing = false ) {

		// Post ID not set, bailing
		if ( ! $post_id = (int) $post_id )
			return false;
			
		// No meta fields in field mapping, bailing
		if ( !isset( $this->form_fields ) || empty( $this->form_fields ) )
			return false;

		foreach ( $this->form_fields as $extra_field ) {
			
			$extra_field_name = $extra_field->name;
			
			if ($extra_field->type == 'file' ) {
			
				if ( $extra_field->name == 'accommodation_images' ) {
					$media_result = $this->_upload_files( $post_id, 'accommodation_images', false );
					$result['media_ids'] = $media_result['media_ids'];
					$result['success'] = $media_result['success'];
					if (isset($result['errors']) && $media_result['errors'])
						$result['errors'] = array_merge( $result['errors'], $media_result['errors'] );
					
					$gallery_images = array();
					foreach ($media_result['media_ids'] as $media_id) {
						$gallery_images[] = array('image' => $media_id);
					}
					add_post_meta( $post_id, $extra_field_name, $gallery_images, true );
				}
				
			} else {
				
				if ( !isset( $_POST[$extra_field_name] ) )
					continue;
					
				$value = $_POST[$extra_field_name];
				
				if ( $extra_field_name == 'facilities' ) {
				
					$term_ids = array();
					foreach ($value as $term_id) {
						$term_ids[] = intval($term_id);
					}
					
					wp_set_post_terms( $post_id, $term_ids, 'facility');
					
				} elseif ($extra_field_name == 'accommodation_type') {	
				
					wp_set_post_terms( $post_id, array(intval($value)), 'accommodation_type');
					
				} else {

					// Sanitize array
					if ( $extra_field->type == 'checkbox' && isset($extra_field->class) && $extra_field->class != 'checkboxes') {
						$value = intval($value);
					} elseif ( is_array( $value ) ) {
						$value = array_map( array( $this, '_sanitize_array_element_callback' ), $value );
						// Sanitize everything else
					} else {
						$value = sanitize_text_field( $value );
					}
					
					if ( !$existing ) 
						add_post_meta( $post_id, $extra_field_name, $value, true );
					else
						update_post_meta( $post_id, $extra_field_name, $value );
				}
			}
		}
	}
	
	function _sanitize_array_element_callback( $el ) {
		return sanitize_text_field( $el );
	}
	
	/**
	 * Handle post uploads
	 */
	function _upload_entry() {
	
		global $byt_accommodations_post_type;
	
		$errors = array();
		$success = true;
		
		$content_type = '';
		if (isset( $_POST['content_type'] ) )
			$content_type = $_POST['content_type'];
		
		if ( $content_type == 'accommodation' || $content_type == 'room_type' ) {

			$post_type = $content_type;
			
			$entry_id = 0;
			$existing_post = null;
			
			$allowed_tags = array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			);
			
			if ( isset($_POST['entry_id']) ) {
				
				$entry_id = intval(wp_kses($_POST['entry_id'], ''));
				$existing_post = get_post($entry_id);
				
				if (!$existing_post) {
					$entry_id = 0;
				} else {
					if ( (int)$existing_post->post_author != (int)$this->get_current_user_id() ) {
						$entry_id = 0;
					} else {
						
						$existing_post->post_content = isset($_POST['post_content']) ? wp_kses($_POST['post_content'], $allowed_tags) : '';
						
						$existing_post->post_title = isset($_POST['post_title']) ? sanitize_text_field( $_POST['post_title'] ) : '';
						$existing_post->post_status = $this->_is_public() ? 'publish' : 'private';
						
						$entry_id = wp_update_post($existing_post, true);
					}
				}
			}

			if ( $entry_id == 0 ) {
				
				// Construct post array;
				$post_array = array(
					'post_type' =>  $post_type,
					'post_title'    => sanitize_text_field( $_POST['post_title'] ),
					'post_content'  => (isset($_POST['post_content']) ? wp_kses($_POST['post_content'], $allowed_tags) : ''),
					'post_status'   => $this->_is_public() ? 'publish' : 'private',
				);

				$author = isset( $_POST['post_author'] ) ? sanitize_text_field( $_POST['post_author'] ) : '';
				$users = get_users( array(
						'search' => $author,
						'fields' => 'ID'
					) );

				if ( isset( $users[0] ) ) {
					$post_array['post_author'] = (int) $users[0];
				}

				$post_array = apply_filters( 'fes_before_create_post', $post_array );
					
				$entry_id = wp_insert_post( $post_array, true );
				
				// If the author name is not in registered users
				// Save the author name if it was filled and post was created successfully
				if ( $author )
					add_post_meta( $entry_id, 'author_name', $author );
				
			}
			
			// Something went wrong
			if ( is_wp_error( $entry_id ) ) {
				$errors[] = 'fes-error-post';
				$success = false;
			} else {
				do_action( 'fes_after_create_post', $entry_id );

				$existing = ($existing_post != null);
				$this->_save_post_meta_fields( $entry_id, $existing);
			}
		} elseif ($content_type == 'vacancy') {
		
			$author = isset( $_POST['post_author'] ) ? sanitize_text_field( $_POST['post_author'] ) : '';
			$users = get_users( array(
					'search' => $author,
					'fields' => 'ID'
				) );
				
			$user_id = (int) $users[0];
				
			$accommodation_id = isset( $_POST['accommodation_id'] ) ? intval( $_POST['accommodation_id'] ) : 0;
			
			if ( $accommodation_id > 0 ) {
				
				$accommodation = get_post($accommodation_id);
				
				if ( $accommodation ) {
					if ( $accommodation->post_author == $user_id ) {
					
						$accommodation_obj = new byt_accommodation($accommodation_id);
						
						$start_date = isset( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : '';
						$end_date = isset( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : '';
						
						$start_date = date('Y-m-d', strtotime($start_date));
						$end_date = date('Y-m-d', strtotime($end_date));
						
						$room_type_id = isset( $_POST['room_type_id'] ) ? intval( $_POST['room_type_id'] ) : 0;
						$room_count = isset( $_POST['room_count'] ) ? intval( $_POST['room_count'] ) : '';
						$room_count = $room_count > 0 ? $room_count : 1;
						$price_per_day = isset( $_POST['price_per_day'] ) ?  sanitize_text_field ( $_POST['price_per_day'] ) : 0;
						$price_per_day_child = isset( $_POST['price_per_day_child'] ) ? sanitize_text_field( $_POST['price_per_day_child'] ) : null;
						
						$entry_id = 0;
						if ( isset($_POST['entry_id']) ) {
							$entry_id = intval(wp_kses($_POST['entry_id'], ''));
							$existing_vacancy = $byt_accommodations_post_type->get_accommodation_vacancy($entry_id);
							
							if (!$existing_vacancy) {
								$entry_id = 0;
							} 
						}
						
						if ($entry_id > 0 )
							$byt_accommodations_post_type->update_accommodation_vacancy($entry_id, '', $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child);
						else
							$entry_id = $byt_accommodations_post_type->create_accommodation_vacancy('', $start_date, $end_date, $accommodation_id, $room_type_id, $room_count, $price_per_day, $price_per_day_child);
						$success = true;
				
					} else {
						$errors[] = 'fes-error-vacancy-wrong-user';
						$success = false;
					}				
				} else {
					$errors[] = 'fes-error-vacancy-no-acc-obj';
					$success = false;
				}
				
			} else {
				$errors[] = 'fes-error-vacancy-no-acc-id';
				$success = false;
			}
		
		}
		
		return array( 'success' => $success, 'entry_id' => $entry_id, 'errors' => $errors, 'content_type' => $content_type );
	}

	/**
	 * Handle post+media upload
	 */
	function fs_upload_content() {
	
		$result = array();

		// Bail if something fishy is going on
		if ( !wp_verify_nonce( $_POST['fes_nonce'], FES_NONCE ) ) {
			wp_safe_redirect( esc_url_raw( add_query_arg( array( 'response' => 'fes-error', 'errors' =>  'nonce-failure' ), wp_get_referer() ) ) );
			exit;
		}

		$form_post_id = isset( $_POST['form_post_id'] ) ? (int) $_POST['form_post_id'] : 0;

		if ( $_POST['content_type'] == 'accommodation' ) 
			$this->_initialize_accommodation_fields();	
		elseif ( $_POST['content_type'] == 'room_type' )
			$this->_initialize_room_type_fields();		
		elseif ( $_POST['content_type'] == 'vacancy' )
			$this->_initialize_vacancy_fields();			
		
			
		if (!$this->_is_demo()) {		
			$result = $this->_upload_entry();
			
			if ( $_POST['content_type'] == 'accommodation' || $_POST['content_type'] == 'room_type' ) {
				if ( ! is_wp_error( $result['entry_id'] ) ) {
					$media_result = $this->_upload_files( $result['entry_id'], 'featured_image', true );
					$result['media_ids'] = $media_result['media_ids'];
					$result['success'] = $media_result['success'];
					$result['errors'] = array_merge( $result['errors'], $media_result['errors'] );
				}
			}			
		} else {
			$result = array( 'success' => true, 'entry_id' => 0, 'errors' => array(), 'content_type' => $_POST['content_type'] );
		}

		/**
		 * Process result with filter
		 *
		 * @param array   $result assoc array holding $post_id, $media_ids, bool $success, array $errors
		 */
		do_action( 'fes_upload_result', $result );

		// Notify the admin via email
		$this->_notify_admin( $result );

		// Handle error and success messages, and redirect
		$this->_handle_result( $result );
		exit;
	}

	/**
	 * Notify site administrator by email
	 */
	function _notify_admin( $result = array() ) {
		// Notify site admins of new upload
		if ( !$result['success'] )
			return;
		// TODO: It'd be nice to add the list of upload files
		//$to = !empty( $this->settings['notification_email'] ) && filter_var( $this->settings['notification_email'], FILTER_VALIDATE_EMAIL ) ? $this->settings['notification_email'] : get_option( 'admin_email' );
		//$subj = __( 'New content was uploaded on your site', 'bookyourtravel' );
		//wp_mail( $to, $subj, $this->settings['admin_notification_text'] );
	}

	/**
	 * Process response from upload logic
	 */
	function _handle_result( $result = array() ) {
		// Redirect to referrer if repsonse is malformed
		if ( empty( $result ) || !is_array( $result ) ) {
			wp_safe_redirect( wp_get_referer() );
			return;
		}

		$errors_formatted = array();
		// Either redirect to success page if it's set and valid
		// Or to referrer

		$url = wp_get_referer();

		// $query_args will hold everything that's needed for displaying notices to user
		$query_args = array();

		// Account for successful uploads
		if ( isset( $result['success'] ) && $result['success'] ) {
			// If it's a post
			if ( isset( $result['entry_id'] ) ) {
				if ( $result['content_type'] == 'room_type' )
					$query_args['response'] = 'fes-room_type-sent';
				if ( $result['content_type'] == 'accommodation' )
					$query_args['response'] = 'fes-accommodation-sent';
				if ( $result['content_type'] == 'vacancy' )
					$query_args['response'] = 'fes-vacancy-sent';
					
			} elseif ( isset( $result['media_ids'] ) && !isset( $result['entry_id'] ) ) {
				// If it's media uploads
				$query_args['response'] = 'fes-sent';
			}
		}

		// Some errors happened
		// Format a string to be passed as GET value
		if ( !empty( $result['errors'] ) ) {
			$query_args['response'] = 'fes-error';
			$_errors = array();
			
			// Iterate through key=>value pairs of errors
			foreach ( $result['errors'] as $key => $error ) {
				if ( isset( $error[0] ) )
					$_errors[$key] = join( ',,,', (array) $error[0] );
			}

			foreach ( $_errors as $key => $value ) {
				$errors_formatted[] = "{$key}::{$value}";
			}

			$query_args['errors'] = join( ';', $errors_formatted );
		}

		// Perform a safe redirect and exit
		wp_safe_redirect( esc_url_raw ( add_query_arg( $query_args, $url ) ) );
		exit;
	}
	
	/**
	 * Handles security checks
	 *
	 * @return bool
	 */
	function _check_perms_and_nonce() {
		return $this->has_correct_role && wp_verify_nonce( $_REQUEST['fes_nonce'], FES_NONCE );
	}
	
	/**
	 * Handle response notices
	 *
	 * @param array   $res [description]
	 * @return [type]      [description]
	 */
	function _display_response_notices( $res = array() ) {
		if ( empty( $res ) )
			return;

		$map_prefix = '';
		if ($this->_is_demo()) 
			$map_prefix = 'If this were not a demo, the message would read: ';
			
		$output = '';
		$map = array(
			'fes-sent' => array(
				'text' => sprintf(__( '%s Your file was successfully uploaded!', 'bookyourtravel' ), $map_prefix),
				'class' => 'success',
			),
			'fes-accommodation-sent' => array(
				'text' => sprintf(__( '%s Your accommodation was successfully submitted!', 'bookyourtravel' ), $map_prefix),
				'class' => 'success',
			),
			'fes-room_type-sent' => array(
				'text' => sprintf(__( '%s Your room type was successfully submitted!', 'bookyourtravel' ), $map_prefix),
				'class' => 'success',
			),
			'fes-vacancy-sent' => array(
				'text' => sprintf(__('%s Your vacancy was successfully submitted!', 'bookyourtravel' ), $map_prefix),
				'class' => 'success',
			),
			'fes-error' => array(
				'text' => sprintf(__( '%s There was an error with your submission', 'bookyourtravel' ), $map_prefix),
				'class' => 'failure',
			),
		);
		
		$edit_notices = array(
			'accommodation' => array (
				'text' => __('You are currently editing your selected accommodation. Click "Submit" to save your changes.', 'bookyourtravel'),
				'class' => 'warning'
			),
			'room_type' => array (
				'text' => __('You are currently editing your selected room type. Click "Submit" to save your changes.', 'bookyourtravel'),
				'class' => 'warning'
			),
			'vacancy' => array (
				'text' => __('You are currently editing your selected vacancy. Click "Submit" to save your changes.', 'bookyourtravel'),
				'class' => 'warning'
			)
		);	

		if ( isset( $res['response'] ) && isset( $map[ $res['response'] ] ) )
			$output .= $this->_notice_html( $map[ $res['response'] ]['text'] , $map[ $res['response'] ]['class'] );

		if ( !empty( $res['errors' ] ) )
			$output .= $this->_display_errors( $res['errors' ] );
			
		if ( !empty( $res['fesid'] ) && isset($this->entry_id) && isset($this->content_type) && $this->entry != null )
			$output .= $this->_notice_html( $edit_notices[ $this->content_type ]['text'] , $edit_notices[ $this->content_type ]['class'] );
			
		echo $output;
	}
	
	/**
	 * Returns html chunk of single notice
	 *
	 * @param string  $message Text of the message
	 * @param string  $class   Class of container
	 * @return string          [description]
	 */
	function _notice_html( $message, $class ) {
		if ( empty( $message ) || empty( $class ) )
			return;
		return sprintf( '<p class="fes-notice %1$s">%2$s</p>', $class, $message );
	}
	
	/**
	 * Handle errors
	 *
	 * @param string  $errors [description]
	 * @return string HTML
	 */
	function _display_errors( $errors ) {
		
		$errors_arr = explode( ';', $errors );
		$output = '';
		$map = array(
			'nonce-failure' => array(
				'text' => __( 'Security check failed!', 'bookyourtravel' ),
			),
			'fes-disallowed-mime-type' => array(
				'text' => __( 'This kind of file is not allowed. Please, try selecting another file.', 'bookyourtravel' ),
				'format' => '%1$s: <br/> File name: %2$s <br/> MIME-TYPE: %3$s',
			),
			'fes-invalid-post' => array(
				'text' =>__( 'The content you are trying to post is invalid.', 'bookyourtravel' ),
			),
			'fes-error-media' => array(
				'text' =>__( "Couldn't upload the file", 'bookyourtravel' ),
			),
			'fes-error-post' => array(
				'text' =>__( "Couldn't create the post", 'bookyourtravel' ),
			),
			'fes-error-vacancy-wrong-user' => array(
				'text' =>__( "User does not own accommodation specified", 'bookyourtravel' ),
			),
			'fes-error-vacancy-no-acc-obj' => array(
				'text' =>__( "Could not find accommodation object", 'bookyourtravel' ),
			),
			'fes-error-vacancy-no-acc-id' => array(
				'text' =>__( "Accommodation id was not specified", 'bookyourtravel' ),
			),
		);

		// TODO: DAMN SON you should refactor this
		foreach ( $errors_arr as $error ) {
			$error_type = explode( '::', $error );
			$error_details = explode( '|', $error_type[1] );
			// Iterate over different errors
			foreach ( $error_details as $single_error ) {

				// And see if there's any additional details
				$details = isset( $single_error ) ? explode( ',,,', $single_error ) : explode( ',,,', $single_error );
				// Add a description to our details array
				array_unshift( $details, $map[ $error_type[0] ]['text']  );
				// If we have a format, let's format an error
				// If not, just display the message
				if ( isset( $map[ $error_type[0] ]['format'] ) )
					$message = vsprintf( $map[ $error_type[0] ]['format'], $details );
				else
					$message = $map[ $error_type[0] ]['text'];
			}
			$output .= $this->_notice_html( $message, 'failure' );
		}

		return $output;
	}
	
	function _render_checkbox_input($atts) {

		$atts = $this->_prepare_atts($atts);
	
		extract( $atts );
		
		$atts = array( 'id' => $id, 'class' => $class, 'multiple' => $multiple );
		
		// Workaround for HTML5 multiple attribute
		if ( (bool) $multiple === false )
			unset( $atts['multiple'] );

		$selected_value = $this->get_entry_field_value($name);
		if ($this->entry != null && isset($selected_value) ) {
			if ($type == 'checkbox' && $selected_value == '1')
				$atts['checked'] = 'checked';
		}
		
		$input = $this->_html_helper->input( $type, $name, $value, $atts );

		$element = $this->_html_helper->element( 'label',  $input . $description, array( 'for' => $id ), false );

		$container_class = 'fes-input-wrapper';
		if (isset($container_class_override) && !empty($container_class_override))
			$container_class .= ' ' . $container_class_override;
		return $this->_html_helper->element( 'div', $element, array( 'class' => $container_class ), false );
		
	}
	
	function _render_input( $atts ) {

		$atts = $this->_prepare_atts($atts);
	
		extract( $atts );
		
		$atts = array( 'id' => $id, 'class' => $class, 'multiple' => $multiple );
		
		// Workaround for HTML5 multiple attribute
		if ( (bool) $multiple === false )
			unset( $atts['multiple'] );

		$selected_value = $this->get_entry_field_value($name);
		if ($this->entry != null && isset($selected_value) ) {
			if ($type == 'checkbox' && $selected_value == '1')
				$atts['checked'] = 'checked';
			else if ($type == 'text')
				$value = $selected_value;
		}
			
		// Allow multiple file upload by default.
		// To do so, we need to add array notation to name field: []
		if ( !strpos( $name, '[]' ) && $type == 'file' )
			$name = $name . '[]';
			
		$input = $this->_html_helper->input( $type, $name, $value, $atts );

		// No need for wrappers or labels for hidden input
		if ( $type == 'hidden' )
			return $input;

		$element = $this->_html_helper->element( 'label', $description . $input , array( 'for' => $id ), false );

		$container_class = 'fes-input-wrapper';
		if (isset($container_class_override) && !empty($container_class_override))
			$container_class .= ' ' . $container_class_override;
		return $this->_html_helper->element( 'div', $element, array( 'class' => $container_class ), false );
	}
	
	/**
	 * Textarea element callback
	 *
	 * @param array   shortcode attributes
	 * @return string formatted html element
	 */
	function _render_textarea( $atts ) {
	
		$atts = $this->_prepare_atts($atts);
	
		extract( $atts );
		
		$selected_value = $this->get_entry_field_value($name);
		if ( $this->entry != null && isset($selected_value) ) {
			$value = $selected_value;
		}
		
		// Render WYSIWYG textarea
		if ( $wysiwyg_enabled ) {
			ob_start();
			wp_editor( $value, $id, array(
					'textarea_name' => $name,
					'media_buttons' => false,
					'teeny' => true,
    "quicktags" => array(
        "buttons" => "em,strong,link"
    )
				) );
			$tiny = ob_get_clean();
			$label =  $this->_html_helper->element( 'label', $description , array( 'for' => $id ), false );
			return $this->_html_helper->element( 'div', $label . $tiny, array( 'class' => 'fes-input-wrapper' ), false ) ;
		}
		// Render plain textarea
		$element = $this->_html_helper->element( 'textarea', $value, array( 'name' => $name, 'id' => $id, 'class' => $class ) );
		$label = $this->_html_helper->element( 'label', $description, array( 'for' => $id ), false );

		$container_class = 'fes-input-wrapper';
		if (isset($container_class_override) && !empty($container_class_override))
			$container_class .= ' ' . $container_class_override;
		return $this->_html_helper->element( 'div', $label . $element, array( 'class' => $container_class ), false );
	}

	function get_entry_field_value($field_id) {
		
		if ($this->entry != null) { 
		
			if ( $this->content_type == 'accommodation' ) {
				
				$accommodation_obj = new byt_accommodation(intval($this->entry_id));
				return $accommodation_obj->get_field_value($field_id, false);
				
			} elseif ( $this->content_type == 'room_type' ) {
				
				$room_type_obj = new byt_room_type(intval($this->entry_id));
				return $room_type_obj->get_field_value($field_id, false);			
				
			} else if ( $this->content_type == 'vacancy' ) {
			
				if (property_exists($this->entry,$field_id) && isset($this->entry->$field_id)) {
					return $this->entry->$field_id;
				}
				
			}			
		}
		
		return null;
	}
	
	/**
	 * Select element callback
	 *
	 * @param array   shortcode attributes
	 * @return [type]       [description]
	 */
	function _render_select( $atts ) {
	
		$atts = $this->_prepare_atts($atts);
	
		extract( $atts );
		$atts = array( 'values' => $values );
		$values = explode( ',', $values );
		$options = '';
		
		$selected_value = $this->get_entry_field_value($name);
		
		//Build options for the list
		foreach ( $values as $option ) {
			$kv = explode( "::", $option );
			$caption = isset( $kv[1] ) ? $kv[1] : $kv[0];
			$option_atts = array( 'value' => $kv[0] );
			if ( isset($selected_value) && $selected_value == $kv[0] )
				$option_atts['selected'] = 'selected';
			
			$options .= $this->_html_helper->element( 'option', $caption, $option_atts, false );
		}

		//Render select field
		$element = $this->_html_helper->element( 'label', $description . $this->_html_helper->element( 'select', $options, array(
					'name' => $name,
					'id' => $id,
					'class' => $class
				), false ), array( 'for' => $id ), false );

		$container_class = 'fes-input-wrapper';
		if (isset($container_class_override) && !empty($container_class_override))
			$container_class .= ' ' . $container_class_override;
		return $this->_html_helper->element( 'div', $element, array( 'class' => $container_class ), false );
	}

	/**
	 * Checkboxes element callback
	 *
	 * @param array   shortcode attributes
	 * @return [type]       [description]
	 */
	function _render_checkboxes( $atts ) {
	
		$atts = $this->_prepare_atts($atts);
		extract( $atts );
		
		$atts = array( 'values' => $values );
		$values = explode( ',', $values );
		$options = '';

		$selected_values = $this->get_entry_field_value($name);
		
		// Making sure we're having array of values for checkboxes
		if ( false === stristr( '[]', $name ) )
			$name = $name . '[]';

		//Build options for the list
		foreach ( $values as $option ) {
			$kv = explode( "::", $option );
			if (is_array($selected_values) && in_array($kv[0], $selected_values)) {
				$atts['checked'] = 'checked';
			} else {
				unset($atts['checked']);
			}
			$options .= $this->_html_helper->_checkbox( $name, isset( $kv[1] ) ? $kv[1] : $kv[0], $kv[0], $atts, array() );
		}

		$description = $label = $this->_html_helper->element( 'label', $description, array(), false );

		// Render select field
		$element = $this->_html_helper->element( 'div', $description . $options, array( 'class' => 'checkbox-wrapper' ), false );
		
		$container_class = 'fes-input-wrapper';
		if (isset($container_class_override) && !empty($container_class_override))
			$container_class .= ' ' . $container_class_override;
		return $this->_html_helper->element( 'div', $element, array( 'class' => $container_class ), false );
	}
	
	function _prepare_atts($atts) {
		$supported_atts = array(
			'id' => '',
			'name' => '',
			'description' => '',
			'value' => '',
			'type' => '',
			'class' => '',
			'multiple' => false,
			'values' => '',
			'wysiwyg_enabled' => false,
			'role' => 'meta',
			'container_class_override' => '',
		);
		return shortcode_atts($supported_atts, $atts);
	}

	function _initialize_vacancy_fields() {
	
		global $byt_accommodations_post_type, $byt_room_types_post_type;
	
		$this->form_fields = array();	

		$accommodation_results = $byt_accommodations_post_type->list_accommodations ( 0, -1, '', '', 0, array(),  array(), array(), false, null, $this->get_current_user_id(), true );
		$accommodations_str = '';
		if ( count($accommodation_results) > 0 && $accommodation_results['total'] > 0 ) {
			foreach ($accommodation_results['results'] as $accommodation_result) {
				$accommodations_str .= "{$accommodation_result->ID}::{$accommodation_result->post_title},";		
			}
		}
		$accommodations_str = '::' . __('Select accommodation', 'bookyourtravel') . ',' .  rtrim($accommodations_str, ',');
		$this->form_fields[] = (object)array( 'type' => 'select', 'role' => 'internal', 'name' => 'accommodation_id', 'id' => 'fes_accommodation_id', 'description' => __( 'Accommodation', 'bookyourtravel' ), 'values' => $accommodations_str, 'class' => 'select required' );
	
		$room_types_str = '::' . __('Select room type', 'bookyourtravel') . ',';
		
		if ($this->entry_id > 0) {

			$room_type_query = $byt_room_types_post_type->list_room_types($this->get_current_user_id(), array('publish', 'private'));
			if ($room_type_query->have_posts()) {
				while ($room_type_query->have_posts()) {
					$room_type_query->the_post();
					global $post;				
					$room_types_str .= "{$post->ID}::{$post->post_title},";
				}
			}
			$room_types_str = rtrim($room_types_str, ',');

		}		
		$this->form_fields[] = (object)array( 'type' => 'select', 'role' => 'internal', 'name' => 'room_type_id', 'id' => 'fes_room_type_id', 'description' => __( 'Room type', 'bookyourtravel' ), 'values' => $room_types_str, 'class' => 'select', 'container_class_override' => 'room_types' );
		
		$number_of_available_rooms_str = '::0,';
		for ($i=1;$i<100;$i++) {
			$number_of_available_rooms_str .= "$i::$i,";
		}
		$number_of_available_rooms_str = rtrim($number_of_available_rooms_str, ',');		
		$this->form_fields[] = (object)array( 'type' => 'select', 'role' => 'internal', 'name' => 'room_count', 'id' => 'fes_room_count', 'description' => __( 'Number of available rooms', 'bookyourtravel' ), 'values' => $number_of_available_rooms_str, 'class' => 'select', 'container_class_override' => 'room_types' );
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'price_per_day', 'id' => 'fes_price_per_day', 'description' => __( 'Price per day', 'bookyourtravel' ), 'class' => 'number required' );
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'price_per_day_child', 'id' => 'fes_price_per_day_child', 'description' => __( 'Price per day child', 'bookyourtravel' ), 'class' => 'number required', 'container_class_override' => 'per_person' );
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'start_date', 'id' => 'fes_start_date', 'description' => __( 'Start date', 'bookyourtravel' ), 'class' => 'dateFormatDate required datepicker' );
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'end_date', 'id' => 'fes_end_date', 'description' => __( 'End date', 'bookyourtravel' ), 'class' => 'dateFormatDate required datepicker' );
					
	}
	
	function _initialize_room_type_fields() {
				
		global $byt_room_types_post_type;

		$this->form_fields = array();	
		
		wp_reset_postdata();
		
		$max_count_str = "0::0,1::1,2::2,3::3,4::4,5::5,6::6,7::7,8::8,9::9,10::10";
		$this->form_fields[] = (object)array( 'type' => 'select', 'role' => 'internal', 'name' => 'room_type_max_count', 'id' => 'fes_room_type_max_count', 'description' => __( 'Max adult count', 'bookyourtravel' ), 'values' => $max_count_str, 'class' => 'select' );
		$this->form_fields[] = (object)array( 'type' => 'select', 'role' => 'internal', 'name' => 'room_type_max_child_count', 'id' => 'fes_room_type_max_child_count', 'description' => __( 'Max child count', 'bookyourtravel' ), 'values' => $max_count_str, 'class' => 'select' );
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'room_type_bed_size', 'id' => 'fes_room_type_bed_size', 'description' => __( 'Bed size', 'bookyourtravel' ) );
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'room_type_room_size', 'id' => 'fes_room_type_room_size', 'description' => __( 'Room size', 'bookyourtravel' ) );
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'room_type_meta', 'id' => 'fes_room_type_meta', 'description' => __( 'Room meta information', 'bookyourtravel' ) );
		
		$taxonomies = array( 'facility' );
		$args = array( 'hide_empty' => false, 'fields' => 'all' ); 
		$facilities = get_terms($taxonomies, $args);
		$facilities_str = '';
		foreach ($facilities as $facility) {
			$facilities_str .= "{$facility->term_id}::{$facility->name},";
		}
		$facilities_str = rtrim($facilities_str, ',');
		$this->form_fields[] = (object)array( 'type' => 'checkbox', 'role' => 'internal', 'name' => 'facilities', 'id' => 'fes_facilities', 'description' => __( 'Facilities', 'bookyourtravel' ), 'values' => $facilities_str, 'class' => 'checkboxes' );
		
	}
	
	function _initialize_accommodation_fields() {	
	
		global $byt_locations_post_type, $byt_room_types_post_type;
	
		$this->form_fields = array();

		$this->form_fields[] = (object)array( 'type' => 'checkbox', 'value' => '1', 'role' => 'internal', 'name' => 'accommodation_is_self_catered', 'id' => 'fes_accommodation_is_self_catered', 'description' => __( '<div>Is self catered?<br /><small>Otherwise accommodation is treated as a hotel-type accommodation.</small></div>', 'bookyourtravel' ) );
		$this->form_fields[] = (object)array( 'type' => 'checkbox', 'value' => '1', 'role' => 'internal', 'name' => 'accommodation_is_reservation_only', 'id' => 'fes_accommodation_is_reservation_only', 'description' => __( '<div>Is for reservation only<br /><small>Meaning booking does not proceed to payment form?</small></div>', 'bookyourtravel' ) );
		$this->form_fields[] = (object)array( 'type' => 'checkbox', 'value' => '1', 'role' => 'internal', 'name' => 'accommodation_is_price_per_person', 'id' => 'fes_accommodation_is_price_per_person', 'description' => __( '<div>Is priced per person?<br /><small>Otherwise it\'s priced on a per-room basis</small></div>', 'bookyourtravel' ) );

		$room_types_str = '';
		$room_type_query = $byt_room_types_post_type->list_room_types($this->get_current_user_id(), array('publish', 'private'));
		if ($room_type_query->have_posts()) {
			while ($room_type_query->have_posts()) {
				$room_type_query->the_post();
				global $post;				
				$room_types_str .= "{$post->ID}::{$post->post_title},";
			}
		}
		$room_types_str = rtrim($room_types_str, ',');
		wp_reset_postdata();
		$this->form_fields[] = (object)array( 'type' => 'checkbox', 'role' => 'internal', 'name' => 'room_types', 'id' => 'fes_room_types', 'description' => __( 'Room types', 'bookyourtravel' ), 'values' => $room_types_str, 'class' => 'checkboxes', 'container_class_override' => 'room_types' );

		$min_days_stay_str = "0::0,1::1,2::2,3::3,4::4,5::5";
		$this->form_fields[] = (object)array( 'type' => 'select', 'role' => 'internal', 'name' => 'accommodation_min_days_stay', 'id' => 'fes_accommodation_min_days_stay', 'description' => __( 'Minimum days stay', 'bookyourtravel' ), 'values' => $min_days_stay_str, 'class' => 'select', 'container_class_override' => '' );
		
		$count_children_stay_free_str = "0::0,1::1,2::2,3::3,4::4,5::5";
		$this->form_fields[] = (object)array( 'type' => 'select', 'role' => 'internal', 'name' => 'accommodation_count_children_stay_free', 'id' => 'fes_accommodation_count_children_stay_free', 'description' => __( 'Count children stay free', 'bookyourtravel' ), 'values' => $count_children_stay_free_str, 'class' => 'select', 'container_class_override' => '' );
		
		$check_in_out_times_str = "";		
		for ($i=0; $i<24;$i++)
			$check_in_out_times_str .= sprintf("%02s:00::%02s:00,", $i, $i);
		$check_in_out_times_str = rtrim($check_in_out_times_str, ',');

		$this->form_fields[] = (object)array( 'type' => 'select', 'role' => 'internal', 'name' => 'accommodation_check_in_time', 'id' => 'fes_accommodation_check_in_time', 'description' => __( 'Check-in time', 'bookyourtravel' ), 'values' => $check_in_out_times_str, 'class' => 'select', 'container_class_override' => '' );
		$this->form_fields[] = (object)array( 'type' => 'select', 'role' => 'internal', 'name' => 'accommodation_check_out_time', 'id' => 'fes_accommodation_check_out_time', 'description' => __( 'Check-out time', 'bookyourtravel' ), 'values' => $check_in_out_times_str, 'class' => 'select', 'container_class_override' => '' );
		
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'accommodation_address', 'id' => 'fes_accommodation_address', 'description' => __( 'Address', 'bookyourtravel' ) );
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'accommodation_website_address', 'id' => 'fes_accommodation_website_address', 'description' => __( 'Website address', 'bookyourtravel' ) );
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'accommodation_contact_email', 'id' => 'fes_accommodation_contact_email', 'description' => __( 'Contact email addresses (separate multiple addresses with semi-colon ;)', 'bookyourtravel' ) );
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'accommodation_latitude', 'id' => 'fes_accommodation_latitude', 'description' => __( 'Latitude coordinates', 'bookyourtravel' ) );
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'accommodation_longitude', 'id' => 'fes_accommodation_longitude', 'description' => __( 'Longitude coordinates', 'bookyourtravel' ) );
		$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => 'accommodation_availability_text', 'id' => 'fes_accommodation_availability_text', 'description' => __( 'Availability extra text', 'bookyourtravel' ) );
		
		$star_count_str = "0::0,1::1,2::2,3::3,4::4,5::5";
		$this->form_fields[] = (object)array( 'type' => 'select', 'role' => 'internal', 'name' => 'accommodation_star_count', 'id' => 'fes_accommodation_star_count', 'description' => __( 'Star count', 'bookyourtravel' ), 'values' => $star_count_str, 'class' => 'select' );

		$taxonomies = array( 'accommodation_type' );
		$args = array( 'hide_empty' => false, 'fields' => 'all' ); 
		$accommodation_types = get_terms($taxonomies, $args);
		$accommodation_types_str = '::' . __('Select accommodation type', 'bookyourtravel') . ',';
		foreach ($accommodation_types as $accommodation_type) {
			$accommodation_types_str .= "{$accommodation_type->term_id}::{$accommodation_type->name},";
		}
		$accommodation_types_str = rtrim($accommodation_types_str, ',');				
		$this->form_fields[] = (object)array( 'type' => 'select', 'role' => 'internal', 'name' => 'accommodation_type', 'id' => 'fes_accommodation_type', 'description' => __( 'Accommodation type', 'bookyourtravel' ), 'values' => $accommodation_types_str, 'class' => 'select required' );

		$taxonomies = array( 'facility' );
		$args = array( 'hide_empty' => false, 'fields' => 'all' ); 
		$facilities = get_terms($taxonomies, $args);
		$facilities_str = '';
		foreach ($facilities as $facility) {
			$facilities_str .= "{$facility->term_id}::{$facility->name},";
		}
		$facilities_str = rtrim($facilities_str, ',');
		$this->form_fields[] = (object)array( 'type' => 'checkbox', 'role' => 'internal', 'name' => 'facilities', 'id' => 'fes_facilities', 'description' => __( 'Facilities', 'bookyourtravel' ), 'values' => $facilities_str, 'class' => 'checkboxes' );
				
		$locations_str = '::' . __('Select location', 'bookyourtravel') . ',';
		$location_results = $byt_locations_post_type->list_locations();
		if ( count($location_results) > 0 && $location_results['total'] > 0 ) {
			foreach ($location_results['results'] as $location_result) {
				$locations_str .= "{$location_result->ID}::{$location_result->post_title},";
			}		
		}
		$locations_str = rtrim($locations_str, ',');
		wp_reset_postdata();
		$this->form_fields[] = (object)array( 'type' => 'select', 'role' => 'internal', 'name' => 'accommodation_location_post_id', 'id' => 'fes_accommodation_location_post_id', 'description' => __( 'Location', 'bookyourtravel' ), 'values' => $locations_str, 'class' => 'select required' );
				
		$this->form_fields[] = (object)array( 'type' => 'file', 'role' => 'file', 'name' => 'accommodation_images', 'id' => 'fes_accommodation_images', 'multiple' => 'multiple', 'description' =>  __( 'Gallery images', 'bookyourtravel' ) );

		$accommodation_extra_fields = of_get_option('accommodation_extra_fields');
		if (!is_array($accommodation_extra_fields) || count($accommodation_extra_fields) == 0)
			$accommodation_extra_fields = $default_accommodation_extra_fields;			
		foreach ($accommodation_extra_fields as $extra_field) {
			$field_is_hidden = isset($extra_field['hide']) ? intval($extra_field['hide']) : 0;
			
			if (!$field_is_hidden) {			
				$field_id = 'accommodation_' . (isset($extra_field['id']) ? $extra_field['id'] : '');
				$field_label = isset($extra_field['label']) ? $extra_field['label'] : '';
				$field_type = isset($extra_field['type']) ? $extra_field['type'] : '';
				
				if ($field_type == 'text') {
					$this->form_fields[] = (object)array( 'type' => 'text', 'role' => 'internal', 'name' => $field_id, 'id' => 'fes_' . $field_id, 'description' => $field_label );
				} elseif ($field_type == 'textarea') {
					$this->form_fields[] = (object)array( 'type' => 'textarea', 'role' => 'file', 'name' => $field_id, 'id' => 'fes_' . $field_id, 'multiple' => 'multiple', 'description' => $field_label );

				} elseif ($field_type == 'image') {
					$this->form_fields[] = (object)array( 'type' => 'file', 'role' => 'file', 'name' => $field_id, 'id' => 'fes_' . $field_id, 'multiple' => false, 'description' => $field_label );
				}
			}		
		}
	
	}
	
	/**
	 * Display the upload post form
	 */
	function upload_form( $content_type = 'accommodation' ) {
	
		if ( $this->user_has_correct_role() ) {
		
			if ( $content_type == 'accommodation' )
				$this->_initialize_accommodation_fields();
			elseif ( $content_type == 'room_type' )
				$this->_initialize_room_type_fields();						
			elseif ( $content_type == 'vacancy' )
				$this->_initialize_vacancy_fields();	
				
			// Reset postdata in case it got polluted somewhere
			wp_reset_postdata();
			$form_post_id = get_the_id();

			$post_id = (int) $form_post_id;
			
			ob_start();
	?>
			<script>
				window.adminAjaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
			</script>
			<form action="<?php echo esc_url(admin_url( 'admin-ajax.php' )) ?>" method="post" id="fes-upload-form-<?php echo esc_attr($content_type); ?>" name="fes-upload-form-<?php echo esc_attr($content_type); ?>" class="fes-upload-form fes-form-<?php echo esc_attr($content_type); ?>" enctype="multipart/form-data">
				<div class="fes-inner-wrapper">
	<?php
					if ( !empty( $_GET ) )
						$this->_display_response_notices( $_GET );

					$atts = array( 'type' => 'hidden', 'role' => 'internal', 'name' => 'post_author', 'id' => 'fes_post_author', 'value' =>  $this->get_current_user_id() );
					echo $this->_render_input($atts);
					
					$atts = array( 'type' => 'hidden', 'role' => 'internal', 'name' => 'content_type', 'id' => 'fes_content_type', 'value' =>  $content_type );
					echo $this->_render_input($atts);
					
					if ($this->entry_id > 0) {
						$atts = array( 'type' => 'hidden', 'role' => 'internal', 'name' => 'entry_id', 'value' => $this->entry_id, 'id' => 'fes_entry_id' );
						echo $this->_render_input($atts);
					}
					
					if ( $content_type == 'accommodation' || $content_type == 'room_type' ) {
						
						$atts = array( 'type' => 'text', 'role' => 'title', 'name' => 'post_title',	'id' => 'fes_post_title', 'class' => 'required', 'description' =>  __( 'Title', 'bookyourtravel' ) );
						echo $this->_render_input($atts);
						
						$atts = array( 'role' => 'content', 'name' => 'post_content', 'id' => 'fes_post_content', 'class' => 'required', 'description' =>  __( 'Description', 'bookyourtravel' ), 'wysiwyg_enabled' => true );
						echo $this->_render_textarea($atts);

						$atts = array( 'type' => 'file', 'role' => 'file', 'name' => 'featured_image', 'id' => 'fes_featured_image', 'multiple' => false, 'description' =>  __( 'Featured image', 'bookyourtravel' ) );
						echo $this->_render_input($atts);

					}
					
					$this->_render_extra_fields();
					
					$atts = array( 'type' => 'submit', 'role' => 'internal', 'name' => 'submit_button', 'id' => 'fes_submit_button', 'class' => 'btn gradient-button', 'value' =>  __( 'Submit', 'bookyourtravel' ) );
					echo $this->_render_input($atts);

					$atts = array( 'type' => 'hidden', 'role' => 'internal', 'name' => 'action', 'id' => 'fes_action', 'value' =>  'frontend_submit' );
					echo $this->_render_input($atts);
	?>
					<?php wp_nonce_field( FES_NONCE, 'fes_nonce' ); ?>
					<input type="hidden" name="form_post_id" value="<?php echo esc_attr($form_post_id); ?>" />
					<div class="clear"></div>
				</div>
			</form>
	<?php
			return ob_get_clean();
		} 
		return '';
	}
	
	function _render_extra_fields() {
		
		foreach ($this->form_fields  as $form_field) {

			if ($form_field->type == 'select')
				echo $this->_render_select($form_field);
			elseif  ($form_field->type == 'checkbox' && isset($form_field->class) && $form_field->class == 'checkboxes')
				echo $this->_render_checkboxes($form_field);
			elseif  ($form_field->type == 'textarea')
				echo $this->_render_textarea($form_field);
			elseif ($form_field->type == 'checkbox')
				echo $this->_render_checkbox_input($form_field);			
			elseif  ($form_field->type == 'text' || $form_field->type == 'file')
				echo $this->_render_input($form_field);			
				
		}

	}
}

global $frontend_submit;
$frontend_submit = new Frontend_Submit();