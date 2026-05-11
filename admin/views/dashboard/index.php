<?php $pageTitle = 'Dashboard'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<div class="row g-3 mb-4">
<?php
$cards = [
  ['Total Users',       $stats['total_users'],       'people',          '#7c3aed', '#ede9fe'],
  ['Active Users',      $stats['active_users'],       'person-check',    '#10b981', '#d1fae5'],
  ['Total Listings',    $stats['total_listings'],     'building',        '#f59e0b', '#fef3c7'],
  ['Approved',          $stats['approved_listings'],  'check2-circle',   '#0ea5e9', '#e0f2fe'],
  ['Pending Listings',  $stats['pending_listings'],   'hourglass-split', '#ef4444', '#fee2e2'],
  ['Cities',            $stats['total_cities'],       'geo-alt',         '#8b5cf6', '#ede9fe'],
  ['Revenue',           '&#8377;'.number_format($stats['total_revenue']), 'currency-rupee', '#10b981', '#d1fae5'],
  ['Pending Payments',  $stats['pending_payments'],   'credit-card',     '#f59e0b', '#fef3c7'],
  ['Pending Reviews',   $stats['pending_reviews'],    'star-half',       '#8b5cf6', '#ede9fe'],
];
foreach ($cards as $c): ?>
<div class="col-sm-6 col-xl-3">
  <div class="stat-card">
    <div class="ib" style="background:<?= $c[4] ?>;color:<?= $c[3] ?>"><i class="bi bi-<?= $c[2] ?>"></i></div>
    <div>
      <div class="val" style="color:<?= $c[3] ?>"><?= $c[1] ?></div>
      <div class="lbl"><?= $c[0] ?></div>
    </div>
  </div>
</div>
<?php endforeach ?>
</div>

<div class="row g-3 mb-4">
  <div class="col-xl-5">
    <div class="card h-100">
      <div class="ch"><i class="bi bi-pie-chart me-2"></i>Users by Plan</div>
      <div class="card-body">
        <?php foreach ($stats['plan_stats'] as $ps):
          $pct = $stats['total_users'] > 0 ? round($ps['cnt'] / $stats['total_users'] * 100) : 0;
          $clr = ['free'=>'#6b7280','basic'=>'#0ea5e9','premium'=>'#f59e0b','pro'=>'#10b981'][$ps['name']] ?? '#7c3aed';
        ?>
        <div class="d-flex align-items-center mb-3">
          <span style="width:80px;font-weight:600;font-size:.83rem"><?= $ps['label'] ?></span>
          <div class="flex-grow-1 mx-2" style="background:#f3f0ff;border-radius:8px;height:12px;overflow:hidden">
            <div style="width:<?= $pct ?>%;background:<?= $clr ?>;height:100%;border-radius:8px;transition:.4s"></div>
          </div>
          <span style="width:36px;text-align:right;font-weight:700;color:<?= $clr ?>"><?= $ps['cnt'] ?></span>
        </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>

  <div class="col-xl-7">
    <div class="card h-100">
      <div class="ch d-flex justify-content-between align-items-center">
        <span><i class="bi bi-person-plus me-2"></i>Recent Registrations</span>
        <a href="<?= BASE_URL ?>/admin/users" class="btn-ghost btn btn-sm">View All</a>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle small">
          <thead><tr><th>Name</th><th>Phone</th><th>Plan</th><th>Status</th><th>When</th></tr></thead>
          <tbody>
          <?php foreach ($stats['recent_users'] as $u): ?>
          <tr>
            <td class="fw-600"><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['phone']) ?></td>
            <td><?= Helper::planBadge($u['plan'] ?? 'free') ?></td>
            <td><?= Helper::statusBadge($u['status']) ?></td>
            <td class="text-muted"><?= Helper::timeAgo($u['created_at']) ?></td>
          </tr>
          <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="ch d-flex justify-content-between align-items-center">
    <span><i class="bi bi-building me-2"></i>Recent Listings</span>
    <a href="<?= BASE_URL ?>/admin/listings/pending" class="btn-ghost btn btn-sm">Pending Queue</a>
  </div>
  <div class="card-body p-0">
    <table class="table table-hover mb-0 align-middle small">
      <thead><tr><th>Business</th><th>Owner</th><th>Status</th><th>When</th></tr></thead>
      <tbody>
      <?php foreach ($stats['recent_listings'] as $l): ?>
      <tr>
        <td class="fw-600"><?= htmlspecialchars($l['business_name'] ?? '—') ?></td>
        <td><?= htmlspecialchars($l['owner']) ?></td>
        <td><?= Helper::statusBadge($l['status']) ?></td>
        <td class="text-muted"><?= Helper::timeAgo($l['created_at']) ?></td>
      </tr>
      <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Activity Log -->
<div class="card mt-3">
  <div class="ch"><i class="bi bi-activity me-2"></i>Recent Activity</div>
  <div class="card-body p-0">
    <?php if(empty($activityLog)): ?>
      <div class="p-3 text-center text-muted small">No activity yet.</div>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-sm align-middle mb-0">
        <thead><tr><th>Admin</th><th>Action</th><th>Description</th><th>Time</th></tr></thead>
        <tbody>
        <?php foreach($activityLog as $log): ?>
        <tr>
          <td class="small fw-600"><?= htmlspecialchars($log['admin_name'] ?? 'System') ?></td>
          <td><span class="badge bg-light text-dark" style="font-size:.7rem"><?= htmlspecialchars($log['action']) ?></span></td>
          <td class="small text-muted"><?= htmlspecialchars($log['description'] ?? '—') ?></td>
          <td class="small text-muted"><?= Helper::timeAgo($log['created_at']) ?></td>
        </tr>
        <?php endforeach ?>
        </tbody>
      </table>
    </div>
    <?php endif ?>
  </div>
</div>

<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
