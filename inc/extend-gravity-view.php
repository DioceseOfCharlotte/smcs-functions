<?php

add_filter( 'gravityview/edit_entry/success', 'sm_edit_gv_entry_success', 10, 4 );
add_filter( 'gravityview/edit_entry/cancel_link', 'sm_edit_gv_cancel_link', 10, 4 );
add_action( 'gravityview/edit_entry/after_update', 'sm_update_family_admin', 10, 3 );
add_filter( 'gravityview_search_criteria', 'sm_created_by_group', 10, 1 );
add_filter( 'user_has_cap', 'admin_edit_owner_gv', 10, 3 );

add_filter( 'gravityview_go_back_url', 'sm_back_link', 10, 1 );

function sm_is_profile_view() {
	return function_exists( 'gravityview_get_view_id' ) && in_array( gravityview_get_view_id(), sm_get_profile_views() );
}

function sm_get_profile_views() {
	$gv_ids = array(
		'1856',
		'1866',
		'1867',
		'1854',
		'1814',
		'1812',
		'1795',
		'1732',
		'1731',
		'1730',
		'1727',
	);
	return $gv_ids;
}

function sm_edit_gv_entry_success( $message, $view_id, $entry, $back_link ) {

	if ( sm_is_profile_view() ) {
		$back_link = home_url( 'accounts/family-dashboard/' );
		$message   = 'Profile Updated. <a href="' . $back_link . '">Return to your Family Dashboard.</a>';
	}
	return $message;
}

function sm_edit_gv_cancel_link( $back_link, $form, $entry, $view_id ) {

	if ( sm_is_profile_view() ) {
		$back_link = home_url( 'accounts/family-dashboard/' );
	}

	return $back_link;
}

function sm_back_link( $back_link ) {

	if ( sm_is_profile_view() ) {
		$back_link = home_url( 'accounts/family-dashboard/' );
	}

	return $back_link;
}


function sm_update_family_admin( $form, $entry_id, $gv_entry ) {

	if ( '40' != $form['id'] ) {
		return;
	}

	$user_id = get_current_user_id();

	if ( ! rcp_is_active( $user_id ) ) {
		return;
	}

	if ( ! rcpga_group_accounts()->members->get_group_id( $user_id ) ) {
		return;
	}

	$admin_email = $gv_entry->entry['3'];
	$add_admin   = $gv_entry->entry['37.1'];

	// earlier users may not have the option even though they have an admin.
	if ( empty( $add_admin ) && ! sm_group_is_full() ) {
		return;
	}

	// only create the admin account if the owner chooses to.
	if ( empty( $admin_email ) ) {
		return;
	}

	$admin_first_name   = $gv_entry->entry['4.3'];
	$admin_last_name    = $gv_entry->entry['4.6'];
	$admin_display_name = $admin_first_name . ' ' . $admin_last_name;
	$username           = strtolower( "{$admin_first_name}{$admin_last_name}" );
	$group_id           = rcpga_group_accounts()->members->get_group_id( $user_id );

	// use the email if for some reason the name fields are empty.
	if ( empty( $username ) ) {
		$username = $admin_email;
	}

	// make the username is unique if it isn't.
	if ( username_exists( $username ) ) {
		$i = 2;
		while ( username_exists( $username . $i ) ) {
			$i++;
		}
		$username = $username . $i;
	}

	// create a new user if member does not already exist
	if ( sm_get_group_admin() ) {

		$admin_userdata = array(
			'ID'         => sm_get_group_admin(),
			'user_email' => $gv_entry->entry['3'],
			'first_name' => $gv_entry->entry['4.3'],
			'last_name'  => $gv_entry->entry['4.6'],
		);
		$admin_user_id  = wp_update_user( $admin_userdata );
	} else {

		$admin_add_args = array(
			'user_email'   => $admin_email,
			'first_name'   => $admin_first_name,
			'last_name'    => $admin_last_name,
			'user_login'   => $username,
			'display_name' => $admin_display_name,
		);
		$admin_user_id  = wp_insert_user( $admin_add_args );
	}

	// if this is a new admin add them to a subscription.
	if ( ! rcp_is_active( $admin_user_id ) ) {

		$add_user_args = array(
			'subscription_id' => 1,
			'status'          => 'active',
		);
		rcp_add_user_to_subscription( $admin_user_id, $add_user_args );
	}

	// add the member to the group
	if ( rcpga_group_accounts()->members->get_group_id( $admin_user_id ) != $group_id ) {

		$add_member_to_group_args = array(
			'user_id'  => $admin_user_id,
			'group_id' => $group_id,
			'role'     => 'admin',
		);
		rcpga_group_accounts()->members->add( $add_member_to_group_args );
	}
}

function sm_created_by_group( $criteria ) {

	if ( ! sm_is_profile_view() ) {
		return $criteria;
	}

	$user_id       = get_current_user_id();
	$group_id      = rcpga_group_accounts()->members->get_group_id( $user_id );
	$group_members = rcpga_group_accounts()->members->get_members( $group_id );
	$group_owner   = sm_get_group_owner_id( $user_id );
	$group_admin   = sm_get_group_admin();

	// $members = array();
	//
	// if ( $group_members ) {
	// 	foreach ( $group_members as $member ) {
	// 		$members[] = $member->user_id;
	// 	}
	// }
	//
	// $creators = $group_id ? $members : $user_id;

	if ( $group_admin ) {
		$criteria['search_criteria'] = array(
			'field_filters' => array(
				array(
					'key'      => 'created_by',
					'operator' => 'is',
					'value'    => $group_owner,
				),
				array(
					'key'      => 'created_by',
					'operator' => 'is',
					'value'    => $group_admin,
				),
				'mode' => 'any',
			),
		);
	} else {
		$criteria['search_criteria'] = array(
			'field_filters' => array(
				array(
					'key'      => 'created_by',
					'operator' => 'is',
					'value'    => $user_id,
				),
				'mode' => 'any',
			),
		);
	}
	return $criteria;
}

function admin_edit_owner_gv( $allcaps, $cap, $args ) {
	// Bail out if we're not asking about a post:
	if ( 'edit_post' === $cap || 'edit_page' === $cap ) {
		return $allcaps;
	}

	if ( ! sm_is_profile_view() ) {
		return $allcaps;
	}

	global $post;
	$allowed_ids = array( 1496 );
	$post_id     = isset( $post ) ? $post->ID : false;
	if ( ! in_array( $post_id, $allowed_ids ) ) {
		return $allcaps;
	}

	if ( ! rcpga_group_accounts()->members->get_group_id() ) {
		return $allcaps;
	}

	$allcaps['gravityview_edit_others_entries'] = true;

	return $allcaps;
}
