<?php
$pageTitle = "Page Not Found";
require CITY_DIR . "/views/layout/header.php";
?>
<main>
<div style="text-align:center;padding:80px 16px;min-height:50vh;display:flex;flex-direction:column;align-items:center;justify-content:center">
  <div style="font-size:4rem;margin-bottom:16px">😕</div>
  <h2 style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.5rem;margin-bottom:8px">Page Not Found</h2>
  <p style="color:var(--text-muted);margin-bottom:20px">This page does not exist or was removed.</p>
  <a href="<?= $cityUrl ?>" style="padding:12px 24px;border-radius:12px;background:var(--primary);color:#fff;font-weight:700">
    <i class="bi bi-house me-2"></i>Back to Home
  </a>
</div>
</main>
<?php require CITY_DIR . "/views/layout/footer.php"; ?>
