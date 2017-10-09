<?php

# Add shortcodes.
add_action( 'init', 'smcs_register_shortcodes' );

function smcs_register_shortcodes() {
	// Add the `[sm_login_form]` shortcode.
	add_shortcode( 'sm_login_form', 'sm_login_form_shortcode' );
	// Add the `[sm_address]` shortcode.
	add_shortcode( 'sm_address', 'sm_address_shortcode' );
	// Add the `[sm_family_name]` shortcode.
	add_shortcode( 'sm_family_name', 'sm_family_name_shortcode' );
	// Add the `[sm_home_phone]` shortcode.
	add_shortcode( 'sm_home_phone', 'sm_home_phone_shortcode' );
}

function sm_family_name_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'wrapper' => 'h3',
			'class' => 'sm-family-name',
		),
		$atts,
		'sm_family_name'
	);

	$user_id = sm_get_group_owner_id();
	$sm_family = rcpga_group_accounts()->members->get_group_name( $user_id );
	$sm_family_name = '';

	if ( $sm_family ) {
		$sm_family_name .= '<' . $atts['wrapper'] . ' class="' . $atts['class'] . '">';
		$sm_family_name .= "{$sm_family}";
		$sm_family_name .= '</' . $atts['wrapper'] . '>';
	}

	return $sm_family_name;

}

function sm_home_phone_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'wrapper' => 'div',
			'class' => 'sm-home-phone',
		),
		$atts,
		'sm_home_phone'
	);

	$user_id = sm_get_group_owner_id();
	$sm_phone = get_user_meta( $user_id, 'sm_home_phone', true );
	$sm_home_phone = '';

	if ( $sm_phone ) {
		$sm_home_phone .= '<' . $atts['wrapper'] . ' class="' . $atts['class'] . '">';
		$sm_home_phone .= "{$sm_phone}";
		$sm_home_phone .= '</' . $atts['wrapper'] . '>';
	}

	return $sm_home_phone;

}

function sm_address_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'wrapper' => 'div',
			'class' => 'sm-address',
		),
		$atts,
		'sm_address'
	);

	$user_id = sm_get_group_owner_id();

	$sm_street = get_user_meta( $user_id, 'sm_home_street', true );
	$sm_city = get_user_meta( $user_id, 'sm_home_city', true );
	$sm_state = get_user_meta( $user_id, 'sm_home_state', true );
	$sm_home_zip = get_user_meta( $user_id, 'sm_home_zip', true );

	$sm_address = '';

	if ( $sm_city ) {
		$sm_address .= '<' . $atts['wrapper'] . ' class="' . $atts['class'] . '">';
		$sm_address .= "{$sm_street}<br>";
		$sm_address .= "{$sm_city}, {$sm_state} {$sm_home_zip}";
		$sm_address .= '</' . $atts['wrapper'] . '>';
	}

	return $sm_address;

}

// Add Shortcode
function sm_group_meta_shortcode( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'key' => 'first_name',
			'wrapper' => 'div',
			'class' => 'sm-group-data',
		),
		$atts,
		'sm_group_meta'
	);

}
add_shortcode( 'sm_group_meta', 'sm_group_meta_shortcode' );

// Login-form shortcode with redirect.
function sm_login_form_shortcode() {

	$logoutlink = wp_logout_url( home_url() );
	$passresetlink = wp_lostpassword_url();

	if ( is_user_logged_in() ) {
		return '<h2>You are already logged in!</h2><a class="btn button logout-button" href="' . $logoutlink . '">Logout</a>';
	}

	$user = wp_get_current_user();
	$url = home_url( 'parent-home' );

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

	$args = array(
		'echo' => false,
		'redirect' => $url,
	);

	return wp_login_form( $args ) . '<a class="reset-pass-link" href="' . $passresetlink . '" title="Lost Password">Lost your password?</a>';
}
