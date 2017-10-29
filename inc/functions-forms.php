<?php

add_filter( 'gform_username_40', 'smcs_parent_username', 10, 4 );
add_action( 'gform_user_updated', 'smcs_backup_display_name', 10, 4 );
add_action( 'gform_user_registered', 'smcs_create_rcp_member', 10, 4 );
add_filter( 'gform_field_value_sm_subscription', 'sm_subscription_populate' );
add_filter( 'gform_field_value_is_smaa_member', 'is_user_smaa_member' );
add_filter( 'gform_field_value_sm_group_full', 'sm_group_full_populate' );

function smcs_parent_username( $username, $feed, $form, $entry ) {

	$username   = strtolower( rgar( $entry, '2.3' ) . rgar( $entry, '2.6' ) );
	$user_email = strtolower( $entry['10'] );

	if ( empty( $username ) ) {
		$username = $user_email;
	}

	if ( ! function_exists( 'username_exists' ) ) {
		require_once( ABSPATH . WPINC . '/registration.php' );
	}

	if ( username_exists( $username ) ) {
		$i = 2;
		while ( username_exists( $username . $i ) ) {
			$i++;
		}
		$username = $username . $i;
	};

	return $username;

}

function smcs_backup_display_name( $user_id, $feed, $entry, $user_pass ) {
	// get display name from field 2
	$display_name = rgar( $entry, '2.3' ) . ' ' . rgar( $entry, '2.6' );

	if ( empty( $display_name ) ) {
		update_user_meta( $user_id, 'display_name', strtolower( $entry['10'] ) );
	}
}


function smcs_create_rcp_member( $user_id, $feed, $entry, $user_pass ) {

	$add_user_args = array(
		'subscription_id' => 1,
		'status'          => 'active',
	);

	// add the just registered user(owner) to a subscription.
	rcp_add_user_to_subscription( $user_id, $add_user_args );

	$group_name = $entry['1'];
	$level_id   = rcp_get_subscription_id( $user_id );
	$seat_count = rcpga_get_level_group_seats_allowed( $level_id );

	$add_group_accounts_args = array(
		'owner_id' => $user_id,
		'name'     => $group_name,
		'seats'    => $seat_count,
	);

	// create a group for the newly registered owner.
	rcpga_group_accounts()->groups->add( $add_group_accounts_args );

	$admin_email = $entry['3'];
	$add_admin   = $entry['37.1'];

	// only create the admin account if the owner chooses to.
	if ( empty( $add_admin ) || empty( $admin_email ) ) {
		return;
	}

	$admin_first_name   = $entry['4.3'];
	$admin_last_name    = $entry['4.6'];
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

	$admin_add_args = array(
		'user_email'   => $admin_email,
		'first_name'   => $admin_first_name,
		'last_name'    => $admin_last_name,
		'user_login'   => $username,
		'display_name' => $admin_display_name,
	);

	$admin_user = get_user_by( 'email', $admin_email );

	// create a new user if member does not already exist
	if ( $admin_user ) {
		$admin_user_id = $admin_user->ID;
	} else {
		$admin_user_id = wp_insert_user( $admin_add_args );
	}

	// add the admin to a subscription.
	rcp_add_user_to_subscription( $admin_user_id, $add_user_args );

	$add_admin_to_group_args = array(
		'user_id'  => $admin_user_id,
		'group_id' => $group_id,
		'role'     => 'admin',
	);

	// add the member to the group
	rcpga_group_accounts()->members->add( $add_admin_to_group_args );

}

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

// dynamically populate a GF field with the RPC subscription ID.
function sm_group_full_populate( $value ) {
	return sm_group_is_full();
}

// Register User Contact Methods
function custom_user_contact_methods( $user_contact_method ) {

	$user_contact_method['rcp_subscription_level'] = __( 'Subscription', 'text_domain' );
	$user_contact_method['rcp_status']             = __( 'Status', 'text_domain' );

	return $user_contact_method;

}
add_filter( 'user_contactmethods', 'custom_user_contact_methods' );








//add_action( 'gform_post_payment_status', 'smaa_upgrade_member', 10, 8 );
function smaa_upgrade_member( $feed, $entry, $status, $transaction_id, $subscriber_id, $amount, $pending_reason, $reason ) {

	if ( $status == 'Paid' ) {

		$user_id = $entry['created_by'];

		$args = array(
			'subscription_id' => 2,
			'status'          => 'active',
		);

		rcp_add_user_to_subscription( $user_id, $args );
	}

}
