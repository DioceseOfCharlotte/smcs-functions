<?php
add_filter( 'gform_field_value_sm_subscription', 'sm_subscription_populate' );
add_filter( 'gform_field_value_is_smaa_member', 'is_user_smaa_member' );

// dynamically populate a GF field with the RPC subscription ID.
function sm_subscription_populate( $value ) {
	return sm_get_group_subscription_id();
}

// dynamically populate a GF field with true if user is SMAA Member.
function is_user_smaa_member( $value ) {
	$subscription_id = sm_get_group_subscription_id();

	if ( 2 === $subscription_id ) {
		return true;
	}

	return false;
}

function sm_get_group_subscription_id() {
	$user_id = get_current_user_id();
	$group_id = rcpga_group_accounts()->members->get_group_id( $user_id );
	$group_count = rcpga_group_accounts()->members->count( $group_id );

	if ( 2 > absint( $group_count ) ) {
		return rcp_get_subscription_id( $user_id );
	}

	if ( rcpga_group_accounts()->members->is_group_admin( $user_id ) ) {
		return rcp_get_subscription_id( $user_id );
	}

	$members = rcpga_group_accounts()->members->get_members( $group_id );
	$member_one = $members[0]->user_id;
	$member_two = $members[1]->user_id;

	if ( rcpga_group_accounts()->members->is_group_admin( $member_two ) ) {
		return rcp_get_subscription_id( $member_two );
	}

	if ( rcpga_group_accounts()->members->is_group_admin( $member_one ) ) {
		return rcp_get_subscription_id( $member_one );
	}

	return rcp_get_subscription_id( $user_id );
}

function sm_get_group_owner_meta( $user_id, $key ) {
	$user_id = get_current_user_id();
	$group_id = rcpga_group_accounts()->members->get_group_id( $user_id );
	$owner_id = rcpga_group_accounts()->groups->get_owner_id( $group_id );

	if ( $owner_id ) {
		return get_user_meta( $owner_id, $key, true );
	}
}

// Register User Contact Methods
function custom_user_contact_methods( $user_contact_method ) {

	$user_contact_method['rcp_subscription_level'] = __( 'Subscription', 'text_domain' );
	$user_contact_method['rcp_status'] = __( 'Status', 'text_domain' );

	return $user_contact_method;

}
add_filter( 'user_contactmethods', 'custom_user_contact_methods' );
