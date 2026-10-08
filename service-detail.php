<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
if (empty($slug)) redirect('services.php');

$stmt = $pdo->prepare("SELECT * FROM services WHERE slug = ? LIMIT 1");
$stmt->execute([$slug]);
$service = $stmt->fetch();
if (!$service) redirect('services.php');

// Related services
$relStmt = $pdo->prepare("SELECT * FROM services WHERE slug != ? ORDER BY RAND() LIMIT 3");
$relStmt->execute([$slug]);
$related = $relStmt->fetchAll();

// Testimonials
$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY RAND() LIMIT 3")->fetchAll();

$features = feature_list($service['features']);

$serviceIcons = [
    'web-development'      => 'bi-globe2',
    'software-development' => 'bi-cpu',
    'app-development'      => 'bi-phone',
    'ui-ux-design'         => 'bi-palette',
    'ai-automation'        => 'bi-robot',
    'cloud-infra'          => 'bi-cloud',
    'digital-marketing'    => 'bi-megaphone',
    'maintenance'          => 'bi-tools',
];
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

$heroIcon   = $serviceIcons[$slug] ?? 'bi-stars';
$heroColor  = $serviceColors[$slug] ?? 'var(--primary)';

// Content per service
$serviceContent = [
    'web-development' => [
        'problems' => [
            'Outdated website hurting brand credibility',
            'Slow load times killing conversions and SEO',
            'No mobile optimization, losing 60%+ of traffic',
            'Difficult to update content without developer help',
        ],
        'benefits' => [
            'Faster load times and higher Google rankings',
            'Better user experience across all devices',
            'Easy content management for your team',
            'Higher conversion rates and more leads',
        ],
    ],
    'software-development' => [
        'problems' => [
            'Manual processes eating up 20+ hours per week',
            'Disconnected tools causing data silos',
            'Spreadsheets breaking at scale',
            'No visibility into business metrics',
        ],
        'benefits' => [
            'Automated workflows saving hundreds of hours',
            'Single source of truth for all data',
            'Scales with your business growth',
            'Real-time dashboards and reports',
        ],
    ],
    'ai-automation' => [
        'problems' => [
            'Repetitive tasks draining team productivity',
            'Slow customer support response times',
            'Manual document processing errors',
            'No 24/7 customer engagement',
        ],
        'benefits' => [
            'AI handles repetitive work 24/7',
            'Instant responses to customer queries',
            'Accurate document processing at scale',
            'Team focuses on high-value work',
        ],
    ],
    'app-development' => [
        'problems' => [
            'No mobile presence for your customers',
            'Competitors with apps winning market share',
            'Manual booking/ordering process',
            'Limited customer engagement',
        ],
        'benefits' => [
            'Direct connection with customers on mobile',
            'Faster booking and purchase flows',
            'Push notifications for instant reach',
            'Better customer loyalty and retention',
        ],
    ],
];

$content = $serviceContent[$slug] ?? [
    'problems' => [
        'Manual processes costing you time and money',
        'Legacy systems holding back growth',
        'Disconnected tools creating data chaos',
        'No visibility into key business metrics',
    ],
    'benefits' => [
        'Streamlined operations and workflows',
        'Modern, scalable infrastructure',
        'Unified data across all systems',
        'Real-time insights for better decisions',
    ],
];

$pageTitle = $service['title'];
$pageDesc  = $service['short_desc'];

include 'includes/header.php';
?>

<!-- ============================================ -->
<!-- HERO                                        -->
<!-- ============================================ -->
<section class="hero" style="padding-bottom:2rem">
  <div class="container">

    <nav class="breadcrumb" style="justify-content:flex-start;margin-bottom:2rem">
      <a href="<?= BASE_URL ?>">Home</a>
      <i class="bi bi-chevron-right"></i>
      <a href="<?= BASE_URL ?>services.php">Services</a>
      <i class="bi bi-chevron-right"></i>
      <span><?= e($service['title']) ?></span>
    </nav>

    <div class="hero-grid">

      <div class="hero-content">
        <span class="badge badge-primary" style="margin-bottom:1rem">
          <i class="bi <?= $heroIcon ?>"></i> Our Service
        </span>

        <h1 style="font-size:clamp(2rem,4vw,3rem);margin-bottom:1rem">
          <?= e($service['title']) ?>
        </h1>

        <p class="lead" style="margin-bottom:2rem">
          <?= e($service['short_desc']) ?>
        </p>

        <div class="hero-cta">
          <a href="<?= BASE_URL ?>request-quote.php?service=<?= e($service['slug']) ?>" class="btn btn-primary btn-lg">
            <i class="bi bi-file-earmark-text"></i> Request a Quote
          </a>
          <a href="<?= BASE_URL ?>book-consultation.php" class="btn btn-ghost btn-lg">
            <i class="bi bi-calendar-check"></i> Book a Call
          </a>
        </div>
      </div>

      <div class="hero-visual">
        <?php foreach (array_slice($features, 0, 5) as $fi => $f): ?>
          <div class="float-card" style="position:relative;top:auto;left:auto;right:auto;bottom:auto;animation-delay:<?= $fi * 0.3 ?>s;margin-bottom:0.8rem;width:100%">
            <i class="bi bi-check2-circle" style="background:color-mix(in srgb, <?= $heroColor ?> 15%, transparent);color:<?= $heroColor ?>"></i>
            <div>
              <strong><?= e($f) ?></strong>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>

<!-- ============================================ -->
<!-- PROBLEMS WE SOLVE                           -->
<!-- ============================================ -->
<section class="section">
  <div class="container">

    <div class="section-head center">
      <span class="eyebrow">Problems we solve</span>
      <h2 class="section-title">Common challenges <span class="gradient-text">we fix.</span></h2>
      <p class="section-sub">If any of these sound familiar, we can help.</p>
    </div>

    <div class="grid-2">
      <?php foreach ($content['problems'] as $i => $problem): ?>
        <div class="bento-card reveal" data-delay="<?= $i * 60 ?>" style="min-height:auto;border-left:3px solid var(--red)">
          <div class="bento-icon" style="background:var(--red-soft);color:var(--red)">
            <i class="bi bi-x-circle-fill"></i>
          </div>
          <p style="font-size:0.95rem;color:var(--text-2);margin:0;font-weight:500"><?= e($problem) ?></p>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ============================================ -->
<!-- BENEFITS                                    -->
<!-- ============================================ -->
<section class="section">
  <div class="container">

    <div class="section-head center">
      <span class="eyebrow">What you get</span>
      <h2 class="section-title">The benefits <span class="gradient-text">you'll see.</span></h2>
      <p class="section-sub">Real, measurable outcomes from our work.</p>
    </div>

    <div class="grid-2">
      <?php foreach ($content['benefits'] as $i => $benefit): ?>
        <div class="bento-card reveal" data-delay="<?= $i * 60 ?>" style="min-height:auto;border-left:3px solid var(--green)">
          <div class="bento-icon" style="background:var(--green-soft);color:var(--green)">
            <i class="bi bi-check-circle-fill"></i>
          </div>
          <p style="font-size:0.95rem;color:var(--text-2);margin:0;font-weight:500"><?= e($benefit) ?></p>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ============================================ -->
<!-- PROCESS                                     -->
<!-- ============================================ -->
<section class="section">
  <div class="container">

    <div class="section-head center">
      <span class="eyebrow">Our process</span>
      <h2 class="section-title">How we <span class="gradient-text">deliver.</span></h2>
      <p class="section-sub">A transparent, proven process from start to finish.</p>
    </div>

    <div class="grid-4">
      <?php
      $steps = [
        ['01', 'Discover', 'Understand goals & constraints', 'bi-search',         'var(--primary)'],
        ['02', 'Design',   'UI system & prototype',          'bi-palette',        'var(--cyan)'],
        ['03', 'Build',    'Sprint-based with demos',        'bi-code-square',    'var(--green)'],
        ['04', 'Test',     'QA, security, performance',      'bi-bug',            'var(--yellow)'],
        ['05', 'Launch',   'Deploy, monitor, handover',      'bi-rocket-takeoff', 'var(--pink)'],
        ['06', 'Support',  'Ongoing care & iteration',       'bi-life-preserver', 'var(--primary)'],
      ];
      foreach ($steps as $i => $st): ?>
        <div class="bento-card reveal" data-delay="<?= $i * 60 ?>" style="min-height:auto;position:relative">
          <span style="position:absolute;top:1rem;right:1.2rem;font-family:var(--font-mono);font-size:1.3rem;font-weight:800;color:var(--border-strong);letter-spacing:-0.05em"><?= $st[0] ?></span>
          <div class="bento-icon" style="background:color-mix(in srgb, <?= $st[4] ?> 15%, transparent);color:<?= $st[4] ?>">
            <i class="bi <?= $st[3] ?>"></i>
          </div>
          <h3 style="font-size:1rem;margin:0"><?= $st[1] ?></h3>
          <p style="margin:0;font-size:0.85rem"><?= $st[2] ?></p>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ============================================ -->
<!-- TECH STACK                                  -->
<!-- ============================================ -->
<section class="section">
  <div class="container">

    <div class="section-head center">
      <span class="eyebrow">Technology</span>
      <h2 class="section-title">Tools we <span class="gradient-text">use.</span></h2>
      <p class="section-sub">The right tools for the job — not the trendiest.</p>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:0.6rem;justify-content:center;max-width:800px;margin:0 auto">
      <?php foreach (get_technologies() as $t): ?>
        <span class="chip" style="padding:0.6rem 1rem;font-size:0.85rem">
          <i class="bi <?= e($t['icon']) ?>" style="color:var(--primary)"></i>
          <?= e($t['name']) ?>
        </span>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ============================================ -->
<!-- TESTIMONIALS                                -->
<!-- ============================================ -->
<?php if (!empty($testimonials)): ?>
<section class="section">
  <div class="container">

    <div class="section-head center">
      <span class="eyebrow">Client feedback</span>
      <h2 class="section-title">What clients <span class="gradient-text">say.</span></h2>
    </div>

    <div class="grid-3">
      <?php foreach ($testimonials as $i => $t): ?>
        <div class="bento-card reveal" data-delay="<?= $i * 60 ?>" style="min-height:auto">
          <div style="display:flex;gap:0.2rem;color:var(--yellow);font-size:0.9rem;margin-bottom:0.6rem">
            <?= str_repeat('<i class="bi bi-star-fill"></i>', (int)$t['rating']) ?>
          </div>
          <p style="font-size:0.92rem;line-height:1.7;color:var(--text-2);margin:0 0 1rem;flex:1">
            "<?= e($t['review']) ?>"
          </p>
          <div style="display:flex;align-items:center;gap:0.7rem;padding-top:1rem;border-top:1px solid var(--border);margin-top:auto">
            <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--cyan));color:#fff;display:grid;place-items:center;font-weight:800;font-size:0.9rem;flex-shrink:0">
              <?= e(strtoupper(substr($t['name'], 0, 1))) ?>
            </div>
            <div>
              <strong style="display:block;font-size:0.88rem"><?= e($t['name']) ?></strong>
              <span style="font-size:0.74rem;color:var(--muted)"><?= e($t['designation']) ?>, <?= e($t['company']) ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>

<!-- ============================================ -->
<!-- FAQ                                         -->
<!-- ============================================ -->
<section class="section">
  <div class="container">
    <div class="narrow">

      <div class="section-head center">
        <span class="eyebrow">FAQ</span>
        <h2 class="section-title">Questions, <span class="gradient-text">answered.</span></h2>
      </div>

      <?php
      $faqs = [
          ['How long does this typically take?', 'Project timelines vary by scope. Small projects take 2–4 weeks, medium 4–10 weeks, and enterprise projects 3–6 months. We provide a detailed timeline in your proposal.'],
          ['What is the typical cost?', 'Cost depends on complexity and features. Small projects start around $1,500, medium projects $5,000–$25,000, and enterprise solutions from $25,000+. Use our calculator for an instant estimate.'],
          ['Do you offer ongoing support?', 'Yes. Every project includes 30 days of free bug-fix support. After that, monthly maintenance plans start at $200/month with 24/7 emergency options available.'],
          ['Will I own the source code?', 'Yes, upon full payment you own 100% of the source code, designs and assets. No vendor lock-in.'],
          ['Can you work with our existing team?', 'Absolutely. We can augment your team, work as an outsourced partner, or hand over complete documentation for your team to maintain.'],
      ];
      foreach ($faqs as $faq): ?>
        <details class="faq-item">
          <summary><?= e($faq[0]) ?></summary>
          <p><?= e($faq[1]) ?></p>
        </details>
      <?php endforeach; ?>

    </div>
  </div>
</section>

<!-- ============================================ -->
<!-- RELATED SERVICES                            -->
<!-- ============================================ -->
<?php if (!empty($related)): ?>
<section class="section">
  <div class="container">

    <div class="section-head" style="display:flex;justify-content:space-between;align-items:end;flex-wrap:wrap;gap:1rem;max-width:100%">
      <div>
        <span class="eyebrow">Related</span>
        <h2 class="section-title">You might also <span class="gradient-text">need.</span></h2>
      </div>
      <a href="<?= BASE_URL ?>services.php" class="btn btn-ghost">
        All Services <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    <div class="grid-3">
      <?php foreach ($related as $i => $rs):
        $rColor = $serviceColors[$rs['slug']] ?? 'var(--primary)';
      ?>
        <a href="<?= BASE_URL ?>service-detail.php?slug=<?= e($rs['slug']) ?>"
           class="service-card reveal"
           style="--card-color: <?= $rColor ?>"
           data-delay="<?= $i * 60 ?>">

          <div class="sc-icon">
            <i class="bi <?= e($rs['icon']) ?>"></i>
          </div>
          <h3><?= e($rs['title']) ?></h3>
          <p><?= e($rs['short_desc']) ?></p>
          <div class="sc-foot">
            <span class="link-arrow">Learn More <i class="bi bi-arrow-right"></i></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>

<!-- ============================================ -->
<!-- FINAL CTA                                   -->
<!-- ============================================ -->
<section class="section">
  <div class="container">
    <div class="cta-box">
      <span class="eyebrow">Get started</span>
      <h2>Ready to build something <span class="gradient-text">great?</span></h2>
      <p>Tell us about your project — we'll reply with a detailed proposal within 24 hours.</p>
      <div class="cta-actions">
        <a href="<?= BASE_URL ?>request-quote.php?service=<?= e($service['slug']) ?>" class="btn btn-primary btn-lg">
          <i class="bi bi-rocket-takeoff"></i> Request a Quote
        </a>
        <a href="<?= BASE_URL ?>book-consultation.php" class="btn btn-ghost btn-lg">
          <i class="bi bi-calendar-check"></i> Book a Call
        </a>
        <a href="<?= BASE_URL ?>calculator.php" class="btn btn-outline btn-lg">
          <i class="bi bi-calculator"></i> Get an Estimate
        </a>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>