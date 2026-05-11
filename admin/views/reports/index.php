<?php $pageTitle = 'Reports'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<div class="row g-3">
  <div class="col-lg-6">
    <div class="card">
      <div class="ch"><i class="bi bi-bar-chart me-2"></i>Plan-wise Users & Revenue</div>
      <div class="card-body p-0">
        <table class="table mb-0 align-middle">
          <thead><tr><th>Plan</th><th>Users</th><th>Revenue</th></tr></thead>
          <tbody>
          <?php foreach ($planStats as $ps): ?>
          <tr>
            <td><?= Helper::planBadge($ps['name']) ?></td>
            <td><strong><?= $ps['cnt'] ?></strong></td>
            <td class="text-success fw-600">&#8377;<?= number_format($ps['revenue'], 2) ?></td>
          </tr>
          <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card">
      <div class="ch"><i class="bi bi-geo-alt me-2"></i>City-wise Statistics</div>
      <div class="card-body p-0">
        <?php if (empty($cities)): ?>
          <div class="p-4 text-center text-muted small">No cities configured.</div>
        <?php else: ?>
          <table class="table mb-0 align-middle">
            <thead><tr><th>City</th><th>Users</th><th>Listings</th></tr></thead>
            <tbody>
            <?php foreach ($cities as $c): ?>
            <tr>
              <td class="fw-600"><?= htmlspecialchars($c['name']) ?></td>
              <td><?= $c['users'] ?></td>
              <td><?= $c['listings'] ?></td>
            </tr>
            <?php endforeach ?>
            </tbody>
          </table>
        <?php endif ?>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="ch"><i class="bi bi-graph-up me-2"></i>Registrations — Last 30 Days</div>
      <div class="card-body">
        <?php if (empty($chart)): ?>
          <p class="text-muted text-center mb-0 small">No registrations in the last 30 days.</p>
        <?php else: ?>
          <canvas id="regChart" height="65"></canvas>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>

<?php $chartJson = json_encode($chart); ?>
<?php $extraJs = '<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
var d = ' . $chartJson . ';
if (d.length) {
  new Chart(document.getElementById("regChart").getContext("2d"), {
    type: "bar",
    data: {
      labels: d.map(function(x){ return x.day; }),
      datasets: [{
        label: "Registrations",
        data: d.map(function(x){ return x.cnt; }),
        backgroundColor: "rgba(124,58,237,.6)",
        borderColor: "#7c3aed",
        borderWidth: 1,
        borderRadius: 5
      }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
  });
}
</script>' ?>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
