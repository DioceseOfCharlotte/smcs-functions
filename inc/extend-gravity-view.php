<?php


add_filter( 'gravityview/edit_entry/success', 'sm_edit_gv_entry_success', 10, 4 );
function sm_edit_gv_entry_success( $entry_updated_message, $view_id, $entry, $back_link ) {
	$back_link = home_url( 'accounts/family-dashboard/' );
	$message   = 'Profile Updated. <a href="' . $back_link . '">Return to your Family Dashboard.</a>';
	return $message;
}

function sm_edit_gv_cancel_link( $back_link, $form, $entry, $view_id ) {
	$back_link = home_url( 'accounts/family-dashboard/' );
	return $back_link;
}
add_filter( 'gravityview/edit_entry/cancel_link', 'sm_edit_gv_cancel_link', 10, 4 );


add_action( 'gravityview/edit_entry/after_update', 'sm_update_family_admin', 10, 3 );

function sm_update_family_admin( $form, $entry_id, $gv_entry ) {

	$member_user = sm_get_group_admin();

	$member_userdata = array(
		'ID'         => $member_user,
		'user_email' => $gv_entry->entry['3'],
		'first_name' => $gv_entry->entry['4.3'],
		'last_name'  => $gv_entry->entry['4.6'],
	);

	wp_update_user( $member_userdata );

}



// add_action( 'gform_post_update_entry_40', 'sm_update_family_admin', 10, 2 );
// function sm_update_family_admin( $entry, $form ) {
//
// }
