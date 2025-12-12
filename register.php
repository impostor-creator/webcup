<?php
// =========================================================
// CREATE ACCOUNT PAGE (REGISTER)
// =========================================================
require_once __DIR__ . '/auth.php';

// If already logged in, go to your dashboard directly
if (auth_check()) {
    $u = auth_user();
    if ($u && $u['role'] === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: user.php');
    }
    exit;
}

$error = '';
$username = '';
$email = '';

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');

    // Basic validation
    if ($username === '' || $email === '' || $password === '' || $confirm === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = 'Username must be between 3 and 50 characters.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        // Try to create the user with default role "user"
        $created = auth_register($username, $email, $password, 'user');
        if (!$created) {
            $error = 'That username or email is already taken.';
        } else {
            // Go back to login with a small flag so we can show a success message
            header('Location: login.php?registered=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="default">
<head>
  <!-- =========================================================
       META & GLOBAL ASSETS
       Create account • NovaSphere
  ========================================================== -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create account • NovaSphere</title>

  <link rel="stylesheet" href="styles.css" />
</head>

<body>
  <canvas id="bgParticles" class="bg-particles" aria-hidden="true"></canvas>

  <!-- LOADER -->
  <div class="loader" id="loader">
    <div class="loader-orbit"></div>
    <p>Preparing your new account...</p>
  </div>

  <div class="page-transition" id="pageTransition"></div>

  <div class="page" id="page">
    <!-- NAVBAR -->
    <header class="navbar">
      <div class="navbar-inner">
        <div class="logo" data-i18n="brand_name">NovaSphere</div>

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

          <div class="nav-account">
            <button id="navAccountButton" class="nav-account-btn">
              Login
            </button>

            <div id="navAccountDropdown" class="nav-account-dropdown">
              <div class="nav-account-header">
                <div class="nav-account-avatar">👤</div>
                <div class="nav-account-meta">
                  <div id="navAccountName" class="nav-account-name">Guest</div>
                  <div id="navAccountRole" class="nav-account-role">Not signed in</div>
                </div>
              </div>
              <div class="nav-account-links">
                <a href="login.php" class="nav-account-link">Go to login</a>
              </div>
            </div>
          </div>

          <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
          </button>
        </div>
      </div>
    </header>

    <!-- REGISTER CONTENT (same layout as login) -->
    <main class="login-wrapper">
      <section class="login-card reveal">
        <h1>Create your account</h1>
        <p>Choose a username, email and password to access the NovaSphere dashboards.</p>

        <?php if ($error): ?>
          <div class="form-error login-error">
            <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
          </div>
        <?php endif; ?>

        <form method="post" class="login-form">
          <div class="form-row">
            <label for="regUsername">Username</label>
            <input
              id="regUsername"
              name="username"
              type="text"
              required
              autocomplete="username"
              placeholder="Pick a unique username"
              value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>"
            />
          </div>

          <div class="form-row">
            <label for="regEmail">Email</label>
            <input
              id="regEmail"
              name="email"
              type="email"
              required
              autocomplete="email"
              placeholder="you@example.com"
              value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
            />
          </div>

          <div class="form-row">
            <label for="regPassword">Password</label>
            <input
              id="regPassword"
              name="password"
              type="password"
              required
              autocomplete="new-password"
              placeholder="Create a strong password"
            />
          </div>

          <div class="form-row">
            <label for="regConfirm">Confirm password</label>
            <input
              id="regConfirm"
              name="confirm_password"
              type="password"
              required
              autocomplete="new-password"
              placeholder="Repeat your password"
            />
          </div>

          <div class="login-btn-row">
            <button type="submit" class="btn btn-primary">
              Create account
            </button>
            <a href="login.php" class="btn btn-ghost">
              Back to login
            </a>
          </div>
        </form>
      </section>
    </main>

    <!-- FOOTER -->
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
