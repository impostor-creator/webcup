<?php
// ============================================================
// ADMIN PANEL (PROFESSIONAL VERSION)
// NovaSphere — Neon/Cyber Admin Dashboard
// Protected: admin only
// ============================================================

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/admin_functions.php';
auth_require_admin();

// Current admin user
$admin = auth_user();

/* ============================================================
   LOAD DASHBOARD DATA
============================================================ */

// 1) Feedback
$feedback = admin_get_all_feedback();

// Rating stats
$totalFeedback = count($feedback);
$avgRating = 0;
$ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

if ($totalFeedback > 0) {
    $sum = 0;
    foreach ($feedback as $f) {
        $r = (int)$f['rating'];
        $sum += $r;
        if (isset($ratingCounts[$r])) $ratingCounts[$r]++;
    }
    $avgRating = round($sum / $totalFeedback, 2);
}

// 2) Users
$users = admin_get_users();
$totalUsers = count($users);
$newUsers7 = 0;

$sevenDaysAgo = strtotime('-7 days');
foreach ($users as $u) {
    if (strtotime($u['created_at']) >= $sevenDaysAgo) {
        $newUsers7++;
    }
}

// 3) Announcements
$announcements = admin_get_announcements();
$totalAnnouncements = count($announcements);

// 4) Admin logs
$logs = admin_get_logs();
$recentLogs = array_slice($logs, 0, 10);

// 5) Activity for charts
// Build last 30 days feedback trend
$feedbackTrend = [];
for ($i = 30; $i >= 0; $i--) {
    $day = date('Y-m-d', strtotime("-$i days"));
    $feedbackTrend[$day] = 0;
}
foreach ($feedback as $f) {
    $day = date('Y-m-d', strtotime($f['created_at']));
    if (isset($feedbackTrend[$day])) {
        $feedbackTrend[$day]++;
    }
}

// 6) Ratings distribution
$ratingLabels = ["1 Star", "2 Stars", "3 Stars", "4 Stars", "5 Stars"];
$ratingValues = array_values($ratingCounts);

?>
<!DOCTYPE html>
<html lang="en" data-theme="default">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Panel • NovaSphere</title>

  <link rel="stylesheet" href="styles.css" />

  <!-- Chart.js for analytics -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    /* ======================================================
       ADMIN PANEL LAYOUT (PROFESSIONAL)
       (Non-destructive: does NOT override your main theme)
    ======================================================= */

    body {
      display: flex;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }

    /* ---- SIDEBAR ---- */
    .admin-sidebar {
      width: 260px;
      background: rgba(0,0,0,0.55);
      backdrop-filter: blur(12px);
      border-right: 1px solid rgba(255,255,255,0.1);
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      padding: 25px 0;
      display: flex;
      flex-direction: column;
      transition: width 0.3s ease;
      z-index: 900;
    }

    .admin-sidebar.collapsed {
      width: 80px;
    }

    .sidebar-header {
      color: #fff;
      padding: 0 20px 30px;
      font-size: 1.4rem;
      font-weight: 600;
    }

    .sidebar-nav {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .sidebar-link {
      color: #ddd;
      padding: 12px 20px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 12px;
      border-radius: 6px;
      transition: background 0.2s;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
      background: rgba(255,255,255,0.08);
      color: #fff;
    }

    .sidebar-icon {
      font-size: 1.2rem;
    }

    /* ---- CONTENT WRAPPER ---- */
    .admin-content {
      margin-left: 260px;
      padding: 30px;
      width: calc(100% - 260px);
      transition: margin-left 0.3s ease, width 0.3s ease;
    }

    .admin-content.collapsed {
      margin-left: 80px;
      width: calc(100% - 80px);
    }

    /* ---- TOPBAR ---- */
    .admin-topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      position: sticky;
      top: 0;
      z-index: 500;
      padding: 15px 20px;
      border-radius: 12px;
      backdrop-filter: blur(10px);
      background: rgba(0,0,0,0.35);
    }

    .topbar-title {
      font-size: 1.6rem;
      font-weight: 600;
      color: #fff;
    }

    .topbar-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .admin-search {
      background: rgba(255,255,255,0.08);
      border: none;
      padding: 10px 14px;
      border-radius: 6px;
      color: #fff;
      min-width: 180px;
    }

    /* Small responsive collapse */
    @media (max-width: 820px) {
      .admin-sidebar {
        width: 80px;
      }
      .admin-content {
        margin-left: 80px;
        width: calc(100% - 80px);
      }
    }
  </style>

</head>
<body>

<!-- ============================================================
     SIDEBAR (AUTO COLLAPSE)
============================================================ -->
<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-header">Admin Panel</div>

  <nav class="sidebar-nav">
  <!-- Go back to main site -->
  <a class="sidebar-link" href="index.php">
    <span class="sidebar-icon">🏠</span>
    <span class="sidebar-text">Home</span>
  </a>
  <a class="sidebar-link" href="feedback.php">
    <span class="sidebar-icon">📝</span>
    <span class="sidebar-text">Site Feedback</span>
  </a>

  <!-- Admin sections -->
  <a class="sidebar-link active">
    <span class="sidebar-icon">📊</span>
    <span class="sidebar-text">Dashboard</span>
  </a>
  <a class="sidebar-link" href="#feedback">
    <span class="sidebar-icon">⭐</span>
    <span class="sidebar-text">Feedback</span>
  </a>
  <a class="sidebar-link" href="#announcements">
    <span class="sidebar-icon">📢</span>
    <span class="sidebar-text">Announcements</span>
  </a>
  <a class="sidebar-link" href="#users">
    <span class="sidebar-icon">👥</span>
    <span class="sidebar-text">Users</span>
  </a>
  <a class="sidebar-link" href="#logs">
    <span class="sidebar-icon">📜</span>
    <span class="sidebar-text">Logs</span>
  </a>
  <a class="sidebar-link" href="logout.php">
    <span class="sidebar-icon">🚪</span>
    <span class="sidebar-text">Logout</span>
  </a>
</nav>

</aside>

<!-- ============================================================
     CONTENT WRAPPER
============================================================ -->
<div class="admin-content" id="adminContent">

  <!-- TOPBAR -->
  <div class="admin-topbar">
    <div class="topbar-title">Dashboard Overview</div>

    <div class="topbar-actions">
      <input type="text" class="admin-search" placeholder="Search..." />

      <select id="langSwitcher" class="nav-select">
        <option value="en">EN</option>
        <option value="fr">FR</option>
      </select>

      <select id="themeSwitcher" class="nav-select">
        <option value="default">Default</option>
        <option value="neon">Neon</option>
        <option value="cyber">Cyber</option>
        <option value="sunset">Sunset</option>
      </select>

      <div class="admin-avatar">
        <span style="color:white;">👑 <?=
          htmlspecialchars($admin['username'])
        ?></span>
      </div>
    </div>
  </div>

  <!-- ============================================================
     DASHBOARD OVERVIEW CARDS
============================================================ -->
<div class="dashboard-cards" style="
  display:grid;
  grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
  gap:20px;
  margin-bottom:30px;
">

  <!-- Total Feedback -->
  <div class="dash-card hover-tilt" style="
    background:rgba(255,255,255,0.07);
    border-radius:14px;
    padding:20px;
    backdrop-filter:blur(10px);
  ">
    <div style="font-size:2rem;">⭐</div>
    <div style="font-size:1.4rem;font-weight:600;color:#fff;">
      <?= $totalFeedback ?>
    </div>
    <div style="color:#ccc;">Total Feedback</div>
  </div>

  <!-- Average Rating -->
  <div class="dash-card hover-tilt" style="
    background:rgba(255,255,255,0.07);
    border-radius:14px;
    padding:20px;
    backdrop-filter:blur(10px);
  ">
    <div style="font-size:2rem;">📈</div>
    <div style="font-size:1.4rem;font-weight:600;color:#fff;">
      <?= $avgRating ?> / 5
    </div>
    <div style="color:#ccc;">Average Rating</div>
  </div>

  <!-- Total Users -->
  <div class="dash-card hover-tilt" style="
    background:rgba(255,255,255,0.07);
    border-radius:14px;
    padding:20px;
    backdrop-filter:blur(10px);
  ">
    <div style="font-size:2rem;">👥</div>
    <div style="font-size:1.4rem;font-weight:600;color:#fff;">
      <?= $totalUsers ?>
    </div>
    <div style="color:#ccc;">Total Users</div>
  </div>

  <!-- New Users (7 days) -->
  <div class="dash-card hover-tilt" style="
    background:rgba(255,255,255,0.07);
    border-radius:14px;
    padding:20px;
    backdrop-filter:blur(10px);
  ">
    <div style="font-size:2rem;">🌱</div>
    <div style="font-size:1.4rem;font-weight:600;color:#fff;">
      <?= $newUsers7 ?>
    </div>
    <div style="color:#ccc;">New Users (7 days)</div>
  </div>

  <!-- Announcements -->
  <div class="dash-card hover-tilt" style="
    background:rgba(255,255,255,0.07);
    border-radius:14px;
    padding:20px;
    backdrop-filter:blur(10px);
  ">
    <div style="font-size:2rem;">📢</div>
    <div style="font-size:1.4rem;font-weight:600;color:#fff;">
      <?= $totalAnnouncements ?>
    </div>
    <div style="color:#ccc;">Announcements</div>
  </div>

</div>

<!-- ============================================================
     ANALYTICS SECTION
============================================================ -->
<div id="analytics" style="margin-bottom:40px;">
  <h2 style="color:#fff;margin-bottom:20px;">Analytics</h2>

  <div style="
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:25px;
  ">
    <!-- Rating Distribution -->
    <div style="
      background:rgba(255,255,255,0.08);
      border-radius:14px;
      padding:20px;
    ">
      <h3 style="color:white;margin-bottom:10px;">Rating Distribution</h3>
      <canvas id="ratingChart" height="220"></canvas>
    </div>

    <!-- Feedback Trend -->
    <div style="
      background:rgba(255,255,255,0.08);
      border-radius:14px;
      padding:20px;
    ">
      <h3 style="color:white;margin-bottom:10px;">Feedback Trend (30 Days)</h3>
      <canvas id="trendChart" height="220"></canvas>
    </div>

    <!-- User Growth -->
    <div style="
      background:rgba(255,255,255,0.08);
      border-radius:14px;
      padding:20px;
    ">
      <h3 style="color:white;margin-bottom:10px;">User Growth</h3>
      <canvas id="userChart" height="220"></canvas>
    </div>
  </div>
</div>

<!-- ============================================================
     FEEDBACK MANAGEMENT PANEL
============================================================ -->
<a id="feedback"></a>
<div style="margin-top:50px;">
  <h2 style="color:white;margin-bottom:15px;">Feedback Management</h2>

  <div style="
    background:rgba(255,255,255,0.06);
    padding:20px;
    border-radius:14px;
  ">

    <table style="width:100%;border-collapse:collapse;color:white;">
      <thead>
        <tr style="background:rgba(255,255,255,0.1);">
          <th style="padding:10px;text-align:left;">User</th>
          <th style="padding:10px;text-align:left;">Rating</th>
          <th style="padding:10px;text-align:left;">Message</th>
          <th style="padding:10px;text-align:left;">Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($feedback as $f): ?>
          <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">
            <td style="padding:10px;">
              <?= $f['username'] ? htmlspecialchars($f['username']) : "<i>Guest</i>" ?>
            </td>
            <td style="padding:10px;"><?= $f['rating'] ?> ⭐</td>
            <td style="padding:10px;"><?= htmlspecialchars($f['message']) ?></td>
            <td style="padding:10px;"><?= $f['created_at'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>
</div>

<!-- ============================================================
     ANNOUNCEMENT CENTER
============================================================ -->
<a id="announcements"></a>
<div style="margin-top:60px;">
  <h2 style="color:white;margin-bottom:15px;">Announcements</h2>

  <!-- Create Announcement -->
  <div style="
    background:rgba(255,255,255,0.08);
    padding:20px;border-radius:14px;
    margin-bottom:20px;
  ">
    <h3 style="color:white;margin-bottom:10px;">Create Announcement</h3>

    <form method="post" action="process_admin.php" style="display:grid;gap:12px;">
      <input type="hidden" name="action" value="create_announcement" />

      <input 
        type="text" 
        name="title" 
        required 
        placeholder="Announcement title"
        style="padding:10px;border-radius:6px;border:none;background:rgba(255,255,255,0.15);color:white;"
      />

      <textarea 
        name="message" 
        required
        placeholder="Announcement message"
        style="padding:10px;border-radius:6px;border:none;background:rgba(255,255,255,0.15);color:white;height:100px;"
      ></textarea>

      <button type="submit" class="btn btn-primary">Publish Announcement</button>
    </form>
  </div>

  <!-- Announcement List -->
  <div style="
    background:rgba(255,255,255,0.06);
    padding:20px;border-radius:14px;
  ">
    <h3 style="color:white;margin-bottom:10px;">All Announcements</h3>

    <table style="width:100%;border-collapse:collapse;color:white;">
      <thead>
        <tr style="background:rgba(255,255,255,0.1);">
          <th style="padding:10px;text-align:left;">Title</th>
          <th style="padding:10px;text-align:left;">Message</th>
          <th style="padding:10px;text-align:left;">Date</th>
          <th style="padding:10px;text-align:left;">Actions</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($announcements as $a): ?>
          <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">
            <td style="padding:10px;"><?= htmlspecialchars($a['title']) ?></td>
            <td style="padding:10px;"><?= nl2br(htmlspecialchars($a['message'])) ?></td>
            <td style="padding:10px;"><?= $a['created_at'] ?></td>
            <td style="padding:10px;">
              <form method="post" action="process_admin.php" style="display:inline;">
                <input type="hidden" name="action" value="delete_announcement" />
                <input type="hidden" name="id" value="<?= $a['id'] ?>" />
                <button class="btn btn-danger" style="padding:6px 10px;">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>
</div>

<!-- ============================================================
     USER MANAGEMENT
============================================================ -->
<a id="users"></a>
<div style="margin-top:60px;">
  <h2 style="color:white;margin-bottom:15px;">User Management</h2>

  <div style="
    background:rgba(255,255,255,0.06);
    padding:20px;border-radius:14px;
  ">
    <table style="width:100%;border-collapse:collapse;color:white;">
      <thead>
        <tr style="background:rgba(255,255,255,0.1);">
          <th style="padding:10px;">Username</th>
          <th style="padding:10px;">Email</th>
          <th style="padding:10px;">Role</th>
          <th style="padding:10px;">Joined</th>
          <th style="padding:10px;">Actions</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($users as $u): ?>
          <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">
            <td style="padding:10px;"><?= htmlspecialchars($u['username']) ?></td>
            <td style="padding:10px;"><?= htmlspecialchars($u['email']) ?></td>
            <td style="padding:10px;"><?= htmlspecialchars($u['role']) ?></td>
            <td style="padding:10px;"><?= $u['created_at'] ?></td>

            <td style="padding:10px;">
              <!-- Promote/Demote -->
              <form method="post" action="process_admin.php" style="display:inline;">
                <input type="hidden" name="action" value="change_role" />
                <input type="hidden" name="id" value="<?= $u['id'] ?>" />

                <?php if ($u['role'] === 'admin'): ?>
                  <input type="hidden" name="role" value="user" />
                  <button class="btn btn-secondary" style="padding:6px 10px;">Demote</button>
                <?php else: ?>
                  <input type="hidden" name="role" value="admin" />
                  <button class="btn btn-primary" style="padding:6px 10px;">Promote</button>
                <?php endif; ?>
              </form>

              <!-- Delete User -->
              <form method="post" action="process_admin.php" style="display:inline;">
                <input type="hidden" name="action" value="delete_user" />
                <input type="hidden" name="id" value="<?= $u['id'] ?>" />
                <button class="btn btn-danger" style="padding:6px 10px;">Delete</button>
              </form>
            </td>

          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ============================================================
     ADMIN LOGS
============================================================ -->
<a id="logs"></a>
<div style="margin-top:60px;">
  <h2 style="color:white;margin-bottom:15px;">Admin Logs</h2>

  <div style="
    background:rgba(255,255,255,0.06);
    padding:20px;border-radius:14px;
  ">
    <?php if (empty($logs)): ?>
      <p style="color:#ddd;">No logs yet.</p>
    <?php else: ?>

      <table style="width:100%;border-collapse:collapse;color:white;">
        <thead>
          <tr style="background:rgba(255,255,255,0.1);">
            <th style="padding:10px;">Admin</th>
            <th style="padding:10px;">Action</th>
            <th style="padding:10px;">Details</th>
            <th style="padding:10px;">Date</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($logs as $log): ?>
            <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">
              <td style="padding:10px;">
                <?= htmlspecialchars($log['username'] ?? 'Unknown') ?>
              </td>
              <td style="padding:10px;"><?= htmlspecialchars($log['action']) ?></td>
              <td style="padding:10px;"><?= nl2br(htmlspecialchars($log['details'])) ?></td>
              <td style="padding:10px;"><?= $log['created_at'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    <?php endif; ?>
  </div>
</div>

<!-- ============================================================
     SYSTEM SETTINGS PANEL
============================================================ -->
<a id="settings"></a>
<div style="margin-top:60px;">
  <h2 style="color:white;margin-bottom:15px;">System Settings</h2>

  <div style="
    background:rgba(255,255,255,0.06);
    padding:20px;border-radius:14px;
  ">

    <p style="color:#ccc;margin-bottom:20px;">
      These settings control how the admin dashboard behaves.  
      (User-specific settings such as avatar and theme are in the User Dashboard.)
    </p>

    <div style="display:grid;gap:15px;max-width:420px;">

      <!-- Sidebar Auto Mode Toggle -->
      <div>
        <label style="color:white;font-weight:600;">Sidebar Mode</label>
        <select id="sidebarMode" style="
          width:100%;padding:10px;border-radius:6px;
          background:rgba(255,255,255,0.1);color:white;border:none;
        ">
          <option value="auto">Auto (Recommended)</option>
          <option value="expanded">Always Expanded</option>
          <option value="collapsed">Always Collapsed</option>
        </select>
      </div>

      <!-- Dashboard Color Accent -->
      <div>
        <label style="color:white;font-weight:600;">Dashboard Accent</label>
        <select id="accentColor" style="
          width:100%;padding:10px;border-radius:6px;
          background:rgba(255,255,255,0.1);color:white;border:none;
        ">
          <option value="default">Default</option>
          <option value="neon">Neon Glow</option>
          <option value="cyber">Cyber Blue</option>
          <option value="sunset">Sunset Orange</option>
        </select>
      </div>

      <!-- Apply Settings -->
      <button class="btn btn-primary" id="saveSettings">Save Settings</button>

    </div>

  </div>
</div>


<!-- ============================================================
     SIDEBAR TOGGLE BUTTON (BOTTOM FLOAT)
============================================================ -->
<button id="sidebarToggleBtn" style="
  position:fixed;bottom:25px;left:25px;z-index:950;
  background:rgba(0,0,0,0.55);
  border:1px solid rgba(255,255,255,0.15);
  color:white;border-radius:40px;
  padding:12px 16px;
  cursor:pointer;backdrop-filter:blur(10px);
">
  ☰
</button>

</div> <!-- END admin-content -->
</body>

<!-- ============================================================
     JAVASCRIPT SECTION (CHARTS + SIDEBAR)
============================================================ -->
<script>
/* -------------------------------------------------------------
   SIDEBAR COLLAPSE HANDLING
------------------------------------------------------------- */
const sidebar = document.getElementById('adminSidebar');
const content = document.getElementById('adminContent');
const toggleBtn = document.getElementById('sidebarToggleBtn');

// Auto-collapse on mobile
function applySidebarAutoMode() {
  if (window.innerWidth <= 820) {
    sidebar.classList.add('collapsed');
    content.classList.add('collapsed');
  } else {
    sidebar.classList.remove('collapsed');
    content.classList.remove('collapsed');
  }
}
applySidebarAutoMode();

window.addEventListener("resize", applySidebarAutoMode);

// Manual toggle
toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle('collapsed');
  content.classList.toggle('collapsed');
});


/* -------------------------------------------------------------
   CHART.JS — RATING DISTRIBUTION
------------------------------------------------------------- */
const ratingCtx = document.getElementById('ratingChart').getContext('2d');
const ratingChart = new Chart(ratingCtx, {
  type: 'doughnut',
  data: {
    labels: <?= json_encode($ratingLabels) ?>,
    datasets: [{
      data: <?= json_encode($ratingValues) ?>,
      backgroundColor: [
        '#ff4d4d', '#ffa64d', '#ffe44d', '#b3ff4d', '#4dff88'
      ],
      borderWidth: 1,
      cutout: '60%'
    }]
  },
  options: {
    plugins: {
      legend: {
        labels: { color: 'white' }
      }
    }
  }
});


/* -------------------------------------------------------------
   FEEDBACK TREND CHART
------------------------------------------------------------- */
const trendCtx = document.getElementById('trendChart').getContext('2d');
const trendChart = new Chart(trendCtx, {
  type: 'line',
  data: {
    labels: <?= json_encode(array_keys($feedbackTrend)) ?>,
    datasets: [{
      label: 'Feedback Count',
      data: <?= json_encode(array_values($feedbackTrend)) ?>,
      borderColor: '#4da6ff',
      backgroundColor: 'rgba(77,166,255,0.25)',
      fill: true,
      tension: 0.3
    }]
  },
  options: {
    scales: {
      x: { ticks: { color: 'white' } },
      y: { ticks: { color: 'white' }, beginAtZero: true }
    },
    plugins: {
      legend: { labels: { color: 'white' } }
    }
  }
});


/* -------------------------------------------------------------
   USER GROWTH CHART (Simple placeholder — can expand)
------------------------------------------------------------- */
const userCtx = document.getElementById('userChart').getContext('2d');
const userChart = new Chart(userCtx, {
  type: 'bar',
  data: {
    labels: ['New Users (7 days)', 'Total Users'],
    datasets: [{
      data: [<?= $newUsers7 ?>, <?= $totalUsers ?>],
      backgroundColor: ['#4dff88', '#4da6ff']
    }]
  },
  options: {
    plugins: {
      legend: { display: false }
    },
    scales: {
      x: { ticks: { color:'white' } },
      y: { ticks: { color:'white' }, beginAtZero:true }
    }
  }
});

/* -------------------------------------------------------------
   SAVE SETTINGS (LOCAL — PER ADMIN DEVICE)
   You can expand this later to store in database.
------------------------------------------------------------- */

const sidebarModeSelect = document.getElementById("sidebarMode");
const accentColorSelect = document.getElementById("accentColor");
const saveBtn = document.getElementById("saveSettings");

// Load saved settings
function loadAdminSettings() {
  const savedSidebar = localStorage.getItem("admin_sidebar_mode");
  const savedAccent  = localStorage.getItem("admin_accent_color");

  if (savedSidebar) sidebarModeSelect.value = savedSidebar;
  if (savedAccent)  accentColorSelect.value = savedAccent;

  applySidebarSetting(savedSidebar);
  applyAccentColor(savedAccent);
}

function applySidebarSetting(mode) {
  if (!mode || mode === "auto") {
    applySidebarAutoMode();
    return;
  }
  if (mode === "expanded") {
    sidebar.classList.remove("collapsed");
    content.classList.remove("collapsed");
  }
  if (mode === "collapsed") {
    sidebar.classList.add("collapsed");
    content.classList.add("collapsed");
  }
}

function applyAccentColor(mode) {
  if (!mode || mode === "default") {
    document.documentElement.style.setProperty("--accent", "#00ffff");
    return;
  }
  if (mode === "neon") {
    document.documentElement.style.setProperty("--accent", "#00eaff");
  }
  if (mode === "cyber") {
    document.documentElement.style.setProperty("--accent", "#3b8bff");
  }
  if (mode === "sunset") {
    document.documentElement.style.setProperty("--accent", "#ff784e");
  }
}

// Save settings
saveBtn.addEventListener("click", () => {
  const sidebarMode = sidebarModeSelect.value;
  const accent = accentColorSelect.value;

  localStorage.setItem("admin_sidebar_mode", sidebarMode);
  localStorage.setItem("admin_accent_color", accent);

  applySidebarSetting(sidebarMode);
  applyAccentColor(accent);

  alert("Settings saved!");
});

// Initialize settings on load
loadAdminSettings();

</script>

</html>
