<?php
// ======================================================
// DATABASE CONFIG (NovaSphere / IAstroMatch)
// ======================================================

// 🔹 Change ONLY these values from cPanel
define('DB_HOST', 'localhost');               // usually localhost
define('DB_NAME', 'serveur14_novasphere');    // database name
define('DB_USER', 'serveur14_serveur14');        // MySQL username
define('DB_PASS', 'QPfqcAEwu&q-n@uf');        // MySQL password

// ======================================================
// CONNECT
// ======================================================
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_errno) {
    http_response_code(500);
    die('Database connection failed: ' . $mysqli->connect_error);
}

// Charset for emojis + UTF-8
$mysqli->set_charset('utf8mb4');

// ======================================================
// SESSION
// ======================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ======================================================
// AUTH HELPERS
// ======================================================
function auth_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_auth() {
    if (!is_logged_in()) {
        header('Location: /login.php');
        exit;
    }
}
