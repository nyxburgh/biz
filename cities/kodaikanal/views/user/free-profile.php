<?php
$pageTitle  = 'My Profile';
$activePage = '';
require CITY_DIR . '/views/layout/header.php';
?>
<main>
<style>
.fp-wrap{max-width:480px;margin:40px auto;padding:0 16px}
.fp-card{background:#fff;border-radius:20px;box-shadow:var(--shadow-hover);overflow:hidden}
.fp-head{background:linear-gradient(135deg,#2d1b69,var(--primary));padding:28px 24px;text-align:center;color:#fff}
.fp-av{width:68px;height:68px;border-radius:50%;background:rgba(255,255,255,0.2);border:3px solid rgba(255,255,255,0.35);display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:1.5rem;color:#fff;margin:0 auto 12px}
.fp-head h2{font-family:'Syne',sans-serif;font-weight:800;font-size:1.2rem;margin-bottom:3px}
.fp-head p{font-size:0.82rem;opacity:0.8}
.free-badge{display:inline-flex;align-items:center;gap:4px;background:rgba(255,255,255,0.15);border-radius:40px;padding:3px 10px;font-size:0.7rem;font-weight:600;margin-top:8px}
.fp-body{padding:24px}
.detail-row{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--sand-dark)}
.detail-row:last-child{border-bottom:none}
.detail-row i{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
.dr-ic-p{background:var(--purple-light);color:var(--primary)}
.dr-ic-g{background:var(--green-light);color:var(--green)}
.dr-ic-t{background:var(--teal-light);color:var(--teal)}
.detail-label{font-size:0.75rem;color:var(--text-muted)}
.detail-val{font-size:0.9rem;font-weight:600;color:var(--text-dark)}
.upgrade-box{background:linear-gradient(135deg,var(--primary),#2d1b69);border-radius:14px;padding:20px;text-align:center;color:#fff;margin-top:20px}
.upgrade-box h3{font-family:'Syne',sans-serif;font-weight:800;font-size:1.05rem;margin-bottom:6px}
.upgrade-box p{font-size:0.8rem;opacity:0.85;margin-bottom:14px;line-height:1.5}
.btn-upg{display:inline-flex;align-items:center;gap:7px;padding:11px 24px;background:#fff;color:var(--primary);border-radius:40px;font-weight:700;font-size:0.88rem;text-decoration:none;transition:var(--transition)}
.btn-upg:hover{transform:translateY(-1px);box-shadow:0 4px 20px rgba(0,0,0,0.2);color:var(--primary)}
.plan-list{display:flex;flex-direction:column;gap:6px;margin-bottom:16px}
.plan-mini{display:flex;justify-content:space-between;align-items:center;background:rgba(255,255,255,0.12);border-radius:8px;padding:8px 12px;font-size:0.8rem}
.plan-mini span{color:#fff;font-weight:600}
.plan-mini em{color:rgba(255,255,255,0.7);font-style:normal;font-size:0.75rem}
.btn-logout{width:100%;padding:11px;border-radius:11px;border:1.5px solid var(--border);background:#fff;color:var(--text-muted);font-size:0.85rem;font-weight:600;font-family:inherit;cursor:pointer;margin-top:16px;min-height:44px}
.btn-logout:hover{border-color:#ef4444;color:#ef4444}
</style>

<div class="fp-wrap">
  <div class="fp-card">
    <div class="fp-head">
      <div class="fp-av"><?= strtoupper(substr($user['name'],0,1)) ?></div>
      <h2><?= htmlspecialchars($user['name']) ?></h2>
      <p><?= htmlspecialchars($user['profession'] ?? '') ?></p>
      <div class="free-badge"><i class="bi bi-person-fill"></i> Free Member</div>
    </div>
    <div class="fp-body">
      <div class="detail-row">
        <div class="detail-row i dr-ic-p"><i class="bi bi-person-fill"></i></div>
        <div><div class="detail-label">Name</div><div class="detail-val"><?= htmlspecialchars($user['name']) ?></div></div>
      </div>
      <div class="detail-row">
        <div class="detail-row i dr-ic-g"><i class="bi bi-briefcase-fill"></i></div>
        <div><div class="detail-label">Profession</div><div class="detail-val"><?= htmlspecialchars($user['profession'] ?? '—') ?></div></div>
      </div>
      <div class="detail-row">
        <div class="detail-row i dr-ic-t"><i class="bi bi-telephone-fill"></i></div>
        <div><div class="detail-label">Phone</div><div class="detail-val">+91 <?= htmlspecialchars($user['phone']) ?></div></div>
      </div>
    </div>
  </div>

  <div class="upgrade-box">
    <h3>Upgrade to Post Your Business</h3>
    <p>Get your own business listing page, appear in search results, and reach more customers.</p>
    <div class="plan-list">
      <?php foreach($plans as $pl): ?>
      <div class="plan-mini">
        <span><?= htmlspecialchars($pl['label']) ?></span>
        <em>₹<?= number_format($pl['price'],0) ?>/year</em>
      </div>
      <?php endforeach ?>
    </div>
    <a href="<?= $cityUrl ?>/upgrade" class="btn-upg">
      <i class="bi bi-arrow-up-circle"></i> Choose a Plan
    </a>
  </div>

  <!-- <form method="POST" action="<?= $cityUrl ?>/logout">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <button type="submit" class="btn-logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
  </form> -->
</div>
</main>
<?php require CITY_DIR . '/views/layout/footer.php'; ?>
