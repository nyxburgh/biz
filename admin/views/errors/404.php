<!DOCTYPE html>
<html><head><title>404 — BizGuide</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>body{background:#f4f3fb;display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:'Segoe UI',sans-serif}</style>
</head><body>
<div class="text-center p-5">
  <div style="font-size:5rem;font-weight:900;color:#7c3aed;line-height:1">404</div>
  <h5 class="mt-2 mb-1" style="color:#1e1245">Page Not Found</h5>
  <p class="text-muted small mb-4">The page you're looking for doesn't exist.</p>
  <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>/admin/dashboard"
     class="btn" style="background:#7c3aed;color:#fff;border-radius:9px;padding:9px 22px">
    <i class="bi bi-house me-2"></i>Back to Dashboard
  </a>
</div>
</body></html>
