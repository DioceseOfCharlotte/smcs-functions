<?php
/**
 * Restrict Content Pro.
 *
 * @package  SMCS
 */

if ( ! function_exists( 'rcp_is_restricted_content' ) ) {
	return;
}

function sm_get_group_admin( $user_id = 0 ) {

	$user_id = $user_id ?: get_current_user_id();

	if ( ! rcpga_group_accounts()->members->get_group_id( $user_id ) ) {
		return false;
	}

	$group_id = rcpga_group_accounts()->members->get_group_id( $user_id );

	if ( 2 > rcpga_group_accounts()->members->count( $group_id ) ) {
		return false;
	}

	$members     = rcpga_group_accounts()->members->get_members( $group_id );
	$group_admin = $members[1]->user_id;

	if ( empty( $group_admin ) ) {
		return false;
	}

	if ( rcpga_group_accounts()->members->is_group_admin( $group_admin ) ) {
		return $group_admin;
	}
}

function sm_get_group_expiration( $id = 0 ) {

	if ( ! function_exists( 'rcp_hardset_expiration_dates_load' ) ) {
		return false;
	}

	global $rcp_hsed;
	$id = $id ?: sm_get_group_subscription_id();

	$date = '';

	if ( $id ) {
		$date = date_create( $rcp_hsed->get_subscription_expiration_date( $id ) );
	}

	return date_format( $date, 'F d, Y' );
}

function sm_get_group_subscription_id( $user_id = 0 ) {

	$user_id = $user_id ?: get_current_user_id();

	if ( ! rcpga_group_accounts()->members->get_group_id( $user_id ) ) {
		return rcp_is_active( $user_id ) ? rcp_get_subscription_id( $user_id ) : false;
	}

	$group_id    = rcpga_group_accounts()->members->get_group_id( $user_id );
	$group_count = rcpga_group_accounts()->members->count( $group_id );

	if ( 2 > absint( $group_count ) ) {
		return rcp_get_subscription_id( $user_id );
	}

	if ( rcpga_group_accounts()->members->is_group_admin( $user_id ) ) {
		return rcp_get_subscription_id( $user_id );
	}

	$members    = rcpga_group_accounts()->members->get_members( $group_id );
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

function sm_get_group_owner_id( $user_id = 0 ) {

	$user_id = $user_id ?: get_current_user_id();

	if ( ! rcpga_group_accounts()->members->get_group_id( $user_id ) ) {
		return rcp_is_active( $user_id ) ? $user_id : false;
	}

	$group_id = rcpga_group_accounts()->members->get_group_id( $user_id );
	$owner_id = rcpga_group_accounts()->groups->get_owner_id( $group_id );

	return $owner_id;
}

function sm_get_group_owner_meta( $user_id = 0, $key ) {

	$user_id = $user_id ?: get_current_user_id();

	if ( ! rcpga_group_accounts()->members->get_group_id( $user_id ) ) {
		return rcp_is_active( $user_id ) ? get_user_meta( $user_id, $key, true ) : false;
	}

	$group_id = rcpga_group_accounts()->members->get_group_id( $user_id );
	$owner_id = rcpga_group_accounts()->groups->get_owner_id( $group_id );

	if ( $owner_id ) {
		return get_user_meta( $owner_id, $key, true );
	}
}

function sm_group_is_full( $user_id = 0 ) {

	$user_id     = $user_id ?: get_current_user_id();
	$group_id    = rcpga_group_accounts()->members->get_group_id( $user_id );
	$total_seats = rcpga_group_accounts()->groups->get_seats_count( $group_id );
	$used_seats  = rcpga_group_accounts()->groups->get_member_count( $group_id );

		return $total_seats <= $used_seats;

}

// Add registration CPT to RCP default page selections.
add_action(
	'current_screen', function() {
		add_filter( 'get_pages', 'jp_rcp_pages_filter' );
	}
);

function jp_rcp_pages_filter( $pages ) {

	if ( 'restrict_page_rcp-settings' !== get_current_screen()->id ) {
		return $pages;
	}

	remove_filter( 'get_pages', 'jp_rcp_pages_filter' );

	$custom_posts = get_pages(
		array(
			'post_type'   => 'registration_pages',
			'post_status' => 'publish',
		)
	);

	add_filter( 'get_pages', 'jp_rcp_pages_filter', 10, 2 );

	foreach ( $custom_posts as $custom_post ) {
		$pages[] = $custom_post;
	}

	return $pages;
}
