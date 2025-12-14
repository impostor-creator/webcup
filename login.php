<?php
declare(strict_types=1);
require __DIR__ . '/db.php';

$errors = [];

if (!empty($_SESSION['user_id'])) {
  header("Location: dashboard.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim((string)($_POST['login'] ?? '')); // username OR email
  $pass  = (string)($_POST['password'] ?? '');

  if ($login === '' || $pass === '') {
    $errors[] = "Enter your username/email and password.";
  } else {
    $stmt = $pdo->prepare("SELECT id, username, email, password_hash FROM users WHERE username = ? OR email = ? LIMIT 1");
    $stmt->execute([$login, $login]);
    $u = $stmt->fetch();

    if (!$u || !password_verify($pass, $u['password_hash'])) {
      $errors[] = "Invalid credentials.";
    } else {
      $_SESSION['user_id'] = (int)$u['id'];
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
  <title>NovaSphere • Login</title>
  <meta name="color-scheme" content="dark" />
  <style>
    :root{
      --bg0:#05060a; --bg1:#070a12;
      --panel:rgba(12,16,28,.72);
      --stroke:rgba(120,170,255,.22);
      --text:#e7efff; --muted:rgba(231,239,255,.72);
      --neon:#62f6ff; --neon2:#b26bff; --danger:#ff4d6d;
      --shadow: 0 18px 60px rgba(0,0,0,.55);
    }
    *{box-sizing:border-box}
    body{
      margin:0; min-height:100vh; font-family: ui-sans-serif, system-ui, Segoe UI, Roboto, Arial;
      color:var(--text);
      background:
        radial-gradient(1000px 600px at 20% 10%, rgba(98,246,255,.10), transparent 55%),
        radial-gradient(900px 520px at 85% 20%, rgba(178,107,255,.12), transparent 60%),
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
      background: linear-gradient(120deg, rgba(98,246,255,.35), rgba(178,107,255,.35));
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
  </style>
</head>
<body>
<div class="grid"></div>

<div class="wrap">
  <div class="card">
    <div class="inner">
      <div class="brand">
        <h1>NovaSphere</h1>
        <div class="pill">SECURE LOGIN</div>
      </div>

      <h2 class="title">Welcome back</h2>
      <p class="subtitle">Login using your username or email. This uses your real database users table.</p>

      <?php if ($errors): ?>
        <div class="alert">
          <?= htmlspecialchars($errors[0]) ?>
        </div>
      <?php endif; ?>

      <form class="form" method="post" autocomplete="off" novalidate>
        <div class="field">
          <label>Username or Email</label>
          <input name="login" required value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" placeholder="username or email">
        </div>

        <div class="field">
          <label>Password</label>
          <input name="password" type="password" required placeholder="••••••••">
        </div>

        <button class="btn" type="submit">Login</button>

        <div class="foot">
          <span>No account yet?</span>
          <a href="register.php">Create one</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
