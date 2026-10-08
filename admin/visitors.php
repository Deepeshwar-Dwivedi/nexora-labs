<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_admin();

$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 50;
$offset = ($page - 1) * $per_page;

$total = $pdo->query("SELECT COUNT(*) FROM visitor_logs")->fetchColumn();
$rows = $pdo->query("SELECT * FROM visitor_logs ORDER BY created_at DESC LIMIT $per_page OFFSET $offset")->fetchAll();
$pages = ceil($total / $per_page);

$stats = [
    'total'        => $total,
    'unique_ips'   => $pdo->query("SELECT COUNT(DISTINCT ip) FROM visitor_logs")->fetchColumn(),
    'today'        => $pdo->query("SELECT COUNT(*) FROM visitor_logs WHERE visit_date=CURDATE()")->fetchColumn(),
    'today_unique' => $pdo->query("SELECT COUNT(DISTINCT ip) FROM visitor_logs WHERE visit_date=CURDATE()")->fetchColumn(),
    'week'         => $pdo->query("SELECT COUNT(*) FROM visitor_logs WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetchColumn(),
    'month'        => $pdo->query("SELECT COUNT(*) FROM visitor_logs WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)")->fetchColumn(),
];

$devices = $pdo->query("SELECT device, COUNT(*) c FROM visitor_logs GROUP BY device ORDER BY c DESC")->fetchAll();
$top_pages = $pdo->query("SELECT page, COUNT(*) hits FROM visitor_logs GROUP BY page ORDER BY hits DESC LIMIT 6")->fetchAll();

$pageTitle = 'Visitor Analytics';
include '../includes/header.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<section class="section">
  <div class="container">

    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:2rem">
      <div>
        <h1 style="font-size:1.8rem;font-weight:800;letter-spacing:-0.02em">Visitor Analytics</h1>
        <p style="color:var(--muted);font-size:0.92rem;margin-top:0.3rem"><?= $total ?> total visits tracked</p>
      </div>
      <div style="display:flex;gap:0.5rem">
        <a href="<?= BASE_URL ?>admin/export.php?type=visitors" class="btn btn-primary">
          <i class="bi bi-file-earmark-excel"></i> Export
        </a>
        <a href="<?= BASE_URL ?>admin/dashboard.php" class="btn btn-ghost">
          <i class="bi bi-arrow-left"></i> Back
        </a>
      </div>
    </div>

    <!-- Summary -->
    <div class="dash-grid" style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr));margin-bottom:2rem">
      <?php
      $sum = [
          ['bi-eye',          $stats['total'],        'Total Visits', 'var(--primary)'],
          ['bi-fingerprint',  $stats['unique_ips'],   'Unique IPs',   'var(--cyan)'],
          ['bi-calendar-day', $stats['today'],        'Today',        'var(--green)'],
          ['bi-person-check', $stats['today_unique'], 'Today Unique', 'var(--yellow)'],
          ['bi-calendar-week',$stats['week'],         'Last 7 Days',  'var(--pink)'],
          ['bi-calendar-month',$stats['month'],       'Last 30 Days', 'var(--primary)'],
      ];
      foreach ($sum as $s): ?>
        <div class="stat-card">
          <div class="sc-icon" style="background:color-mix(in srgb, <?= $s[3] ?> 15%, transparent);color:<?= $s[3] ?>;width:38px;height:38px;font-size:1rem">
            <i class="bi <?= $s[0] ?>"></i>
          </div>
          <strong style="font-size:1.5rem"><?= $s[1] ?></strong>
          <span class="stat-label"><?= $s[2] ?></span>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Chart + Pages -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem" class="visitors-row">
      <div class="card" style="padding:1.5rem">
        <h3 style="font-size:0.95rem;font-weight:800;margin-bottom:1rem">
          <i class="bi bi-pie-chart" style="color:var(--primary)"></i> Visitors by Device
        </h3>
        <div style="height:220px;position:relative">
          <canvas id="deviceChart"></canvas>
        </div>
      </div>

      <div class="card" style="padding:1.5rem">
        <h3 style="font-size:0.95rem;font-weight:800;margin-bottom:1rem">
          <i class="bi bi-file-earmark" style="color:var(--primary)"></i> Top Pages
        </h3>
        <?php if (empty($top_pages)): ?>
          <p style="color:var(--muted);font-size:0.85rem">No data yet.</p>
        <?php else:
          $max_h = max(array_column($top_pages, 'hits'));
          foreach ($top_pages as $p):
            $pct = $max_h > 0 ? ($p['hits'] / $max_h) * 100 : 0;
        ?>
          <div style="margin-bottom:0.9rem">
            <div style="display:flex;justify-content:space-between;margin-bottom:0.35rem;font-size:0.82rem">
              <span style="font-weight:600"><?= e($p['page']) ?></span>
              <strong style="color:var(--primary)"><?= (int)$p['hits'] ?></strong>
            </div>
            <div style="height:5px;background:var(--primary-soft);border-radius:999px;overflow:hidden">
              <div style="height:100%;width:<?= $pct ?>%;background:linear-gradient(90deg,var(--primary),var(--cyan));border-radius:999px"></div>
            </div>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>

    <!-- Table -->
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>IP</th>
            <th>Page</th>
            <th>Device</th>
            <th>Referrer</th>
            <th>Date</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): 
            $devIcon = ['Desktop'=>'bi-display','Mobile'=>'bi-phone','Tablet'=>'bi-tablet'][$r['device']] ?? 'bi-circle';
            $devColor = ['Desktop'=>'var(--primary)','Mobile'=>'var(--cyan)','Tablet'=>'var(--yellow)'][$r['device']] ?? 'var(--muted)';
          ?>
            <tr>
              <td style="font-family:var(--font-mono);font-weight:600;color:var(--primary);font-size:0.8rem"><?= e($r['ip']) ?></td>
              <td><?= e($r['page']) ?></td>
              <td><i class="bi <?= $devIcon ?>" style="color:<?= $devColor ?>"></i> <?= e($r['device']) ?></td>
              <td style="color:var(--muted);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= e($r['referrer']) ?>"><?= e($r['referrer']) ?></td>
              <td><?= e($r['visit_date']) ?></td>
              <td style="color:var(--muted)"><?= e($r['visit_time']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if ($pages > 1): ?>
      <div style="display:flex;justify-content:center;gap:0.5rem;margin-top:2rem;flex-wrap:wrap">
        <?php 
        $start = max(1, $page - 2);
        $end = min($pages, $page + 2);
        if ($page > 1): ?>
          <a href="?page=<?= $page - 1 ?>" class="chip">← Prev</a>
        <?php endif;
        for ($i = $start; $i <= $end; $i++): ?>
          <a href="?page=<?= $i ?>" class="chip" style="<?= $i === $page ? 'background:var(--primary);color:#fff;border-color:var(--primary)' : '' ?>"><?= $i ?></a>
        <?php endfor;
        if ($page < $pages): ?>
          <a href="?page=<?= $page + 1 ?>" class="chip">Next →</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<style>
@media (max-width: 900px) { .visitors-row { grid-template-columns: 1fr !important; } }
</style>

<script>
const deviceEl = document.getElementById('deviceChart');
if (deviceEl) {
  new Chart(deviceEl, {
    type: 'doughnut',
    data: {
      labels: <?= json_encode(array_column($devices, 'device')) ?>,
      datasets: [{
        data: <?= json_encode(array_column($devices, 'c')) ?>,
        backgroundColor: ['#635BFF','#00D4FF','#FFAB00','#FF0080','#00E599','#7C6BFF'],
        borderWidth: 0
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '65%',
      plugins: {
        legend: { position: 'bottom', labels: { color: '#8A8F98', font: { size: 11 }, padding: 12, usePointStyle: true } }
      }
    }
  });
}
</script>

<?php include '../includes/footer.php'; ?>