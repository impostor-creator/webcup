<?php
// =========================================================
// db.php — Single PDO connection helper (SQLite)
// ---------------------------------------------------------
// This project was mixing MySQL constants (config.php) and
// demo/session auth. To make everything actually persist,
// we use ONE database: SQLite file stored in /database.
// =========================================================

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $dbDir  = __DIR__ . '/database';
    $dbPath = $dbDir . '/iastromatch.db';

    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0755, true);
    }

    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    return $pdo;
}
