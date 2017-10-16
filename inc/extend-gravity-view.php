<?php


add_filter( 'gravityview/edit_entry/success', 'sm_edit_gv_entry_success', 10, 4 );
add_filter( 'gravityview/edit_entry/cancel_link', 'sm_edit_gv_cancel_link', 10, 4 );
add_action( 'gravityview/edit_entry/after_update', 'sm_update_family_admin', 10, 3 );


function sm_edit_gv_entry_success( $entry_updated_message, $view_id, $entry, $back_link ) {
	$back_link = home_url( 'accounts/family-dashboard/' );
	$message   = 'Profile Updated. <a href="' . $back_link . '">Return to your Family Dashboard.</a>';
	return $message;
}

function sm_edit_gv_cancel_link( $back_link, $form, $entry, $view_id ) {
	$back_link = home_url( 'accounts/family-dashboard/' );
	return $back_link;
}



function sm_update_family_admin( $form, $entry_id, $gv_entry ) {
	if ( ! rcpga_group_accounts()->members->get_group_id() ) {
		return;
	}

	if ( '40' == $form['id'] ) {

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


add_filter( 'gravityview_search_criteria', 'sm_created_by_group', 10, 1 );
function sm_created_by_group( $entries, $criteria ) {

	$gv_ids = array(
		'1814',
		'1812',
		'1795',
		'1732',
		'1731',
		'1730',
		'1727',
	);

	if ( function_exists( 'gravityview_get_view_id' ) && ! in_array( gravityview_get_view_id(), $gv_ids ) ) {
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
