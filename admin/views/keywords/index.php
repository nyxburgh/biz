<?php $pageTitle = 'Keywords'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<form method="GET" class="filter-bar">
  <div class="flex-grow-1">
    <label class="form-label mb-1 small fw-600">Search</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($filters['search']) ?>">
    </div>
  </div>
  <div style="min-width:140px">
    <label class="form-label mb-1 small fw-600">Category</label>
    <select name="category" class="form-select"><option value="">All</option>
      <?php foreach ($cats as $c): ?>
        <option value="<?= $c['id'] ?>" <?= $filters['category']==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div style="min-width:105px">
    <label class="form-label mb-1 small fw-600">Status</label>
    <select name="status" class="form-select"><option value="">All</option>
      <option value="active" <?= $filters['status']=='active'?'selected':'' ?>>Active</option>
      <option value="inactive" <?= $filters['status']=='inactive'?'selected':'' ?>>Inactive</option>
    </select>
  </div>
  <div class="d-flex gap-2 align-self-end">
    <button type="submit" class="btn btn-p"><i class="bi bi-search me-1"></i>Search</button>
    <a href="<?= BASE_URL ?>/admin/keywords" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
  </div>
</form>
<div class="card">
  <div class="ch d-flex justify-content-between align-items-center">
    <span><i class="bi bi-tags me-2"></i>Keywords <span class="badge bg-white text-dark ms-1"><?= $pager['total'] ?></span></span>
    <div class="d-flex gap-2">
      <a href="<?= BASE_URL ?>/admin/keywords/suggestions" class="btn-ghost btn btn-sm"><i class="bi bi-lightbulb me-1"></i>Suggestions</a>
      <button class="btn-ghost btn btn-sm" data-bs-toggle="modal" data-bs-target="#addKwModal"><i class="bi bi-plus-circle me-1"></i>Add</button>
    </div>
  </div>
  <div class="card-body p-0">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>Keyword</th><th>Category</th><th>Subcategory</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if (empty($pager['data'])): ?>
        <tr><td colspan="5" class="text-center py-4 text-muted">No keywords yet.</td></tr>
      <?php else: foreach ($pager['data'] as $k): ?>
      <tr>
        <td><span class="badge" style="background:#ede9fe;color:#2d1b69;font-size:.85rem"><?= htmlspecialchars($k['name']) ?></span></td>
        <td><?= htmlspecialchars($k['cat_name'] ?? '—') ?></td>
        <td><?= htmlspecialchars($k['sub_name'] ?? '—') ?></td>
        <td><?= Helper::statusBadge($k['status']) ?></td>
        <td>
          <div class="d-flex gap-1">
            <button class="btn btn-sm btn-op" data-bs-toggle="modal" data-bs-target="#editKwModal"
              data-id="<?= $k['id'] ?>" data-name="<?= htmlspecialchars($k['name'],ENT_QUOTES) ?>"
              data-cat="<?= $k['category_id'] ?? '' ?>" data-status="<?= $k['status'] ?>">
              <i class="bi bi-pencil"></i>
            </button>
            <form method="POST" action="<?= BASE_URL ?>/admin/keywords/delete" onsubmit="return confirm('Delete this keyword?')">
              <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
              <input type="hidden" name="id" value="<?= $k['id'] ?>">
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
    <?= Helper::paginationLinks($pager, BASE_URL . '/admin/keywords?' . http_build_query(array_filter($filters))) ?>
  </div>
  <?php endif ?>
</div>
<div class="modal fade" id="addKwModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header"><h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Keyword</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <form method="POST" action="<?= BASE_URL ?>/admin/keywords/store">
    <div class="modal-body">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
      <div class="mb-3"><label class="form-label fw-600">Keyword *</label><input type="text" name="name" class="form-control" required></div>
      <div class="mb-3"><label class="form-label fw-600">Category</label>
        <select name="category_id" class="form-select"><option value="">— None —</option>
          <?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option><?php endforeach ?>
        </select>
      </div>
      <div class="mb-3"><label class="form-label fw-600">Status</label>
        <select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-p"><i class="bi bi-plus me-1"></i>Add Keyword</button></div>
  </form>
</div></div></div>
<div class="modal fade" id="editKwModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Keyword</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <form method="POST" action="<?= BASE_URL ?>/admin/keywords/update">
    <div class="modal-body">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
      <input type="hidden" name="id" id="ekId">
      <div class="mb-3"><label class="form-label fw-600">Keyword *</label><input type="text" name="name" id="ekName" class="form-control" required></div>
      <div class="mb-3"><label class="form-label fw-600">Category</label>
        <select name="category_id" id="ekCat" class="form-select"><option value="">— None —</option>
          <?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option><?php endforeach ?>
        </select>
      </div>
      <div class="mb-3"><label class="form-label fw-600">Status</label>
        <select name="status" id="ekStatus" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-p"><i class="bi bi-save me-1"></i>Update</button></div>
  </form>
</div></div></div>
<?php $extraJs = '<script>
document.getElementById("editKwModal").addEventListener("show.bs.modal",function(e){
  var b=e.relatedTarget;
  document.getElementById("ekId").value=b.dataset.id;
  document.getElementById("ekName").value=b.dataset.name;
  document.getElementById("ekCat").value=b.dataset.cat||"";
  document.getElementById("ekStatus").value=b.dataset.status;
});
</script>' ?>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
