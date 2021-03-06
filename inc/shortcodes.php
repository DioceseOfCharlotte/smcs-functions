<?php

# Add shortcodes.
add_action( 'init', 'smcs_register_shortcodes' );

function smcs_register_shortcodes() {
	// Add the `[sm_login_form]` shortcode.
	add_shortcode( 'sm_login_form', 'sm_login_form_shortcode' );
	// Add the `[sm_if_same_group other="52"]` shortcode.
	add_shortcode( 'sm_if_same_group', 'sm_if_same_group_shortcode' );
	// Add the `[sm_group_expiration id="2"]` shortcode.
	add_shortcode( 'sm_group_expiration', 'sm_group_expiration_shortcode' );
	// Add the `[sm_group_name]` shortcode.
	add_shortcode( 'sm_group_name', 'sm_group_name_shortcode' );
	// Add the `[sm_subscription_id id="2"]` shortcode.
	add_shortcode( 'sm_subscription_id', 'sm_subscription_id_shortcode' );
	// Add the `[sm_has_subscription id="2"]` shortcode.
	add_shortcode( 'sm_has_subscription', 'sm_has_subscription_shortcode' );
	// Add the `[sm_group_is_full not="true"]` shortcode.
	add_shortcode( 'sm_group_is_full', 'sm_group_is_full_shortcode' );
	// Add the `[sm_address]` shortcode.
	add_shortcode( 'sm_address', 'sm_address_shortcode' );
	// Add the `[sm_students id='5']` shortcode.
	add_shortcode( 'sm_students', 'sm_students_shortcode' );
	// Add the `[sm_students id='5']` shortcode.
	add_shortcode( 'sm_student_grades', 'sm_student_grades_shortcode' );
	// Add the `[sm_parent parent="2"]` shortcode.
	add_shortcode( 'sm_parent', 'sm_parent_shortcode' );
	// Add the `[sm_parents]` shortcode.
	add_shortcode( 'sm_parents', 'sm_parents_shortcode' );
	// Add the `[sm_home_info]` shortcode.
	add_shortcode( 'sm_home_info', 'sm_home_info_shortcode' );
	// Add the `[sm_home_phone]` shortcode.
	add_shortcode( 'sm_home_phone', 'sm_home_phone_shortcode' );
}

function sm_group_name_shortcode() {
	$account_creater_id = sm_get_group_owner_id();
	$sm_family          = rcpga_group_accounts()->members->get_group_name( $account_creater_id );

	return $sm_family;
}

function sm_subscription_id_shortcode( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts,
		'sm_subscription_id'
	);

	return sm_get_group_subscription_id( $atts['id'] );

}

function sm_if_same_group_shortcode( $atts, $content = null ) {

	$atts = shortcode_atts(
		array(
			'other' => '',
			'user'  => get_current_user_id(),
		),
		$atts,
		'sm_in_same_group'
	);

	if ( $atts['other'] ) {
		if ( sm_in_same_group( $atts['other'], $atts['user'] ) ) {
			return do_shortcode( $content );
		}
	}
}

function sm_group_expiration_shortcode( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts,
		'sm_group_expiration'
	);

	return sm_get_group_expiration( $atts['id'] );

}

function sm_has_subscription_shortcode( $atts, $content = null ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts,
		'sm_has_subscription'
	);

	if ( $atts['id'] ) {
		if ( sm_get_group_subscription_id() == $atts['id'] ) {
			return do_shortcode( $content );
		}
	} elseif ( sm_get_group_subscription_id() ) {
		return do_shortcode( $content );
	}

}

function sm_group_is_full_shortcode( $atts, $content = null ) {

	$user_id     = get_current_user_id();
	$group_id    = rcpga_group_accounts()->members->get_group_id( $user_id );
	$total_seats = rcpga_group_accounts()->groups->get_seats_count( $group_id );
	$used_seats  = rcpga_group_accounts()->groups->get_member_count( $group_id );

	// Attributes
	$atts = shortcode_atts(
		array(
			'not' => '',
		),
		$atts,
		'sm_group_is_full'
	);

	if ( 'true' == $atts['not'] ) {
		if ( $total_seats > $used_seats ) {
				return do_shortcode( $content );
		}
	} elseif ( $total_seats <= $used_seats ) {
		return do_shortcode( $content );
	}

}

function sm_home_info_shortcode() {
	$account_creater_id = sm_get_group_owner_id();
	$sm_family          = rcpga_group_accounts()->members->get_group_name( $account_creater_id );
	$sm_home_info       = '';

	if ( $sm_family ) {
		$sm_home_info .= sm_get_template_part( 'family-home-info' );
	}

	return $sm_home_info;
}

function sm_home_phone_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'wrapper' => 'span',
			'class'   => 'sm-home-phone',
		),
		$atts,
		'sm_home_phone'
	);

	$account_creater_id = sm_get_group_owner_id();
	$sm_phone           = get_user_meta( $account_creater_id, 'sm_home_phone', true );
	$sm_home_phone      = '';

	if ( $sm_phone ) {
		$sm_home_phone .= '<' . $atts['wrapper'] . ' class="' . $atts['class'] . '">';
		$sm_home_phone .= $sm_phone;
		$sm_home_phone .= '</' . $atts['wrapper'] . '>';
	}

	return $sm_home_phone;

}

function sm_address_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'wrapper' => 'div',
			'class'   => 'sm-address',
		),
		$atts,
		'sm_address'
	);

	$account_creater_id = sm_get_group_owner_id();

	$sm_address = '';

	if ( sm_get_address( $account_creater_id ) ) {
		$sm_address .= '<' . $atts['wrapper'] . ' class="' . $atts['class'] . '">';
		$sm_address .= sm_get_address( $account_creater_id );
		$sm_address .= '</' . $atts['wrapper'] . '>';
	}

	return $sm_address;
}

function sm_students_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'id' => sm_get_group_owner_id(),
		),
		$atts,
		'sm_students'
	);

	return sm_get_students( $atts['id'] );
}

function sm_student_grades_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'id' => sm_get_group_owner_id(),
		),
		$atts,
		'sm_student_grades'
	);

	return sm_get_student_grades( $atts['id'] );
}

function sm_parent_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'parent'  => '1',
			'wrapper' => 'div',
			'class'   => 'sm-parent',
		),
		$atts,
		'sm_parent'
	);

	$account_creater_id = sm_get_group_owner_id();
	$p_number           = $atts['parent'];

	$sm_parent = '';

	if ( sm_get_parent( $account_creater_id, $p_number ) ) {
		$sm_parent .= '<' . $atts['wrapper'] . ' class="' . $atts['class'] . '">';
		$sm_parent .= sm_get_parent( $account_creater_id, $p_number );
		$sm_parent .= '</' . $atts['wrapper'] . '>';
	}

	return $sm_parent;
}

function sm_parents_shortcode() {

	$account_creater_id = sm_get_group_owner_id();
	$sm_parents         = '';

	if ( sm_get_parent( $account_creater_id ) ) {
		$sm_parents = sm_get_template_part( 'family-parents' );
	}

	return $sm_parents;
}

// Login-form shortcode with redirect.
function sm_login_form_shortcode() {

	$logoutlink    = wp_logout_url( home_url() );
	$passresetlink = wp_lostpassword_url();

	if ( is_user_logged_in() ) {
		return '<h2>You are already logged in!</h2><a class="btn button logout-button" href="' . $logoutlink . '">Logout</a>';
	}

	$user = wp_get_current_user();
	$url  = home_url( 'parent-home' );

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
		'echo'     => false,
		'redirect' => $url,
	);

	return wp_login_form( $args ) . '<a class="reset-pass-link" href="' . $passresetlink . '" title="Lost Password">Lost your password?</a>';
}
