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

  // Soft fade-up for [data-reveal] items within a [data-reveal-group]
  // (service/project cards). Skips the stagger (and the observer
  // entirely) when the visitor prefers reduced motion — items are
  // simply visible immediately, matching the instant CSS transitions
  // that --duration-standard already resolves to in that case.
  var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  document.querySelectorAll('[data-reveal-group]').forEach(function (group) {
    var items = group.querySelectorAll('[data-reveal]');

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
      items.forEach(function (item) {
        item.classList.add('is-visible');
      });
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          var index = Array.prototype.indexOf.call(items, entry.target);
          entry.target.style.transitionDelay = Math.max(0, index) * 60 + 'ms';
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        });
      },
      { threshold: 0.2, rootMargin: '0px 0px -10% 0px' }
    );

    items.forEach(function (item) {
      observer.observe(item);
    });
  });
})();
