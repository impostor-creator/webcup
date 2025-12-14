<?php
declare(strict_types=1);
require __DIR__ . '/db.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim((string)($_POST['username'] ?? ''));
  $email    = trim((string)($_POST['email'] ?? ''));
  $pass1    = (string)($_POST['password'] ?? '');
  $pass2    = (string)($_POST['password_confirm'] ?? '');

  // ✅ Requirements (normal + secure)
  if ($username === '' || strlen($username) < 3 || strlen($username) > 50) $errors[] = "Username must be 3–50 characters.";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Enter a valid email address.";
  if (strlen($pass1) < 8) $errors[] = "Password must be at least 8 characters.";
  if (!preg_match('/[A-Z]/', $pass1)) $errors[] = "Password must contain at least one uppercase letter.";
  if (!preg_match('/[a-z]/', $pass1)) $errors[] = "Password must contain at least one lowercase letter.";
  if (!preg_match('/[0-9]/', $pass1)) $errors[] = "Password must contain at least one number.";
  if ($pass1 !== $pass2) $errors[] = "Passwords do not match.";

  if (!$errors) {
    // Check unique username/email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
      $errors[] = "Username or email already exists.";
    } else {
      $hash = password_hash($pass1, PASSWORD_DEFAULT);
      $ins = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
      $ins->execute([$username, $email, $hash]);

      // Auto-login after register
      $_SESSION['user_id'] = (int)$pdo->lastInsertId();
      header("Location: dashboard.php");
      exit;
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>NovaSphere • Register</title>
  <meta name="color-scheme" content="dark" />
  <style>
    :root{
      --bg0:#05060a; --bg1:#070a12;
      --panel:rgba(12,16,28,.72);
      --stroke:rgba(120,170,255,.22);
      --text:#e7efff; --muted:rgba(231,239,255,.72);
      --neon:#62f6ff; --neon2:#b26bff; --danger:#ff4d6d; --ok:#39ff88;
      --shadow: 0 18px 60px rgba(0,0,0,.55);
    }
    *{box-sizing:border-box}
    body{
      margin:0; min-height:100vh; font-family: ui-sans-serif, system-ui, Segoe UI, Roboto, Arial;
      color:var(--text);
      background:
        radial-gradient(1000px 600px at 20% 10%, rgba(98,246,255,.10), transparent 55%),
        radial-gradient(900px 520px at 85% 20%, rgba(178,107,255,.12), transparent 60%),
        radial-gradient(900px 520px at 60% 90%, rgba(57,255,136,.08), transparent 60%),
        linear-gradient(180deg, var(--bg0), var(--bg1));
      overflow-x:hidden;
    }
    .grid{
      position:fixed; inset:0; pointer-events:none; opacity:.18;
      background-image: linear-gradient(to right, rgba(98,246,255,.08) 1px, transparent 1px),
                        linear-gradient(to bottom, rgba(178,107,255,.08) 1px, transparent 1px);
      background-size: 48px 48px;
      mask-image: radial-gradient(circle at 50% 40%, black 0 52%, transparent 74%);
    }
    .wrap{min-height:100vh; display:flex; align-items:center; justify-content:center; padding:28px}
    .card{
      width:min(520px, 100%);
      background:var(--panel);
      border:1px solid var(--stroke);
      border-radius:18px;
      box-shadow:var(--shadow);
      position:relative;
      overflow:hidden;
    }
    .card:before{
      content:""; position:absolute; inset:-2px;
      background: linear-gradient(120deg, rgba(98,246,255,.35), rgba(178,107,255,.35), rgba(57,255,136,.25));
      filter: blur(22px); opacity:.35;
      z-index:0;
    }
    .inner{position:relative; z-index:1; padding:22px 22px 18px}
    .brand{
      display:flex; align-items:center; justify-content:space-between; gap:12px;
      padding-bottom:14px; margin-bottom:14px;
      border-bottom:1px solid rgba(120,170,255,.18);
    }
    .brand h1{margin:0; font-size:18px; letter-spacing:.5px}
    .brand .pill{
      font-size:12px; color:var(--muted);
      padding:6px 10px; border:1px solid rgba(98,246,255,.25);
      border-radius:999px;
      background:rgba(8,12,20,.5);
    }
    .title{margin:0 0 6px; font-size:26px}
    .subtitle{margin:0 0 16px; color:var(--muted); font-size:13px; line-height:1.4}
    .alert{
      border-radius:12px; padding:12px 12px; margin:0 0 12px;
      border:1px solid rgba(255,77,109,.35);
      background: rgba(255,77,109,.10);
      color: #ffd2da;
      font-size:13px;
    }
    .form{display:grid; gap:12px}
    label{font-size:12px; color:var(--muted)}
    .field{display:grid; gap:6px}
    input{
      width:100%;
      padding:12px 12px;
      border-radius:12px;
      border:1px solid rgba(120,170,255,.25);
      outline:none;
      background: rgba(6,9,16,.72);
      color: var(--text);
      transition: .18s border, .18s box-shadow;
    }
    input:focus{
      border-color: rgba(98,246,255,.55);
      box-shadow: 0 0 0 3px rgba(98,246,255,.12);
    }
    .row2{display:grid; grid-template-columns:1fr 1fr; gap:10px}
    @media (max-width:520px){ .row2{grid-template-columns:1fr} }
    .btn{
      cursor:pointer;
      border:none;
      border-radius:14px;
      padding:12px 14px;
      font-weight:700;
      color:#061018;
      background: linear-gradient(90deg, var(--neon), var(--neon2));
      box-shadow: 0 10px 30px rgba(98,246,255,.18);
      transition: transform .12s ease, filter .12s ease;
    }
    .btn:hover{transform: translateY(-1px); filter:saturate(1.05)}
    .foot{
      display:flex; justify-content:space-between; align-items:center; gap:10px;
      margin-top:10px; padding-top:14px;
      border-top:1px solid rgba(120,170,255,.18);
      color:var(--muted); font-size:12px;
    }
    a{color:var(--neon); text-decoration:none}
    a:hover{text-decoration:underline}
    .hint{font-size:12px; color:rgba(231,239,255,.70); margin-top:6px}
  </style>
</head>
<body>
<div class="grid"></div>

<div class="wrap">
  <div class="card">
    <div class="inner">
      <div class="brand">
        <h1>NovaSphere</h1>
        <div class="pill">SECURE REGISTER</div>
      </div>

      <h2 class="title">Create your account</h2>
      <p class="subtitle">Enter your details. This will create a real user row in your MySQL database.</p>

      <?php if ($errors): ?>
        <div class="alert">
          <strong>Fix these:</strong>
          <ul style="margin:8px 0 0; padding-left:18px;">
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form class="form" method="post" autocomplete="off" novalidate>
        <div class="field">
          <label>Username</label>
          <input name="username" maxlength="50" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" placeholder="e.g. OperatorR17">
        </div>

        <div class="field">
          <label>Email</label>
          <input name="email" type="email" maxlength="120" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="you@domain.com">
        </div>

        <div class="row2">
          <div class="field">
            <label>Password</label>
            <input name="password" type="password" required placeholder="Minimum 8 chars">
          </div>
          <div class="field">
            <label>Confirm password</label>
            <input name="password_confirm" type="password" required placeholder="Repeat password">
          </div>
        </div>

        <button href="login.php" class="btn" type="submit">Register</button>

        <div class="hint">
          Password must include: uppercase, lowercase, number, and 8+ characters.
        </div>

        <div class="foot">
          <span>Already have an account?</span>
          <a href="login.php">Login</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
