<?php $pageTitle = 'Free Users'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<form method="GET" class="filter-bar">
  <div class="flex-grow-1">
    <label class="form-label mb-1 small fw-600">Search</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input type="text" name="search" class="form-control" placeholder="Name, phone…" value="<?= htmlspecialchars($filters['search']) ?>">
    </div>
  </div>
  <div style="min-width:105px">
    <label class="form-label mb-1 small fw-600">Status</label>
    <select name="status" class="form-select"><option value="">All</option>
      <?php foreach(['active','pending','suspended'] as $s): ?>
        <option value="<?= $s ?>" <?= $filters['status']==$s?'selected':'' ?>><?= ucfirst($s) ?></option>
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
  <div class="d-flex gap-2 align-self-end">
    <button type="submit" class="btn btn-p"><i class="bi bi-funnel me-1"></i>Filter</button>
    <a href="<?= BASE_URL ?>/admin/users/free" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
  </div>
</form>

<div class="card">
  <div class="ch d-flex justify-content-between align-items-center">
    <span><i class="bi bi-person me-2"></i>Free Users <span class="badge bg-white text-dark ms-1"><?= $pager['total'] ?></span></span>
    <a href="<?= BASE_URL ?>/admin/users/create" class="btn-ghost btn btn-sm">
      <i class="bi bi-person-plus me-1"></i>Create New User
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead><tr><th>#</th><th>Name</th><th>User Plan</th><th>Profession</th><th>Phone</th><th>City</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if(empty($pager['data'])): ?>
          <tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>No free users.</td></tr>
        <?php else: foreach($pager['data'] as $i => $u): ?>
        <tr>
          <td class="text-muted small"><?= ($pager['current_page']-1)*$pager['per_page']+$i+1 ?></td>
          <td>
            <a href="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>" class="fw-600 text-decoration-none" style="color:#1e1245">
              <?= htmlspecialchars($u['name']) ?>
            </a>
          </td>
          <td>
            <span class="badge bg-primary"><?= htmlspecialchars($u['plan_label'] ?? ucfirst($u['plan_name'] ?? 'Free')) ?></span>
          </td>
          <td class="small"><?= htmlspecialchars($u['profession'] ?? '—') ?></td>
          <td><?= htmlspecialchars($u['phone']) ?></td>
          <td><?= htmlspecialchars($u['city_name'] ?? '—') ?></td>
          <td><?= Helper::statusBadge($u['status']) ?></td>
          <td class="small"><?= Helper::formatDate($u['created_at']) ?></td>
          <td>
            <div class="d-flex gap-1">
              <a href="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>" class="btn btn-sm btn-op" title="View"><i class="bi bi-eye"></i></a>
              <a href="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/edit" class="btn btn-sm btn-op" title="Edit"><i class="bi bi-pencil"></i></a>
              <!-- Upgrade plan inline -->
              <form method="POST" action="<?= BASE_URL ?>/admin/users/upgrade-plan" class="d-flex gap-1">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <select name="plan_id" class="form-select form-select-sm" style="width:90px">
                  <?php foreach($plans as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= $p['label'] ?></option>
                  <?php endforeach ?>
                </select>
                <button class="btn btn-sm btn-success" title="Upgrade"><i class="bi bi-arrow-up-circle"></i></button>
              </form>
              <form method="POST" action="<?= BASE_URL ?>/admin/users/toggle">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <input type="hidden" name="back" value="<?= BASE_URL ?>/admin/users/free">
                <button class="btn btn-sm <?= $u['status']==='active'?'btn-outline-warning':'btn-outline-success' ?>" title="<?= $u['status']==='active'?'Suspend':'Activate' ?>">
                  <i class="bi bi-<?= $u['status']==='active'?'pause-circle':'play-circle' ?>"></i>
                </button>
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
      <small class="text-muted"><?= $pager['total'] ?> free users</small>
      <?= Helper::paginationLinks($pager, BASE_URL.'/admin/users/free?'.http_build_query(array_filter($filters))) ?>
    </div>
  </div>
  <?php endif ?>
</div>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
