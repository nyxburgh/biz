<?php
/**
 * BizGuide — First-Run Admin Setup
 * ─────────────────────────────────
 * 1. Visit /admin/setup.php in your browser
 * 2. Create your superadmin account
 * 3. DELETE this file immediately after
 * No use of this file once setup completed on project start
 * 
 * /
define('ROOT',      dirname(__DIR__));
define('BASE_PATH', ROOT);

require_once ROOT . '/config/config.php';
require_once ROOT . '/core/Database.php';
require_once ROOT . '/core/Auth.php';

$msg  = '';
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password']   ?? '';

    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 8) {
        $msg  = 'All fields required. Password minimum 8 characters.';
        $type = 'danger';
    } elseif (Database::fetchOne("SELECT id FROM admins WHERE email = ?", [$email])) {
        $msg  = 'That email is already registered.';
        $type = 'danger';
    } else {
        Database::execute(
            "INSERT INTO admins (name, email, password, role) VALUES (?, ?, ?, 'superadmin')",
            [$name, $email, Auth::hashPassword($pass)]
        );
        $msg  = '✓ Admin created successfully! <a href="' . BASE_URL . '/admin/login" style="color:#fff;font-weight:700;text-decoration:underline">Login now</a> — then delete this file.';
        $type = 'success';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>BizGuide — Setup</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{background:linear-gradient(135deg,#2d1b69,#7c3aed);min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',sans-serif}
.box{background:#fff;border-radius:18px;padding:44px 40px;width:100%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,.4)}
.form-control{border-color:#e5e0ff;border-radius:9px;padding:11px 14px}
.form-control:focus{border-color:#7c3aed;box-shadow:0 0 0 3px rgba(124,58,237,.15)}
.input-group-text{border-color:#e5e0ff;background:#faf8ff}
</style>
</head>
<body>
<div class="box">
  <div class="text-center mb-4">
    <div style="width:60px;height:60px;background:linear-gradient(135deg,#7c3aed,#2d1b69);border-radius:15px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px">
      <i class="bi bi-compass text-white fs-3"></i>
    </div>
    <h4 class="fw-800 mb-1" style="color:#1e1245">BizGuide Setup</h4>
    <small class="text-muted">Create your superadmin account</small>
  </div>

  <?php if ($msg): ?>
    <div class="alert alert-<?= $type ?> mb-3"><?= $msg ?></div>
  <?php endif ?>

  <form method="POST">
    <div class="mb-3">
      <label class="form-label fw-600 small">Full Name</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-person text-muted"></i></span>
        <input type="text" name="name" class="form-control" required placeholder="Your Name">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label fw-600 small">Email Address</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
        <input type="email" name="email" class="form-control" required placeholder="admin@bizguide.com">
      </div>
    </div>
    <div class="mb-4">
      <label class="form-label fw-600 small">Password <small class="text-muted">(min 8 characters)</small></label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
        <input type="password" name="password" class="form-control" required minlength="8" placeholder="••••••••">
      </div>
    </div>
    <button type="submit" class="btn w-100 fw-700 py-2" style="background:linear-gradient(135deg,#7c3aed,#2d1b69);color:#fff;border-radius:9px">
      <i class="bi bi-person-plus me-2"></i>Create Admin Account
    </button>
  </form>
  <p class="text-danger text-center mt-3 small fw-600">
    <i class="bi bi-exclamation-triangle me-1"></i>Delete this file after setup!
  </p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
