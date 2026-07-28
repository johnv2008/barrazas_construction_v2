/* ============================================================
   GREYBOX — minimal prototype JS.

   Deliberately dependency-free. No GSAP, no ScrollTrigger, no
   Lenis, no SplitType. Nothing here intercepts, throttles, or
   overrides scrolling — every listener is passive and read-only.
   Its entire job is: toggle variants, report the Datum position,
   switch Chapter 03 state, and measure pacing.
   ============================================================ */

(function () {
  'use strict';

  var body = document.body;
  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- variant toggles ---------- */
  document.querySelectorAll('.proto-bar button[data-set]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var set = btn.dataset.set;
      var val = btn.dataset.val;
      body.dataset[set] = val;

      document.querySelectorAll('.proto-bar button[data-set="' + set + '"]')
        .forEach(function (b) { b.setAttribute('aria-pressed', String(b === btn)); });

      measure();
    });
  });

  /* ---------- chapter registry ---------- */
  var chapters = Array.prototype.slice.call(document.querySelectorAll('[data-chapter]'));

  var datumNum = document.getElementById('datumNum');
  var datumLabel = document.getElementById('datumLabel');
  var datumProgress = document.getElementById('datumProgress');
  var datumTicks = document.getElementById('datumTicks');
  var datumMobile = document.getElementById('datumMobile');
  var datumMobileBar = document.getElementById('datumMobileBar');
  var readout = document.getElementById('readout');

  /* Chapter-boundary ticks drawn on the fixed Datum line. Positioned by
     each chapter's share of total document height. */
  function drawTicks() {
    if (!datumTicks) return;
    var docH = document.documentElement.scrollHeight;
    datumTicks.innerHTML = '';
    chapters.forEach(function (ch) {
      var i = document.createElement('i');
      i.style.top = ((ch.offsetTop / docH) * 100).toFixed(3) + '%';
      datumTicks.appendChild(i);
    });
  }

  /* ---------- Chapter 03 state (sticky variant) ---------- */
  var midTrack = document.getElementById('midTrack');
  var midFrames = document.querySelectorAll('#midFrame [data-state]');
  var midPanels = document.querySelectorAll('#midPanel [data-state]');
  var midRuler = document.querySelectorAll('#midRuler span');

  /* Approved allocation: Before 22%, During 48%, After 30%.
     The middle deliberately holds the most scroll distance. */
  var STATE_BOUNDS = [0.22, 0.70];

  function setMidState(i) {
    midFrames.forEach(function (el, n) { el.classList.toggle('is-active', n === i); });
    midPanels.forEach(function (el, n) { el.classList.toggle('is-active', n === i); });
    midRuler.forEach(function (el, n) { el.classList.toggle('is-active', n === i); });
  }

  function updateMid() {
    if (!midTrack || body.dataset.ch03 !== 'a') return;
    if (window.innerWidth < 1024) { setMidState(1); return; }

    var r = midTrack.getBoundingClientRect();
    var travel = r.height - window.innerHeight;
    if (travel <= 0) return;

    var p = Math.min(1, Math.max(0, -r.top / travel));
    var i = p < STATE_BOUNDS[0] ? 0 : (p < STATE_BOUNDS[1] ? 1 : 2);
    setMidState(i);
  }

  /* ---------- scroll reporting (passive, read-only) ---------- */
  function onScroll() {
    var y = window.scrollY;
    var docH = document.documentElement.scrollHeight - window.innerHeight;
    var pct = docH > 0 ? y / docH : 0;

    if (datumProgress) datumProgress.style.height = (pct * 100).toFixed(2) + '%';
    if (datumMobileBar) datumMobileBar.style.width = (pct * 100).toFixed(2) + '%';

    var probe = y + window.innerHeight * 0.4;
    var current = chapters[0];
    chapters.forEach(function (ch) { if (ch.offsetTop <= probe) current = ch; });

    if (current) {
      var n = current.dataset.chapter;
      var t = current.dataset.title;
      if (datumNum) datumNum.textContent = n;
      if (datumLabel) datumLabel.textContent = t;
      if (datumMobile) datumMobile.textContent = n + ' · ' + t;
    }

    updateMid();
  }

  var ticking = false;
  window.addEventListener('scroll', function () {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(function () { onScroll(); ticking = false; });
  }, { passive: true });

  /* ---------- pacing measurement ---------- */
  function measure() {
    if (!readout) return;

    // Let layout settle after a variant toggle before measuring.
    window.requestAnimationFrame(function () {
      var vh = window.innerHeight;
      var docH = document.documentElement.scrollHeight;
      var rows = chapters.map(function (ch) {
        return ch.dataset.chapter + ':' + (ch.offsetHeight / vh).toFixed(2) + 'vh';
      });

      // Scan ≈ fast scroll. Engaged ≈ reading at ~230 wpm plus dwell.
      var words = (document.body.innerText || '').trim().split(/\s+/).length;
      var scan = Math.round((docH / vh) * 1.6);
      var engaged = Math.round(words / 230 * 60);

      readout.textContent =
        'viewport ' + window.innerWidth + '×' + vh +
        '  ·  total ' + (docH / vh).toFixed(1) + ' vh (' + docH + 'px)' +
        '  ·  ' + rows.join(' ') +
        '  ·  scan ~' + scan + 's / engaged ~' + Math.round(engaged / 60) + 'm' +
        (reduced ? '  ·  REDUCED-MOTION' : '');

      drawTicks();
    });
  }

  window.addEventListener('resize', function () { measure(); onScroll(); });
  window.addEventListener('load', function () { measure(); onScroll(); });

  drawTicks();
  onScroll();
  measure();

  // Console summary for the pacing analysis write-up.
  window.gbReport = function () {
    var vh = window.innerHeight;
    console.table(chapters.map(function (ch) {
      return {
        ch: ch.dataset.chapter,
        title: ch.dataset.title,
        px: ch.offsetHeight,
        vh: +(ch.offsetHeight / vh).toFixed(2)
      };
    }));
    console.log('total vh:', +(document.documentElement.scrollHeight / vh).toFixed(2));
  };
})();
