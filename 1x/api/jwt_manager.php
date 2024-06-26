<?php
/**
 * @package 1x
 * @version 1.0.0
 */

require_once JWTPBM_PLUGIN_DIR . '/api/jwt/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTPBM_JWTManager {

	public static $secret_key                  = 'my_secret_key';
	public static $token_issuer                = 'im_token_issuer';
	public static $token_audience              = 'customer';
	public static $refreshTokenExpiryInMinutes = ( 60 * 60 * 24 );    // 24 hours
	// public static $refreshTokenExpiryInMinutes = (120);    // 1 Minute
	public static $tokenExpiryInSeconds = (60 * 60 * 4); // 4 hour

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function __construct() {
	}


	/**
	 * Undocumented function
	 *
	 * @return JWTPBM_JWTManager
	 */
	public static function getInstance() {
		return new JWTPBM_JWTManager();
	}

	/**
	 * Undocumented function
	 *
	 * @param integer $length
	 * @return String
	 */
	public static function generateSecureToken( $length = 64 ) {
		return bin2hex( random_bytes( $length ) );
	}

	/**
	 * Undocumented function
	 *
	 * @param integer $user_id
	 * @return Array
	 */
	public function generateLoginToken( int $user_id ) {
		$response_A = array(
			'status'  => 0,
			'data'    => array(),
			'message' => '',
		);

		try {

			self::deleteExpiredRefreshToken();  // clear old refresh token
			$refreshToken            = self::generateSecureToken();
			$refreshTokenExpiry_unix = strtotime( date( 'Y-m-d H:i:s', ( strtotime( 'now' ) + self::$refreshTokenExpiryInMinutes ) ) ); // Unix timestamp
			$payload                 = array(
				'iss' => self::$token_issuer,
				'aud' => self::$token_audience,
				'iat' => time(), // Issued at
				'exp' => time() + self::$tokenExpiryInSeconds,
				'sub' => $user_id,
			);

			$secret_key = self::$secret_key;
			$jwt        = JWT::encode( $payload, $secret_key, 'HS256' );
			$token      = base64_encode( $jwt );

			$response_A = array(
				'status'       => 1,
				'token'        => $token,
				'refreshToken' => $refreshToken,
			);

			$token_insert_response = self::storeRefreshToken( $user_id, $refreshToken, $refreshTokenExpiry_unix );
			if ( $token_insert_response === false ) {
				throw new Exception( 'Refresh token not inserted ', 500 );
			}
		} catch ( \Exception $e ) {
			$response_A['message'] = $e->getMessage();
		}

		// update_user_meta($user_id, 'jwt_pbm_login_token', $token);
		return $response_A;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $user_id
	 * @param [type] $refreshToken
	 * @param [type] $expires
	 * @return int|boolean
	 */
	public static function storeRefreshToken( $user_id, $refreshToken, $expires ) {
		global $wpdb;
		self::deleteExpiredRefreshToken();  // clear old refresh token

		$table_name = $wpdb->prefix . JWTPBM_DbTables::$tbl_refresh_tokens;
		$token      = $refreshToken;
		$wpdb->insert(
			$table_name,
			array(
				'user_id' => $user_id,
				'token'   => $token,
				'expires' => $expires,
			),
			array( '%d', '%s', '%d' )
		);
		if ( $wpdb->insert_id ) {
			return $wpdb->insert_id;
		} else {
			return false;
		}
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public static function deleteExpiredRefreshToken() {
		global $wpdb;
		$table_name = $wpdb->prefix . JWTPBM_DbTables::$tbl_refresh_tokens;
		$wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE expires < %d", time() ) );
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $token
	 * @return int|boolean
	 */
	public static function isRefreshTokenExists( $token ) {
		global $wpdb;
		self::deleteExpiredRefreshToken();
		$table_name = $wpdb->prefix . JWTPBM_DbTables::$tbl_refresh_tokens;
		$sql        = $wpdb->prepare( "SELECT user_id FROM $table_name WHERE token = %s", $token );
		$user_id    = $wpdb->get_var( $sql );
		return is_null( $user_id ) ? false : (int) $user_id;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $user_id
	 * @return Array
	 */
	public static function generateAccessToken( $user_id ) {
		$response_A = array(
			'status'  => 0,
			'data'    => array(),
			'message' => '',
		);

		try {

			self::deleteExpiredRefreshToken();  // clear old refresh token
			$payload = array(
				'iss' => self::$token_issuer,
				'aud' => self::$token_audience,
				'iat' => time(), // Issued at
				'exp' => time() + self::$tokenExpiryInSeconds,
				'sub' => $user_id,
			);

			$secret_key = self::$secret_key;
			$jwt        = JWT::encode( $payload, $secret_key, 'HS256' );
			$token      = base64_encode( $jwt );

			$response_A = array(
				'status' => 1,
				'token'  => $token,
			);

		} catch ( \Exception $e ) {
			$response_A['message'] = $e->getMessage();
		}

		// update_user_meta($user_id, 'jwt_pbm_login_token', $token);
		return $response_A;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $token
	 * @return stdClass
	 */
	public static function decode_token( $token ) {
		$jwt        = base64_decode( $token );
		$secret_key = self::$secret_key;
		$decoded    = JWT::decode( $jwt, new Key( $secret_key, 'HS256' ) );
		return $decoded;
	}
}
