<?php
// ============================================================
// PROCESS ADMIN ACTIONS
// Handles: announcements, roles, user delete, feedback control
// ============================================================

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/admin_functions.php';
require_once __DIR__ . '/db.php';

auth_require_admin();

$admin = auth_user();
$adminId = $admin['id'];

$action = $_POST['action'] ?? null;


// ============================================================
// 1) CREATE ANNOUNCEMENT
// ============================================================
if ($action === 'create_announcement') {

    $title = trim($_POST['title']);
    $message = trim($_POST['message']);

    if ($title === "" || $message === "") {
        die("<script>alert('Fill all fields!'); history.back();</script>");
    }

    admin_create_announcement($adminId, $title, $message);

    echo "<script>alert('Announcement published!'); window.location='admin.php#announcements';</script>";
    exit;
}



// ============================================================
// 2) DELETE ANNOUNCEMENT
// ============================================================
if ($action === 'delete_announcement') {

    $id = (int)$_POST['id'];

    $stmt = db()->prepare("DELETE FROM announcements WHERE id = :id");
    $stmt->execute(['id' => $id]);

    admin_log($adminId, "announcement_deleted", "Deleted announcement ID $id");

    echo "<script>alert('Announcement deleted!'); window.location='admin.php#announcements';</script>";
    exit;
}



// ============================================================
// 3) CHANGE USER ROLE
// ============================================================
if ($action === 'change_role') {

    $id   = (int)$_POST['id'];
    $role = $_POST['role'];

    admin_set_role($id, $role);
    admin_log($adminId, "change_role", "Changed user #$id role to $role");

    echo "<script>alert('User role updated!'); window.location='admin.php#users';</script>";
    exit;
}



// ============================================================
// 4) DELETE USER
// ============================================================
if ($action === 'delete_user') {

    $id = (int)$_POST['id'];

    // Can't delete yourself
    if ($id === $adminId) {
        die("<script>alert('You cannot delete your own account.'); history.back();</script>");
    }

    $stmt = db()->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);

    admin_log($adminId, "delete_user", "Deleted user ID $id");

    echo "<script>alert('User deleted.'); window.location='admin.php#users';</script>";
    exit;
}



// ============================================================
// 5) DELETE FEEDBACK (optional moderation)
// ============================================================
if ($action === 'delete_feedback') {

    $id = (int)$_POST['id'];

    $stmt = db()->prepare("DELETE FROM feedback WHERE id = :id");
    $stmt->execute(['id' => $id]);

    admin_log($adminId, "delete_feedback", "Deleted feedback ID $id");

    echo "<script>alert('Feedback deleted.'); window.location='admin.php#feedback';</script>";
    exit;
}



// ============================================================
// UNKNOWN ACTION
// ============================================================
echo "Invalid action.";
exit;

