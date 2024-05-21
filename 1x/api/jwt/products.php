<?php
/**
 * @package 1x
 * @version 1.0
 */


require 'vendor/autoload.php';
require_once './token_functionality.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$headers = getallheaders();


/**
 *  status: 0 ( Faliure)
 *  status: 1 (Success)
 */

$response            = array();
$response['status']  = 0;
$response['message'] = 'Please Provide us message';
$http_response_code  = 401;
try {


	if ( ! isset( $headers['Authorization'] ) ) {
		$http_response_code = 400;
		throw new Exception( 'Authorization header does not exists' );
	}

	$authorization = explode( ' ', $headers['Authorization'] );
	if ( count( $authorization ) !== 2 ) {
		$http_response_code = 400;
		throw new Exception( 'Authorization header is not valid' );
	}

	$token_type = strtolower( trim( $authorization[0] ) );
	if ( $token_type !== 'bearer' ) {
		$http_response_code = 400;
		throw new Exception( 'Authorization token type is not valid' );
	}
	if ( trim( $authorization[1] ) === '' ) {
		$http_response_code = 401;
		throw new Exception( 'Authorization token is not valid' );
	}


	$token = trim( $authorization[1] );
	$jwt   = base64_decode( $token );

	$your_secret_key     = mySecrectKey();
	$decoded             = JWT::decode( $jwt, new Key( $your_secret_key, 'HS256' ) );
	$http_response_code  = 200;
	$response['status']  = 1;
	$response['message'] = 'Success';
	$response['data']    = $decoded;
} catch ( \Exception  $e ) {
	$error_mesage        = $e->getMessage();
	$response['message'] = $error_mesage;
}

header( 'Content-Type: application/json' );
http_response_code( $http_response_code );
echo wp_json_encode( $response );
die();
