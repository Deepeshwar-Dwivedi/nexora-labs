<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$posts = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC")->fetchAll();

$featured = !empty($posts) ? $posts[0] : null;
$otherPosts = array_slice($posts, 1);

$categories = [];
foreach ($posts as $p) {
    $cat = $p['category'] ?? 'General';
    if (!in_array($cat, $categories)) $categories[] = $cat;
}
sort($categories);

$totalPosts = count($posts);
$totalCats  = count($categories);

$authors = [];
foreach ($posts as $p) {
    if (!empty($p['author']) && !in_array($p['author'], $authors)) $authors[] = $p['author'];
}

$pageTitle = 'Insights';
$pageDesc  = 'Practical advice on software, AI and digital transformation.';

include 'includes/header.php';

$blogCatColors = [
    'Software'               => 'var(--primary)',
    'Business'               => 'var(--cyan)',
    'AI'                     => 'var(--pink)',
    'Web Development'        => 'var(--green)',
    'Technology'             => 'var(--primary)',
    'Digital Transformation' => 'var(--yellow)',
];
?>

<!-- HERO -->
<section class="hero">
  <div class="container" style="text-align:center">
    <span class="eyebrow">Insights</span>
    <h1 class="display-2" style="margin-bottom:1rem">
      Latest <span class="gradient-text">thinking.</span>
    </h1>
    <p class="lead" style="margin:0 auto 2.5rem;text-align:center">
      Practical advice on software, AI and digital transformation — from the team that ships.
    </p>

    <div class="hero-stats" style="justify-content:center;max-width:720px;margin-left:auto;margin-right:auto">
      <div class="hero-stat"><strong data-count="<?= $totalPosts ?>">0</strong><span>Articles</span></div>
      <div class="hero-stat"><strong data-count="<?= $totalCats ?>">0</strong><span>Categories</span></div>
      <div class="hero-stat"><strong data-count="<?= count($authors) ?>">0</strong><span>Authors</span></div>
      <div class="hero-stat"><strong data-count="1000">0</strong><span>Readers</span></div>
    </div>
  </div>
</section>

<!-- MAIN -->
<section class="section" style="padding-top:1rem">
  <div class="container">

    <?php if (empty($posts)): ?>
      <div class="no-results">
        <i class="bi bi-journal-x"></i>
        <h3>No articles yet</h3>
        <p>Insights will appear here once published.</p>
      </div>
    <?php else: ?>

      <!-- Search -->
      <div class="search-wrap">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" id="filterSearch" placeholder="Search articles, topics or keywords...">
        </div>
      </div>

      <!-- Filter -->
      <div class="filter-bar">
        <button class="filter-btn active" data-filter="all">
          <i class="bi bi-grid-3x3-gap-fill"></i> All
          <span class="filter-count"><?= $totalPosts ?></span>
        </button>
        <?php foreach ($categories as $cat):
          $count = 0;
          foreach ($posts as $p) if (($p['category'] ?? '') === $cat) $count++;
          $cc = $blogCatColors[$cat] ?? 'var(--primary)';
        ?>
          <button class="filter-btn" data-filter="<?= e($cat) ?>">
            <i class="bi <?= blog_cat_icon($cat) ?>"></i> <?= e($cat) ?>
            <span class="filter-count"><?= $count ?></span>
          </button>
        <?php endforeach; ?>
      </div>

      <!-- Featured -->
      <?php if ($featured):
        $fCat = $featured['category'] ?? 'General';
        $fColor = $blogCatColors[$fCat] ?? 'var(--primary)';
      ?>
        <a href="<?= BASE_URL ?>blog-detail.php?slug=<?= e($featured['slug']) ?>"
           class="bento-card span-2 reveal"
           style="display:grid;grid-template-columns:1.1fr 1fr;gap:2rem;padding:0;overflow:hidden;min-height:380px;cursor:pointer;margin-bottom:2.5rem;grid-column:span 2"
           data-category="<?= e($fCat) ?>"
           data-search="<?= e(strtolower($featured['title'] . ' ' . $fCat . ' ' . $featured['excerpt'])) ?>">

          <div style="position:relative;background:linear-gradient(135deg, <?= $fColor ?>, var(--cyan));display:grid;place-items:center;min-height:340px;overflow:hidden">
            <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.08) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.08) 1px,transparent 1px);background-size:26px 26px"></div>
            <i class="bi <?= blog_cat_icon($fCat) ?>" style="position:relative;z-index:2;font-size:6rem;color:rgba(255,255,255,0.95)"></i>
            <span style="position:absolute;top:20px;left:20px;z-index:3;background:rgba(0,0,0,0.55);color:#fff;backdrop-filter:blur(10px);font-size:0.65rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;padding:0.35rem 0.8rem;border-radius:999px;border:1px solid rgba(255,255,255,0.2)">
              <i class="bi bi-star-fill" style="color:var(--yellow)"></i> Featured
            </span>
          </div>

          <div style="padding:2.5rem 2.5rem 2.5rem 0;display:flex;flex-direction:column;justify-content:center">
            <span class="badge" style="background:color-mix(in srgb, <?= $fColor ?> 15%, transparent);color:<?= $fColor ?>;align-self:flex-start;margin-bottom:1rem">
              <i class="bi <?= blog_cat_icon($fCat) ?>"></i> <?= e($fCat) ?>
            </span>
            <h2 style="font-size:clamp(1.3rem,2.4vw,1.8rem);font-weight:800;line-height:1.25;letter-spacing:-0.02em;margin-bottom:0.8rem">
              <?= e($featured['title']) ?>
            </h2>
            <p style="color:var(--muted);font-size:0.95rem;line-height:1.65;margin-bottom:1.2rem">
              <?= e($featured['excerpt']) ?>
            </p>
            <div class="blog-meta" style="border:none;padding-top:0;margin-bottom:1rem">
              <span><i class="bi bi-person-circle"></i> <?= e($featured['author']) ?></span>
              <span><i class="bi bi-clock"></i> <?= (int)$featured['reading_time'] ?> min read</span>
              <span><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($featured['created_at'])) ?></span>
            </div>
            <span class="btn btn-primary" style="align-self:flex-start">
              Read Article <i class="bi bi-arrow-right"></i>
            </span>
          </div>
        </a>
      <?php endif; ?>

      <!-- Grid -->
      <div class="grid-3">
        <?php foreach ($otherPosts as $i => $b):
          $cat = $b['category'] ?? 'General';
          $cc = $blogCatColors[$cat] ?? 'var(--primary)';
        ?>
          <a href="<?= BASE_URL ?>blog-detail.php?slug=<?= e($b['slug']) ?>"
             class="blog-card reveal"
             data-category="<?= e($cat) ?>"
             data-search="<?= e(strtolower($b['title'] . ' ' . $cat . ' ' . $b['excerpt'] . ' ' . $b['author'])) ?>"
             data-delay="<?= $i * 50 ?>">

            <span class="blog-cat" style="background:color-mix(in srgb, <?= $cc ?> 15%, transparent);color:<?= $cc ?>">
              <i class="bi <?= blog_cat_icon($cat) ?>"></i> <?= e($cat) ?>
            </span>
            <h3><?= e($b['title']) ?></h3>
            <p><?= e($b['excerpt']) ?></p>
            <div class="blog-meta">
              <span><i class="bi bi-person-circle"></i> <?= e($b['author']) ?></span>
              <span><i class="bi bi-clock"></i> <?= (int)$b['reading_time'] ?> min</span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <div class="no-results" id="noResults" style="display:none">
        <i class="bi bi-search"></i>
        <h3>No matching articles</h3>
        <p>Try a different filter or clear your search.</p>
      </div>

    <?php endif; ?>

    <!-- Newsletter -->
    <div class="cta-box" style="margin-top:3rem">
      <span class="eyebrow"><i class="bi bi-envelope-heart"></i> Newsletter</span>
      <h2>Get new articles in your inbox</h2>
      <p>Join 1000+ readers getting practical insights on software, AI and digital transformation. No spam, unsubscribe anytime.</p>
      <form method="post" action="<?= BASE_URL ?>subscribe.php" style="display:flex;gap:0.6rem;max-width:480px;margin:0 auto;flex-wrap:wrap;justify-content:center">
        <input type="email" name="email" placeholder="you@company.com" required
               style="flex:1;min-width:220px;padding:0.75rem 1rem;background:var(--surface-2);border:1.5px solid var(--border-strong);border-radius:10px;color:var(--text);font-size:0.92rem">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-send-fill"></i> Subscribe
        </button>
      </form>
    </div>

  </div>
</section>

<style>
@media (max-width: 900px) {
  .bento-card.span-2 { grid-template-columns: 1fr !important; }
  .bento-card.span-2 > div:last-child { padding: 2rem !important; }
}
</style>

<?php include 'includes/footer.php'; ?>