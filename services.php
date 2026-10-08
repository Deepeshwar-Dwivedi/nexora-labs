<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$services = get_services();
$totalServices = count($services);

$pageTitle = 'Services';
$pageDesc  = 'Complete digital capability — websites, software, apps, AI, cloud, design and more.';

include 'includes/header.php';

// Color per service
$serviceColors = [
    'web-development'      => 'var(--primary)',
    'software-development' => 'var(--cyan)',
    'app-development'      => 'var(--green)',
    'ui-ux-design'         => 'var(--yellow)',
    'ai-automation'        => 'var(--pink)',
    'cloud-infra'          => 'var(--primary)',
    'digital-marketing'    => 'var(--cyan)',
    'maintenance'          => 'var(--green)',
];

// Group services by category
$groups = [
    'development'    => ['web-development', 'software-development', 'app-development', 'ai-automation'],
    'design'         => ['ui-ux-design', 'digital-marketing'],
    'infrastructure' => ['cloud-infra', 'maintenance'],
];
?>

<!-- ============================================ -->
<!-- HERO                                        -->
<!-- ============================================ -->
<section class="hero">
  <div class="container" style="text-align:center">
    <span class="eyebrow">Services</span>
    <h1 class="display-2" style="margin-bottom:1rem">
      Everything you need to <span class="gradient-text">go digital.</span>
    </h1>
    <p class="lead" style="margin:0 auto 2.5rem;text-align:center">
      Choose from our full-service technology stack — engineered end-to-end by senior developers.
    </p>

    <div class="hero-stats" style="justify-content:center;max-width:720px;margin-left:auto;margin-right:auto">
      <div class="hero-stat">
        <strong data-count="<?= $totalServices ?>">0</strong>
        <span>Services</span>
      </div>
      <div class="hero-stat">
        <strong data-count="500">0</strong>
        <span>Projects Delivered</span>
      </div>
      <div class="hero-stat">
        <strong data-count="50">0</strong>
        <span>Technologies</span>
      </div>
      <div class="hero-stat">
        <strong>24/7</strong>
        <span>Support</span>
      </div>
    </div>
  </div>
</section>

<!-- ============================================ -->
<!-- SERVICES GRID                               -->
<!-- ============================================ -->
<section class="section" style="padding-top:1rem">
  <div class="container">

    <?php if (empty($services)): ?>

      <div class="no-results">
        <i class="bi bi-inbox"></i>
        <h3>No services yet</h3>
        <p>Services will appear here once added.</p>
      </div>

    <?php else: ?>

      <!-- Filter bar -->
      <div class="filter-bar">
        <button class="filter-btn active" data-filter="all">
          <i class="bi bi-grid-3x3-gap-fill"></i>
          All Services
          <span class="filter-count"><?= $totalServices ?></span>
        </button>
        <button class="filter-btn" data-filter="development">
          <i class="bi bi-code-slash"></i> Development
        </button>
        <button class="filter-btn" data-filter="design">
          <i class="bi bi-palette"></i> Design
        </button>
        <button class="filter-btn" data-filter="infrastructure">
          <i class="bi bi-cloud"></i> Infrastructure
        </button>
      </div>

      <div class="grid-3">
        <?php foreach ($services as $i => $s):
          $color = $serviceColors[$s['slug']] ?? 'var(--primary)';
          // Group classify
          $group = 'development';
          foreach ($groups as $g => $slugs) {
              if (in_array($s['slug'], $slugs)) { $group = $g; break; }
          }
        ?>
          <a href="<?= BASE_URL ?>service-detail.php?slug=<?= e($s['slug']) ?>"
             class="service-card reveal"
             style="--card-color: <?= $color ?>"
             data-category="<?= $group ?>"
             data-delay="<?= $i * 60 ?>">

            <div class="sc-icon">
              <i class="bi <?= e($s['icon']) ?>"></i>
            </div>

            <h3><?= e($s['title']) ?></h3>
            <p><?= e($s['short_desc']) ?></p>

            <ul class="sc-features">
              <?php foreach (array_slice(feature_list($s['features']), 0, 4) as $f): ?>
                <li><i class="bi bi-check2-circle"></i><?= e($f) ?></li>
              <?php endforeach; ?>
            </ul>

            <div class="sc-foot">
              <span class="link-arrow">
                Learn More <i class="bi bi-arrow-right"></i>
              </span>
              <span class="chip">Get Quote</span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <div class="no-results" id="noResults" style="display:none">
        <i class="bi bi-search"></i>
        <h3>No matching services</h3>
        <p>Try a different filter.</p>
      </div>

    <?php endif; ?>

  </div>
</section>

<!-- ============================================ -->
<!-- WHY US                                      -->
<!-- ============================================ -->
<section class="section">
  <div class="container">

    <div class="section-head center">
      <span class="eyebrow">Why us</span>
      <h2 class="section-title">Built different. <span class="gradient-text">Delivered better.</span></h2>
      <p class="section-sub">Senior engineers, clear communication, and no surprises.</p>
    </div>

    <div class="grid-4">
      <?php
      $whyItems = [
        ['bi-people-fill',      'Senior Team',      'No juniors on critical paths.',        'var(--primary)'],
        ['bi-shield-lock-fill', 'Secure by Design', 'OWASP-aligned security throughout.',   'var(--green)'],
        ['bi-lightning-charge-fill', 'Fast Delivery', 'Sprint-based, weekly demos.',         'var(--yellow)'],
        ['bi-arrow-repeat',     'Post-Launch Care', '30 days free support included.',       'var(--cyan)'],
      ];
      foreach ($whyItems as $i => $w): ?>
        <div class="bento-card reveal" data-delay="<?= $i * 60 ?>" style="min-height:auto">
          <div class="bento-icon" style="background:color-mix(in srgb, <?= $w[3] ?> 15%, transparent);color:<?= $w[3] ?>">
            <i class="bi <?= $w[0] ?>"></i>
          </div>
          <h3 style="font-size:1rem;margin:0"><?= $w[1] ?></h3>
          <p style="margin:0"><?= $w[2] ?></p>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ============================================ -->
<!-- FINAL CTA                                   -->
<!-- ============================================ -->
<section class="section">
  <div class="container">
    <div class="cta-box">
      <span class="eyebrow">Get started</span>
      <h2>Not sure which service you need?</h2>
      <p>Tell us your project — we'll suggest the right approach and give you a detailed quote.</p>
      <div class="cta-actions">
        <a href="<?= BASE_URL ?>request-quote.php" class="btn btn-primary btn-lg">
          <i class="bi bi-file-earmark-text"></i> Request a Quote
        </a>
        <a href="<?= BASE_URL ?>calculator.php" class="btn btn-ghost btn-lg">
          <i class="bi bi-calculator"></i> Cost Calculator
        </a>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>