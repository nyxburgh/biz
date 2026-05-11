<?php $pageTitle = 'Payments'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<?php
$conf = Database::fetchOne("SELECT COUNT(*) AS c, COALESCE(SUM(amount),0) AS s FROM payments WHERE status='confirmed'");
$pend = Database::fetchOne("SELECT COUNT(*) AS c FROM payments WHERE status='pending'");
?>
<div class="row g-3 mb-3">
  <div class="col-sm-4">
    <div class="stat-card">
      <div class="ib" style="background:#d1fae5;color:#10b981"><i class="bi bi-currency-rupee"></i></div>
      <div><div class="val" style="color:#10b981">&#8377;<?= number_format($conf['s']) ?></div><div class="lbl">Total Revenue</div></div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="stat-card">
      <div class="ib" style="background:#fef3c7;color:#f59e0b"><i class="bi bi-hourglass-split"></i></div>
      <div><div class="val" style="color:#f59e0b"><?= $pend['c'] ?></div><div class="lbl">Pending</div></div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="stat-card">
      <div class="ib" style="background:#ede9fe;color:#7c3aed"><i class="bi bi-check2-all"></i></div>
      <div><div class="val" style="color:#7c3aed"><?= $conf['c'] ?></div><div class="lbl">Confirmed</div></div>
    </div>
  </div>
</div>

<form method="GET" class="filter-bar">
  <div class="flex-grow-1" style="min-width:150px">
    <label class="form-label mb-1 small fw-600">Search</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input type="text" name="search" class="form-control" placeholder="User, phone, reference…" value="<?= htmlspecialchars($filters['search']) ?>">
    </div>
  </div>
  <div style="min-width:105px">
    <label class="form-label mb-1 small fw-600">Status</label>
    <select name="status" class="form-select"><option value="">All</option>
      <?php foreach (['pending','confirmed','rejected'] as $s): ?>
        <option value="<?= $s ?>" <?= $filters['status'] == $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div style="min-width:105px">
    <label class="form-label mb-1 small fw-600">Plan</label>
    <select name="plan" class="form-select"><option value="">All</option>
      <?php foreach ($plans as $p): ?>
        <option value="<?= $p['name'] ?>" <?= $filters['plan'] == $p['name'] ? 'selected' : '' ?>><?= $p['label'] ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div style="min-width:120px">
    <label class="form-label mb-1 small fw-600">From</label>
    <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($filters['from']) ?>">
  </div>
  <div style="min-width:120px">
    <label class="form-label mb-1 small fw-600">To</label>
    <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($filters['to']) ?>">
  </div>
  <div class="d-flex gap-2 align-self-end">
    <button type="submit" class="btn btn-p"><i class="bi bi-funnel me-1"></i>Filter</button>
    <a href="<?= BASE_URL ?>/admin/payments" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
  </div>
</form>

<div class="card">
  <div class="ch"><i class="bi bi-credit-card me-2"></i>Payments <span class="badge bg-white text-dark ms-2"><?= $pager['total'] ?></span></div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead><tr><th>#</th><th>User</th><th>Plan</th><th>Amount</th><th>Mode</th><th>Reference</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
        <?php if (empty($pager['data'])): ?>
          <tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>No payments found</td></tr>
        <?php else: foreach ($pager['data'] as $i => $pay): ?>
        <tr>
          <td class="text-muted small"><?= ($pager['current_page'] - 1) * $pager['per_page'] + $i + 1 ?></td>
          <td>
            <div class="fw-600"><?= htmlspecialchars($pay['user_name']) ?></div>
            <small class="text-muted"><?= htmlspecialchars($pay['user_phone'] ?? '') ?></small>
          </td>
          <td><?= Helper::planBadge($pay['plan_name'] ?? 'free') ?></td>
          <td class="fw-700" style="color:#10b981">&#8377;<?= number_format($pay['amount'], 2) ?></td>
          <td><?= htmlspecialchars($pay['payment_mode'] ?? '—') ?></td>
          <td><code class="small"><?= htmlspecialchars($pay['reference'] ?? '—') ?></code></td>
          <td><?= Helper::statusBadge($pay['status']) ?></td>
          <td class="small"><?= Helper::formatDate($pay['created_at']) ?></td>
          <td>
            <?php if ($pay['status'] === 'pending'): ?>
            <div class="d-flex gap-1">
              <form method="POST" action="<?= BASE_URL ?>/admin/payments/confirm">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <input type="hidden" name="id" value="<?= $pay['id'] ?>">
                <button class="btn btn-sm btn-success" title="Confirm"><i class="bi bi-check2-circle"></i></button>
              </form>
              <button class="btn btn-sm btn-outline-danger" title="Reject"
                data-bs-toggle="modal" data-bs-target="#rejectPayModal" data-id="<?= $pay['id'] ?>">
                <i class="bi bi-x-circle"></i>
              </button>
            </div>
            <?php else: ?>
              <span class="text-muted small"><?= ucfirst($pay['status']) ?></span>
            <?php endif ?>
          </td>
        </tr>
        <?php endforeach; endif ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if ($pager['last_page'] > 1): ?>
  <div class="card-footer" style="background:#f8f7ff">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <small class="text-muted"><?= $pager['total'] ?> records</small>
      <?= Helper::paginationLinks($pager, BASE_URL . '/admin/payments?' . http_build_query(array_filter($filters))) ?>
    </div>
  </div>
  <?php endif ?>
</div>

<div class="modal fade" id="rejectPayModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Reject Payment</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="<?= BASE_URL ?>/admin/payments/reject">
      <div class="modal-body">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <input type="hidden" name="id" id="rpId">
        <label class="form-label fw-600">Rejection Note</label>
        <textarea name="note" class="form-control" rows="3" placeholder="Reason…"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Reject</button>
      </div>
    </form>
  </div></div>
</div>
<?php $extraJs = '<script>
document.getElementById("rejectPayModal").addEventListener("show.bs.modal", function(e) {
  document.getElementById("rpId").value = e.relatedTarget.dataset.id;
});
</script>' ?>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
