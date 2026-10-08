<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$cats = $pdo->query("SELECT DISTINCT category FROM faqs ORDER BY category")->fetchAll();
$current_cat = $_GET['cat'] ?? '';

if ($current_cat) {
    $stmt = $pdo->prepare("SELECT * FROM faqs WHERE category = ? ORDER BY id");
    $stmt->execute([$current_cat]);
    $faqs = $stmt->fetchAll();
} else {
    $faqs = $pdo->query("SELECT * FROM faqs ORDER BY category, id")->fetchAll();
}

$totalFaqs = $pdo->query("SELECT COUNT(*) FROM faqs")->fetchColumn();

$pageTitle = 'FAQ';
$pageDesc  = 'Frequently asked questions about working with Nexora Labs.';

include 'includes/header.php';

$catColors = [
    'General'     => 'var(--primary)',
    'Pricing'     => 'var(--yellow)',
    'Development' => 'var(--cyan)',
    'Support'     => 'var(--green)',
    'Security'    => 'var(--red)',
    'Payments'    => 'var(--green)',
];
?>

<section class="hero">
  <div class="container" style="text-align:center">
    <span class="eyebrow">FAQ</span>
    <h1 class="display-2" style="margin-bottom:1rem">
      Questions, <span class="gradient-text">answered.</span>
    </h1>
    <p class="lead" style="margin:0 auto 2.5rem;text-align:center">
      Everything you need to know before starting a project with us.
    </p>

    <div class="hero-stats" style="justify-content:center;max-width:720px;margin-left:auto;margin-right:auto">
      <div class="hero-stat"><strong data-count="<?= $totalFaqs ?>">0</strong><span>Total FAQs</span></div>
      <div class="hero-stat"><strong data-count="<?= count($cats) ?>">0</strong><span>Categories</span></div>
      <div class="hero-stat"><strong>24h</strong><span>Reply Time</span></div>
      <div class="hero-stat"><strong data-count="500">0</strong><span>Happy Clients</span></div>
    </div>
  </div>
</section>

<section class="section" style="padding-top:1rem">
  <div class="container">
    <div class="narrow">

      <div class="filter-bar" style="flex-wrap:wrap">
        <a href="faq.php" class="filter-btn <?= $current_cat===''?'active':'' ?>" style="text-decoration:none">
          <i class="bi bi-grid-3x3-gap-fill"></i> All
          <span class="filter-count"><?= $totalFaqs ?></span>
        </a>
        <?php foreach ($cats as $c):
          $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM faqs WHERE category = ?");
          $stmtCount->execute([$c['category']]);
          $count = $stmtCount->fetchColumn();
        ?>
          <a href="faq.php?cat=<?= urlencode($c['category']) ?>"
             class="filter-btn <?= $current_cat===$c['category']?'active':'' ?>"
             style="text-decoration:none">
            <?= e($c['category']) ?>
            <span class="filter-count"><?= $count ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <?php if (empty($faqs)): ?>
        <div class="no-results">
          <i class="bi bi-inbox"></i>
          <h3>No FAQs in this category</h3>
          <p>Try a different category or view all questions.</p>
        </div>
      <?php else: ?>
        <?php foreach ($faqs as $f):
          $cc = $catColors[$f['category']] ?? 'var(--primary)';
        ?>
          <details class="faq-item">
            <summary>
              <span>
                <span class="badge" style="background:color-mix(in srgb, <?= $cc ?> 15%, transparent);color:<?= $cc ?>;margin-bottom:0.5rem;display:inline-flex">
                  <?= e($f['category']) ?>
                </span>
                <span style="display:block"><?= e($f['question']) ?></span>
              </span>
            </summary>
            <p><?= e($f['answer']) ?></p>
          </details>
        <?php endforeach; ?>
      <?php endif; ?>

      <!-- Support CTA -->
      <div class="cta-box" style="margin-top:3rem">
        <span class="eyebrow">Still curious?</span>
        <h2>Still have questions?</h2>
        <p>Our team is here to help. Reach out and we'll get back within 24 hours.</p>
        <div class="cta-actions">
          <a href="<?= BASE_URL ?>contact.php" class="btn btn-primary btn-lg">
            <i class="bi bi-send-fill"></i> Contact Us
          </a>
          <a href="<?= BASE_URL ?>book-consultation.php" class="btn btn-ghost btn-lg">
            <i class="bi bi-calendar-check"></i> Book a Call
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>