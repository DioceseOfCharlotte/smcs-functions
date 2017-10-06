<?php
add_filter( 'gform_field_value_sm_subscription', 'sm_subscription_populate' );
add_action( 'login_enqueue_scripts', 'smcs_login_logo' );
add_filter( 'login_headerurl', 'smcs_login_logo_url' );
add_filter( 'login_headertitle', 'smcs_login_logo_url_title' );

// dynamically populate a GF field with the RPC subscription ID.
function sm_subscription_populate( $value ) {
	return rcp_get_subscription_id( get_current_user_id() );
}


add_theme_support( 'custom-logo', array(
	'height'      => 78,
	'flex-width' => true,
) );

function smcs_login_logo() {
	if ( ! has_custom_logo() ) { return; }
	$bg_image = get_background_image();
	$logo_image = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ) ); ?>

		<style id="login-custom-logo">
			body.login {
				background-image: url(<?php echo $bg_image ?>);
				background-size: cover;
			}
			#login:after {
				content: "";
				background-color: rgba(255, 255, 255, 0.6);
				width: 100%;
				height: 100%;
				position: absolute;
				top: 0;
				left: 0;
				opacity: .9;
				z-index: -1;
			}
			#login h1 a {
				background-image: url(<?php echo $logo_image ?>);
			}
			#backtoblog {
				display: none;
			}
			.login .login-links {
				width: 272px;
				margin: 12px auto 0;
				font-size: 13px;
			}
			.login a {
				text-decoration: none;
				color: #555d66;
			}
			#login .message {
				border-left: 4px solid #e7b20e;
			}
		</style>
	<?php }

function smcs_login_logo_url() {
	return home_url();
}

function smcs_login_logo_url_title() {
	ob_start();
	bloginfo( 'name' );
	return ob_get_clean();
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
