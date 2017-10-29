<?php
/**
 * Taxonomies.
 *
 * @package  SMCS
 */

add_action( 'init', 'sm_access_taxonomy', 11 );

// Register Custom Taxonomy
function sm_access_taxonomy() {

	$post_type_args = array(
		'public'  => true,
		'show_ui' => true,
	);

	$post_types = get_post_types( $post_type_args );

	register_extended_taxonomy(
		'smcs_access', $post_types,
		array(
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,

			'admin_cols'        => array(
				'term_role' => array(
					'title'    => 'Roles',
					'meta_key' => 'members_term_role',
				),
			),
		),
		array(
			'singular' => 'SM Access Role',
			'plural'   => 'SM Access Roles',
			'slug'     => 'smcs_access',
		)
	);

}
