<?php
const DB_HOST = 'localhost';
const DB_NAME = 'serveur14_novasphere';
const DB_USER = 'serveur14_admin';     // <- from cPanel (usually prefixed)
const DB_PASS = 'QPfqcAEwu&q-n@uf';       // <- from cPanel

const BASE_URL = 'https://serveur14.webcup.hodi.cloud';

if (session_status() === PHP_SESSION_NONE) session_start();

try {
  $pdo = new PDO(
    "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]
  );
} catch (Throwable $e) {
  http_response_code(500);
  echo "DB connection failed.";
  exit;
}