<?php


add_filter( 'gravityview/edit_entry/success', 'sm_edit_gv_entry_success', 10, 4 );
function sm_edit_gv_entry_success(  $entry_updated_message , $view_id, $entry, $back_link ) {
	$back_link = home_url( 'accounts/family-dashboard/' );
	$message = 'Profile Updated. <a href="'.$back_link .'">Return to your Family Dashboard.</a>';
	return $message;
}

function sm_edit_gv_cancel_link( $back_link, $form, $entry, $view_id ) {
	$back_link = home_url( 'accounts/family-dashboard/' );
    return $back_link;
}
add_filter( 'gravityview/edit_entry/cancel_link', 'sm_edit_gv_cancel_link', 10, 4 );
