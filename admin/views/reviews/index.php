<?php $pageTitle = 'Reviews'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<form method="GET" class="filter-bar">
  <div class="flex-grow-1">
    <label class="form-label mb-1 small fw-600">Search</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input type="text" name="search" class="form-control" placeholder="Reviewer, business..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
    </div>
  </div>
  <div style="min-width:110px">
    <label class="form-label mb-1 small fw-600">Status</label>
    <select name="status" class="form-select">
      <option value="">All</option>
      <?php foreach (['pending','approved','rejected'] as $s): ?>
        <option value="<?= $s ?>" <?= ($filters['status']??'')==$s?'selected':'' ?>><?= ucfirst($s) ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div style="min-width:110px">
    <label class="form-label mb-1 small fw-600">Rating</label>
    <select name="rating" class="form-select">
      <option value="">All</option>
      <?php for($i=5;$i>=1;$i--): ?>
        <option value="<?= $i ?>" <?= ($filters['rating']??'')==$i?'selected':'' ?>><?= $i ?> ★</option>
      <?php endfor ?>
    </select>
  </div>
  <div class="d-flex gap-2 align-self-end">
    <button type="submit" class="btn btn-p"><i class="bi bi-search me-1"></i>Search</button>
    <a href="<?= BASE_URL ?>/admin/reviews" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
  </div>
</form>
<div class="card">
  <div class="ch"><i class="bi bi-star me-2"></i>Reviews <span class="badge bg-white text-dark ms-2"><?= $pager['total'] ?></span></div>
  <div class="card-body p-0">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>Reviewer</th><th>Listing</th><th>Rating</th><th>Comment</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
      <tbody>
      <?php if (empty($pager['data'])): ?>
        <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-star fs-2 d-block mb-2"></i>No reviews found</td></tr>
      <?php else: foreach ($pager['data'] as $r): ?>
      <tr>
        <td>
          <div class="fw-600"><?= htmlspecialchars($r['reviewer_name'] ?? '—') ?></div>
          <small class="text-muted"><?= htmlspecialchars($r['reviewer_phone'] ?? '') ?></small>
        </td>
        <td>
          <div class="fw-600 small"><?= htmlspecialchars($r['business_name'] ?? '—') ?></div>
        </td>
        <td><span style="color:#f59e0b;font-size:1rem"><?= str_repeat('★',(int)$r['rating']) ?></span></td>
        <td class="small text-muted" style="max-width:200px"><?= htmlspecialchars(Helper::truncate($r['comment'] ?? '', 60)) ?></td>
        <td><?= Helper::statusBadge($r['status']) ?></td>
        <td class="small"><?= Helper::formatDate($r['created_at']) ?></td>
        <td>
          <?php if ($r['status'] === 'pending'): ?>
          <div class="d-flex gap-1">
            <form method="POST" action="<?= BASE_URL ?>/admin/reviews/approve">
              <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
              <input type="hidden" name="id" value="<?= $r['id'] ?>">
              <button class="btn btn-sm btn-success" title="Approve"><i class="bi bi-check2-circle"></i></button>
            </form>
            <form method="POST" action="<?= BASE_URL ?>/admin/reviews/reject">
              <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
              <input type="hidden" name="id" value="<?= $r['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" title="Reject"><i class="bi bi-x-circle"></i></button>
            </form>
          </div>
          <?php else: ?>
            <span class="text-muted small"><?= ucfirst($r['status']) ?></span>
          <?php endif ?>
        </td>
      </tr>
      <?php endforeach; endif ?>
      </tbody>
    </table>
  </div>
  <?php if ($pager['last_page'] > 1): ?>
  <div class="card-footer" style="background:#f8f7ff">
    <?= Helper::paginationLinks($pager, BASE_URL . '/admin/reviews?' . http_build_query(array_filter($filters))) ?>
  </div>
  <?php endif ?>
</div>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
