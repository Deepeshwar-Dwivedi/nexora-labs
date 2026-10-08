<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

if (isset($pdo) && strpos($_SERVER['PHP_SELF'], '/admin/') === false && strpos($_SERVER['PHP_SELF'], '/client/') === false) {
    track_visit($pdo, basename($_SERVER['PHP_SELF']));
}

$currentPage = basename($_SERVER['PHP_SELF']);
$pageTitle   = $pageTitle ?? 'Nexora Labs';
$pageDesc    = $pageDesc  ?? 'We design, develop and scale digital products for ambitious businesses.';
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($pageTitle) ?> — Nexora Labs</title>
<meta name="description" content="<?= e($pageDesc) ?>">
<meta name="theme-color" content="#08090A">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
<script>
  (function() {
    try {
      var saved = localStorage.getItem('nexora-theme');
      var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      document.documentElement.setAttribute('data-theme', saved || (prefersDark ? 'dark' : 'light'));
    } catch (e) {}
  })();
</script>
</head>
<body>

<!-- Scroll Progress -->
<div class="scroll-progress" id="scrollProgress"></div>

<!-- Animated Background -->
<div class="bg-canvas" aria-hidden="true">
  <div class="bg-orb o1"></div>
  <div class="bg-orb o2"></div>
  <div class="bg-orb o3"></div>
  <div class="bg-grid"></div>
  <div class="bg-noise"></div>
</div>

<!-- HEADER -->
<header class="site-header">
  <div class="container nav-wrap">

    <a href="<?= BASE_URL ?>index.php" class="logo">
      <span class="logo-mark">N</span>
      <span class="logo-text">Nexora</span>
    </a>

    <nav class="main-nav" id="mainNav">

      <!-- Services Mega -->
      <div class="nav-item has-mega">
        <a href="<?= BASE_URL ?>services.php" class="nav-link <?= $currentPage==='services.php'?'active':'' ?>">
          Services <i class="bi bi-chevron-down chev"></i>
        </a>
        <div class="mega-menu">
          <div class="mega-grid">
            <div class="mega-section-title">Development</div>
            <a href="<?= BASE_URL ?>service-detail.php?slug=web-development" class="mega-item">
              <span class="mega-icon"><i class="bi bi-globe2"></i></span>
              <span class="mega-body">
                <span class="mega-title">Web Development</span>
                <span class="mega-desc">Business sites, e-commerce, custom CMS</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>service-detail.php?slug=software-development" class="mega-item i-cyan">
              <span class="mega-icon"><i class="bi bi-cpu"></i></span>
              <span class="mega-body">
                <span class="mega-title">Custom Software</span>
                <span class="mega-desc">ERP, CRM, HRMS, billing systems</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>service-detail.php?slug=app-development" class="mega-item i-green">
              <span class="mega-icon"><i class="bi bi-phone"></i></span>
              <span class="mega-body">
                <span class="mega-title">App Development</span>
                <span class="mega-desc">iOS, Android, PWA, SaaS platforms</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>service-detail.php?slug=ai-automation" class="mega-item i-pink">
              <span class="mega-icon"><i class="bi bi-robot"></i></span>
              <span class="mega-body">
                <span class="mega-title">AI &amp; Automation <span class="new-badge">New</span></span>
                <span class="mega-desc">AI integration, chatbots, workflows</span>
              </span>
            </a>

            <div class="mega-section-title">Design &amp; Growth</div>
            <a href="<?= BASE_URL ?>service-detail.php?slug=ui-ux-design" class="mega-item i-yellow">
              <span class="mega-icon"><i class="bi bi-palette"></i></span>
              <span class="mega-body">
                <span class="mega-title">UI/UX Design</span>
                <span class="mega-desc">Interfaces, prototypes, design systems</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>service-detail.php?slug=cloud-infra" class="mega-item">
              <span class="mega-icon"><i class="bi bi-cloud"></i></span>
              <span class="mega-body">
                <span class="mega-title">Cloud &amp; Infrastructure</span>
                <span class="mega-desc">Deployment, hosting, migration</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>service-detail.php?slug=digital-marketing" class="mega-item i-pink">
              <span class="mega-icon"><i class="bi bi-megaphone"></i></span>
              <span class="mega-body">
                <span class="mega-title">Digital Marketing</span>
                <span class="mega-desc">SEO, social, performance, content</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>service-detail.php?slug=maintenance" class="mega-item i-cyan">
              <span class="mega-icon"><i class="bi bi-tools"></i></span>
              <span class="mega-body">
                <span class="mega-title">Maintenance &amp; Support</span>
                <span class="mega-desc">Bug fixes, security, optimization</span>
              </span>
            </a>
          </div>
        </div>
      </div>

      <!-- Solutions Mega -->
      <div class="nav-item has-mega">
        <a href="<?= BASE_URL ?>services.php" class="nav-link">
          Solutions <i class="bi bi-chevron-down chev"></i>
        </a>
        <div class="mega-menu">
          <div class="mega-grid">
            <div class="mega-section-title">Ready-made Solutions</div>
            <a href="<?= BASE_URL ?>services.php" class="mega-item">
              <span class="mega-icon"><i class="bi bi-diagram-3"></i></span>
              <span class="mega-body">
                <span class="mega-title">School ERP</span>
                <span class="mega-desc">Attendance, fees, exams, parent portal</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>services.php" class="mega-item i-cyan">
              <span class="mega-icon"><i class="bi bi-people"></i></span>
              <span class="mega-body">
                <span class="mega-title">Sales CRM</span>
                <span class="mega-desc">Pipeline, leads, automation</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>services.php" class="mega-item i-green">
              <span class="mega-icon"><i class="bi bi-cart3"></i></span>
              <span class="mega-body">
                <span class="mega-title">E-commerce</span>
                <span class="mega-desc">Multi-vendor marketplace</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>services.php" class="mega-item i-yellow">
              <span class="mega-icon"><i class="bi bi-box-seam"></i></span>
              <span class="mega-body">
                <span class="mega-title">Inventory &amp; Billing</span>
                <span class="mega-desc">Stock, GST invoices, barcode</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>services.php" class="mega-item i-pink">
              <span class="mega-icon"><i class="bi bi-heart-pulse"></i></span>
              <span class="mega-body">
                <span class="mega-title">Hospital Management</span>
                <span class="mega-desc">Patients, appointments, billing</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>services.php" class="mega-item">
              <span class="mega-icon"><i class="bi bi-book"></i></span>
              <span class="mega-body">
                <span class="mega-title">Learning Management</span>
                <span class="mega-desc">Courses, quizzes, certificates</span>
              </span>
            </a>
          </div>
        </div>
      </div>

      <a href="<?= BASE_URL ?>portfolio.php" class="nav-link <?= $currentPage==='portfolio.php'?'active':'' ?>">Portfolio</a>
      <a href="<?= BASE_URL ?>blog.php" class="nav-link <?= $currentPage==='blog.php'?'active':'' ?>">Insights</a>

      <!-- Resources Mega -->
      <div class="nav-item has-mega">
        <a href="<?= BASE_URL ?>faq.php" class="nav-link">
          Resources <i class="bi bi-chevron-down chev"></i>
        </a>
        <div class="mega-menu" style="min-width:520px">
          <div class="mega-grid">
            <a href="<?= BASE_URL ?>calculator.php" class="mega-item">
              <span class="mega-icon"><i class="bi bi-calculator"></i></span>
              <span class="mega-body">
                <span class="mega-title">Cost Calculator</span>
                <span class="mega-desc">Get an instant estimate</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>faq.php" class="mega-item i-cyan">
              <span class="mega-icon"><i class="bi bi-question-circle"></i></span>
              <span class="mega-body">
                <span class="mega-title">FAQ</span>
                <span class="mega-desc">Common questions answered</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>blog.php" class="mega-item i-pink">
              <span class="mega-icon"><i class="bi bi-journal-text"></i></span>
              <span class="mega-body">
                <span class="mega-title">Insights</span>
                <span class="mega-desc">Latest articles &amp; guides</span>
              </span>
            </a>
            <a href="<?= BASE_URL ?>contact.php" class="mega-item i-green">
              <span class="mega-icon"><i class="bi bi-life-preserver"></i></span>
              <span class="mega-body">
                <span class="mega-title">Support</span>
                <span class="mega-desc">Contact our team</span>
              </span>
            </a>
          </div>
        </div>
      </div>

      <a href="<?= BASE_URL ?>contact.php" class="nav-link <?= $currentPage==='contact.php'?'active':'' ?>">Contact</a>
    </nav>

    <div class="nav-actions">

      <button class="theme-toggle" type="button" aria-label="Toggle theme">
        <i class="bi bi-sun-fill sun-icon"></i>
        <i class="bi bi-moon-stars-fill moon-icon"></i>
      </button>

      <?php if (is_logged_in() && is_admin()): ?>
        <a href="<?= BASE_URL ?>admin/dashboard.php" class="btn btn-ghost btn-sm">
          <i class="bi bi-speedometer2"></i><span> Dashboard</span>
        </a>
        <a href="<?= BASE_URL ?>admin/logout.php" class="btn btn-ghost btn-sm" aria-label="Logout">
          <i class="bi bi-box-arrow-right"></i>
        </a>
      <?php else: ?>
        <a href="<?= BASE_URL ?>admin/login.php" class="btn btn-ghost btn-sm">
          <i class="bi bi-shield-lock"></i><span> Login</span>
        </a>
        <a href="<?= BASE_URL ?>request-quote.php" class="btn btn-primary btn-sm">
          Get Started <i class="bi bi-arrow-right"></i>
        </a>
      <?php endif; ?>

      <button class="nav-toggle" id="navToggle" type="button" aria-label="Menu">
        <i class="bi bi-list"></i>
      </button>
    </div>

  </div>
</header>