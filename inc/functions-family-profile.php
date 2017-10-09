<?php




function sm_get_address( $user_id ) {

	$user_id = sm_get_group_owner_id();

	$sm_family = rcpga_group_accounts()->members->get_group_name( $user_id );
	$sm_home_phone = get_user_meta( $user_id, 'sm_home_phone', true );
	$sm_street = get_user_meta( $user_id, 'sm_home_street', true );
	$sm_city = get_user_meta( $user_id, 'sm_home_city', true );
	$sm_state = get_user_meta( $user_id, 'sm_home_state', true );
	$sm_home_zip = get_user_meta( $user_id, 'sm_home_zip', true );

	$address = '';

	$address .= "<h3>{$sm_family} Family</h3>";
	$address .= "<p>Home: {$sm_home_phone}</p>";
	$address .= "<p>{$sm_street}<br>";
	$address .= "{$sm_city}, {$sm_state} {$sm_home_zip}</p>";

	return $address;
}

// sm_home_phone
// sm_home_street
// sm_home_city
// sm_home_state
// sm_home_zip
// sm_parent_1_phone
// sm_parent_2_first
// sm_parent_2_last
// sm_parent_2_email
// sm_parent_2_phone
//
// sm_student_1_first
// sm_student_1_last
// sm_student_1_grade
