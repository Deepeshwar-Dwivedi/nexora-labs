<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_admin();

$rows = $pdo->query("SELECT * FROM consultations ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Consultations';
include '../includes/header.php';
?>

<section class="section">
  <div class="container">

    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:2rem">
      <div>
        <h1 style="font-size:1.8rem;font-weight:800;letter-spacing:-0.02em">Consultations</h1>
        <p style="color:var(--muted);font-size:0.92rem;margin-top:0.3rem"><?= count($rows) ?> total bookings</p>
      </div>
      <div style="display:flex;gap:0.5rem">
        <a href="<?= BASE_URL ?>admin/export.php?type=consultations" class="btn btn-primary">
          <i class="bi bi-file-earmark-excel"></i> Export CSV
        </a>
        <a href="<?= BASE_URL ?>admin/dashboard.php" class="btn btn-ghost">
          <i class="bi bi-arrow-left"></i> Back
        </a>
      </div>
    </div>

    <?php if (empty($rows)): ?>
      <div class="no-results">
        <i class="bi bi-calendar-x"></i>
        <h3>No consultations yet</h3>
        <p>Booked calls will appear here.</p>
      </div>
    <?php else: ?>

      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Service</th>
              <th>Date</th>
              <th>Time</th>
              <th>Booked</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><strong style="color:var(--text)"><?= e($r['name']) ?></strong></td>
                <td><a href="mailto:<?= e($r['email']) ?>" style="color:var(--primary);font-weight:600"><?= e($r['email']) ?></a></td>
                <td><?= e($r['phone'] ?: '—') ?></td>
                <td><?= e($r['service'] ?: '—') ?></td>
                <td>
                  <span class="badge badge-cyan">
                    <i class="bi bi-calendar"></i> <?= $r['preferred_date'] ? date('d M Y', strtotime($r['preferred_date'])) : '—' ?>
                  </span>
                </td>
                <td><?= e($r['preferred_time'] ?: '—') ?></td>
                <td style="color:var(--muted)"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    <?php endif; ?>

  </div>
</section>

<?php include '../includes/footer.php'; ?>