<?php
/**
 * Restrict Content Pro.
 *
 * @package  SMCS
 */

if ( ! function_exists( 'rcp_is_restricted_content' ) ) {
	return;
}

// Allow per post Members roles to be selectable from a taxonomy.
add_filter( 'members_can_user_view_post', 'smcs_tax_content_permissions' );
add_action( 'gform_user_registered', 'smcs_create_rcp_member', 10, 4 );


function smcs_tax_content_permissions( $can_view, $user_id, $post_id ) {

	$terms = get_terms( array(
		'taxonomy' => 'smcs_access',
	) );

	if ( ! empty( $terms ) ) {

		$can_view = false;

		if ( rcp_is_restricted_content( $post_id ) && rcp_user_can_access( $user_id, $post_id ) ) {
			$can_view = true;
		}

		if ( has_term( 'admin-athletics', 'smcs_access' ) ) {
			if ( members_user_has_role( $user_id, 'sm_sports_admin' ) ) {
				$can_view = true;
			}
		}

		if ( has_term( 'admin-pto', 'smcs_access' ) ) {
			if ( members_user_has_role( $user_id, 'sm_pto_admin' ) ) {
				$can_view = true;
			}
		}
	}

	return $can_view;
}

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
		'post_type' => 'registration_pages', // change to your custom post type
		'post_status' => 'publish',
	) );

	add_filter( 'get_pages', 'jp_rcp_pages_filter', 10, 2 );

	foreach( $custom_posts as $custom_post ) {
		$pages[] = $custom_post;
	}

	return $pages;
}


function meh_is_in_same_group( $user_id = 0, $compare_user_id ) {
	$user_id = $user_id ? $user_id : get_current_user_id();
	$user_group_id = rcpga_group_accounts()->members->get_group_id( $user_id );
	$compare_group_id = rcpga_group_accounts()->members->get_group_id( $compare_user_id );

	if ( $user_group_id != $compare_group_id ) {
		return false;
	}
}



function smcs_create_rcp_member( $user_id, $feed, $entry, $user_pass ) {

	// $user_id = $entry['created_by'];
	//$user_id = get_current_user_id();
	$group_name = $entry['1'];
	$member_email = $entry['3'];
	$member_first_name = $entry['4.3'];
	$member_last_name = $entry['4.6'];
	$member_invite = empty( $entry['36'] ) ? false : true;
	$level_id   = rcp_get_subscription_id( $user_id );
	$seat_count = rcpga_get_level_group_seats_allowed( $level_id );

	$add_group_args = array(
		'owner_id'    => $user_id,
		'name'        => $group_name,
		'seats'       => $seat_count,
	);

	rcpga_group_accounts()->groups->add( $add_group_args );

	rcpga_add_member_to_group( array(
	    'user_email'  => $member_email,
	    'group_id'    => rcpga_group_accounts()->members->get_group_id( $user_id ),
	    'send_invite' => false,
	) );

	// Get the added members ID
	if ( $member_user = get_user_by( 'email', $member_email ) ) {
		$member_user_id = $member_user->ID;
	}

	update_user_meta( $member_user_id, 'first_name', $member_first_name );
	update_user_meta( $member_user_id, 'last_name', $member_last_name );
	update_user_meta( $user_id, 'group_peer_id', $member_user_id );
	update_user_meta( $member_user_id, 'group_owner_id', $user_id );

}
