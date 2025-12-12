<?php
// ============================================================
// AUTH SYSTEM (LOGIN, REGISTER, PROFILE, ROLES)
// ============================================================
require_once __DIR__ . '/db.php';
session_start();

/* ------------------------------------------------------------
   Find user by username OR email
------------------------------------------------------------ */
function auth_find_user(string $identifier) {
    $sql = "SELECT * FROM users 
            WHERE username = :username OR email = :email
            LIMIT 1";

    $stmt = db()->prepare($sql);
    $stmt->execute([
        'username' => $identifier,
        'email'    => $identifier
    ]);

    $user = $stmt->fetch();
    return $user ?: null;
}

/* ------------------------------------------------------------
   Find user by id
------------------------------------------------------------ */
function auth_find_user_by_id(int $id) {
    $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $stmt = db()->prepare($sql);
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch();
    return $user ?: null;
}

/* ------------------------------------------------------------
   Register User
------------------------------------------------------------ */
function auth_register(string $username, string $email, string $password, string $role = "user"): bool {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password_hash, role)
            VALUES (:u, :e, :p, :r)";
    $stmt = db()->prepare($sql);

    try {
        $stmt->execute([
            'u' => $username,
            'e' => $email,
            'p' => $hash,
            'r' => $role
        ]);
    } catch (Throwable $e) {
        // username or email already exists or other DB error
        return false;
    }

    return true;
}

/* ------------------------------------------------------------
   Login
------------------------------------------------------------ */
function auth_attempt_login(string $identifier, string $password): ?array {

    $user = auth_find_user($identifier);
    if (!$user) return null;

    if (!password_verify($password, $user['password_hash'])) {
        return null;
    }

    $_SESSION['user_id'] = $user['id'];

    auth_log_activity($user['id'], "login", "User logged in");

    return $user;
}

/* ------------------------------------------------------------
   Logout
------------------------------------------------------------ */
function auth_logout(): void {
    if (isset($_SESSION['user_id'])) {
        auth_log_activity($_SESSION['user_id'], "logout", "User logged out");
    }

    $_SESSION = [];
    if (session_id() !== "") {
        session_destroy();
    }
}

/* ------------------------------------------------------------
   Current user helpers
------------------------------------------------------------ */
function auth_user(): ?array {
    if (!isset($_SESSION['user_id'])) return null;
    return auth_find_user_by_id((int)$_SESSION['user_id']);
}

function auth_check(): bool {
    return isset($_SESSION['user_id']);
}

function auth_require_login(): void {
    if (!auth_check()) {
        header("Location: login.php");
        exit;
    }
}

function auth_is_admin(): bool {
    $u = auth_user();
    return $u && $u['role'] === 'admin';
}

function auth_require_admin(): void {
    if (!auth_is_admin()) {
        header("Location: login.php");
        exit;
    }
}

/* ------------------------------------------------------------
   Profile updates
------------------------------------------------------------ */
function auth_update_profile(
    int $id,
    string $username,
    string $email,
    string $avatar,
    string $badge = null
): void {

    if ($badge === null) {
        $sql = "UPDATE users
                SET username = :u, email = :e, avatar = :a
                WHERE id = :id";
        $params = [
            'u'  => $username,
            'e'  => $email,
            'a'  => $avatar,
            'id' => $id
        ];
    } else {
        $sql = "UPDATE users
                SET username = :u, email = :e, avatar = :a, badge = :b
                WHERE id = :id";
        $params = [
            'u'  => $username,
            'e'  => $email,
            'a'  => $avatar,
            'b'  => $badge,
            'id' => $id
        ];
    }

    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    auth_log_activity($id, "profile_update", "User updated profile");
}

/* ------------------------------------------------------------
   Password change
------------------------------------------------------------ */
function auth_change_password(int $id, string $newPassword): void {
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = db()->prepare("UPDATE users SET password_hash = :p WHERE id = :id");
    $stmt->execute([
        'p'  => $hash,
        'id' => $id
    ]);

    auth_log_activity($id, "password_change", "User changed password");
}

/* ------------------------------------------------------------
   Activity logging
------------------------------------------------------------ */
function auth_log_activity(int $userId, string $type, string $description): void {
    if (!$userId) return;

    $stmt = db()->prepare("
        INSERT INTO user_activity (user_id, activity_type, description)
        VALUES (:id, :t, :d)
    ");
    $stmt->execute([
        'id' => $userId,
        't'  => $type,
        'd'  => $description
    ]);
}
