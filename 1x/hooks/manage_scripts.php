<?php 

/**** Register Scripts For Admin Panel ***********/
add_action( 'admin_enqueue_scripts', 'jwtpbm_register_wp_enqueue_scripts_admin_end' );
function jwtpbm_register_wp_enqueue_scripts_admin_end() {
    $plugin_version =JWTPBM_PLUGIN_VERSION ;
    wp_register_script( '1x-admin-js', JWTPBM_PLUGIN_URL .'hooks/js/admin.js', array( 'jquery' ), $plugin_version, true );
    wp_register_style( '1x-admin-css', JWTPBM_PLUGIN_URL .'hooks/css/admin.css', array(), $plugin_version );

    wp_enqueue_script( '1x-admin-js' );
    wp_enqueue_style( '1x-admin-css' );
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
