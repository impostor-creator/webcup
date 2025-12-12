<?php
require_once __DIR__ . '/auth.php';

header('Content-Type: text/plain; charset=utf-8');

$created = [];

if (!auth_find_user('admin')) {
    if (auth_register('admin', 'admin@example.com', 'admin123', 'admin')) {
        $created[] = 'admin';
    }
}

if (!auth_find_user('user')) {
    if (auth_register('user', 'user@example.com', 'user123', 'user')) {
        $created[] = 'user';
    }
}

if (!$created) {
    echo "No new users created. They already exist.\n";
} else {
    echo "Created users: " . implode(', ', $created) . "\n";
}
