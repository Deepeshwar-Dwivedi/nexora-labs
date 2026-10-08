<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
if (empty($slug)) redirect('blog.php');

$stmt = $pdo->prepare("SELECT * FROM blogs WHERE slug = ? LIMIT 1");
$stmt->execute([$slug]);
$post = $stmt->fetch();
if (!$post) redirect('blog.php');

$relStmt = $pdo->prepare("SELECT * FROM blogs WHERE category = ? AND id != ? ORDER BY created_at DESC LIMIT 3");
$relStmt->execute([$post['category'], $post['id']]);
$related = $relStmt->fetchAll();
if (count($related) < 3) {
    $moreStmt = $pdo->prepare("SELECT * FROM blogs WHERE id != ? ORDER BY created_at DESC LIMIT 3");
    $moreStmt->execute([$post['id']]);
    $related = $moreStmt->fetchAll();
}

$blogCatColors = [
    'Software' => 'var(--primary)', 'Business' => 'var(--cyan)',
    'AI' => 'var(--pink)', 'Web Development' => 'var(--green)',
    'Technology' => 'var(--primary)', 'Digital Transformation' => 'var(--yellow)',
];
$catColor = $blogCatColors[$post['category']] ?? 'var(--primary)';
$catIcon  = blog_cat_icon($post['category']);

$pageTitle = $post['title'];
$pageDesc  = $post['excerpt'];

include 'includes/header.php';
?>

<style>
/* ==============================================
   BLOG DETAIL — PREMIUM READABILITY
   ============================================== */

/* Reading progress bar */
.reading-progress {
  position: fixed;
  top: 68px;
  left: 0;
  height: 3px;
  width: 0%;
  background: linear-gradient(90deg, var(--primary), var(--cyan));
  z-index: 999;
  transition: width 0.1s linear;
  box-shadow: 0 0 12px var(--primary-glow);
}

/* Hero */
.bd-hero {
  padding: 3.5rem 0 2rem;
}

.bd-hero h1 {
  font-size: clamp(1.9rem, 4.2vw, 2.9rem);
  font-weight: 800;
  letter-spacing: -0.03em;
  line-height: 1.15;
  margin: 1rem 0 1.5rem;
  max-width: 900px;
}

.bd-hero-visual {
  height: 340px;
  border-radius: var(--radius-xl);
  background: linear-gradient(135deg, <?= $catColor ?>, var(--cyan));
  display: grid;
  place-items: center;
  position: relative;
  overflow: hidden;
  margin: 2.5rem 0 3rem;
}
.bd-hero-visual::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px);
  background-size: 30px 30px;
}
.bd-hero-visual > i {
  position: relative;
  z-index: 2;
  font-size: 6rem;
  color: rgba(255,255,255,0.95);
  filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));
}

/* Article layout */
.bd-layout {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 4rem;
  align-items: start;
}

/* ==============================================
   ARTICLE CONTENT — MAIN READING AREA
   ============================================== */
.article-content {
  max-width: 700px;
  font-size: 1.1rem;
  line-height: 1.85;
  color: var(--text-2);
  letter-spacing: -0.003em;
  font-feature-settings: 'kern', 'liga', 'calt';
}

/* Paragraphs */
.article-content p {
  margin: 0 0 1.75rem;
  line-height: 1.85;
  color: var(--text-2);
}
.article-content p:last-child { margin-bottom: 0; }

/* First paragraph — editorial intro */
.article-content > p:first-of-type {
  font-size: 1.22rem;
  line-height: 1.75;
  color: var(--text);
  font-weight: 450;
  margin-bottom: 2rem;
}

/* Headings */
.article-content h2 {
  font-size: 1.75rem;
  font-weight: 800;
  letter-spacing: -0.025em;
  line-height: 1.25;
  color: var(--text);
  margin: 3.5rem 0 1.5rem;
  position: relative;
  padding-top: 0.5rem;
}
.article-content h2:first-child { margin-top: 0; }

.article-content h3 {
  font-size: 1.35rem;
  font-weight: 700;
  letter-spacing: -0.02em;
  line-height: 1.3;
  color: var(--text);
  margin: 2.5rem 0 1rem;
}

.article-content h4 {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--text);
  margin: 2rem 0 0.75rem;
  letter-spacing: -0.01em;
}

/* Lists */
.article-content ul,
.article-content ol {
  margin: 0 0 1.75rem;
  padding-left: 1.75rem;
}
.article-content ul li,
.article-content ol li {
  margin-bottom: 0.85rem;
  padding-left: 0.35rem;
  line-height: 1.75;
}
.article-content ul li:last-child,
.article-content ol li:last-child {
  margin-bottom: 0;
}
.article-content ul li::marker {
  color: var(--primary);
  font-size: 1.1em;
}
.article-content ol li::marker {
  color: var(--primary);
  font-weight: 700;
}

/* Strong & emphasis */
.article-content strong {
  color: var(--text);
  font-weight: 700;
}
.article-content em {
  color: var(--text);
  font-style: italic;
}

/* Links */
.article-content a {
  color: var(--primary);
  font-weight: 600;
  text-decoration: underline;
  text-decoration-color: color-mix(in srgb, var(--primary) 35%, transparent);
  text-decoration-thickness: 2px;
  text-underline-offset: 4px;
  transition: all 0.2s;
}
.article-content a:hover {
  text-decoration-color: var(--primary);
  color: var(--primary-2);
}

/* Blockquote — editorial */
.article-content blockquote {
  margin: 2.5rem 0;
  padding: 1.75rem 2rem 1.75rem 2.25rem;
  background: linear-gradient(135deg, var(--primary-soft), color-mix(in srgb, var(--cyan) 5%, transparent));
  border-left: 4px solid var(--primary);
  border-radius: 0 16px 16px 0;
  font-size: 1.18rem;
  line-height: 1.7;
  font-style: italic;
  color: var(--text);
  position: relative;
  letter-spacing: -0.005em;
}
.article-content blockquote::before {
  content: '"';
  position: absolute;
  top: 0.5rem;
  left: 0.75rem;
  font-size: 4rem;
  color: var(--primary);
  opacity: 0.15;
  font-family: Georgia, 'Times New Roman', serif;
  line-height: 1;
  font-weight: 900;
}
.article-content blockquote p {
  margin: 0;
  position: relative;
  z-index: 1;
}

/* Inline code */
.article-content code {
  font-family: var(--font-mono);
  background: var(--surface-2);
  color: var(--primary);
  padding: 0.2em 0.5em;
  border-radius: 6px;
  font-size: 0.85em;
  border: 1px solid var(--border);
  font-weight: 500;
}

/* Code blocks */
.article-content pre {
  background: var(--surface-2);
  border: 1px solid var(--border);
  border-radius: 14px;
  padding: 1.5rem 1.75rem;
  overflow-x: auto;
  margin: 2rem 0;
  font-family: var(--font-mono);
  font-size: 0.88rem;
  line-height: 1.7;
  position: relative;
}
.article-content pre code {
  background: none;
  border: none;
  padding: 0;
  color: var(--text-2);
  font-size: inherit;
}

/* Images */
.article-content img {
  border-radius: 16px;
  margin: 2.5rem 0;
  width: 100%;
  border: 1px solid var(--border);
}

/* Horizontal rule */
.article-content hr {
  border: none;
  height: 1px;
  background: var(--border);
  margin: 3rem 0;
}

/* ==============================================
   ARTICLE META
   ============================================== */
.article-meta {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  color: var(--muted);
  font-size: 0.88rem;
  flex-wrap: wrap;
  padding: 1.5rem 0;
  border-top: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
  margin-bottom: 3rem;
}
.article-meta span {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}
.article-meta i {
  color: var(--primary);
  font-size: 0.95rem;
}

/* ==============================================
   SHARE
   ============================================== */
.bd-share {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  margin-top: 3.5rem;
  padding-top: 2rem;
  border-top: 1px solid var(--border);
  flex-wrap: wrap;
}
.bd-share-label {
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--muted);
  margin-right: 0.4rem;
}
.bd-share a {
  width: 42px;
  height: 42px;
  border-radius: 10px;
  background: var(--surface-2);
  border: 1px solid var(--border);
  display: grid;
  place-items: center;
  color: var(--text-2);
  font-size: 1rem;
  transition: all 0.25s;
}
.bd-share a:hover {
  background: var(--primary);
  color: #fff;
  border-color: var(--primary);
  transform: translateY(-2px);
}

/* ==============================================
   AUTHOR BOX
   ============================================== */
.bd-author {
  display: flex;
  gap: 1.2rem;
  padding: 1.75rem;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  margin-top: 2.5rem;
  align-items: center;
}
.bd-author-avatar {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: linear-gradient(135deg, <?= $catColor ?>, var(--cyan));
  display: grid;
  place-items: center;
  color: #fff;
  font-size: 1.6rem;
  font-weight: 800;
  flex-shrink: 0;
}
.bd-author h5 {
  font-size: 1rem;
  font-weight: 800;
  margin-bottom: 0.3rem;
  color: var(--text);
}
.bd-author p {
  font-size: 0.88rem;
  color: var(--muted);
  line-height: 1.6;
  margin: 0;
}

/* ==============================================
   SIDEBAR
   ============================================== */
.bd-sidebar {
  position: sticky;
  top: 100px;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.sidebar-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 14px;
  padding: 1.4rem;
}
.sidebar-card h4 {
  font-size: 0.78rem;
  font-weight: 800;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 1rem;
}
.sidebar-card ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.7rem;
}
.sidebar-card ul li a {
  font-size: 0.88rem;
  color: var(--text-2);
  display: flex;
  gap: 0.5rem;
  line-height: 1.45;
  transition: color 0.2s;
}
.sidebar-card ul li a:hover { color: var(--primary); }
.sidebar-card ul li a i {
  color: var(--primary);
  font-size: 0.75rem;
  margin-top: 0.45rem;
  flex-shrink: 0;
}

/* ==============================================
   RELATED
   ============================================== */
.bd-related {
  margin-top: 5rem;
  padding-top: 3rem;
  border-top: 1px solid var(--border);
}
.bd-related h3 {
  font-size: 1.3rem;
  font-weight: 800;
  margin-bottom: 1.5rem;
  letter-spacing: -0.02em;
}

/* ==============================================
   RESPONSIVE
   ============================================== */
@media (max-width: 1000px) {
  .bd-layout {
    grid-template-columns: 1fr;
    gap: 3rem;
  }
  .bd-sidebar {
    position: static;
  }
  .article-content {
    max-width: 100%;
  }
}

@media (max-width: 640px) {
  .bd-hero { padding: 2rem 0 1rem; }
  .bd-hero-visual { height: 220px; margin: 1.5rem 0 2rem; }
  .bd-hero-visual > i { font-size: 4rem; }

  .article-content {
    font-size: 1.02rem;
    line-height: 1.75;
  }
  .article-content > p:first-of-type {
    font-size: 1.1rem;
    line-height: 1.7;
  }
  .article-content h2 {
    font-size: 1.4rem;
    margin: 2.5rem 0 1.2rem;
  }
  .article-content h3 {
    font-size: 1.15rem;
    margin: 2rem 0 0.9rem;
  }
  .article-content p { margin-bottom: 1.5rem; }
  .article-content blockquote {
    padding: 1.25rem 1.4rem;
    font-size: 1.02rem;
    margin: 2rem 0;
  }
  .bd-author {
    flex-direction: column;
    text-align: center;
  }
}
</style>

<!-- Reading Progress Bar -->
<div class="reading-progress" id="readingProgress"></div>

<!-- ============================================ -->
<!-- HERO                                        -->
<!-- ============================================ -->
<section class="bd-hero">
  <div class="container">

    <nav class="breadcrumb" style="justify-content:flex-start;margin-bottom:2rem">
      <a href="<?= BASE_URL ?>">Home</a>
      <i class="bi bi-chevron-right"></i>
      <a href="<?= BASE_URL ?>blog.php">Insights</a>
      <i class="bi bi-chevron-right"></i>
      <span><?= e($post['category']) ?></span>
    </nav>

    <span class="badge" style="background:color-mix(in srgb, <?= $catColor ?> 15%, transparent);color:<?= $catColor ?>;margin-bottom:1rem">
      <i class="bi <?= $catIcon ?>"></i> <?= e($post['category']) ?>
    </span>

    <h1><?= e($post['title']) ?></h1>

    <div class="article-meta">
      <span><i class="bi bi-person-circle"></i> <?= e($post['author']) ?></span>
      <span><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($post['created_at'])) ?></span>
      <span><i class="bi bi-clock"></i> <?= (int)$post['reading_time'] ?> min read</span>
    </div>

    <div class="bd-hero-visual">
      <i class="bi <?= $catIcon ?>"></i>
    </div>

  </div>
</section>

<!-- ============================================ -->
<!-- CONTENT                                     -->
<!-- ============================================ -->
<section class="section" style="padding-top:0">
  <div class="container">
    <div class="bd-layout">

      <article>
        <div class="article-content">
          <?php
          $content = $post['content'] ?? '';
          if (strlen(strip_tags($content)) < 200) {
              $content = '
                <p>' . e($post['excerpt']) . '</p>

                <p>In today\'s fast-moving digital landscape, businesses that embrace modern software practices gain a significant competitive edge. Whether it\'s automating a repetitive workflow, building a customer-facing application, or integrating AI into your operations — the right technology decisions compound over time.</p>

                <p>At Nexora Labs, we\'ve shipped over 500 projects across industries including education, healthcare, e-commerce, and finance. Through that experience, we\'ve learned what works — and, more importantly, what doesn\'t.</p>

                <h2>Why this matters</h2>

                <p>Technology should be an enabler, not a bottleneck. Yet many businesses end up with systems that slow them down — legacy code that no one wants to touch, disconnected tools that force manual work, or platforms that can\'t scale with growth.</p>

                <blockquote>The best technology decisions are the ones that solve real business problems, not the ones that look impressive in a pitch deck.</blockquote>

                <p>When we audit a new client\'s setup, we look at three things: <strong>speed</strong>, <strong>security</strong>, and <strong>scalability</strong>. If any of these are compromised, it\'s usually a sign that deeper architectural work is needed.</p>

                <h2>Key takeaways</h2>

                <ul>
                  <li><strong>Start with the problem, not the tool.</strong> Technology is a means to an end. Understand what you\'re trying to achieve before choosing a stack.</li>
                  <li><strong>Invest in foundations.</strong> Clean architecture, security, and testing pay dividends long after launch.</li>
                  <li><strong>Ship early, iterate often.</strong> Real user feedback beats internal assumptions every time.</li>
                  <li><strong>Measure everything.</strong> If you can\'t measure it, you can\'t improve it.</li>
                  <li><strong>Partner with engineers, not vendors.</strong> The right team takes ownership of outcomes, not just tasks.</li>
                </ul>

                <h2>How to apply this to your business</h2>

                <p>Whether you\'re a startup building your first product or an enterprise modernising legacy systems, the principles are the same. Start small, validate quickly, and invest in the parts that matter most.</p>

                <h3>For startups</h3>

                <p>Focus on speed of iteration. Choose boring technology that lets you ship fast. Don\'t over-engineer for a scale you don\'t have yet. A monolith is fine — until it isn\'t.</p>

                <h3>For enterprises</h3>

                <p>Focus on integration and change management. The technical work is often the easy part; getting teams aligned on new processes is where the real challenge lies. Invest in documentation and training from day one.</p>

                <h2>Where to go from here</h2>

                <p>If you\'re exploring a project and want a second opinion — a real one, not a sales pitch — we\'re always happy to talk. Book a free 30-minute call and we\'ll help you think through your options.</p>
              ';
          }
          echo $content;
          ?>
        </div>

        <!-- Share -->
        <div class="bd-share">
          <span class="bd-share-label">Share this article:</span>
          <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
          <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" aria-label="Copy link" onclick="copyLink(this); return false;">
            <i class="bi bi-link-45deg"></i>
          </a>
        </div>

        <!-- Author -->
        <div class="bd-author">
          <div class="bd-author-avatar">
            <?= strtoupper(substr($post['author'], 0, 1)) ?>
          </div>
          <div>
            <h5><?= e($post['author']) ?></h5>
            <p>Senior engineer at Nexora Labs. Writing about software, AI and building products that matter.</p>
          </div>
        </div>
      </article>

      <!-- Sidebar -->
      <aside class="bd-sidebar">

        <div class="sidebar-card">
          <h4>Article info</h4>
          <ul>
            <li><a href="#"><i class="bi bi-tag-fill"></i> <?= e($post['category']) ?></a></li>
            <li><a href="#"><i class="bi bi-clock-history"></i> <?= (int)$post['reading_time'] ?> min read</a></li>
            <li><a href="#"><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($post['created_at'])) ?></a></li>
          </ul>
        </div>

        <div class="sidebar-card">
          <h4>Get a Quote</h4>
          <p style="font-size:0.86rem;color:var(--muted);margin-bottom:1rem;line-height:1.55">
            Got a project in mind? Let's talk about what we can build together.
          </p>
          <a href="<?= BASE_URL ?>request-quote.php" class="btn btn-primary" style="width:100%;font-size:0.85rem">
            <i class="bi bi-chat-dots"></i> Start a Project
          </a>
        </div>

        <?php if (!empty($related)): ?>
          <div class="sidebar-card">
            <h4>Related articles</h4>
            <ul>
              <?php foreach ($related as $r): ?>
                <li>
                  <a href="<?= BASE_URL ?>blog-detail.php?slug=<?= e($r['slug']) ?>">
                    <i class="bi bi-arrow-right"></i> <?= e($r['title']) ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

      </aside>

    </div>

    <!-- Related -->
    <?php if (!empty($related)): ?>
      <div class="bd-related">
        <h3>Read more articles</h3>
        <div class="grid-3">
          <?php foreach ($related as $r):
            $rCat = $r['category'] ?? 'General';
            $rColor = $blogCatColors[$rCat] ?? 'var(--primary)';
          ?>
            <a href="<?= BASE_URL ?>blog-detail.php?slug=<?= e($r['slug']) ?>" class="blog-card">
              <span class="blog-cat" style="background:color-mix(in srgb, <?= $rColor ?> 15%, transparent);color:<?= $rColor ?>">
                <i class="bi <?= blog_cat_icon($rCat) ?>"></i> <?= e($rCat) ?>
              </span>
              <h3><?= e($r['title']) ?></h3>
              <p><?= e($r['excerpt']) ?></p>
              <div class="blog-meta">
                <span><i class="bi bi-person-circle"></i> <?= e($r['author']) ?></span>
                <span><i class="bi bi-clock"></i> <?= (int)$r['reading_time'] ?> min</span>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<script>
/* ============================================
   READING PROGRESS
   ============================================ */
(function() {
  const bar = document.getElementById('readingProgress');
  const article = document.querySelector('.article-content');
  if (!bar || !article) return;

  function updateProgress() {
    const rect = article.getBoundingClientRect();
    const articleTop = rect.top + window.scrollY;
    const articleHeight = rect.height;
    const scrollStart = articleTop - 100;
    const scrollEnd = articleTop + articleHeight - window.innerHeight + 100;
    const current = window.scrollY;

    let progress = (current - scrollStart) / (scrollEnd - scrollStart);
    progress = Math.max(0, Math.min(1, progress));
    bar.style.width = (progress * 100) + '%';
  }

  window.addEventListener('scroll', updateProgress, { passive: true });
  window.addEventListener('resize', updateProgress);
  updateProgress();
})();

/* ============================================
   COPY LINK
   ============================================ */
function copyLink(el) {
  const url = window.location.href;
  if (navigator.clipboard) {
    navigator.clipboard.writeText(url).then(() => {
      const original = el.innerHTML;
      el.innerHTML = '<i class="bi bi-check-lg"></i>';
      el.style.background = 'var(--green)';
      el.style.color = '#fff';
      el.style.borderColor = 'var(--green)';
      setTimeout(() => {
        el.innerHTML = original;
        el.style.background = '';
        el.style.color = '';
        el.style.borderColor = '';
      }, 1800);
    });
  }
}
</script>

<?php include 'includes/footer.php'; ?>