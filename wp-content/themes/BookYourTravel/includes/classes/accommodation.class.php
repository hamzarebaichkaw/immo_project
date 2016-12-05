<?php

class byt_accommodation extends byt_entity
{
    public function __construct( $entity ) {
		parent::__construct( $entity, 'accommodation' );
    }
	
	public function get_location() {
		$location_id = $this->get_custom_field('location_post_id');
		return $location_id ? new byt_location(intval($location_id)) : '';
	}
	
    public function get_type_name() {	
		$type_objs = wp_get_post_terms( $this->get_id(), 'accommodation_type', array( "fields" => "all" ) );
		return $type_objs ? $type_objs[0]->name : '';
    }
	
    public function get_type_id() {	
		$type_objs = wp_get_post_terms( $this->get_id(), 'accommodation_type', array( "fields" => "all" ) );
		return $type_objs ? $type_objs[0]->term_id : null;
    }
	
	public function get_is_price_per_person() {
		$is_price_per_person = $this->get_custom_field( 'is_price_per_person' );
		return isset($is_price_per_person) ? $is_price_per_person : 0;
	}
	
	public function get_is_self_catered() {
		$is_self_catered = $this->get_custom_field( 'is_self_catered' );
		return isset($is_self_catered) ? $is_self_catered : 0;
	}
	
	public function get_is_reservation_only() {
		$is_reservation_only = $this->get_custom_field( 'is_reservation_only' );
		return isset($is_reservation_only) ? $is_reservation_only : 0;
	}
	
	public function get_count_children_stay_free() {
		$count_children_stay_free = $this->get_custom_field( 'count_children_stay_free' );
		return isset($count_children_stay_free) ? $count_children_stay_free : 0;
	}

	public function get_room_types() {
		$room_type_ids = $this->get_custom_field( 'room_types', false );
		return unserialize($room_type_ids);
	}
	
	public function get_tags() {
		$type_objs = wp_get_post_terms( $this->get_id(), 'acc_tag', array( "fields" => "all" ) );
		return $type_objs;
	}
	
	public function get_facilities() {
		return wp_get_post_terms($this->get_id(), 'facility', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all'));	
	}
	
	public function get_field_value($field_name, $use_prefix = true) {
		if ( $field_name == 'facilities' ) {
			$facility_ids = array();
			$facilities = $this->get_facilities();
			if ($facilities && count($facilities) > 0) {
				for( $i = 0; $i < count($facilities); $i++) {
					$facility = $facilities[$i];
					$facility_ids[] = $facility->term_id;
				}
			}
			return $facility_ids;
		} elseif ( $field_name == 'accommodation_type' )
			return $this->get_type_id();
		elseif ( $field_name == 'room_types' )
			return $this->get_custom_field($field_name, false);
		elseif ( $field_name == 'post_title' )
			return $this->post->post_title;
		elseif ( $field_name == 'post_content' )
			return $this->post->post_content;
		else
			return $this->get_custom_field($field_name, $use_prefix);			
	}

}