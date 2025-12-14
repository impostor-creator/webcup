<?php
// ============================================================
// USER DASHBOARD - IASTROMATCH BIOPUNK
// ============================================================
// Uses real auth now (SQLite). This page still shows mostly
// demo content, but the logged-in user data is real.

require_once __DIR__ . '/auth.php';
auth_require_login();

$user = auth_user();

// Simulated data (replace with database queries later)
$announcements = [
    [
        'title' => 'Welcome to IAstroMatch',
        'message' => 'The biopunk dating platform is now live! Start matching with compatible lifeforms.',
        'created_at' => date('Y-m-d H:i:s')
    ],
    [
        'title' => 'New Species Added',
        'message' => 'Chloro-Humanoids and Gel-Forms are now available for matching.',
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
    ]
];

// Simulated user feedback
$userFeedback = [
    [
        'rating' => 5,
        'message' => 'Great compatibility match with a Gel-Form!',
        'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
    ],
    [
        'rating' => 4,
        'message' => 'Good interface, needs more species options',
        'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
    ]
];

// Simulated activity
$activity = [
    [
        'activity_type' => 'Match Found',
        'description' => 'Matched with Chloro-Humanoid "Sun_Seeker"',
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
    ],
    [
        'activity_type' => 'Profile Updated',
        'description' => 'Updated biological preferences',
        'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))
    ],
    [
        'activity_type' => 'Compatibility Test',
        'description' => 'Completed biopunk compatibility quiz',
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
    ]
];

// Calculate stats
$totalUserFeedback = count($userFeedback);
$avgUserRating = $totalUserFeedback > 0 
    ? round(array_sum(array_column($userFeedback, 'rating')) / $totalUserFeedback, 2)
    : 0;

// Badge based on activity
$badge = "new";
if ($totalUserFeedback >= 3) $badge = "explorer";
if ($totalUserFeedback >= 5) $badge = "pro";
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= htmlspecialchars($user['theme']) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard • IAstroMatch</title>
  
  <!-- Biopunk CSS -->
  <style>
    :root {
        --bio-dark: #0E1F1A;
        --bio-card: #102821;
        --bio-green: #3FA66B;
        --bio-cyan: #4FB3A2;
        --bio-mid: #1C3A2E;
        --bio-light: #7A9C7D;
        --bio-text: #E6E2D8;
        --bio-warning: #9BAA4D;
        --bio-error: #9B2A4D;
        --bio-glow: rgba(63, 166, 107, 0.3);
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
        background: var(--bio-dark);
        color: var(--bio-text);
        min-height: 100vh;
    }
    
    /* Dashboard Layout */
    .dashboard-container {
        display: flex;
        min-height: 100vh;
    }
    
    /* Sidebar */
    .sidebar {
        width: 260px;
        background: linear-gradient(180deg, var(--bio-mid), var(--bio-card));
        border-right: 2px solid var(--bio-green);
        padding: 30px 20px;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }
    
    .sidebar-header {
        color: var(--bio-green);
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 30px;
        text-align: center;
        text-shadow: 0 0 15px var(--bio-glow);
    }
    
    .sidebar-nav {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .sidebar-link {
        color: var(--bio-text);
        text-decoration: none;
        padding: 14px 16px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
    }
    
    .sidebar-link:hover {
        background: rgba(63, 166, 107, 0.2);
        color: var(--bio-green);
        transform: translateX(5px);
    }
    
    .sidebar-link.active {
        background: var(--bio-green);
        color: var(--bio-dark);
        font-weight: 600;
    }
    
    /* Main Content */
    .main-content {
        margin-left: 260px;
        padding: 30px;
        width: calc(100% - 260px);
    }
    
    /* Topbar */
    .topbar {
        background: linear-gradient(90deg, var(--bio-card), var(--bio-mid));
        border: 2px solid var(--bio-green);
        border-radius: 15px;
        padding: 20px 30px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 30px var(--bio-glow);
    }
    
    .welcome-text {
        font-size: 1.5rem;
        color: var(--bio-green);
        font-weight: 700;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .avatar {
        font-size: 2rem;
    }
    
    /* Cards */
    .card {
        background: linear-gradient(145deg, var(--bio-card), var(--bio-mid));
        border: 2px solid var(--bio-green);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    .card-header {
        color: var(--bio-green);
        font-size: 1.4rem;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }
    
    .stat-card {
        background: rgba(28, 58, 46, 0.5);
        border: 1px solid var(--bio-green);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
    }
    
    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    
    .stat-number {
        font-size: 2rem;
        color: var(--bio-green);
        font-weight: 700;
    }
    
    .stat-label {
        color: var(--bio-light);
        font-size: 0.9rem;
    }
    
    /* Tables */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    
    .data-table th {
        background: rgba(63, 166, 107, 0.2);
        color: var(--bio-green);
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid var(--bio-green);
    }
    
    .data-table td {
        padding: 12px;
        border-bottom: 1px solid rgba(63, 166, 107, 0.1);
    }
    
    .data-table tr:hover {
        background: rgba(63, 166, 107, 0.1);
    }
    
    /* Badges */
    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .badge-new { background: rgba(155, 170, 77, 0.3); color: var(--bio-warning); }
    .badge-explorer { background: rgba(63, 166, 107, 0.3); color: var(--bio-green); }
    .badge-pro { background: rgba(79, 179, 162, 0.3); color: var(--bio-cyan); }
    
    /* Buttons */
    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--bio-green), var(--bio-cyan));
        color: var(--bio-dark);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px var(--bio-glow);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            width: 80px;
        }
        
        .sidebar-header span {
            display: none;
        }
        
        .sidebar-link span:last-child {
            display: none;
        }
        
        .main-content {
            margin-left: 80px;
            width: calc(100% - 80px);
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-header">
        IAstro<span>Match</span>
      </div>
      
      <nav class="sidebar-nav">
        <a href="index.php" class="sidebar-link">
          <span>🏠</span> <span>Home</span>
        </a>
        <a href="#dashboard" class="sidebar-link active">
          <span>📊</span> <span>Dashboard</span>
        </a>
        <a href="#profile" class="sidebar-link">
          <span>🧬</span> <span>Profile</span>
        </a>
        <a href="#matches" class="sidebar-link">
          <span>💞</span> <span>Matches</span>
        </a>
        <a href="#compatibility" class="sidebar-link">
          <span>⚗️</span> <span>Compatibility</span>
        </a>
        <a href="#settings" class="sidebar-link">
          <span>⚙️</span> <span>Settings</span>
        </a>
        <a href="logout.php" class="sidebar-link">
          <span>🚪</span> <span>Logout</span>
        </a>
      </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
      <!-- Topbar -->
      <div class="topbar">
        <div class="welcome-text">
          Welcome back, <span style="color: var(--bio-cyan);"><?= htmlspecialchars($user['username']) ?></span>!
        </div>
        <div class="user-info">
          <div class="avatar">
            <?= ($user['avatar'] === 'spark') ? '✨' : '👤' ?>
          </div>
          <div>
            <div style="font-weight: 600;"><?= htmlspecialchars($user['species']) ?></div>
            <div style="font-size: 0.9rem; color: var(--bio-light);"><?= htmlspecialchars($user['email']) ?></div>
          </div>
        </div>
      </div>
      
      <!-- Profile Card -->
      <div class="card" id="profile">
        <div class="card-header">
          <span>🧬</span> Biological Profile
        </div>
        
        <div style="display: flex; align-items: center; gap: 30px; flex-wrap: wrap;">
          <div style="font-size: 4rem;">
            <?= ($user['avatar'] === 'spark') ? '✨' : '👤' ?>
          </div>
          
          <div style="flex: 1;">
            <h2 style="color: var(--bio-green); margin-bottom: 10px;">
              <?= htmlspecialchars($user['username']) ?>
            </h2>
            <div style="color: var(--bio-light); margin-bottom: 15px;">
              <?= htmlspecialchars($user['email']) ?>
            </div>
            
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
              <div class="badge badge-<?= $badge ?>">
                <?= ucfirst($badge) ?> Explorer
              </div>
              <div style="background: rgba(79, 179, 162, 0.2); padding: 6px 12px; border-radius: 20px; color: var(--bio-cyan);">
                <?= htmlspecialchars($user['species']) ?>
              </div>
            </div>
          </div>
          
          <button class="btn btn-primary">Edit Profile</button>
        </div>
      </div>
      
      <!-- Stats Grid -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">💞</div>
          <div class="stat-number">3</div>
          <div class="stat-label">Active Matches</div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">⭐</div>
          <div class="stat-number"><?= $avgUserRating ?></div>
          <div class="stat-label">Average Rating</div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">🧪</div>
          <div class="stat-number"><?= $totalUserFeedback ?></div>
          <div class="stat-label">Tests Completed</div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">🌍</div>
          <div class="stat-number">5</div>
          <div class="stat-label">Species Compatible</div>
        </div>
      </div>
      
      <!-- Recent Matches -->
      <div class="card" id="matches">
        <div class="card-header">
          <span>💞</span> Recent Matches
        </div>
        
        <table class="data-table">
          <thead>
            <tr>
              <th>Species</th>
              <th>Compatibility</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Chloro-Humanoid</td>
              <td>92%</td>
              <td><span style="color: var(--bio-green);">Active</span></td>
              <td><?= date('Y-m-d') ?></td>
            </tr>
            <tr>
              <td>Gel-Form</td>
              <td>85%</td>
              <td><span style="color: var(--bio-warning);">Pending</span></td>
              <td><?= date('Y-m-d', strtotime('-1 day')) ?></td>
            </tr>
            <tr>
              <td>Mycelian</td>
              <td>78%</td>
              <td><span style="color: var(--bio-cyan);">Connected</span></td>
              <td><?= date('Y-m-d', strtotime('-3 days')) ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Recent Activity -->
      <div class="card" id="activity">
        <div class="card-header">
          <span>📜</span> Recent Activity
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 15px;">
          <?php foreach ($activity as $act): ?>
          <div style="
            padding: 15px;
            background: rgba(28, 58, 46, 0.3);
            border-radius: 10px;
            border-left: 4px solid var(--bio-green);
          ">
            <div style="font-weight: 600; color: var(--bio-green);">
              <?= htmlspecialchars($act['activity_type']) ?>
            </div>
            <div style="color: var(--bio-text); margin: 5px 0;">
              <?= htmlspecialchars($act['description']) ?>
            </div>
            <div style="font-size: 0.85rem; color: var(--bio-light);">
              <?= $act['created_at'] ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      
      <!-- Announcements -->
      <div class="card" id="announcements">
        <div class="card-header">
          <span>📢</span> Announcements
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 20px;">
          <?php foreach ($announcements as $announcement): ?>
          <div style="
            padding: 20px;
            background: rgba(63, 166, 107, 0.1);
            border-radius: 12px;
            border: 1px solid var(--bio-green);
          ">
            <div style="font-size: 1.2rem; color: var(--bio-green); margin-bottom: 10px;">
              <?= htmlspecialchars($announcement['title']) ?>
            </div>
            <div style="color: var(--bio-text); margin-bottom: 10px;">
              <?= nl2br(htmlspecialchars($announcement['message'])) ?>
            </div>
            <div style="font-size: 0.85rem; color: var(--bio-light);">
              <?= $announcement['created_at'] ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      
      <!-- Settings -->
      <div class="card" id="settings">
        <div class="card-header">
          <span>⚙️</span> Settings
        </div>
        
        <div style="max-width: 400px;">
          <div style="margin-bottom: 20px;">
            <label style="display: block; color: var(--bio-cyan); margin-bottom: 8px;">Theme</label>
            <select style="
              width: 100%;
              padding: 12px;
              background: rgba(28, 58, 46, 0.7);
              border: 1px solid var(--bio-green);
              border-radius: 10px;
              color: var(--bio-text);
            ">
              <option value="biopunk" <?= $user['theme'] === 'biopunk' ? 'selected' : '' ?>>Biopunk</option>
              <option value="neon">Neon</option>
              <option value="sunset">Sunset</option>
            </select>
          </div>
          
          <button class="btn btn-primary" style="width: 100%;">
            Save Settings
          </button>
        </div>
      </div>
    </main>
  </div>
  
  <script>
    // Simple sidebar toggle for mobile
    document.querySelectorAll('.sidebar-link').forEach(link => {
      link.addEventListener('click', function(e) {
        if (this.getAttribute('href').startsWith('#')) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
          }
        }
      });
    });
    
    // Theme switching
    document.querySelector('select').addEventListener('change', function() {
      document.documentElement.setAttribute('data-theme', this.value);
      alert('Theme changed to ' + this.value + ' (would save in real app)');
    });
    
    // Auto-hide sidebar on small screens
    function checkScreenSize() {
      if (window.innerWidth <= 768) {
        document.querySelector('.sidebar').style.width = '80px';
        document.querySelector('.main-content').style.marginLeft = '80px';
        document.querySelector('.main-content').style.width = 'calc(100% - 80px)';
      } else {
        document.querySelector('.sidebar').style.width = '260px';
        document.querySelector('.main-content').style.marginLeft = '260px';
        document.querySelector('.main-content').style.width = 'calc(100% - 260px)';
      }
    }
    
    window.addEventListener('resize', checkScreenSize);
    checkScreenSize();
  </script>
</body>
</html>