<?php $pageTitle = 'Create User'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<div class="d-flex align-items-center gap-2 mb-3">
  <a href="<?= BASE_URL ?>/admin/users" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back to Users
  </a>
</div>

<div class="row g-3 justify-content-center">
<div class="col-lg-8 col-12">
<div class="card">
  <div class="ch"><i class="bi bi-person-plus me-2"></i>Create New User</div>
  <div class="card-body">
    <form method="POST" action="<?= BASE_URL ?>/admin/users/store">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

      <div class="row g-3 mb-3">
        <div class="col-sm-6">
          <label class="form-label fw-600">Full Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" required placeholder="e.g. Ramesh Kumar">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Profession / Business Type</label>
          <input type="text" name="profession" class="form-control" placeholder="e.g. Electrician, Hotel">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Phone <span class="text-danger">*</span></label>
          <input type="text" name="phone" class="form-control" required placeholder="+91 98765 43210">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Email</label>
          <input type="email" name="email" class="form-control" placeholder="optional">
        </div>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-sm-6">
          <label class="form-label fw-600">City</label>
          <select name="city_id" class="form-select">
            <option value="">— Not assigned —</option>
            <?php foreach ($cities as $c): ?>
              <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Status</label>
          <select name="status" class="form-select">
            <option value="active">Active</option>
            <option value="pending">Pending</option>
            <option value="suspended">Suspended</option>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-600">Password</label>
        <input type="text" name="password" class="form-control"
               placeholder="Leave blank to use default: bizguide@123">
        <div class="form-text text-muted">Share this password with the user so they can log in.</div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-p">
          <i class="bi bi-person-plus me-1"></i>Create User
        </button>
        <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
</div>

</div>
</div>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
