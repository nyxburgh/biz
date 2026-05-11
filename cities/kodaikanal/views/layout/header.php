<?php
$cityName  = defined('CITY_NAME')  ? CITY_NAME  : 'BizGuide';
$cityUrl   = defined('CITY_URL')   ? CITY_URL   : '';
$cityColor = defined('CITY_COLOR') ? CITY_COLOR : '#7c3aed';
$pageTitle = $pageTitle ?? $cityName;
$isUser    = !empty($_SESSION['user_id']);
$userData  = $_SESSION['user_data'] ?? null;
$userInit  = $isUser ? strtoupper(substr($userData['name']??'U',0,1)) : '';
$isOwner   = $isUser && ($userData['user_type']??'owner') === 'owner';
// Check if owner already has a listing — hide Post Ad if so
$hasListing = false;
if ($isOwner) {
    $hasListing = (bool) Database::fetchOne(
        "SELECT bl.id
         FROM business_listings bl
         JOIN users u ON bl.user_id = u.id
         LEFT JOIN plans pl ON u.plan_id = pl.id
         WHERE bl.user_id=? AND COALESCE(pl.name, 'free') != 'free'
         LIMIT 1",
        [$_SESSION['user_id']]
    );
}
$flashS = Helper::getFlash('success');
$flashE = Helper::getFlash('error');
$flashI = Helper::getFlash('info');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="<?= htmlspecialchars($cityColor) ?>">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<title><?= htmlspecialchars($pageTitle) ?> — BizGuide <?= htmlspecialchars($cityName) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root{
  --primary:<?= htmlspecialchars($cityColor) ?>;
  --sand:#f5ede0;--sand-light:#faf6f0;--sand-dark:#e8d9c4;
  --purple:#7c3aed;--purple-light:#ede9fe;--purple-muted:#a78bfa;
  --green:#3a7c5a;--green-light:#e6f4ec;
  --teal:#2a7d8c;--teal-light:#e0f5f8;
  --amber:#b45309;--amber-light:#fef3c7;
  --maroon:#9b2335;--maroon-light:#fde8eb;
  --text-dark:#1a1018;--text-mid:#4a3f52;--text-muted:#8b7d96;
  --border:#e2d5f0;
  --header-h:64px;--footer-h:64px;
  --radius:14px;--radius-sm:8px;
  --shadow:0 4px 20px rgba(124,58,237,0.08);
  --shadow-hover:0 12px 40px rgba(124,58,237,0.18);
  --transition:all 0.25s cubic-bezier(0.34,1.2,0.64,1);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;overflow-x:hidden;-webkit-text-size-adjust:100%}
body{font-family:'DM Sans',sans-serif;background:var(--sand-light);color:var(--text-dark);font-size:15px;line-height:1.6;padding-top:var(--header-h);overflow-x:hidden}
a{text-decoration:none;color:inherit}
img{max-width:100%;height:auto}
.site-header{position:fixed;top:0;left:0;right:0;z-index:900;height:var(--header-h);background:rgba(250,246,240,0.95);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 20px}
.header-logo{display:flex;align-items:center;gap:8px;font-family:'Syne',sans-serif;font-weight:800;font-size:1.2rem;color:var(--primary)}
.city-tag{font-size:0.68rem;font-weight:600;color:var(--green);background:var(--green-light);padding:2px 8px;border-radius:40px}
.header-nav{display:flex;align-items:center;gap:4px}
.header-nav a{padding:7px 13px;border-radius:40px;font-size:0.85rem;font-weight:500;color:var(--text-mid);transition:var(--transition)}
.header-nav a:hover{background:var(--purple-light);color:var(--primary)}
.header-actions{display:flex;align-items:center;gap:8px}
.btn-login{padding:7px 16px;border-radius:40px;border:1.5px solid var(--primary);color:var(--primary);background:transparent;cursor:pointer;font-size:0.85rem;font-weight:500;font-family:inherit;transition:var(--transition)}
.btn-login:hover{background:var(--primary);color:#fff}
.btn-post{padding:8px 16px;border-radius:40px;background:var(--primary);color:#fff;border:none;cursor:pointer;font-size:0.85rem;font-weight:600;font-family:inherit;display:flex;align-items:center;gap:5px;transition:var(--transition)}
.btn-post:hover{background:#6d28d9}
.user-av{width:36px;height:36px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.88rem;text-decoration:none}
.mobile-bottom-bar{display:none;position:fixed;bottom:0;left:0;right:0;z-index:950;height:var(--footer-h);background:rgba(250,246,240,0.97);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-top:1px solid var(--border);align-items:center;justify-content:space-around;padding:0 8px;padding-bottom:env(safe-area-inset-bottom)}
.mb-btn{display:flex;flex-direction:column;align-items:center;gap:2px;padding:6px 12px;border-radius:10px;cursor:pointer;background:none;border:none;color:var(--text-muted);font-family:inherit;-webkit-tap-highlight-color:transparent;min-width:44px;min-height:44px;justify-content:center}
.mb-btn span{font-size:0.58rem;font-weight:600;letter-spacing:.03em;text-transform:uppercase}
.mb-btn i{font-size:1.25rem}
.mb-btn.active,.mb-btn:active{color:var(--primary)}
.mb-fab{width:50px;height:50px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-top:-18px;box-shadow:0 4px 20px rgba(124,58,237,.4);cursor:pointer;flex-shrink:0;border:3px solid rgba(250,246,240,.97);-webkit-tap-highlight-color:transparent;text-decoration:none}
.desktop-only{display:flex}
.mobile-center-logo{display:none;align-items:center;justify-content:center;width:100%;gap:8px}
.flash-area{position:fixed;top:calc(var(--header-h)+8px);right:12px;z-index:9999;display:flex;flex-direction:column;gap:8px;max-width:300px}
.flash{padding:11px 15px;border-radius:10px;font-size:0.85rem;font-weight:500;box-shadow:0 4px 20px rgba(0,0,0,.12);display:flex;align-items:center;gap:8px;animation:fIn .3s ease}
@keyframes fIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}
.flash-s{background:#d1fae5;color:#065f46;border-left:4px solid #10b981}
.flash-e{background:#fee2e2;color:#991b1b;border-left:4px solid #ef4444}
.flash-i{background:#e0f2fe;color:#0c4a6e;border-left:4px solid #0ea5e9}
/* @media(max-width:768px){
  body{padding-bottom:calc(var(--footer-h)+env(safe-area-inset-bottom))}
  .mobile-bottom-bar{display:flex}
  .header-nav,.header-actions,.desktop-only{display:none!important}
  .mobile-center-logo{display:flex}
  .site-header{justify-content:center}
  .flash-area{right:8px;left:8px;max-width:100%}
  
} */
@media(max-width:768px){
  body{padding-bottom:calc(var(--footer-h) + 24px + env(safe-area-inset-bottom))}
  .mobile-bottom-bar{display:flex}
  .header-nav,.header-actions,.desktop-only{display:none!important}
  .mobile-center-logo{display:flex}
  .site-header{justify-content:center}
  .flash-area{right:8px;left:8px;max-width:100%}
}
@media(max-width:768px){
  .site-footer-main{display:none!important}
}
</style>
<?= $extraCss ?? '' ?>
</head>
<body>
<?php if($flashS||$flashE||$flashI): ?>
<div class="flash-area" id="flashes">
  <?php if($flashS): ?><div class="flash flash-s"><i class="bi bi-check-circle-fill"></i><?= htmlspecialchars($flashS) ?></div><?php endif ?>
  <?php if($flashE): ?><div class="flash flash-e"><i class="bi bi-exclamation-circle-fill"></i><?= htmlspecialchars($flashE) ?></div><?php endif ?>
  <?php if($flashI): ?><div class="flash flash-i"><i class="bi bi-info-circle-fill"></i><?= htmlspecialchars($flashI) ?></div><?php endif ?>
</div>
<script>setTimeout(function(){var w=document.getElementById('flashes');if(w)w.style.display='none';},4500);</script>
<?php endif ?>
<header class="site-header">
  <a href="<?= $cityUrl ?>" class="header-logo desktop-only">
    <i class="bi bi-grid-3x3-gap-fill" style="font-size:1.3rem"></i>
    BizGuide <span class="city-tag"><?= htmlspecialchars($cityName) ?></span>
  </a>
  <div class="mobile-center-logo">
    <i class="bi bi-grid-3x3-gap-fill" style="font-size:1.2rem;color:var(--primary)"></i>
    <a href="<?= $cityUrl ?>" style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.1rem;color:var(--primary)">BizGuide</a>
    <span class="city-tag"><?= htmlspecialchars($cityName) ?></span>
  </div>
  <nav class="header-nav">
    <a href="<?= $cityUrl ?>">Home</a>
    <a href="<?= $cityUrl ?>/search">Businesses</a>
  </nav>
  <div class="header-actions">
    <?php if($isUser): ?>
      <a href="<?= $cityUrl ?>/dashboard" class="user-av" title="Dashboard"><?= $userInit ?></a>
      <?php if($isOwner && !$hasListing): ?>
        <a href="<?= $cityUrl ?>/post-ad" class="btn-post"><i class="bi bi-plus-lg"></i> Post Ad</a>
      <?php elseif($isOwner && $hasListing): ?>
        <a href="<?= $cityUrl ?>/dashboard" class="btn-post" style="background:var(--green)"><i class="bi bi-grid-1x2"></i> My Ads</a>
      <?php endif ?>
    <?php else: ?>
      <?php if(($activePage ?? '') === 'search'): ?>
        <a href="<?= $cityUrl ?>" class="btn-login">Home</a>
      <?php else: ?>
        <a href="<?= $cityUrl ?>/login" class="btn-login">Login</a>
      <?php endif ?>
      <a href="<?= $cityUrl ?>/login" class="btn-post"><i class="bi bi-plus-lg"></i> Post Ad</a>
    <?php endif ?>
  </div>
</header>
<nav class="mobile-bottom-bar">
  <a href="<?= $cityUrl ?>" class="mb-btn <?= ($activePage??'')==='home'?'active':'' ?>"><i class="bi bi-house-fill"></i><span>Home</span></a>
  <a href="<?= $cityUrl ?>/search" class="mb-btn <?= ($activePage??'')==='search'?'active':'' ?>"><i class="bi bi-search"></i><span>Search</span></a>
  <?php if($isOwner && !$hasListing): ?>
    <a href="<?= $cityUrl ?>/post-ad" class="mb-fab"><i class="bi bi-plus-lg"></i></a>
  <?php elseif($isOwner && $hasListing): ?>
    <a href="<?= $cityUrl ?>/edit-ad" class="mb-fab" style="background:var(--green)"><i class="bi bi-pencil"></i></a>
  <?php else: ?>
    <a href="<?= $cityUrl ?>/login" class="mb-fab"><i class="bi bi-plus-lg"></i></a>
  <?php endif ?>
  <?php if($isUser): ?>
    <a href="<?= $cityUrl ?>/dashboard" class="mb-btn <?= ($activePage??'')==='dashboard'?'active':'' ?>"><i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span></a>
    <a href="<?= $cityUrl ?>/logout" class="mb-btn"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a>
  <?php else: ?>
    <a href="<?= $cityUrl ?>/login" class="mb-btn <?= ($activePage??'')==='login'?'active':'' ?>"><i class="bi bi-person-circle"></i><span>Login</span></a>
    <a href="<?= $cityUrl ?>/search" class="mb-btn"><i class="bi bi-bookmark"></i><span>Saved</span></a>
  <?php endif ?>
</nav>
