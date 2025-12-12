<?php
// =========================================================
// LOGOUT ENDPOINT
// =========================================================
require_once __DIR__ . '/auth.php';

auth_logout();

// After logout, back to login page
header('Location: login.php');
exit;
