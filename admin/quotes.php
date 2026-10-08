<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_admin();

$quotes = $pdo->query("SELECT * FROM quotes ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Quote Requests';
include '../includes/header.php';
?>

<section class="section">
  <div class="container">

    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:2rem">
      <div>
        <h1 style="font-size:1.8rem;font-weight:800;letter-spacing:-0.02em">Quote Requests</h1>
        <p style="color:var(--muted);font-size:0.92rem;margin-top:0.3rem"><?= count($quotes) ?> total enquiries</p>
      </div>
      <div style="display:flex;gap:0.5rem">
        <a href="<?= BASE_URL ?>admin/export.php?type=quotes" class="btn btn-primary">
          <i class="bi bi-file-earmark-excel"></i> Export CSV
        </a>
        <a href="<?= BASE_URL ?>admin/dashboard.php" class="btn btn-ghost">
          <i class="bi bi-arrow-left"></i> Back
        </a>
      </div>
    </div>

    <?php if (empty($quotes)): ?>
      <div class="no-results">
        <i class="bi bi-inbox"></i>
        <h3>No quote requests yet</h3>
        <p>New enquiries will appear here.</p>
      </div>
    <?php else: ?>

      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Enquiry ID</th>
              <th>Name</th>
              <th>Contact</th>
              <th>Service</th>
              <th>Budget</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($quotes as $q): ?>
              <tr>
                <td>
                  <span style="font-family:var(--font-mono);font-weight:700;color:var(--primary);font-size:0.78rem"><?= e($q['enquiry_id']) ?></span>
                </td>
                <td>
                  <strong style="color:var(--text)"><?= e($q['name']) ?></strong>
                  <?php if ($q['company']): ?><br><small style="color:var(--muted)"><?= e($q['company']) ?></small><?php endif; ?>
                </td>
                <td>
                  <a href="mailto:<?= e($q['email']) ?>" style="color:var(--primary);font-weight:600"><?= e($q['email']) ?></a>
                  <?php if ($q['phone']): ?><br><small style="color:var(--muted)"><?= e($q['phone']) ?></small><?php endif; ?>
                </td>
                <td><?= e($q['service'] ?: '—') ?></td>
                <td>
                  <?php if ($q['budget']): ?>
                    <span class="badge badge-green"><?= e($q['budget']) ?></span>
                  <?php else: ?>—<?php endif; ?>
                </td>
                <td style="color:var(--muted)"><?= date('d M Y', strtotime($q['created_at'])) ?></td>
                <td>
                  <button onclick='showDetails(<?= htmlspecialchars(json_encode($q), ENT_QUOTES) ?>)' class="btn btn-ghost btn-sm">
                    <i class="bi bi-eye"></i> View
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    <?php endif; ?>

  </div>
</section>

<!-- Modal -->
<div id="detailModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);z-index:9999;align-items:center;justify-content:center;padding:1rem">
  <div style="background:var(--surface-solid);border:1px solid var(--border);border-radius:var(--radius-lg);max-width:600px;width:100%;max-height:90vh;overflow-y:auto;padding:2rem;box-shadow:var(--shadow-lg)">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
      <h2 style="font-size:1.3rem;font-weight:800">Quote Details</h2>
      <button onclick="document.getElementById('detailModal').style.display='none'" style="font-size:1.5rem;color:var(--muted);width:36px;height:36px;border-radius:8px;display:grid;place-items:center">×</button>
    </div>
    <div id="modalContent"></div>
  </div>
</div>

<script>
function showDetails(q) {
  const rows = [
    ['Enquiry ID', q.enquiry_id],
    ['Name', q.name],
    ['Email', q.email],
    ['Phone', q.phone || '—'],
    ['Company', q.company || '—'],
    ['Service', q.service || '—'],
    ['Budget', q.budget || '—'],
    ['Timeline', q.timeline || '—'],
    ['Requirements', q.requirements || '—'],
    ['Status', q.status],
    ['Submitted', new Date(q.created_at).toLocaleString()]
  ];
  let html = '';
  rows.forEach(([k, v]) => {
    html += `<div style="padding:0.8rem 0;border-bottom:1px solid var(--border)">
      <div style="color:var(--muted);font-size:0.7rem;text-transform:uppercase;font-weight:800;letter-spacing:0.05em;margin-bottom:0.35rem">${k}</div>
      <div style="font-size:0.92rem;color:var(--text-2);word-wrap:break-word;line-height:1.5">${String(v).replace(/</g,'&lt;')}</div>
    </div>`;
  });
  document.getElementById('modalContent').innerHTML = html;
  document.getElementById('detailModal').style.display = 'flex';
}
</script>

<?php include '../includes/footer.php'; ?>