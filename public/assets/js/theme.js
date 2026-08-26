/* ============================================================
   WEB EDUKASI — PREMIUM MOTION ENGINE
   reveal • count-up • tilt • ripple • particles • confetti
   ============================================================ */
(function () {
  'use strict';

  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- Preloader ---------- */
  function initLoader() {
    const loader = document.getElementById('brandLoader');
    if (!loader) return;
    const done = () => loader.classList.add('done');
    if (document.readyState === 'complete') setTimeout(done, 250);
    else window.addEventListener('load', () => setTimeout(done, 350));
    // safety net
    setTimeout(done, 2200);
  }

  /* ---------- Scroll Reveal (with stagger groups) ---------- */
  function initReveal() {
    // auto-stagger inside [data-stagger] parents
    document.querySelectorAll('[data-stagger]').forEach((group) => {
      Array.from(group.children).forEach((child, i) => {
        child.classList.add('reveal');
        child.style.transitionDelay = Math.min(i * 90, 700) + 'ms';
      });
    });
    const els = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
    if (!els.length) return;
    if (prefersReduced || !('IntersectionObserver' in window)) {
      els.forEach((el) => el.classList.add('is-visible'));
      return;
    }
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );
    els.forEach((el) => io.observe(el));
  }

  /* ---------- Count Up ---------- */
  function animateCount(el) {
    const target = parseFloat(el.dataset.count || '0');
    const suffix = el.dataset.suffix || '';
    const duration = parseInt(el.dataset.duration || '1600', 10);
    const decimals = (el.dataset.decimals | 0);
    if (prefersReduced) { el.textContent = target.toFixed(decimals) + suffix; return; }
    const start = performance.now();
    function frame(now) {
      const p = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - p, 4); // easeOutQuart
      el.textContent = (target * eased).toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, '.') + suffix;
      if (p < 1) requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);
  }
  function initCounters() {
    const counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;
    const io = new IntersectionObserver(
      (entries) => entries.forEach((e) => {
        if (e.isIntersecting) { animateCount(e.target); io.unobserve(e.target); }
      }),
      { threshold: 0.5 }
    );
    counters.forEach((c) => io.observe(c));
  }

  /* ---------- Ripple on buttons ---------- */
  function initRipple() {
    document.querySelectorAll('.btn-premium, .btn-gold, .btn-ripple').forEach((btn) => {
      if (btn.dataset.rippleBound) return;
      btn.dataset.rippleBound = '1';
      btn.addEventListener('click', function (e) {
        const rect = this.getBoundingClientRect();
        const wave = document.createElement('span');
        const size = Math.max(rect.width, rect.height);
        wave.className = 'ripple-wave';
        wave.style.width = wave.style.height = size + 'px';
        wave.style.left = e.clientX - rect.left - size / 2 + 'px';
        wave.style.top = e.clientY - rect.top - size / 2 + 'px';
        this.appendChild(wave);
        setTimeout(() => wave.remove(), 700);
      });
    });
  }

  /* ---------- 3D Tilt ---------- */
  function initTilt() {
    if (prefersReduced) return;
    document.querySelectorAll('[data-tilt]').forEach((card) => {
      if (card.dataset.tiltBound) return;
      card.dataset.tiltBound = '1';
      const max = parseFloat(card.dataset.tiltMax || '8');
      card.style.transition = 'transform .18s ease-out';
      card.addEventListener('mousemove', (e) => {
        const r = card.getBoundingClientRect();
        const px = (e.clientX - r.left) / r.width - 0.5;
        const py = (e.clientY - r.top) / r.height - 0.5;
        card.style.transform = `perspective(900px) rotateY(${px * max}deg) rotateX(${-py * max}deg) translateY(-6px)`;
      });
      card.addEventListener('mouseleave', () => {
        card.style.transition = 'transform .6s cubic-bezier(.22,1,.36,1)';
        card.style.transform = '';
      });
    });
  }

  /* ---------- Navbar scroll state ---------- */
  function initNavScroll() {
    const nav = document.querySelector('.nav-shell');
    if (!nav) return;
    const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 24);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---------- Floating particles canvas ---------- */
  function initParticles() {
    const canvases = document.querySelectorAll('canvas[data-particles]');
    if (!canvases.length || prefersReduced) return;
    canvases.forEach((canvas) => {
      const ctx = canvas.getContext('2d');
      let w, h, dots = [], raf;
      const COUNT = parseInt(canvas.dataset.particlesCount || '46', 10);
      const palette = ['#a78bfa', '#22d3ee', '#ffc53d', '#fb7185', '#ffffff'];

      function resize() {
        w = canvas.width = canvas.offsetWidth * devicePixelRatio;
        h = canvas.height = canvas.offsetHeight * devicePixelRatio;
      }
      function makeDot() {
        return {
          x: Math.random() * w,
          y: Math.random() * h,
          r: (Math.random() * 2.2 + 0.7) * devicePixelRatio,
          vy: -(Math.random() * 0.35 + 0.12) * devicePixelRatio,
          vx: (Math.random() - 0.5) * 0.22 * devicePixelRatio,
          o: Math.random() * 0.55 + 0.15,
          c: palette[(Math.random() * palette.length) | 0],
        };
      }
      function loop() {
        ctx.clearRect(0, 0, w, h);
        for (const d of dots) {
          d.x += d.vx; d.y += d.vy;
          if (d.y < -10) { d.y = h + 10; d.x = Math.random() * w; }
          if (d.x < -10) d.x = w + 10;
          if (d.x > w + 10) d.x = -10;
          ctx.globalAlpha = d.o;
          ctx.fillStyle = d.c;
          ctx.beginPath();
          ctx.arc(d.x, d.y, d.r, 0, Math.PI * 2);
          ctx.fill();
        }
        raf = requestAnimationFrame(loop);
      }
      resize();
      dots = Array.from({ length: COUNT }, makeDot);
      loop();
      window.addEventListener('resize', resize);
      document.addEventListener('visibilitychange', () => {
        if (document.hidden) cancelAnimationFrame(raf); else loop();
      });
    });
  }

  /* ---------- Mouse spotlight (hero areas) ---------- */
  function initSpotlight() {
    if (prefersReduced) return;
    document.querySelectorAll('[data-spotlight]').forEach((zone) => {
      const glow = document.createElement('div');
      glow.className = 'spotlight';
      glow.style.opacity = '0';
      zone.appendChild(glow);
      zone.addEventListener('mousemove', (e) => {
        glow.style.left = e.clientX + 'px';
        glow.style.top = e.clientY + 'px';
        glow.style.opacity = '1';
      });
      zone.addEventListener('mouseleave', () => (glow.style.opacity = '0'));
    });
  }

  /* ---------- Confetti burst (global API) ---------- */
  window.premiumConfetti = function (originX, originY, count) {
    if (prefersReduced) return;
    originX = originX ?? window.innerWidth / 2;
    originY = originY ?? window.innerHeight / 3;
    count = count ?? 90;
    const colors = ['#7c5cff', '#22d3ee', '#ffc53d', '#fb7185', '#34d399', '#f472b6'];
    for (let i = 0; i < count; i++) {
      const piece = document.createElement('div');
      piece.className = 'confetti-piece';
      const size = Math.random() * 8 + 5;
      const isCircle = Math.random() > 0.6;
      piece.style.cssText =
        `left:${originX}px;top:${originY}px;width:${size}px;height:${isCircle ? size : size * 0.45}px;` +
        `background:${colors[i % colors.length]};border-radius:${isCircle ? '50%' : '2px'};`;
      document.body.appendChild(piece);
      const angle = Math.random() * Math.PI * 2;
      const velocity = Math.random() * 340 + 140;
      const dx = Math.cos(angle) * velocity;
      const dy = Math.sin(angle) * velocity - 260;
      const rot = Math.random() * 720 - 360;
      const dur = Math.random() * 1300 + 1500;
      piece.animate(
        [
          { transform: 'translate(0,0) rotate(0deg)', opacity: 1 },
          { transform: `translate(${dx}px, ${dy}px) rotate(${rot / 2}deg)`, opacity: 1, offset: 0.35 },
          { transform: `translate(${dx * 1.25}px, ${dy + 520}px) rotate(${rot}deg)`, opacity: 0 },
        ],
        { duration: dur, easing: 'cubic-bezier(.16,.74,.35,1)' }
      ).onfinish = () => piece.remove();
    }
  };

  /* ---------- Magnetic buttons (desktop only) ---------- */
  function initMagnetic() {
    if (prefersReduced || window.matchMedia('(hover: none)').matches) return;
    document.querySelectorAll('[data-magnetic]').forEach((el) => {
      if (el.dataset.magneticBound) return;
      el.dataset.magneticBound = '1';
      el.addEventListener('mousemove', (e) => {
        const r = el.getBoundingClientRect();
        const mx = (e.clientX - r.left - r.width / 2) * 0.28;
        const my = (e.clientY - r.top - r.height / 2) * 0.28;
        el.style.transform = `translate(${mx}px, ${my}px)`;
      });
      el.addEventListener('mouseleave', () => (el.style.transform = ''));
    });
  }

  /* ---------- Parallax float layers ---------- */
  function initParallax() {
    if (prefersReduced) return;
    const layers = document.querySelectorAll('[data-parallax]');
    if (!layers.length) return;
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      layers.forEach((layer) => {
        const speed = parseFloat(layer.dataset.parallax || '0.12');
        layer.style.transform = `translateY(${y * speed}px)`;
      });
    }, { passive: true });
  }

  /* ---------- Boot ---------- */
  function boot() {
    initLoader();
    initReveal();
    initCounters();
    initRipple();
    initTilt();
    initMagnetic();
    initNavScroll();
    initParticles();
    initSpotlight();
    initParallax();
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();

  // re-init ripple for dynamically added buttons
  window.premiumRefresh = function () { initRipple(); initTilt(); initMagnetic(); };
})();
