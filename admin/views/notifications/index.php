<?php require BASE_PATH . '/admin/views/layout/header.php'; ?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h2 class="mb-0 fw-bold" style="font-size:1.35rem;color:var(--purple-darker)">
      <i class="bi bi-bell-fill me-2" style="color:var(--purple)"></i>Push Notifications
    </h2>
    <p class="text-muted mb-0 mt-1" style="font-size:.82rem">Send push notifications to users via Firebase Cloud Messaging</p>
  </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-md-3">
    <div class="card border-0 shadow-sm">
      <div class="card-body d-flex align-items-center gap-3 py-3">
        <div style="width:44px;height:44px;border-radius:12px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0">
          <i class="bi bi-phone" style="color:var(--purple);font-size:1.2rem"></i>
        </div>
        <div>
          <div class="fw-bold" style="font-size:1.5rem;line-height:1"><?= number_format($tokenCount) ?></div>
          <div class="text-muted" style="font-size:.75rem">Registered Devices</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-3">
    <div class="card border-0 shadow-sm">
      <div class="card-body d-flex align-items-center gap-3 py-3">
        <div style="width:44px;height:44px;border-radius:12px;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0">
          <i class="bi bi-people" style="color:#065f46;font-size:1.2rem"></i>
        </div>
        <div>
          <div class="fw-bold" style="font-size:1.5rem;line-height:1"><?= number_format($userCount) ?></div>
          <div class="text-muted" style="font-size:.75rem">Logged-in Users</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Send Form -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white border-bottom py-3">
    <h6 class="mb-0 fw-bold"><i class="bi bi-send me-2" style="color:var(--purple)"></i>Send Notification</h6>
  </div>
  <div class="card-body">
    <form method="POST" action="<?= BASE_URL ?>/admin/notifications/send">
      <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.85rem">Send To</label>
        <div class="d-flex flex-wrap gap-3">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="target" id="t-all" value="all" checked onchange="toggleTarget(this.value)">
            <label class="form-check-label" for="t-all">All Devices (<?= $tokenCount ?>)</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="target" id="t-city" value="city" onchange="toggleTarget(this.value)">
            <label class="form-check-label" for="t-city">By City</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="target" id="t-user" value="user" onchange="toggleTarget(this.value)">
            <label class="form-check-label" for="t-user">By User ID</label>
          </div>
        </div>
      </div>

      <div class="mb-3 d-none" id="city-field">
        <label class="form-label fw-semibold" style="font-size:.85rem">City</label>
        <select name="city_slug" class="form-select" style="max-width:280px">
          <option value="">— Select city —</option>
          <?php foreach ($cities as $c): ?>
            <option value="<?= htmlspecialchars($c['slug']) ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach ?>
        </select>
      </div>

      <div class="mb-3 d-none" id="user-field">
        <label class="form-label fw-semibold" style="font-size:.85rem">User ID</label>
        <input type="number" name="user_id" class="form-control" style="max-width:200px" placeholder="e.g. 42" min="1">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.85rem">Title <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" placeholder="e.g. New listing approved!" maxlength="100" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.85rem">Message <span class="text-danger">*</span></label>
        <textarea name="body" class="form-control" rows="3" placeholder="e.g. Your listing is now live on BizGuide." maxlength="300" required></textarea>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold" style="font-size:.85rem">Click URL <span class="text-muted fw-normal">(optional)</span></label>
        <input type="url" name="click_action" class="form-control" placeholder="https://bizguide.in/cities/kodaikanal">
        <div class="form-text">User is taken to this URL when they tap the notification.</div>
      </div>

      <button type="submit" class="btn btn-primary px-4" style="background:var(--purple);border-color:var(--purple)"
        onclick="return confirm('Send this notification now?')">
        <i class="bi bi-send me-2"></i>Send Notification
      </button>
    </form>
  </div>
</div>

<!-- Token Table -->
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white border-bottom py-3">
    <h6 class="mb-0 fw-bold"><i class="bi bi-list-ul me-2" style="color:var(--purple)"></i>Recent Registered Tokens</h6>
  </div>
  <div class="table-responsive">
    <table class="table table-sm table-hover mb-0" style="font-size:.82rem">
      <thead style="background:#f8f7ff">
        <tr>
          <th class="px-3 py-2">ID</th>
          <th class="py-2">User</th>
          <th class="py-2">City</th>
          <th class="py-2">Token (partial)</th>
          <th class="py-2">Last Seen</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($tokens)): foreach ($tokens as $t): ?>
        <tr>
          <td class="px-3"><?= (int)$t['id'] ?></td>
          <td><?= $t['user_id'] ? htmlspecialchars($t['user_name'] ?? '#'.$t['user_id']) : '<span class="text-muted">Guest</span>' ?></td>
          <td><?= $t['city_slug'] ? htmlspecialchars($t['city_slug']) : '—' ?></td>
          <td><code><?= htmlspecialchars(substr($t['token'], 0, 32)) ?>…</code></td>
          <td>
            <?= Helper::timeAgo($t['updated_at'] ?? $t['created_at']) ?>
            <div class="text-muted" style="font-size:.68rem">Added <?= Helper::timeAgo($t['created_at']) ?></div>
          </td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="5" class="text-center text-muted py-4">No tokens registered yet. Open a city page and allow notifications.</td></tr>
        <?php endif ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function toggleTarget(val) {
  document.getElementById('city-field').classList.toggle('d-none', val !== 'city');
  document.getElementById('user-field').classList.toggle('d-none', val !== 'user');
}
</script>

<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
