<?php

// dynamically populate a GF field with the RPC subscription ID.
add_filter( 'gform_field_value_sm_subscription', 'sm_subscription_populate' );
function sm_subscription_populate( $value ) {
	return rcp_get_subscription_id( get_current_user_id() );
}









// $user_id = get_current_user_id();
// $group_id = rcpga_group_accounts()->members->get_group_id( $user_id );
// $owner_id = rcpga_group_accounts()->groups->get_owner_id( $group_id);
// $owner = get_user_by( 'id', $owner_id );

// if( $user ) {
// 	wp_set_current_user( $user_id, $user->user_login );
// 	wp_set_auth_cookie( $user_id );
// 	do_action( 'wp_login', $user->user_login );
// }

// if ( is_single( '1496' ) ) {
// 	if ( $group_id && rcpga_group_accounts()->members->is_group_admin() ) {
// 		wp_set_current_user( $user_id, $owner->user_login );
// 	}
// }


// function meh_is_in_same_group( $user_id = 0, $compare_user_id ) {
// 	$user_id = $user_id ? $user_id : get_current_user_id();
// 	$user_group_id = rcpga_group_accounts()->members->get_group_id( $user_id );
// 	$compare_group_id = rcpga_group_accounts()->members->get_group_id( $compare_user_id );
// 	if ( $user_group_id != $compare_group_id ) {
// 		return false;
// 	}
// }
