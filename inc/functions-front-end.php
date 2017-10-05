<?php
add_filter( 'body_class', 'smcs_administrator_body_class' );

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
