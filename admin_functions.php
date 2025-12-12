<?php
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/auth.php";

/* ============================================================
   ANNOUNCEMENTS
============================================================ */
function admin_create_announcement($adminId, $title, $message) {
    $sql = "INSERT INTO announcements (admin_id, title, message)
            VALUES (:a, :t, :m)";
    $stmt = db()->prepare($sql);
    $stmt->execute([
        'a' => $adminId,
        't' => $title,
        'm' => $message
    ]);

    admin_log($adminId, "announcement_created", "Created announcement: $title");
}

function admin_get_announcements() {
    $stmt = db()->query("SELECT * FROM announcements ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

/* ============================================================
   FEEDBACK (DB version)
============================================================ */
function admin_get_all_feedback() {
    $sql = "SELECT f.*, u.username
            FROM feedback f
            LEFT JOIN users u ON u.id = f.user_id
            ORDER BY f.created_at DESC";

    return db()->query($sql)->fetchAll();
}

/* ============================================================
   USER MANAGEMENT
============================================================ */
function admin_get_users() {
    return db()->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
}

function admin_set_role($id, $role) {
    $stmt = db()->prepare("UPDATE users SET role = :r WHERE id = :id");
    $stmt->execute(['r' => $role, 'id' => $id]);
}

/* ============================================================
   ADMIN LOGS
============================================================ */
function admin_log($adminId, $action, $details) {
    $sql = "INSERT INTO admin_logs (admin_id, action, details)
            VALUES (:a, :ac, :d)";
    $stmt = db()->prepare($sql);
    $stmt->execute([
        'a'  => $adminId,
        'ac' => $action,
        'd'  => $details
    ]);
}

function admin_get_logs() {
    $sql = "SELECT l.*, u.username
            FROM admin_logs l
            LEFT JOIN users u ON u.id = l.admin_id
            ORDER BY l.created_at DESC";

    return db()->query($sql)->fetchAll();
}
