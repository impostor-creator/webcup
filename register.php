<?php
session_start();
// Simple login simulation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = $_POST['username'] ?? 'BioPunkUser';
    $_SESSION['email'] = $_POST['email'] ?? 'user@iastromatch.bio';
    $_SESSION['species'] = 'Grafted';
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - IAstroMatch</title>
    <style>
        body { background: #0a1f1a; color: white; font-family: Arial; }
        .login-box { max-width: 400px; margin: 100px auto; padding: 30px; background: #1a3a2e; border: 2px solid #3fcc8a; border-radius: 10px; }
        input, button { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; }
        button { background: #3fcc8a; color: black; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔬 IAstroMatch Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Login to Dashboard</button>
        </form>
        <p style="color: #aaa; margin-top: 20px;">Demo: Just click login to continue</p>
    </div>
</body>
</html>