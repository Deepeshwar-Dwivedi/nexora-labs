<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$items = $pdo->query("SELECT * FROM portfolio ORDER BY id DESC")->fetchAll();

$categories = [];
foreach ($items as $it) {
    $cat = $it['category'] ?? 'Other';
    if (!in_array($cat, $categories)) $categories[] = $cat;
}
sort($categories);

$total_projects   = count($items);
$total_categories = count($categories);

$pageTitle = 'Portfolio';
$pageDesc  = 'Real projects, real results — built with modern technology.';

include 'includes/header.php';

$catColors = [
    'ERP'         => 'var(--primary)',
    'CRM'         => 'var(--cyan)',
    'E-commerce'  => 'var(--green)',
    'Mobile Apps' => 'var(--pink)',
    'Websites'    => 'var(--yellow)',
    'Software'    => 'var(--primary)',
    'AI'          => 'var(--pink)',
    'Automation'  => 'var(--cyan)',
];
?>

<!-- HERO -->
<section class="hero">
  <div class="container" style="text-align:center">
    <span class="eyebrow">Portfolio</span>
    <h1 class="display-2" style="margin-bottom:1rem">
      Selected <span class="gradient-text">work.</span>
    </h1>
    <p class="lead" style="margin:0 auto 2.5rem;text-align:center">
      Real projects, real results — built with modern technology and engineering discipline.
    </p>

    <div class="hero-stats" style="justify-content:center;max-width:720px;margin-left:auto;margin-right:auto">
      <div class="hero-stat"><strong data-count="<?= $total_projects ?>">0</strong><span>Projects Delivered</span></div>
      <div class="hero-stat"><strong data-count="<?= $total_categories ?>">0</strong><span>Categories</span></div>
      <div class="hero-stat"><strong data-count="98">0</strong><span>% Satisfaction</span></div>
      <div class="hero-stat"><strong data-count="11">0</strong><span>Industries</span></div>
    </div>
  </div>
</section>

<!-- GRID -->
<section class="section" style="padding-top:1rem">
  <div class="container">

    <?php if (empty($items)): ?>
      <div class="no-results">
        <i class="bi bi-folder-x"></i>
        <h3>No projects yet</h3>
        <p>Portfolio projects will appear here soon.</p>
      </div>
    <?php else: ?>

      <div class="search-wrap">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" id="filterSearch" placeholder="Search projects, tech or description...">
        </div>
      </div>

      <div class="filter-bar">
        <button class="filter-btn active" data-filter="all">
          <i class="bi bi-grid-3x3-gap-fill"></i> All
          <span class="filter-count"><?= $total_projects ?></span>
        </button>
        <?php foreach ($categories as $cat):
          $count = 0;
          foreach ($items as $it) if (($it['category'] ?? '') === $cat) $count++;
        ?>
          <button class="filter-btn" data-filter="<?= e($cat) ?>">
            <i class="bi <?= cat_icon($cat) ?>"></i> <?= e($cat) ?>
            <span class="filter-count"><?= $count ?></span>
          </button>
        <?php endforeach; ?>
      </div>

      <div class="grid-3">
        <?php foreach ($items as $i => $p):
          $color = $catColors[$p['category']] ?? 'var(--primary)';
        ?>
          <a href="<?= BASE_URL ?>portfolio-detail.php?id=<?= (int)$p['id'] ?>"
             class="portfolio-card reveal"
             style="--card-color: <?= $color ?>"
             data-category="<?= e($p['category']) ?>"
             data-search="<?= e(strtolower($p['title'] . ' ' . $p['category'] . ' ' . $p['technology'] . ' ' . $p['description'])) ?>"
             data-delay="<?= $i * 40 ?>">
            <div class="pc-header">
              <span class="pc-cat"><?= e($p['category']) ?></span>
              <i class="bi <?= cat_icon($p['category']) ?>"></i>
            </div>
            <div class="pc-body">
              <h3><?= e($p['title']) ?></h3>
              <p><?= e($p['description']) ?></p>
              <span class="pc-tech">
                <i class="bi bi-code-slash"></i> <?= e($p['technology']) ?>
              </span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <div class="no-results" id="noResults" style="display:none">
        <i class="bi bi-search"></i>
        <h3>No matching projects</h3>
        <p>Try a different filter or clear your search.</p>
      </div>

    <?php endif; ?>

    <!-- CTA -->
    <div class="cta-box" style="margin-top:3rem">
      <span class="eyebrow">Get started</span>
      <h2>Have a project in mind?</h2>
      <p>Tell us what you want to build — we'll reply with a detailed proposal within 24 hours.</p>
      <div class="cta-actions">
        <a href="<?= BASE_URL ?>request-quote.php" class="btn btn-primary btn-lg">
          <i class="bi bi-rocket-takeoff"></i> Start Your Project
        </a>
        <a href="<?= BASE_URL ?>contact.php" class="btn btn-ghost btn-lg">
          <i class="bi bi-chat-dots"></i> Talk to Us
        </a>
      </div>
    </div>

  </div>
</section>

<?php include 'includes/footer.php'; ?>