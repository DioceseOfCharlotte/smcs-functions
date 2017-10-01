<?php
/**
 * Post Types.
 *
 * @package  SMCS
 */

add_action( 'init', 'smcs_register_post_types' );
add_action( 'init', 'registration_forms_cpt' );

/**
 * Register post_types.
 *
 * @since  0.1.0
 * @access public
 */
function smcs_register_post_types() {

	register_extended_post_type( 'smcs_athletics',
		array(
			'admin_cols' => array(
				'featured_image' => array(
					'title'          => 'Image',
					'featured_image' => 'thumbnail',
					'width'          => 60,
					'height'         => 60,
				),
				'athletics_season' => array(
					'taxonomy' => 'athletics_season',
				),
				'last_modified' => array(
					'post_field' => 'post_modified',
				),
			),
			'admin_filters' => array(
				'athletics_season' => array(
					'taxonomy' => 'athletics_season',
				),
			),
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'           => 'dashicons-megaphone',
			'menu_position'       => 25,
			'has_archive'         => false,
		),
		array(
			'singular' => 'Athletics Page',
			'plural'   => 'Athletics',
			'slug'     => 'smcs-athletics',
		)
	);

	register_extended_taxonomy( 'athletics_season', 'smcs_athletics',
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


// Register Custom Post Type
function registration_forms_cpt() {

	$labels = array(
		'name'                  => _x( 'Registration Pages', 'Post Type General Name', 'smcs' ),
		'singular_name'         => _x( 'Registration Page', 'Post Type Singular Name', 'smcs' ),
		'menu_name'             => __( 'Registration Pages', 'smcs' ),
		'name_admin_bar'        => __( 'Registration Page', 'smcs' ),
		'archives'              => __( 'Registration Page Archives', 'smcs' ),
		'attributes'            => __( 'Page Attributes', 'smcs' ),
		'parent_item_colon'     => __( 'Parent Page:', 'smcs' ),
		'all_items'             => __( 'All Pages', 'smcs' ),
		'add_new_item'          => __( 'Add New Page', 'smcs' ),
		'add_new'               => __( 'Add New', 'smcs' ),
		'new_item'              => __( 'New Page', 'smcs' ),
		'edit_item'             => __( 'Edit Page', 'smcs' ),
		'update_item'           => __( 'Update Page', 'smcs' ),
		'view_item'             => __( 'View Page', 'smcs' ),
		'view_items'            => __( 'View Pages', 'smcs' ),
		'search_items'          => __( 'Search Page', 'smcs' ),
		'not_found'             => __( 'Not found', 'smcs' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'smcs' ),
		'featured_image'        => __( 'Featured Image', 'smcs' ),
		'set_featured_image'    => __( 'Set featured image', 'smcs' ),
		'remove_featured_image' => __( 'Remove featured image', 'smcs' ),
		'use_featured_image'    => __( 'Use as featured image', 'smcs' ),
		'insert_into_item'      => __( 'Insert into Page', 'smcs' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Page', 'smcs' ),
		'items_list'            => __( 'Items list', 'smcs' ),
		'items_list_navigation' => __( 'Items list navigation', 'smcs' ),
		'filter_items_list'     => __( 'Filter items list', 'smcs' ),
	);
	$rewrite = array(
		'slug'                  => 'accounts',
		'with_front'            => false,
		'pages'                 => false,
		'feeds'                 => false,
	);
	$args = array(
		'label'                 => __( 'Registration Page', 'smcs' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 25,
		'menu_icon'             => 'dashicons-id-alt',
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
	);
	register_post_type( 'registration_pages', $args );

}
