<?php

class BYT_Theme_Of_Custom extends BYT_BaseSingleton {
	
	protected function __construct() {
	
        // our parent class might contain shared code in its constructor
        parent::__construct();
		
    }
	
    public function init() {
		
		add_filter( 'optionsframework_repeat_tab', array( $this, 'repeat_tab_option_type' ), 10, 3 );
		add_filter( 'optionsframework_repeat_extra_field', array( $this, 'repeat_extra_field_option_type' ), 10, 3 );
		add_filter( 'optionsframework_link_button_field', array( $this, 'link_button_field_option_type' ), 10, 3 );
		add_filter( 'optionsframework_dummy_text', array( $this, 'dummy_text_option_type' ), 10, 3 );
		add_filter( 'optionsframework_repeat_review_field', array( $this, 'repeat_review_field_option_type' ), 10, 3 );
		add_filter( 'of_sanitize_repeat_review_field', array( $this, 'sanitize_repeat_review_field' ), 10, 2 );
		add_filter( 'of_sanitize_repeat_extra_field', array( $this, 'sanitize_repeat_extra_field' ), 10, 2 );
		add_filter( 'of_sanitize_repeat_tab', array( $this, 'sanitize_repeat_tab' ), 10, 2 );
		add_action( 'optionsframework_custom_scripts', array( $this, 'of_byt_options_script' ) );
		
	}
	
	public function register_dynamic_string_for_translation($name, $value) {
		if (function_exists('icl_register_string')) {
			icl_register_string('BookYourTravel Theme', $name, $value);
		}
	}
	
	public function get_translated_dynamic_string($name, $value) {
		if (function_exists('icl_t')) {
			//if ($name == 'Tour tab')
			//	echo 'icl_t' . $name . ' ' . $value;
			return icl_t('BookYourTravel Theme', $name, $value);
		}
		return $value;
	}

	/*
	 * Add custom, repeatable input fields to options framework thanks to HelgaTheViking
	 * https://gist.github.com/helgatheviking/6022215
	 */
	public function repeat_tab_option_type( $option_name, $option, $values ){

		global $byt_theme_of_default_fields;
	
		$counter = 0;
		
		$default_values = $byt_theme_of_default_fields->get_default_tab_array($option['id']);
		
		if (!is_array( $values ) || count($values) == 0 || count($values) < count($default_values) ) {
			$values = $default_values;
		}
		
		$output = '<div class="of-repeat-loop">';
		$output .= '<ul class="sortable of-repeat-tabs">';
	 
		if( is_array( $values ) ) { 
		
			foreach ( (array)$values as $value ){
				if (isset($value['label']) && 
					isset($value['id'])) {
					
					$output .= '<li class="ui-state-default of-repeat-group">';
					$output .= '<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-input input-tab-label" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][label]' ) . '" type="text" value="' . esc_attr( $value['label'] ) . '" />';
					$output .= '<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="input-tab-id" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][id]' ) . '" type="hidden" value="' . esc_attr( $value['id'] ) . '" />';
					$output .= '<div class="of-checkbox-wrap">';
					$output .= '<label data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-label label-hide-tab" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][hide]' ) . '">' . __('Is hidden?', 'bookyourtravel') . '</label>';
					$output .= '<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-checkbox checkbox-hide-tab" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][hide]' ) . '" type="checkbox" value="1" ' . (isset($value['hide']) && $value['hide'] == '1' ? 'checked' : '') . ' />';
					$output .= '</div>';
					if (isset($value['id']) && isset($value['label']) && count(BYT_Theme_Utils::custom_array_search($default_values, 'id', $value['id'])) == 0) {
						$output .= '<span class="ui-icon ui-icon-close"></span>';
					}

					$output .= '</li><!--.of-repeat-group-->';
					
					$counter++;
				}
			}
		}
	 
		$output .= '<li class="to-copy ui-state-default of-repeat-group">';	
		$output .= '<input class="of-input input-tab-label" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="text" value="' . esc_attr( $option['std'] ) . '" />';
		$output .= '<div class="of-checkbox-wrap">';
		$output .= '<label class="of-label label-hide-tab" for="">' . __('Is hidden?', 'bookyourtravel') . '</label>';
		$output .= '<input class="of-checkbox checkbox-hide-tab" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="checkbox" value="1" />';
		$output .= '</div>';
		$output .= '<span class="ui-icon ui-icon-close"></span>';
		$output .= '</li><!--.of-repeat-group-->';
		$output .= '</ul>';	
		$output .= '<a href="#" class="docopy_tab button icon add">' . __('Add tab', 'bookyourtravel') . '</a>';
		$output .= '</div><!--.of-repeat-loop-->';
	 
		return $output;
	}
	
	function repeat_extra_field_option_type( $option_name, $option, $values ){

		global $byt_theme_of_default_fields, $repeatable_field_types, $default_accommodation_extra_fields, $default_tour_extra_fields, $default_car_rental_extra_fields, $default_location_extra_fields, $default_cruise_extra_fields;
		$counter = 0;
		
		$default_values = array();
		$tab_array = array();
		
		if ($option['id'] == 'accommodation_extra_fields') {
			$default_values = $default_accommodation_extra_fields;
			$tab_key = 'accommodation_tabs';
		} elseif ($option['id'] == 'tour_extra_fields') {
			$default_values = $default_tour_extra_fields;
			$tab_key = 'tour_tabs';
		} elseif ($option['id'] == 'car_rental_extra_fields') {
			$default_values = $default_car_rental_extra_fields;
			$tab_key = 'car_rental_tabs';
		} elseif ($option['id'] == 'location_extra_fields') {
			$default_values = $default_location_extra_fields;
			$tab_key = 'location_tabs';
		} elseif ($option['id'] == 'cruise_extra_fields') {
			$default_values = $default_cruise_extra_fields;
			$tab_key = 'cruise_tabs';
		}

		$tab_array = of_get_option($tab_key);
		$default_tab_array = $byt_theme_of_default_fields->get_default_tab_array($tab_key);
		
		if (!is_array( $tab_array ) || count($tab_array) == 0 || count($tab_array) < count($default_tab_array)) {
			$tab_array = $default_tab_array;
		}
		
		if (!is_array( $values ) || count($values) == 0 ) {
			$values = $default_values;
		}

		$output = '<div class="of-repeat-loop">';
		
		if ($tab_array && count($tab_array) > 0) {

			$output .= '<ul class="sortable of-repeat-extra-fields">';

			if( is_array( $values ) && is_array($tab_array) ) {

				foreach ( (array)$values as $key => $value ){
					if (isset($value['label']) && 
						isset($value['type']) &&
						isset($value['tab_id']) &&
						isset($value['id'])) {
		 
						$output .= '<li class="ui-state-default of-repeat-group">';
						$output .= '<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="input-field-id" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][id]' ) . '" type="hidden" value="' . esc_attr( $value['id'] ) . '" />';					
						$output .= '<div class="of-input-wrap">';
						$output .= '<label data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-label label-field-label" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][label]' ) . '">' . __('Field label', 'bookyourtravel') . '</label>';
						$output .= '<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-input input-field-label" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][label]' ) . '" type="text" value="' . esc_attr( $value['label'] ) . '" />';
						$output .= '</div>';
						$output .= '<div class="of-select-wrap">';
						$output .= '<label data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-label label-field-type" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][type]' ) . '">' . __('Field type', 'bookyourtravel') . '</label>';
						$output .= '<select data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-select select-field-type" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][type]' ) . '">';
						
						foreach($repeatable_field_types as $input_type_key => $input_type_text) {
							$output .= '<option value="' . $input_type_key . '" ' . ($value['type'] == $input_type_key ? 'selected' : '') . '>' . $input_type_text . '</option>';
						}		
						
						$output .= '</select>';
						$output .= '</div>';
						$output .= '<div class="of-select-wrap">';
						$output .= '<label data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-label label-field-tab" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][tab_id]' ) . '">' . __('Field tab', 'bookyourtravel') . '</label>';
						$output .= '<select data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-select select-field-tab" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][tab_id]' ) . '">';
						foreach($tab_array as $tab) {
							if (isset($tab['id']) && isset($tab['label'])) {
								$tab_name = $tab['label'];
								$tab_id = $tab['id'];				
								$output .= '<option value="' . $tab_id . '" ' . (isset($value['tab_id']) && $value['tab_id'] == $tab_id ? 'selected' : '') . '>' . $tab_name . '</option>';
							}
						}		
						$output .= '</select>';
						$output .= '</div>';
						$output .= '<label data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-label label-hide-field" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][hide]' ) . '">' . __('Is hidden?', 'bookyourtravel') . '</label>';
						$output .= '<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-checkbox checkbox-hide-field" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][hide]' ) . '" type="checkbox" value="1" ' . (isset($value['hide']) && $value['hide'] == '1' ? 'checked' : '') . ' />';
						if (isset($value['id']) && isset($value['label']) && count(BYT_Theme_Utils::custom_array_search($default_values, 'id', $value['id'])) == 0) {
							$output .= '<span class="ui-icon ui-icon-close"></span>';
						}
						
						$output .= '</li><!--.of-repeat-group-->';
				 
						$counter++;
					}
				}
			}
		 
			$output .= '<li class="to-copy ui-state-default of-repeat-group">';
			$output .= '<div class="of-input-wrap">';
			$output .= '<label class="of-label label-field-label" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . __('Field label', 'bookyourtravel') . '</label>';
			$output .= '<input class="of-input input-field-label" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="text" value="' . esc_attr( $option['std'] ) . '" />';
			$output .= '</div>';
			$output .= '<div class="of-select-wrap">';
			$output .= '<label class="of-label label-field-type" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . __('Field type', 'bookyourtravel') . '</label>';
			$output .= '<select class="of-select select-field-type" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">';
			foreach($repeatable_field_types as $input_type_key => $input_type_text) {
				$output .= '<option value="' . $input_type_key . '">' . $input_type_text . '</option>';
			}
			$output .= '</select>';
			$output .= '</div>';
			$output .= '<div class="of-select-wrap">';
			$output .= '<label class="of-label label-field-tab" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . __('Field tab', 'bookyourtravel') . '</label>';
			$output .= '<select class="of-select select-field-tab" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">';
			if (is_array($tab_array) && count($tab_array)) {
				foreach($tab_array as $tab) {
					$tab_name = isset($tab['label']) ? $tab['label'] : '';
					$tab_id = isset($tab['id']) ? $tab['id'] : '';
					if (!empty($tab_name) && !empty($tab_id))
						$output .= '<option value="' . $tab_id . '">' . $tab_name . '</option>';
				}		
			}
			$output .= '</select>';
			$output .= '</div>';
			$output .= '<label class="of-label label-hide-field" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . __('Is hidden?', 'bookyourtravel') . '</label>';
			$output .= '<input class="of-checkbox checkbox-hide-field" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="checkbox" value="1" />';
			$output .= '<span class="ui-icon ui-icon-close"></span>';
			$output .= '</li><!--.of-repeat-group-->'; 
			$output .= '</ul><!--.sortable-->';
			$output .= '<a href="#" class="docopy_field button icon add">' . __('Add field', 'bookyourtravel') . '</a>';
		} else {
			$output .= '<p>' . __('Please hit the "Save Options" button to create the initial collection of tabs so that extra fields can be associated with tabs correctly.', 'bookyourtravel') . '</p>';
		}
		$output .= '</div><!--.of-repeat-loop-->';

		return $output;

	}

	function link_button_field_option_type ( $option_name, $option, $values) {

		$button_text = $option['name'];
		if (isset($option['text'])) {
			$button_text = $option['text'];
		}
	
		$output = '<div class="of-input">';
		$output .= '<a href="#" class="button-secondary of-button-field ' . $option['id'] . '">' . $button_text . '</a>';
		if ($option['id'] == 'synchronise_reviews' || 
			$option['id'] == 'fix_partial_booking_issue' ||
			$option['id'] == 'upgrade_byt_database') {
			$output .= '<div style="display:none" class="loading">...</div>';
		}
		$output .= '</div>';

		return $output;
	}
	
	function dummy_text_option_type ( $option_name, $option, $values) {

		$output = '';

		return $output;
	}

	function repeat_review_field_option_type( $option_name, $option, $values ){

		global $byt_theme_of_default_fields;
	
		$counter = 0;
		
		$default_values = $byt_theme_of_default_fields->get_default_review_fields_array($option['id']);
		if (!is_array( $values ) || count($values) == 0 ) {
			$values = $default_values;
		}
		
		$post_type = '';
		if ($option['id'] == 'accommodation_review_fields')
			$post_type = 'accommodation';
		elseif ($option['id'] == 'tour_review_fields')
			$post_type = 'tour';
		elseif ($option['id'] == 'cruise_review_fields')
			$post_type = 'cruise';
			
		$output = '<div class="of-repeat-loop">';
		
		$output .= '<ul class="sortable of-repeat-review-fields">';

		if ( is_array( $values ) ) {

			foreach ( (array)$values as $key => $value ){
				if (isset($value['label']) && 
					isset($value['post_type']) &&
					isset($value['id'])) {
	 
					$output .= '<li class="ui-state-default of-repeat-group">';
					$output .= '<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="input-field-id" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][id]' ) . '" type="hidden" value="' . esc_attr( $value['id'] ) . '" />';					
					$output .= '<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][post_type]' ) . '" type="hidden" value="' . $post_type . '" />';									
					$output .= '<div class="of-input-wrap">';
					$output .= '<label data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-label label-field-label" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][label]' ) . '">' . __('Field label', 'bookyourtravel') . '</label>';
					$output .= '<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-input input-field-label" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][label]' ) . '" type="text" value="' . esc_attr( $value['label'] ) . '" />';
					$output .= '</div>';
					$output .= '<label data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-label label-hide-field" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][hide]' ) . '">' . __('Is hidden?', 'bookyourtravel') . '</label>';
					$output .= '<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-checkbox checkbox-hide-field" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$counter.'][hide]' ) . '" type="checkbox" value="1" ' . (isset($value['hide']) && $value['hide'] == '1' ? 'checked' : '') . ' />';
					if (isset($value['id']) && isset($value['label']) && count(BYT_Theme_Utils::custom_array_search($default_values, 'id', $value['id'])) == 0) {
						$output .= '<span class="ui-icon ui-icon-close"></span>';
					}
					
					$output .= '</li><!--.of-repeat-group-->';
			 
					$counter++;
				}
			}
		}
	 
		$output .= '<li class="to-copy ui-state-default of-repeat-group">';
		$output .= '<input class="input-post-type" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="hidden" value="' . $post_type . '" />';									
		$output .= '<div class="of-input-wrap">';
		$output .= '<label class="of-label label-field-label" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . __('Field label', 'bookyourtravel') . '</label>';
		$output .= '<input class="of-input input-field-label" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="text" value="' . esc_attr( $option['std'] ) . '" />';
		$output .= '</div>';
		$output .= '<label class="of-label label-hide-field" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . __('Is hidden?', 'bookyourtravel') . '</label>';
		$output .= '<input class="of-checkbox checkbox-hide-field" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="checkbox" value="1" />';
		$output .= '<span class="ui-icon ui-icon-close"></span>';
		$output .= '</li><!--.of-repeat-group-->'; 
		$output .= '</ul><!--.sortable-->';
		$output .= '<a href="#" class="docopy_review_field button icon add">' . __('Add review field', 'bookyourtravel') . '</a>';
		$output .= '</div><!--.of-repeat-loop-->';

		return $output;

	}

	function get_option_id_context($option_id) {

		$option_id_context = '';
		
		if ($option_id == 'location_extra_fields')
			$option_id_context = 'Location extra field';
		elseif ($option_id == 'location_tabs')
			$option_id_context = 'Location tab';
		elseif ($option_id == 'accommodation_extra_fields')
			$option_id_context = 'Accommodation extra field';
		elseif ($option_id == 'accommodation_tabs')
			$option_id_context = 'Accommodation tab';
		elseif ($option_id == 'tour_extra_fields')
			$option_id_context = 'Tour extra field';
		elseif ($option_id == 'tour_tabs')
			$option_id_context = 'Tour tab';
		elseif ($option_id == 'car_rental_extra_fields')
			$option_id_context = 'Car rental extra field';
		elseif ($option_id == 'car_rental_tabs')
			$option_id_context = 'Car rental tab';
		elseif ($option_id == 'cruise_extra_fields')
			$option_id_context = 'Cruise extra field';
		elseif ($option_id == 'cruise_tabs')
			$option_id_context = 'Cruise tab';
		elseif ($option_id == 'accommodation_review_fields')
			$option_id_context = 'Accommodation review field';
		elseif ($option_id == 'tour_review_fields')
			$option_id_context = 'Tour review field';	
		elseif ($option_id == 'cruise_review_fields')
			$option_id_context = 'Cruise review field';
			
		return $option_id_context;
	}
	
	/*
	 * Sanitize Repeat review inputs
	 */
	function sanitize_repeat_review_field( $fields, $option ){	
		$results = array();
		if (is_array($fields)) {
			for ($i = 0; $i < count($fields); $i++) { 
				if (isset($fields[$i])) {
					$field = $fields[$i];
					if (!isset($field['id']) && isset($field['label'])) {
						$field['id'] = 'review_' . URLify::filter($field['label']);
					}
					
					if (isset($field['label']))
						$this->register_dynamic_string_for_translation($this->get_option_id_context($option['id']) . ' ' . $field['label'], $field['label']);
					
					$results[] = $field;
				}
			}
		}
		return $results;
	}
	
	/*
	 * Sanitize Repeat inputs
	 */
	function sanitize_repeat_extra_field( $fields, $option ){	
		$results = array();
		if (is_array($fields)) {
			for ($i = 0; $i < count($fields); $i++) { 
				if (isset($fields[$i])) {
					$field = $fields[$i];
					if (!isset($field['id']) && isset($field['label'])) {
						$field['id'] = URLify::filter($field['label']);
					}
						
					if (isset($field['label']))
						$this->register_dynamic_string_for_translation($this->get_option_id_context($option['id']) . ' ' . $field['label'], $field['label']);
						
					$results[] = $field;
				}
			}
		}
		return $results;
	}
	

	/*
	 * Sanitize Repeat tabs
	 */
	function sanitize_repeat_tab( $tabs, $option ){
		$results = array();
		if (is_array($tabs)) {
			for ($i = 0; $i < count($tabs); $i++) { 
				if (isset($tabs[$i])) {
					$tab = $tabs[$i];
					if (!isset($tab['id']) && isset($tab['label'])) {
						$tab['id'] = URLify::filter($tab['label']);
					}
					
					if (isset($tab['label']))
						$this->register_dynamic_string_for_translation($this->get_option_id_context($option['id']) . ' ' . $tab['label'], $tab['label']);

					$results[] = $tab;
				}
			}
		}
		return $results;
	}
	
	/*
	 * Custom repeating field scripts
	 * Add and Delete buttons
	 */
	function of_byt_options_script(){	
		global $byt_theme_globals;?>
		<style>
			#optionsframework .to-copy {display: none;}
		</style>
		<script type="text/javascript">
		<?php
			echo '	window.adminAjaxUrl = "' . admin_url('admin-ajax.php') . '";';
			echo '  window.adminSiteUrl = "' . admin_url('themes.php?page=options-framework') . '";';
		?>	
		jQuery(function($){
		
			$(".synchronise_reviews").on('click', function(e) {
				
				var parentDiv = $(this).parent();
				var loadingDiv = parentDiv.find('.loading');
				loadingDiv.show();
				var _wpnonce = jQuery('#_wpnonce').val();
					
				var dataObj = {
						'action':'sync_reviews_ajax_request',
						'nonce' : _wpnonce }				  

				jQuery.ajax({
					url: window.adminAjaxUrl,
					data: dataObj,
					success:function(json) {
						// This outputs the result of the ajax request
						loadingDiv.hide();
					},
					error: function(errorThrown){
						
					}
				}); 
				
				e.preventDefault();
			});
			
			$(".upgrade_byt_database").on('click', function(e) {
				
				var parentDiv = $(this).parent();
				var loadingDiv = parentDiv.find('.loading');
				loadingDiv.show();
				var _wpnonce = jQuery('#_wpnonce').val();
					
				var dataObj = {
						'action':'upgrade_byt_database_ajax_request',
						'nonce' : _wpnonce }				  

				jQuery.ajax({
					url: window.adminAjaxUrl,
					data: dataObj,
					success:function(json) {
						// This outputs the result of the ajax request
						loadingDiv.hide();
						window.location = window.adminSiteUrl;
					},
					error: function(errorThrown){
						console.log(errorThrown);
					}
				}); 
				
				e.preventDefault();
			});
			
			$(".fix_partial_booking_issue").on('click', function(e) {
				
				var parentDiv = $(this).parent();
				var loadingDiv = parentDiv.find('.loading');
				loadingDiv.show();
				var _wpnonce = jQuery('#_wpnonce').val();
					
				var dataObj = {
						'action':'fix_partial_booking_issue_ajax_request',
						'nonce' : _wpnonce }				  

				jQuery.ajax({
					url: window.adminAjaxUrl,
					data: dataObj,
					success:function(json) {
						// This outputs the result of the ajax request
						loadingDiv.hide();
					},
					error: function(errorThrown){
						
					}
				}); 
				
				e.preventDefault();
			});
	 
			$(".of-repeat-review-fields").sortable({
				update: function(event, ui) {
					var count = 0;
					
					$section = $(this).closest(".section");
					$tab_loop = $section.find('.of-repeat-review-fields');	
					$tab_loop.find('.of-repeat-group').each(function (index, element) {

						$this = $(this);
						
						if (!$this.hasClass('to-copy')) {						
						
							$input_field_id = $this.find('input.input-field-id');
							input_field_id_name = $input_field_id.attr('data-rel');
							$input_field_id.attr('name', input_field_id_name + '[' + index + '][id]');
							
							$input_field_label = $this.find('input.input-field-label');
							input_field_label_name = $input_field_label.attr('data-rel');
							$input_field_label.attr('name', input_field_label_name + '[' + index + '][label]');

							$input_post_type = $this.find('input.input-post-type');
							input_post_type_name = $input_post_type.attr('data-rel');
							$input_post_type.attr('name', input_post_type_name + '[' + index + '][post_type]');

							$checkbox_hide_field = $this.find('input.checkbox-hide-field');
							checkbox_hide_field_name = $checkbox_hide_field.attr('data-rel'); 
							$checkbox_hide_field.attr('name', checkbox_hide_field_name + '[' + index + '][hide]'); 
							
							$label_hide_field = $this.find('label.label-hide-field');
							label_hide_field_for = checkbox_hide_field_name + '[' + index + '][hide]';
							$label_hide_field.attr('for', label_hide_field_for);
							
						}
						
					});
					
				}
			});

	 
			$(".docopy_review_field").on("click", function(e){
	 
			  // the loop object
			  $section = $(this).closest(".section");
			  $field_loop = $section.find('.of-repeat-review-fields');
	 
			  // the group to copy
			  $to_copy = $field_loop.find('.to-copy');
			  $group = $to_copy.clone();
			  $group.removeClass('to-copy');
			  $group.insertBefore($to_copy);

			  count = $field_loop.children('.of-repeat-group').not('.to-copy').length;
			  
			  // the new input
			  $input_field_label = $group.find('input.input-field-label');
			  input_field_label_name = $input_field_label.attr('data-rel');
			  $input_field_label.attr('name', input_field_label_name + '[' + ( count - 1 ) + '][label]');
			  
			  $input_post_type = $group.find('input.input-post-type');
			  input_post_type_name = $input_post_type.attr('data-rel');
			  $input_post_type.attr('name', input_post_type_name + '[' + ( count - 1 ) + '][post_type]');
			  
			  $checkbox_hide_field = $group.find('input.checkbox-hide-field');
			  checkbox_hide_field_name = $checkbox_hide_field.attr('data-rel'); 
			  $checkbox_hide_field.attr('name', checkbox_hide_field_name + '[' + ( count - 1 ) + '][hide]'); 
			  
			  $label_hide_field = $group.find('label.label-hide-field');
			  label_hide_field_for = checkbox_hide_field_name + '[' + ( count - 1 ) + '][hide]';
			  $label_hide_field.attr('for', label_hide_field_for);
			  
			  bindIconCloseEvent();
			  
			  e.preventDefault();
	 
			});
		
			$(".of-repeat-extra-fields").sortable({
				update: function(event, ui) {
					var count = 0;
					
					$section = $(this).closest(".section");
					$field_loop = $section.find('.of-repeat-extra-fields');	
					$field_loop.find('.of-repeat-group').each(function (index, element) {

						$this = $(this);
						
						if (!$this.hasClass('to-copy')) {						
							$input_field_id = $this.find('input.input-field-id');
							input_field_id_name = $input_field_id.attr('data-rel');
							$input_field_id.attr('name', input_field_id_name + '[' + index + '][id]');

							$input_field_label = $this.find('input.input-field-label');
							input_field_label_name = $input_field_label.attr('data-rel');
							$input_field_label.attr('name', input_field_label_name + '[' + index + '][label]');
							$label_field_label = $this.find('label.label-field-label');
							$label_field_label.attr('for', input_field_label_name + '[' + index + '][label]');

							$select_field_type = $this.find('select.select-field-type');
							select_field_type_name = $select_field_type.attr('data-rel');
							$select_field_type.attr('name', select_field_type_name + '[' + index + '][type]'); 
							$label_field_type = $this.find('label.label-field-type');
							$label_field_type.attr('for', select_field_type_name + '[' + index + '][type]');

							$select_field_tab = $this.find('select.select-field-tab');
							select_field_tab_name = $select_field_tab.attr('data-rel');
							$select_field_tab.attr('name', select_field_tab_name + '[' + index + '][tab_id]'); 
							$label_field_tab = $this.find('label.label-field-tab');
							$label_field_tab.attr('for', select_field_tab_name + '[' + index + '][tab_id]');

							$checkbox_hide_field = $this.find('input.checkbox-hide-field');
							checkbox_hide_field_name = $checkbox_hide_field.attr('data-rel');
							$checkbox_hide_field.attr('name', checkbox_hide_field_name + '[' + index + '][hide]'); 
							
							$label_hide_field = $this.find('label.label-hide-field');
							label_hide_field_for = checkbox_hide_field_name + '[' + index + '][hide]';
							$label_hide_field.attr('for', label_hide_field_for);
						}
						
					});
					
				}
			});
	 
			$(".docopy_field").on("click", function(e){
	 
			  // the loop object
			  $section = $(this).closest(".section");
			  $field_loop = $section.find('.of-repeat-extra-fields');
	 
			  // the group to copy
			  $to_copy = $field_loop.find('.to-copy');
			  $group = $to_copy.clone();
			  $group.removeClass('to-copy');
			  $group.insertBefore($to_copy);

			  count = $field_loop.children('.of-repeat-group').not('.to-copy').length;
			  
			  // the new input
			  $input_field_label = $group.find('input.input-field-label');
			  input_field_label_name = $input_field_label.attr('data-rel');
			  $input_field_label.attr('name', input_field_label_name + '[' + ( count - 1 ) + '][label]');
			  
			  $select_field_type = $group.find('select.select-field-type');
			  select_field_type_name = $select_field_type.attr('data-rel');
			  $select_field_type.attr('name', select_field_type_name + '[' + ( count - 1 ) + '][type]'); 
			  
			  $select_field_tab = $group.find('select.select-field-tab');
			  select_field_tab_name = $select_field_tab.attr('data-rel');
			  $select_field_tab.attr('name', select_field_tab_name + '[' + ( count - 1 ) + '][tab_id]'); 

			  $checkbox_hide_field = $group.find('input.checkbox-hide-field');
			  checkbox_hide_field_name = $checkbox_hide_field.attr('data-rel');
			  $checkbox_hide_field.attr('name', checkbox_hide_field_name + '[' + ( count - 1 ) + '][hide]'); 
			  
			  $label_hide_field = $group.find('label.label-hide-field');
			  label_hide_field_for = checkbox_hide_field_name + '[' + ( count - 1 ) + '][hide]';
			  $label_hide_field.attr('for', label_hide_field_for);
			  
			  bindIconCloseEvent();
			  
			  e.preventDefault();
	 
			});
			
			$(".of-repeat-tabs").sortable({
				update: function(event, ui) {
					var count = 0;
					
					$section = $(this).closest(".section");
					$tab_loop = $section.find('.of-repeat-tabs');	
					$tab_loop.find('.of-repeat-group').each(function (index, element) {

						$this = $(this);
						
						if (!$this.hasClass('to-copy')) {
						
							$input_tab_id = $this.find('input.input-tab-id');
							input_tab_id_name = $input_tab_id.attr('data-rel');
							$input_tab_id.attr('name', input_tab_id_name + '[' + ( index ) + '][id]');

							$input_tab_label = $this.find('input.input-tab-label');
							input_tab_label_name = $input_tab_label.attr('data-rel');
							$input_tab_label.attr('name', input_tab_label_name + '[' + index + '][label]'); 
							
							$checkbox_hide_tab = $this.find('input.checkbox-hide-tab');
							checkbox_hide_tab_name = $checkbox_hide_tab.attr('data-rel');
							$checkbox_hide_tab.attr('name', checkbox_hide_tab_name + '[' + index + '][hide]'); 
							
							$label_hide_tab = $this.find('label.label-hide-tab');
							label_hide_tab_for = checkbox_hide_tab_name + '[' + index + '][hide]';
							$label_hide_tab.attr('for', label_hide_tab_for);
						}
						
					});
					
				}
			});
	 
			$(".docopy_tab").on("click", function(e){
	 
			  // the loop object
			  $section = $(this).closest(".section");
			  $tab_loop = $section.find('.of-repeat-tabs');		  
	 
			  // the group to copy
			  $to_copy = $tab_loop.find('.to-copy');
			  $group = $to_copy.clone();
			  $group.removeClass('to-copy');
			  $group.insertBefore($to_copy);
	 
			  // the new input
			  $input_tab_label = $group.find('input.input-tab-label');
			  $checkbox_hide_tab = $group.find('input.checkbox-hide-tab');
			  $label_hide_tab = $group.find('label.label-hide-tab');
			  
			  input_tab_label_name = $input_tab_label.attr('data-rel');
			  checkbox_hide_tab_name = $checkbox_hide_tab.attr('data-rel');
			  
			  count = $tab_loop.children('.of-repeat-group').not('.to-copy').length;
	 
			  $input_tab_label.attr('name', input_tab_label_name + '[' + ( count - 1 ) + '][label]'); 
			  $checkbox_hide_tab.attr('name', checkbox_hide_tab_name + '[' + ( count - 1 ) + '][hide]'); 

			  label_hide_tab_for = checkbox_hide_tab_name + '[' + ( count - 1 ) + '][hide]';
			  $label_hide_tab.attr('for', label_hide_tab_for);
			  
			  bindIconCloseEvent();
			  
			  e.preventDefault();
			});
			
			bindIconCloseEvent();	
			
			function bindIconCloseEvent() {
				/* Bind the X behavior to the original elements*/
				$(".ui-icon-close").click(function() {
					$(this).parent().remove();
					return false;
				});
			}
			
			bindTabVisibility('accommodations', 'enable_accommodations');
			bindTabVisibility('tours', 'enable_tours');
			bindTabVisibility('carrentals', 'enable_car_rentals');
			bindTabVisibility('cruises', 'enable_cruises');
			bindTabVisibility('reviews', 'enable_reviews');
			
			function bindTabVisibility(groupClass, checkboxId) {
				toggleTabVisibility($("#" + checkboxId).is(':checked'), groupClass, checkboxId);
				
				$("#" + checkboxId).change(function() {
					toggleTabVisibility(this.checked, groupClass, checkboxId);
				});
			}
			
			function toggleTabVisibility(show, groupClass, checkboxId) {
				if (show){
					$(".group." + groupClass).children().show();
				} else {
					$(".group." + groupClass).children().hide();
					$("#section-" + checkboxId).show();
					$(".group." + groupClass + " > h3").show();
					$("#section-" + checkboxId).children().show();	
				}		
			}
			
		});
		 
		</script>
	<?php
	}
}

// store the instance in a variable to be retrieved later and call init
$byt_theme_of_custom = BYT_Theme_Of_Custom::get_instance();
$byt_theme_of_custom->init();