<?php
add_filter( 'body_class', 'smcs_administrator_body_class' );
add_action( 'template_redirect', 'smcs_redirect_parent_home' );

// Adds a class of administrator if the logged in user is an administrator.
function smcs_administrator_body_class( $classes ) {
	if ( is_admin() ) {
		return $classes;
	}

	if ( current_user_can( 'administrator' ) ) {
		$classes[] = 'is-administrator';
	}
	return $classes;
}

// redirect parents to the parent-home for homepage.
function smcs_redirect_parent_home() {
	if ( current_user_can( 'sm_family' ) && is_front_page() ) {
		wp_redirect( home_url( '/parent-home/' ) );
		exit();
	}
}
