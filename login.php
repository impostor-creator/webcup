<?php
// =========================================================
// LOGIN PAGE (BACKEND VERSION)
// =========================================================
require_once __DIR__ . '/auth.php';

// If already logged in, go to appropriate dashboard
if (auth_check()) {
    $u = auth_user();
    if ($u && $u['role'] === 'admin') {
        header("Location: admin.php");

    } else {
       header("Location: user.php");

    }
    exit;
}

$error = '';
$username = '';

// Handle POST login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please fill in both fields.';
    } else {
        // username OR email works here
        $user = auth_attempt_login($username, $password);
        if (!$user) {
            $error = 'Invalid username, email, or password.';
        } else {
            // Redirect to correct dashboard
            if ($user['role'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: user.php');
            }
            exit;
        }
    }
}

// Was the user just registered?
$justRegistered = isset($_GET['registered']) && $_GET['registered'] === '1';
?>
<!DOCTYPE html>
<html lang="en" data-theme="default">
<head>
  <!-- =========================================================
       META & GLOBAL ASSETS
       Login • NovaSphere
  ========================================================== -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login • NovaSphere</title>

  <link rel="stylesheet" href="styles.css" />
</head>

<body>
  <canvas id="bgParticles" class="bg-particles" aria-hidden="true"></canvas>

  <!-- LOADER -->
  <div class="loader" id="loader">
    <div class="loader-orbit"></div>
    <p>Preparing sign in...</p>
  </div>

  <!-- PAGE TRANSITION -->
  <div class="page-transition" id="pageTransition"></div>

  <!-- PAGE WRAPPER -->
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

          <!-- Simple account button (guest) -->
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
                <a href="register.php" class="nav-account-link">Create account</a>
              </div>
            </div>
          </div>

          <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
          </button>
        </div>
      </div>
    </header>

    <!-- LOGIN CONTENT: uses your .login-wrapper / .login-card layout -->
    <main class="login-wrapper">
      <section class="login-card reveal">
        <h1>Welcome back</h1>
        <p>Sign in to access your NovaSphere dashboards.</p>

        <?php if ($justRegistered): ?>
          <div class="form-success login-success">
            Account created! You can log in now.
          </div>
        <?php endif; ?>

        <div id="loginError" class="form-error login-error">
          <?php if ($error): ?>
            <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
          <?php endif; ?>
        </div>

        <form method="post" id="loginForm" class="login-form">
          <div class="form-row">
            <label for="username">Username or email</label>
            <input
              type="text"
              id="username"
              name="username"
              autocomplete="username"
              required
              value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>"
              placeholder="admin, user, or your email"
            />
          </div>

          <div class="form-row">
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              autocomplete="current-password"
              required
              placeholder="Your password"
            />
          </div>

          <div class="login-extra-row">
            <a href="forgot_password.php" class="auth-link">Forgot password?</a>
            <a href="register.php" class="auth-link">Create account</a>
          </div>

          <div class="login-btn-row">
            <button type="submit" class="btn btn-primary">Sign in</button>
          </div>
        </form>

        <div class="login-test-accounts">
          <strong>Demo accounts</strong>
          <ul>
            <li><code>admin / admin123</code> → Admin dashboard</li>
            <li><code>user / user123</code> → User dashboard</li>
          </ul>
          <p class="text-muted">
            These live in your MySQL database and are checked by PHP.
          </p>
        </div>
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