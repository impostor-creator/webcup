<?php
require_once __DIR__ . '/auth.php';
$currentUser = auth_user();
?>


<!DOCTYPE html>
<html lang="en" data-theme="default">

<head>
  <!-- =========================================================
       META & GLOBAL ASSETS
       Home page for IAstroMatch
  ========================================================== -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IAstroMatch - Biopunk Dating Platform</title>

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
    <p>Booting IAstroMatch...</p>
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
        <div class="logo" data-i18n="brand_name">IAstroMatch</div>

        <!-- MAIN NAV LINKS -->
        <nav class="nav-links" id="navLinks">
          <a href="#hero" class="nav-link active" data-i18n="nav_home">Home</a>
          <a href="#features" class="nav-link" data-i18n="nav_features">Features</a>
          <a href="#gallery" class="nav-link" data-i18n="nav_gallery">Punks</a>
          <a href="feedback.php" class="nav-link" data-i18n="nav_feedback">Feedback</a>
          <a href="about.php" class="nav-link" data-i18n="nav_about">About</a>
        </nav>

        <!-- RIGHT SIDE CONTROLS -->
        <div class="nav-actions">
          <!-- Theme -->
          <select id="themeSwitcher" class="nav-select" aria-label="Theme switcher">
            <option value="default" data-i18n="theme_default">Biopunk</option>
            <option value="neon" data-i18n="theme_neon">Neon</option>
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
         - Bio-Digital Dating Interface intro
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
              Welcome to IAstroMatch
            </p>

            <h1 class="hero-title">
              <span data-i18n="hero_title_line1">A living, evolving</span><br />
              <span class="hero-highlight">
                Bio-Digital Dating Interface
              </span>
            </h1>

            <p class="hero-subtitle" data-i18n="hero_subtitle">
              Organic textures, cellular motion, and semi-transparent layers pulse beneath every dating interaction.
              User profiles don't simply load — they respond to interest, attraction, and mutual curiosity. 
              Matches are formed through biological-inspired signals, adaptive patterns, and subtle mutations that evolve as
              two individuals engage, flirt, and connect. Compatibility is experienced as chemistry, shaped by attraction, 
              intent, and emotional resonance.
            </p>

            <div class="hero-actions">
              <a href="#features" class="btn btn-primary" data-i18n="btn_explore_features">
                Begin Matching
              </a>
              <a href="login.php" class="btn btn-ghost" data-i18n="btn_login_now">
                Login &amp; try dashboards
              </a>
            </div>

            <!-- SMALL PILL LIST UNDER BUTTONS -->
            <div class="hero-footnote">
              <span>• Adaptive Dating profiles</span> ·
              <span>• Cross-species Compatibility</span> ·
              <span>• Dashboards</span>
            </div>

            <!-- Stats row -->
            <div class="stats-row">
              <div class="stat">
                <div class="stat-label">Potential Matches</div>
                <div class="stat-value" data-stat-target="6">0</div>
              </div>
              <div class="stat">
                <div class="stat-label">Active Matches</div>
                <div class="stat-value" data-stat-target="4">0</div>
              </div>
              <div class="stat">
                <div class="stat-label">Species Matches</div>
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
              <span class="hero-pill">Enter Symbiosis</span>
              <span class="hero-pill">Love with no end</span>
            </div>

            <div class="hero-visual-body">
              <p>
                From distant galaxies to destined partners — 
                witness how our bio-symbiotic matching algorithm connected two 
                incompatible species and transformed cosmic tensions into interstellar romance.
              </p>

              <!-- Animated mini "dashboard" inside the blank square -->
              <div class="hero-preview-window">
                <div class="hero-preview-header">
                  <span class="hero-preview-dots">
                    <span></span><span></span><span></span>
                  </span>
                  <span class="hero-preview-title">IAstroMatch - Ecosystem</span>
                </div>

                <div class="hero-preview-body">
                  <div class="hero-preview-sidebar">
                    <div class="hero-preview-pill hero-preview-pill-active">
                      Specimen
                    </div>
                    <div class="hero-preview-pill">Genome</div>
                    <div class="hero-preview-pill">Lab AI</div>
                  </div>

                  <div class="hero-preview-main">
                    <div class="hero-preview-bar hero-preview-bar-lg"></div>
                    <div class="hero-preview-bar hero-preview-bar-md"></div>

                    <div class="hero-preview-row">
                      <div class="hero-preview-chip">Stability: 78%</div>
                      <div class="hero-preview-chip">Mutation: Moderate</div>
                      <div class="hero-preview-chip">Symbiosis: High</div>
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
                  <strong>🧬 Sample:</strong> Hybrid organic / synthetic entity detected
                </div>
                <div class="hero-preview-meta-item">
                  <strong>🧠 AI State:</strong> Cultivated intelligence — active analysis
                </div>
                <div class="hero-preview-meta-item">
                  <strong>⚠️ Warning:</strong>
                  Prolonged exposure may alter both organisms
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
        <h2>Bio-compatibility Overview</h2>
        <p>
          An initial overview of the living systems already integrated into IAstroMatch,
          designed to connect incompatible species and reduce interstellar conflict
          through biological and emotional compatibility.
        </p>
      </div>

      <div class="about-story-inner">
        <article class="card reveal">
          <h3>Adaptive Encounter Interface</h3>
          <p>
            Navigation and interaction adjust dynamically to the species using the platform.
            The interface responds to biological needs, perception differences, and emotional signals.
          </p>
        </article>

        <article class="card reveal">
          <h3>Cultivated Match Intelligence</h3>
          <p>
            IAstroMatch uses a grown intelligence that analyzes profiles, intentions, and biological traits
            to propose compatible, improbable, or potentially dangerous matches.
          </p>
        </article>

        <article class="card reveal">
          <h3>Shared Biological Memory</h3>
          <p>
            Feedback from encounters is preserved as organic memory.
            Successful and failed matches influence future compatibility predictions across species.
          </p>
        </article>

        <article class="card reveal">
          <h3>Modular Diplomatic System</h3>
          <p>
            Each component of the platform operates independently.
            This allows new species, environments, or diplomatic protocols to be integrated without destabilizing the whole.
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
          <div class="card-icon">🧬</div>
          <h3 data-i18n="feature1_title">Species-Adaptive Profiles</h3>
          <p data-i18n="feature1_text">
            IAstroMatch profiles are shaped by biology, not assumptions.
            Each species defines its environment, morphology, communication method, 
            and biological constraints to ensure accurate compatibility analysis.
          </p>
        </article>

        <!-- Feature 2 -->
        <article class="card reveal hover-tilt">
          <div class="card-icon">🧠</div>
          <h3 data-i18n="feature2_title">Machine-Learning-Assisted Compatibility Testing</h3>
          <p data-i18n="feature2_text">
            IAstroMatch uses a lightweight machine-learning model to assist compatibility analysis.
            The model evaluates biological traits, environmental tolerance, and user intent to estimate
            match viability and identify potential risks.
          </p>
        </article>

        <!-- Feature 3 -->
        <article class="card reveal hover-tilt">
          <div class="card-icon">⚠️</div>
          <h3 data-i18n="feature3_title">Biological & Diplomatic Risk Indicators</h3>
          <p data-i18n="feature3_text">
            Compatibility is not always safe.
            The system highlights possible biological rejection, environmental incompatibility,
            or diplomatic instability before first contact occurs.
          </p>
        </article>

        <!-- Extra feature 4 -->
        <article class="card reveal hover-tilt">
          <div class="card-icon">⚗️</div>
          <h3>Match Outcome Preview</h3>
          <p>
            Before committing to a match, users can preview a projected outcome.
            This estimate provides guidance on stability, coexistence potential, and overall compatibility.
          </p>
        </article>

        <!-- Extra feature 5 -->
        <article class="card reveal hover-tilt">
          <div class="card-icon">🧪</div>
          <h3>Data Input Constraints</h3>
          <p>
            Compatibility results are based on the information provided by each species.
            Incomplete or ambiguous data may reduce accuracy and increase uncertainty in the analysis.
          </p>
        </article>

        <!-- Extra feature 6 -->
        <article class="card reveal hover-tilt">
          <div class="card-icon">🌍</div>
          <h3>Environmental Compatibility Filtering</h3>
          <p>
            Matches are filtered based on atmospheric, thermal, and environmental constraints
            to prevent immediate biological rejection.
          </p>
        </article>
      </div>
    </section>

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
          Keep what you like, remove what you don't, and plug in your own ideas.
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
            <p>Hi! I'm your IAstroMatch helper. Ask what you can do on this page.</p>
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
        <span data-i18n="footer_text">IAstroMatch • Built for interstellar connections.</span>
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