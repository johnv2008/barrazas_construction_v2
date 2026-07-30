<?php
/**
 * Project detail template — first-class content, not a lightbox.
 *
 * SIGNATURE GESTURE for this page type (DESIGN_SYSTEM.md §10.1):
 * construction documentation and annotated details. The page is built
 * around numbered notes tied to the photographs and to a spec schedule.
 *
 * Why that gesture suits this page type specifically: it works at ANY
 * inventory tier. A project with one photograph and real scope, materials
 * and method notes is still a complete record, because the page documents
 * one job rather than promising a body of work. The notes carry the page
 * when the photography cannot — which is the situation for three of the
 * four seeded projects, and will be the situation for most jobs until the
 * photography brief is adopted.
 *
 * Deliberately absent: the homepage's type-over-photograph slab, the
 * Portfolio's contact-sheet spread, and any edge-bleed. This page earns its
 * character from annotation density, not from a bleed.
 *
 * @var array $project
 * @var array|null $service
 * @var array|null $hero
 * @var array $supporting   images other than the hero
 * @var array $related
 * @var array $specRows     pre-filtered — empty values never reach here
 * @var array $breadcrumb
 * @var array $ch
 */
$notes = $project['notes'] ?? [];
?>

<!-- ============ 01 · The project =========================================
     Record header. Copy left, photograph right, spec schedule beneath the
     copy so the facts sit next to the claim. -->
<section class="ch ch--prj-arrival" id="top" data-chapter="<?= e($ch['top']) ?>" data-chapter-label="The project">
  <div class="container prj-arrival">
    <div class="prj-arrival__copy">
      <?php \App\Core\View::component('breadcrumb', ['breadcrumb' => $breadcrumb]); ?>
      <span class="eyebrow eyebrow--accent"><?= e($project['project_type']) ?></span>
      <h1 class="prj-arrival__heading" data-reveal><?= e($project['title']) ?></h1>
      <p class="prj-arrival__lead" data-reveal><?= e($project['short_description']) ?></p>
    </div>

    <?php if ($hero !== null): ?>
      <figure class="prj-arrival__media" data-reveal-media>
        <?= responsive_image($hero['image_path'], [
              'alt'      => $hero['alt_text'] ?? '',
              'priority' => true,
              'sizes'    => '(min-width: 1024px) 52vw, 100vw',
        ]) ?>
        <?php if (!empty($hero['caption'])): ?>
          <figcaption class="credit"><?= e($hero['caption']) ?></figcaption>
        <?php endif; ?>
      </figure>
    <?php endif; ?>
  </div>
</section>


<!-- ============ 02 · The record ==========================================
     Narrative plus the spec schedule. Rows with no known value were
     dropped by the controller rather than filled with "TBD". -->
<section class="ch ch--prj-record" id="record" data-chapter="<?= e($ch['record']) ?>" data-chapter-label="Record">
  <div class="container prj-record">
    <div class="prj-record__narrative">
      <span class="eyebrow eyebrow--accent">The record</span>
      <p data-reveal><?= e($project['full_description']) ?></p>

      <?php if (!empty($project['disclosure'])): ?>
        <p class="disclosure" data-reveal><?= e($project['disclosure']) ?></p>
      <?php endif; ?>
    </div>

    <div class="prj-record__spec">
      <?php \App\Core\View::component('spec-table', ['rows' => $specRows, 'tight' => true]); ?>

      <?php if ($service !== null): ?>
        <a href="<?= e('/services/' . $service['slug']) ?>" class="link-arrow">
          More about <?= e(strtolower($service['title'])) ?>
        </a>
      <?php endif; ?>
    </div>
  </div>
</section>


<!-- ============ 03 · Documentation =======================================
     ★ THE SIGNATURE for this page type. Numbered notes, each tied to
     something visible, alongside the remaining photography. Renders when
     there is either a second photograph or notes to make — so it survives
     a one-photograph project. -->
<?php if ($supporting !== [] || $notes !== []): ?>
<section class="ch ch--prj-doc" id="documentation" data-chapter="<?= e($ch['documentation'] ?? '03') ?>" data-chapter-label="Documentation">
  <div class="container prj-doc">
    <div class="section-head">
      <span class="eyebrow eyebrow--accent">Documentation</span>
      <h2 data-reveal>What to look at, and why it was done that way.</h2>
    </div>

    <div class="prj-doc__body">
      <?php if ($notes !== []): ?>
        <ol class="notes">
          <?php foreach ($notes as $note): ?>
            <li class="notes__item" data-reveal>
              <span class="notes__num"><?= e($note['n']) ?></span>
              <div class="notes__text">
                <h3 class="notes__title"><?= e($note['title']) ?></h3>
                <p class="notes__body"><?= e($note['body']) ?></p>
              </div>
            </li>
          <?php endforeach; ?>
        </ol>
      <?php endif; ?>

      <?php if ($supporting !== []): ?>
        <div class="prj-doc__media">
          <?php foreach ($supporting as $image): ?>
            <figure class="prj-doc__shot" data-reveal-media>
              <?= responsive_image($image['image_path'], [
                    'alt'   => $image['alt_text'] ?? '',
                    'sizes' => '(min-width: 1024px) 34vw, 92vw',
              ]) ?>
              <?php if (!empty($image['caption'])): ?>
                <figcaption class="credit"><?= e($image['caption']) ?></figcaption>
              <?php endif; ?>
            </figure>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ============ 04 · Other work ==========================================
     Editorial, unequal. Not a contact sheet. -->
<?php if ($related !== []): ?>
<section class="ch ch--prj-projects" id="projects" data-chapter="<?= e($ch['projects'] ?? '04') ?>" data-chapter-label="Other work">
  <div class="container">
    <?php /* h2 for the same reason as the service page's filing label. */ ?>
    <h2 class="filing">Other work — Tuolumne County</h2>

    <div class="pgrid">
      <?php foreach ($related as $i => $relatedProject): ?>
        <?php \App\Core\View::component('project-card', [
            'project' => $relatedProject,
            'size' => $i === 0 ? 'lead' : 'support',
        ]); ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ============ 05 · Consultation ========================================
     The same ending as a service page, reusing .ch--begin verbatim. Every
     page in the site funnels into one identical, already-approved form. -->
<section class="ch ch--begin" id="contact" data-chapter="<?= e($ch['contact']) ?>" data-chapter-label="Consultation">
  <div class="begin__bg" aria-hidden="true">
    <?= responsive_image('images/projects/hero-exterior.jpg', [
          'decorative' => true,
          'sizes'      => '100vw',
    ]) ?>
  </div>

  <div class="container begin">
    <div class="begin__copy">
      <span class="eyebrow eyebrow--accent">Start a conversation</span>
      <h2 data-reveal>Want something like this?</h2>
      <p class="begin__body" data-reveal>
        Tell us about the room. We will look at it, tell you what it involves,
        and put a number in writing.
      </p>
      <?php if (license_line() !== ''): ?>
        <p class="begin__license"><?= e(license_line()) ?></p>
      <?php endif; ?>
    </div>

    <div class="begin__form">
      <?php \App\Core\View::component('project-form', [
          'projectTypes' => $projectTypes ?? [],
          'preselect' => $preselectType ?? '',
      ]); ?>
    </div>
  </div>
</section>
