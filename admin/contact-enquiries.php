<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_admin();

$rows = $pdo->query("SELECT * FROM contact_enquiries ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Contact Enquiries';
include '../includes/header.php';
?>

<section class="section">
  <div class="container">

    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:2rem">
      <div>
        <h1 style="font-size:1.8rem;font-weight:800;letter-spacing:-0.02em">Contact Enquiries</h1>
        <p style="color:var(--muted);font-size:0.92rem;margin-top:0.3rem"><?= count($rows) ?> total messages</p>
      </div>
      <div style="display:flex;gap:0.5rem">
        <a href="<?= BASE_URL ?>admin/export.php?type=contacts" class="btn btn-primary">
          <i class="bi bi-file-earmark-excel"></i> Export CSV
        </a>
        <a href="<?= BASE_URL ?>admin/dashboard.php" class="btn btn-ghost">
          <i class="bi bi-arrow-left"></i> Back
        </a>
      </div>
    </div>

    <?php if (empty($rows)): ?>
      <div class="no-results">
        <i class="bi bi-inbox"></i>
        <h3>No contact enquiries yet</h3>
        <p>Messages will appear here.</p>
      </div>
    <?php else: ?>

      <div class="grid-3">
        <?php foreach ($rows as $i => $r): ?>
          <div class="clean-card reveal" data-delay="<?= $i * 40 ?>">
            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:0.7rem">
              <div style="display:flex;align-items:center;gap:0.6rem">
                <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--cyan));color:#fff;display:grid;place-items:center;font-weight:800;font-size:0.9rem;flex-shrink:0">
                  <?= e(strtoupper(substr($r['name'], 0, 1))) ?>
                </div>
                <strong style="font-size:0.95rem;color:var(--text)"><?= e($r['name']) ?></strong>
              </div>
              <small style="color:var(--muted);font-size:0.7rem"><?= date('d M', strtotime($r['created_at'])) ?></small>
            </div>

            <div style="font-size:0.82rem;color:var(--muted);margin-bottom:0.7rem">
              <div><i class="bi bi-envelope" style="color:var(--primary)"></i> <?= e($r['email']) ?></div>
              <?php if ($r['phone']): ?>
                <div style="margin-top:0.2rem"><i class="bi bi-telephone" style="color:var(--primary)"></i> <?= e($r['phone']) ?></div>
              <?php endif; ?>
            </div>

            <div style="font-weight:700;margin-bottom:0.5rem;font-size:0.88rem;color:var(--text-2)">
              <?= e($r['subject']) ?>
            </div>

            <p style="color:var(--muted);font-size:0.85rem;line-height:1.55;margin:0;padding-top:0.7rem;border-top:1px solid var(--border)">
              <?= e($r['message']) ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>

  </div>
</section>

<?php include '../includes/footer.php'; ?>