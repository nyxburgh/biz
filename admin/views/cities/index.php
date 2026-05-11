<?php $pageTitle = 'Cities'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<form method="GET" class="filter-bar">
  <div class="flex-grow-1">
    <label class="form-label mb-1 small fw-600">Search</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($filters['search']) ?>">
    </div>
  </div>
  <div style="min-width:105px">
    <label class="form-label mb-1 small fw-600">Status</label>
    <select name="status" class="form-select">
      <option value="">All</option>
      <option value="active" <?= $filters['status']=='active'?'selected':'' ?>>Active</option>
      <option value="inactive" <?= $filters['status']=='inactive'?'selected':'' ?>>Inactive</option>
    </select>
  </div>
  <div class="d-flex gap-2 align-self-end">
    <button type="submit" class="btn btn-p"><i class="bi bi-search me-1"></i>Search</button>
    <a href="<?= BASE_URL ?>/admin/cities" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
  </div>
</form>
<div class="card">
  <div class="ch d-flex justify-content-between align-items-center">
    <span><i class="bi bi-geo-alt me-2"></i>Cities <span class="badge bg-white text-dark ms-1"><?= $pager['total'] ?></span></span>
    <button class="btn-ghost btn btn-sm" data-bs-toggle="modal" data-bs-target="#addCityModal">
      <i class="bi bi-plus-circle me-1"></i>Add City
    </button>
  </div>
  <div class="card-body p-0">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>Name</th><th>Slug</th><th>Domain</th><th>Users</th><th>Listings</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if (empty($pager['data'])): ?>
        <tr><td colspan="7" class="text-center py-4 text-muted">No cities yet.</td></tr>
      <?php else: foreach ($pager['data'] as $city): ?>
      <tr>
        <td class="fw-600"><?= htmlspecialchars($city['name']) ?></td>
        <td><code><?= htmlspecialchars($city['slug']) ?></code></td>
        <td class="small text-muted"><?= htmlspecialchars($city['domain'] ?? '—') ?></td>
        <td><?= $city['user_count'] ?></td>
        <td><?= $city['listing_count'] ?></td>
        <td><?= Helper::statusBadge($city['status']) ?></td>
        <td>
          <div class="d-flex gap-1">
            <button class="btn btn-sm btn-op" data-bs-toggle="modal" data-bs-target="#editCityModal"
              data-id="<?= $city['id'] ?>" data-name="<?= htmlspecialchars($city['name'],ENT_QUOTES) ?>"
              data-domain="<?= htmlspecialchars($city['domain']??'',ENT_QUOTES) ?>"
              data-desc="<?= htmlspecialchars($city['description']??'',ENT_QUOTES) ?>"
              data-sort="<?= $city['sort_order'] ?>" data-status="<?= $city['status'] ?>">
              <i class="bi bi-pencil"></i>
            </button>
            <form method="POST" action="<?= BASE_URL ?>/admin/cities/delete" onsubmit="return confirm('Delete this city?')">
              <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
              <input type="hidden" name="id" value="<?= $city['id'] ?>">
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; endif ?>
      </tbody>
    </table>
  </div>
  <?php if ($pager['last_page'] > 1): ?>
  <div class="card-footer" style="background:#f8f7ff">
    <?= Helper::paginationLinks($pager, BASE_URL . '/admin/cities?' . http_build_query(array_filter($filters))) ?>
  </div>
  <?php endif ?>
</div>
<div class="modal fade" id="addCityModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header"><h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add City</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <form method="POST" action="<?= BASE_URL ?>/admin/cities/store">
    <div class="modal-body">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
      <div class="mb-3"><label class="form-label fw-600">City Name *</label><input type="text" name="name" class="form-control" required></div>
      <div class="mb-3"><label class="form-label fw-600">Domain <small class="text-muted">(e.g. kodai.bizguide.in)</small></label><input type="text" name="domain" class="form-control"></div>
      <div class="mb-3"><label class="form-label fw-600">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
      <div class="row g-2">
        <div class="col-6"><label class="form-label fw-600">Sort Order</label><input type="number" name="sort_order" class="form-control" value="0"></div>
        <div class="col-6"><label class="form-label fw-600">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-p"><i class="bi bi-plus me-1"></i>Create & Clone Template</button></div>
  </form>
</div></div></div>
<div class="modal fade" id="editCityModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit City</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <form method="POST" action="<?= BASE_URL ?>/admin/cities/update">
    <div class="modal-body">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
      <input type="hidden" name="id" id="ecityId">
      <div class="mb-3"><label class="form-label fw-600">City Name *</label><input type="text" name="name" id="ecityName" class="form-control" required></div>
      <div class="mb-3"><label class="form-label fw-600">Domain</label><input type="text" name="domain" id="ecityDomain" class="form-control"></div>
      <div class="mb-3"><label class="form-label fw-600">Description</label><textarea name="description" id="ecityDesc" class="form-control" rows="2"></textarea></div>
      <div class="row g-2">
        <div class="col-6"><label class="form-label fw-600">Sort Order</label><input type="number" name="sort_order" id="ecitySort" class="form-control"></div>
        <div class="col-6"><label class="form-label fw-600">Status</label><select name="status" id="ecityStatus" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-p"><i class="bi bi-save me-1"></i>Update</button></div>
  </form>
</div></div></div>
<?php $extraJs = '<script>
document.getElementById("editCityModal").addEventListener("show.bs.modal",function(e){
  var b=e.relatedTarget;
  document.getElementById("ecityId").value=b.dataset.id;
  document.getElementById("ecityName").value=b.dataset.name;
  document.getElementById("ecityDomain").value=b.dataset.domain||"";
  document.getElementById("ecityDesc").value=b.dataset.desc||"";
  document.getElementById("ecitySort").value=b.dataset.sort;
  document.getElementById("ecityStatus").value=b.dataset.status;
});
</script>' ?>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
