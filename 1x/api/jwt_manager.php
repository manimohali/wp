<?php

include_once JWTPBM_PLUGIN_DIR . '/api/jwt/vendor/autoload.php';

use \Firebase\JWT\JWT;

class JWTPBM_JWTManager {

    public static $secret_key = 'my_secret_key';
    public static $token_issuer = 'im_token_issuer';
    public static $token_audience = 'customer';
    public static $refreshTokenExpiryInMinutes = (60 * 60 * 24);    // 24 hours 
    public static $tokenExpiryInSeconds = 60 * 60; // 1 hour



    public function __construct() {
    }

   public static function getInstance(){
        return new JWTPBM_JWTManager();
    }


    function generateSecureToken($length = 64) {
        return bin2hex(random_bytes($length));
    }

    function generateLoginToken(int $user_id) {
        $response_A = ['status' => 0, 'data' => array(), 'message' => ""];

        try {

            $refreshToken = $this->generateSecureToken();
            $refreshTokenExpiry = strtotime(date('Y-m-d H:i:s', (strtotime('now') + self::$refreshTokenExpiryInMinutes))); //Unix timestamp
            //   storeRefreshToken($user_id, $refreshToken, $refreshTokenExpiry_unix);

            $payload = [
                "iss" => self::$token_issuer,
                "aud" => self::$token_audience,
                "iat" => time(), // Issued at
                "exp" => time() + self::$tokenExpiryInSeconds,
                "sub" => $user_id
            ];

            $secret_key =  self::$secret_key;
            $jwt = JWT::encode($payload, $secret_key, 'HS256');
            $token = base64_encode($jwt);

            $response_A = [
                'status' => 1, 
                'token' => $token,
                'refreshToken' => $refreshToken,
            ];
        } catch (\Exception $e) {
            $response_A['message'] = $e->getMessage();
        }

        // update_user_meta($user_id, 'jwt_pbm_login_token', $token);
        return $response_A;
    }
}
