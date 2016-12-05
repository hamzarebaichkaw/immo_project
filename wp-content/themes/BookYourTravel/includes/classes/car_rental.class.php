<?php

class byt_car_rental extends byt_entity
{
    public function __construct( $entity ) {
		parent::__construct( $entity, 'car_rental' );
    }
	
	public function get_location() {
		$location_id = $this->get_custom_field('location_post_id');
		return $location_id ? new byt_location(intval($location_id)) : '';
	}
	
    public function get_type_name() {	
		$type_objs = wp_get_post_terms( $this->get_id(), 'car_type', array( "fields" => "all" ) );
		return $type_objs ? $type_objs[0]->name : '';
    }
		
	public function get_tags() {
		$type_objs = wp_get_post_terms( $this->get_id(), 'car_rental_tag', array( "fields" => "all" ) );
		return $type_objs;
	}
	
}