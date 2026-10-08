<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$pageTitle = 'Cost Calculator';
$pageDesc  = 'Get an instant project cost estimate. Adjust options live.';

include 'includes/header.php';
?>

<section class="hero">
  <div class="container" style="text-align:center">
    <span class="eyebrow">Estimator</span>
    <h1 class="display-2" style="margin-bottom:1rem">
      Project <span class="gradient-text">cost calculator.</span>
    </h1>
    <p class="lead" style="margin:0 auto 2.5rem;text-align:center">
      Answer 4 quick questions and get a realistic budget range in under 60 seconds.
    </p>

    <div class="hero-stats" style="justify-content:center;max-width:720px;margin-left:auto;margin-right:auto">
      <div class="hero-stat"><strong data-count="60">0</strong><span>Seconds</span></div>
      <div class="hero-stat"><strong data-count="500">0</strong><span>Projects Priced</span></div>
      <div class="hero-stat"><strong data-count="98">0</strong><span>% Accuracy</span></div>
      <div class="hero-stat"><strong data-count="24">0</strong><span>Hour Followup</span></div>
    </div>
  </div>
</section>

<section class="section" style="padding-top:1rem">
  <div class="container">
    <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:2rem;align-items:start" class="calc-grid">

      <!-- Form -->
      <div class="card reveal" style="padding:2rem;border-radius:var(--radius-xl)">

        <div style="margin-bottom:2rem">
          <div style="display:flex;align-items:center;gap:0.7rem;font-size:0.8rem;font-weight:800;color:var(--text-2);margin-bottom:1rem;letter-spacing:0.02em;text-transform:uppercase">
            <span style="width:26px;height:26px;border-radius:8px;background:var(--primary);color:#fff;font-size:0.72rem;font-weight:800;display:grid;place-items:center">1</span>
            What do you need?
          </div>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:0.6rem" id="typeGrid">
            <?php
            $types = [
              ['website',   'bi-globe2',       'Website'],
              ['webapp',    'bi-window-stack', 'Web App'],
              ['mobile',    'bi-phone',        'Mobile App'],
              ['erp',       'bi-diagram-3',    'ERP / CRM'],
              ['ai',        'bi-robot',        'AI Solution'],
              ['ecommerce', 'bi-cart3',        'E-commerce'],
            ];
            foreach ($types as $i => $t): ?>
              <button type="button"
                      class="calc-type-btn <?= $i === 0 ? 'active' : '' ?>"
                      data-value="<?= $t[0] ?>"
                      data-type="type"
                      style="padding:1rem 0.8rem;background:var(--surface-2);border:1.5px solid var(--border-strong);border-radius:10px;cursor:pointer;font-family:inherit;text-align:center;display:flex;flex-direction:column;align-items:center;gap:0.5rem;transition:all 0.25s;color:var(--text-2)">
                <i class="bi <?= $t[1] ?>" style="font-size:1.3rem;color:var(--muted)"></i>
                <span style="font-size:0.78rem;font-weight:700;line-height:1.2"><?= $t[2] ?></span>
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <div style="margin-bottom:2rem">
          <div style="display:flex;align-items:center;gap:0.7rem;font-size:0.8rem;font-weight:800;color:var(--text-2);margin-bottom:1rem;letter-spacing:0.02em;text-transform:uppercase">
            <span style="width:26px;height:26px;border-radius:8px;background:var(--primary);color:#fff;font-size:0.72rem;font-weight:800;display:grid;place-items:center">2</span>
            Project complexity
          </div>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:0.6rem" id="complexityGroup">
            <?php
            $complexity = [
              ['basic',      'Basic',      'Simple, single-purpose'],
              ['standard',   'Standard',   'Typical business app'],
              ['advanced',   'Advanced',   'Complex features'],
              ['enterprise', 'Enterprise', 'Large scale, multi-tenant'],
            ];
            foreach ($complexity as $i => $c): ?>
              <button type="button"
                      class="calc-radio-btn <?= $i === 1 ? 'active' : '' ?>"
                      data-value="<?= $c[0] ?>"
                      data-type="complexity"
                      style="padding:0.9rem 1rem;background:var(--surface-2);border:1.5px solid var(--border-strong);border-radius:10px;cursor:pointer;font-family:inherit;text-align:left;display:flex;flex-direction:column;gap:0.2rem;transition:all 0.25s">
                <strong style="font-size:0.88rem;font-weight:800;color:var(--text-2)"><?= $c[1] ?></strong>
                <small style="font-size:0.72rem;color:var(--muted);font-weight:600"><?= $c[2] ?></small>
              </button>
            <?php endforeach; ?>
          </div>
        </div>

        <div style="margin-bottom:2rem">
          <div style="display:flex;align-items:center;gap:0.7rem;font-size:0.8rem;font-weight:800;color:var(--text-2);margin-bottom:1rem;letter-spacing:0.02em;text-transform:uppercase">
            <span style="width:26px;height:26px;border-radius:8px;background:var(--primary);color:#fff;font-size:0.72rem;font-weight:800;display:grid;place-items:center">3</span>
            Features needed
          </div>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:0.5rem">
            <?php
            $features = [
              ['auth',      'Authentication'],
              ['admin',     'Admin Dashboard'],
              ['payment',   'Payment Gateway'],
              ['api',       'API Integration'],
              ['ai',        'AI / Automation'],
              ['multilang', 'Multi-language'],
            ];
            foreach ($features as $f): ?>
              <label style="display:flex;align-items:center;gap:0.7rem;padding:0.75rem 1rem;background:var(--surface-2);border:1.5px solid var(--border-strong);border-radius:10px;cursor:pointer;transition:all 0.25s;user-select:none" class="calc-feature">
                <input type="checkbox" data-value="<?= $f[0] ?>" data-feature style="position:absolute;opacity:0;pointer-events:none">
                <span class="check" style="width:20px;height:20px;border-radius:6px;border:2px solid var(--border-strong);display:grid;place-items:center;transition:all 0.25s;flex-shrink:0">
                  <i class="bi bi-check-lg" style="font-size:0.7rem;color:#fff;opacity:0;transform:scale(0.5);transition:all 0.25s"></i>
                </span>
                <span class="text" style="flex:1;font-size:0.85rem;font-weight:600;color:var(--text-2)"><?= $f[1] ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div>
          <div style="display:flex;align-items:center;gap:0.7rem;font-size:0.8rem;font-weight:800;color:var(--text-2);margin-bottom:1rem;letter-spacing:0.02em;text-transform:uppercase">
            <span style="width:26px;height:26px;border-radius:8px;background:var(--primary);color:#fff;font-size:0.72rem;font-weight:800;display:grid;place-items:center">4</span>
            Timeline
          </div>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:0.6rem" id="timelineGroup">
            <?php
            $timelines = [
              ['urgent',   'Urgent',   '1–2 weeks · +30%'],
              ['standard', 'Standard', '1–2 months · Standard'],
              ['flexible', 'Flexible', '3+ months · −10%'],
            ];
            foreach ($timelines as $i => $t): ?>
              <button type="button"
                      class="calc-radio-btn <?= $i === 1 ? 'active' : '' ?>"
                      data-value="<?= $t[0] ?>"
                      data-type="timeline"
                      style="padding:0.9rem 1rem;background:var(--surface-2);border:1.5px solid var(--border-strong);border-radius:10px;cursor:pointer;font-family:inherit;text-align:left;display:flex;flex-direction:column;gap:0.2rem;transition:all 0.25s">
                <strong style="font-size:0.88rem;font-weight:800;color:var(--text-2)"><?= $t[1] ?></strong>
                <small style="font-size:0.72rem;color:var(--muted);font-weight:600"><?= $t[2] ?></small>
              </button>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

      <!-- Result -->
      <aside style="position:sticky;top:100px;display:flex;flex-direction:column;gap:1rem" class="calc-result-panel">

        <div style="position:relative;background:linear-gradient(135deg,var(--primary),var(--primary-2));color:#fff;border-radius:var(--radius-xl);padding:2rem;overflow:hidden;box-shadow:var(--shadow-glow)">
          <div style="position:absolute;inset:0;background:radial-gradient(circle at 20% 20%,rgba(255,255,255,0.2),transparent 45%),radial-gradient(circle at 80% 80%,rgba(0,212,255,0.35),transparent 45%)"></div>
          <div style="position:relative;z-index:1">
            <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.15em;font-weight:800;opacity:0.85;margin-bottom:0.6rem;display:flex;align-items:center;gap:0.4rem">
              <i class="bi bi-graph-up-arrow"></i> Estimated Range
            </div>
            <div id="calcOutput" style="font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:800;letter-spacing:-0.02em;line-height:1.1;margin-bottom:0.6rem;color:#fff;font-variant-numeric:tabular-nums">$0 – $0</div>
            <div style="font-size:0.78rem;opacity:0.75;line-height:1.5;margin-bottom:1.3rem">
              Estimated budget only. Final pricing depends on detailed requirements.
            </div>

            <div style="display:flex;flex-direction:column;gap:0.4rem;padding:1rem 0;border-top:1px solid rgba(255,255,255,0.15);border-bottom:1px solid rgba(255,255,255,0.15);margin-bottom:1.3rem;font-size:0.82rem">
              <div style="display:flex;justify-content:space-between;padding:0.25rem 0"><span style="opacity:0.75">Base</span><span id="bdBase" style="font-weight:800">$0</span></div>
              <div style="display:flex;justify-content:space-between;padding:0.25rem 0"><span style="opacity:0.75">Complexity</span><span id="bdComplexity" style="font-weight:800">$0</span></div>
              <div style="display:flex;justify-content:space-between;padding:0.25rem 0"><span style="opacity:0.75">Features</span><span id="bdFeatures" style="font-weight:800">$0</span></div>
              <div style="display:flex;justify-content:space-between;padding:0.25rem 0"><span style="opacity:0.75">Timeline</span><span id="bdTimeline" style="font-weight:800">$0</span></div>
            </div>

            <div style="display:flex;flex-direction:column;gap:0.5rem">
              <a href="<?= BASE_URL ?>request-quote.php" class="btn" style="background:#fff;color:var(--primary);font-weight:800;width:100%">
                <i class="bi bi-arrow-right"></i> Continue With This
              </a>
              <a href="<?= BASE_URL ?>book-consultation.php" class="btn" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3);width:100%">
                <i class="bi bi-calendar-check"></i> Book a Call
              </a>
            </div>
          </div>
        </div>

        <div class="clean-card" style="padding:1.3rem">
          <h4 style="font-size:0.82rem;font-weight:800;margin-bottom:0.8rem;display:flex;align-items:center;gap:0.4rem">
            <i class="bi bi-info-circle-fill" style="color:var(--primary)"></i> What's included
          </h4>
          <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.55rem">
            <?php foreach ([
              'Discovery call & requirements',
              'UI/UX design with revisions',
              'Development with weekly demos',
              'QA, testing & security review',
              'Deployment + 30 days free support',
            ] as $item): ?>
              <li style="display:flex;gap:0.5rem;font-size:0.82rem;color:var(--muted);line-height:1.5">
                <i class="bi bi-check-circle-fill" style="color:var(--green);font-size:0.95rem;flex-shrink:0;margin-top:0.1rem"></i>
                <?= $item ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

      </aside>

    </div>
  </div>
</section>

<style>
  .calc-type-btn:hover { border-color: var(--primary); }
  .calc-type-btn.active { background: var(--primary-soft) !important; border-color: var(--primary) !important; color: var(--primary) !important; }
  .calc-type-btn.active i, .calc-type-btn.active span { color: var(--primary) !important; }
  .calc-radio-btn:hover { border-color: var(--primary); }
  .calc-radio-btn.active { background: var(--primary-soft) !important; border-color: var(--primary) !important; }
  .calc-radio-btn.active strong { color: var(--primary) !important; }
  .calc-feature:hover { border-color: var(--primary); }
  .calc-feature input:checked ~ .check { background: var(--primary); border-color: var(--primary); }
  .calc-feature input:checked ~ .check i { opacity: 1; transform: scale(1); }
  .calc-feature input:checked ~ .text { color: var(--primary); }
  @media (max-width: 900px) {
    .calc-grid { grid-template-columns: 1fr !important; }
    .calc-result-panel { position: static !important; }
  }
</style>

<script>
(function() {
  const BASE = {
    website: 1500, webapp: 5000, mobile: 8000,
    erp: 12000, ai: 10000, ecommerce: 4000,
  };
  const MULT = { basic: 1, standard: 1.5, advanced: 2.5, enterprise: 4 };
  const TL_MOD = { urgent: 1.30, standard: 1.00, flexible: 0.90 };
  const FEATURE_PRICE = 1200;

  const state = { type: 'website', complexity: 'standard', timeline: 'standard', features: [] };
  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

  $$('.calc-type-btn, .calc-radio-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const type = btn.dataset.type;
      const group = type === 'complexity' ? btn.closest('#complexityGroup')
                  : type === 'timeline'   ? btn.closest('#timelineGroup')
                  : btn.closest('#typeGrid');
      group.querySelectorAll('button').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      state[type] = btn.dataset.value;
      update();
    });
  });

  $$('input[data-feature]').forEach(cb => {
    cb.addEventListener('change', () => {
      state.features = $$('input[data-feature]:checked').map(el => el.dataset.value);
      update();
    });
  });

  function animateVal(el, from, to, dur = 350) {
    if (from === to) { el.textContent = '$' + to.toLocaleString(); return; }
    const start = performance.now();
    function tick(now) {
      const t = Math.min((now - start) / dur, 1);
      const e = 1 - Math.pow(1 - t, 3);
      const cur = Math.round(from + (to - from) * e);
      el.textContent = '$' + cur.toLocaleString();
      if (t < 1) requestAnimationFrame(tick);
      else el.textContent = '$' + to.toLocaleString();
    }
    requestAnimationFrame(tick);
  }

  let prev = { base: 0, complexity: 0, features: 0, timeline: 0 };

  function update() {
    const base = BASE[state.type] || 0;
    const multiplier = MULT[state.complexity] || 1;
    const featureSum = state.features.length * FEATURE_PRICE;
    const tlMod = TL_MOD[state.timeline] || 1;

    const preTimeline = (base * multiplier) + featureSum;
    const final = preTimeline * tlMod;
    const complexityTotal = (base * multiplier) - base;
    const timelineTotal = preTimeline * (tlMod - 1);

    const low = Math.round(final * 0.85);
    const high = Math.round(final * 1.20);

    const output = $('#calcOutput');
    if (output) output.textContent = `$${low.toLocaleString()} – $${high.toLocaleString()}`;

    animateVal($('#bdBase'), prev.base, base);
    animateVal($('#bdComplexity'), prev.complexity, Math.max(0, Math.round(complexityTotal)));
    animateVal($('#bdFeatures'), prev.features, featureSum);
    animateVal($('#bdTimeline'), prev.timeline, Math.round(timelineTotal));

    prev = {
      base,
      complexity: Math.max(0, Math.round(complexityTotal)),
      features: featureSum,
      timeline: Math.round(timelineTotal),
    };
  }

  update();
})();
</script>

<?php include 'includes/footer.php'; ?>