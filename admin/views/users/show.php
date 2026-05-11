<?php $pageTitle = 'User Profile'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <a href="<?= BASE_URL ?>/admin/<?= ($user['plan_name']==='free') ? 'users/free' : 'users' ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back
  </a>
  <div class="d-flex gap-2">
    <a href="<?= BASE_URL ?>/admin/users/<?= $user['id'] ?>/edit" class="btn btn-sm btn-op">
      <i class="bi bi-pencil me-1"></i>Edit
    </a>
    <form method="POST" action="<?= BASE_URL ?>/admin/users/toggle">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
      <input type="hidden" name="id" value="<?= $user['id'] ?>">
      <input type="hidden" name="back" value="<?= BASE_URL ?>/admin/users/<?= $user['id'] ?>">
      <button class="btn btn-sm <?= $user['status']==='active'?'btn-outline-warning':'btn-outline-success' ?>">
        <i class="bi bi-<?= $user['status']==='active'?'pause-circle':'play-circle' ?> me-1"></i>
        <?= $user['status']==='active' ? 'Suspend' : 'Activate' ?>
      </button>
    </form>
  </div>
</div>

<div class="row g-3">
  <!-- Left col -->
  <div class="col-lg-4">

    <!-- Profile card -->
    <div class="card mb-3">
      <div class="ch"><i class="bi bi-person me-2"></i>Profile</div>
      <div class="card-body text-center">
        <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#2d1b69);
             display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;
             font-weight:800;margin:0 auto 12px">
          <?= strtoupper(substr($user['name'],0,1)) ?>
        </div>
        <h6 class="fw-700 mb-1"><?= htmlspecialchars($user['name']) ?></h6>
        <p class="text-muted small mb-2"><?= htmlspecialchars($user['profession'] ?? '') ?></p>
        <?= Helper::statusBadge($user['status']) ?>
        <hr>
        <dl class="row text-start small mb-0">
          <dt class="col-5 text-muted">Phone</dt><dd class="col-7"><?= htmlspecialchars($user['phone']) ?></dd>
          <dt class="col-5 text-muted">Email</dt><dd class="col-7"><?= htmlspecialchars($user['email'] ?? '—') ?></dd>
          <dt class="col-5 text-muted">Plan</dt><dd class="col-7"><?= Helper::planBadge($user['plan_name'] ?? 'free') ?></dd>
          <dt class="col-5 text-muted">City</dt><dd class="col-7"><?= htmlspecialchars($user['city_name'] ?? '—') ?></dd>
          <dt class="col-5 text-muted">Expires</dt><dd class="col-7"><?= $user['plan_expires_at'] ? Helper::formatDate($user['plan_expires_at']) : '—' ?></dd>
          <dt class="col-5 text-muted">Joined</dt><dd class="col-7"><?= Helper::formatDate($user['created_at']) ?></dd>
        </dl>
      </div>
    </div>

    <!-- Plan change — always visible -->
    <div class="card mb-3">
      <div class="ch"><i class="bi bi-arrow-up-circle me-2"></i>Change Plan</div>
      <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/admin/users/upgrade-plan">
          <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
          <input type="hidden" name="id" value="<?= $user['id'] ?>">
          <select name="plan_id" class="form-select mb-2">
            <?php foreach($plans as $p): ?>
              <option value="<?= $p['id'] ?>" <?= $user['plan_id']==$p['id']?'selected':'' ?>>
                <?= htmlspecialchars($p['label']) ?> — ₹<?= number_format($p['price']) ?>
              </option>
            <?php endforeach ?>
          </select>
          <button type="submit" class="btn btn-p w-100">
            <i class="bi bi-arrow-up-circle me-1"></i>Update Plan
          </button>
        </form>
      </div>
    </div>

    <!-- Payments -->
    <div class="card">
      <div class="ch"><i class="bi bi-credit-card me-2"></i>Payments</div>
      <div class="card-body p-0">
        <?php if(empty($payments)): ?>
          <div class="p-3 text-center text-muted small">No payment records.</div>
        <?php else: ?>
          <table class="table mb-0 small">
            <thead><tr><th>Plan</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach($payments as $pay): ?>
            <tr>
              <td><?= htmlspecialchars($pay['label'] ?? '—') ?></td>
              <td class="fw-600">₹<?= number_format($pay['amount'],2) ?></td>
              <td><?= Helper::statusBadge($pay['status']) ?></td>
              <td><?= Helper::formatDate($pay['created_at']) ?></td>
            </tr>
            <?php endforeach ?>
            </tbody>
          </table>
        <?php endif ?>
      </div>
    </div>

  </div>

  <!-- Right col: listing -->
  <div class="col-lg-8">
    <?php if($listing): ?>
      <div class="card">
        <div class="ch d-flex justify-content-between align-items-center">
          <span><i class="bi bi-building me-2"></i>Business Listing</span>
          <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/listings/<?= $listing['id'] ?>/edit" class="btn-ghost btn btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
            <a href="<?= BASE_URL ?>/admin/listings/<?= $listing['id'] ?>" class="btn-ghost btn btn-sm"><i class="bi bi-eye me-1"></i>View</a>
          </div>
        </div>
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-3">
            <div>
              <div class="fw-700 fs-6"><?= htmlspecialchars($listing['business_name'] ?? 'Unnamed') ?></div>
              <div class="small text-muted"><?= htmlspecialchars($listing['cat_name'] ?? '') ?></div>
              <?php if(!empty($listing['first_image'])): ?>
                <img src="<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($listing['first_image']) ?>" class="mt-2 rounded" style="height:60px; width:auto;">
              <?php endif ?>
              <?php if(!empty($listing['top_banner'])): ?>
                <div class="mt-2 small text-muted">Pro Banner:</div>
                <img src="<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($listing['top_banner']) ?>" class="rounded" style="height:40px; width:auto;">
              <?php endif ?>
            </div>
            <div class="d-flex gap-2 flex-wrap">
              <?= Helper::planBadge($listing['plan_level']) ?>
              <?= Helper::statusBadge($listing['status']) ?>
            </div>
          </div>
          <dl class="row small mb-0">
            <dt class="col-4 text-muted">Phone</dt>
            <dd class="col-8"><?= htmlspecialchars($listing['phone'] ?? '—') ?></dd>
            <dt class="col-4 text-muted">WhatsApp</dt>
            <dd class="col-8"><?= htmlspecialchars($listing['whatsapp'] ?? '—') ?></dd>
            <dt class="col-4 text-muted">Email</dt>
            <dd class="col-8"><?= htmlspecialchars($listing['email'] ?? '—') ?></dd>
            <dt class="col-4 text-muted">Address</dt>
            <dd class="col-8"><?= htmlspecialchars($listing['address'] ?? '—') ?></dd>
            <?php if($listing['website']): ?>
            <dt class="col-4 text-muted">Website</dt>
            <dd class="col-8"><a href="<?= htmlspecialchars($listing['website']) ?>" target="_blank"><?= htmlspecialchars($listing['website']) ?></a></dd>
            <?php endif ?>
            <dt class="col-4 text-muted">Description</dt>
            <dd class="col-8"><?= htmlspecialchars($listing['short_description'] ?? '—') ?></dd>
            <dt class="col-4 text-muted">Views</dt>
            <dd class="col-8"><?= number_format($listing['views'] ?? 0) ?></dd>
            <dt class="col-4 text-muted">Published</dt>
            <dd class="col-8"><?= $listing['published_at'] ? Helper::formatDate($listing['published_at']) : '—' ?></dd>
          </dl>
          <?php if($listing['status']==='pending'): ?>
            <div class="alert alert-warning py-2 mt-3 mb-0 small">
              <i class="bi bi-hourglass-split me-1"></i>Pending approval.
              <a href="<?= BASE_URL ?>/admin/listings/<?= $listing['id'] ?>">Review now</a>
            </div>
          <?php elseif($listing['status']==='rejected' && $listing['rejection_note']): ?>
            <div class="alert alert-danger py-2 mt-3 mb-0 small">
              <strong>Rejected:</strong> <?= htmlspecialchars($listing['rejection_note']) ?>
            </div>
          <?php endif ?>
        </div>
      </div>

    <?php else: ?>
      <div class="card" style="border:2px dashed #a78bfa">
        <div class="card-body text-center py-5">
          <div style="font-size:3rem;margin-bottom:12px">📋</div>
          <h6 class="fw-700 mb-1" style="color:#2d1b69">No Ad Posted Yet</h6>
          <p class="text-muted small mb-4">Post a listing for this user. Select the plan on the next page.</p>
          <?php if($user['plan_name'] !== 'free'): ?>
            <a href="<?= BASE_URL ?>/admin/listings/create?user_id=<?= $user['id'] ?>" class="btn btn-p btn-lg">
              <i class="bi bi-plus-circle me-2"></i>Post Ad
            </a>
          <?php else: ?>
            <div class="alert alert-info py-2 small mb-3">
              <i class="bi bi-info-circle me-1"></i>Set a plan for this user first, then post their ad.
            </div>
          <?php endif ?>
        </div>
      </div>
    <?php endif ?>
  </div>

</div>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
