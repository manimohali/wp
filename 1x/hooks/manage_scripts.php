<?php
/**
 * Manage Scripts
 *
 * @package 1x
 */


/**
 *  Modify the added script
 *
 * @param [type] $tag
 * @param [type] $handle
 * @param [type] $src
 * @return $tag
 */
function add_type_script_attribute_1x( $tag, $handle, $src ) {

	if ( '1x-react-js' === $handle ) {

		$tag = '<script type="module"  crossorigin src="' . esc_url( $src ) . '"></script>';
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'add_type_script_attribute_1x', 10, 3 );



/**
 *  Modify the added style
 * apply_filters( 'style_loader_tag', $tag, $handle, $href, $media );
 *
 * @param [type] $tag
 * @param [type] $handle
 * @param [type] $href
 * @param [type] $media
 * @return  $tag
 */
function add_type_style_attribute_1x( $tag, $handle, $href, $media ) {

	if ( '1x-react-css' === $handle ) {
		$tag = '<link rel="stylesheet" id="' . $handle . '-css"  type="text/css" href="' . esc_url( $href ) . '" media="' . esc_attr( $media ) . '" />';
	}

	return $tag;
}
add_filter( 'style_loader_tag', 'add_type_style_attribute_1x', 10, 4 );



/**
 *  Register Scripts For Admin Panel
 *
 * @param [type] $hook
 * @return void
 */
function jwtpbm_register_wp_enqueue_scripts_admin_end( $hook ) {
	$plugin_version = JWTPBM_PLUGIN_VERSION;
	wp_register_script( '1x-admin-js', JWTPBM_PLUGIN_URL . 'hooks/js/admin.js', array( 'jquery' ), $plugin_version, true );
	wp_register_style( '1x-admin-css', JWTPBM_PLUGIN_URL . 'hooks/css/admin.css', array(), $plugin_version );

	wp_register_style( '1x-nestable-css', JWTPBM_PLUGIN_URL . 'hooks/css/jquery.nestable.min.css', array(), $plugin_version );
	wp_register_script( '1x-nestable-js', JWTPBM_PLUGIN_URL . 'hooks/js/jquery.nestable.js', array( 'jquery' ), $plugin_version, true );

	wp_register_style( '1x-select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), $plugin_version );
	wp_register_script( '1x-select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), '1.10.24', false );

	wp_enqueue_script( '1x-admin-js' );
	wp_enqueue_style( '1x-admin-css' );

	/* page menu slug: sortable-menu */
	if ( 'toplevel_page_sortable-menu' === $hook ) {
		wp_enqueue_style( '1x-nestable-css' );
		wp_enqueue_script( '1x-nestable-js' );
		wp_enqueue_style( '1x-select2-css' );
		wp_enqueue_script( '1x-select2-js' );
	}

	if ( 'toplevel_page_page-title-slug-2' === $hook ) {
		wp_register_style( '1x-react-css', JWTPBM_PLUGIN_URL . 'rc/rc/dist/assets/index.css', array(), $plugin_version );
		wp_register_script( '1x-react-js', JWTPBM_PLUGIN_URL . 'rc/rc/dist/assets/index.js', array(), $plugin_version . time(), false );
		wp_enqueue_style( '1x-react-css' );
		wp_enqueue_script( '1x-react-js' );
	}
}
add_action( 'admin_enqueue_scripts', 'jwtpbm_register_wp_enqueue_scripts_admin_end' );


/**
 *  Register Scripts For Front End
 *
 * @return void
 */
function jwtpbm_register_wp_enqueue_scripts_front_end() {
	$plugin_version = JWTPBM_PLUGIN_VERSION;
	wp_register_script( '1x-js', JWTPBM_PLUGIN_URL . 'hooks/js/1x.js', array( 'jquery' ), $plugin_version, true );
	wp_register_style( '1x-css', JWTPBM_PLUGIN_URL . 'hooks/css/style.css', array(), $plugin_version );

	wp_enqueue_script( '1x-js' );
	wp_enqueue_style( '1x-css' );

	// localize script
	$localize_data = array(
		'ajax_url'   => admin_url( 'admin-ajax.php' ),
		'plugin_url' => JWTPBM_PLUGIN_URL,
		'nonce'      => wp_create_nonce( 'jwtpbm_nonce' ),
	);
	wp_localize_script( '1x-js', 'jwtpbm_localize_data', $localize_data );
}
add_action( 'wp_enqueue_scripts', 'jwtpbm_register_wp_enqueue_scripts_front_end' );
