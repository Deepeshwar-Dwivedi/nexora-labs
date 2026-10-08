<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$services     = get_services();
$testimonials = $pdo->query("SELECT * FROM testimonials LIMIT 6")->fetchAll();
$portfolio    = $pdo->query("SELECT * FROM portfolio ORDER BY id DESC LIMIT 6")->fetchAll();
$blogs        = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC LIMIT 3")->fetchAll();

$pageTitle = 'Software & Digital Services';
$pageDesc  = 'We design, develop and scale websites, software, applications and automation systems for ambitious businesses.';

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

// Category colors for portfolio
$catColors = [
    'ERP'         => 'var(--primary)',
    'CRM'         => 'var(--cyan)',
    'E-commerce'  => 'var(--green)',
    'Mobile Apps' => 'var(--pink)',
    'Websites'    => 'var(--yellow)',
    'Software'    => 'var(--primary)',
    'AI'          => 'var(--pink)',
];
?>

<!-- ============================================ -->
<!-- HERO                                        -->
<!-- ============================================ -->
<section class="hero">
  <div class="container">
    <div class="hero-grid">

      <div class="hero-content">
        <a href="<?= BASE_URL ?>blog.php" class="hero-pill">
          <span class="pill-new">New</span>
          AI &amp; Automation services now live
          <i class="bi bi-arrow-right arrow"></i>
        </a>

        <h1>
          Build digital products that <span class="gradient-text">move your business forward.</span>
        </h1>

        <p class="lead">
          We design, develop and scale websites, software, applications and automation
          systems for ambitious businesses worldwide.
        </p>

        <div class="hero-cta">
          <a href="<?= BASE_URL ?>start-project.php" class="btn btn-primary btn-lg">
            Start Your Project <i class="bi bi-arrow-right"></i>
          </a>
          <a href="<?= BASE_URL ?>services.php" class="btn btn-ghost btn-lg">
            <i class="bi bi-grid-3x3-gap"></i> Explore Services
          </a>
        </div>

        <div class="hero-stats">
          <div class="hero-stat">
            <strong data-count="500">0</strong>
            <span>Projects Delivered</span>
          </div>
          <div class="hero-stat">
            <strong data-count="50">0</strong>
            <span>Technologies</span>
          </div>
          <div class="hero-stat">
            <strong data-count="98">0</strong>
            <span>% Satisfaction</span>
          </div>
          <div class="hero-stat">
            <strong>24/7</strong>
            <span>Support</span>
          </div>
        </div>
      </div>

      <div class="hero-visual">
        <!-- Floating cards -->
        <div class="float-card fc-1">
          <i class="bi bi-code-slash"></i>
          <div>
            <strong>Clean Code</strong>
            <span>PHP 8 • MySQL</span>
          </div>
        </div>
        <div class="float-card fc-2">
          <i class="bi bi-graph-up-arrow"></i>
          <div>
            <strong>+38% Conversion</strong>
            <span>Avg. client lift</span>
          </div>
        </div>
        <div class="float-card fc-3">
          <i class="bi bi-shield-check"></i>
          <div>
            <strong>Secure</strong>
            <span>OWASP aligned</span>
          </div>
        </div>
        <div class="float-card fc-4">
          <i class="bi bi-lightning-charge-fill"></i>
          <div>
            <strong>Fast Deploy</strong>
            <span>CI/CD ready</span>
          </div>
        </div>

        <!-- Browser mockup -->
        <div class="browser-mock">
          <div class="bm-bar">
            <span></span><span></span><span></span>
          </div>
          <div class="bm-body">
            <div class="bm-line w70"></div>
            <div class="bm-line w45"></div>
            <div class="bm-chart">
              <i style="height:40%"></i>
              <i style="height:65%"></i>
              <i style="height:50%"></i>
              <i style="height:85%"></i>
              <i style="height:70%"></i>
              <i style="height:95%"></i>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ============================================ -->
<!-- TRUST STRIP                                 -->
<!-- ============================================ -->
<section class="trust-strip">
  <div class="container">
    <p class="trust-label">Trusted by businesses, startups and organizations</p>
    <div class="trust-logos">
      <?php
      $logos = [
        ['bi-hexagon-fill', 'Acme'],
        ['bi-triangle-fill', 'Vertex'],
        ['bi-circle-fill', 'Orbit'],
        ['bi-square-fill', 'Prism'],
        ['bi-diamond-fill', 'Apex'],
        ['bi-star-fill', 'Nova'],
      ];
      foreach ($logos as $logo): ?>
        <div class="trust-logo">
          <i class="bi <?= $logo[0] ?>"></i>
          <span><?= $logo[1] ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================ -->
<!-- BENTO GRID — CORE CAPABILITIES              -->
<!-- ============================================ -->
<section class="section">
  <div class="container">

    <div class="section-head center">
      <span class="eyebrow">What we do</span>
      <h2 class="section-title">
        A complete digital capability, <span class="gradient-text">under one roof.</span>
      </h2>
      <p class="section-sub">
        From a landing page to a full ERP platform — one accountable team, one standard of quality.
      </p>
    </div>

    <div class="bento-grid">

      <!-- Big card -->
      <div class="bento-card span-2 reveal" data-delay="0">
        <div class="bento-icon"><i class="bi bi-cpu"></i></div>
        <h3>Custom Software Development</h3>
        <p>
          We build ERPs, CRMs, HRMS, billing systems and complete business platforms
          tailored to how you actually work — not how a template thinks you should.
        </p>
        <div class="bento-visual">
          <div class="mini-bars">
            <i style="height:35%"></i>
            <i style="height:55%"></i>
            <i style="height:75%"></i>
            <i style="height:45%"></i>
            <i style="height:85%"></i>
            <i style="height:65%"></i>
            <i style="height:95%"></i>
            <i style="height:70%"></i>
          </div>
        </div>
      </div>

      <div class="bento-card c-pink reveal" data-delay="60">
        <div class="bento-icon"><i class="bi bi-robot"></i></div>
        <h3>AI &amp; Automation</h3>
        <p>Chatbots, workflow automation, document AI — practical solutions that save hours daily.</p>
      </div>

      <div class="bento-card c-cyan reveal" data-delay="120">
        <div class="bento-icon"><i class="bi bi-globe2"></i></div>
        <h3>Web Development</h3>
        <p>Business sites, e-commerce, custom CMS — fast, secure, SEO-ready.</p>
      </div>

      <div class="bento-card c-green reveal" data-delay="180">
        <div class="bento-icon"><i class="bi bi-phone"></i></div>
        <h3>Mobile Apps</h3>
        <p>Native iOS &amp; Android, PWAs, cross-platform with Flutter and React Native.</p>
      </div>

      <div class="bento-card c-yellow reveal" data-delay="240">
        <div class="bento-icon"><i class="bi bi-palette"></i></div>
        <h3>UI/UX Design</h3>
        <p>Interfaces users love — from wireframes to full design systems.</p>
      </div>

    </div>

  </div>
</section>

<!-- ============================================ -->
<!-- SERVICES SHOWCASE                           -->
<!-- ============================================ -->
<section class="section">
  <div class="container">

    <div class="section-head" style="display:flex;justify-content:space-between;align-items:end;flex-wrap:wrap;gap:1rem;max-width:100%">
      <div style="max-width:600px">
        <span class="eyebrow">Services</span>
        <h2 class="section-title">Everything you need to <span class="gradient-text">go digital.</span></h2>
        <p class="section-sub">Choose from our full-service technology stack — engineered end-to-end.</p>
      </div>
      <a href="<?= BASE_URL ?>services.php" class="btn btn-ghost">
        All Services <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    <div class="grid-3">
      <?php foreach (array_slice($services, 0, 6) as $i => $s):
        $color = $serviceColors[$s['slug']] ?? 'var(--primary)';
      ?>
        <a href="<?= BASE_URL ?>service-detail.php?slug=<?= e($s['slug']) ?>"
           class="service-card reveal"
           style="--card-color: <?= $color ?>"
           data-delay="<?= $i * 60 ?>">

          <div class="sc-icon">
            <i class="bi <?= e($s['icon']) ?>"></i>
          </div>

          <h3><?= e($s['title']) ?></h3>
          <p><?= e($s['short_desc']) ?></p>

          <ul class="sc-features">
            <?php foreach (array_slice(feature_list($s['features']), 0, 3) as $f): ?>
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

  </div>
</section>

<!-- ============================================ -->
<!-- WHY US — STATS                              -->
<!-- ============================================ -->
<section class="section">
  <div class="container">

    <div class="section-head center">
      <span class="eyebrow">Why choose us</span>
      <h2 class="section-title">Engineers first, <span class="gradient-text">agency second.</span></h2>
      <p class="section-sub">
        No account-manager telephone game. You work directly with the people building your product.
      </p>
    </div>

    <div class="grid-4">
      <?php
      $stats = [
        ['bi-people-fill',      '12',   'Years Experience',  'var(--primary)'],
        ['bi-box-seam-fill',    '500',  'Projects Shipped',  'var(--cyan)'],
        ['bi-globe',            '11',   'Industries Served', 'var(--green)'],
        ['bi-star-fill',        '4.9',  'Avg. Client Rating','var(--yellow)'],
      ];
      foreach ($stats as $i => $st): ?>
        <div class="stat-card reveal" data-delay="<?= $i * 60 ?>">
          <div class="sc-icon" style="background:color-mix(in srgb, <?= $st[3] ?> 15%, transparent);color:<?= $st[3] ?>">
            <i class="bi <?= $st[0] ?>"></i>
          </div>
          <strong data-count="<?= is_numeric($st[1]) ? $st[1] : '' ?>">
            <?= $st[1] ?><?= $st[1] === '4.9' ? '' : (is_numeric($st[1]) ? '+' : '') ?>
          </strong>
          <span class="stat-label"><?= $st[2] ?></span>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ============================================ -->
<!-- PROCESS TIMELINE                            -->
<!-- ============================================ -->
<section class="section">
  <div class="container">

    <div class="section-head center">
      <span class="eyebrow">How we work</span>
      <h2 class="section-title">A process with <span class="gradient-text">no surprises.</span></h2>
      <p class="section-sub">From first call to final deployment — transparent, on time.</p>
    </div>

    <div class="grid-4">
      <?php
      $steps = [
        ['01', 'Discover', 'Map your goals, users and constraints.', 'bi-search',         'var(--primary)'],
        ['02', 'Design',   'Wireframes, UI system, prototype.',       'bi-palette',        'var(--cyan)'],
        ['03', 'Build',    'Sprint-based dev with weekly demos.',     'bi-code-square',    'var(--green)'],
        ['04', 'Launch',   'Deploy, monitor, hand over docs.',        'bi-rocket-takeoff', 'var(--yellow)'],
      ];
      foreach ($steps as $i => $st): ?>
        <div class="bento-card reveal" data-delay="<?= $i * 80 ?>" style="min-height:auto;text-align:left;position:relative">
          <span style="position:absolute;top:1.2rem;right:1.4rem;font-family:var(--font-mono);font-size:1.4rem;font-weight:800;color:var(--border-strong);letter-spacing:-0.05em"><?= $st[0] ?></span>
          <div class="bento-icon" style="background:color-mix(in srgb, <?= $st[4] ?> 15%, transparent);color:<?= $st[4] ?>">
            <i class="bi <?= $st[3] ?>"></i>
          </div>
          <h3><?= $st[1] ?></h3>
          <p><?= $st[2] ?></p>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ============================================ -->
<!-- PORTFOLIO PREVIEW                           -->
<!-- ============================================ -->
<?php if (!empty($portfolio)): ?>
<section class="section">
  <div class="container">

    <div class="section-head" style="display:flex;justify-content:space-between;align-items:end;flex-wrap:wrap;gap:1rem;max-width:100%">
      <div style="max-width:600px">
        <span class="eyebrow">Portfolio</span>
        <h2 class="section-title">Selected <span class="gradient-text">work.</span></h2>
        <p class="section-sub">Real projects, real results — built with modern technology.</p>
      </div>
      <a href="<?= BASE_URL ?>portfolio.php" class="btn btn-ghost">
        All Projects <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    <div class="grid-3">
      <?php foreach ($portfolio as $i => $p):
        $color = $catColors[$p['category']] ?? 'var(--primary)';
      ?>
        <a href="<?= BASE_URL ?>portfolio-detail.php?id=<?= (int)$p['id'] ?>"
           class="portfolio-card reveal"
           style="--card-color: <?= $color ?>"
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

  </div>
</section>
<?php endif; ?>

<!-- ============================================ -->
<!-- CALCULATOR CTA                              -->
<!-- ============================================ -->
<section class="section-sm">
  <div class="container">
    <div class="cta-box">
      <span class="eyebrow">
        <i class="bi bi-calculator"></i> Instant Estimate
      </span>
      <h2>Not sure what your project costs?</h2>
      <p>Answer 4 quick questions and get a realistic budget range in under 60 seconds.</p>
      <div class="cta-actions">
        <a href="<?= BASE_URL ?>calculator.php" class="btn btn-primary btn-lg">
          <i class="bi bi-calculator-fill"></i> Calculate Project Cost
        </a>
        <a href="<?= BASE_URL ?>book-consultation.php" class="btn btn-ghost btn-lg">
          <i class="bi bi-calendar-check"></i> Book a Free Call
        </a>
      </div>
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
      <h2 class="section-title">What partners <span class="gradient-text">say about us.</span></h2>
    </div>

    <div class="grid-3">
      <?php foreach (array_slice($testimonials, 0, 3) as $i => $t): ?>
        <div class="bento-card reveal" data-delay="<?= $i * 80 ?>" style="min-height:auto">
          <div style="display:flex;gap:0.2rem;color:var(--yellow);font-size:0.95rem;margin-bottom:0.5rem">
            <?= str_repeat('<i class="bi bi-star-fill"></i>', (int)$t['rating']) ?>
          </div>
          <p style="font-size:0.95rem;line-height:1.7;color:var(--text-2);margin:0 0 1.2rem;flex:1">
            "<?= e($t['review']) ?>"
          </p>
          <div style="display:flex;align-items:center;gap:0.8rem;padding-top:1rem;border-top:1px solid var(--border);margin-top:auto">
            <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--cyan));color:#fff;display:grid;place-items:center;font-weight:800;font-size:1rem;flex-shrink:0">
              <?= e(strtoupper(substr($t['name'], 0, 1))) ?>
            </div>
            <div>
              <strong style="display:block;font-size:0.9rem"><?= e($t['name']) ?></strong>
              <span style="font-size:0.76rem;color:var(--muted)"><?= e($t['designation']) ?><?= $t['company'] ? ', ' . e($t['company']) : '' ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>

<!-- ============================================ -->
<!-- BLOG PREVIEW                                -->
<!-- ============================================ -->
<?php if (!empty($blogs)): ?>
<section class="section">
  <div class="container">

    <div class="section-head" style="display:flex;justify-content:space-between;align-items:end;flex-wrap:wrap;gap:1rem;max-width:100%">
      <div style="max-width:600px">
        <span class="eyebrow">Insights</span>
        <h2 class="section-title">Latest <span class="gradient-text">thinking.</span></h2>
        <p class="section-sub">Practical advice on software, AI and digital transformation.</p>
      </div>
      <a href="<?= BASE_URL ?>blog.php" class="btn btn-ghost">
        All Articles <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    <div class="grid-3">
      <?php foreach ($blogs as $i => $b):
        $catColor = [
          'Software'               => 'var(--primary)',
          'Business'               => 'var(--cyan)',
          'AI'                     => 'var(--pink)',
          'Web Development'        => 'var(--green)',
          'Technology'             => 'var(--purple, var(--primary))',
          'Digital Transformation' => 'var(--yellow)',
        ][$b['category']] ?? 'var(--primary)';
      ?>
        <a href="<?= BASE_URL ?>blog-detail.php?slug=<?= e($b['slug']) ?>"
           class="blog-card reveal"
           data-delay="<?= $i * 60 ?>">

          <span class="blog-cat" style="background:color-mix(in srgb, <?= $catColor ?> 15%, transparent);color:<?= $catColor ?>">
            <i class="bi <?= blog_cat_icon($b['category']) ?>"></i>
            <?= e($b['category']) ?>
          </span>

          <h3><?= e($b['title']) ?></h3>
          <p><?= e($b['excerpt']) ?></p>

          <div class="blog-meta">
            <span><i class="bi bi-person-circle"></i> <?= e($b['author']) ?></span>
            <span><i class="bi bi-clock"></i> <?= (int)$b['reading_time'] ?> min read</span>
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
      <h2>Have an idea? <span class="gradient-text">Let's build it.</span></h2>
      <p>
        Tell us what you need. You'll get a real human reply within one business day — 
        no sales pitch, just honest advice.
      </p>
      <div class="cta-actions">
        <a href="<?= BASE_URL ?>start-project.php" class="btn btn-primary btn-lg">
          <i class="bi bi-rocket-takeoff"></i> Start a Project
        </a>
        <a href="<?= BASE_URL ?>book-consultation.php" class="btn btn-ghost btn-lg">
          <i class="bi bi-calendar-check"></i> Book a Free Call
        </a>
        <a href="<?= BASE_URL ?>contact.php" class="btn btn-outline btn-lg">
          <i class="bi bi-chat-dots"></i> Contact Us
        </a>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>