<?php $pageTitle = 'Keyword Suggestions'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<form method="GET" class="filter-bar">
  <div class="flex-grow-1">
    <label class="form-label mb-1 small fw-600">Search</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($filters['search']) ?>">
    </div>
  </div>
  <div style="min-width:120px">
    <label class="form-label mb-1 small fw-600">Status</label>
    <select name="status" class="form-select"><option value="">All</option>
      <?php foreach (['pending','approved','rejected','converted'] as $s): ?>
        <option value="<?= $s ?>" <?= $filters['status'] == $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div class="d-flex gap-2 align-self-end">
    <button type="submit" class="btn btn-p"><i class="bi bi-funnel"></i></button>
    <a href="<?= BASE_URL ?>/admin/keywords/suggestions" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
  </div>
</form>

<div class="card">
  <div class="ch d-flex justify-content-between align-items-center">
    <span><i class="bi bi-lightbulb me-2"></i>Keyword Suggestions <span class="badge bg-white text-dark ms-1"><?= $pager['total'] ?></span></span>
    <a href="<?= BASE_URL ?>/admin/keywords" class="btn-ghost btn btn-sm"><i class="bi bi-tags me-1"></i>All Keywords</a>
  </div>
  <div class="card-body p-0">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>Keyword</th><th>Suggested By</th><th>Category</th><th>Note</th><th>Status</th><th>When</th><th>Action</th></tr></thead>
      <tbody>
      <?php if (empty($pager['data'])): ?>
        <tr><td colspan="7" class="text-center py-5 text-muted">
          <i class="bi bi-lightbulb fs-2 d-block mb-2"></i>No suggestions yet.
        </td></tr>
      <?php else: foreach ($pager['data'] as $s): ?>
      <tr>
        <td class="fw-600"><?= htmlspecialchars($s['keyword']) ?></td>
        <td><?= htmlspecialchars($s['user_name']) ?></td>
        <td><?= htmlspecialchars($s['cat_name'] ?? '—') ?></td>
        <td class="small text-muted"><?= htmlspecialchars(Helper::truncate($s['note'] ?? '', 40)) ?></td>
        <td><?= Helper::statusBadge($s['status']) ?></td>
        <td class="small"><?= Helper::timeAgo($s['created_at']) ?></td>
        <td>
          <?php if ($s['status'] === 'pending'): ?>
          <div class="d-flex gap-1">
            <form method="POST" action="<?= BASE_URL ?>/admin/keywords/suggestions/approve">
              <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
              <input type="hidden" name="id" value="<?= $s['id'] ?>">
              <input type="hidden" name="keyword" value="<?= htmlspecialchars($s['keyword'], ENT_QUOTES) ?>">
              <button class="btn btn-sm btn-success" title="Approve & Add"><i class="bi bi-check2"></i></button>
            </form>
            <button class="btn btn-sm btn-outline-danger" title="Reject"
              data-bs-toggle="modal" data-bs-target="#rejectSugModal" data-id="<?= $s['id'] ?>">
              <i class="bi bi-x"></i>
            </button>
          </div>
          <?php else: ?>
            <span class="text-muted small"><?= ucfirst($s['status']) ?></span>
          <?php endif ?>
        </td>
      </tr>
      <?php endforeach; endif ?>
      </tbody>
    </table>
  </div>
  <?php if ($pager['last_page'] > 1): ?>
  <div class="card-footer" style="background:#f8f7ff">
    <?= Helper::paginationLinks($pager, BASE_URL . '/admin/keywords/suggestions?' . http_build_query(array_filter($filters))) ?>
  </div>
  <?php endif ?>
</div>

<div class="modal fade" id="rejectSugModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Reject Suggestion</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="<?= BASE_URL ?>/admin/keywords/suggestions/reject">
      <div class="modal-body">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <input type="hidden" name="id" id="rsId">
        <label class="form-label fw-600">Admin Note <small class="text-muted">(shown to user)</small></label>
        <textarea name="admin_note" class="form-control" rows="3" placeholder="Reason for rejection…"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Reject</button>
      </div>
    </form>
  </div></div>
</div>
<?php $extraJs = '<script>
document.getElementById("rejectSugModal").addEventListener("show.bs.modal", function(e) {
  document.getElementById("rsId").value = e.relatedTarget.dataset.id;
});
</script>' ?>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
