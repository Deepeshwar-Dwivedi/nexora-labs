<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('portfolio.php');

$stmt = $pdo->prepare("SELECT * FROM portfolio WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$project = $stmt->fetch();
if (!$project) redirect('portfolio.php');

$relStmt = $pdo->prepare("SELECT * FROM portfolio WHERE category = ? AND id != ? LIMIT 3");
$relStmt->execute([$project['category'], $project['id']]);
$related = $relStmt->fetchAll();

if (count($related) < 3) {
    $moreStmt = $pdo->prepare("SELECT * FROM portfolio WHERE id != ? ORDER BY RAND() LIMIT 3");
    $moreStmt->execute([$project['id']]);
    $related = $moreStmt->fetchAll();
}

$catIcons = [
    'ERP' => 'bi-diagram-3', 'CRM' => 'bi-people',
    'E-commerce' => 'bi-cart3', 'Mobile Apps' => 'bi-phone',
    'Websites' => 'bi-globe2', 'Software' => 'bi-cpu',
    'AI' => 'bi-robot', 'Automation' => 'bi-gear',
];
$catColors = [
    'ERP' => 'var(--primary)', 'CRM' => 'var(--cyan)',
    'E-commerce' => 'var(--green)', 'Mobile Apps' => 'var(--pink)',
    'Websites' => 'var(--yellow)', 'Software' => 'var(--primary)',
    'AI' => 'var(--pink)', 'Automation' => 'var(--cyan)',
];
$catIcon  = $catIcons[$project['category']]  ?? 'bi-window-stack';
$catColor = $catColors[$project['category']] ?? 'var(--primary)';

$pageTitle = $project['title'];
$pageDesc  = $project['description'];

include 'includes/header.php';
?>

<!-- HERO -->
<section class="hero" style="padding-bottom:2rem">
  <div class="container">

    <nav class="breadcrumb" style="justify-content:flex-start;margin-bottom:2rem">
      <a href="<?= BASE_URL ?>">Home</a>
      <i class="bi bi-chevron-right"></i>
      <a href="<?= BASE_URL ?>portfolio.php">Portfolio</a>
      <i class="bi bi-chevron-right"></i>
      <span><?= e($project['title']) ?></span>
    </nav>

    <span class="badge badge-primary" style="margin-bottom:1rem">
      <i class="bi <?= $catIcon ?>"></i> <?= e($project['category']) ?>
    </span>

    <h1 style="font-size:clamp(2rem,4.5vw,3rem);margin-bottom:1rem;max-width:800px">
      <?= e($project['title']) ?>
    </h1>

    <p class="lead" style="margin-bottom:2rem;max-width:720px">
      <?= e($project['description']) ?>
    </p>

    <!-- Hero visual -->
    <div style="height:340px;border-radius:var(--radius-xl);background:linear-gradient(135deg, <?= $catColor ?>, var(--cyan));display:grid;place-items:center;position:relative;overflow:hidden;margin-bottom:2.5rem">
      <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.08) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.08) 1px,transparent 1px);background-size:34px 34px"></div>
      <i class="bi <?= $catIcon ?>" style="position:relative;z-index:2;font-size:7rem;color:rgba(255,255,255,0.95);filter:drop-shadow(0 12px 35px rgba(0,0,0,0.3))"></i>
    </div>

    <!-- Meta -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;margin-bottom:3rem">
      <div class="clean-card" style="padding:1.1rem 1.2rem">
        <div style="font-size:0.7rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);margin-bottom:0.4rem">Category</div>
        <div style="font-size:0.95rem;font-weight:700"><?= e($project['category']) ?></div>
      </div>
      <div class="clean-card" style="padding:1.1rem 1.2rem">
        <div style="font-size:0.7rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);margin-bottom:0.4rem">Tech Stack</div>
        <div style="font-size:0.82rem;font-weight:700;line-height:1.4"><?= e($project['technology']) ?></div>
      </div>
      <div class="clean-card" style="padding:1.1rem 1.2rem">
        <div style="font-size:0.7rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);margin-bottom:0.4rem">Status</div>
        <div style="font-size:0.95rem;font-weight:700;color:var(--green)">✓ Delivered</div>
      </div>
      <div class="clean-card" style="padding:1.1rem 1.2rem">
        <div style="font-size:0.7rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);margin-bottom:0.4rem">Year</div>
        <div style="font-size:0.95rem;font-weight:700"><?= date('Y', strtotime($project['created_at'])) ?></div>
      </div>
    </div>

  </div>
</section>

<!-- CONTENT -->
<section class="section" style="padding-top:0">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 300px;gap:3rem;align-items:start" class="pd-content">

      <!-- Main -->
      <div>
        <h2 style="font-size:1.35rem;font-weight:800;margin-bottom:1rem">Project overview</h2>
        <p style="font-size:1rem;line-height:1.8;color:var(--text-2);margin-bottom:1.2rem">
          <?= e($project['description']) ?>
        </p>
        <p style="font-size:1rem;line-height:1.8;color:var(--text-2);margin-bottom:1.2rem">
          Our client came to us with a clear challenge: they needed a modern, scalable solution that could handle real-world usage without breaking. The legacy approach was slowing them down — manual processes, disconnected tools, and no visibility into what was working.
        </p>

        <h2 style="font-size:1.35rem;font-weight:800;margin:2rem 0 1rem">The challenge</h2>
        <p style="font-size:1rem;line-height:1.8;color:var(--text-2);margin-bottom:1rem">
          Before starting, we conducted a thorough discovery phase to understand business goals, user needs and technical constraints. This revealed several key problems:
        </p>
        <ul style="list-style:none;padding:0;margin:1rem 0 1.5rem;display:flex;flex-direction:column;gap:0.6rem">
          <?php foreach ([
            'Fragmented workflows causing delays and errors',
            'No centralized data — reports took hours to build',
            'Poor mobile experience — losing users on smaller screens',
            'Security gaps flagged in the previous audit',
          ] as $item): ?>
            <li style="display:flex;gap:0.6rem;font-size:0.95rem;color:var(--text-2);line-height:1.55">
              <i class="bi bi-check-circle-fill" style="color:var(--green);flex-shrink:0;margin-top:0.15rem"></i>
              <?= e($item) ?>
            </li>
          <?php endforeach; ?>
        </ul>

        <h2 style="font-size:1.35rem;font-weight:800;margin:2rem 0 1rem">Our solution</h2>
        <p style="font-size:1rem;line-height:1.8;color:var(--text-2);margin-bottom:1.5rem">
          We designed and built a solution using <strong style="color:var(--text)"><?= e($project['technology']) ?></strong> — chosen for reliability, performance and long-term maintainability. The system was delivered in sprints with weekly demos so the client could see progress and give feedback early.
        </p>

        <!-- Results -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;margin:1.5rem 0 2rem">
          <?php
          $results = [
            ['65%',  'Faster Workflows',  'var(--primary)'],
            ['3x',   'Faster Reports',    'var(--cyan)'],
            ['+42%', 'User Engagement',   'var(--green)'],
            ['99.9%','Uptime',            'var(--yellow)'],
          ];
          foreach ($results as $r): ?>
            <div class="clean-card" style="text-align:center;padding:1.3rem">
              <strong style="display:block;font-size:1.8rem;font-weight:800;letter-spacing:-0.03em;color:<?= $r[2] ?>;line-height:1;margin-bottom:0.4rem"><?= $r[0] ?></strong>
              <span style="font-size:0.78rem;color:var(--muted);font-weight:600"><?= $r[1] ?></span>
            </div>
          <?php endforeach; ?>
        </div>

        <h2 style="font-size:1.35rem;font-weight:800;margin:2rem 0 1rem">What we delivered</h2>
        <ul style="list-style:none;padding:0;margin:1rem 0 1.5rem;display:flex;flex-direction:column;gap:0.6rem">
          <?php foreach ([
            'Full discovery and requirements documentation',
            'UI/UX design system and clickable prototype',
            'Complete development with weekly demos',
            'QA, security review and performance testing',
            'Deployment, monitoring and team training',
            '30 days post-launch support',
          ] as $item): ?>
            <li style="display:flex;gap:0.6rem;font-size:0.95rem;color:var(--text-2);line-height:1.55">
              <i class="bi bi-check-circle-fill" style="color:var(--green);flex-shrink:0;margin-top:0.15rem"></i>
              <?= e($item) ?>
            </li>
          <?php endforeach; ?>
        </ul>

        <h2 style="font-size:1.35rem;font-weight:800;margin:2rem 0 1rem">Client feedback</h2>
        <blockquote style="border-left:4px solid var(--primary);padding:1rem 1.4rem;background:var(--primary-soft);border-radius:0 10px 10px 0;font-style:italic;color:var(--text-2);margin:1.5rem 0;font-size:1rem;line-height:1.7">
          "The Nexora team took ownership from day one. They shipped on time, communicated clearly, and the solution has been rock-solid since launch. We're already planning phase two."
        </blockquote>
      </div>

      <!-- Sidebar -->
      <aside style="position:sticky;top:100px;display:flex;flex-direction:column;gap:1rem" class="pd-sidebar">

        <div class="clean-card" style="padding:1.4rem">
          <h4 style="font-size:0.78rem;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:0.9rem">Technologies used</h4>
          <div style="display:flex;flex-wrap:wrap;gap:0.4rem">
            <?php foreach (array_map('trim', explode(',', $project['technology'])) as $tech): ?>
              <span class="chip"><?= e($tech) ?></span>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="clean-card" style="padding:1.4rem">
          <h4 style="font-size:0.78rem;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:0.9rem">Start your project</h4>
          <p style="font-size:0.86rem;color:var(--muted);line-height:1.6;margin-bottom:1rem">
            Have a similar challenge? Let's talk about what we can build together.
          </p>
          <a href="<?= BASE_URL ?>request-quote.php" class="btn btn-primary" style="width:100%">
            <i class="bi bi-chat-dots"></i> Get a Quote
          </a>
        </div>

        <div class="clean-card" style="padding:1.4rem">
          <h4 style="font-size:0.78rem;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:0.9rem">Quick info</h4>
          <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.7rem">
            <li style="font-size:0.85rem;color:var(--text-2);display:flex;gap:0.5rem">
              <i class="bi bi-calendar3" style="color:var(--primary)"></i>
              <?= date('F Y', strtotime($project['created_at'])) ?>
            </li>
            <li style="font-size:0.85rem;color:var(--text-2);display:flex;gap:0.5rem">
              <i class="bi bi-tag-fill" style="color:var(--primary)"></i>
              <?= e($project['category']) ?>
            </li>
            <li style="font-size:0.85rem;color:var(--text-2);display:flex;gap:0.5rem">
              <i class="bi bi-check-circle-fill" style="color:var(--green)"></i>
              Delivered
            </li>
          </ul>
        </div>

      </aside>

    </div>

    <!-- Related -->
    <?php if (!empty($related)): ?>
      <div style="margin-top:4rem;padding-top:3rem;border-top:1px solid var(--border)">
        <h3 style="font-size:1.3rem;font-weight:800;margin-bottom:1.5rem">More projects</h3>
        <div class="grid-3">
          <?php foreach ($related as $r):
            $rColor = $catColors[$r['category']] ?? 'var(--primary)';
          ?>
            <a href="<?= BASE_URL ?>portfolio-detail.php?id=<?= (int)$r['id'] ?>"
               class="portfolio-card"
               style="--card-color: <?= $rColor ?>">
              <div class="pc-header">
                <span class="pc-cat"><?= e($r['category']) ?></span>
                <i class="bi <?= $catIcons[$r['category']] ?? 'bi-window-stack' ?>"></i>
              </div>
              <div class="pc-body">
                <h3><?= e($r['title']) ?></h3>
                <p><?= e($r['description']) ?></p>
                <span class="pc-tech"><i class="bi bi-code-slash"></i> <?= e($r['technology']) ?></span>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<style>
@media (max-width: 1000px) {
  .pd-content { grid-template-columns: 1fr !important; }
  .pd-sidebar { position: static !important; }
}
</style>

<?php include 'includes/footer.php'; ?>