<?php
// ============================================================
// FEEDBACK SUBMISSION (DB)
// Works for logged-in AND guest users
// ============================================================

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

// Read submission
$name    = trim($_POST['name'] ?? "");
$message = trim($_POST['message'] ?? "");
$rating  = intval($_POST['rating'] ?? 5);

// Clean rating
if ($rating < 1) $rating = 1;
if ($rating > 5) $rating = 5;

// Validation
if ($message === "") {
    die("<script>alert('Message cannot be empty!'); history.back();</script>");
}

// Identify user
$user = auth_user();
$userId = $user ? $user['id'] : null;

// Insert into DB
$stmt = db()->prepare("
    INSERT INTO feedback (user_id, rating, message)
    VALUES (:uid, :r, :m)
");

$stmt->execute([
    'uid' => $userId,
    'r'   => $rating,
    'm'   => $message
]);

// Log activity for logged-in users
if ($userId) {
    $stmt = db()->prepare("
        INSERT INTO user_activity (user_id, activity_type, description)
        VALUES (:id, 'feedback', 'Submitted feedback')
    ");
    $stmt->execute(['id' => $userId]);
}

echo "<script>alert('Thank you for your feedback!'); window.location='feedback.php';</script>";
exit;
