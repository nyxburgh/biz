<?php
// ============================================================
// BizGuide — Main Landing Page (City Directory)
// ============================================================
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Helper.php';

$cities = Database::fetchAll(
    "SELECT * FROM cities WHERE status = 'active' ORDER BY sort_order ASC, name ASC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>BizGuide — Your City Business Directory</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{font-family:'Segoe UI',sans-serif;background:#f8f7ff;margin:0}
.hero{background:linear-gradient(135deg,#2d1b69 0%,#1e1245 45%,#7c3aed 100%);
      color:#fff;padding:70px 0 55px;text-align:center}
.hero h1{font-size:2.6rem;font-weight:900;margin-bottom:8px}
.hero p{font-size:1.05rem;opacity:.85;margin:0}
.city-card{display:flex;align-items:center;gap:14px;padding:20px 22px;
           background:#fff;border-radius:14px;text-decoration:none;color:inherit;
           box-shadow:0 3px 14px rgba(124,58,237,.1);transition:.2s;border:1.5px solid transparent}
.city-card:hover{transform:translateY(-4px);box-shadow:0 8px 28px rgba(124,58,237,.2);
                 border-color:#a78bfa;color:inherit}
.city-icon{width:50px;height:50px;min-width:50px;border-radius:12px;
           background:linear-gradient(135deg,#7c3aed,#2d1b69);display:flex;
           align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.05rem}
.city-name{font-weight:700;font-size:.97rem;color:#1e1245}
.city-desc{font-size:.75rem;color:#6b7280;margin-top:2px}
</style>
</head>
<body>

<div class="hero">
  <div class="container">
    <h1><i class="bi bi-compass me-2"></i>BizGuide</h1>
    <p>Your city's complete business directory — find professionals, shops & services</p>
  </div>
</div>

<div class="container py-5">
  <h5 class="fw-700 text-center mb-4" style="color:#2d1b69">
    <i class="bi bi-geo-alt me-2"></i>Choose Your City
  </h5>

  <?php if (empty($cities)): ?>
    <div class="text-center text-muted py-5">
      <i class="bi bi-geo-alt fs-1 d-block mb-3 text-muted"></i>
      <p>No cities available yet. Check back soon!</p>
    </div>
  <?php else: ?>
    <div class="row g-3 justify-content-center">
      <?php foreach ($cities as $city):
        $url = $city['domain']
          ? 'https://' . $city['domain']
          : BASE_URL . '/cities/' . $city['slug'];
      ?>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="<?= htmlspecialchars($url) ?>" class="city-card">
          <div class="city-icon">
            <?= strtoupper(substr($city['name'], 0, 2)) ?>
          </div>
          <div>
            <div class="city-name"><?= htmlspecialchars($city['name']) ?></div>
            <?php if ($city['description']): ?>
              <div class="city-desc"><?= htmlspecialchars(Helper::truncate($city['description'], 40)) ?></div>
            <?php endif ?>
          </div>
        </a>
      </div>
      <?php endforeach ?>
    </div>
  <?php endif ?>
</div>

<footer class="text-center py-4 text-muted" style="border-top:1px solid #e5e0ff;font-size:.82rem">
  &copy; <?= date('Y') ?> BizGuide &nbsp;|&nbsp;
  <a href="<?= BASE_URL ?>/admin/login" style="color:#7c3aed;text-decoration:none">Admin</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
