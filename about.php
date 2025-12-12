<?php
require_once __DIR__ . '/auth.php';
$currentUser = auth_user();
?>


<!DOCTYPE html>
<html lang="en" data-theme="default">
<head>
  <!-- =========================================================
       META & GLOBAL ASSETS
       About page for NovaSphere
  ========================================================== -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About • NovaSphere</title>

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
    <p>Loading About NovaSphere...</p>
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
          <a href="index.php#hero" class="nav-link" data-i18n="nav_home">Home</a>
          <a href="index.php#features" class="nav-link" data-i18n="nav_features">Features</a>
          <a href="index.php#gallery" class="nav-link" data-i18n="nav_gallery">Gallery</a>
          <a href="feedback.php" class="nav-link" data-i18n="nav_feedback">Feedback</a>
          <a href="about.php" class="nav-link active" data-i18n="nav_about">About</a>
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
         ABOUT PAGE CONTENT
    ========================================================== -->
    <main class="section">
      <div class="section-header">
        <h1>About NovaSphere</h1>
        <p>
          NovaSphere is a completely front-end demo project designed to look
          like a small modern app with multiple pages, themes, language
          support and demo dashboards.
        </p>
      </div>

      <section class="about-story-inner">
        <article class="card reveal">
          <h2>Why this structure?</h2>
          <p>
            The goal is to give you a clean, understandable starting point. Each
            page is its own HTML file, with one main CSS and one main JS file
            holding all the logic and visual effects.
          </p>
        </article>

        <article class="card reveal">
          <h2>How the demo login works</h2>
          <p>
            The login form on the dedicated login page checks the username and
            password against two hard-coded demo accounts. If they match, it
            stores a small “auth” object in localStorage and redirects you to
            either the user or admin dashboard.
          </p>
          <ul class="simple-list">
            <li><code>admin / admin123</code> → Admin dashboard</li>
            <li><code>user / user123</code> → User dashboard</li>
          </ul>
        </article>

        <article class="card reveal">
          <h2>How feedback is shared</h2>
          <p>
            Feedback is stored in the browser’s localStorage using a single key.
            The feedback page can save and filter entries. The user dashboard
            shows feedback where the “name” equals the current username, and
            the admin dashboard shows a summary of everything.
          </p>
        </article>

        <article class="card reveal">
          <h2>What you can customize</h2>
          <p>
            Almost everything is meant to be changed: text, images, icons,
            colors, sections, and even the structure. You can also rip out the
            fake login and connect the UI to a real backend or API.
          </p>
        </article>
      </section>

      <section class="section section-alt">
        <div class="section-header">
          <h2>Technical summary</h2>
          <p>A quick overview of how the pieces are wired together.</p>
        </div>

        <div class="about-story-inner">
          <article class="card reveal">
            <h3>HTML</h3>
            <p>
              Separate HTML files for each page keep the layout clear. The
              navbar is repeated on every page so you can change it once and
              copy it around.
            </p>
          </article>

          <article class="card reveal">
            <h3>CSS</h3>
            <p>
              One main stylesheet, split into sections by comments (layout,
              navbar, hero, cards, forms, dashboards, etc.), so you can jump
              to what you need quickly.
            </p>
          </article>

          <article class="card reveal">
            <h3>JavaScript</h3>
            <p>
              A single script controls the loader, theme &amp; language
              switchers, scroll effects, carousel, feedback storage, login
              logic, and dashboards.
            </p>
          </article>

          <article class="card reveal">
            <h3>Ready for real data</h3>
            <p>
              You can keep the HTML and CSS almost exactly as is, but replace
              the localStorage logic with actual API calls or database queries
              later.
            </p>
          </article>
        </div>
      </section>

      <section class="section">
        <div class="section-header">
          <h2>Next steps for you</h2>
          <p>
            This template is designed as a base for your own ideas. You can use
            it for a portfolio, a mock SaaS app, or a prototype for a bigger
            project.
          </p>
        </div>

        <div class="about-story-inner">
          <article class="card reveal">
            <h3>Step 1 · Adjust content</h3>
            <p>
              Swap all placeholder text and images with your own. Make sure it
              reflects your story, your team or your product.
            </p>
          </article>

          <article class="card reveal">
            <h3>Step 2 · Refine the design</h3>
            <p>
              Tweak spacing, colors and animations to match your personal
              style. You can make it calmer, more energetic or more minimalistic.
            </p>
          </article>

          <article class="card reveal">
            <h3>Step 3 · Connect to a backend</h3>
            <p>
              Turn the demo login into a real authentication system and plug
              the feedback and dashboards into real data sources.
            </p>
          </article>

          <article class="card reveal">
            <h3>Step 4 · Share it</h3>
            <p>
              Host NovaSphere on a static hosting service, share the link, and
              iterate based on what people say.
            </p>
          </article>
        </div>
      </section>

      <section class="section cta-section">
        <div class="cta-inner reveal">
          <h2>Use this as your launchpad</h2>
          <p>
            NovaSphere is not meant to be perfect. It’s meant to be a starting
            point that already feels alive, structured and modern so that you
            can focus on your own ideas instead of boilerplate.
          </p>
          <p>
            Replace, delete and rearrange sections as needed. The goal is to
            end up with something that feels like <strong>your</strong> app,
            not just a template.
          </p>
          <div class="hero-actions">
            <a href="index.php" class="btn btn-primary">Back to home</a>
            <a href="feedback.php" class="btn btn-ghost">Share feedback</a>
          </div>
        </div>
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
            placeholder="Example: What can I explore here?"
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
