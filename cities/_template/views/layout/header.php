<?php
$cityName = defined('CITY_NAME') ? CITY_NAME : 'BizGuide';
$cityUrl = defined('CITY_URL') ? CITY_URL : '';
$cityColor = defined('CITY_COLOR') ? CITY_COLOR : '#7c3aed';
$pageTitle = $pageTitle ?? $cityName;
$isUser = !empty($_SESSION['user_id']);
$userData = $_SESSION['user_data'] ?? null;
$userInit = $isUser ? strtoupper(substr($userData['name'] ?? 'U', 0, 1)) : '';
$isOwner = $isUser && ($userData['user_type'] ?? 'owner') === 'owner';
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
  <link
    href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    :root {
      --primary: <?= htmlspecialchars($cityColor) ?>;
      --sand: #f5ede0;
      --sand-light: #faf6f0;
      --sand-dark: #e8d9c4;
      --purple: #7c3aed;
      --purple-light: #ede9fe;
      --purple-muted: #a78bfa;
      --green: #3a7c5a;
      --green-light: #e6f4ec;
      --teal: #2a7d8c;
      --teal-light: #e0f5f8;
      --amber: #b45309;
      --amber-light: #fef3c7;
      --maroon: #9b2335;
      --maroon-light: #fde8eb;
      --text-dark: #1a1018;
      --text-mid: #4a3f52;
      --text-muted: #8b7d96;
      --border: #e2d5f0;
      --header-h: 64px;
      --footer-h: 64px;
      --radius: 14px;
      --radius-sm: 8px;
      --shadow: 0 4px 20px rgba(124, 58, 237, 0.08);
      --shadow-hover: 0 12px 40px rgba(124, 58, 237, 0.18);
      --transition: all 0.25s cubic-bezier(0.34, 1.2, 0.64, 1);
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    html {
      scroll-behavior: smooth;
      overflow-x: hidden;
      -webkit-text-size-adjust: 100%
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--sand-light);
      color: var(--text-dark);
      font-size: 15px;
      line-height: 1.6;
      padding-top: var(--header-h);
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
      min-height: 100vh
    }

    main {
      flex: 1
    }

    a {
      text-decoration: none;
      color: inherit
    }

    img {
      max-width: 100%;
      height: auto
    }

    .site-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 900;
      height: var(--header-h);
      background: rgba(250, 246, 240, 0.95);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px
    }

    .header-logo {
      display: flex;
      align-items: center;
      gap: 8px;
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 1.2rem;
      color: var(--primary)
    }

    .city-tag {
      font-size: 0.68rem;
      font-weight: 600;
      color: var(--green);
      background: var(--green-light);
      padding: 2px 8px;
      border-radius: 40px
    }

    .header-nav {
      display: flex;
      align-items: center;
      gap: 4px
    }

    .header-nav a {
      padding: 7px 13px;
      border-radius: 40px;
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--text-mid);
      transition: var(--transition)
    }

    .header-nav a:hover {
      background: var(--purple-light);
      color: var(--primary)
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 8px
    }

    .btn-login {
      padding: 7px 16px;
      border-radius: 40px;
      border: 1.5px solid var(--primary);
      color: var(--primary);
      background: transparent;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: 500;
      font-family: inherit;
      transition: var(--transition)
    }

    .btn-login:hover {
      background: var(--primary);
      color: #fff
    }

    .btn-post {
      padding: 8px 16px;
      border-radius: 40px;
      background: var(--primary);
      color: #fff;
      border: none;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: 600;
      font-family: inherit;
      display: flex;
      align-items: center;
      gap: 5px;
      transition: var(--transition)
    }

    .btn-post:hover {
      background: #6d28d9
    }

    .user-av {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--primary);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.88rem;
      text-decoration: none
    }

    .mobile-back-btn {
      display: none;
      position: fixed;
      top: calc(12px + env(safe-area-inset-top));
      left: 12px;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #fff;
      border: 1px solid rgba(124, 58, 237, 0.18);
      box-shadow: 0 12px 26px rgba(124, 58, 237, 0.18);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
      overflow: hidden;
      touch-action: manipulation
    }

    .mobile-back-btn svg {
      width: 18px;
      height: 18px;
      stroke: var(--primary);
      stroke-width: 2;
      fill: none
    }

    .mobile-back-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 16px 32px rgba(124, 58, 237, 0.22);
      background: rgba(124, 58, 237, 0.04)
    }

    .mobile-back-btn:active {
      transform: scale(0.96)
    }

    .mobile-home-btn {
      display: none;
      position: fixed;
      top: calc(12px + env(safe-area-inset-top));
      right: 12px;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #fff;
      border: 1px solid rgba(124, 58, 237, 0.18);
      box-shadow: 0 12px 26px rgba(124, 58, 237, 0.18);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
      touch-action: manipulation;
      text-decoration: none;
      color: var(--primary);
      font-size: 1.1rem
    }

    .mobile-home-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 16px 32px rgba(124, 58, 237, 0.22);
      background: rgba(124, 58, 237, 0.04)
    }

    .mobile-home-btn:active {
      transform: scale(0.96)
    }

    .desktop-only {
      display: flex
    }

    .mobile-center-logo {
      display: none;
      align-items: center;
      justify-content: center;
      width: 100%;
      gap: 8px
    }

    .flash-area {
      position: fixed;
      top: calc(var(--header-h)+8px);
      right: 12px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 8px;
      max-width: 300px
    }

    .flash {
      padding: 11px 15px;
      border-radius: 10px;
      font-size: 0.85rem;
      font-weight: 500;
      box-shadow: 0 4px 20px rgba(0, 0, 0, .12);
      display: flex;
      align-items: center;
      gap: 8px;
      animation: fIn .3s ease
    }

    @keyframes fIn {
      from {
        transform: translateX(100%);
        opacity: 0
      }
      to {
        transform: translateX(0);
        opacity: 1
      }
    }

    .flash-s {
      background: #d1fae5;
      color: #065f46;
      border-left: 4px solid #10b981
    }

    .flash-e {
      background: #fee2e2;
      color: #991b1b;
      border-left: 4px solid #ef4444
    }

    .flash-i {
      background: #e0f2fe;
      color: #0c4a6e;
      border-left: 4px solid #0ea5e9
    }


    /* ═══════════════════════════════════════════════
       MOBILE BOTTOM-SHEET CITY SELECTOR — PREMIUM UI
       ═══════════════════════════════════════════════ */

    /* Floating bottom-bar — hidden on desktop */
    .mobile-bottom-bar { display: none; }

    /* ── Backdrop overlay ── */
    .city-sheet-backdrop {
      position: fixed;
      inset: 0;
      z-index: 99990;
      background: rgba(15, 10, 30, 0.55);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.35s ease;
    }
    .city-sheet-backdrop.is-open {
      opacity: 1;
      pointer-events: auto;
    }

    /* ── Floating sheet wrapper ── */
    .city-bottom-sheet {
      position: fixed;
      inset: 0;
      z-index: 99999;
      display: flex;
      align-items: flex-end;
      justify-content: center;
      pointer-events: none;
      padding-bottom: 18px;
      padding-bottom: calc(18px + env(safe-area-inset-bottom));
    }

    /* ── Sheet panel ── */
    .city-sheet-panel {
      width: 91%;
      max-width: 420px;
      background: #ffffff;
      border-radius: 30px;
      box-shadow:
        0 2px 0 rgba(255,255,255,0.9) inset,
        0 30px 80px rgba(15, 5, 40, 0.38),
        0 8px 24px rgba(124, 58, 237, 0.18),
        0 0 0 1px rgba(124, 58, 237, 0.07);
      transform: translateY(110%);
      transition: transform 0.46s cubic-bezier(0.16, 1, 0.3, 1);
      will-change: transform;
      pointer-events: auto;
      overflow: hidden;
    }
    .city-bottom-sheet.is-open .city-sheet-panel {
      transform: translateY(0);
    }

    /* ── Drag handle ── */
    .city-sheet-handle {
      width: 36px;
      height: 4px;
      background: #ddd8f0;
      border-radius: 99px;
      margin: 12px auto 0;
    }

    /* ── Header row ── */
    .city-sheet-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 20px 14px;
    }
    .city-sheet-title {
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 1.22rem;
      color: #1a1028;
      letter-spacing: -0.025em;
    }
    .city-sheet-close {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      border: 1.5px solid rgba(124, 58, 237, 0.25);
      background: rgba(124, 58, 237, 0.05);
      color: var(--primary);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 1rem;
      transition: background 0.2s ease, transform 0.2s ease;
      -webkit-tap-highlight-color: transparent;
    }
    .city-sheet-close:active {
      transform: scale(0.88);
      background: rgba(124, 58, 237, 0.12);
    }

    /* ── City card list ── */
    .city-sheet-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
      padding: 0 14px 18px;
    }

    /* ── Individual city card ── */
    .city-sheet-card {
      display: flex;
      align-items: center;
      padding: 13px 14px 13px 13px;
      border-radius: 20px;
      border: 1.5px solid rgba(226, 213, 240, 0.7);
      background: #faf9ff;
      text-decoration: none;
      color: #1a1028;
      transition:
        transform 0.22s cubic-bezier(0.34, 1.4, 0.64, 1),
        box-shadow 0.22s ease,
        border-color 0.22s ease,
        background 0.22s ease;
      -webkit-tap-highlight-color: transparent;
      position: relative;
      overflow: hidden;
      min-height: 68px;
    }
    .city-sheet-card:active {
      transform: scale(0.966);
      background: #f0ecff;
    }

    /* Ripple */
    .city-sheet-card .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(124, 58, 237, 0.18);
      transform: scale(0);
      animation: rippleAnim 0.55s linear;
      pointer-events: none;
    }
    @keyframes rippleAnim {
      to { transform: scale(4); opacity: 0; }
    }

    /* ── Selected card ── */
    .city-sheet-card.active {
      border-color: var(--primary);
      background: rgba(124, 58, 237, 0.07);
      box-shadow:
        0 0 0 3px rgba(124, 58, 237, 0.13),
        0 6px 20px rgba(124, 58, 237, 0.16);
    }

    /* ── Card icon container ── */
    .city-sheet-icon {
      width: 48px;
      height: 48px;
      border-radius: 15px;
      background: #fff;
      box-shadow: 0 3px 12px rgba(124, 58, 237, 0.14);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      color: var(--primary);
      margin-right: 14px;
      flex-shrink: 0;
      transition: background 0.22s ease, box-shadow 0.22s ease;
    }
    .city-sheet-card.active .city-sheet-icon {
      background: var(--primary);
      color: #fff;
      box-shadow: 0 6px 18px rgba(124, 58, 237, 0.42);
    }

    /* ── Card text ── */
    .city-sheet-info {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 2px;
    }
    .city-sheet-name {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 0.97rem;
      color: #1a1028;
      letter-spacing: -0.01em;
    }
    .city-sheet-state {
      font-size: 0.73rem;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      gap: 3px;
    }

    /* ── Right indicator ── */
    .city-sheet-arrow {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      color: var(--text-muted);
      background: rgba(0,0,0,0.04);
      margin-left: 8px;
      flex-shrink: 0;
      transition: background 0.2s ease, color 0.2s ease;
    }
    .city-sheet-card.active .city-sheet-arrow {
      background: var(--primary);
      color: #fff;
    }

    /* ═══════════════════════════════════════
       MOBILE BOTTOM NAV BAR — PREMIUM REDESIGN
       ═══════════════════════════════════════ */
    @media(max-width: 768px) {
      body {
        padding-bottom: calc(var(--footer-h) + 24px + env(safe-area-inset-bottom));
      }

      .mobile-bottom-bar {
        display: flex;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 950;
        height: 72px;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(28px) saturate(1.8);
        -webkit-backdrop-filter: blur(28px) saturate(1.8);
        border-top: 1px solid rgba(124, 58, 237, 0.08);
        border-radius: 22px 22px 0 0;
        box-shadow: 0 -6px 32px rgba(15, 5, 40, 0.08), 0 -1px 0 rgba(255,255,255,0.9);
        align-items: center;
        justify-content: space-evenly;
        padding: 0 4px;
        padding-bottom: env(safe-area-inset-bottom);
      }

      .mb-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        flex: 1;
        height: 100%;
        color: #b0a0c8;
        text-decoration: none;
        transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.2s ease;
        -webkit-tap-highlight-color: transparent;
        background: none;
        border: none;
        padding: 0;
        position: relative;
        cursor: pointer;
      }

      .mb-btn span {
        font-size: 0.62rem;
        font-weight: 500;
        letter-spacing: 0.02em;
        transition: color 0.2s ease, font-weight 0.2s ease;
      }

      .mb-btn i {
        font-size: 1.3rem;
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.3s ease;
      }

      .mb-btn:active { transform: scale(0.88); }

      .mb-btn.active {
        color: var(--primary);
      }
      .mb-btn.active i {
        transform: translateY(-2px);
        filter: drop-shadow(0 4px 8px rgba(124, 58, 237, 0.45));
      }
      .mb-btn.active span { font-weight: 700; }

      /* Active underline dot */
      .mb-btn.active::after {
        content: '';
        position: absolute;
        bottom: 8px;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--primary);
        box-shadow: 0 0 6px rgba(124, 58, 237, 0.6);
      }



      .mobile-back-btn { display: flex; }
      .mobile-home-btn { display: flex; }
      .header-nav, .header-actions, .desktop-only { display: none !important; }
      .mobile-center-logo { display: flex; }
      .site-header { justify-content: center; }
      .flash-area { right: 8px; left: 8px; max-width: 100%; }
      .site-footer-main { display: none !important; }
    }

  </style>
  <?= $extraCss ?? '' ?>
</head>

<body>
  <?php if (($activePage ?? '') !== 'home'): ?>
    <button class="mobile-back-btn" type="button" onclick="handleMobileBackButton()" aria-label="Go back">
      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </button>
    <a class="mobile-home-btn" href="<?= htmlspecialchars($cityUrl) ?>" aria-label="Go home">
      <i class="bi bi-house-fill"></i>
    </a>
    <script>
      function handleMobileBackButton() {
        if (window.history.length > 1) {
          window.history.back();
        } else {
          window.location.href = '<?= htmlspecialchars($cityUrl) ?>';
        }
      }
    </script>
  <?php endif ?>
  <?php if ($flashS || $flashE || $flashI): ?>
    <div class="flash-area" id="flashes">
      <?php if ($flashS): ?>
        <div class="flash flash-s"><i class="bi bi-check-circle-fill"></i><?= htmlspecialchars($flashS) ?></div>
      <?php endif ?>
      <?php if ($flashE): ?>
        <div class="flash flash-e"><i class="bi bi-exclamation-circle-fill"></i><?= htmlspecialchars($flashE) ?></div>
      <?php endif ?>
      <?php if ($flashI): ?>
        <div class="flash flash-i"><i class="bi bi-info-circle-fill"></i><?= htmlspecialchars($flashI) ?></div>
      <?php endif ?>
    </div>
    <script>setTimeout(function () { var w = document.getElementById('flashes'); if (w) w.style.display = 'none'; }, 4500);</script>
  <?php endif ?>
  <header class="site-header">
    <a href="<?= $cityUrl ?>" class="header-logo desktop-only">
      <i class="bi bi-grid-3x3-gap-fill" style="font-size:1.3rem"></i>
      BizGuide <span class="city-tag"><?= htmlspecialchars($cityName) ?></span>
    </a>
    <div class="mobile-center-logo">
      <i class="bi bi-grid-3x3-gap-fill" style="font-size:1.2rem;color:var(--primary)"></i>
      <a href="<?= $cityUrl ?>"
        style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.1rem;color:var(--primary)">BizGuide</a>
      <span class="city-tag"><?= htmlspecialchars($cityName) ?></span>
    </div>
    <nav class="header-nav">
      <a href="<?= $cityUrl ?>">Home</a>
      <a href="<?= $cityUrl ?>/search">Businesses</a>
    </nav>
    <div class="header-actions">
      <?php if ($isUser): ?>
        <a href="<?= $cityUrl ?>/dashboard" class="user-av" title="Dashboard"><?= $userInit ?></a>
        <?php if ($isOwner && !$hasListing): ?>
          <a href="<?= $cityUrl ?>/post-ad" class="btn-post"><i class="bi bi-plus-lg"></i> Post Ad</a>
        <?php elseif ($isOwner && $hasListing): ?>
          <a href="<?= $cityUrl ?>/dashboard" class="btn-post" style="background:var(--green)"><i
              class="bi bi-grid-1x2"></i> My Ads</a>
        <?php endif ?>
      <?php else: ?>
        <!-- <a href="<?= $cityUrl ?>/login" class="btn-login">Login</a> -->
        <a href="<?= $cityUrl ?>/login" class="btn-post"><i class="bi bi-plus-lg"></i> Post Ad</a>
      <?php endif ?>
    </div>
  </header>
  <nav class="mobile-bottom-bar">
    <a href="<?= htmlspecialchars($cityUrl) ?>" class="mb-btn <?= ($activePage ?? '') === 'home' ? 'active' : '' ?>">
      <i class="bi bi-house-fill"></i><span>Home</span>
    </a>
    <a href="<?= htmlspecialchars($cityUrl) ?>/search" class="mb-btn <?= ($activePage ?? '') === 'search' ? 'active' : '' ?>">
      <i class="bi bi-search"></i><span>Search</span>
    </a>
    <?php if ($isUser): ?>
      <a href="<?= htmlspecialchars($cityUrl) ?>/dashboard" class="mb-btn <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">
        <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
      </a>
    <?php else: ?>
      <a href="<?= htmlspecialchars($cityUrl) ?>/login" class="mb-btn <?= ($activePage ?? '') === 'post-ad' ? 'active' : '' ?>">
        <i class="bi bi-plus-circle-fill"></i><span>Post Ad</span>
      </a>
    <?php endif ?>
    <button class="mb-btn <?= ($activePage ?? '') === 'map' ? 'active' : '' ?>" onclick="openCitySheet(event)">
      <i class="bi bi-geo-alt-fill"></i><span>Map</span>
    </button>
  </nav>

  <!-- ── City Selection Bottom Sheet ── -->
  <div class="city-sheet-backdrop" id="citySheetBackdrop" onclick="closeCitySheet()"></div>
  <div class="city-bottom-sheet" id="cityBottomSheet">
    <div class="city-sheet-panel">
      <div class="city-sheet-handle"></div>
      <div class="city-sheet-header">
        <span class="city-sheet-title">Select City</span>
        <button class="city-sheet-close" onclick="closeCitySheet()" aria-label="Close">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>
      <div class="city-sheet-list">
        <a href="/biz/cities/kodaikanal" class="city-sheet-card <?= strpos($cityUrl, 'kodaikanal') !== false ? 'active' : '' ?>" onclick="sheetRipple(event)">
          <div class="city-sheet-icon"><i class="bi bi-geo-alt-fill"></i></div>
          <div class="city-sheet-info">
            <span class="city-sheet-name">Kodaikanal</span>
            <span class="city-sheet-state"><i class="bi bi-pin-map-fill"></i> Tamil Nadu</span>
          </div>
          <div class="city-sheet-arrow"><i class="bi bi-chevron-right"></i></div>
        </a>
        <a href="/biz/cities/dindugal" class="city-sheet-card <?= strpos($cityUrl, 'dindugal') !== false ? 'active' : '' ?>" onclick="sheetRipple(event)">
          <div class="city-sheet-icon"><i class="bi bi-geo-alt-fill"></i></div>
          <div class="city-sheet-info">
            <span class="city-sheet-name">Dindigul</span>
            <span class="city-sheet-state"><i class="bi bi-pin-map-fill"></i> Tamil Nadu</span>
          </div>
          <div class="city-sheet-arrow"><i class="bi bi-chevron-right"></i></div>
        </a>
        <a href="/biz/cities/bengaluru" class="city-sheet-card <?= strpos($cityUrl, 'bengaluru') !== false ? 'active' : '' ?>" onclick="sheetRipple(event)">
          <div class="city-sheet-icon"><i class="bi bi-geo-alt-fill"></i></div>
          <div class="city-sheet-info">
            <span class="city-sheet-name">Bengaluru</span>
            <span class="city-sheet-state"><i class="bi bi-pin-map-fill"></i> Karnataka</span>
          </div>
          <div class="city-sheet-arrow"><i class="bi bi-chevron-right"></i></div>
        </a>
      </div>
    </div>
  </div>
<script>
  function openCitySheet(e) {
    if (e) e.preventDefault();
    document.getElementById('citySheetBackdrop').classList.add('is-open');
    document.getElementById('cityBottomSheet').classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }
  function closeCitySheet() {
    document.getElementById('citySheetBackdrop').classList.remove('is-open');
    document.getElementById('cityBottomSheet').classList.remove('is-open');
    document.body.style.overflow = '';
  }
  function sheetRipple(e) {
    var card = e.currentTarget;
    var r = document.createElement('span');
    var rect = card.getBoundingClientRect();
    var size = Math.max(rect.width, rect.height);
    r.className = 'ripple';
    r.style.cssText = 'width:' + size + 'px;height:' + size + 'px;left:' + (e.clientX - rect.left - size/2) + 'px;top:' + (e.clientY - rect.top - size/2) + 'px';
    card.appendChild(r);
    setTimeout(function() { r.remove(); }, 600);
  }
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeCitySheet();
  });
</script>
