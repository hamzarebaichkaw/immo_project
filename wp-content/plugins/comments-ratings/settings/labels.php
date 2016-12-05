<?php

$labels_settings = array(
	'type'    => 'postbox',
	'label'   => esc_html__( 'Labels', 'comments-ratings' ),
	'options' => array(
		'review_rating_label'      => array(
			'label'   => esc_html__( 'Review Rating Label: ', 'comments-ratings' ),
			'default' => esc_html__( 'Your overall rating of this listing:', 'comments-ratings' ),
			'type'    => 'text',
			'size'    => "80"
		),
		'review_title_label'       => array(
			'label'   => esc_html__( 'Review Title Label: ', 'comments-ratings' ),
			'default' => esc_html__( 'Title of your review', 'comments-ratings' ),
			'type'    => 'text',
			'size'    => "80"
		),
		'review_title_placeholder' => array(
			'label'   => esc_html__( 'Review Title Placeholder: ', 'comments-ratings' ),
			'default' => esc_html__( 'Summarize your opinion or highlight an interesting detail', 'comments-ratings' ),
			'type'    => 'text',
			'size'    => "80"
		),
		'review_label'             => array(
			'label'   => esc_html__( 'Review Label: ', 'comments-ratings' ),
			'default' => esc_html__( 'Your Review', 'comments-ratings' ),
			'type'    => 'text',
			'size'    => "80"
		),
		'review_placeholder'       => array(
			'label'   => esc_html__( 'Review Placeholder: ', 'comments-ratings' ),
			'default' => esc_html__( 'Tell about your experience or leave a tip for others', 'comments-ratings' ),
			'type'    => 'text',
			'size'    => "80"
		),
		'review_submit_button'     => array(
			'label'   => esc_html__( 'Review Submit Button: ', 'comments-ratings' ),
			'default' => esc_html__( 'Submit your Review', 'comments-ratings' ),
			'type'    => 'text',
			'size'    => "80"
		),
	)
); # config

return $labels_settings;