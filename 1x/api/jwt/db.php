<?php

$host    = '127.0.0.1';
$db      = 'test_localhost';
$user    = 'root';
$pass    = 'password';
$charset = 'utf8mb4';

$dsn     = "mysql:host=$host;dbname=$db;charset=$charset";
$options = array(
	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES   => false,
);

$connection = array(
	'status'  => false,
	'pdo'     => '',
	'message' => '',
);
try {
	$pdo                  = new PDO( $dsn, $user, $pass, $options );
	$connection['status'] = true;
	$connection['pdo']    = $pdo;
	$_GLOBALS['pdo']      = $pdo;
} catch ( PDOException $e ) {
	$connection['message'] = $e->getMessage();
}

return $connection;
