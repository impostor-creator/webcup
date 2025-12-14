<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'serveur14_iastromatch');
define('DB_USER', 'serveur14_serveur14');
define('DB_PASS', 'QPfqcAEwu&q-n@uf');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
  http_response_code(500);
  die('DB connection failed: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
