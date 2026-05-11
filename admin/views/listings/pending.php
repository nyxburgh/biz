<?php $pageTitle = 'Pending Approvals'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<div class="card">
  <div class="ch">
    <i class="bi bi-hourglass-split me-2"></i>Awaiting Approval
    <span class="badge bg-warning text-dark ms-2"><?= $pager['total'] ?></span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead><tr><th>#</th><th>Business</th><th>Owner</th><th>Phone</th><th>City</th><th>Plan</th><th>Submitted</th><th>Action</th></tr></thead>
        <tbody>
        <?php if (empty($pager['data'])): ?>
          <tr><td colspan="8" class="text-center py-5 text-muted">
            <i class="bi bi-check2-all fs-2 d-block mb-2 text-success"></i>All clear — no pending listings!
          </td></tr>
        <?php else: foreach ($pager['data'] as $i => $l): ?>
        <tr>
          <td class="text-muted small"><?= $i + 1 ?></td>
          <td class="fw-600"><?= htmlspecialchars($l['business_name'] ?? 'Draft') ?></td>
          <td><?= htmlspecialchars($l['user_name']) ?></td>
          <td><?= htmlspecialchars($l['user_phone'] ?? '') ?></td>
          <td><?= htmlspecialchars($l['city_name'] ?? '—') ?></td>
          <td><?= Helper::planBadge($l['plan_level']) ?></td>
          <td class="small"><?= Helper::timeAgo($l['created_at']) ?></td>
          <td>
            <a href="<?= BASE_URL ?>/admin/listings/<?= $l['id'] ?>" class="btn btn-sm btn-p">
              <i class="bi bi-eye me-1"></i>Review
            </a>
          </td>
        </tr>
        <?php endforeach; endif ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if ($pager['last_page'] > 1): ?>
  <div class="card-footer" style="background:#f8f7ff">
    <?= Helper::paginationLinks($pager, BASE_URL . '/admin/listings/pending') ?>
  </div>
  <?php endif ?>
</div>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
