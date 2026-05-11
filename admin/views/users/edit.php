<?php $pageTitle = 'Edit User'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<div class="d-flex align-items-center gap-2 mb-3">
  <a href="<?= BASE_URL ?>/admin/users/<?= $user['id'] ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back
  </a>
</div>
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
  <div class="ch"><i class="bi bi-pencil me-2"></i>Edit — <?= htmlspecialchars($user['name']) ?></div>
  <div class="card-body">
    <form method="POST" action="<?= BASE_URL ?>/admin/users/update">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
      <input type="hidden" name="id" value="<?= $user['id'] ?>">
      <div class="row g-3">
        <div class="col-sm-6">
          <label class="form-label fw-600">Full Name *</label>
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Phone *</label>
          <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Profession</label>
          <input type="text" name="profession" class="form-control" value="<?= htmlspecialchars($user['profession'] ?? '') ?>">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">City</label>
          <select name="city_id" class="form-select">
            <option value="">— Not assigned —</option>
            <?php foreach($cities as $c): ?>
              <option value="<?= $c['id'] ?>" <?= $user['city_id']==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-p"><i class="bi bi-save me-1"></i>Save Changes</button>
        <a href="<?= BASE_URL ?>/admin/users/<?= $user['id'] ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
</div>
</div>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
