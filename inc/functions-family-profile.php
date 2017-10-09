<?php

function sm_get_address( $user_id ) {

	$user_id = sm_get_group_owner_id();

	$sm_street = get_user_meta( $user_id, 'sm_home_street', true );
	$sm_city = get_user_meta( $user_id, 'sm_home_city', true );
	$sm_state = get_user_meta( $user_id, 'sm_home_state', true );
	$sm_home_zip = get_user_meta( $user_id, 'sm_home_zip', true );

	$address = '';

	if ( $sm_city ) {
		$address .= "{$sm_street}<br>";
		$address .= "{$sm_city}, {$sm_state} {$sm_home_zip}";
	}

	return $address;
}

function sm_get_students( $user_id ) {

	$user_id = sm_get_group_owner_id();

	$s_1_first = get_user_meta( $user_id, 'sm_student_1_first', true );
	$s_1_last = get_user_meta( $user_id, 'sm_student_1_last', true );
	$s_1_grade = get_user_meta( $user_id, 'sm_student_1_grade', true );
	$s_2_first = get_user_meta( $user_id, 'sm_student_2_first', true );
	$s_2_last = get_user_meta( $user_id, 'sm_student_2_last', true );
	$s_2_grade = get_user_meta( $user_id, 'sm_student_2_grade', true );
	$s_3_first = get_user_meta( $user_id, 'sm_student_3_first', true );
	$s_3_last = get_user_meta( $user_id, 'sm_student_3_last', true );
	$s_3_grade = get_user_meta( $user_id, 'sm_student_3_grade', true );
	$s_4_first = get_user_meta( $user_id, 'sm_student_4_first', true );
	$s_4_last = get_user_meta( $user_id, 'sm_student_4_last', true );
	$s_4_grade = get_user_meta( $user_id, 'sm_student_4_grade', true );
	$s_5_first = get_user_meta( $user_id, 'sm_student_5_first', true );
	$s_5_last = get_user_meta( $user_id, 'sm_student_5_last', true );
	$s_5_grade = get_user_meta( $user_id, 'sm_student_5_grade', true );

	$students = '';

	if ( $s_1_first ) {
		$students .= '<ul>';
		$students .= "<li><strong>{$s_1_grade}</strong> {$s_1_first} {$s_1_last}</li>";
		if ( $s_2_first ) {
			$students .= "<li><strong>{$s_2_grade}</strong> {$s_2_first} {$s_2_last}</li>";
		}
		if ( $s_3_first ) {
			$students .= "<li><strong>{$s_3_grade}</strong> {$s_3_first} {$s_3_last}</li>";
		}
		if ( $s_4_first ) {
			$students .= "<li><strong>{$s_4_grade}</strong> {$s_4_first} {$s_4_last}</li>";
		}
		if ( $s_5_first ) {
			$students .= "<li><strong>{$s_5_grade}</strong> {$s_5_first} {$s_2_last}</li>";
		}
		$students .= '<ul>';
	}

	return $students;
}

function sm_get_parent( $user_id, $p_number ) {
	$p_number = $p_number ? $p_number : '1';
	$user_id = sm_get_group_owner_id();

	$sm_first = get_user_meta( $user_id, "sm_parent_{$p_number}_first", true );
	$sm_last = get_user_meta( $user_id, "sm_parent_{$p_number}_last", true );
	$sm_email = get_user_meta( $user_id, "sm_parent_{$p_number}_email", true );
	$sm_phone = get_user_meta( $user_id, "sm_parent_{$p_number}_phone", true );

	if ( '1' == $p_number ) {
		$sm_first = get_user_meta( $user_id, 'first_name', true );
		$sm_last = get_user_meta( $user_id, 'last_name', true );
		$sm_email = get_user_meta( $user_id, 'user_email', true );
	}

	$parent = '';

	if ( $sm_first ) {
		$parent .= "<h4>{$sm_first} {$sm_last}</h4>";
		$parent .= "{$sm_email}<br>";
		$parent .= "{$sm_phone}";
	}

	return $parent;
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
