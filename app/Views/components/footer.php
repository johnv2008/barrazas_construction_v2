<footer class="site-footer" id="contact">
  <div class="container">
    <div class="site-footer__grid">
      <div class="site-footer__brand-block">
        <img
          src="<?= e(asset('images/brand/logo-full-dark.png')) ?>"
          alt="Barraza's Construction logo"
          class="site-footer__logo"
        >
        <p class="site-footer__desc">
          Residential construction and remodeling for the San Francisco Bay Area —
          experienced craftsmanship, clear communication, and attention to detail
          from first consultation to final walkthrough.
        </p>
        <div class="site-footer__social" aria-label="Social links">
          <a href="#" aria-label="Barraza's Construction on Instagram">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.5"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
          </a>
          <a href="#" aria-label="Barraza's Construction on Facebook">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 9h3V6h-3c-2 0-3.5 1.5-3.5 3.5V11H8v3h2.5v6h3v-6H16l.5-3h-3V9.5c0-.3.2-.5.5-.5z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </div>

      <div>
        <h4>Company</h4>
        <ul class="site-footer__links">
          <li><a href="#services">Services</a></li>
          <li><a href="#projects">Our Work</a></li>
          <li><a href="#process">Process</a></li>
          <li><a href="#about">About</a></li>
        </ul>
      </div>

      <div>
        <h4>Services</h4>
        <ul class="site-footer__links">
          <li><a href="#services">Kitchen Remodeling</a></li>
          <li><a href="#services">Bathroom Remodeling</a></li>
          <li><a href="#services">Whole-Home Renovation</a></li>
          <li><a href="#services">Additions &amp; ADUs</a></li>
        </ul>
      </div>

      <div>
        <h4>Get in Touch</h4>
        <ul class="site-footer__links">
          <li>San Francisco Bay Area, California</li>
          <li><a href="#contact">Request a Consultation</a></li>
        </ul>
        <p class="site-footer__note">Licensed, Bonded &amp; Insured.</p>
      </div>
    </div>

    <div class="site-footer__bottom">
      <span>&copy; <?= date('Y') ?> Barraza's Construction Inc. All rights reserved.</span>
      <div class="site-footer__legal">
        <a href="<?= e(base_url('privacy-policy')) ?>">Privacy Policy</a>
        <a href="<?= e(base_url('terms')) ?>">Terms</a>
      </div>
    </div>
  </div>
</footer>
