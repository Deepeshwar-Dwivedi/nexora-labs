<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_admin();

$user = current_user();

$stats = [
    'clients'        => $pdo->query("SELECT COUNT(*) FROM users WHERE role='client'")->fetchColumn(),
    'quotes'         => $pdo->query("SELECT COUNT(*) FROM quotes")->fetchColumn(),
    'new_quotes'     => $pdo->query("SELECT COUNT(*) FROM quotes WHERE status='new'")->fetchColumn(),
    'consultations'  => $pdo->query("SELECT COUNT(*) FROM consultations")->fetchColumn(),
    'contacts'       => $pdo->query("SELECT COUNT(*) FROM contact_enquiries")->fetchColumn(),
    'visitors_today' => $pdo->query("SELECT COUNT(DISTINCT ip) FROM visitor_logs WHERE visit_date=CURDATE()")->fetchColumn(),
    'visitors_week'  => $pdo->query("SELECT COUNT(DISTINCT ip) FROM visitor_logs WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetchColumn(),
    'visitors_total' => $pdo->query("SELECT COUNT(DISTINCT ip) FROM visitor_logs")->fetchColumn(),
    'visitors_live'  => $pdo->query("SELECT COUNT(DISTINCT ip) FROM visitor_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)")->fetchColumn(),
];

$visitor_chart = $pdo->query("
    SELECT visit_date, COUNT(DISTINCT ip) as count
    FROM visitor_logs
    WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY visit_date
")->fetchAll();

$chart_labels = [];
$chart_data = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $chart_labels[] = date('d M', strtotime($d));
    $found = false;
    foreach ($visitor_chart as $row) {
        if ($row['visit_date'] === $d) {
            $chart_data[] = (int)$row['count'];
            $found = true;
            break;
        }
    }
    if (!$found) $chart_data[] = 0;
}

$quote_status = $pdo->query("SELECT status, COUNT(*) as c FROM quotes GROUP BY status")->fetchAll();

$recent_quotes   = $pdo->query("SELECT * FROM quotes ORDER BY created_at DESC LIMIT 8")->fetchAll();
$recent_contacts = $pdo->query("SELECT * FROM contact_enquiries ORDER BY created_at DESC LIMIT 8")->fetchAll();
$recent_consults = $pdo->query("SELECT * FROM consultations ORDER BY created_at DESC LIMIT 8")->fetchAll();
$top_pages       = $pdo->query("SELECT page, COUNT(*) as hits FROM visitor_logs GROUP BY page ORDER BY hits DESC LIMIT 6")->fetchAll();

$pageTitle = 'Admin Dashboard';
include '../includes/header.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<section class="section">
  <div class="container">

    <!-- Header -->
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:2rem">
      <div>
        <h1 style="font-size:1.8rem;font-weight:800;letter-spacing:-0.02em">Admin Dashboard</h1>
        <p style="color:var(--muted);font-size:0.92rem;margin-top:0.4rem">
          Welcome back, <?= e($user['name']) ?> 👋
          <span style="display:inline-flex;align-items:center;gap:0.4rem;margin-left:1rem;padding:0.25rem 0.7rem;background:var(--green-soft);color:var(--green);border-radius:999px;font-size:0.72rem;font-weight:700">
            <span style="width:7px;height:7px;background:var(--green);border-radius:50%;box-shadow:0 0 8px var(--green);animation:pulse 2s infinite"></span>
            <?= $stats['visitors_live'] ?> live now
          </span>
        </p>
      </div>
      <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
        <a href="<?= BASE_URL ?>admin/quotes.php" class="btn btn-primary">
          <i class="bi bi-file-earmark-text"></i> Quotes
          <?php if ($stats['new_quotes'] > 0): ?>
            <span style="background:#fff;color:var(--primary);border-radius:999px;padding:0.05rem 0.45rem;font-size:0.68rem;font-weight:800"><?= $stats['new_quotes'] ?></span>
          <?php endif; ?>
        </a>
        <a href="<?= BASE_URL ?>admin/export.php?type=all" class="btn btn-ghost">
          <i class="bi bi-download"></i> Export
        </a>
        <a href="<?= BASE_URL ?>admin/logout.php" class="btn btn-ghost">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </div>
    </div>

    <!-- Stats -->
    <div class="dash-grid" style="grid-template-columns:repeat(auto-fit,minmax(170px,1fr))">
      <?php
      $cards = [
          ['bi-people',            $stats['clients'],        'Clients',         'var(--primary)'],
          ['bi-file-earmark-text', $stats['quotes'],         'Quotes',          'var(--cyan)', $stats['new_quotes'] > 0 ? $stats['new_quotes'].' new' : null],
          ['bi-calendar-check',    $stats['consultations'],  'Consultations',   'var(--yellow)'],
          ['bi-envelope',          $stats['contacts'],       'Contacts',        'var(--pink)'],
          ['bi-eye',               $stats['visitors_today'], 'Today Visitors',  'var(--green)'],
          ['bi-graph-up',          $stats['visitors_week'],  'Week Visitors',   'var(--primary)'],
          ['bi-globe',             $stats['visitors_total'], 'Total Visitors',  'var(--cyan)'],
          ['bi-activity',          $stats['visitors_live'],  'Live Now',        'var(--red)'],
      ];
      foreach ($cards as $c): ?>
        <div class="stat-card">
          <div class="sc-icon" style="background:color-mix(in srgb, <?= $c[3] ?> 15%, transparent);color:<?= $c[3] ?>">
            <i class="bi <?= $c[0] ?>"></i>
          </div>
          <strong><?= $c[1] ?></strong>
          <span class="stat-label"><?= $c[2] ?></span>
          <?php if (!empty($c[4])): ?>
            <span class="badge badge-red" style="align-self:flex-start;margin-top:0.3rem"><?= $c[4] ?></span>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Charts -->
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-top:2rem" class="dash-charts">
      <div class="card" style="padding:1.8rem">
        <h3 style="font-size:1rem;font-weight:800;margin-bottom:1.2rem">
          <i class="bi bi-graph-up-arrow" style="color:var(--primary)"></i> Visitors — Last 7 Days
        </h3>
        <div style="height:220px"><canvas id="visitorChart"></canvas></div>
      </div>
      <div class="card" style="padding:1.8rem">
        <h3 style="font-size:1rem;font-weight:800;margin-bottom:1.2rem">
          <i class="bi bi-pie-chart" style="color:var(--primary)"></i> Quote Status
        </h3>
        <div style="height:220px"><canvas id="statusChart"></canvas></div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="card tabs-container" style="padding:0;overflow:hidden;margin-top:2rem;border-radius:var(--radius-lg)">
      <div class="tabs">
        <button class="tab active" data-tab="quotes">
          <i class="bi bi-file-earmark-text"></i> Quotes
          <span class="tab-badge"><?= count($recent_quotes) ?></span>
        </button>
        <button class="tab" data-tab="consults">
          <i class="bi bi-calendar-check"></i> Consultations
          <span class="tab-badge"><?= count($recent_consults) ?></span>
        </button>
        <button class="tab" data-tab="contacts">
          <i class="bi bi-envelope"></i> Contacts
          <span class="tab-badge"><?= count($recent_contacts) ?></span>
        </button>
        <button class="tab" data-tab="pages">
          <i class="bi bi-bar-chart"></i> Top Pages
        </button>
      </div>

      <div class="tab-content active" data-content="quotes">
        <?php if (empty($recent_quotes)): ?>
          <div style="padding:3rem;text-align:center;color:var(--muted)">
            <i class="bi bi-inbox" style="font-size:2.5rem;opacity:0.4"></i>
            <p style="margin-top:0.8rem">No quotes yet.</p>
          </div>
        <?php else: ?>
          <div style="max-height:420px;overflow-y:auto">
            <?php foreach ($recent_quotes as $q): ?>
              <div style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap">
                <div style="flex:1;min-width:200px">
                  <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.3rem">
                    <strong style="font-size:0.95rem"><?= e($q['name']) ?></strong>
                    <span style="background:var(--primary-soft);color:var(--primary);font-size:0.68rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:999px;font-family:var(--font-mono)"><?= e($q['enquiry_id']) ?></span>
                  </div>
                  <div style="color:var(--muted);font-size:0.82rem">
                    <?= e($q['email']) ?> • <?= e($q['service'] ?: 'General') ?>
                  </div>
                </div>
                <div style="text-align:right">
                  <div style="font-weight:700;color:var(--primary);font-size:0.85rem"><?= e($q['budget'] ?: '—') ?></div>
                  <div style="color:var(--muted);font-size:0.72rem"><?= date('d M, H:i', strtotime($q['created_at'])) ?></div>
                </div>
                <a href="<?= BASE_URL ?>admin/quotes.php" class="btn btn-ghost btn-sm">
                  View <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
          <div style="padding:1rem;text-align:center;background:var(--surface-2)">
            <a href="<?= BASE_URL ?>admin/quotes.php" style="color:var(--primary);font-weight:700;font-size:0.85rem">
              View All Quotes <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        <?php endif; ?>
      </div>

      <div class="tab-content" data-content="consults">
        <?php if (empty($recent_consults)): ?>
          <div style="padding:3rem;text-align:center;color:var(--muted)">
            <i class="bi bi-calendar-x" style="font-size:2.5rem;opacity:0.4"></i>
            <p style="margin-top:0.8rem">No consultations yet.</p>
          </div>
        <?php else: ?>
          <div style="max-height:420px;overflow-y:auto">
            <?php foreach ($recent_consults as $c): ?>
              <div style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap">
                <div style="flex:1;min-width:200px">
                  <strong style="font-size:0.95rem"><?= e($c['name']) ?></strong>
                  <div style="color:var(--muted);font-size:0.82rem;margin-top:0.2rem">
                    <?= e($c['email']) ?> <?= $c['phone'] ? '• '.e($c['phone']) : '' ?>
                  </div>
                </div>
                <div style="text-align:right">
                  <div style="font-weight:700;color:var(--primary);font-size:0.85rem">
                    <i class="bi bi-calendar"></i> <?= $c['preferred_date'] ? date('d M', strtotime($c['preferred_date'])) : '—' ?>
                  </div>
                  <div style="color:var(--muted);font-size:0.72rem"><?= e($c['preferred_time'] ?: '') ?></div>
                </div>
                <a href="<?= BASE_URL ?>admin/consultations.php" class="btn btn-ghost btn-sm">
                  View <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
          <div style="padding:1rem;text-align:center;background:var(--surface-2)">
            <a href="<?= BASE_URL ?>admin/consultations.php" style="color:var(--primary);font-weight:700;font-size:0.85rem">
              View All <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        <?php endif; ?>
      </div>

      <div class="tab-content" data-content="contacts">
        <?php if (empty($recent_contacts)): ?>
          <div style="padding:3rem;text-align:center;color:var(--muted)">
            <i class="bi bi-inbox" style="font-size:2.5rem;opacity:0.4"></i>
            <p style="margin-top:0.8rem">No contacts yet.</p>
          </div>
        <?php else: ?>
          <div style="max-height:420px;overflow-y:auto">
            <?php foreach ($recent_contacts as $c): ?>
              <div style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap">
                <div style="flex:1;min-width:200px">
                  <strong style="font-size:0.95rem"><?= e($c['name']) ?></strong>
                  <div style="color:var(--muted);font-size:0.82rem;margin-top:0.2rem"><?= e($c['email']) ?></div>
                  <div style="font-size:0.85rem;color:var(--text-2);margin-top:0.3rem">
                    <i class="bi bi-chat-left-text" style="color:var(--primary)"></i> <?= e(substr($c['subject'], 0, 60)) ?>
                  </div>
                </div>
                <div style="color:var(--muted);font-size:0.72rem"><?= date('d M, H:i', strtotime($c['created_at'])) ?></div>
                <a href="<?= BASE_URL ?>admin/contact-enquiries.php" class="btn btn-ghost btn-sm">
                  View <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
          <div style="padding:1rem;text-align:center;background:var(--surface-2)">
            <a href="<?= BASE_URL ?>admin/contact-enquiries.php" style="color:var(--primary);font-weight:700;font-size:0.85rem">
              View All <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        <?php endif; ?>
      </div>

      <div class="tab-content" data-content="pages">
        <?php if (empty($top_pages)): ?>
          <div style="padding:3rem;text-align:center;color:var(--muted)">
            <i class="bi bi-bar-chart" style="font-size:2.5rem;opacity:0.4"></i>
            <p style="margin-top:0.8rem">No visits tracked yet.</p>
          </div>
        <?php else: 
          $max_hits = max(array_column($top_pages, 'hits'));
        ?>
          <div style="padding:1.5rem">
            <?php foreach ($top_pages as $p): 
              $pct = $max_hits > 0 ? ($p['hits'] / $max_hits) * 100 : 0;
            ?>
              <div style="margin-bottom:1rem">
                <div style="display:flex;justify-content:space-between;margin-bottom:0.4rem;font-size:0.88rem">
                  <span style="font-weight:600"><i class="bi bi-file-earmark" style="color:var(--primary)"></i> <?= e($p['page']) ?></span>
                  <strong><?= (int)$p['hits'] ?> views</strong>
                </div>
                <div style="height:6px;background:var(--primary-soft);border-radius:999px;overflow:hidden">
                  <div style="height:100%;width:<?= $pct ?>%;background:linear-gradient(90deg,var(--primary),var(--cyan));border-radius:999px"></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</section>

<style>
@keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }
@media (max-width: 900px) { .dash-charts { grid-template-columns: 1fr !important; } }
</style>

<script>
new Chart(document.getElementById('visitorChart'), {
  type: 'line',
  data: {
    labels: <?= json_encode($chart_labels) ?>,
    datasets: [{
      data: <?= json_encode($chart_data) ?>,
      borderColor: '#635BFF',
      backgroundColor: 'rgba(99,91,255,0.1)',
      tension: 0.4,
      fill: true,
      pointBackgroundColor: '#635BFF',
      pointBorderColor: '#fff',
      pointBorderWidth: 3,
      pointRadius: 5,
      borderWidth: 3
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#8A8F98' } },
      x: { grid: { display: false }, ticks: { color: '#8A8F98' } }
    }
  }
});

new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: {
    labels: <?= json_encode(array_map('ucfirst', array_column($quote_status, 'status'))) ?>,
    datasets: [{
      data: <?= json_encode(array_column($quote_status, 'c')) ?>,
      backgroundColor: ['#635BFF','#00D4FF','#FFAB00','#62666D','#00E599'],
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
</script>

<?php include '../includes/footer.php'; ?>