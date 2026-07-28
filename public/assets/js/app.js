/*
 * Barraza's Construction — frontend behavior.
 * Vanilla JS, no dependencies. Progressive enhancement only: every
 * interactive element here must remain usable (just less animated)
 * if this file fails to load.
 */
(function () {
  'use strict';

  var header = document.querySelector('[data-site-header]');

  if (header) {
    var onScroll = function () {
      header.classList.toggle('is-scrolled', window.scrollY > 12);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  var navToggle = document.querySelector('[data-nav-toggle]');
  var mobileNav = document.querySelector('[data-mobile-nav]');
  var navClose = document.querySelector('[data-nav-close]');

  function openMobileNav() {
    if (!mobileNav) return;
    mobileNav.setAttribute('data-open', 'true');
    document.body.style.overflow = 'hidden';
    var firstLink = mobileNav.querySelector('a, button');
    if (firstLink) firstLink.focus();
  }

  function closeMobileNav() {
    if (!mobileNav) return;
    mobileNav.setAttribute('data-open', 'false');
    document.body.style.overflow = '';
    if (navToggle) navToggle.focus();
  }

  if (navToggle && mobileNav) {
    navToggle.addEventListener('click', openMobileNav);
  }

  if (navClose) {
    navClose.addEventListener('click', closeMobileNav);
  }

  if (mobileNav) {
    mobileNav.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') closeMobileNav();
    });

    mobileNav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', closeMobileNav);
    });
  }

  var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var supportsObserver = 'IntersectionObserver' in window;

  /* ------------------------------------------------------------------
     Scroll reveals.
     Deliberately no animation library. Everything here is a class
     toggle driving a CSS transition — the whole motion layer costs
     well under 2KB and cannot delay content, because every element is
     already server-rendered and only its opacity changes.
     Reveals fire once; re-animating on scroll-back is animation for
     its own sake.
     ------------------------------------------------------------------ */
  var revealItems = document.querySelectorAll('[data-reveal], [data-reveal-media]');

  if (!revealItems.length) {
    // nothing to do
  } else if (prefersReducedMotion || !supportsObserver) {
    revealItems.forEach(function (item) {
      item.classList.add('is-revealed');
    });
  } else {
    var revealObserver = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;

          // Stagger only within an explicit group, so unrelated
          // elements never wait on each other.
          var group = entry.target.closest('[data-reveal-group]');
          if (group) {
            var siblings = group.querySelectorAll('[data-reveal], [data-reveal-media]');
            var index = Array.prototype.indexOf.call(siblings, entry.target);
            entry.target.style.transitionDelay = Math.max(0, index) * 70 + 'ms';
          }

          entry.target.classList.add('is-revealed');
          revealObserver.unobserve(entry.target);
        });
      },
      { threshold: 0.12, rootMargin: '0px 0px -8% 0px' }
    );

    revealItems.forEach(function (item) {
      revealObserver.observe(item);
    });
  }

  /* ------------------------------------------------------------------
     The Datum — chapter tracking and progress.
     Read-only: a passive scroll listener rAF-throttled to one read per
     frame. Nothing here intercepts or overrides scrolling.
     ------------------------------------------------------------------ */
  var datum = document.querySelector('[data-datum]');
  var chapters = Array.prototype.slice.call(document.querySelectorAll('[data-chapter]'));
  var numEls = document.querySelectorAll('[data-datum-num]');
  var labelEls = document.querySelectorAll('[data-datum-label]');
  var progressEls = document.querySelectorAll('[data-datum-progress]');

  // Chapters rendered on an ink ground need the light Datum treatment.
  var DARK_CHAPTERS = { '02': true, '08': true };

  var proof = document.querySelector('[data-proof]');
  var proofShots = document.querySelectorAll('[data-proof-shot]');
  var proofTexts = document.querySelectorAll('[data-proof-text]');
  var proofSteps = document.querySelectorAll('[data-proof-step]');

  // Approved allocation: the middle state holds the most scroll.
  var PROOF_BOUNDS = [0.28, 0.64];
  var proofState = -1;

  function setProofState(index) {
    if (index === proofState) return;
    proofState = index;

    proofShots.forEach(function (el, i) {
      var on = i === index;
      el.classList.toggle('is-active', on);
      // Keep one state in the accessibility tree at a time — all three
      // remain in the HTML for crawlers and no-JS readers.
      if (on) {
        el.removeAttribute('inert');
      } else {
        el.setAttribute('inert', '');
      }
    });

    proofTexts.forEach(function (el, i) {
      var on = i === index;
      el.classList.toggle('is-active', on);
      if (on) {
        el.removeAttribute('inert');
      } else {
        el.setAttribute('inert', '');
      }
    });

    proofSteps.forEach(function (el, i) {
      el.classList.toggle('is-active', i === index);
    });
  }

  function updateProof() {
    if (!proof || !proofShots.length) return;

    // Below 1024px the stage is not pinned; show every state stacked.
    if (window.innerWidth < 1024) {
      proofShots.forEach(function (el) {
        el.classList.add('is-active');
        el.removeAttribute('inert');
      });
      proofTexts.forEach(function (el) {
        el.classList.add('is-active');
        el.removeAttribute('inert');
      });
      proofState = -1;
      return;
    }

    var rect = proof.getBoundingClientRect();
    var travel = rect.height - window.innerHeight;
    if (travel <= 0) return;

    var p = Math.min(1, Math.max(0, -rect.top / travel));
    setProofState(p < PROOF_BOUNDS[0] ? 0 : (p < PROOF_BOUNDS[1] ? 1 : 2));
  }

  function updateDatum() {
    if (!chapters.length) return;

    var max = document.documentElement.scrollHeight - window.innerHeight;
    var pct = max > 0 ? Math.min(1, Math.max(0, window.scrollY / max)) : 0;

    progressEls.forEach(function (el) {
      if (el.classList.contains('datum__progress')) {
        el.style.height = (pct * 100).toFixed(2) + '%';
      } else {
        el.style.width = (pct * 100).toFixed(2) + '%';
      }
    });

    var probe = window.scrollY + window.innerHeight * 0.42;
    var current = chapters[0];

    chapters.forEach(function (ch) {
      if (ch.offsetTop <= probe) current = ch;
    });

    var n = current.getAttribute('data-chapter');
    var label = current.getAttribute('data-chapter-label') || '';

    numEls.forEach(function (el) { el.textContent = n; });
    labelEls.forEach(function (el) { el.textContent = label; });

    if (datum) {
      datum.classList.toggle('is-on-dark', DARK_CHAPTERS[n] === true);
    }
  }

  var ticking = false;

  function onScrollFrame() {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(function () {
      updateDatum();
      updateProof();
      ticking = false;
    });
  }

  if (chapters.length) {
    window.addEventListener('scroll', onScrollFrame, { passive: true });
    window.addEventListener('resize', onScrollFrame, { passive: true });
    updateDatum();
    updateProof();
  }
})();
