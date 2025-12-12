<?php
// =========================================================
// RESET PASSWORD PAGE
// =========================================================
require_once __DIR__ . '/auth.php';

$error = null;
$success = null;

// Token from URL
$token = trim($_GET['token'] ?? '');

if ($token === '') {
    $error = 'Missing reset token.';
    $user = null;
} else {
    $user = auth_find_by_reset_token($token);
    if (!$user) {
        $error = 'Invalid or expired reset link.';
    }
}

// Handle form submit
if ($user && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');

    if ($password === '' || $password_confirm === '') {
        $error = 'Please fill in both password fields.';
    } elseif ($password !== $password_confirm) {
        $error = 'Passwords do not match.';
    } else {
        // Update password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'UPDATE users
                SET password_hash = :p
                WHERE id = :id';
        $stmt = db()->prepare($sql);
        $stmt->execute([
            'p' => $hash,
            'id' => $user['id'],
        ]);

        // Clear the token
        auth_clear_reset_token((int)$user['id']);

        $success = 'Password updated! You can now log in.';
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="default">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset password • NovaSphere</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <div class="loader" id="loader">
    <div class="loader-orbit"></div>
    <p>Updating your credentials...</p>
  </div>

  <div class="page" id="page">
    <header class="navbar">
      <div class="nav-inner">
        <div class="nav-left">
          <a href="index.php" class="nav-logo">
            <span class="nav-logo-orbit"></span>
            <span class="nav-logo-text">NovaSphere</span>
          </a>
        </div>

        <nav class="nav-links" id="navLinks">
          <a href="index.php#hero" class="nav-link" data-i18n="nav_home">Home</a>
          <a href="index.php#features" class="nav-link" data-i18n="nav_features">Features</a>
          <a href="index.php#gallery" class="nav-link" data-i18n="nav_gallery">Gallery</a>
          <a href="feedback.php" class="nav-link" data-i18n="nav_feedback">Feedback</a>
          <a href="about.html" class="nav-link" data-i18n="nav_about">About</a>
        </nav>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
          <span></span><span></span><span></span>
        </button>
      </div>
    </header>

    <main class="auth-main">
      <section class="auth-wrapper reveal">
        <div class="auth-card">
          <h1 class="auth-title">Reset your password</h1>

          <?php if ($error): ?>
            <div class="auth-alert auth-alert-error">
              <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="auth-alert auth-alert-success">
              <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>

            <p class="auth-footer-note">
              <a href="login.php" class="auth-link">Return to login</a>
            </p>
          <?php elseif ($user): ?>
            <form method="post" class="auth-form">
              <div class="form-group">
                <label for="password">New password</label>
                <input
                  type="password"
                  id="password"
                  name="password"
                  required
                  autocomplete="new-password"
                  placeholder="Enter a new password" />
              </div>

              <div class="form-group">
                <label for="password_confirm">Confirm new password</label>
                <input
                  type="password"
                  id="password_confirm"
                  name="password_confirm"
                  required
                  autocomplete="new-password"
                  placeholder="Repeat the new password" />
              </div>

              <button type="submit" class="btn-primary auth-submit">
                Update password
              </button>
            </form>
          <?php endif; ?>
        </div>
      </section>
    </main>

    <footer class="footer">
      <p>
        © <span id="year"></span>
        <span data-i18n="footer_text">NovaSphere • Built step by step with you.</span>
      </p>
    </footer>
  </div>

  <script src="script.js"></script>
</body>
</html>
