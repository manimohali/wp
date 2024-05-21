<?php
/**
 * @package 1x
 * @version 1.0
 */

/**
 * Undocumented function
 *
 * @param integer $length
 * @return string
 */
function generateSecureToken( $length = 64 ) {
	return bin2hex( random_bytes( $length ) );
}


/**
 * @param integer $userId
 * @param string  $token
 * @param integer $expiry
 * @return void
 */
function storeRefreshToken( $userId, $token, $expiry ) {
	global $pdo;
	try {
		$stmt = $pdo->prepare( 'INSERT INTO refresh_tokens (user_id, token, expires) VALUES (:user_id, :token, :expires)' );
		$stmt->execute(
			array(
				':user_id' => $userId,
				':token'   => $token,
				':expires' => $expiry,
			)
		);
	} catch ( \Exception $e ) {
		throw new \Exception( 'Database error: Refresh token not stored in database => ' . $e->getMessage() );
	}
}


/**
 * Undocumented function
 *
 * @return void
 */
function clearExpiredRefreshTokens() {
	global $pdo;
	try {
		$stmt = $pdo->prepare( 'DELETE FROM refresh_tokens WHERE expires < UNIX_TIMESTAMP()' );
		$stmt->execute();
	} catch ( \Exception $e ) {
		throw new \Exception( 'Database error: Refresh tokens not cleared from database => ' . $e->getMessage() );
	}
}


/**
 * @return bool|int ( false if token not exists, user_id if token exists )
 * @param string $token
 */
function isRefreshTokenExists( $token ) {
	global $pdo;
	try {
		$stmt = $pdo->prepare( 'SELECT * FROM refresh_tokens WHERE token = :token' );
		$stmt->execute( array( ':token' => $token ) );
		$result = $stmt->fetch();
		if ( $result ) {
			return (int) $result['user_id'];
		} else {
			return false;
		}
	} catch ( \Exception $e ) {
		throw new \Exception( 'Database error:' . $e->getMessage() );
	}
}


/**
 * @return string
 */
function mySecrectKey() {
	return 'mySecretKey_jfbhfbhfjhuh';
}
