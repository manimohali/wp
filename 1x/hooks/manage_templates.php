<?php

/**
 * Fires before determining which template to load.
 *
 * @since 1.5.0
 */

// add_action( 'template_redirect','jwtpbm_template_redirect' );
function jwtpbm_template_redirect() {

	if ( is_front_page() ) {
		wp_redirect( home_url( '/dashboard/' ) );
		die;
	}
}


/**
 * Filters the path of the current template before including it.
 *
 * @param string $template The path of the template to include.
 */
add_filter( 'template_include', 'jwtpbm_template_include', 99 );
function jwtpbm_template_include( $template ) {
	if ( is_front_page() ) {
		// $template = JWTPBM_PLUGIN_DIR . '/hooks/templates/dashboard.php';
	}

	return $template;
}
