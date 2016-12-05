<?php

class byt_cabin_type extends byt_entity
{
    public function __construct( $entity ) {
		parent::__construct( $entity, 'cabin_type' );
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
}