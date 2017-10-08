<?php
add_action( 'login_enqueue_scripts', 'smcs_login_styles' );
add_filter( 'login_headerurl', 'smcs_login_logo_url' );
add_filter( 'login_headertitle', 'smcs_login_logo_url_title' );

add_theme_support( 'custom-logo', array(
	'height'      => 84,
	'flex-width' => true,
) );

function smcs_login_styles() {

	$style = get_smcs_login_styles();

	$style = "\n" . '<style type="text/css" id="custom-login">' . trim( $style ) . '</style>' . "\n";

	echo $style;
}

function get_smcs_login_styles() {
	$bg_image = get_background_image();
	$background_color = get_background_color();
	$logo_image = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ) );

	$style = '';

	if ( has_custom_logo() ) {
		$style .= "#login h1 a {background-image: url({$logo_image});}";
	}
	if ( $bg_image ) {
		$style .= "body.login{background-image:url({$bg_image});background-size:cover;}";
	}
	if ( $background_color ) {
		$style .= "body.login{background-color:#{$background_color};}";
	}
	if ( $bg_image && $background_color ) {
		$style .= "#login:after{content:'';background-color:#{$background_color};}";
		$style .= '#login:after{width: 100%;height: 100%;position: absolute;top: 0;left: 0;opacity: .9;z-index: -1;}';
	}
	$style .= '#login .message {border-left: 4px solid #e7b20e;}';

	//return str_replace( array( "\r", "\n", "\t" ), '', $style );
	return $style;
}

function smcs_login_logo_url() {
	return home_url();
}

function smcs_login_logo_url_title() {
	return get_bloginfo( 'name' );
}
