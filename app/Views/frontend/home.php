<?php
/**
 * Homepage — eight chapters.
 *
 * Emotional order is Dream -> Proof -> Trust -> Consultation. Finished
 * work leads; process substantiates it and never replaces it. No two
 * adjacent chapters share ground tone, axis, and density.
 *
 * All copy is server-rendered. The motion layer in app.js only reveals
 * markup that is already in the DOM — nothing here depends on JS to be
 * readable, and nothing is injected by it.
 *
 * @var array $chapters
 * @var array $hero
 * @var array $philosophy
 * @var array $middle
 * @var array $craft
 * @var array $work
 * @var array $trust
 * @var array $process
 * @var array $consultation
 * @var array $projectTypes
 */
?>

<!-- ============ 01 · Arrival ============================================
     Aspiration first. One authentic completed project, large. -->
<section class="ch ch--arrival" id="top" data-chapter="01" data-chapter-label="Arrival">
  <div class="arrival">
    <div class="arrival__copy">
      <span class="eyebrow eyebrow--accent"><?= e($hero['eyebrow']) ?></span>
      <h1 class="arrival__heading" data-reveal><?= e($hero['heading']) ?></h1>
      <p class="arrival__lead" data-reveal><?= e($hero['lead']) ?></p>
      <div class="arrival__actions" data-reveal>
        <a href="<?= e($hero['primaryCta']['href']) ?>" class="btn btn-primary"><?= e($hero['primaryCta']['label']) ?></a>
        <a href="<?= e($hero['secondaryCta']['href']) ?>" class="link-arrow"><?= e($hero['secondaryCta']['label']) ?></a>
      </div>
    </div>

    <figure class="arrival__media" data-reveal-media>
      <img src="<?= e($hero['image']) ?>" alt="<?= e($hero['imageAlt']) ?>" fetchpriority="high" decoding="async" width="1200" height="1600">
      <figcaption class="plate">
        <span class="plate__label"><?= e($hero['plate']['label']) ?></span>
        <span class="plate__title"><?= e($hero['plate']['title']) ?></span>
        <span class="plate__meta"><?= e($hero['plate']['meta']) ?></span>
      </figcaption>
    </figure>
  </div>

  <p class="arrival__trust">
    <span><?= e($hero['since']) ?></span>
    <span class="arrival__trust-sep" aria-hidden="true"></span>
    <span><?= e($hero['trust']) ?></span>
  </p>
</section>


<!-- ============ 02 · Approach ===========================================
     Dark, quiet, largely empty. Maximum contrast after the hero. -->
<section class="ch ch--approach" id="about" data-chapter="02" data-chapter-label="Approach">
  <div class="container approach">
    <div class="approach__copy">
      <span class="eyebrow eyebrow--accent"><?= e($philosophy['eyebrow']) ?></span>
      <h2 class="approach__heading" data-reveal><?= e($philosophy['heading']) ?></h2>
      <p class="approach__body" data-reveal><?= e($philosophy['body']) ?></p>
    </div>
    <figure class="approach__media" data-reveal-media>
      <img src="<?= e($philosophy['image']) ?>" alt="<?= e($philosophy['imageAlt']) ?>" loading="lazy" decoding="async" width="450" height="600">
      <figcaption><?= e($philosophy['caption']) ?></figcaption>
    </figure>
  </div>
</section>


<!-- ============ 03 · The Middle Is the Proof ============================
     The signature chapter, held to roughly 2.2 viewports. The frame is
     pinned with position:sticky — the browser's own scroll is never
     intercepted. All three states ship in the HTML; inactive ones are
     marked inert so screen readers announce one at a time. -->
<section class="ch ch--proof" id="proof" data-chapter="03" data-chapter-label="The Middle">
  <div class="container">
    <div class="proof__head">
      <span class="eyebrow eyebrow--accent"><?= e($middle['eyebrow']) ?></span>
      <h2 data-reveal><?= e($middle['heading']) ?></h2>
      <p class="proof__lead" data-reveal><?= e($middle['lead']) ?></p>
    </div>

    <div class="proof__track" data-proof>
      <div class="proof__stage">
        <div class="proof__frame">
          <?php foreach ($middle['states'] as $i => $state): ?>
            <figure class="proof__shot<?= $i === 0 ? ' is-active' : '' ?>" data-proof-shot="<?= e((string) $i) ?>"<?= $i === 0 ? '' : ' inert' ?>>
              <img src="<?= e($state['image']) ?>" alt="<?= e($state['imageAlt']) ?>" loading="lazy" decoding="async">
            </figure>
          <?php endforeach; ?>
        </div>

        <div class="proof__panel">
          <ol class="proof__ruler">
            <?php foreach ($middle['states'] as $i => $state): ?>
              <li class="proof__step<?= $i === 0 ? ' is-active' : '' ?>" data-proof-step="<?= e((string) $i) ?>"><?= e($state['label']) ?></li>
            <?php endforeach; ?>
          </ol>

          <?php foreach ($middle['states'] as $i => $state): ?>
            <div class="proof__text<?= $i === 0 ? ' is-active' : '' ?>" data-proof-text="<?= e((string) $i) ?>"<?= $i === 0 ? '' : ' inert' ?>>
              <h3><?= e($state['title']) ?></h3>
              <p><?= e($state['body']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ============ 04 · What We Build ======================================
     Five services, three distinct row layouts. Never a card grid. -->
<section class="ch ch--craft" id="services" data-chapter="04" data-chapter-label="What We Build">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow eyebrow--accent"><?= e($craft['eyebrow']) ?></span>
      <h2 data-reveal><?= e($craft['heading']) ?></h2>
    </div>

    <div class="craft">
      <?php foreach ($craft['items'] as $item): ?>
        <?php \App\Core\View::component('craft-row', ['item' => $item]); ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ============ 05 · Selected Work ======================================
     Editorial spread plus a deliberately uneven tile composition. -->
<section class="ch ch--work" id="projects" data-chapter="05" data-chapter-label="Selected Work">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow eyebrow--accent"><?= e($work['eyebrow']) ?></span>
      <h2 data-reveal><?= e($work['heading']) ?></h2>
    </div>

    <?php $f = $work['featured']; ?>
    <article class="feature">
      <div class="feature__media">
        <figure class="feature__main" data-reveal-media>
          <img src="<?= e($f['image']) ?>" alt="<?= e($f['imageAlt']) ?>" loading="lazy" decoding="async" width="1200" height="1600">
        </figure>
        <figure class="feature__detail" data-reveal-media>
          <img src="<?= e($f['detail']) ?>" alt="<?= e($f['detailAlt']) ?>" loading="lazy" decoding="async" width="450" height="600">
        </figure>
      </div>

      <div class="feature__copy">
        <span class="eyebrow eyebrow--accent"><?= e($f['label']) ?></span>
        <h3 data-reveal><?= e($f['title']) ?></h3>
        <p data-reveal><?= e($f['body']) ?></p>
        <dl class="feature__meta">
          <?php foreach ($f['meta'] as $row): ?>
            <div>
              <dt><?= e($row['label']) ?></dt>
              <dd><?= e($row['value']) ?></dd>
            </div>
          <?php endforeach; ?>
        </dl>
        <a href="<?= e($f['href']) ?>" class="link-arrow">Talk about a project like this</a>
      </div>
    </article>

    <div class="tiles">
      <?php foreach ($work['tiles'] as $tile): ?>
        <figure class="tile tile--<?= e($tile['size']) ?>" data-reveal-media>
          <img src="<?= e($tile['image']) ?>" alt="<?= e($tile['imageAlt']) ?>" loading="lazy" decoding="async">
          <figcaption>
            <span class="tile__title"><?= e($tile['title']) ?></span>
            <span class="tile__meta"><?= e($tile['meta']) ?></span>
          </figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ============ 06 · Credentials ========================================
     Said once, with numbers attached. No badges, no icon grid. -->
<section class="ch ch--trust" id="trust" data-chapter="06" data-chapter-label="Credentials">
  <div class="container trust">
    <div class="trust__copy">
      <span class="eyebrow eyebrow--accent"><?= e($trust['eyebrow']) ?></span>
      <h2 data-reveal><?= e($trust['heading']) ?></h2>
      <p class="trust__body" data-reveal><?= e($trust['body']) ?></p>
    </div>
    <div class="trust__evidence">
      <dl class="trust__list" data-reveal>
        <?php foreach ($trust['items'] as $item): ?>
          <div class="trust__row">
            <dt><?= e($item['label']) ?></dt>
            <dd><?= e($item['value']) ?></dd>
          </div>
        <?php endforeach; ?>
      </dl>

      <figure class="trust__media" data-reveal-media>
        <img src="<?= e($trust['image']) ?>" alt="<?= e($trust['imageAlt']) ?>" loading="lazy" decoding="async" width="450" height="600">
        <figcaption><?= e($trust['caption']) ?></figcaption>
      </figure>
    </div>
  </div>
</section>


<!-- ============ 07 · What Happens =======================================
     The only horizontal moment. Static fit, no pin, no interception. -->
<section class="ch ch--process" id="process" data-chapter="07" data-chapter-label="What Happens">
  <div class="container">
    <div class="section-head section-head--split">
      <div>
        <span class="eyebrow eyebrow--accent"><?= e($process['eyebrow']) ?></span>
        <h2 data-reveal><?= e($process['heading']) ?></h2>
      </div>
    </div>

    <ol class="track" data-reveal-group>
      <?php foreach ($process['steps'] as $step): ?>
        <li class="track__step" data-reveal>
          <span class="track__num"><?= e($step['n']) ?></span>
          <h3><?= e($step['title']) ?></h3>
          <p><?= e($step['body']) ?></p>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>


<!-- ============ 08 · Begin =============================================
     Dark, warm photography, generous spacing. The form is the chapter. -->
<section class="ch ch--begin" id="contact" data-chapter="08" data-chapter-label="Begin">
  <div class="begin__bg" aria-hidden="true">
    <img src="<?= e($consultation['image']) ?>" alt="" loading="lazy" decoding="async">
  </div>

  <div class="container begin">
    <div class="begin__copy">
      <span class="eyebrow eyebrow--accent"><?= e($consultation['eyebrow']) ?></span>
      <h2 data-reveal><?= e($consultation['heading']) ?></h2>
      <p class="begin__body" data-reveal><?= e($consultation['body']) ?></p>
      <?php if (license_line() !== ''): ?>
        <p class="begin__license"><?= e(license_line()) ?></p>
      <?php endif; ?>
    </div>

    <div class="begin__form">
      <?php \App\Core\View::component('project-form', ['projectTypes' => $projectTypes]); ?>
    </div>
  </div>
</section>
