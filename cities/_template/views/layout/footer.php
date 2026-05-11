</main>
<footer class="site-footer-main" style="background:linear-gradient(135deg,#2d1b69,#1e1245);color:rgba(255,255,255,0.7);padding:40px 20px 24px;margin-top:40px">
  <div style="max-width:1100px;margin:0 auto">
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:28px;margin-bottom:28px">
      <div>
        <h3 style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.1rem;color:#fff;margin-bottom:8px">
          BizGuide <span style="color:#a78bfa"><?= htmlspecialchars($cityName) ?></span>
        </h3>
        <p style="font-size:0.8rem;line-height:1.7;max-width:220px">Your trusted local business directory.</p>
      </div>
      <div>
        <h4 style="font-size:0.8rem;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:10px">Quick Links</h4>
        <div style="display:flex;flex-direction:column;gap:6px;font-size:0.8rem">
          <a href="<?= $cityUrl ?>" style="color:rgba(255,255,255,0.55)">Home</a>
          <a href="<?= $cityUrl ?>/search" style="color:rgba(255,255,255,0.55)">All Businesses</a>
          <a href="<?= $cityUrl ?>/post-ad" style="color:rgba(255,255,255,0.55)">Post Free Ad</a>
          <a href="<?= $cityUrl ?>/login" style="color:rgba(255,255,255,0.55)">Login / Register</a>
        </div>
      </div>
      <div>
        <h4 style="font-size:0.8rem;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:10px">Plans</h4>
        <div style="display:flex;flex-direction:column;gap:6px;font-size:0.8rem">
          <a href="<?= $cityUrl ?>/post-ad" style="color:rgba(255,255,255,0.55)">Free Listing</a>
          <a href="<?= $cityUrl ?>/upgrade" style="color:rgba(255,255,255,0.55)">Basic — ₹299</a>
          <a href="<?= $cityUrl ?>/upgrade" style="color:rgba(255,255,255,0.55)">Premium — ₹599</a>
          <a href="<?= $cityUrl ?>/upgrade" style="color:rgba(255,255,255,0.55)">Pro — ₹999</a>
        </div>
      </div>
    </div>
    <div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:18px;font-size:0.76rem;color:rgba(255,255,255,0.4)">
      © <?= date('Y') ?> BizGuide <?= htmlspecialchars($cityName) ?>. All rights reserved.
    </div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $extraJs ?? '' ?>
</body>
</html>
