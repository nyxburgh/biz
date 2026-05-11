<?php $pageTitle = 'Categories'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<div class="row g-3">
<div class="col-lg-9">
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
    <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
  </div>
</form>
<div class="card">
  <div class="ch d-flex justify-content-between align-items-center">
    <span><i class="bi bi-grid me-2"></i>Categories <span class="badge bg-white text-dark ms-1"><?= $pager['total'] ?></span></span>
    <button class="btn-ghost btn btn-sm" data-bs-toggle="modal" data-bs-target="#addCatModal">
      <i class="bi bi-plus-circle me-1"></i>Add Category
    </button>
  </div>
  <div class="card-body p-0">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>Name</th><th>Slug</th><th>Subcats</th><th>Listings</th><th>Sort</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if (empty($pager['data'])): ?>
        <tr><td colspan="7" class="text-center py-4 text-muted">No categories yet.</td></tr>
      <?php else: foreach ($pager['data'] as $cat): ?>
      <tr>
        <td class="fw-600"><?= htmlspecialchars($cat['name']) ?></td>
        <td><code><?= htmlspecialchars($cat['slug']) ?></code></td>
        <td><a href="<?= BASE_URL ?>/admin/categories/<?= $cat['id'] ?>/subcategories" class="badge bg-info text-decoration-none"><?= $cat['sub_count'] ?> subcats</a></td>
        <td><span class="badge bg-secondary"><?= $cat['listing_count'] ?></span></td>
        <td><?= $cat['sort_order'] ?></td>
        <td><?= Helper::statusBadge($cat['status']) ?></td>
        <td>
          <div class="d-flex gap-1">
            <button class="btn btn-sm btn-op" data-bs-toggle="modal" data-bs-target="#editCatModal"
              data-id="<?= $cat['id'] ?>" data-name="<?= htmlspecialchars($cat['name'],ENT_QUOTES) ?>"
              data-desc="<?= htmlspecialchars($cat['description']??'',ENT_QUOTES) ?>"
              data-sort="<?= $cat['sort_order'] ?>" data-status="<?= $cat['status'] ?>">
              <i class="bi bi-pencil"></i>
            </button>
            <form method="POST" action="<?= BASE_URL ?>/admin/categories/delete" onsubmit="return confirm('Delete this category and all its subcategories?')">
              <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
              <input type="hidden" name="id" value="<?= $cat['id'] ?>">
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
    <?= Helper::paginationLinks($pager, BASE_URL . '/admin/categories?' . http_build_query(array_filter($filters))) ?>
  </div>
  <?php endif ?>
</div>
</div>
<div class="col-lg-3">
  <div class="card">
    <div class="ch"><i class="bi bi-bar-chart me-2"></i>Quick Stats</div>
    <div class="card-body small">
      <?php
      $tc = Database::fetchOne("SELECT COUNT(*) AS c FROM categories")['c'];
      $ts = Database::fetchOne("SELECT COUNT(*) AS c FROM subcategories")['c'];
      $tk = Database::fetchOne("SELECT COUNT(*) AS c FROM keywords")['c'];
      $ps = Database::fetchOne("SELECT COUNT(*) AS c FROM keyword_suggestions WHERE status='pending'")['c'];
      foreach ([['Categories',$tc,'#ede9fe'],['Subcategories',$ts,'#e0f2fe'],['Keywords',$tk,'#d1fae5'],['Pending Sugg.',$ps,'#fef3c7']] as [$lbl,$val,$bg]): ?>
      <div class="d-flex justify-content-between align-items-center p-2 rounded mb-2" style="background:<?= $bg ?>">
        <span><?= $lbl ?></span><strong><?= $val ?></strong>
      </div>
      <?php endforeach ?>
      <a href="<?= BASE_URL ?>/admin/keywords/suggestions" class="btn btn-sm btn-p mt-1 w-100">
        <i class="bi bi-lightbulb me-1"></i>View Suggestions
      </a>
    </div>
  </div>
</div>
</div>
<div class="modal fade" id="addCatModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header"><h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <form method="POST" action="<?= BASE_URL ?>/admin/categories/store">
    <div class="modal-body">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
      <div class="mb-3"><label class="form-label fw-600">Name *</label><input type="text" name="name" class="form-control" required></div>
      <div class="mb-3"><label class="form-label fw-600">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
      <div class="row g-2">
        <div class="col-6"><label class="form-label fw-600">Sort Order</label><input type="number" name="sort_order" class="form-control" value="0"></div>
        <div class="col-6"><label class="form-label fw-600">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-p"><i class="bi bi-plus me-1"></i>Create</button></div>
  </form>
</div></div></div>
<div class="modal fade" id="editCatModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <form method="POST" action="<?= BASE_URL ?>/admin/categories/update">
    <div class="modal-body">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
      <input type="hidden" name="id" id="ecId">
      <div class="mb-3"><label class="form-label fw-600">Name *</label><input type="text" name="name" id="ecName" class="form-control" required></div>
      <div class="mb-3"><label class="form-label fw-600">Description</label><textarea name="description" id="ecDesc" class="form-control" rows="2"></textarea></div>
      <div class="row g-2">
        <div class="col-6"><label class="form-label fw-600">Sort Order</label><input type="number" name="sort_order" id="ecSort" class="form-control"></div>
        <div class="col-6"><label class="form-label fw-600">Status</label><select name="status" id="ecStatus" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
      </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-p"><i class="bi bi-save me-1"></i>Update</button></div>
  </form>
</div></div></div>
<?php $extraJs = '<script>
document.getElementById("editCatModal").addEventListener("show.bs.modal",function(e){
  var b=e.relatedTarget;
  document.getElementById("ecId").value=b.dataset.id;
  document.getElementById("ecName").value=b.dataset.name;
  document.getElementById("ecDesc").value=b.dataset.desc;
  document.getElementById("ecSort").value=b.dataset.sort;
  document.getElementById("ecStatus").value=b.dataset.status;
});
</script>' ?>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
