
<?php
require 'vendor/autoload.php';
$db = require_once './db.php';
require_once './token_functionality.php';
$pdo = $db['pdo'];
$status_code = 502;

$response_A = ['status' => 0, 'data' => array(), 'message' => "",];

use \Firebase\JWT\JWT;

try {
    if (!isset($_POST['refresh_token']) || trim($_POST['refresh_token']) == "") {
        $status_code = 400;
        throw new \Exception("Refresh token is required");
    }

    clearExpiredRefreshTokens();

    $refresh_token  =  $_POST['refresh_token'];
    $user_id = isRefreshTokenExists($refresh_token);
    if (!$user_id) {
        throw new \Exception("Refresh token Does not Exists login again.");
    }

    $payload = [
        "iss" => "your_issuer", // Issuer
        "aud" => "your_audience", // Audience
        "iat" => time(), // Issued at
        "exp" => time() + 3600, // Expiration time
        "sub" => $user['id'] // Subject
    ];

    $your_secret_key =  mySecrectKey();
    $jwt = JWT::encode($payload, $your_secret_key, 'HS256');
    $token = base64_encode($jwt);

    $response_A = [
        'status' => 1,
        'data' => ['token' => $token],
        'message' => "Access token generated successfully",
    ];
} catch (\Exception $e) {
    $response_A['message'] = $e->getMessage();
}

header('Content-Type: application/json');
http_response_code($status_code);
echo json_encode($response_A);
