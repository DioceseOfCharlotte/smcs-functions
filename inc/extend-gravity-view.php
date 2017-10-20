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

	if ( '40' == $form['id'] ) {

		if ( ! rcpga_group_accounts()->members->get_group_id() ) {
			return;
		}

		$member_user = sm_get_group_admin();

		$member_userdata = array(
			'ID'         => $member_user,
			'user_email' => $gv_entry->entry['3'],
			'first_name' => $gv_entry->entry['4.3'],
			'last_name'  => $gv_entry->entry['4.6'],
		);

		wp_update_user( $member_userdata );

	}

}

function sm_created_by_group( $criteria ) {

	if ( ! sm_is_profile_view() ) {
		return $criteria;
	}

	$user_id       = get_current_user_id();
	$group_id      = rcpga_group_accounts()->members->get_group_id( $user_id );
	$group_members = rcpga_group_accounts()->members->get_members( $group_id );

	$members = array();

	if ( $group_members ) {
		foreach ( $group_members as $member ) {
			$members[] = $member->user_id;
		}
	}

	$creators = $group_id ? $members : $user_id;

	$criteria['search_criteria'] = array(
		'field_filters' => array(
			array(
				'key'      => 'created_by',
				'operator' => 'is',
				'value'    => $creators,
			),
			'mode' => 'any',
		),
	);

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
