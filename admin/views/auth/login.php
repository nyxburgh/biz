<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login — BizGuide</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{background:linear-gradient(135deg,#2d1b69 0%,#1e1245 50%,#7c3aed 100%);
     min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',sans-serif}
.box{background:#fff;border-radius:18px;padding:44px 40px;width:100%;max-width:390px;
     box-shadow:0 20px 60px rgba(0,0,0,.4)}
.logo-icon{width:64px;height:64px;background:linear-gradient(135deg,#7c3aed,#2d1b69);
           border-radius:16px;display:inline-flex;align-items:center;justify-content:center}
.form-control{border-color:#e5e0ff;border-radius:9px;padding:11px 14px}
.form-control:focus{border-color:#7c3aed;box-shadow:0 0 0 3px rgba(124,58,237,.15)}
.input-group-text{border-color:#e5e0ff;background:#faf8ff}
.btn-login{background:linear-gradient(135deg,#7c3aed,#2d1b69);color:#fff;
           border:none;border-radius:9px;padding:12px;font-weight:700;width:100%;font-size:.95rem}
.btn-login:hover{opacity:.9;color:#fff}
</style>
</head>
<body>
<div class="box">
  <div class="text-center mb-4">
    <div class="logo-icon mb-3">
      <i class="bi bi-compass text-white" style="font-size:1.7rem"></i>
    </div>
    <h4 class="fw-800 mb-1" style="color:#1e1245">BizGuide Admin</h4>
    <small class="text-muted">Sign in to continue</small>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-danger py-2 rounded-3 mb-3 small">
      <i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?>
    </div>
  <?php endif ?>

  <form method="POST" action="">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <div class="mb-3">
      <label class="form-label fw-600 small">Email Address</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
        <input type="email" name="email" class="form-control" placeholder="admin@bizguide.com" required autofocus>
      </div>
    </div>
    <div class="mb-4">
      <label class="form-label fw-600 small">Password</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
      </div>
    </div>
    <button type="submit" class="btn btn-login">
      <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
    </button>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
