<?php
// =========================================================
// auth.php — Database-backed auth (SQLite)
// =========================================================
// This project previously had client-side "demo" login and
// session-only registrations. That prevented real persistence
// and broke pages that expected DB rows.
//
// This file makes authentication real:
// - Stores users in SQLite (db.php)
// - Uses PHP session user_id (INTEGER from DB)
// =========================================================

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function auth_check(): bool {
    return isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
}

function auth_require_login(): void {
    if (!auth_check()) {
        header('Location: login.php');
        exit;
    }
}

function auth_require_admin(): void {
    auth_require_login();
    $u = auth_user();
    if (!$u || ($u['role'] ?? 'user') !== 'admin') {
        http_response_code(403);
        echo "<script>alert('Admin only.'); window.location='user.php';</script>";
        exit;
    }
}

function auth_user(): ?array {
    if (!auth_check()) return null;

    $id = (int)$_SESSION['user_id'];
    $stmt = db()->prepare('SELECT id, username, email, species, role, theme, language, avatar, created_at FROM users WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    if (!$row) return null;

    // Keep session copy in sync (useful for navbar labels)
    $_SESSION['username'] = $row['username'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['species'] = $row['species'];
    $_SESSION['theme'] = $row['theme'] ?? 'default';
    $_SESSION['language'] = $row['language'] ?? 'en';
    $_SESSION['avatar'] = $row['avatar'] ?? 'spark';

    return $row;
}

function auth_find_user(string $usernameOrEmail): ?array {
    $value = trim($usernameOrEmail);
    if ($value === '') return null;

    $stmt = db()->prepare('SELECT * FROM users WHERE username = :v OR email = :v LIMIT 1');
    $stmt->execute(['v' => $value]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function auth_register(string $username, string $email, string $password, string $species = 'Grafted', string $role = 'user'): array {
    $username = trim($username);
    $email = trim($email);
    $species = trim($species) ?: 'Grafted';
    $role = ($role === 'admin') ? 'admin' : 'user';

    if ($username === '' || $email === '' || $password === '') {
        throw new RuntimeException('Missing fields.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException('Invalid email.');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = db()->prepare(
        'INSERT INTO users (username, email, password_hash, species, role, theme, language, avatar)
         VALUES (:u, :e, :p, :s, :r, :t, :l, :a)'
    );

    $stmt->execute([
        'u' => $username,
        'e' => $email,
        'p' => $hash,
        's' => $species,
        'r' => $role,
        't' => 'default',
        'l' => 'en',
        'a' => 'spark',
    ]);

    $id = (int)db()->lastInsertId();
    $_SESSION['user_id'] = $id;

    return auth_user() ?? [
        'id' => $id,
        'username' => $username,
        'email' => $email,
        'species' => $species,
        'role' => $role,
        'theme' => 'default',
        'language' => 'en',
        'avatar' => 'spark',
    ];
}

function auth_attempt_login(string $usernameOrEmail, string $password): ?array {
    $row = auth_find_user($usernameOrEmail);
    if (!$row) return null;

    if (!password_verify($password, $row['password_hash'] ?? '')) {
        return null;
    }

    $_SESSION['user_id'] = (int)$row['id'];
    return auth_user();
}

function auth_logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'], $params['secure'], $params['httponly']
        );
    }
    session_destroy();
    session_start();
}
