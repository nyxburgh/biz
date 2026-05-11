<?php
$admin           = Auth::user();
$pageTitle       = $pageTitle ?? 'Admin';
$successMsg      = Helper::getFlash('success');
$errorMsg        = Helper::getFlash('error');
$infoMsg         = Helper::getFlash('info');
$pendingListings = (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM business_listings WHERE status='pending'")['c'] ?? 0);
$pendingPayments = (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM payments WHERE status='pending'")['c'] ?? 0);
$pendingSuggs    = (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM keyword_suggestions WHERE status='pending'")['c'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($pageTitle) ?> — BizGuide Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root{--purple:#7c3aed;--purple-dark:#2d1b69;--purple-darker:#1e1245;--sw:256px;--th:60px}
body{margin:0;font-family:'Segoe UI',sans-serif;background:#f4f3fb;color:#1e1245}
/* Sidebar */
#sb{position:fixed;top:0;left:0;width:var(--sw);height:100vh;
    background:linear-gradient(180deg,var(--purple-dark),var(--purple-darker));
    display:flex;flex-direction:column;z-index:1050;overflow:hidden;
    transition:transform .25s ease}
#sb .brand{padding:18px 20px;border-bottom:1px solid rgba(255,255,255,.1);flex-shrink:0}
#sb .brand h4{color:#fff;margin:0;font-size:1.05rem;font-weight:800;letter-spacing:.3px}
#sb .brand small{color:#a78bfa;font-size:.7rem}
#sb nav{flex:1;overflow-y:auto;padding:6px 0}
#sb nav::-webkit-scrollbar{width:3px}
#sb nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.2);border-radius:3px}
#sb nav a{display:flex;align-items:center;gap:10px;color:rgba(255,255,255,.65);
          text-decoration:none;padding:9px 20px;font-size:.855rem;
          border-left:3px solid transparent;transition:.15s}
#sb nav a:hover,#sb nav a.active{color:#fff;background:rgba(255,255,255,.09);border-left-color:#a78bfa}
#sb nav a i{width:16px;text-align:center;font-size:.95rem;flex-shrink:0}
#sb nav .sect{padding:12px 20px 3px;font-size:.66rem;text-transform:uppercase;
              letter-spacing:1.3px;color:rgba(255,255,255,.28);font-weight:600}
#sb .foot{padding:10px 16px;border-top:1px solid rgba(255,255,255,.1);flex-shrink:0}
#sb .foot a{display:flex;align-items:center;gap:9px;color:rgba(255,255,255,.5);
            text-decoration:none;font-size:.82rem;padding:6px 4px;transition:.15s}
#sb .foot a:hover{color:#fff}
/* Topbar */
#tb{position:fixed;top:0;left:var(--sw);right:0;height:var(--th);
    background:#fff;border-bottom:1px solid #e5e0ff;display:flex;
    align-items:center;justify-content:space-between;padding:0 22px;
    z-index:900;box-shadow:0 1px 4px rgba(124,58,237,.07);transition:left .25s ease}
#tb .pt{font-weight:700;font-size:.96rem;color:var(--purple-darker)}
#tb .tr{display:flex;align-items:center;gap:10px}
#tb .av{width:32px;height:32px;border-radius:50%;background:var(--purple);
        display:flex;align-items:center;justify-content:center;color:#fff;
        font-weight:800;font-size:.8rem}
#hbg{display:none;background:none;border:none;color:var(--purple-darker);font-size:1.3rem;
     padding:4px 8px;cursor:pointer;margin-right:6px}
/* Overlay */
#sb-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1040}
/* Main */
#main{margin-left:var(--sw);margin-top:var(--th);padding:24px;min-height:calc(100vh - var(--th));transition:margin-left .25s ease}
/* Mobile */
@media(max-width:768px){
  #sb{transform:translateX(-100%)}
  #sb.open{transform:translateX(0)}
  #sb-overlay.open{display:block}
  #tb{left:0}
  #main{margin-left:0;padding:16px}
  #hbg{display:inline-flex;align-items:center;justify-content:center}
  .stat-card .val{font-size:1.2rem}
  .filter-bar{padding:10px}
}
/* Stat card */
.stat-card{border:none;border-radius:12px;box-shadow:0 2px 10px rgba(124,58,237,.07);
           padding:18px 20px;display:flex;align-items:center;gap:14px;background:#fff}
.stat-card .ib{width:46px;height:46px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0}
.stat-card .val{font-size:1.55rem;font-weight:800;line-height:1}
.stat-card .lbl{font-size:.76rem;color:#6b7280;margin-top:2px}
/* Card */
.card{border:none;border-radius:11px;box-shadow:0 2px 8px rgba(124,58,237,.07)}
.ch{background:linear-gradient(135deg,var(--purple),var(--purple-dark));color:#fff;
    border-radius:11px 11px 0 0;padding:13px 18px;font-weight:600;font-size:.9rem}
.ch .btn-ghost{background:rgba(255,255,255,.15);color:#fff;border:none;
               font-size:.8rem;padding:4px 12px;border-radius:6px}
/* Table */
.table thead th{font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;
                color:#6b7280;border-color:#e5e0ff;font-weight:600;white-space:nowrap}
.table-hover tbody tr:hover{background:#f8f7ff}
/* Filter bar */
.filter-bar{background:#fff;border-radius:10px;padding:13px 16px;margin-bottom:16px;
            display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;
            box-shadow:0 1px 5px rgba(124,58,237,.06)}
/* Pagination */
.pagination .page-link{color:var(--purple);border-color:#e5e0ff;font-size:.85rem}
.pagination .active .page-link{background:var(--purple);border-color:var(--purple);color:#fff}
/* Buttons */
.btn-p{background:var(--purple);color:#fff;border:none}
.btn-p:hover{background:var(--purple-dark);color:#fff}
.btn-op{border:1.5px solid var(--purple);color:var(--purple);background:transparent}
.btn-op:hover{background:var(--purple);color:#fff}
/* Modal */
.modal-header{background:linear-gradient(135deg,var(--purple),var(--purple-dark));color:#fff}
.modal-header .btn-close{filter:invert(1)}
/* Toast */
.toast-wrap{position:fixed;top:68px;right:18px;z-index:9999;min-width:270px}
.fw-600{font-weight:600}
</style>
</head>
<body>
<div id="sb-overlay" onclick="toggleSidebar()"></div>
<div id="sb">
  <div class="brand">
    <h4><i class="bi bi-compass me-2"></i>BizGuide</h4>
    <small>Admin Panel</small>
  </div>
  <nav>
    <div class="sect">Overview</div>
    <a href="<?= BASE_URL ?>/admin/dashboard"
       <?= str_contains($_SERVER['REQUEST_URI'], '/dashboard') ? 'class="active"' : '' ?>>
      <i class="bi bi-speedometer2"></i>Dashboard
    </a>

        <div class="sect">Manage</div>
    <?php if(Auth::isSuperAdmin()): ?>
    <a href="<?= BASE_URL ?>/admin/cities"
       <?= str_contains($_SERVER['REQUEST_URI'], '/cities') ? 'class="active"' : '' ?>>
      <i class="bi bi-geo-alt"></i>Cities
    </a>
    <?php endif ?>
    <a href="<?= BASE_URL ?>/admin/users"
       <?= str_contains($_SERVER['REQUEST_URI'], '/admin/users') && !str_contains($_SERVER['REQUEST_URI'],'/free') ? 'class="active"' : '' ?>>
      <i class="bi bi-people"></i>Users
    </a>
    <a href="<?= BASE_URL ?>/admin/users/free"
       <?= str_contains($_SERVER['REQUEST_URI'], '/users/free') ? 'class="active"' : '' ?>>
      <i class="bi bi-person"></i>Free Users
      <?php
      $freeCount = (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM users u JOIN plans pl ON u.plan_id=pl.id WHERE pl.name='free'")['c'] ?? 0);
      if($freeCount > 0): ?><span class="badge bg-secondary ms-auto"><?= $freeCount ?></span><?php endif ?>
    </a>
    <a href="<?= BASE_URL ?>/admin/listings"
       <?= str_contains($_SERVER['REQUEST_URI'], '/admin/listings') && !str_contains($_SERVER['REQUEST_URI'],'/pending') && !str_contains($_SERVER['REQUEST_URI'],'/expired') ? 'class="active"' : '' ?>>
      <i class="bi bi-building"></i>Active Ads
    </a>
    <a href="<?= BASE_URL ?>/admin/listings/pending"
       <?= str_contains($_SERVER['REQUEST_URI'], '/pending') ? 'class="active"' : '' ?>>
      <i class="bi bi-hourglass-split"></i>Pending Approval
      <?php if($pendingListings > 0): ?><span class="badge bg-danger ms-auto"><?= $pendingListings ?></span><?php endif ?>
    </a>
    <a href="<?= BASE_URL ?>/admin/listings/expired"
       <?= str_contains($_SERVER['REQUEST_URI'], '/expired') ? 'class="active"' : '' ?>>
      <i class="bi bi-clock-history"></i>Expired Ads
    </a>
    <?php if(Auth::isSuperAdmin()): ?>
    <div class="sect">Content</div>
    <a href="<?= BASE_URL ?>/admin/categories"
       <?= str_contains($_SERVER['REQUEST_URI'], '/categories') ? 'class="active"' : '' ?>>
      <i class="bi bi-grid"></i>Categories
    </a>
    <a href="<?= BASE_URL ?>/admin/keywords"
       <?= str_contains($_SERVER['REQUEST_URI'], '/keywords') && !str_contains($_SERVER['REQUEST_URI'],'/suggestions') ? 'class="active"' : '' ?>>
      <i class="bi bi-tags"></i>Keywords
    </a>
    <a href="<?= BASE_URL ?>/admin/keywords/suggestions"
       <?= str_contains($_SERVER['REQUEST_URI'], '/suggestions') ? 'class="active"' : '' ?>>
      <i class="bi bi-lightbulb"></i>Suggestions
      <?php if($pendingSuggs > 0): ?><span class="badge bg-warning text-dark ms-auto"><?= $pendingSuggs ?></span><?php endif ?>
    </a>
    <a href="<?= BASE_URL ?>/admin/reviews"
       <?= str_contains($_SERVER['REQUEST_URI'], '/reviews') ? 'class="active"' : '' ?>>
      <i class="bi bi-chat-dots"></i>Reviews
      <?php
      $pendingReviews = (int)(Database::fetchOne("SELECT COUNT(*) AS c FROM listing_reviews WHERE status='pending'")['c'] ?? 0);
      if($pendingReviews > 0): ?><span class="badge bg-warning text-dark ms-auto"><?= $pendingReviews ?></span><?php endif ?>
    </a>

    <?php endif ?>
    <div class="sect">Finance</div>
    <?php if(Auth::isSuperAdmin()): ?>
    <a href="<?= BASE_URL ?>/admin/plans"
       <?= str_contains($_SERVER['REQUEST_URI'], '/plans') ? 'class="active"' : '' ?>>
      <i class="bi bi-tag"></i>Plans & Pricing
    </a>
    <?php endif ?>
    <a href="<?= BASE_URL ?>/admin/payments"
       <?= str_contains($_SERVER['REQUEST_URI'], '/payments') ? 'class="active"' : '' ?>>
      <i class="bi bi-credit-card"></i>Payments
      <?php if ($pendingPayments > 0): ?>
        <span class="badge bg-warning text-dark ms-auto"><?= $pendingPayments ?></span>
      <?php endif ?>
    </a>

    <?php if(Auth::isSuperAdmin()): ?>
    <div class="sect">System</div>
    <a href="<?= BASE_URL ?>/admin/admins"
       <?= str_contains($_SERVER['REQUEST_URI'], '/admin/admins') ? 'class="active"' : '' ?>>
      <i class="bi bi-shield-lock"></i>Admin Accounts
    </a>
    <?php endif ?>

    <div class="sect">Analytics</div>
    <a href="<?= BASE_URL ?>/admin/reports"
       <?= str_contains($_SERVER['REQUEST_URI'], '/reports') ? 'class="active"' : '' ?>>
      <i class="bi bi-bar-chart-line"></i>Reports
    </a>
  </nav>
  <div class="foot">
    <a href="<?= BASE_URL ?>/admin/logout">
      <i class="bi bi-box-arrow-left"></i>Logout
    </a>
  </div>
</div>

<div id="tb">
  <button id="hbg" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
  <span class="pt"><?= htmlspecialchars($pageTitle) ?></span>
  <div class="tr">
    <?php if ($pendingPayments > 0): ?>
      <a href="<?= BASE_URL ?>/admin/payments" class="btn btn-sm btn-warning py-1">
        <i class="bi bi-bell-fill me-1"></i><?= $pendingPayments ?> pending
      </a>
    <?php endif ?>
    <div class="av"><?= strtoupper(substr($admin['name'] ?? 'A', 0, 1)) ?></div>
    <span style="font-size:.85rem;font-weight:600"><?= htmlspecialchars($admin['name'] ?? 'Admin') ?></span>
  </div>
</div>

<div class="toast-wrap">
  <?php if ($successMsg): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm py-2 mb-2">
      <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($successMsg) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif ?>
  <?php if ($errorMsg): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm py-2 mb-2">
      <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($errorMsg) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif ?>
  <?php if ($infoMsg): ?>
    <div class="alert alert-info alert-dismissible fade show shadow-sm py-2 mb-2">
      <i class="bi bi-info-circle me-2"></i><?= htmlspecialchars($infoMsg) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif ?>
</div>

<div id="main">
