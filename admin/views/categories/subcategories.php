<?php $pageTitle = 'Subcategories — ' . $cat['name']; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<div class="d-flex align-items-center gap-2 mb-3">
  <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Categories</a>
  <span class="text-muted">/</span>
  <strong><?= htmlspecialchars($cat['name']) ?></strong>
</div>
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
    <a href="<?= BASE_URL ?>/admin/categories/<?= $cat['id'] ?>/subcategories" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
  </div>
</form>
<div class="card">
  <div class="ch d-flex justify-content-between align-items-center">
    <span><i class="bi bi-grid-3x3-gap me-2"></i><?= htmlspecialchars($cat['name']) ?> — Subcategories <span class="badge bg-white text-dark ms-1"><?= $pager['total'] ?></span></span>
    <button class="btn-ghost btn btn-sm" data-bs-toggle="modal" data-bs-target="#addSubModal"><i class="bi bi-plus-circle me-1"></i>Add</button>
  </div>
  <div class="card-body p-0">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>Name</th><th>Slug</th><th>Sort</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if (empty($pager['data'])): ?>
        <tr><td colspan="5" class="text-center py-4 text-muted">No subcategories yet.</td></tr>
      <?php else: foreach ($pager['data'] as $s): ?>
      <tr>
        <td class="fw-600"><?= htmlspecialchars($s['name']) ?></td>
        <td><code><?= htmlspecialchars($s['slug']) ?></code></td>
        <td><?= $s['sort_order'] ?></td>
        <td><?= Helper::statusBadge($s['status']) ?></td>
        <td>
          <div class="d-flex gap-1">
            <button class="btn btn-sm btn-op" data-bs-toggle="modal" data-bs-target="#editSubModal"
              data-id="<?= $s['id'] ?>" data-name="<?= htmlspecialchars($s['name'],ENT_QUOTES) ?>"
              data-sort="<?= $s['sort_order'] ?>" data-status="<?= $s['status'] ?>">
              <i class="bi bi-pencil"></i>
            </button>
            <form method="POST" action="<?= BASE_URL ?>/admin/categories/subcategories/delete" onsubmit="return confirm('Delete this subcategory?')">
              <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
              <input type="hidden" name="id" value="<?= $s['id'] ?>">
              <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
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
    <?= Helper::paginationLinks($pager, BASE_URL . '/admin/categories/' . $cat['id'] . '/subcategories?' . http_build_query(array_filter($filters))) ?>
  </div>
  <?php endif ?>
</div>
<div class="modal fade" id="addSubModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header"><h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Subcategory</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <form method="POST" action="<?= BASE_URL ?>/admin/categories/subcategories/store">
    <div class="modal-body">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
      <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
      <div class="mb-3"><label class="form-label fw-600">Name *</label><input type="text" name="name" class="form-control" required></div>
      <div class="row g-2">
        <div class="col-6"><label class="form-label fw-600">Sort Order</label><input type="number" name="sort_order" class="form-control" value="0"></div>
        <div class="col-6"><label class="form-label fw-600">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-p"><i class="bi bi-plus me-1"></i>Create</button></div>
  </form>
</div></div></div>
<div class="modal fade" id="editSubModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Subcategory</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <form method="POST" action="<?= BASE_URL ?>/admin/categories/subcategories/update">
    <div class="modal-body">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
      <input type="hidden" name="id" id="esId">
      <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
      <div class="mb-3"><label class="form-label fw-600">Name *</label><input type="text" name="name" id="esName" class="form-control" required></div>
      <div class="row g-2">
        <div class="col-6"><label class="form-label fw-600">Sort Order</label><input type="number" name="sort_order" id="esSort" class="form-control"></div>
        <div class="col-6"><label class="form-label fw-600">Status</label><select name="status" id="esStatus" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-p"><i class="bi bi-save me-1"></i>Update</button></div>
  </form>
</div></div></div>
<?php $extraJs = '<script>
document.getElementById("editSubModal").addEventListener("show.bs.modal",function(e){
  var b=e.relatedTarget;
  document.getElementById("esId").value=b.dataset.id;
  document.getElementById("esName").value=b.dataset.name;
  document.getElementById("esSort").value=b.dataset.sort;
  document.getElementById("esStatus").value=b.dataset.status;
});
</script>' ?>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
