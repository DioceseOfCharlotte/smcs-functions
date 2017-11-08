<?php

function sm_get_address( $user_id ) {

	$user_id = sm_get_group_owner_id();

	$sm_street   = get_user_meta( $user_id, 'sm_home_street', true );
	$sm_city     = get_user_meta( $user_id, 'sm_home_city', true );
	$sm_state    = get_user_meta( $user_id, 'sm_home_state', true );
	$sm_home_zip = get_user_meta( $user_id, 'sm_home_zip', true );

	$address = '';

	if ( $sm_city ) {
		$address .= "{$sm_street}<br>";
		$address .= "{$sm_city}, {$sm_state} {$sm_home_zip}";
	}

	return $address;
}

function sm_get_student_array( $user_id = 0 ) {

	$user_id  = $user_id ?: get_current_user_id();
	$owner_id = sm_get_group_owner_id( $user_id );

	$s_1_first = get_user_meta( $owner_id, 'sm_student_1_first', true );
	$s_1_last  = get_user_meta( $owner_id, 'sm_student_1_last', true );
	$s_1_grade = get_user_meta( $owner_id, 'sm_student_1_grade', true );
	$s_2_first = get_user_meta( $owner_id, 'sm_student_2_first', true );
	$s_2_last  = get_user_meta( $owner_id, 'sm_student_2_last', true );
	$s_2_grade = get_user_meta( $owner_id, 'sm_student_2_grade', true );
	$s_3_first = get_user_meta( $owner_id, 'sm_student_3_first', true );
	$s_3_last  = get_user_meta( $owner_id, 'sm_student_3_last', true );
	$s_3_grade = get_user_meta( $owner_id, 'sm_student_3_grade', true );
	$s_4_first = get_user_meta( $owner_id, 'sm_student_4_first', true );
	$s_4_last  = get_user_meta( $owner_id, 'sm_student_4_last', true );
	$s_4_grade = get_user_meta( $owner_id, 'sm_student_4_grade', true );
	$s_5_first = get_user_meta( $owner_id, 'sm_student_5_first', true );
	$s_5_last  = get_user_meta( $owner_id, 'sm_student_5_last', true );
	$s_5_grade = get_user_meta( $owner_id, 'sm_student_5_grade', true );

	$students = array();

	if ( $s_1_first ) {
		$students = array(
			's1' => array(
				'first' => $s_1_first,
				'last'  => $s_1_last,
				'grade' => $s_1_grade,
			),
			's2' => array(
				'first' => $s_2_first,
				'last'  => $s_2_last,
				'grade' => $s_2_grade,
			),
			's3' => array(
				'first' => $s_3_first,
				'last'  => $s_3_last,
				'grade' => $s_3_grade,
			),
			's4' => array(
				'first' => $s_4_first,
				'last'  => $s_4_last,
				'grade' => $s_4_grade,
			),
			's5' => array(
				'first' => $s_5_first,
				'last'  => $s_5_last,
				'grade' => $s_5_grade,
			),
		);
	}

	return $students;
}

function sm_get_student_names( $user_id = 0 ) {

	$user_id  = $user_id ?: get_current_user_id();
	$owner_id = sm_get_group_owner_id( $user_id );

	$students = sm_get_student_array( $owner_id );

	$student_name = '';

	foreach ( $students as $student ) {

		if ( ! empty( $student['first'] ) ) {
			$student_name .= '<span class="sm-firstname">' . $student['first'] . ' </span><span class="sm-lastname">' . $student['last'] . ' </span>';
		}
	}

	return $student_name;
}

function sm_get_student_grades( $user_id = 0 ) {

	$user_id  = $user_id ?: get_current_user_id();
	$owner_id = sm_get_group_owner_id( $user_id );

	$students = sm_get_student_array( $owner_id );

	$student_grade = '';

	foreach ( $students as $student ) {

		if ( '1' == $student['grade'] ) {
			$grade = '1st';
		} elseif ( '2' == $student['grade'] ) {
			$grade = '2nd';
		} elseif ( '3' == $student['grade'] ) {
			$grade = '3rd';
		} elseif ( '4' == $student['grade'] ) {
			$grade = '4th';
		} elseif ( '5' == $student['grade'] ) {
			$grade = '5th';
		} elseif ( '6' == $student['grade'] ) {
			$grade = '6th';
		} elseif ( '7' == $student['grade'] ) {
			$grade = '7th';
		} elseif ( '8' == $student['grade'] ) {
			$grade = '8th';
		} else {
			$grade = 'K';
		}

		if ( ! empty( $student['first'] ) ) {
			$student_grade .= $grade;
		}
	}

	return $student_grade;
}

function sm_get_students( $user_id = 0 ) {

	$user_id  = $user_id ?: get_current_user_id();
	$owner_id = sm_get_group_owner_id( $user_id );

	$students = sm_get_student_array( $owner_id );

	$student_name = '';

	foreach ( $students as $student ) {

		if ( '1' == $student['grade'] ) {
			$grade = '1st';
		} elseif ( '2' == $student['grade'] ) {
			$grade = '2nd';
		} elseif ( '3' == $student['grade'] ) {
			$grade = '3rd';
		} elseif ( '4' == $student['grade'] ) {
			$grade = '4th';
		} elseif ( '5' == $student['grade'] ) {
			$grade = '5th';
		} elseif ( '6' == $student['grade'] ) {
			$grade = '6th';
		} elseif ( '7' == $student['grade'] ) {
			$grade = '7th';
		} elseif ( '8' == $student['grade'] ) {
			$grade = '8th';
		} else {
			$grade = 'K';
		}

		if ( ! empty( $student['first'] ) ) {
			$student_name .= '<div class="sm-student-list-item"><span class="sm-firstname">' . $student['first'] . ' </span><span class="sm-lastname">' . $student['last'] . ' </span><span class="sm-grade">' . $grade . '</span></div>';
		}
	}

	return $student_name;
}

function sm_get_parent( $account_creater_id, $p_number = '1' ) {
	$account_creater_id = sm_get_group_owner_id();
	$user_info          = get_userdata( $account_creater_id );

	$sm_first = get_user_meta( $account_creater_id, "sm_parent_{$p_number}_first", true );
	$sm_last  = get_user_meta( $account_creater_id, "sm_parent_{$p_number}_last", true );
	$sm_email = get_user_meta( $account_creater_id, "sm_parent_{$p_number}_email", true );
	$sm_phone = get_user_meta( $account_creater_id, "sm_parent_{$p_number}_phone", true );

	if ( '1' == $p_number && ! empty( $user_info ) ) {
		$sm_first = $user_info->first_name;
		;
		$sm_last  = $user_info->last_name;
		$sm_email = $user_info->user_email;
	}

	$parent = '';

	if ( $sm_first ) {
		$parent .= "<div class='sm-parent parent-{$p_number}'>";
		$parent .= "<div class='parent-name'>{$sm_first} {$sm_last}</div>";
		$parent .= "<div class='parent-phone'>{$sm_phone}</div>";
		$parent .= "<div class='parent-email'>{$sm_email}</div>";
		$parent .= '</div>';
	}

	return $parent;
}

function sm_get_template_part( $slug ) {

	$template       = smcs_functions()->dir . "templates/{$slug}.php";
	$theme_template = locate_template( array( "smcs/{$slug}.php" ) );

	if ( $theme_template ) {
		return get_template_part( "smcs/{$slug}" );
	}

	// If template is found, include it.
	if ( $template ) {
		ob_start();
		include( $template );
		return ob_get_clean();
	}
}
