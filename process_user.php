<?php
// ============================================================
// PROCESS USER ACTIONS
// Handles: profile update, password change, avatar, preferences
// ============================================================

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
auth_require_login();

$user = auth_user();
$userId = $user['id'];

// Helper: record activity
function log_activity($userId, $type, $description) {
    $stmt = db()->prepare("INSERT INTO user_activity (user_id, activity_type, description) VALUES (:u, :t, :d)");
    $stmt->execute([
        'u' => $userId,
        't' => $type,
        'd' => $description
    ]);
}

$action = $_POST['action'] ?? null;

// ============================================================
// 1) UPDATE PROFILE
// ============================================================
if ($action === 'update_profile') {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $avatar   = trim($_POST['avatar'] ?? $user['avatar']);
    $newPass  = trim($_POST['new_password'] ?? "");

    if ($username === "" || $email === "") {
        die("<script>alert('Invalid input!'); history.back();</script>");
    }

    // Update username, email, avatar
    $stmt = db()->prepare("
        UPDATE users 
        SET username = :u, email = :e, avatar = :a
        WHERE id = :id
    ");

    try {
        $stmt->execute([
            'u' => $username,
            'e' => $email,
            'a' => $avatar,
            'id' => $userId
        ]);
    } catch (Throwable $e) {
        die("<script>alert('Username or email already taken.'); history.back();</script>");
    }

    // Password update (optional)
    if ($newPass !== "") {
        $hash = password_hash($newPass, PASSWORD_DEFAULT);

        $stmt = db()->prepare("UPDATE users SET password_hash = :p WHERE id = :id");
        $stmt->execute([
            'p' => $hash,
            'id' => $userId
        ]);

        log_activity($userId, "password_change", "User updated their password");
    }

    log_activity($userId, "profile_update", "User updated profile info");

    echo "<script>alert('Profile updated successfully!'); window.location='user.php';</script>";
    exit;
}



// ============================================================
// 2) SAVE USER PREFERENCES (theme + language)
// Called via AJAX (fetch())
// ============================================================
if ($action === 'save_preferences') {

    $theme = $_POST['theme'] ?? "default";
    $lang  = $_POST['language'] ?? "en";

    $stmt = db()->prepare("
        UPDATE users
        SET theme = :t, language = :l
        WHERE id = :id
    ");

    $stmt->execute([
        't' => $theme,
        'l' => $lang,
        'id'=> $userId
    ]);

    log_activity($userId, "preferences_update", "Changed preferences: theme=$theme, lang=$lang");

    echo "OK";
    exit;
}



// ============================================================
// Unknown action
// ============================================================
echo "Invalid action.";
exit;
