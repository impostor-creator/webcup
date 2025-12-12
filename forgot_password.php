<?php
// =========================================================
// FORGOT PASSWORD PAGE
// (Creates a reset token; in a real app you'd email it)
// =========================================================
require_once __DIR__ . '/auth.php';

$error = null;
$success = null;
$generatedLink = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    if ($username === '') {
        $error = 'Please enter your username.';
    } else {
        $user = auth_find_user($username);
        if (!$user) {
            $error = 'No account found with that username.';
        } else {
            // Generate token and expiry (1 hour from now)
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + 3600);
            auth_set_reset_token((int)$user['id'], $token, $expiresAt);

            // In this demo we just show the reset link directly
            $generatedLink = BASE_URL . '/reset_password.php?token=' . urlencode($token);
            $success = 'Password reset link generated. In a real app this would be emailed.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="default">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot password • NovaSphere</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <div class="loader" id="loader">
    <div class="loader-orbit"></div>
    <p>Checking your account...</p>
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
          <a href="about.php" class="nav-link" data-i18n="nav_about">About</a>
        </nav>

        <div class="nav-actions">
          <select id="langSwitcher" class="nav-select" aria-label="Language switcher">
            <option value="en">EN</option>
            <option value="fr">FR</option>
          </select>

          <select id="themeSwitcher" class="nav-select" aria-label="Theme switcher">
            <option value="default" data-i18n="theme_default">Default</option>
            <option value="neon" data-i18n="theme_neon">Neon</option>
            <option value="cyber" data-i18n="theme_cyber">Cyber</option>
            <option value="sunset" data-i18n="theme_sunset">Sunset</option>
          </select>
        </div>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
          <span></span><span></span><span></span>
        </button>
      </div>
    </header>

    <main class="auth-main">
      <section class="auth-wrapper reveal">
        <div class="auth-card">
          <h1 class="auth-title">Forgot your password?</h1>
          <p class="auth-subtitle">
            Enter your username and we will generate a reset link.
          </p>

          <?php if ($error): ?>
            <div class="auth-alert auth-alert-error">
              <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="auth-alert auth-alert-success">
              <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($generatedLink): ?>
            <div class="auth-alert auth-alert-info">
              Reset link (demo):<br>
              <a href="<?php echo htmlspecialchars($generatedLink, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($generatedLink, ENT_QUOTES, 'UTF-8'); ?>
              </a>
            </div>
          <?php endif; ?>

          <form method="post" class="auth-form">
            <div class="form-group">
              <label for="username">Username</label>
              <input
                type="text"
                id="username"
                name="username"
                required
                autocomplete="username"
                placeholder="Your account username" />
            </div>

            <button type="submit" class="btn-primary auth-submit">
              Generate reset link
            </button>
          </form>

          <p class="auth-footer-note">
            Already remember it?
            <a href="login.php" class="auth-link">Back to login</a>
          </p>
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
