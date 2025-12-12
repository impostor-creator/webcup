<?php
// ============================================================
// USER DASHBOARD (PRO VERSION)
// Protected: login required
// ============================================================
require_once __DIR__ . '/auth.php';
auth_require_login();

require_once __DIR__ . '/admin_functions.php';
require_once __DIR__ . '/db.php';

// Current logged-in user
$user = auth_user();
$userId = $user['id'];

/* ============================================================
   LOAD USER DATA
============================================================ */

// 1) Load announcements
$announcements = admin_get_announcements();

// 2) Load user feedback from DB
$stmt = db()->prepare("SELECT * FROM feedback WHERE user_id = :id ORDER BY created_at DESC");
$stmt->execute(['id' => $userId]);
$userFeedback = $stmt->fetchAll();

// 3) Load activity history
$stmt = db()->prepare("SELECT * FROM user_activity WHERE user_id = :id ORDER BY created_at DESC LIMIT 25");
$stmt->execute(['id' => $userId]);
$activity = $stmt->fetchAll();

// 4) Calculate feedback statistics
$totalUserFeedback = count($userFeedback);
$avgUserRating = 0;

if ($totalUserFeedback > 0) {
    $sum = 0;
    foreach ($userFeedback as $f) {
        $sum += (int)$f['rating'];
    }
    $avgUserRating = round($sum / $totalUserFeedback, 2);
}

// 5) Dynamic badge assignment (basic example)
$badge = "new";
if ($totalUserFeedback >= 5) $badge = "explorer";
if ($totalUserFeedback >= 15) $badge = "pro";
if ($totalUserFeedback >= 30) $badge = "elite";

?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= htmlspecialchars($user['theme']) ?>">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard • NovaSphere</title>
  <link rel="stylesheet" href="styles.css">

  <!-- Chart.js for user analytics -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    /* ======================================================
       USER DASHBOARD LAYOUT (PRO EDITION)
       — sidebar + topbar, neon styling
       — DOES NOT override your main CSS
    ======================================================= */

    body {
      display: flex;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      background: var(--background, #000);
    }

    /* ---- SIDEBAR ---- */
    .user-sidebar {
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

    .user-sidebar.collapsed {
      width: 80px;
    }

    .user-sidebar .sidebar-header {
      color: #fff;
      font-size: 1.4rem;
      padding: 0 20px 30px;
      font-weight: 600;
    }

    .sidebar-link {
      color: #ddd;
      padding: 12px 20px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 12px;
      border-radius: 6px;
      transition: background .2s;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
      background: rgba(255,255,255,0.08);
      color: #fff;
    }

    /* ---- MAIN CONTENT ---- */
    .user-content {
      margin-left: 260px;
      padding: 30px;
      width: calc(100% - 260px);
      transition: margin-left .3s ease;
    }

    .user-content.collapsed {
      margin-left: 80px;
      width: calc(100% - 80px);
    }

    /* ---- TOPBAR ---- */
    .user-topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding: 15px 20px;
      border-radius: 12px;
      background: rgba(0,0,0,0.35);
      backdrop-filter: blur(10px);
    }

    .topbar-title {
      font-size: 1.6rem;
      font-weight: 600;
      color: white;
    }

    .topbar-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    /* Responsive collapse */
    @media (max-width: 820px) {
      .user-sidebar {
        width: 80px;
      }
      .user-content {
        margin-left: 80px;
        width: calc(100% - 80px);
      }
    }
  </style>
</head>

<body>

<!-- ============================================================
     SIDEBAR
============================================================ -->
<aside class="user-sidebar" id="userSidebar">
  <div class="sidebar-header">User Panel</div>

  <nav class="sidebar-nav">
  <!-- Links back to the public site -->
  <a class="sidebar-link" href="index.php">
    <span>🏠</span> Home
  </a>
  <a class="sidebar-link" href="feedback.php">
    <span>📝</span> Feedback Page
  </a>

  <!-- User dashboard sections -->
  <a class="sidebar-link active" href="#">
    <span>📊</span> Dashboard
  </a>
  <a class="sidebar-link" href="#profile">
    <span>👤</span> Profile
  </a>
  <a class="sidebar-link" href="#announcements">
    <span>📢</span> Announcements
  </a>
  <a class="sidebar-link" href="#feedback">
    <span>⭐</span> My Feedback
  </a>
  <a class="sidebar-link" href="#activity">
    <span>📜</span> Activity
  </a>
  <a class="sidebar-link" href="logout.php">
    <span>🚪</span> Logout
  </a>
</nav>

</aside>

<!-- ============================================================
     MAIN CONTENT
============================================================ -->
<div class="user-content" id="userContent">

  <!-- TOPBAR -->
  <div class="user-topbar">
    <div class="topbar-title">Welcome back, <?= htmlspecialchars($user['username']) ?>!</div>

    <div class="topbar-actions">
      <select id="langSwitcher" class="nav-select">
        <option value="en" <?= $user['language'] === 'en' ? 'selected' : '' ?>>EN</option>
        <option value="fr" <?= $user['language'] === 'fr' ? 'selected' : '' ?>>FR</option>
      </select>

      <select id="themeSwitcher" class="nav-select">
        <option value="default" <?= $user['theme'] === 'default' ? 'selected' : '' ?>>Default</option>
        <option value="neon" <?= $user['theme'] === 'neon' ? 'selected' : '' ?>>Neon</option>
        <option value="cyber" <?= $user['theme'] === 'cyber' ? 'selected' : '' ?>>Cyber</option>
        <option value="sunset" <?= $user['theme'] === 'sunset' ? 'selected' : '' ?>>Sunset</option>
      </select>

      <div class="user-avatar" style="color:white;">
        <?= $user['avatar'] === "orb" ? "🔮" : 
            ($user['avatar'] === "planet" ? "🪐" :
            ($user['avatar'] === "cube" ? "🧊" : "✨")) ?>
      </div>
    </div>
  </div>

  <!-- ============================================================
     PROFILE SUMMARY SECTION
============================================================ -->
<a id="profile"></a>
<div style="margin-bottom:50px;">
  <h2 style="color:white;margin-bottom:15px;">Your Profile</h2>

  <div style="
    display:flex;
    gap:25px;
    background:rgba(255,255,255,0.06);
    padding:25px;
    border-radius:14px;
    align-items:center;
    flex-wrap:wrap;
  ">
    
    <!-- Avatar -->
    <div style="font-size:4rem;">
      <?= $user['avatar'] === "orb" ? "🔮" :
          ($user['avatar'] === "planet" ? "🪐" :
          ($user['avatar'] === "cube" ? "🧊" : "✨")) ?>
    </div>

    <!-- User Info -->
    <div style="flex:1;">
      <div style="font-size:1.6rem;color:white;font-weight:600;">
        <?= htmlspecialchars($user['username']) ?>
      </div>
      <div style="color:#ccc;">
        <?= htmlspecialchars($user['email']) ?>
      </div>

      <!-- Badge -->
      <div style="margin-top:10px;">
        <?php if ($badge === "new"): ?>
          <span style="background:#444;padding:5px 10px;border-radius:6px;">🌱 New Explorer</span>
        <?php elseif ($badge === "explorer"): ?>
          <span style="background:#00ffaa33;padding:5px 10px;border-radius:6px;">🧭 Explorer</span>
        <?php elseif ($badge === "pro"): ?>
          <span style="background:#0099ff33;padding:5px 10px;border-radius:6px;">💠 Pro User</span>
        <?php else: ?>
          <span style="background:#ff00ff33;padding:5px 10px;border-radius:6px;">👑 Elite User</span>
        <?php endif; ?>
      </div>
    </div>

    <!-- Edit Profile Button -->
    <button class="btn btn-primary" id="editProfileBtn">Edit Profile</button>
  </div>
</div>


<!-- ============================================================
     EDIT PROFILE MODAL
============================================================ -->
<div id="editProfileModal" style="
  display:none;
  position:fixed;
  top:0;left:0;width:100%;height:100%;
  background:rgba(0,0,0,0.75);
  backdrop-filter:blur(6px);
  justify-content:center;
  align-items:center;
  z-index:999;
">
  <div style="
    background:rgba(255,255,255,0.1);
    padding:25px;
    border-radius:14px;
    width:95%;
    max-width:500px;
    color:white;
  ">
    <h2>Edit Profile</h2>

    <form method="post" action="process_user.php" style="display:grid;gap:10px;">
      <input type="hidden" name="action" value="update_profile">

      <!-- Username -->
      <div>
        <label>Username</label>
        <input 
          type="text" 
          name="username"
          required
          value="<?= htmlspecialchars($user['username']) ?>"
          style="width:100%;padding:10px;border:none;border-radius:6px;
                 background:rgba(255,255,255,0.2);color:white;"
        >
      </div>

      <!-- Email -->
      <div>
        <label>Email</label>
        <input 
          type="email" 
          name="email"
          required
          value="<?= htmlspecialchars($user['email']) ?>"
          style="width:100%;padding:10px;border:none;border-radius:6px;
                 background:rgba(255,255,255,0.2);color:white;"
        >
      </div>

      <!-- New Password -->
      <div>
        <label>New Password (optional)</label>
        <input 
          type="password" 
          name="new_password"
          placeholder="Leave blank to keep current password"
          style="width:100%;padding:10px;border:none;border-radius:6px;
                 background:rgba(255,255,255,0.2);color:white;"
        >
      </div>

      <!-- Avatar Selection -->
      <div>
        <label style="margin-bottom:6px;display:block;">Avatar</label>
        <div style="display:flex;gap:12px;">
          
          <label style="font-size:2rem;cursor:pointer;">
            <input type="radio" name="avatar" value="orb" <?= $user['avatar'] === 'orb' ? 'checked' : '' ?>>
            🔮
          </label>

          <label style="font-size:2rem;cursor:pointer;">
            <input type="radio" name="avatar" value="planet" <?= $user['avatar'] === 'planet' ? 'checked' : '' ?>>
            🪐
          </label>

          <label style="font-size:2rem;cursor:pointer;">
            <input type="radio" name="avatar" value="cube" <?= $user['avatar'] === 'cube' ? 'checked' : '' ?>>
            🧊
          </label>

          <label style="font-size:2rem;cursor:pointer;">
            <input type="radio" name="avatar" value="spark" <?= $user['avatar'] === 'spark' ? 'checked' : '' ?>>
            ✨
          </label>

        </div>
      </div>

      <!-- Save + Cancel -->
      <div style="display:flex;justify-content:space-between;margin-top:15px;">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <button type="button" id="closeProfileModal" class="btn btn-ghost">Cancel</button>
      </div>

    </form>
  </div>
</div>


<!-- ============================================================
     USER PREFERENCES
============================================================ -->
<div style="margin-top:60px;">
  <h2 style="color:white;margin-bottom:15px;">Preferences</h2>

  <div style="
    background:rgba(255,255,255,0.06);
    padding:20px;border-radius:14px;
    max-width:450px;
  ">
    
    <!-- Theme -->
    <div style="margin-bottom:15px;">
      <label style="color:white;font-weight:600;">Theme</label>
      <select id="userThemeSelector" style="
        width:100%;padding:10px;
        background:rgba(255,255,255,0.15);
        border:none;border-radius:6px;color:white;
      ">
        <option value="default" <?= $user['theme']==='default' ? 'selected':'' ?>>Default</option>
        <option value="neon" <?= $user['theme']==='neon' ? 'selected':'' ?>>Neon</option>
        <option value="cyber" <?= $user['theme']==='cyber' ? 'selected':'' ?>>Cyber</option>
        <option value="sunset" <?= $user['theme']==='sunset' ? 'selected':'' ?>>Sunset</option>
      </select>
    </div>

    <!-- Language -->
    <div style="margin-bottom:15px;">
      <label style="color:white;font-weight:600;">Language</label>
      <select id="userLangSelector" style="
        width:100%;padding:10px;
        background:rgba(255,255,255,0.15);
        border:none;border-radius:6px;color:white;
      ">
        <option value="en" <?= $user['language']==='en' ? 'selected':'' ?>>EN</option>
        <option value="fr" <?= $user['language']==='fr' ? 'selected':'' ?>>FR</option>
      </select>
    </div>

    <button class="btn btn-primary" id="saveUserPreferences">Save Preferences</button>

  </div>
</div>

<!-- ============================================================
     ANNOUNCEMENTS FEED
============================================================ -->
<a id="announcements"></a>
<div style="margin-top:60px;">
  <h2 style="color:white;margin-bottom:15px;">Announcements</h2>

  <div style="
    display:flex;
    flex-direction:column;
    gap:15px;
  ">

    <?php if (empty($announcements)): ?>
      <p style="color:#ccc;">No announcements available.</p>
    <?php else: ?>

      <?php foreach ($announcements as $a): ?>
        <div style="
          background:rgba(255,255,255,0.08);
          padding:20px;
          border-radius:14px;
          color:white;
        ">
          <h3 style="margin-bottom:8px;color:white;">
            <?= htmlspecialchars($a['title']) ?>
          </h3>

          <p style="color:#ddd;">
            <?= nl2br(htmlspecialchars($a['message'])) ?>
          </p>

          <div style="font-size:0.85rem;color:#aaa;margin-top:8px;">
            <?= $a['created_at'] ?>
          </div>
        </div>
      <?php endforeach; ?>

    <?php endif; ?>

  </div>
</div>


<!-- ============================================================
     USER FEEDBACK SUMMARY
============================================================ -->
<a id="feedback"></a>
<div style="margin-top:60px;">
  <h2 style="color:white;margin-bottom:15px;">My Feedback Summary</h2>

  <div style="
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(240px,1fr));
    gap:20px;
    margin-bottom:30px;
  ">

    <!-- Total Feedback -->
    <div style="
      background:rgba(255,255,255,0.06);
      padding:20px;border-radius:14px;
    ">
      <div style="font-size:2rem;">📝</div>
      <div style="font-size:1.4rem;color:white;font-weight:600;">
        <?= $totalUserFeedback ?>
      </div>
      <div style="color:#ccc;">Feedback Submitted</div>
    </div>

    <!-- Average Rating -->
    <div style="
      background:rgba(255,255,255,0.06);
      padding:20px;border-radius:14px;
    ">
      <div style="font-size:2rem;">⭐</div>
      <div style="font-size:1.4rem;color:white;font-weight:600;">
        <?= $avgUserRating ?> / 5
      </div>
      <div style="color:#ccc;">Average Rating</div>
    </div>

  </div>

  <!-- Feedback Chart -->
  <div style="
    background:rgba(255,255,255,0.08);
    padding:20px;border-radius:14px;
    margin-bottom:40px;
  ">
    <h3 style="color:white;margin-bottom:10px;">Rating Chart</h3>
    <canvas id="userRatingChart" height="200"></canvas>
  </div>

  <!-- Full Feedback List -->
  <div style="
    background:rgba(255,255,255,0.06);
    padding:20px;border-radius:14px;
  ">
    <h3 style="color:white;margin-bottom:10px;">Your Feedback</h3>

    <?php if (empty($userFeedback)): ?>
      <p style="color:#ccc;">You haven't submitted any feedback yet.</p>
    <?php else: ?>

    <table style="width:100%;border-collapse:collapse;color:white;">
      <thead>
        <tr style="background:rgba(255,255,255,0.1);">
          <th style="padding:10px;text-align:left;">Rating</th>
          <th style="padding:10px;text-align:left;">Message</th>
          <th style="padding:10px;text-align:left;">Date</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($userFeedback as $f): ?>
          <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">
            <td style="padding:10px;"><?= $f['rating'] ?> ⭐</td>
            <td style="padding:10px;"><?= htmlspecialchars($f['message']) ?></td>
            <td style="padding:10px;"><?= $f['created_at'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php endif; ?>

  </div>
</div>


<!-- ============================================================
     ACTIVITY TIMELINE
============================================================ -->
<a id="activity"></a>
<div style="margin-top:60px;margin-bottom:40px;">
  <h2 style="color:white;margin-bottom:15px;">Recent Activity</h2>

  <div style="
    display:flex;
    flex-direction:column;
    gap:12px;
  ">
    <?php if (empty($activity)): ?>
      <p style="color:#ccc;">No recent activity found.</p>
    <?php else: ?>
      <?php foreach ($activity as $act): ?>
        <div style="
          background:rgba(255,255,255,0.06);
          padding:15px;border-radius:12px;
          color:white;
        ">
          <div style="font-weight:600;">
            <?= htmlspecialchars($act['activity_type']) ?>
          </div>

          <div style="color:#ddd;">
            <?= htmlspecialchars($act['description']) ?>
          </div>

          <div style="font-size:0.85rem;color:#aaa;margin-top:6px;">
            <?= $act['created_at'] ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>

<!-- ============================================================
     JAVASCRIPT SECTION
============================================================ -->
<script>
// ------------------------------------------------------------
// SIDEBAR COLLAPSE HANDLING
// ------------------------------------------------------------
const userSidebar = document.getElementById("userSidebar");
const userContent = document.getElementById("userContent");

// Auto collapse on mobile
function applyUserSidebarAuto() {
  if (window.innerWidth <= 820) {
    userSidebar.classList.add("collapsed");
    userContent.classList.add("collapsed");
  } else {
    userSidebar.classList.remove("collapsed");
    userContent.classList.remove("collapsed");
  }
}
applyUserSidebarAuto();
window.addEventListener("resize", applyUserSidebarAuto);

// ------------------------------------------------------------
// EDIT PROFILE MODAL
// ------------------------------------------------------------
const editBtn = document.getElementById("editProfileBtn");
const modal = document.getElementById("editProfileModal");
const modalClose = document.getElementById("closeProfileModal");

editBtn.addEventListener("click", () => {
  modal.style.display = "flex";
});

modalClose.addEventListener("click", () => {
  modal.style.display = "none";
});

// Close modal when clicking background
modal.addEventListener("click", (e) => {
  if (e.target === modal) modal.style.display = "none";
});


// ------------------------------------------------------------
// USER RATING CHART
// ------------------------------------------------------------
<?php
  // Prepare data for rating distribution (user only)
  $ratingDist = [1=>0,2=>0,3=>0,4=>0,5=>0];
  foreach ($userFeedback as $f) {
    $r = (int)$f['rating'];
    if (isset($ratingDist[$r])) $ratingDist[$r]++;
  }
?>
const userRatingCtx = document.getElementById("userRatingChart").getContext("2d");

const userRatingChart = new Chart(userRatingCtx, {
  type: "doughnut",
  data: {
    labels: ["1 Star","2 Stars","3 Stars","4 Stars","5 Stars"],
    datasets: [{
      data: <?= json_encode(array_values($ratingDist)) ?>,
      backgroundColor: [
        "#ff4d4d", "#ffa64d", "#ffe44d", "#b3ff4d", "#4dff88"
      ],
      borderWidth: 1,
      cutout: "60%"
    }]
  },
  options: {
    plugins: {
      legend: { labels: { color: "white" } }
    }
  }
});


// ------------------------------------------------------------
// SAVE USER PREFERENCES (Theme + Language)
// ------------------------------------------------------------
document.getElementById("saveUserPreferences").addEventListener("click", () => {

  const theme = document.getElementById("userThemeSelector").value;
  const lang = document.getElementById("userLangSelector").value;

  const formData = new FormData();
  formData.append("action", "save_preferences");
  formData.append("theme", theme);
  formData.append("language", lang);

  fetch("process_user.php", {
    method: "POST",
    body: formData
  })
  .then(r => r.text())
  .then(res => {
    alert("Preferences saved!");
    location.reload();
  });
});


// ------------------------------------------------------------
// Smooth section scrolling for sidebar
// ------------------------------------------------------------
document.querySelectorAll(".sidebar-link").forEach(link => {
  link.addEventListener("click", (e) => {
    const href = link.getAttribute("href");
    if (!href || !href.startsWith("#")) return;

    e.preventDefault();

    const target = document.querySelector(href);
    if (!target) return;

    window.scrollTo({
      top: target.offsetTop - 40,
      behavior: "smooth"
    });
  });
});

</script>
</body>
</html>

<!-- ============================================================
     USER DASHBOARD COMPLETE
============================================================ -->

<!-- Nothing more to add here — all logic, layout, charts, 
     profile editing, announcements, preferences, and activity 
     are fully implemented in previous sections. -->

</body>
</html>
