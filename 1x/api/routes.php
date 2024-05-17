<?php

function permission_callback_JWTPBM( $request ) {
	$respone = verify_bearer_authorization_header_JWTPBM();
	if ( $respone['status'] != 1 ) {
		return new WP_Error( 'invalid_token', $respone['message'], array( 'status' => $respone['error_code'] ) );
	}
	return (int) $respone['data']->sub;
}

add_action( 'rest_api_init', 'jwtpbm_register_api', 10 );
function jwtpbm_register_api( $wp_rest_server ) {
	$JWTPBM_Posts      = new JWTPBM_Posts();
	$JWTPBM_WP_Options = new JWTPBM_WP_Options();
	/*
	* @prefix jwtpbm ( JWT plugin By Mani )
	*/
	$route_prefix = 'jwtpbm/v1';

	register_rest_route(
		$route_prefix,
		'/login',
		array(
			'methods'             => 'POST',
			'callback'            => 'jwtpbm_login',
			'permission_callback' => '__return_true',
			'args'                => array(
				'username' => array(
					'type'        => 'string',
					'required'    => true,
					'description' => 'The user username or email address',
				),
				'password' => array(
					'type'        => 'string',
					'required'    => true,
					'description' => 'The user password',
				),

			),
		)
	);

	register_rest_route(
		$route_prefix,
		'/register',
		array(
			'methods'             => 'POST',
			'callback'            => 'jwtpbm_register_user',
			'permission_callback' => '__return_true',
			'args'                => array(
				'email'    => array(
					'type'        => 'string',
					'required'    => true,
					'description' => 'The user email address',
				),
				'password' => array(
					'type'        => 'string',
					'required'    => true,
					'description' => 'The user password',
				),
				'username' => array(
					'type'        => 'string',
					'required'    => true,
					'description' => 'The user username',
				),
			),
		)
	);

	register_rest_route(
		$route_prefix,
		'/token',
		array(
			'methods'             => 'POST',
			'callback'            => 'jwtpbm_get_access_token',
			'permission_callback' => '__return_true',
			'args'                => array(
				'refreshToken' => array(
					'type'        => 'string',
					'required'    => true,
					'description' => 'The user refreshToken',
				),
			),
		)
	);

	register_rest_route(
		$route_prefix,
		'/posts',
		array(
			'methods'             => 'GET',
			'callback'            => array( $JWTPBM_Posts, 'get_posts' ),
			'permission_callback' => 'permission_callback_JWTPBM',
			'args'                => array(

				'page'        => array(
					'type'        => 'integer',
					'required'    => false,
					'description' => 'Page Number',
				),

				'per_page'    => array(
					'type'        => 'integer',
					'required'    => false,
					'description' => 'Per Page',
				),
				'post_type'   => array(
					'type'        => 'string',
					'required'    => false,
					'description' => 'Post Type',
				),

				'post_status' => array(
					'type'        => 'string',
					'required'    => false,
					'description' => 'Post Status',
				),

			),
		)
	);

	register_rest_route(
		$route_prefix,
		'/post',
		array(
			'methods'             => 'GET',
			'callback'            => array( $JWTPBM_Posts, 'get_post' ),
			'permission_callback' => 'permission_callback_JWTPBM',
			'args'                => array(

				'id' => array(
					'type'        => 'integer',
					'required'    => true,
					'description' => 'Post id',
				),
			),
		)
	);

	register_rest_route(
		$route_prefix,
		'/category/head',
		array(
			'methods'             => 'GET',
			'callback'            => array( $JWTPBM_WP_Options, 'head_categories' ),
			'permission_callback' => 'permission_callback_JWTPBM',
			'args'                => array(),
		)
	);

	register_rest_route(
		$route_prefix,
		'/category/posts',
		array(
			'methods'             => 'GET',
			'callback'            => array( $JWTPBM_Posts, 'get_posts_by_category' ),
			'permission_callback' => 'permission_callback_JWTPBM',
			'args'                => array(

				'term_id'        => array(
					'type'        => 'integer',
					'required'    => true,
					'description' => 'Category id (term_id ) is required',
				),
				'posts_per_page' => array(
					'type'        => 'integer',
					'required'    => false,
					'description' => 'Posts per page ( In integer)',
				),
				'page'           => array(
					'type'        => 'integer',
					'required'    => false,
					'description' => 'Current page number',
				),
				'taxonomy'       => array(
					'type'        => 'string',
					'required'    => false,
					'description' => 'Taxonomy name ( slug )',
				),
				'post_type'      => array(
					'type'        => 'string',
					'required'    => false,
					'description' => 'Post type slug',
				),

			),
		)
	);

	// this route accept only intiger only (customer_id:int , product_id:int)
	register_rest_route(
		$route_prefix,
		'/customer/(?P<customer_id>\d+)/product/(?P<product_id>\d+)',
		array(
			'methods'  => 'GET',
			'callback' => array( $JWTPBM_Posts, 'custom_get_customer_product_data' ),
		)
	);

	// this route accept  string and int both (customer_id:string|int , product_id:int)
	register_rest_route(
		$route_prefix,
		'/customer/(?P<customer_id>[a-zA-Z0-9-]+)/product/(?P<product_id>\d+)',
		array(
			'methods'  => 'GET',
			'callback' => array( $JWTPBM_Posts, 'custom_get_customer_product_data_string' ),
		)
	);
}
