<?php

use Firebase\JWT\JWT;

/**
 * Path: /wp-json/jwtpbm/v1/token
 *
 * @param WP_REST_Request $request
 *
 * @return WP_REST_Response
 *
 * @method POST   ['refreshToken']
 */
function jwtpbm_get_access_token( WP_REST_Request $request ) {
	$refreshToken = trim( $request->get_param( 'refreshToken' ) );
	$user_ID      = JWTPBM_JWTManager::isRefreshTokenExists( $refreshToken );

	if ( $user_ID == false ) {
		return new WP_Error( 'invalid_refresh_token', 'Invalid refresh token please login again', array( 'status' => 401 ) );
	}

	$token_response = JWTPBM_JWTManager::generateAccessToken( $user_ID );
	if ( $token_response['status'] == 0 ) {
		return new WP_Error( 'token_generation_failed', $token_response['message'], array( 'status' => 401 ) );
	}

	return new WP_REST_Response( $token_response, 200 );
}

/**
 * Path: /wp-json/jwtpbm/v1/register
 *
 * @param WP_REST_Request $request
 *
 * @return WP_REST_Response
 *
 * @method POST   ['username','password','email']
 */
function jwtpbm_register_user( WP_REST_Request $request ) {

	$username = trim( $request->get_param( 'username' ) );
	$password = trim( $request->get_param( 'password' ) );
	$email    = trim( $request->get_param( 'email' ) );

	if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
		return new WP_Error( 'missing_data', 'Username, password, and email are required', array( 'status' => 400 ) );
	}

	if ( username_exists( $username ) ) {
		return new WP_Error( 'user_exists', 'User with provided username already exists', array( 'status' => 409 ) );  // 409 Conflict
	}

	if ( email_exists( $email ) ) {
		return new WP_Error( 'user_exists', 'User with provided  email already exists', array( 'status' => 400 ) );
	}

	$user_id = wp_create_user( $username, $password, $email );

	if ( is_wp_error( $user_id ) ) {
		$error_string = $user_id->get_error_message();
		$error_code   = array_key_first( $user_id->errors );
		return new WP_Error( 'user_create_error', "Error: {$error_string} , error_code {$error_code}", array( 'status' => 400 ) );
	}

	// Return success message
	return new WP_REST_Response(
		array(
			'status'  => 1,
			'message' => 'User registered successfully',
			'user_id' => $user_id,
		),
		200
	);
}


/**
 * Path: /wp-json/jwtpbm/v1/register
 *
 * @param WP_REST_Request $request
 *
 * @return WP_REST_Response
 *
 * @method POST   ['username ( username or email)','email','password']
 */
function jwtpbm_login( WP_REST_Request $request ) {
	$username = $request->get_param( 'username' );
	$password = $request->get_param( 'password' );

	// Basic validation
	if ( empty( $username ) || empty( $password ) ) {
		return new WP_Error( 'missing_data', 'Username or Email  and password are required', array( 'status' => 400 ) );
	}

	$isEmail = filter_var( $username, FILTER_VALIDATE_EMAIL ) !== false;
	if ( $isEmail ) {
		$user = get_user_by( 'email', $username );
	}

	if ( ! $isEmail ) {
		$user = get_user_by( 'login', $username );
	}

	if ( ! $user ) {
		return new WP_Error( 'invalid_credentials', 'Invalid username or password', array( 'status' => 401 ) );
	}

	// Check if password is correct
	if ( ! wp_check_password( $password, $user->user_pass ) ) {
		return new WP_Error( 'invalid_credentials', 'Invalid username or password', array( 'status' => 401 ) );
	}

	JWTPBM_DbTables::create_refresh_tokens_table();

	$token_response = JWTPBM_JWTManager::getInstance()->generateLoginToken( $user->ID );
	if ( $token_response['status'] == 0 ) {
		return new WP_Error( 'token_generation_failed', $token_response['message'], array( 'status' => 401 ) );
	}

	// Return token
	return new WP_REST_Response( $token_response, 200 );
}


function verify_bearer_authorization_header_JWTPBM() {
	$headers             = getallheaders();
	$response            = array();
	$response['status']  = 0;
	$response['message'] = 'Please Provide us message';
	try {

		if ( ! isset( $headers['Authorization'] ) ) {
			throw new Exception( 'Authorization header does not exists', 400 );
		}

		$authorization = explode( ' ', $headers['Authorization'] );
		if ( count( $authorization ) != 2 ) {
			throw new Exception( 'Authorization header is not valid', 400 );
		}

		$token_type = strtolower( trim( $authorization[0] ) );
		if ( $token_type != 'bearer' ) {
			throw new Exception( 'Authorization token type is not valid', 400 );
		}
		if ( trim( $authorization[1] ) == '' ) {
			throw new Exception( 'Authorization token is not valid', 401 );
		}

		$token   = trim( $authorization[1] );
		$decoded = JWTPBM_JWTManager::decode_token( $token );

		$response['status']  = 1;
		$response['message'] = 'Success';
		$response['data']    = $decoded;
	} catch ( \Exception  $e ) {
		$error_mesage           = $e->getMessage();
		$response['error_code'] = $e->getCode() == 0 ? 400 : $e->getCode();
		$response['message']    = $error_mesage;
	}

	return $response;
}


/** Allow Woocommerce to use JWT auth  **/
function is_request_to_rest_api_jwtpbm() {
	if ( empty( $_SERVER['REQUEST_URI'] ) ) {
		return false;
	}

	$rest_prefix = trailingslashit( rest_get_url_prefix() );
	$request_uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );

	// Check if the request is to the WC API endpoints.
	$woocommerce = ( false !== strpos( $request_uri, $rest_prefix . 'wc/' ) ||
					false !== strpos( $request_uri, $rest_prefix . 'jwtpbm/' )
					);
	return $woocommerce;
}



/**
 * @param $user_id  int|false
*/
add_filter( 'determine_current_user', 'authenticate_jwtpbm', 10 );
function authenticate_jwtpbm( $user_id ) {

	if ( ! is_request_to_rest_api_jwtpbm() ) {
		return $user_id;
	}

	/** if user logged in return user_id */
	if ( is_int( $user_id ) ) {
		return $user_id;
	}

	$respone = verify_bearer_authorization_header_JWTPBM();
	if ( $respone['status'] != 1 ) {
		return $user_id;
	}

	return (int) $respone['data']->sub;
}





/**
 * Overrider user permission for rest api
 *
 * @param $permission  bool
 * @param $context  string   read|create|edit|delete|batch
 * @param $object_id  int  UserId
 * @param $post_type  string    'reports'| 'product'
*/

add_filter( 'woocommerce_rest_check_permissions', 'jwtpbm_override_user_permission', 9999999, 4 );
function jwtpbm_override_user_permission( $permission, $context, $object_id, $post_type ) {
	if ( $permission ) {
		return $permission;   // If permission is already true then return it
	}

	// $current_route = $_SERVER['REQUEST_URI'];
	// if ( strpos( $current_route, 'your-specific-route' ) !== false ) {
	// }

	$user    = wp_get_current_user();
	$user_id = (int) $user->ID;
	if ( $user_id == 0 ) {
		return $permission;
	}

	// if user customer and has capability to read
	if ( in_array( 'customer', $user->roles ) && current_user_can( 'read' ) ) {
		return true;
	}

	return $permission;
}
