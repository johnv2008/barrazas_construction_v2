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

  // Staggered scroll-reveal for [data-reveal] items within a
  // [data-reveal-group]. Skips the stagger (and the observer
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
          entry.target.style.transitionDelay = Math.max(0, index) * 80 + 'ms';
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

  // ---- Before / after (progress -> complete) slider ----
  // A native <input type="range"> drives everything: dragging,
  // touch, and full keyboard support (arrows/Home/End) come for
  // free. JS only mirrors its value onto a CSS custom property that
  // both the image clip-path and the visual divider/knob read.
  document.querySelectorAll('[data-ba-slider]').forEach(function (root) {
    var frame = root.querySelector('.ba-slider__frame');
    var range = root.querySelector('.ba-slider__range');
    if (!frame || !range) return;

    var setPosition = function () {
      frame.style.setProperty('--ba-pos', range.value + '%');
    };

    range.addEventListener('input', setPosition);
    setPosition();
  });

  // ---- Sticky services: active-step tracking ----
  // Purely an observer -- native scrolling is never intercepted, so
  // this cannot scroll-jack. On mobile the visual column is hidden by
  // CSS, so this simply has no visible effect there (harmless).
  var serviceSteps = document.querySelectorAll('[data-service-step]');
  var serviceFrames = document.querySelectorAll('[data-service-frame]');

  if (serviceSteps.length && serviceFrames.length && 'IntersectionObserver' in window) {
    var activateService = function (index) {
      serviceSteps.forEach(function (step) {
        step.classList.toggle('is-active', step.getAttribute('data-index') === index);
      });
      serviceFrames.forEach(function (frame) {
        frame.classList.toggle('is-active', frame.getAttribute('data-index') === index);
      });
    };

    var serviceObserver = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            activateService(entry.target.getAttribute('data-index'));
          }
        });
      },
      { threshold: 0, rootMargin: '-45% 0px -45% 0px' }
    );

    serviceSteps.forEach(function (step) {
      serviceObserver.observe(step);
    });
  }

  // ---- Guided project form: progressive-enhancement stepper ----
  // Without JS, both steps render visible in one scrollable form and
  // submit normally. With JS, step 2 is hidden until "Continue" and a
  // Back control appears -- purely a presentation layer over the same
  // single <form>.
  var projectForm = document.querySelector('[data-project-form]');

  if (projectForm) {
    var steps = projectForm.querySelectorAll('[data-form-step]');
    var indicatorItems = projectForm.querySelectorAll('[data-form-indicator]');

    if (steps.length > 1) {
      var showStep = function (targetIndex) {
        steps.forEach(function (step, i) {
          step.hidden = i !== targetIndex;
        });
        indicatorItems.forEach(function (item, i) {
          item.classList.toggle('is-active', i === targetIndex);
        });
        var heading = steps[targetIndex].querySelector('h2, h3, legend');
        if (heading) {
          heading.setAttribute('tabindex', '-1');
          heading.focus();
        }
      };

      projectForm.querySelectorAll('[data-step-continue]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var currentStep = btn.closest('[data-form-step]');
          var requiredFields = currentStep.querySelectorAll('[required]');
          for (var i = 0; i < requiredFields.length; i++) {
            if (!requiredFields[i].checkValidity()) {
              requiredFields[i].reportValidity();
              return;
            }
          }
          showStep(Array.prototype.indexOf.call(steps, currentStep) + 1);
        });
      });

      projectForm.querySelectorAll('[data-step-back]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var currentStep = btn.closest('[data-form-step]');
          showStep(Array.prototype.indexOf.call(steps, currentStep) - 1);
        });
      });

      showStep(0);
    }
  }
})();
