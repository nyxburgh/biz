</main>
<footer class="site-footer-main" style="background:#1e1245;color:rgba(255,255,255,0.5);padding:24px 20px;margin-top:40px;border-top:1px solid rgba(255,255,255,0.05)">
  <div style="max-width:1100px;margin:0 auto;font-size:0.75rem">
    © <?= date('Y') ?> BizGuide <?= htmlspecialchars($cityName) ?>. All rights reserved.
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $extraJs ?? '' ?>
</body>
</html>
