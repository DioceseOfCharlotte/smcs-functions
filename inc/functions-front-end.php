<?php
add_action( 'init', 'sm_register_shortcodes' );

add_filter( 'login_redirect', 'smcs_login_redirect', 10, 3 );
add_filter( 'body_class', 'smcs_administrator_body_class' );
add_action( 'template_redirect', 'smcs_redirect_parent_home' );

function sm_register_shortcodes() {
	// Add the `[sm_login_form]` shortcode.
	add_shortcode( 'sm_login_form', 'sm_login_form_shortcode' );
}

// Login-form shortcode with redirect.
function sm_login_form_shortcode() {

	$logoutlink = wp_logout_url( home_url() );
	$passresetlink = wp_lostpassword_url();

	if ( is_user_logged_in() ) {
		return '<h2>You are already logged in!</h2><a class="btn button logout-button" href="' . $logoutlink . '">Logout</a>';
	}

	$user = wp_get_current_user();

	$url = home_url( '/parent-home/' );

	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		// athletics admins
		if ( in_array( 'sm_sports_admin', $user->roles ) ) {
			$url = home_url( '/administration-athletics-home/' );

			// pto admins
		} elseif ( in_array( 'sm_pto_admin', $user->roles ) ) {
			$url = home_url( '/admin-pto/' );

			// most others
		} else {
			$url = home_url( '/parent-home/' );
		}
	}

	$args = array(
		'echo' => false,
		'redirect' => $url,
	);

	return wp_login_form( $args ) . '<a class="reset-pass-link" href="' . $passresetlink . '" title="Lost Password">Lost your password?</a>';
}

// Login redirects.
function smcs_login_redirect( $url, $request, $user ) {

	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		// athletics admins
		if ( in_array( 'sm_sports_admin', $user->roles ) ) {
			$url = home_url( '/administration-athletics-home/' );

			// pto admins
		} elseif ( in_array( 'sm_pto_admin', $user->roles ) ) {
			$url = home_url( '/admin-pto/' );

			// most others
		} else {
			$url = home_url( '/parent-home/' );
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
