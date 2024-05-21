<?php
/**
 * @package 1x
 * @since 1.0.0
 */


/**
 * Admin footer
 *
 * @return void
 */
function jwtpbm_admin_footer() {
	// echo "admin_footer_test";
}
add_action( 'admin_footer', 'jwtpbm_admin_footer' );


/**
 * Front footer
 *
 * @return void
 */
function jwtpbm_wp_footer() {
	// echo "wp_footer_test";
}
add_action( 'wp_footer', 'jwtpbm_wp_footer' );
