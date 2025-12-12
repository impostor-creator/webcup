<?php
// =========================================================
// NOVASPHERE BACKEND CONFIG
// =========================================================

const DB_HOST = 'localhost';
const DB_NAME = 'novasphere';
const DB_USER = 'root';
const DB_PASS = '';  // change if needed

// Base URL of your project (no trailing slash)
// Example: 'http://localhost/novasphere'
const BASE_URL = 'http://localhost/novasphere';

// Start session for all backend pages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
