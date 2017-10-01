<?php
/**
 * Restrict Content Pro.
 *
 * @package  SMCS
 */

if ( ! function_exists( 'rcp_is_restricted_content' ) ) {
	return;
}


add_filter( 'gform_username_40', 'smcs_parent_username', 10, 4 );
add_action( 'gform_user_updated', 'smcs_backup_display_name', 10, 4 );
add_action( 'gform_user_registered', 'smcs_create_rcp_member', 10, 4 );

// Add registration CPT to RCP default page selections.
add_action( 'current_screen', function() {
	add_filter( 'get_pages', 'jp_rcp_pages_filter' );
});

function jp_rcp_pages_filter( $pages ) {

	if ( 'restrict_page_rcp-settings' !== get_current_screen()->id ) {
		return $pages;
	}

	remove_filter( 'get_pages', 'jp_rcp_pages_filter' );

	$custom_posts = get_pages( array(
		'post_type' => 'registration_pages',
		'post_status' => 'publish',
	) );

	add_filter( 'get_pages', 'jp_rcp_pages_filter', 10, 2 );

	foreach( $custom_posts as $custom_post ) {
		$pages[] = $custom_post;
	}

	return $pages;
}

function smcs_parent_username( $username, $feed, $form, $entry ) {

	$username = strtolower( rgar( $entry, '2.3' ) . rgar( $entry, '2.6' ) );
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

	rcp_add_user_to_subscription( $user_id, $add_user_args );

	$group_name = $entry['1'];
	$level_id   = rcp_get_subscription_id( $user_id );
	$seat_count = rcpga_get_level_group_seats_allowed( $level_id );

	$add_group_accounts_args = array(
		'owner_id'    => $user_id,
		'name'        => $group_name,
		'seats'       => $seat_count,
	);

	rcpga_group_accounts()->groups->add( $add_group_accounts_args );

	$group_id = rcpga_group_accounts()->members->get_group_id( $user_id );
	$member_email = $entry['3'];
	$member_first_name = $entry['4.3'];
	$member_last_name = $entry['4.6'];
	$send_invite = empty( $entry['36'] ) ? false : true;
	$member_display_name = $member_first_name . ' ' . $member_last_name;
	$username = strtolower( "{$member_first_name}{$member_last_name}" );

	if ( empty( $username ) ) {
		$username = $member_email;
	}

	if ( username_exists( $username ) ) {
		$i = 2;
		while ( username_exists( $username . $i ) ) {
			$i++;
		}
		$username = $username . $i;
	};

	if ( ! empty( $member_email ) ) {

		$member_add_args = array(
			'user_email'   => $member_email,
			'first_name'   => $member_first_name,
			'last_name'    => $member_last_name,
			'user_login'   => $username,
			'display_name' => $member_display_name,
		);

		// if ( empty( $args['user_login'] ) ) {
		// 	$args['user_login'] = $args['user_email'];
		// }

		// create a new user if member does not already exist
		if ( $member_user = get_user_by( 'email', $member_email ) ) {
			$member_user_id = $member_user->ID;
		} else {
			$member_user_id = wp_insert_user( $member_add_args );
		}

		rcp_add_user_to_subscription( $member_user_id, $add_user_args );

		$add_member_to_group_args = array(
			'user_id'  => $member_user_id,
			'group_id' => $group_id,
			'role'     => 'admin',
		);

		// add the member to the group
		rcpga_group_accounts()->members->add( $add_member_to_group_args );

		update_user_meta( $member_user_id, 'group_owners_id', $user_id );
		update_user_meta( $member_user_id, 'sm_group_id', $group_id );
		update_user_meta( $user_id, 'group_admins_id', $member_user_id );
	}

	update_user_meta( $user_id, 'sm_group_id', $group_id );
}
