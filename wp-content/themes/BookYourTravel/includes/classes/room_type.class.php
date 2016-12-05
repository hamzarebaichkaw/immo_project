<?php

class byt_room_type extends byt_entity
{
    public function __construct( $entity ) {
		parent::__construct( $entity, 'room_type' );
    }

	public function get_facilities() {
		return wp_get_post_terms($this->get_id(), 'facility', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all'));	
	}
	
	public function get_facilities_string() {
		$facilities_string = "";	
		$facilities = $this->get_facilities();
		if ($facilities && count($facilities) > 0) {
			for( $i = 0; $i < count($facilities); $i++) {
				$facility = $facilities[$i];
				$facilities_string .= $facility->name . ', ';
			}
			$facilities_string = rtrim($facilities_string, ', ');
		}
		return $facilities_string;
	}	
	
	public function get_field_value($field_name, $use_prefix = true) {
		if ( $field_name == 'facilities' ) {
			$facility_ids = array();
			$facilities = $this->get_facilities();
			if ( $facilities && count($facilities) > 0) {
				for( $i = 0; $i < count($facilities); $i++) {
					$facility = $facilities[$i];
					$facility_ids[] = $facility->term_id;
				}
			}
			return $facility_ids;
		} elseif ( $field_name == 'post_title' )
			return $this->post->post_title;
		elseif ( $field_name == 'post_content' )
			return $this->post->post_content;
		else
			return $this->get_custom_field($field_name, $use_prefix);			
	}
}