<?php $pageTitle = 'Plans'; require BASE_PATH . '/admin/views/layout/header.php'; ?>

<div class="row g-3">
<?php foreach ($plans as $plan):
  $colors = ['free'=>'#6b7280','basic'=>'#0ea5e9','premium'=>'#f59e0b','pro'=>'#10b981'];
  $bgs    = ['free'=>'#f3f4f6','basic'=>'#e0f2fe','premium'=>'#fef3c7','pro'=>'#d1fae5'];
  $clr    = $colors[$plan['name']] ?? '#7c3aed';
  $bg     = $bgs[$plan['name']]    ?? '#ede9fe';
?>
<div class="col-sm-6 col-xl-3">
  <div class="card h-100">
    <div class="card-body">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div style="width:48px;height:48px;border-radius:12px;background:<?= $bg ?>;
             display:flex;align-items:center;justify-content:center;font-size:1.4rem">
          <?= ['free'=>'🆓','basic'=>'⭐','premium'=>'💎','pro'=>'🚀'][$plan['name']] ?? '📦' ?>
        </div>
        <div>
          <div class="fw-700" style="color:<?= $clr ?>;font-size:1rem"><?= htmlspecialchars($plan['label']) ?></div>
          <small class="text-muted"><?= $plan['user_count'] ?> users</small>
        </div>
        <div class="ms-auto">
          <?= Helper::statusBadge($plan['status']) ?>
        </div>
      </div>

      <div class="text-center mb-3 p-3 rounded" style="background:<?= $bg ?>">
        <div style="font-size:2rem;font-weight:900;color:<?= $clr ?>">
          ₹<?= number_format($plan['price'], 0) ?>
        </div>
        <div class="small text-muted"><?= $plan['duration_days'] ?> days validity</div>
      </div>

      <button class="btn btn-p w-100 btn-sm"
        data-bs-toggle="modal" data-bs-target="#editPlanModal"
        data-id="<?= $plan['id'] ?>"
        data-label="<?= htmlspecialchars($plan['label'], ENT_QUOTES) ?>"
        data-price="<?= $plan['price'] ?>"
        data-days="<?= $plan['duration_days'] ?>"
        data-status="<?= $plan['status'] ?>"
        data-name="<?= $plan['name'] ?>">
        <i class="bi bi-pencil me-1"></i>Edit Plan
      </button>
    </div>
  </div>
</div>
<?php endforeach ?>
</div>

<div class="alert alert-info mt-3 small">
  <i class="bi bi-info-circle me-2"></i>
  Plan <strong>names</strong> (free/basic/premium/pro) are fixed — they control which features unlock.
  You can freely change <strong>label, price, and validity</strong>.
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editPlanModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Plan — <span id="epName"></span></h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>/admin/plans/update">
      <div class="modal-body">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <input type="hidden" name="id" id="epId">
        <div class="mb-3">
          <label class="form-label fw-600">Display Label</label>
          <input type="text" name="label" id="epLabel" class="form-control" required>
        </div>
        <div class="row g-3 mb-3">
          <div class="col-6">
            <label class="form-label fw-600">Price (₹)</label>
            <div class="input-group">
              <span class="input-group-text">₹</span>
              <input type="number" name="price" id="epPrice" class="form-control" min="0" step="1" required>
            </div>
          </div>
          <div class="col-6">
            <label class="form-label fw-600">Validity (days)</label>
            <div class="input-group">
              <input type="number" name="duration_days" id="epDays" class="form-control" min="1" required>
              <span class="input-group-text">days</span>
            </div>
          </div>
        </div>
        <div class="mb-1">
          <label class="form-label fw-600">Status</label>
          <select name="status" id="epStatus" class="form-select">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-p"><i class="bi bi-save me-1"></i>Save Changes</button>
      </div>
    </form>
  </div></div>
</div>

<?php $extraJs = '<script>
document.getElementById("editPlanModal").addEventListener("show.bs.modal", function(e) {
  var b = e.relatedTarget;
  document.getElementById("epId").value     = b.dataset.id;
  document.getElementById("epName").textContent = b.dataset.name;
  document.getElementById("epLabel").value  = b.dataset.label;
  document.getElementById("epPrice").value  = b.dataset.price;
  document.getElementById("epDays").value   = b.dataset.days;
  document.getElementById("epStatus").value = b.dataset.status;
});
</script>' ?>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
