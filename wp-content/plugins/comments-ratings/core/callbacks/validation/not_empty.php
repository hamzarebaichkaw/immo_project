<?php defined('ABSPATH') or die;

	function pixreviews_validate_not_empty($fieldvalue, $processor) {
		return ! empty($fieldvalue);
	}
