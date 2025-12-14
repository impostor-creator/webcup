<?php
// =========================================================
// CREATE ACCOUNT - IAstroMatch (Biopunk)
// =========================================================
// Real DB registration (SQLite). Run seed.php once first.

require_once __DIR__ . '/auth.php';

if (auth_check()) {
    header('Location: user.php');
    exit;
}

$error = '';
$success = '';
$username = '';
$email = '';
$species = 'Grafted';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');
    $species  = trim($_POST['species'] ?? 'Grafted');

    if ($username === '' || $email === '' || $password === '' || $confirm === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (auth_find_user($username) || auth_find_user($email)) {
        $error = 'Username or email already exists.';
    } else {
        try {
            auth_register($username, $email, $password, $species, 'user');
            $success = 'Account created successfully! Redirecting...';
            header('refresh:1;url=user.php');
        } catch (Throwable $e) {
            $error = 'Registration failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="biopunk">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Profile • IAstroMatch</title>
  
  <style>
    /* ============================================
       BIOPUNK CSS
    ============================================ */
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
      background: linear-gradient(135deg, var(--bio-dark) 0%, #1C3A2E 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      color: var(--bio-text);
    }
    
    /* Background cells */
    .bio-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      opacity: 0.1;
    }
    
    .bio-cell {
      position: absolute;
      border-radius: 50%;
      background: radial-gradient(circle, var(--bio-green), transparent);
      animation: float 25s infinite linear;
    }
    
    .bio-cell:nth-child(1) { width: 250px; height: 250px; top: 15%; left: 10%; animation-delay: 0s; }
    .bio-cell:nth-child(2) { width: 180px; height: 180px; top: 70%; right: 15%; animation-delay: -8s; }
    .bio-cell:nth-child(3) { width: 120px; height: 120px; bottom: 10%; left: 25%; animation-delay: -15s; }
    
    @keyframes float {
      0%, 100% { transform: translate(0, 0) rotate(0deg); }
      25% { transform: translate(30px, 20px) rotate(90deg); }
      50% { transform: translate(0, 40px) rotate(180deg); }
      75% { transform: translate(-30px, 20px) rotate(270deg); }
    }
    
    /* Register Container */
    .register-container {
      width: 100%;
      max-width: 500px;
      z-index: 10;
    }
    
    /* Register Card */
    .register-card {
      background: linear-gradient(145deg, var(--bio-card), var(--bio-mid));
      border: 2px solid var(--bio-green);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 20px 60px var(--bio-glow);
      position: relative;
      overflow: hidden;
    }
    
    .register-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--bio-green), var(--bio-cyan), var(--bio-green));
      animation: pulse-glow 3s infinite;
    }
    
    @keyframes pulse-glow {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.6; }
    }
    
    /* Header */
    .register-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .register-logo {
      font-size: 2.5rem;
      font-weight: 800;
      color: var(--bio-green);
      margin-bottom: 8px;
      text-shadow: 0 0 25px var(--bio-glow);
    }
    
    .register-tagline {
      color: var(--bio-cyan);
      font-size: 1rem;
      opacity: 0.9;
    }
    
    /* Alerts */
    .alert {
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 0.95rem;
    }
    
    .alert-error {
      background: rgba(155, 42, 77, 0.2);
      border: 1px solid var(--bio-error);
      color: #FF8E9E;
    }
    
    .alert-success {
      background: rgba(63, 166, 107, 0.2);
      border: 1px solid var(--bio-green);
      color: var(--bio-cyan);
    }
    
    /* Form */
    .register-form {
      margin-bottom: 25px;
    }
    
    .form-group {
      margin-bottom: 24px;
    }
    
    .form-label {
      display: block;
      color: var(--bio-cyan);
      margin-bottom: 8px;
      font-weight: 600;
      font-size: 0.95rem;
    }
    
    .form-input {
      width: 100%;
      padding: 14px 18px;
      background: rgba(28, 58, 46, 0.7);
      border: 1px solid var(--bio-green);
      border-radius: 12px;
      color: var(--bio-text);
      font-size: 1rem;
      transition: all 0.3s ease;
      outline: none;
    }
    
    .form-input:focus {
      border-color: var(--bio-cyan);
      box-shadow: 0 0 0 3px rgba(79, 179, 162, 0.2);
      transform: translateY(-2px);
    }
    
    .form-input::placeholder {
      color: var(--bio-light);
      opacity: 0.7;
    }
    
    /* Species Selector */
    .species-selector {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      margin-top: 10px;
    }
    
    @media (max-width: 480px) {
      .species-selector {
        grid-template-columns: 1fr;
      }
    }
    
    .species-option {
      background: rgba(28, 58, 46, 0.7);
      border: 1px solid var(--bio-green);
      border-radius: 10px;
      padding: 15px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: center;
    }
    
    .species-option:hover {
      background: rgba(63, 166, 107, 0.2);
      transform: translateY(-2px);
    }
    
    .species-option.selected {
      background: var(--bio-green);
      border-color: var(--bio-cyan);
      box-shadow: 0 0 15px rgba(63, 166, 107, 0.5);
    }
    
    .species-option input {
      display: none;
    }
    
    .species-name {
      color: var(--bio-text);
      font-weight: 600;
      font-size: 0.95rem;
      display: block;
    }
    
    .species-desc {
      color: var(--bio-light);
      font-size: 0.8rem;
      margin-top: 4px;
      display: block;
    }
    
    /* Password strength */
    .password-strength {
      height: 4px;
      background: rgba(28, 58, 46, 0.5);
      border-radius: 2px;
      margin-top: 8px;
      overflow: hidden;
    }
    
    .strength-bar {
      height: 100%;
      width: 0%;
      border-radius: 2px;
      transition: width 0.3s ease;
    }
    
    /* Register Button */
    .register-button {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, var(--bio-green), var(--bio-cyan));
      border: none;
      border-radius: 12px;
      color: var(--bio-dark);
      font-size: 1.1rem;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
    }
    
    .register-button:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 30px rgba(63, 166, 107, 0.5);
    }
    
    /* Links */
    .form-links {
      display: flex;
      justify-content: space-between;
      margin-top: 25px;
      padding-top: 25px;
      border-top: 1px solid rgba(63, 166, 107, 0.2);
    }
    
    .form-link {
      color: var(--bio-cyan);
      text-decoration: none;
      font-size: 0.95rem;
      transition: all 0.3s ease;
    }
    
    .form-link:hover {
      color: var(--bio-text);
      text-decoration: underline;
    }
    
    /* Requirements */
    .requirements {
      margin-top: 20px;
      padding: 15px;
      background: rgba(28, 58, 46, 0.4);
      border-radius: 10px;
      border: 1px solid rgba(63, 166, 107, 0.3);
    }
    
    .requirements-title {
      color: var(--bio-warning);
      font-size: 0.9rem;
      margin-bottom: 10px;
    }
    
    .requirements-list {
      color: var(--bio-light);
      font-size: 0.85rem;
      list-style: none;
      padding-left: 0;
    }
    
    .requirements-list li {
      margin-bottom: 5px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .requirements-list li::before {
      content: '•';
      color: var(--bio-cyan);
    }
    
    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .register-card {
      animation: fadeIn 0.6s ease-out;
    }
  </style>
</head>

<body>
  <!-- Background -->
  <div class="bio-bg">
    <div class="bio-cell"></div>
    <div class="bio-cell"></div>
    <div class="bio-cell"></div>
  </div>
  
  <!-- Register Container -->
  <div class="register-container">
    <div class="register-card">
      <!-- Header -->
      <div class="register-header">
        <h1 class="register-logo">IAstroMatch</h1>
        <p class="register-tagline">Create Your Biological Profile</p>
      </div>
      
      <!-- Alerts -->
      <?php if ($error): ?>
        <div class="alert alert-error">
          ⚠️ <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>
      
      <?php if ($success): ?>
        <div class="alert alert-success">
          ✅ <?php echo htmlspecialchars($success); ?>
        </div>
      <?php endif; ?>
      
      <!-- Registration Form -->
      <form method="post" class="register-form" id="registerForm">
        <!-- Username -->
        <div class="form-group">
          <label class="form-label" for="username">Username</label>
          <input type="text" 
                 id="username" 
                 name="username" 
                 class="form-input" 
                 required 
                 value="<?php echo htmlspecialchars($username); ?>"
                 placeholder="Choose a unique username"
                 autocomplete="username">
        </div>
        
        <!-- Email -->
        <div class="form-group">
          <label class="form-label" for="email">Email</label>
          <input type="email" 
                 id="email" 
                 name="email" 
                 class="form-input" 
                 required 
                 value="<?php echo htmlspecialchars($email); ?>"
                 placeholder="your@email.com"
                 autocomplete="email">
        </div>
        
        <!-- Password -->
        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <input type="password" 
                 id="password" 
                 name="password" 
                 class="form-input" 
                 required 
                 placeholder="Create a strong password"
                 autocomplete="new-password">
          <div class="password-strength">
            <div class="strength-bar" id="strengthBar"></div>
          </div>
        </div>
        
        <!-- Confirm Password -->
        <div class="form-group">
          <label class="form-label" for="confirm_password">Confirm Password</label>
          <input type="password" 
                 id="confirm_password" 
                 name="confirm_password" 
                 class="form-input" 
                 required 
                 placeholder="Repeat your password"
                 autocomplete="new-password">
        </div>
        
        <!-- Species Selection -->
        <div class="form-group">
          <label class="form-label">Select Your Biological Form</label>
          <div class="species-selector" id="speciesSelector">
            <?php
            $species_options = [
              'Grafted' => 'Bio-Mechanical Hybrid',
              'Chloro-Humanoid' => 'Photosynthetic Being',
              'Gel-Form' => 'Adaptive Amoeboid',
              'Mycelian' => 'Fungal Intelligence',
              'Bone-Crafter' => 'Calcified Being',
              'Pheromone-Type' => 'Chemical Communicator'
            ];
            
            foreach ($species_options as $species_id => $description):
            ?>
            <label class="species-option <?php echo ($species === $species_id) ? 'selected' : ''; ?>">
              <input type="radio" 
                     name="species" 
                     value="<?php echo $species_id; ?>"
                     <?php echo ($species === $species_id) ? 'checked' : ''; ?>>
              <span class="species-name"><?php echo $species_id; ?></span>
              <span class="species-desc"><?php echo $description; ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        
        <!-- Requirements -->
        <div class="requirements">
          <div class="requirements-title">Profile Requirements:</div>
          <ul class="requirements-list">
            <li>Username: 3-50 characters</li>
            <li>Password: Minimum 6 characters</li>
            <li>Valid email address</li>
            <li>Select your biological form</li>
          </ul>
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="register-button">
          Create Biological Profile
        </button>
      </form>
      
      <!-- Links -->
      <div class="form-links">
        <a href="login.php" class="form-link">Already have an account? Login</a>
        <a href="index.php" class="form-link">Back to Home</a>
      </div>
    </div>
  </div>
  
  <script>
    // Species selection
    document.querySelectorAll('.species-option').forEach(option => {
      option.addEventListener('click', function() {
        // Remove selected class from all options
        document.querySelectorAll('.species-option').forEach(opt => {
          opt.classList.remove('selected');
        });
        
        // Add selected class to clicked option
        this.classList.add('selected');
        
        // Check the radio button
        const radio = this.querySelector('input[type="radio"]');
        if (radio) {
          radio.checked = true;
        }
      });
    });
    
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('strengthBar');
    
    passwordInput.addEventListener('input', function() {
      const password = this.value;
      let strength = 0;
      
      // Length check
      if (password.length >= 6) strength += 20;
      if (password.length >= 8) strength += 20;
      
      // Complexity checks
      if (/[A-Z]/.test(password)) strength += 20;
      if (/[0-9]/.test(password)) strength += 20;
      if (/[^A-Za-z0-9]/.test(password)) strength += 20;
      
      // Update strength bar
      strengthBar.style.width = strength + '%';
      
      // Color based on strength
      if (strength < 40) {
        strengthBar.style.background = '#9B2A4D'; // Red
      } else if (strength < 80) {
        strengthBar.style.background = '#9BAA4D'; // Yellow
      } else {
        strengthBar.style.background = '#3FA66B'; // Green
      }
    });
    
    // Password confirmation check
    const confirmInput = document.getElementById('confirm_password');
    
    function checkPasswordMatch() {
      const password = passwordInput.value;
      const confirm = confirmInput.value;
      
      if (confirm === '') return;
      
      if (password !== confirm) {
        confirmInput.style.borderColor = '#9B2A4D';
        confirmInput.style.boxShadow = '0 0 0 3px rgba(155, 42, 77, 0.2)';
      } else {
        confirmInput.style.borderColor = '#3FA66B';
        confirmInput.style.boxShadow = '0 0 0 3px rgba(63, 166, 107, 0.2)';
      }
    }
    
    confirmInput.addEventListener('input', checkPasswordMatch);
    passwordInput.addEventListener('input', checkPasswordMatch);
    
    // Form validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      const username = document.getElementById('username').value.trim();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();
      const confirm = document.getElementById('confirm_password').value.trim();
      const speciesSelected = document.querySelector('input[name="species"]:checked');
      
      // Basic validation
      if (!username || !email || !password || !confirm) {
        e.preventDefault();
        alert('Please fill in all required fields.');
        return;
      }
      
      if (!speciesSelected) {
        e.preventDefault();
        alert('Please select your biological form.');
        return;
      }
      
      if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match.');
        return;
      }
      
      if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long.');
        return;
      }
      
      if (username.length < 3) {
        e.preventDefault();
        alert('Username must be at least 3 characters long.');
        return;
      }
      
      // Email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return;
      }
    });
    
    // Auto-focus on username field
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('username').focus();
    });
  </script>
</body>
</html>