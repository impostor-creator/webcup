<?php
require_once __DIR__ . '/auth.php';
$currentUser = auth_user();
?>


<!DOCTYPE html>
<html lang="en" data-theme="default">
<head>
  <!-- =========================================================
       META & GLOBAL ASSETS
       Feedback page for NovaSphere
  ========================================================== -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Feedback • NovaSphere</title>

  <!-- Main stylesheet -->
  <link rel="stylesheet" href="styles.css" />
</head>

<body>
  <canvas id="bgParticles" class="bg-particles" aria-hidden="true"></canvas>

  <!-- =========================================================
       GLOBAL LOADER (SAME AS HOME)
  ========================================================== -->
  <div class="loader" id="loader">
    <div class="loader-orbit"></div>
    <p>Loading feedback module...</p>
  </div>

  <!-- Global page transition overlay -->
  <div class="page-transition" id="pageTransition"></div>

  <!-- =========================================================
       PAGE WRAPPER (HIDDEN UNTIL LOADER DISAPPEARS)
  ========================================================== -->
  <div class="page" id="page">
    <!-- =====================================================
         NAVBAR (SAME AS HOME)
    ====================================================== -->
    <header class="navbar">
      <div class="navbar-inner">
        <div class="logo" data-i18n="brand_name">NovaSphere</div>

        <nav class="nav-links" id="navLinks">
          <a href="index.php" class="nav-link" data-i18n="nav_home">Home</a>
          <a href="index.php" class="nav-link" data-i18n="nav_features">Features</a>
          <a href="index.php" class="nav-link" data-i18n="nav_gallery">Gallery</a>
          <a href="feedback.php" class="nav-link active" data-i18n="nav_feedback">Feedback</a>
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

          <!-- Account dropdown -->
          <div class="nav-account">
  <button id="navAccountButton" class="nav-account-btn">
    <?php if ($currentUser): ?>
      <?= htmlspecialchars($currentUser['username']) ?>
    <?php else: ?>
      Login
    <?php endif; ?>
  </button>

  <div id="navAccountDropdown" class="nav-account-dropdown">
    <div class="nav-account-header">
      <div class="nav-account-avatar">
        👤
      </div>
      <div class="nav-account-meta">
        <div id="navAccountName" class="nav-account-name">
          <?php if ($currentUser): ?>
            <?= htmlspecialchars($currentUser['username']) ?>
          <?php else: ?>
            Guest
          <?php endif; ?>
        </div>
        <div id="navAccountRole" class="nav-account-role">
          <?php if ($currentUser): ?>
            <?= $currentUser['role'] === 'admin' ? 'Admin' : 'User' ?>
          <?php else: ?>
            Not signed in
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="nav-account-links">
      <?php if ($currentUser): ?>
        <?php if ($currentUser['role'] === 'admin'): ?>
          <a href="admin.php" class="nav-account-link">
            Admin dashboard
          </a>
        <?php else: ?>
          <a href="user.php" class="nav-account-link">
            User dashboard
          </a>
        <?php endif; ?>
      <?php else: ?>
        <a href="login.php" class="nav-account-link">
          Login
        </a>
        <a href="register.php" class="nav-account-link">
          Create account
        </a>
      <?php endif; ?>
    </div>

    <?php if ($currentUser): ?>
      <button class="nav-account-link nav-account-logout"
              onclick="window.location.href='logout.php'; return false;">
        Logout
      </button>
    <?php endif; ?>
  </div>
</div>


          <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
          </button>
        </div>
      </div>
    </header>

    <!-- =====================================================
         FEEDBACK PAGE CONTENT
    ========================================================== -->
    <main class="section">
      <div class="section-header">
        <h1>Share your feedback</h1>
        <p>
          This page stores feedback in your browser’s localStorage and shows
          it in a nice, filterable list. The dashboards reuse the same data.
        </p>
      </div>

      <section class="feedback-layout">
        <!-- LEFT: FORM -->
        <article class="card feedback-form-card reveal">
          <h2>Leave a comment</h2>
          <p>Tell us how NovaSphere feels to use, what you like, and what you’d improve.</p>

          <form id="feedbackForm" class="feedback-form" action="feedback2.php" method="POST">
            <div class="form-row">
              <label for="feedbackName">Name (optional)</label>
              <input
                id="feedbackName"
                name="name"
                type="text"
                placeholder="Your nickname or name"
              />
            </div>

            <div class="form-row">
              <label for="feedbackRating">Overall experience</label>
              <select id="feedbackRating" name="rating">
                <option value="5">★★★★★ · Amazing</option>
                <option value="4">★★★★☆ · Good</option>
                <option value="3">★★★☆☆ · Okay</option>
                <option value="2">★★☆☆☆ · Needs work</option>
                <option value="1">★☆☆☆☆ · Not great</option>
              </select>
            </div>

            <div class="form-row">
              <label for="feedbackMessage">Message</label>
              <textarea
                id="feedbackMessage"
                name="message"
                rows="4"
                placeholder="Write your thoughts here..."
                required
              ></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
              Submit feedback
            </button>
          </form>
        </article>

        <!-- RIGHT: LIST -->
        <article class="card feedback-list-card reveal">
          <div class="feedback-list-header">
            <h2>What people have said</h2>
            <div class="feedback-filters">
              <button
                type="button"
                class="chip chip-active"
                data-feedback-filter="all"
              >
                All
              </button>
              <button
                type="button"
                class="chip"
                data-feedback-filter="positive"
              >
                Positive
              </button>
              <button
                type="button"
                class="chip"
                data-feedback-filter="neutral"
              >
                Neutral
              </button>
              <button
                type="button"
                class="chip"
                data-feedback-filter="negative"
              >
                Negative
              </button>
            </div>
          </div>

          <div id="feedbackList" class="feedback-list">
            <!-- Filled by JS -->
          </div>
        </article>
      </section>
    </main>

    <!-- =====================================================
         GLOBAL ASSISTANT & COMMAND PALETTE
    ========================================================== -->
    <button class="assistant-toggle" id="assistantToggle" aria-label="Open Nova assistant">
      ✦
    </button>

    <div class="assistant-panel" id="assistantPanel" aria-hidden="true">
      <div class="assistant-header">
        <div class="assistant-title">Nova helper</div>
        <button class="assistant-close" id="assistantClose" aria-label="Close assistant">×</button>
      </div>
      <div class="assistant-body">
        <div class="assistant-messages" id="assistantMessages">
          <div class="assistant-message assistant-message-bot">
            <p>Hi! I’m your NovaSphere helper. Ask what you can do on this page.</p>
          </div>
        </div>
        <form class="assistant-form" id="assistantForm">
          <input
            type="text"
            id="assistantInput"
            placeholder="Example: How is feedback stored?"
            autocomplete="off"
          />
          <button type="submit">Send</button>
        </form>
      </div>
    </div>

    <div class="command-palette" id="commandPalette" aria-hidden="true">
      <div class="command-panel" role="dialog" aria-modal="true" aria-labelledby="commandTitle">
        <div class="command-header">
          <span id="commandTitle">Quick command palette</span>
          <span class="command-hint">Ctrl + K</span>
        </div>
        <div class="command-input-wrapper">
          <input
            type="text"
            id="commandInput"
            placeholder="Type a command, page, or theme..."
            autocomplete="off"
          />
        </div>
        <ul class="command-list" id="commandList" role="listbox"></ul>
      </div>
    </div>

    <!-- =====================================================
         GLOBAL FOOTER
    ====================================================== -->
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
