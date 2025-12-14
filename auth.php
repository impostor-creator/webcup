<?php
// auth.php - Simple authentication for IAstroMatch
session_start();

function auth_require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function auth_user() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    // Return user data from session
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? 'Guest',
        'email' => $_SESSION['email'] ?? '',
        'species' => $_SESSION['species'] ?? 'Grafted',
        'role' => $_SESSION['role'] ?? 'user',
        'theme' => $_SESSION['theme'] ?? 'default',
        'language' => $_SESSION['language'] ?? 'en',
        'avatar' => $_SESSION['avatar'] ?? 'spark'
    ];
}

function auth_check() {
    return isset($_SESSION['user_id']);
}

function auth_register($username, $email, $password, $role = 'user') {
    // Simple session-based registration
    $user_id = uniqid();
    
    // Store in session
    $_SESSION['demo_users'][$username] = [
        'id' => $user_id,
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'role' => $role,
        'theme' => 'default',
        'language' => 'en',
        'avatar' => 'spark',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    return true;
}

function auth_attempt_login($username, $password) {
    // Check demo users
    $demo_users = [
        'xenomorph_researcher' => 'bio123',
        'sun_seeker' => 'chloro123',
        'gel_wanderer' => 'amoeba123',
        'admin' => 'admin123',
        'user' => 'user123'
    ];
    
    if (isset($demo_users[$username]) && $demo_users[$username] === $password) {
        $user_id = uniqid();
        
        // Store in session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $username . '@example.com';
        $_SESSION['role'] = ($username === 'admin') ? 'admin' : 'user';
        $_SESSION['species'] = 'Grafted';
        $_SESSION['theme'] = 'default';
        $_SESSION['language'] = 'en';
        $_SESSION['avatar'] = 'spark';
        
        return [
            'id' => $user_id,
            'username' => $username,
            'email' => $username . '@example.com',
            'role' => ($username === 'admin') ? 'admin' : 'user',
            'theme' => 'default',
            'language' => 'en',
            'avatar' => 'spark'
        ];
    }
    
    return false;
}

function auth_logout() {
    session_destroy();
    session_start();
}
?>