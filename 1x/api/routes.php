
<?php 

add_action('rest_api_init', 'jwtpbm_register_api', 10);
function jwtpbm_register_api($wp_rest_server) {
    /*
    * @prefix jwtpbm ( JWT plugin By Mani )
    */
    $route_prefix = 'jwtpbm/v1';

    register_rest_route($route_prefix, '/login', array(
        'methods'             => 'POST',
        'callback'            => 'jwtpbm_login',
        'permission_callback' => '__return_true',
        'args' => array(
            'username' => array(
                'type' => 'string',
                'required' => true,
                'description' => 'The user username or email address',
            ),
            'password' => array(
                'type' => 'string',
                'required' => true,
                'description' => 'The user password',
            ),

        ),
    ));


    register_rest_route($route_prefix, '/register', array(
        'methods' => 'POST',
        'callback' => 'jwtpbm_register_user',
        'permission_callback' => '__return_true',
        'args' => array(
            'email' => array(
                'type' => 'string',
                'required' => true,
                'description' => 'The user email address',
            ),
            'password' => array(
                'type' => 'string',
                'required' => true,
                'description' => 'The user password',
            ),
            'username' => array(
                'type' => 'string',
                'required' => true,
                'description' => 'The user username',
            )
        ),
    ));

    register_rest_route($route_prefix, '/register', array(
        'methods' => 'POST',
        'callback' => 'jwtpbm_register_user',
        'permission_callback' => '__return_true',
        'args' => array(
            'email' => array(
                'type' => 'string',
                'required' => true,
                'description' => 'The user email address',
            ),
            'password' => array(
                'type' => 'string',
                'required' => true,
                'description' => 'The user password',
            ),
            'username' => array(
                'type' => 'string',
                'required' => true,
                'description' => 'The user username',
            )
        ),
    ));

    register_rest_route($route_prefix, '/token', array(
        'methods' => 'POST',
        'callback' => 'jwtpbm_get_access_token',
        'permission_callback' => '__return_true',
        'args' => array(
            'refreshToken' => array(
                'type' => 'string',
                'required' => true,
                'description' => 'The user refreshToken',
            )
        ),
    ));
}