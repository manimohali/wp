<?php 

add_action('admin_footer', 'jwtpbm_admin_footer');
function jwtpbm_admin_footer() {
    // echo "admin_footer_test";
}

add_action('wp_footer', 'jwtpbm_wp_footer');
function jwtpbm_wp_footer() {
        // echo "wp_footer_test";
}