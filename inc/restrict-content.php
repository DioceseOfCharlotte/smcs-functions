<?php
/**
 * Restrict Content Pro.
 *
 * @package  SMCS
 */

add_action( 'current_screen', function() {
	add_filter( 'get_pages', 'jp_rcp_pages_filter' );
});

function jp_rcp_pages_filter( $pages ) {

	if ( 'restrict_page_rcp-settings' !== get_current_screen()->id ) {
		return $pages;
	}

	remove_filter( 'get_pages', 'jp_rcp_pages_filter' );

	$custom_posts = get_pages( array(
		'post_type' => 'registration_pages', // change to your custom post type
		'post_status' => 'publish',
	) );

	add_filter( 'get_pages', 'jp_rcp_pages_filter', 10, 2 );

	foreach( $custom_posts as $custom_post ) {
		$pages[] = $custom_post;
	}

	return $pages;
}
