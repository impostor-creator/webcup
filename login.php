<!DOCTYPE html>
<html lang="en" data-theme="biopunk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login • IAstroMatch</title>
    
    <!-- Biopunk CSS -->
    <style>
        /* ============================================
           BIOPUNK CSS VARIABLES
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
        
        /* ============================================
           RESET & BASE STYLES
        ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            background: var(--bio-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--bio-text);
            position: relative;
            overflow-x: hidden;
        }
        
        /* ============================================
           BACKGROUND EFFECTS
        ============================================ */
        .bio-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.15;
        }
        
        .bio-cell {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, var(--bio-green), transparent);
            animation: float 20s infinite linear;
        }
        
        .bio-cell:nth-child(1) {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }
        
        .bio-cell:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 60%;
            right: 10%;
            animation-delay: -5s;
        }
        
        .bio-cell:nth-child(3) {
            width: 150px;
            height: 150px;
            bottom: 20%;
            left: 20%;
            animation-delay: -10s;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(20px, 20px) rotate(90deg); }
            50% { transform: translate(0, 40px) rotate(180deg); }
            75% { transform: translate(-20px, 20px) rotate(270deg); }
        }
        
        /* ============================================
           LOGIN CONTAINER
        ============================================ */
        .login-container {
            width: 100%;
            max-width: 420px;
            z-index: 10;
        }
        
        /* ============================================
           LOGIN CARD
        ============================================ */
        .login-card {
            background: linear-gradient(145deg, var(--bio-card), var(--bio-mid));
            border: 2px solid var(--bio-green);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px var(--bio-glow);
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--bio-green), var(--bio-cyan), var(--bio-green));
            animation: pulse-glow 4s infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        /* ============================================
           LOGIN HEADER
        ============================================ */
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-logo {
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--bio-green);
            margin-bottom: 8px;
            text-shadow: 0 0 25px var(--bio-glow);
            letter-spacing: -1px;
        }
        
        .login-tagline {
            color: var(--bio-cyan);
            font-size: 1rem;
            opacity: 0.9;
        }
        
        /* ============================================
           FORM STYLES
        ============================================ */
        .login-form {
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
        
        /* ============================================
           LOGIN BUTTON
        ============================================ */
        .login-button {
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
            position: relative;
            overflow: hidden;
        }
        
        .login-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(63, 166, 107, 0.5);
        }
        
        .login-button:active {
            transform: translateY(-1px);
        }
        
        .login-button::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }
        
        .login-button:hover::after {
            left: 100%;
        }
        
        /* ============================================
           FORM LINKS
        ============================================ */
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
            position: relative;
        }
        
        .form-link:hover {
            color: var(--bio-text);
        }
        
        .form-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--bio-cyan);
            transition: width 0.3s ease;
        }
        
        .form-link:hover::after {
            width: 100%;
        }
        
        /* ============================================
           DEMO ACCOUNTS
        ============================================ */
        .demo-section {
            margin-top: 30px;
            padding: 20px;
            background: rgba(28, 58, 46, 0.4);
            border-radius: 12px;
            border: 1px solid rgba(63, 166, 107, 0.3);
        }
        
        .demo-title {
            color: var(--bio-warning);
            font-size: 0.95rem;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .demo-title::before {
            content: '🧬';
            font-size: 1.1rem;
        }
        
        .demo-account {
            color: var(--bio-text);
            font-size: 0.9rem;
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .demo-account code {
            background: rgba(63, 166, 107, 0.2);
            padding: 4px 10px;
            border-radius: 6px;
            color: var(--bio-cyan);
            font-family: 'SF Mono', 'Cascadia Code', monospace;
            font-size: 0.85rem;
            flex: 1;
        }
        
        .demo-note {
            color: var(--bio-light);
            font-size: 0.85rem;
            margin-top: 12px;
            font-style: italic;
            line-height: 1.5;
        }
        
        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
            
            .login-logo {
                font-size: 2.2rem;
            }
            
            .form-links {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .demo-account {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .demo-account code {
                width: 100%;
            }
        }
        
        /* ============================================
           ANIMATIONS
        ============================================ */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-card {
            animation: fadeIn 0.6s ease-out;
        }
        
        /* ============================================
           FORM VALIDATION STYLES
        ============================================ */
        .form-input:invalid {
            border-color: var(--bio-error);
        }
        
        .form-input:valid {
            border-color: var(--bio-green);
        }
        
        /* ============================================
           PASSWORD TOGGLE (OPTIONAL)
        ============================================ */
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--bio-light);
            cursor: pointer;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Background Cells -->
    <div class="bio-bg">
        <div class="bio-cell"></div>
        <div class="bio-cell"></div>
        <div class="bio-cell"></div>
    </div>
    
    <!-- Main Login Container -->
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <h1 class="login-logo">IAstroMatch</h1>
                <p class="login-tagline">Biological Compatibility Interface</p>
            </div>
            
            <!-- Login Form -->
            <form class="login-form" id="loginForm">
                <!-- Username/Email Field -->
                <div class="form-group">
                    <label class="form-label" for="username">Username or Email</label>
                    <input type="text" 
                           id="username" 
                           class="form-input" 
                           required 
                           placeholder="Enter username or email"
                           autocomplete="username">
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" 
                               id="password" 
                               class="form-input" 
                               required 
                               placeholder="Enter your password"
                               autocomplete="current-password">
                        <button type="button" class="toggle-password" id="togglePassword">
                            👁️
                        </button>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="login-button">
                    Initiate Neural Connection
                </button>
            </form>
            
            <!-- Form Links -->
            <div class="form-links">
                <a href="register.php" class="form-link">Create Biological Profile</a>
                <a href="index.php" class="form-link">Return to Home</a>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
        });
        
        // Form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            // Basic validation
            if (!username || !password) {
                alert('Please fill in both username and password fields.');
                return;
            }
            
            // Simple authentication (for demo)
            const demoUsers = {
                'xenomorph_researcher': 'bio123',
                'sun_seeker': 'chloro123',
                'gel_wanderer': 'amoeba123',
                'admin': 'admin123',
                'user': 'user123'
            };
            
            if (demoUsers[username] === password) {
                // Success - store in sessionStorage for demo
                sessionStorage.setItem('loggedIn', 'true');
                sessionStorage.setItem('username', username);
                sessionStorage.setItem('species', 'Grafted'); // Default
                
                // Redirect to user page
                window.location.href = 'user.html';
            } else {
                alert('Invalid credentials. Use demo accounts listed below.');
            }
        });
        
        // Auto-focus on username field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
        
        // Add floating particles on mouse move
        document.addEventListener('mousemove', function(e) {
            const particle = document.createElement('div');
            particle.style.position = 'fixed';
            particle.style.width = '4px';
            particle.style.height = '4px';
            particle.style.backgroundColor = 'var(--bio-green)';
            particle.style.borderRadius = '50%';
            particle.style.left = e.clientX + 'px';
            particle.style.top = e.clientY + 'px';
            particle.style.pointerEvents = 'none';
            particle.style.zIndex = '5';
            particle.style.opacity = '0.7';
            document.body.appendChild(particle);
            
            // Remove particle after animation
            setTimeout(() => {
                particle.style.transition = 'all 1s ease';
                particle.style.opacity = '0';
                particle.style.transform = 'translateY(-20px)';
                setTimeout(() => particle.remove(), 1000);
            }, 100);
        });
    </script>
</body>
</html>