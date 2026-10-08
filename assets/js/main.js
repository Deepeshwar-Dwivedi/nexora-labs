/* ================================================================
   NEXORA LABS — MAIN JS
   Premium SaaS Interactions
   ================================================================ */
(function() {
  'use strict';

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const isTouch = window.matchMedia('(hover: none)').matches;

  /* ============================================
     1. THEME TOGGLE
     ============================================ */
  function initTheme() {
    const saved = localStorage.getItem('nexora-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = saved || (prefersDark ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', theme);

    document.querySelectorAll('.theme-toggle').forEach(btn => {
      btn.addEventListener('click', () => {
        const current = document.documentElement.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', next);
        try { localStorage.setItem('nexora-theme', next); } catch (e) {}
      });
    });
  }

  /* ============================================
     2. HEADER SCROLL
     ============================================ */
  function initHeaderScroll() {
    const header = document.querySelector('.site-header');
    if (!header) return;
    const onScroll = () => {
      if (window.scrollY > 10) header.classList.add('scrolled');
      else header.classList.remove('scrolled');
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ============================================
     3. MOBILE NAV
     ============================================ */
  function initMobileNav() {
    const toggle = document.getElementById('navToggle');
    const nav = document.getElementById('mainNav');
    if (!toggle || !nav) return;

    function close() {
      nav.classList.remove('open');
      const i = toggle.querySelector('i');
      if (i) i.className = 'bi bi-list';
      document.querySelectorAll('.nav-item.open').forEach(el => el.classList.remove('open'));
    }

    toggle.addEventListener('click', (e) => {
      e.stopPropagation();
      nav.classList.toggle('open');
      const i = toggle.querySelector('i');
      if (i) i.className = nav.classList.contains('open') ? 'bi bi-x-lg' : 'bi bi-list';
    });

    // Mobile: toggle mega menus on click
    document.querySelectorAll('.nav-item.has-mega > .nav-link').forEach(link => {
      link.addEventListener('click', (e) => {
        if (window.innerWidth <= 900) {
          e.preventDefault();
          const item = link.closest('.nav-item');
          const wasOpen = item.classList.contains('open');
          document.querySelectorAll('.nav-item.open').forEach(el => el.classList.remove('open'));
          if (!wasOpen) item.classList.add('open');
        }
      });
    });

    document.addEventListener('click', (e) => {
      if (!nav.contains(e.target) && !toggle.contains(e.target)) close();
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth > 900) nav.classList.remove('open');
    });
  }

  /* ============================================
     4. SCROLL PROGRESS BAR
     ============================================ */
  function initScrollProgress() {
    const bar = document.getElementById('scrollProgress');
    if (!bar) return;
    const update = () => {
      const h = document.documentElement.scrollHeight - window.innerHeight;
      const pct = h > 0 ? (window.scrollY / h) * 100 : 0;
      bar.style.width = pct + '%';
    };
    window.addEventListener('scroll', update, { passive: true });
    update();
  }

  /* ============================================
     5. REVEAL ON SCROLL
     ============================================ */
  function initReveal() {
    const els = document.querySelectorAll('.reveal');
    if (!els.length) return;
    if (prefersReducedMotion) {
      els.forEach(el => el.classList.add('visible'));
      return;
    }
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const delay = Number(entry.target.dataset.delay) || 0;
          setTimeout(() => entry.target.classList.add('visible'), delay);
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
    els.forEach(el => io.observe(el));
  }

  /* ============================================
     6. COUNTER ANIMATION
     ============================================ */
  function initCounters() {
    const counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;
    counters.forEach(el => {
      const target = Number(el.dataset.count) || 0;
      let started = false;
      const obs = new IntersectionObserver(([entry]) => {
        if (!entry.isIntersecting || started) return;
        started = true;
        obs.disconnect();
        if (prefersReducedMotion) { el.textContent = target; return; }
        const dur = 1600;
        const start = performance.now();
        const tick = (now) => {
          const t = Math.min((now - start) / dur, 1);
          const eased = 1 - Math.pow(1 - t, 3);
          el.textContent = Math.floor(target * eased);
          if (t < 1) requestAnimationFrame(tick);
          else el.textContent = target;
        };
        requestAnimationFrame(tick);
      }, { threshold: 0.4 });
      obs.observe(el);
    });
  }

  /* ============================================
     7. FILTER + SEARCH
     ============================================ */
  function initFilter() {
    const btns = document.querySelectorAll('[data-filter]');
    const items = document.querySelectorAll('[data-category]');
    const searchBox = document.getElementById('filterSearch');
    const noResults = document.getElementById('noResults');
    if (!btns.length || !items.length) return;

    let activeFilter = 'all';
    let query = '';

    function apply() {
      let visible = 0;
      const q = query.trim().toLowerCase();
      items.forEach(item => {
        const cat = (item.dataset.category || '').toLowerCase();
        const search = (item.dataset.search || '').toLowerCase();
        const mF = activeFilter === 'all' || cat === activeFilter.toLowerCase();
        const mS = !q || search.includes(q);
        if (mF && mS) { item.style.display = ''; visible++; }
        else { item.style.display = 'none'; }
      });
      if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
    }

    btns.forEach(btn => {
      btn.addEventListener('click', () => {
        btns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeFilter = btn.dataset.filter || 'all';
        apply();
      });
    });

    if (searchBox) {
      let timer;
      searchBox.addEventListener('input', e => {
        clearTimeout(timer);
        timer = setTimeout(() => { query = e.target.value; apply(); }, 180);
      });
    }
  }

  /* ============================================
     8. SMOOTH ANCHOR SCROLL
     ============================================ */
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(link => {
      link.addEventListener('click', e => {
        const id = link.getAttribute('href');
        if (id === '#' || id.length < 2) return;
        const target = document.querySelector(id);
        if (!target) return;
        e.preventDefault();
        const header = document.querySelector('.site-header');
        const offset = header ? header.offsetHeight + 20 : 80;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
      });
    });
  }

  /* ============================================
     9. TABS
     ============================================ */
  function initTabs() {
    document.querySelectorAll('.tab').forEach(tab => {
      tab.addEventListener('click', () => {
        const target = tab.dataset.tab;
        const parent = tab.closest('.tabs-container') || document;
        parent.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        parent.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        tab.classList.add('active');
        const content = parent.querySelector(`.tab-content[data-content="${target}"]`);
        if (content) content.classList.add('active');
      });
    });
  }

  /* ============================================
     10. FORM LOADING STATE
     ============================================ */
  function initForms() {
    document.querySelectorAll('form[data-loading]').forEach(form => {
      form.addEventListener('submit', () => {
        const btn = form.querySelector('button[type="submit"]');
        if (btn && !btn.disabled) {
          btn.disabled = true;
          const orig = btn.innerHTML;
          btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Please wait...';
          setTimeout(() => {
            if (btn.disabled) { btn.disabled = false; btn.innerHTML = orig; }
          }, 10000);
        }
      });
    });
  }

  /* ============================================
     11. PARALLAX HERO (mouse move)
     ============================================ */
  function initParallax() {
    if (isTouch || prefersReducedMotion) return;
    const hero = document.querySelector('.hero');
    if (!hero) return;
    const floats = hero.querySelectorAll('.float-card, .browser-mock');
    if (!floats.length) return;

    hero.addEventListener('mousemove', (e) => {
      const rect = hero.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;
      floats.forEach((el, i) => {
        const depth = (i + 1) * 6;
        el.style.transform = `translate(${x * depth}px, ${y * depth}px)`;
      });
    });

    hero.addEventListener('mouseleave', () => {
      floats.forEach(el => { el.style.transform = ''; });
    });
  }

  /* ============================================
     12. BENTO CARD MOUSE GLOW
     ============================================ */
  function initBentoGlow() {
    if (isTouch) return;
    document.querySelectorAll('.bento-card, .service-card').forEach(card => {
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        card.style.setProperty('--mouse-x', x + 'px');
        card.style.setProperty('--mouse-y', y + 'px');
      });
    });
  }

  /* ============================================
     INIT
     ============================================ */
  function init() {
    initTheme();
    initHeaderScroll();
    initMobileNav();
    initScrollProgress();
    initReveal();
    initCounters();
    initFilter();
    initSmoothScroll();
    initTabs();
    initForms();
    initParallax();
    initBentoGlow();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();