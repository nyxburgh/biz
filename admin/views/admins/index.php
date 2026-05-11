<?php $pageTitle = 'Admin Management'; require BASE_PATH . '/admin/views/layout/header.php'; ?>

<div class="card">
  <div class="ch d-flex justify-content-between align-items-center">
    <span><i class="bi bi-shield-lock me-2"></i>Admin Accounts</span>
    <button class="btn-ghost btn btn-sm" data-bs-toggle="modal" data-bs-target="#addAdminModal">
      <i class="bi bi-plus-circle me-1"></i>Add Admin
    </button>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>City</th><th>Status</th><th>Last Login</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach($admins as $i => $a): ?>
        <tr>
          <td class="text-muted small"><?= $i+1 ?></td>
          <td class="fw-600"><?= htmlspecialchars($a['name']) ?></td>
          <td class="small"><?= htmlspecialchars($a['email']) ?></td>
          <td>
            <?php if($a['role']==='super_admin'): ?>
              <span class="badge" style="background:#7c3aed">Super Admin</span>
            <?php else: ?>
              <span class="badge bg-info text-dark">City Admin</span>
            <?php endif ?>
          </td>
          <td class="small"><?= htmlspecialchars($a['city_name'] ?? '—') ?></td>
          <td><?= Helper::statusBadge($a['status']) ?></td>
          <td class="small"><?= $a['last_login_at'] ? Helper::timeAgo($a['last_login_at']) : 'Never' ?></td>
          <td>
            <div class="d-flex gap-1">
              <?php if($a['id'] !== Auth::id()): ?>
                <button class="btn btn-sm btn-op" title="Edit"
                  data-bs-toggle="modal" data-bs-target="#editAdminModal"
                  data-id="<?= $a['id'] ?>"
                  data-name="<?= htmlspecialchars($a['name'], ENT_QUOTES) ?>"
                  data-role="<?= $a['role'] ?>"
                  data-city="<?= $a['assigned_city_id'] ?>"
                  data-status="<?= $a['status'] ?>">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-secondary" title="Reset Password"
                  data-bs-toggle="modal" data-bs-target="#resetPwModal"
                  data-id="<?= $a['id'] ?>" data-name="<?= htmlspecialchars($a['name'], ENT_QUOTES) ?>">
                  <i class="bi bi-key"></i>
                </button>
                <form method="POST" action="<?= BASE_URL ?>/admin/admins/delete"
                      onsubmit="return confirm('Delete <?= htmlspecialchars($a['name'], ENT_QUOTES) ?>?')">
                  <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                  <input type="hidden" name="id" value="<?= $a['id'] ?>">
                  <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                </form>
              <?php else: ?>
                <span class="badge bg-light text-muted">You</span>
              <?php endif ?>
            </div>
          </td>
        </tr>
        <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Add Admin</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>/admin/admins/store">
      <div class="modal-body">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <div class="row g-3">
          <div class="col-sm-6">
            <label class="form-label fw-600">Name *</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="col-sm-6">
            <label class="form-label fw-600">Email *</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="col-sm-6">
            <label class="form-label fw-600">Role</label>
            <select name="role" id="addRole" class="form-select" onchange="toggleCityField('add')">
              <option value="city_admin">City Admin</option>
              <option value="super_admin">Super Admin</option>
            </select>
          </div>
          <div class="col-sm-6" id="addCityField">
            <label class="form-label fw-600">Assign City *</label>
            <select name="assigned_city_id" class="form-select">
              <option value="">— Select City —</option>
              <?php foreach($cities as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label fw-600">Password</label>
            <input type="text" name="password" class="form-control" placeholder="Default: admin@123">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-p">Create Admin</button>
      </div>
    </form>
  </div></div>
</div>

<!-- Edit Admin Modal -->
<div class="modal fade" id="editAdminModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Admin</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>/admin/admins/update">
      <div class="modal-body">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <input type="hidden" name="id" id="editId">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-600">Name</label>
            <input type="text" name="name" id="editName" class="form-control" required>
          </div>
          <div class="col-sm-6">
            <label class="form-label fw-600">Role</label>
            <select name="role" id="editRole" class="form-select" onchange="toggleCityField('edit')">
              <option value="city_admin">City Admin</option>
              <option value="super_admin">Super Admin</option>
            </select>
          </div>
          <div class="col-sm-6" id="editCityField">
            <label class="form-label fw-600">Assign City</label>
            <select name="assigned_city_id" id="editCity" class="form-select">
              <option value="">— Select City —</option>
              <?php foreach($cities as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="col-sm-6">
            <label class="form-label fw-600">Status</label>
            <select name="status" id="editStatus" class="form-select">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-p">Save</button>
      </div>
    </form>
  </div></div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPwModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title"><i class="bi bi-key me-2"></i>Reset Password</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>/admin/admins/reset-password">
      <div class="modal-body">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <input type="hidden" name="id" id="rpId">
        <p class="small text-muted mb-3">Resetting password for: <strong id="rpName"></strong></p>
        <label class="form-label fw-600">New Password</label>
        <input type="text" name="password" class="form-control" placeholder="Default: admin@123">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-warning">Reset Password</button>
      </div>
    </form>
  </div></div>
</div>

<?php $extraJs = '<script>
function toggleCityField(prefix) {
  var role = document.getElementById(prefix+"Role").value;
  document.getElementById(prefix+"CityField").style.display = role==="super_admin" ? "none" : "";
}
document.getElementById("editAdminModal").addEventListener("show.bs.modal", function(e) {
  var b = e.relatedTarget;
  document.getElementById("editId").value     = b.dataset.id;
  document.getElementById("editName").value   = b.dataset.name;
  document.getElementById("editRole").value   = b.dataset.role;
  document.getElementById("editCity").value   = b.dataset.city;
  document.getElementById("editStatus").value = b.dataset.status;
  toggleCityField("edit");
});
document.getElementById("resetPwModal").addEventListener("show.bs.modal", function(e) {
  var b = e.relatedTarget;
  document.getElementById("rpId").value       = b.dataset.id;
  document.getElementById("rpName").textContent = b.dataset.name;
});
</script>' ?>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
