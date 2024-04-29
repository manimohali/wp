
<?php 

function permission_callback_JWTPBM($request){
    $respone = verify_bearer_authorization_header_JWTPBM();
    if($respone['status'] != 1){
        return false;
    }
    return (int) $respone['data']->sub;
}

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


    register_rest_route($route_prefix, '/posts', array(
        'methods' => 'GET',
        'callback' => 'jwtpbm_get_access_token',
        'permission_callback' => 'permission_callback_JWTPBM',
        'args' => array(
            // 'refreshToken' => array(
            //     'type' => 'string',
            //     'required' => true,
            //     'description' => 'The user refreshToken',
            // )
        ),
    ));



}






/**
 * Custom endpoint to retrieve custom post data.
 */
function custom_get_custom_posts( $request ) {
    // Set up pagination parameters.
    $params = $request->get_params();
    $page = isset( $params['page'] ) ? absint( $params['page'] ) : 1;
    $per_page = 10;
    $offset = ( $page - 1 ) * $per_page;

    // Retrieve custom post data.
    $args = array(
        'post_type'      => 'custom_post',
        'posts_per_page' => $per_page,
        'offset'         => $offset,
        'paged'          => $page,
    );
    $custom_posts = new WP_Query( $args );

    // Check if custom posts are found.
    if ( $custom_posts->have_posts() ) {
        // Prepare custom post data for response.
        $data = array();
        while ( $custom_posts->have_posts() ) {
            $custom_posts->the_post();
            $data[] = array(
                'id'    => get_the_ID(),
                'title' => get_the_title(),
                'content' => get_the_content(),
                // Add more post data as needed.
            );
        }

        // Reset post data.
        wp_reset_postdata();

        // Return success response with custom post data.
        return rest_ensure_response( $data );
    } else {
        // No custom posts found.
        return new WP_Error( 'no_custom_posts_found', __( 'No custom posts found.', 'your-text-domain' ), array( 'status' => 404 ) );
    }
}