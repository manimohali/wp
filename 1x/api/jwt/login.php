<?php

require 'vendor/autoload.php';


use Firebase\JWT\JWT;

// Database connection
$db = require_once './db.php';
require_once './token_functionality.php';
$pdo         = $db['pdo'];
$status_code = 500;

try {

	if ( ! $db['status'] ) {
		$status_code = 502;
		throw new \Exception( 'Error connecting to database' );
	}

	if ( ! isset( $_POST['username'] ) || ! isset( $_POST['password'] ) ) {
		$status_code = 400;
		throw new \Exception( 'Username and password are required' );
	}


	$username = $_POST['username'];
	$password = $_POST['password'];


	$stmt = $pdo->prepare( 'SELECT * FROM users WHERE username = ?' );
	$stmt->execute( array( $username ) );
	$user = $stmt->fetch();

	$response_A = array(
		'status'  => 0,
		'data'    => array(),
		'message' => '',
	);


	$pass_status = password_verify( trim( $password ), password_hash( trim( $user['password'] ), PASSWORD_BCRYPT ) );
	if ( $user && $pass_status ) {

		$userId                  = (int) $user['id'];
		$refreshToken            = generateSecureToken();
		$refreshTokenExpiry      = date( 'Y-m-d H:i:s', ( strtotime( 'now' ) + 60 * 5 ) );   // Refresh token expires after  5 minutes
		$refreshTokenExpiry_unix = strtotime( $refreshTokenExpiry );  // Unix timestamp
		storeRefreshToken( $userId, $refreshToken, $refreshTokenExpiry_unix );

		$payload = array(
			'iss' => 'your_issuer', // Issuer
			'aud' => 'your_audience', // Audience
			'iat' => time(), // Issued at
			'exp' => time() + 3600, // Expiration time
			'sub' => $user['id'], // Subject
		);

		$your_secret_key = mySecrectKey();
		$jwt             = JWT::encode( $payload, $your_secret_key, 'HS256' );
		$token           = base64_encode( $jwt );

		$response_A  = array(
			'status'  => 1,
			'data'    => array(
				'token'        => $token,
				'refreshToken' => $refreshToken,
			),
			'message' => 'You logged in successfully',
		);
		$status_code = 200;
	} else {
		$status_code           = 401;
		$response_A['status']  = 0;
		$response_A['message'] = 'Invalid credentials';
	}
} catch ( \Exception $e ) {
	$status_code           = 502;
	$response_A['message'] = $e->getMessage();
}

header( 'Content-Type: application/json' );
http_response_code( $status_code );
echo wp_json_encode( $response_A );
