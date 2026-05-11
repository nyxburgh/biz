<?php $pageTitle = 'Expired Ads'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<div class="card">
  <div class="ch"><i class="bi bi-clock-history me-2"></i>Expired Ads <span class="badge bg-white text-dark ms-1"><?= $pager['total'] ?></span></div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead><tr><th>#</th><th>Business</th><th>Owner</th><th>Plan</th><th>City</th><th>Expired</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if(empty($pager['data'])): ?>
          <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>No expired ads.</td></tr>
        <?php else: foreach($pager['data'] as $i => $l): ?>
        <tr>
          <td class="text-muted small"><?= ($pager['current_page']-1)*$pager['per_page']+$i+1 ?></td>
          <td>
            <div class="fw-600"><?= htmlspecialchars($l['business_name'] ?? '—') ?></div>
            <div class="small text-muted"><?= htmlspecialchars($l['category_name'] ?? '') ?></div>
          </td>
          <td>
            <a href="<?= BASE_URL ?>/admin/users/<?= $l['user_id'] ?>" class="text-decoration-none small">
              <?= htmlspecialchars($l['user_name'] ?? '—') ?>
            </a>
            <div class="small text-muted"><?= htmlspecialchars($l['user_phone'] ?? '') ?></div>
          </td>
          <td><?= Helper::planBadge($l['plan_level']) ?></td>
          <td class="small"><?= htmlspecialchars($l['city_name'] ?? '—') ?></td>
          <td class="small text-danger fw-600"><?= Helper::formatDate($l['plan_expires_at']) ?></td>
          <td>
            <a href="<?= BASE_URL ?>/admin/listings/<?= $l['id'] ?>" class="btn btn-sm btn-op" title="View"><i class="bi bi-eye"></i></a>
          </td>
        </tr>
        <?php endforeach; endif ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if($pager['last_page']>1): ?>
  <div class="card-footer" style="background:#f8f7ff">
    <?= Helper::paginationLinks($pager, BASE_URL.'/admin/listings/expired') ?>
  </div>
  <?php endif ?>
</div>
