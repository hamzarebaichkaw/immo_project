<?php
/**
 * YOAST SEO compatibility
 * WE ensure that Listable plays nicely with YOAST SEO
 * See: https://wordpress.org/plugins/wordpress-seo/
 *
 * @package Listable
 */

if ( ! function_exists( 'listable_yoast_listing_image' ) ) {
	function listable_yoast_listing_image( $metadata, $object_id, $meta_key, $single ) {

		//WPSEO wants to get all the metas, hence $meta_key is empty and so on
		//When a featured image is defined we don't want to do anything
		if ( null === $metadata && empty( $meta_key ) && false === $single && 'job_listing' == get_post_type( $object_id ) && ! has_post_thumbnail( $object_id ) ) {
			remove_filter( 'get_post_metadata', 'listable_yoast_listing_image', 100 );
			$current_meta = get_post_meta( $object_id, '', false );
			add_filter( 'get_post_metadata', 'listable_yoast_listing_image', 100, 4 );

			if ( empty( $current_meta[ WPSEO_Meta::$meta_prefix . 'twitter-image' ][0] ) ) {
				//get the first image in the listing's gallery
				$photos = listable_get_listing_gallery_ids();
				if ( ! empty( $photos[0] ) ) {
					$current_meta[ WPSEO_Meta::$meta_prefix . 'twitter-image' ][0] = wp_get_attachment_url( $photos[0] );
				}
			}

			if ( empty( $current_meta[ WPSEO_Meta::$meta_prefix . 'opengraph-image' ][0] ) ) {
				if ( empty( $photos ) ) {
					//get the first image in the listing's gallery
					$photos = listable_get_listing_gallery_ids();
				}
				if ( ! empty( $photos[0] ) ) {
					$current_meta[ WPSEO_Meta::$meta_prefix . 'opengraph-image' ][0] = wp_get_attachment_url( $photos[0] );
				}
			}

			return $current_meta;
		}

		// Return original if the check does not pass
		return $metadata;
	}
	add_filter( 'get_post_metadata', 'listable_yoast_listing_image', 100, 4 );
}