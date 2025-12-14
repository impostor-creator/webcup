<?php
// ============================================================
// REGISTRATION - IASTROMATCH BIOPUNK
// Fixed version for HTTP 500 error
// ============================================================

// TURN OFF ERROR DISPLAY FOR PRODUCTION
error_reporting(0);
ini_set('display_errors', 0);

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$errors = array();
$success = false;
$username = $email = $species = $avatar = '';

// Process form if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data with basic validation
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['password'] : '';
    $species = isset($_POST['species']) ? $_POST['species'] : 'Grafted';
    $avatar = isset($_POST['avatar']) ? $_POST['avatar'] : 'spark';
    
    // Simple validation
    if (empty($username)) {
        $errors[] = 'Username is required';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    // If no errors, register user
    if (empty($errors)) {
        // Set user data in session (simulated registration)
        $_SESSION['user_id'] = rand(1000, 9999);
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['species'] = $species;
        $_SESSION['role'] = 'user';
        $_SESSION['theme'] = 'biopunk';
        $_SESSION['language'] = 'en';
        $_SESSION['avatar'] = $avatar;
        $_SESSION['logged_in'] = true;
        
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="biopunk">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register • IAstroMatch Biopunk</title>
  
  <!-- Biopunk CSS - Same as your dashboard -->
  <style>
    :root {
        --bio-dark: #102217ff;
        --bio-card: #2a413aff;
        --bio-green: rgba(255, 255, 255, 1);
        --bio-cyan: #3fcc8aff;
        --bio-mid: #2f8f6cff;
        --bio-light: #7A9C7D;
        --bio-text: #ffffffff;
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
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    
    .register-container {
        width: 100%;
        max-width: 500px;
    }
    
    .register-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .register-title {
        color: var(--bio-green);
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        text-shadow: 0 0 15px var(--bio-glow);
    }
    
    .register-subtitle {
        color: var(--bio-light);
        font-size: 1.1rem;
    }
    
    .register-card {
        background: linear-gradient(145deg, var(--bio-card), var(--bio-mid));
        border: 2px solid var(--bio-green);
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px var(--bio-glow);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    label {
        display: block;
        color: var(--bio-green);
        margin-bottom: 8px;
        font-weight: 600;
    }
    
    input, select {
        width: 100%;
        padding: 12px 15px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid var(--bio-mid);
        border-radius: 10px;
        color: var(--bio-text);
        font-size: 1rem;
    }
    
    input:focus, select:focus {
        outline: none;
        border-color: var(--bio-green);
    }
    
    .error-box {
        background: rgba(155, 42, 77, 0.2);
        border: 1px solid var(--bio-error);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        color: var(--bio-error);
    }
    
    .success-box {
        background: rgba(63, 204, 138, 0.2);
        border: 1px solid var(--bio-green);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        color: var(--bio-green);
        text-align: center;
    }
    
    .btn {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, var(--bio-green), var(--bio-cyan));
        color: var(--bio-dark);
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 10px;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px var(--bio-glow);
    }
    
    .login-link {
        text-align: center;
        margin-top: 20px;
        color: var(--bio-light);
    }
    
    .login-link a {
        color: var(--bio-green);
        text-decoration: none;
        font-weight: 600;
    }
    
    .species-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 10px;
    }
    
    .species-option {
        padding: 15px;
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid var(--bio-mid);
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
    }
    
    .species-option.selected {
        background: rgba(63, 204, 138, 0.2);
        border-color: var(--bio-green);
        color: var(--bio-green);
    }
    
    .avatar-options {
        display: flex;
        gap: 15px;
        margin-top: 10px;
        justify-content: center;
    }
    
    .avatar-option {
        font-size: 2rem;
        padding: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid var(--bio-mid);
        border-radius: 50%;
        cursor: pointer;
    }
    
    .avatar-option.selected {
        border-color: var(--bio-green);
        background: rgba(63, 204, 138, 0.2);
    }
    
    @media (max-width: 600px) {
        .species-options {
            grid-template-columns: 1fr;
        }
        
        .register-card {
            padding: 20px;
        }
    }
  </style>
</head>
<body>
  <div class="register-container">
    <!-- Header -->
    <div class="register-header">
      <h1 class="register-title">IAstro<span style="color: var(--bio-cyan);">Match</span></h1>
      <p class="register-subtitle">Register for biopunk compatibility matching</p>
    </div>
    
    <!-- Registration Card -->
    <div class="register-card">
      <?php if ($success): ?>
        <!-- Success Message -->
        <div class="success-box">
          <h3 style="margin-bottom: 10px;">🎉 Registration Successful!</h3>
          <p>Welcome to IAstroMatch, <?php echo htmlspecialchars($username); ?>!</p>
          <p style="margin-top: 10px;">Redirecting to dashboard...</p>
          <script>
            setTimeout(function() {
              window.location.href = 'dashboard.php';
            }, 2000);
          </script>
        </div>
      <?php else: ?>
        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
          <div class="error-box">
            <strong>⚠️ Registration Errors:</strong>
            <ul style="margin-top: 10px; padding-left: 20px;">
              <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        
        <!-- Registration Form -->
        <form method="POST" action="">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" 
                   id="username" 
                   name="username" 
                   value="<?php echo htmlspecialchars($username); ?>" 
                   required 
                   placeholder="Enter your username">
          </div>
          
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="<?php echo htmlspecialchars($email); ?>" 
                   required 
                   placeholder="your@email.com">
          </div>
          
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   required 
                   placeholder="Minimum 6 characters">
          </div>
          
          <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" 
                   id="confirm_password" 
                   name="confirm_password" 
                   required 
                   placeholder="Re-enter your password">
          </div>
          
          <div class="form-group">
            <label>Choose Your Species Type</label>
            <div class="species-options">
              <label class="species-option <?php echo ($species === 'Grafted') ? 'selected' : ''; ?>">
                <input type="radio" name="species" value="Grafted" 
                       <?php echo ($species === 'Grafted') ? 'checked' : 'checked'; ?> hidden>
                🧬 Grafted
              </label>
              <label class="species-option <?php echo ($species === 'Chloro-Humanoid') ? 'selected' : ''; ?>">
                <input type="radio" name="species" value="Chloro-Humanoid" 
                       <?php echo ($species === 'Chloro-Humanoid') ? 'checked' : ''; ?> hidden>
                🌿 Chloro-Humanoid
              </label>
              <label class="species-option <?php echo ($species === 'Gel-Form') ? 'selected' : ''; ?>">
                <input type="radio" name="species" value="Gel-Form" 
                       <?php echo ($species === 'Gel-Form') ? 'checked' : ''; ?> hidden>
                💧 Gel-Form
              </label>
              <label class="species-option <?php echo ($species === 'Mycelian') ? 'selected' : ''; ?>">
                <input type="radio" name="species" value="Mycelian" 
                       <?php echo ($species === 'Mycelian') ? 'checked' : ''; ?> hidden>
                🍄 Mycelian
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>Choose Your Avatar</label>
            <div class="avatar-options">
              <label class="avatar-option <?php echo ($avatar === 'spark') ? 'selected' : 'selected'; ?>">
                <input type="radio" name="avatar" value="spark" 
                       <?php echo ($avatar === 'spark') ? 'checked' : 'checked'; ?> hidden>
                ✨
              </label>
              <label class="avatar-option <?php echo ($avatar === 'dna') ? 'selected' : ''; ?>">
                <input type="radio" name="avatar" value="dna" 
                       <?php echo ($avatar === 'dna') ? 'checked' : ''; ?> hidden>
                🧬
              </label>
              <label class="avatar-option <?php echo ($avatar === 'robot') ? 'selected' : ''; ?>">
                <input type="radio" name="avatar" value="robot" 
                       <?php echo ($avatar === 'robot') ? 'checked' : ''; ?> hidden>
                🤖
              </label>
              <label class="avatar-option <?php echo ($avatar === 'alien') ? 'selected' : ''; ?>">
                <input type="radio" name="avatar" value="alien" 
                       <?php echo ($avatar === 'alien') ? 'checked' : ''; ?> hidden>
                👽
              </label>
            </div>
          </div>
          
          <button type="submit" class="btn">Create Biopunk Account</button>
        </form>
        
        <div class="login-link">
          Already have an account? <a href="login.php">Login here</a>
        </div>
      <?php endif; ?>
    </div>
    
    <!-- Footer -->
    <div style="text-align: center; margin-top: 30px; color: var(--bio-light); font-size: 0.9rem;">
      <p>IAstroMatch Biopunk Dating Platform • v1.0</p>
      <p style="margin-top: 5px;">Find compatible lifeforms across the galaxy</p>
    </div>
  </div>
  
  <script>
    // JavaScript for interactive selection
    document.querySelectorAll('.species-option').forEach(option => {
      option.addEventListener('click', function() {
        document.querySelectorAll('.species-option').forEach(opt => {
          opt.classList.remove('selected');
        });
        this.classList.add('selected');
        this.querySelector('input[type="radio"]').checked = true;
      });
    });
    
    document.querySelectorAll('.avatar-option').forEach(option => {
      option.addEventListener('click', function() {
        document.querySelectorAll('.avatar-option').forEach(opt => {
          opt.classList.remove('selected');
        });
        this.classList.add('selected');
        this.querySelector('input[type="radio"]').checked = true;
      });
    });
  </script>
</body>
</html>