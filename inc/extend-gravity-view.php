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

	//if ( '40' == $form['id'] ) {

		$member_user = sm_get_group_admin();

		$member_userdata = array(
			'ID'         => $member_user,
			'user_email' => $gv_entry->entry['3'],
			'first_name' => $gv_entry->entry['4.3'],
			'last_name'  => $gv_entry->entry['4.6'],
		);

		wp_update_user( $member_userdata );

	//}

}


add_filter( 'gravityview_entries', 'sm_created_by_group', 10, 2 );
function sm_created_by_group( $entries, $criteria ) {

	$user_id = get_current_user_id();
	$group_owner = sm_get_group_owner_id( $user_id );
	$group_admin = sm_get_group_admin();
	//$entries = GFAPI::get_entries( $form_id, $criteria );
	//$criteria['field_filters'][] = array( 'key' => 'created_by', 'value' => $user_id );
	//
	if ( empty( $group_admin ) ) {

		//$entries = GFAPI::get_entries( $form_id, $criteria );
		return $entries;
	}

	// $criteria['search_criteria']['field_filters'][0]['value'] = $group_owner;
	// $criteria['search_criteria']['field_filters'][1]['value'] = $group_admin;

	//$form_id  = 40;
	$criteria = array(
		'field_filters' => array(
			'mode' => 'any',
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
		),
	);

	$entries = GFAPI::get_entries( $form_id, $criteria );

	return $entries;
	//var_dump($criteria);
}



// add_action( 'gform_post_update_entry_40', 'sm_update_family_admin', 10, 2 );
// function sm_update_family_admin( $entry, $form ) {
//
// }


//add_filter( 'gravityview_search_criteria', 'my_gv_custom_role_filter', 110, 1 );
function my_gv_custom_role_filter( $criteria ) {

	// check if we are displaying the correct View
	// if ( function_exists( 'gravityview_get_view_id' ) && '1814' != gravityview_get_view_id() ) {
	// 	return $criteria;
	// }

	// Check if user is logged-in
	if ( ! is_user_logged_in() ) {
		return $criteria;
	}

	// var_dump(sm_get_group_owner_id( $current_user ));
	// var_dump($criteria['search_criteria']['field_filters']['0']["value"]);

	$current_user = get_current_user_id();
	$owner_id = sm_get_group_owner_id( $current_user );

	$g_value      = $criteria['search_criteria']['field_filters']['0']["value"];

	// Check if user belongs to a certain 'admin' role
	// $current_user = get_current_user_id();
	// if ( sm_get_group_owner_id( $current_user ) != $criteria['search_criteria']['field_filters']['0']['value'] ) {
	// 	return $criteria;
	// }

	// if we get here, then remove the 'create by' filter from the $criteria
	if ( ! empty( $criteria['search_criteria']['field_filters'] ) && is_array( $criteria['search_criteria']['field_filters'] ) ) {
		foreach ( $criteria['search_criteria']['field_filters'] as $k => $filter ) {
			if ( $k !== 'mode' && 'created_by' === $filter['key'] ) {

				//var_dump($filter['value']);
				//if ( rcpga_group_accounts()->members->is_group_admin( $current_user ) ) {
					//if ( $filter['value'] == $current_user ) {
						$filter['value'] = $owner_id;

						//var_dump($criteria);
					//}
				//}

				// unset( $criteria['search_criteria']['field_filters'][ $k ] );
			}
		}
	}
	return $criteria;
}
