<?php
$pageTitle  = "Set Password";
$activePage = "dashboard";
require CITY_DIR . "/views/layout/header.php";
?>
<main>
<div style="max-width:420px;margin:40px auto;padding:0 16px">
  <div style="background:#fff;border-radius:20px;box-shadow:var(--shadow-hover);overflow:hidden">
    <div style="background:linear-gradient(135deg,var(--primary),#2d1b69);padding:24px;text-align:center;color:#fff">
      <div style="font-size:2.5rem;margin-bottom:8px">🔑</div>
      <h2 style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.2rem">Set Your Password</h2>
      <p style="font-size:0.82rem;opacity:0.8;margin-top:4px">Login without OTP next time</p>
    </div>
    <div style="padding:24px">
      <form method="POST" action="<?= $cityUrl ?>/set-password">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <div style="margin-bottom:14px">
          <label style="display:block;font-size:0.83rem;font-weight:600;color:var(--text-mid);margin-bottom:6px">New Password</label>
          <input type="password" name="password" style="width:100%;padding:12px 14px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:0.9rem;outline:none" placeholder="Minimum 6 characters" required minlength="6">
        </div>
        <div style="margin-bottom:18px">
          <label style="display:block;font-size:0.83rem;font-weight:600;color:var(--text-mid);margin-bottom:6px">Confirm Password</label>
          <input type="password" name="confirm" style="width:100%;padding:12px 14px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:0.9rem;outline:none" required>
        </div>
        <button type="submit" style="width:100%;padding:13px;border-radius:11px;background:var(--primary);color:#fff;border:none;font-size:0.95rem;font-weight:700;font-family:inherit;cursor:pointer;min-height:48px">
          Save Password
        </button>
      </form>
      <div style="text-align:center;margin-top:12px">
        <a href="<?= $cityUrl ?>/dashboard" style="font-size:0.8rem;color:var(--text-muted)">Skip for now</a>
      </div>
    </div>
  </div>
</div>
</main>
<?php require CITY_DIR . "/views/layout/footer.php"; ?>
