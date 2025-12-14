<?php
// =========================================================
// seed.php — Initialize SQLite schema + seed demo users
// =========================================================
// Run once:
//   - Open in browser: http://.../seed.php
//   - or CLI: php seed.php
// =========================================================

require_once __DIR__ . '/db.php';

header('Content-Type: text/plain; charset=utf-8');

$pdo = db();

// --- Schema (idempotent) ---
$pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT NOT NULL UNIQUE,
  email TEXT NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  species TEXT NOT NULL DEFAULT 'Grafted',
  role TEXT NOT NULL DEFAULT 'user',
  theme TEXT NOT NULL DEFAULT 'default',
  language TEXT NOT NULL DEFAULT 'en',
  avatar TEXT NOT NULL DEFAULT 'spark',
  created_at TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS feedback (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NULL,
  rating INTEGER NULL,
  message TEXT NOT NULL,
  created_at TEXT NOT NULL DEFAULT (datetime('now')),
  FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS announcements (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  admin_id INTEGER NOT NULL,
  title TEXT NOT NULL,
  message TEXT NOT NULL,
  created_at TEXT NOT NULL DEFAULT (datetime('now')),
  FOREIGN KEY(admin_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS admin_logs (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  admin_id INTEGER NOT NULL,
  action TEXT NOT NULL,
  details TEXT NOT NULL,
  created_at TEXT NOT NULL DEFAULT (datetime('now')),
  FOREIGN KEY(admin_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_activity (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  activity_type TEXT NOT NULL,
  description TEXT NOT NULL,
  created_at TEXT NOT NULL DEFAULT (datetime('now')),
  FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);
SQL);

// Helpful indexes
$pdo->exec("CREATE INDEX IF NOT EXISTS idx_feedback_user_id ON feedback(user_id)");
$pdo->exec("CREATE INDEX IF NOT EXISTS idx_user_activity_user_id ON user_activity(user_id)");
$pdo->exec("CREATE INDEX IF NOT EXISTS idx_admin_logs_admin_id ON admin_logs(admin_id)");

// --- Seed demo accounts (idempotent) ---
require_once __DIR__ . '/auth.php';

$seed = [
  ['username' => 'admin', 'email' => 'admin@example.com', 'password' => 'admin123', 'species' => 'Grafted', 'role' => 'admin'],
  ['username' => 'user',  'email' => 'user@example.com',  'password' => 'user123',  'species' => 'Grafted', 'role' => 'user'],
  ['username' => 'xenomorph_researcher', 'email' => 'bio@example.com',    'password' => 'bio123',    'species' => 'Grafted',         'role' => 'user'],
  ['username' => 'sun_seeker',           'email' => 'chloro@example.com', 'password' => 'chloro123', 'species' => 'Chloro-Humanoid', 'role' => 'user'],
  ['username' => 'gel_wanderer',         'email' => 'amoeba@example.com', 'password' => 'amoeba123', 'species' => 'Gelatinous',      'role' => 'user'],
];

$created = [];
foreach ($seed as $u) {
  if (!auth_find_user($u['username'])) {
    auth_register($u['username'], $u['email'], $u['password'], $u['species'], $u['role']);
    auth_logout(); // don’t keep the session logged in after seeding
    $created[] = $u['username'];
  }
}

echo "✅ DB ready.\n";
if ($created) {
  echo "Created users: " . implode(', ', $created) . "\n";
} else {
  echo "No new users created (already exist).\n";
}

echo "\nLogin accounts:\n";
echo "- admin / admin123\n";
echo "- user / user123\n";
echo "- xenomorph_researcher / bio123\n";
echo "- sun_seeker / chloro123\n";
echo "- gel_wanderer / amoeba123\n";