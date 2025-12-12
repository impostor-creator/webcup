<?php
// ============================================================
// DATABASE CONNECTION (PDO)
// ============================================================

$DB_HOST = "localhost";
$DB_NAME = "novasphere";
$DB_USER = "root";
$DB_PASS = "";

// Optional: enable errors in dev
$DEV_MODE = true;

function db(): PDO {
    global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS, $DEV_MODE;

    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE            => $DEV_MODE ? PDO::ERRMODE_EXCEPTION : PDO::ERRMODE_SILENT,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
    return $pdo;
}
