<?php
/**
 * Post Types.
 *
 * @package  SMCS
 */

add_action( 'init', 'smcs_register_post_types' );

/**
 * Register post_types.
 *
 * @since  0.1.0
 * @access public
 */
function smcs_register_post_types() {

	register_extended_post_type(
		'registration_pages',
		array(
			'admin_cols'    => array(
				'last_modified' => array(
					'post_field' => 'post_modified',
				),
			),
			'supports'      => array( 'title', 'editor' ),
			'menu_icon'     => 'dashicons-id-alt',
			'menu_position' => 25,
			'has_archive'   => false,
		),
		array(
			'singular' => 'Registration Page',
			'plural'   => 'Registration Pages',
			'slug'     => 'accounts',
		)
	);

	register_extended_post_type(
		'smcs_athletics',
		array(
			'admin_cols'    => array(
				'featured_image'   => array(
					'title'          => 'Image',
					'featured_image' => 'thumbnail',
					'width'          => 60,
					'height'         => 60,
				),
				'athletics_season' => array(
					'taxonomy' => 'athletics_season',
				),
				'last_modified'    => array(
					'post_field' => 'post_modified',
				),
			),
			'admin_filters' => array(
				'athletics_season' => array(
					'taxonomy' => 'athletics_season',
				),
			),
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'     => 'dashicons-megaphone',
			'menu_position' => 25,
			'has_archive'   => false,
		),
		array(
			'singular' => 'Athletics Page',
			'plural'   => 'Athletics',
			'slug'     => 'smcs-athletics',
		)
	);

	register_extended_taxonomy(
		'athletics_season', 'smcs_athletics',
		array(
			'meta_box' => 'radio',
		),
		array(
			'singular' => 'Season',
			'plural'   => 'Seasons',
			'slug'     => 'sports-season',
		)
	);

}
