<?php
add_filter( 'login_redirect', 'smcs_login_redirect', 10, 3 );
add_filter( 'body_class', 'smcs_administrator_body_class' );
add_action( 'template_redirect', 'smcs_redirect_parent_home' );



// Login redirects.
function smcs_login_redirect( $url, $request, $user ) {

	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		// athletics admins
		if ( in_array( 'sm_sports_admin', $user->roles ) ) {
			$url = home_url( 'administration-athletics-home' );

			// pto admins
		} elseif ( in_array( 'sm_pto_admin', $user->roles ) ) {
			$url = home_url( 'admin-pto' );

			// most others
		} else {
			$url = home_url( 'parent-home' );
		}
	}

	return $url;
}

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
	if ( current_user_can( 'sm_sports_admin' ) && is_front_page() ) {
		wp_redirect( home_url( '/administration-athletics-home/' ) );
		exit();
	} elseif ( current_user_can( 'sm_pto_admin' ) && is_front_page() ) {
		wp_redirect( home_url( '/admin-pto/' ) );
		exit();
	} elseif ( is_user_logged_in() && is_front_page() ) {
		wp_redirect( home_url( '/parent-home/' ) );
		exit();
	}
}
