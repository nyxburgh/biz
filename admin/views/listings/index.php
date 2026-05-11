<?php $pageTitle = 'Active Ads'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<form method="GET" class="filter-bar">
  <div class="flex-grow-1">
    <label class="form-label mb-1 small fw-600">Search</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input type="text" name="search" class="form-control" placeholder="Business name, phone…" value="<?= htmlspecialchars($filters['search']) ?>">
    </div>
  </div>
  <div style="min-width:110px">
    <label class="form-label mb-1 small fw-600">Plan</label>
    <select name="plan" class="form-select"><option value="">All</option>
      <?php foreach(['basic','premium','pro'] as $p): ?>
        <option value="<?= $p ?>" <?= $filters['plan']==$p?'selected':'' ?>><?= ucfirst($p) ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div style="min-width:110px">
    <label class="form-label mb-1 small fw-600">City</label>
    <select name="city" class="form-select"><option value="">All</option>
      <?php foreach($cities as $c): ?>
        <option value="<?= $c['id'] ?>" <?= $filters['city']==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div style="min-width:110px">
    <label class="form-label mb-1 small fw-600">Category</label>
    <select name="category" class="form-select"><option value="">All</option>
      <?php foreach($cats as $c): ?>
        <option value="<?= $c['id'] ?>" <?= $filters['category']==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div class="d-flex gap-2 align-self-end">
    <button type="submit" class="btn btn-p"><i class="bi bi-funnel me-1"></i>Filter</button>
    <a href="<?= BASE_URL ?>/admin/listings" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
  </div>
</form>

<div class="card">
  <div class="ch"><i class="bi bi-building me-2"></i>Active Ads <span class="badge bg-white text-dark ms-1"><?= $pager['total'] ?></span></div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead><tr><th>#</th><th>Business</th><th>Owner</th><th>Plan</th><th>City</th><th>Published</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if(empty($pager['data'])): ?>
          <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>No active ads.</td></tr>
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
          <td class="small"><?= $l['published_at'] ? Helper::formatDate($l['published_at']) : '—' ?></td>
          <td>
            <div class="d-flex gap-1">
              <a href="<?= BASE_URL ?>/admin/listings/<?= $l['id'] ?>" class="btn btn-sm btn-op" title="View"><i class="bi bi-eye"></i></a>
              <a href="<?= BASE_URL ?>/admin/listings/<?= $l['id'] ?>/edit" class="btn btn-sm btn-op" title="Edit"><i class="bi bi-pencil"></i></a>
              <form method="POST" action="<?= BASE_URL ?>/admin/listings/suspend">
                <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">
                <input type="hidden" name="id" value="<?= $l['id'] ?>">
                <button class="btn btn-sm btn-outline-warning" title="Suspend"><i class="bi bi-pause-circle"></i></button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; endif ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if($pager['last_page']>1): ?>
  <div class="card-footer" style="background:#f8f7ff">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <small class="text-muted"><?= $pager['total'] ?> active ads</small>
      <?= Helper::paginationLinks($pager, BASE_URL.'/admin/listings?'.http_build_query(array_filter($filters))) ?>
    </div>
  </div>
  <?php endif ?>
</div>
