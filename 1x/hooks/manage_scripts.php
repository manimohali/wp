<?php 

/**** Register Scripts For Admin Panel ***********/
add_action( 'admin_enqueue_scripts', 'jwtpbm_register_wp_enqueue_scripts_admin_end' );
function jwtpbm_register_wp_enqueue_scripts_admin_end($hook ) {
    $plugin_version =JWTPBM_PLUGIN_VERSION ;
    wp_register_script( '1x-admin-js', JWTPBM_PLUGIN_URL .'hooks/js/admin.js', array( 'jquery' ), $plugin_version, true );
    wp_register_style( '1x-admin-css', JWTPBM_PLUGIN_URL .'hooks/css/admin.css', array(), $plugin_version );

    wp_register_style( '1x-nestable-css', JWTPBM_PLUGIN_URL .'hooks/css/jquery.nestable.min.css', array(), $plugin_version );
    wp_register_script( '1x-nestable-js', JWTPBM_PLUGIN_URL .'hooks/js/jquery.nestable.js', array( 'jquery' ), $plugin_version, true );

    wp_register_script( '1x-select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array( ), $plugin_version );
    wp_register_script( '1x-select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), '1.10.24', false );


    wp_enqueue_script( '1x-admin-js' );
    wp_enqueue_style( '1x-admin-css' );

    /* page menu slug: sortable-menu */
    if ( 'toplevel_page_sortable-menu' == $hook ) {
        wp_enqueue_style( '1x-nestable-css' );
        wp_enqueue_script( '1x-nestable-js' );
        wp_enqueue_style( '1x-select2-css' );
        wp_enqueue_script( '1x-select2-js' );
    }
}


/*** Register Scripts For Front End ***********/
add_action( 'wp_enqueue_scripts', 'jwtpbm_register_wp_enqueue_scripts_front_end' );
function jwtpbm_register_wp_enqueue_scripts_front_end() {
    $plugin_version =JWTPBM_PLUGIN_VERSION ;
    wp_register_script( '1x-js', JWTPBM_PLUGIN_URL .'hooks/js/1x.js', array( 'jquery' ), $plugin_version, true );
    wp_register_style( '1x-css', JWTPBM_PLUGIN_URL .'hooks/css/style.css', array(), $plugin_version );
    
    wp_enqueue_script( '1x-js' );
    wp_enqueue_style( '1x-css' );

    #localize script    
    $localize_data = array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'plugin_url' => JWTPBM_PLUGIN_URL,
        'nonce' => wp_create_nonce( 'jwtpbm_nonce' ),
    );
    wp_localize_script( '1x-js', 'jwtpbm_localize_data', $localize_data );


}
