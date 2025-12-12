<?php
require_once __DIR__ . '/auth.php';
$currentUser = auth_user();
?>


<!DOCTYPE html>
<html lang="en" data-theme="default">

<head>
  <!-- =========================================================
       META & GLOBAL ASSETS
       Home page for NovaSphere
  ========================================================== -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NovaSphere • Multi-page neon UI</title>

  <!-- Main stylesheet -->
  <link rel="stylesheet" href="styles.css" />
</head>

<body>
  <canvas id="bgParticles" class="bg-particles" aria-hidden="true"></canvas>
  <!-- =========================================================
       GLOBAL LOADER
  ========================================================== -->
  <div class="loader" id="loader">
    <div class="loader-orbit"></div>
    <p>Booting NovaSphere...</p>
  </div>

  <!-- Global page transition overlay -->
  <div class="page-transition" id="pageTransition"></div>

  <!-- =========================================================
       PAGE WRAPPER (ALL CONTENT)
  ========================================================== -->
  <div class="page" id="page">

    <!-- =====================================================
         GLOBAL NAVBAR
    ====================================================== -->
    <header class="navbar">
      <div class="navbar-inner">
        <!-- Brand / logo -->
        <div class="logo" data-i18n="brand_name">NovaSphere</div>

        <!-- MAIN NAV LINKS -->
        <nav class="nav-links" id="navLinks">
          <a href="#hero" class="nav-link active" data-i18n="nav_home">Home</a>
          <a href="#features" class="nav-link" data-i18n="nav_features">Features</a>
          <a href="#gallery" class="nav-link" data-i18n="nav_gallery">Gallery</a>
          <a href="feedback.php" class="nav-link" data-i18n="nav_feedback">Feedback</a>
          <a href="about.php" class="nav-link" data-i18n="nav_about">About</a>
        </nav>

        <!-- RIGHT SIDE CONTROLS -->
        <div class="nav-actions">
          <!-- Language -->
          <select id="langSwitcher" class="nav-select" aria-label="Language switcher">
            <option value="en">EN</option>
            <option value="fr">FR</option>
          </select>

          <!-- Theme -->
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


          <!-- Mobile hamburger -->
          <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
          </button>
        </div>
      </div>
    </header>

    <!-- =====================================================
         HERO SECTION
         - "Multi-page neon UI" intro
    ========================================================== -->
    <div class="hero-outer">
      <section id="hero" class="hero">
        <!-- Background parallax orbs -->
        <div class="hero-orb" data-depth="0.5"></div>
        <div class="hero-orb" data-depth="0.9"></div>

        <div class="hero-grid">
          <!-- HERO LEFT -->
          <div class="hero-content reveal">
            <p class="hero-kicker" data-i18n="hero_kicker">
              Welcome to the future hhhhh
            </p>

            <h1 class="hero-title">
              <span data-i18n="hero_title_line1">A futuristic, animated</span><br />
              <span class="hero-highlight">
                Multi-page neon UI
              </span>
            </h1>

            <p class="hero-subtitle" data-i18n="hero_subtitle">
              Neon glow, 3D effects, theme switching, and interactive dashboards —
              everything wired in a way that’s easy to edit and expand later.
            </p>

            <div class="hero-actions">
              <a href="#features" class="btn btn-primary" data-i18n="btn_explore_features">
                Explore features
              </a>
              <a href="login.php" class="btn btn-ghost" data-i18n="btn_login_now">
                Login &amp; try dashboards
              </a>
            </div>

            <!-- SMALL PILL LIST UNDER BUTTONS -->
            <div class="hero-footnote">
              <span>• Multi-page structure</span> ·
              <span>• Animated UI</span> ·
              <span>• Demo login &amp; dashboards</span>
            </div>

            <!-- Stats row -->
            <div class="stats-row">
              <div class="stat">
                <div class="stat-label">Pages</div>
                <div class="stat-value" data-stat-target="6">0</div>
              </div>
              <div class="stat">
                <div class="stat-label">Themes</div>
                <div class="stat-value" data-stat-target="4">0</div>
              </div>
              <div class="stat">
                <div class="stat-label">Demo dashboards</div>
                <div class="stat-value" data-stat-target="2">0</div>
              </div>
            </div>
          </div>

          <!-- HERO RIGHT: VISUAL PANEL WITH PLANET + PREVIEW -->
          <div class="hero-visual-panel reveal hover-tilt">
            <!-- 3D-ish neon planet -->
            <div class="hero-planet-3d">
              <div class="planet-glow"></div>
              <div class="planet-core"></div>
              <div class="planet-ring planet-ring-outer"></div>
              <div class="planet-ring planet-ring-inner"></div>
              <div class="planet-satellite planet-satellite-1"></div>
              <div class="planet-satellite planet-satellite-2"></div>
            </div>

            <div class="hero-visual-top">
              <span class="hero-pill">Live preview</span>
              <span class="hero-pill">Front-end only</span>
            </div>

            <div class="hero-visual-body">
              <p>
                This card represents the whole project: multiple pages, animated
                layout, and a small fake auth system — all running only in your browser.
              </p>

              <!-- Animated mini “dashboard” inside the blank square -->
              <div class="hero-preview-window">
                <div class="hero-preview-header">
                  <span class="hero-preview-dots">
                    <span></span><span></span><span></span>
                  </span>
                  <span class="hero-preview-title">NovaSphere demo</span>
                </div>

                <div class="hero-preview-body">
                  <div class="hero-preview-sidebar">
                    <div class="hero-preview-pill hero-preview-pill-active">
                      Home
                    </div>
                    <div class="hero-preview-pill">User</div>
                    <div class="hero-preview-pill">Admin</div>
                  </div>

                  <div class="hero-preview-main">
                    <div class="hero-preview-bar hero-preview-bar-lg"></div>
                    <div class="hero-preview-bar hero-preview-bar-md"></div>

                    <div class="hero-preview-row">
                      <div class="hero-preview-chip">Pages: 6</div>
                      <div class="hero-preview-chip">Themes: 4</div>
                      <div class="hero-preview-chip">Dashboards: 2</div>
                    </div>

                    <div class="hero-preview-sparkline">
                      <span class="spark spark-1"></span>
                      <span class="spark spark-2"></span>
                      <span class="spark spark-3"></span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Small stacked info cards under the window -->
              <div class="hero-preview-meta">
                <div class="hero-preview-meta-item">
                  <strong>🌐 Pages:</strong> Home, About, Feedback, Login, User, Admin
                </div>
                <div class="hero-preview-meta-item">
                  <strong>🎨 Themes:</strong> Default, Neon, Cyber, Sunset
                </div>
                <div class="hero-preview-meta-item">
                  <strong>👤 Demo accounts:</strong>
                  <code>admin/admin123</code>, <code>user/user123</code>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>
    </div>

    <!-- =====================================================
         QUICK HIGHLIGHTS STRIP
         - Small section under hero
    ========================================================== -->
    <section class="section section-alt">
      <div class="section-header">
        <h2>Quick highlights</h2>
        <p>
          A fast overview of what’s already included before you even start
          writing your own backend or hooking this to a database.
        </p>
      </div>

      <div class="about-story-inner">
        <article class="card reveal">
          <h3>Interactive navbar</h3>
          <p>
            Links for Home, Features and Gallery scroll on this page, while
            Feedback and About open separate pages. The login button turns
            into an account dropdown when you sign in.
          </p>
        </article>

        <article class="card reveal">
          <h3>Theme &amp; language switch</h3>
          <p>
            Four themes and two languages (English/French) are wired in with
            a simple dictionary in JavaScript, ready to be extended.
          </p>
        </article>

        <article class="card reveal">
          <h3>Local feedback storage</h3>
          <p>
            The feedback page saves comments in localStorage. The user and
            admin dashboards read from the same data.
          </p>
        </article>

        <article class="card reveal">
          <h3>Clean file separation</h3>
          <p>
            Each page has its own HTML file. The main logic is in one
            script.js and styling in one styles.css, both organized by sections.
          </p>
        </article>
      </div>
    </section>

    <!-- =====================================================
         FEATURES SECTION
    ========================================================== -->
    <section id="features" class="section">
      <div class="section-header">
        <h2 data-i18n="features_title">Core features</h2>
        <p data-i18n="features_subtitle">
          Every block is clearly separated and commented, so you can jump in
          and modify anything without getting lost.
        </p>
      </div>

      <div class="features-grid">
        <!-- Feature 1 -->
        <article class="card reveal hover-tilt">
          <div class="card-icon">✨</div>
          <h3 data-i18n="feature1_title">Futuristic animations</h3>
          <p data-i18n="feature1_text">
            Parallax, hover tilt, reveals on scroll, glowing elements — all
            controlled by small, readable JavaScript.
          </p>
        </article>

        <!-- Feature 2 -->
        <article class="card reveal hover-tilt">
          <div class="card-icon">🗺️</div>
          <h3 data-i18n="feature2_title">Multi-page structure</h3>
          <p data-i18n="feature2_text">
            Separate files for Home, About, Feedback, Login, User, Admin —
            easy to open and edit individually.
          </p>
        </article>

        <!-- Feature 3 -->
        <article class="card reveal hover-tilt">
          <div class="card-icon">🧱</div>
          <h3 data-i18n="feature3_title">Clean organization</h3>
          <p data-i18n="feature3_text">
            Sections are labeled with comments in HTML, CSS, and JS so even a
            new person can understand the structure quickly.
          </p>
        </article>

        <!-- Extra feature 4 -->
        <article class="card reveal hover-tilt">
          <div class="card-icon">📊</div>
          <h3>Dashboard-ready</h3>
          <p>
            Simple user and admin pages are already built. You can later plug
            them into your own backend and turn them into real dashboards.
          </p>
        </article>

        <!-- Extra feature 5 -->
        <article class="card reveal hover-tilt">
          <div class="card-icon">🧪</div>
          <h3>Safe playground</h3>
          <p>
            Because everything is on the front-end, you can try ideas and
            break stuff without affecting any real server.
          </p>
        </article>

        <!-- Extra feature 6 -->
        <article class="card reveal hover-tilt">
          <div class="card-icon">🔧</div>
          <h3>Easy to modify</h3>
          <p>
            Need another page, section or card? Copy, paste, rename the
            section in comments and adjust the text.
          </p>
        </article>
      </div>
    </section>

    <!-- =====================================================
         GALLERY SECTION (CAROUSEL)
    ========================================================== -->
    <!-- =====================================================
         GALLERY SECTION (INTERACTIVE GRID)
    ========================================================== -->
    <section id="gallery" class="section section-alt">
      <div class="section-header">
        <h2 data-i18n="gallery_title">Interactive gallery</h2>
        <p data-i18n="gallery_subtitle">
          A grid of animated concept cards. Click any card to reveal more details
          about that part of NovaSphere.
        </p>
      </div>

      <div class="gallery-layout reveal">
        <div class="gallery-grid" id="galleryGrid">
          <!-- Card 1 -->
          <article class="gallery-card" data-gallery-id="concept-ui">
            <div class="gallery-card-inner">
              <div class="gallery-thumb">
                <img src="https://images.pexels.com/photos/546819/pexels-photo-546819.jpeg" alt="Neon UI concept" />
              </div>
              <div class="gallery-main">
                <p class="gallery-kicker">Concept · 01</p>
                <h3 data-i18n="gallery_item1_title">Neon dashboard concept</h3>
                <p class="gallery-line">
                  Hover to see the glow, click to reveal what this concept represents.
                </p>
              </div>
              <div class="gallery-extra">
                <p data-i18n="gallery_item1_text">
                  A sample visual of how data or content can be presented in a clean,
                  modern card. Replace this with your own UI concept.
                </p>
                <ul class="gallery-tags">
                  <li>UI Design</li>
                  <li>Animations</li>
                  <li>Neon</li>
                </ul>
              </div>
            </div>
          </article>

          <!-- Card 2 -->
          <article class="gallery-card" data-gallery-id="team">
            <div class="gallery-card-inner">
              <div class="gallery-thumb">
                <img src="https://images.pexels.com/photos/1181675/pexels-photo-1181675.jpeg"
                  alt="Team collaborating" />
              </div>
              <div class="gallery-main">
                <p class="gallery-kicker">Concept · 02</p>
                <h3 data-i18n="gallery_item2_title">Team & collaboration</h3>
                <p class="gallery-line">
                  A card to showcase the people behind the project or competition entry.
                </p>
              </div>
              <div class="gallery-extra">
                <p data-i18n="gallery_item2_text">
                  Use this card to highlight a team, a community, or a group of
                  contributors. The image is a placeholder — swap with your own.
                </p>
                <ul class="gallery-tags">
                  <li>Teamwork</li>
                  <li>Process</li>
                  <li>Story</li>
                </ul>
              </div>
            </div>
          </article>

          <!-- Card 3 -->
          <article class="gallery-card" data-gallery-id="abstract">
            <div class="gallery-card-inner">
              <div class="gallery-thumb">
                <img src="https://images.pexels.com/photos/313782/pexels-photo-313782.jpeg"
                  alt="Abstract neon lights" />
              </div>
              <div class="gallery-main">
                <p class="gallery-kicker">Concept · 03</p>
                <h3 data-i18n="gallery_item3_title">Abstract motion</h3>
                <p class="gallery-line">
                  A space to show motion, experiments, or anything visually bold.
                </p>
              </div>
              <div class="gallery-extra">
                <p data-i18n="gallery_item3_text">
                  Swap these placeholder visuals with anything that fits your project:
                  dashboards, logos, or animated concepts.
                </p>
                <ul class="gallery-tags">
                  <li>Motion</li>
                  <li>Experiments</li>
                  <li>Playground</li>
                </ul>
              </div>
            </div>
          </article>
        </div>

        <p class="gallery-hint">
          Tip: click different cards to compare how the content and tags change.
        </p>
      </div>
    </section>


    <!-- =====================================================
         WORKFLOW / STEPS SECTION
    ========================================================== -->
    <section class="section">
      <div class="section-header">
        <h2>How you can grow this project</h2>
        <p>
          Here is one possible roadmap. You can ignore it, change it, or use it
          as inspiration for your own plan.
        </p>
      </div>

      <div class="about-story-inner">
        <article class="card reveal">
          <h3>Step 1 · Customize visuals</h3>
          <p>
            Replace placeholder images and text, adjust colors and spacing to
            match your style.
          </p>
        </article>

        <article class="card reveal">
          <h3>Step 2 · Connect a backend</h3>
          <p>
            Swap the fake login and feedback storage for your own API or
            database. Keep the same UI.
          </p>
        </article>

        <article class="card reveal">
          <h3>Step 3 · Add real data</h3>
          <p>
            Feed the user and admin dashboards with live stats, lists, or
            analytics from your system.
          </p>
        </article>

        <article class="card reveal">
          <h3>Step 4 · Iterate &amp; ship</h3>
          <p>
            Refine the UX, performance and design until it feels ready to show
            friends, teammates or clients.
          </p>
        </article>
      </div>
    </section>

    <!-- =====================================================
         FINAL CTA SECTION
    ========================================================== -->
    <section class="section cta-section">
      <div class="cta-inner reveal">
        <h2>Ready to customize everything?</h2>
        <p>
          Every image, sentence, color and layout block here is just a placeholder.
          Keep what you like, remove what you don’t, and plug in your own ideas.
        </p>
        <div class="hero-actions">
          <a href="about.html" class="btn btn-primary">Learn more about this setup</a>
          <a href="feedback.php" class="btn btn-ghost">Leave feedback</a>
        </div>
      </div>
    </section>

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
          <input type="text" id="assistantInput" placeholder="Example: What can I explore here?" autocomplete="off" />
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
          <input type="text" id="commandInput" placeholder="Type a command, page, or theme..." autocomplete="off" />
        </div>
        <ul class="command-list" id="commandList" role="listbox"></ul>
      </div>
    </div>

    <!-- =====================================================
         GLOBAL FOOTER
    ========================================================== -->
    <footer class="footer">
      <p>
        © <span id="year"></span>
        <span data-i18n="footer_text">NovaSphere • Built step by step with you.</span>
      </p>
    </footer>
  </div>

  <!-- =====================================================
       MAIN SCRIPT
    ====================================================== -->
  <script src="https://unpkg.com/three@0.159.0/build/three.min.js"></script>
  <script src="script.js"></script>
</body>

</html>