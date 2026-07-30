<?php
/**
 * Master service page template.
 *
 * Every service inherits this file. Nothing here is Kitchen-specific: the
 * copy, the photography, the FAQ set and the inventory tier all arrive as
 * data, so a second service is a content record rather than a new template.
 *
 * CHAPTER NUMBERS come from $ch[id] rather than being written in. A service
 * whose tier removes a chapter reads 01-07 with no gaps, instead of 01-09
 * with holes — see ServiceController::chapters().
 *
 * WHAT MAKES THIS NOT LOOK TEMPLATED
 *   - Ground tone alternates and never repeats consecutively.
 *   - The axis is mirrored against the homepage hero (image left, copy
 *     right), so arrival reads as the same language the other way round
 *     rather than the same composition reused.
 *   - Chapter 04 reuses the approved craft-row roles, so five outcomes
 *     never resolve into five matching cards.
 *   - The page's ONE bleed is spent on its signature chapter (06 Materials
 *     for the Kitchen type), not on the hero. The homepage bleeds at 01.
 *   - Type crossing a photograph is absent: permanently reserved to the
 *     homepage (DESIGN_SYSTEM.md §10.1).
 *
 * @var array $service
 * @var array $sections
 * @var array $faqs
 * @var array|null $featured
 * @var bool $hasTransformation
 * @var array $related
 * @var array $breadcrumb
 * @var array $ch        chapter id => two-digit number
 */
$why = $sections['why_it_matters'] ?? [];
$changes = $sections['what_changes'] ?? [];
$steps = $sections['process_step'] ?? [];
$materials = $sections['materials_item'] ?? [];

$featuredHero = $featured !== null
    ? \App\Content\Catalog::imageByRole($featured, 'hero', 'during', 'detail', 'context')
    : null;
?>

<!-- ============ 01 · Arrival ============================================
     Contained, and mirrored against the homepage hero. -->
<section class="ch ch--svc-arrival" id="top" data-chapter="<?= e($ch['top']) ?>" data-chapter-label="Arrival">
  <div class="container svc-arrival">
    <figure class="svc-arrival__media" data-reveal-media>
      <?= responsive_image($service['hero_image_path'], [
            'alt'      => $service['hero_image_alt'],
            'priority' => true,
            'sizes'    => '(min-width: 1024px) 46vw, 100vw',
      ]) ?>
      <figcaption class="plate">
        <span class="plate__label"><?= e($service['hero_caption_label']) ?></span>
        <span class="plate__title"><?= e($service['hero_caption_title']) ?></span>
        <span class="plate__meta"><?= e($service['hero_caption_meta']) ?></span>
      </figcaption>
    </figure>

    <div class="svc-arrival__copy">
      <?php \App\Core\View::component('breadcrumb', ['breadcrumb' => $breadcrumb]); ?>
      <span class="eyebrow eyebrow--accent"><?= e($service['eyebrow']) ?></span>
      <h1 class="svc-arrival__heading" data-reveal><?= e($service['h1_statement']) ?></h1>
      <p class="svc-arrival__lead" data-reveal><?= e($service['lead']) ?></p>
      <div class="svc-arrival__actions" data-reveal>
        <a href="#contact" class="btn btn-primary">Start your project</a>
        <?php if ($hasTransformation): ?>
          <?php
            // Was hardcoded to "See a finished kitchen", which read as
            // "See a finished kitchen" on the Bathroom page — the exact
            // failure mode a shared template invites. Service-supplied,
            // with a neutral fallback rather than a guess.
          ?>
          <a href="#transformation" class="link-arrow"><?= e($service['hero_cta_secondary'] ?? 'See a finished project') ?></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>


<!-- ============ 02 · Why This Room Matters ===============================
     Dark, very sparse, no image. Fraunces italic — 1 of 2 on this page. -->
<?php if ($why !== []): ?>
<section class="ch ch--svc-why" id="why" data-chapter="<?= e($ch['why']) ?>" data-chapter-label="Why it matters">
  <div class="container svc-why">
    <h2 class="svc-why__statement editorial-text" data-reveal><?= e($why['heading']) ?></h2>
    <p class="svc-why__body" data-reveal><?= e($why['body']) ?></p>
  </div>
</section>
<?php endif; ?>


<!-- ============ 03 · Featured Transformation =============================
     One project at depth. Removed entirely when the inventory cannot
     support it — thinned is worse than absent. -->
<?php if ($hasTransformation && $featuredHero !== null): ?>
<section class="ch ch--svc-featured" id="transformation" data-chapter="<?= e($ch['transformation']) ?>" data-chapter-label="Transformation">
  <div class="container svc-featured">
    <div class="section-head">
      <span class="eyebrow eyebrow--accent">Featured project</span>
      <h2 data-reveal><?= e($featured['title']) ?></h2>
    </div>

    <div class="svc-featured__body">
      <figure class="svc-featured__media" data-reveal-media>
        <?= responsive_image($featuredHero['image_path'], [
              'alt'   => $featuredHero['alt_text'] ?? '',
              'sizes' => '(min-width: 1024px) 52vw, 100vw',
        ]) ?>
        <?php if (!empty($featuredHero['caption'])): ?>
          <figcaption class="credit"><?= e($featuredHero['caption']) ?></figcaption>
        <?php endif; ?>
      </figure>

      <div class="svc-featured__copy">
        <p data-reveal><?= e($featured['full_description']) ?></p>
        <?php \App\Core\View::component('spec-table', [
            'rows' => array_values(array_filter([
                !empty($featured['scope']) ? ['label' => 'Scope', 'value' => $featured['scope']] : null,
                !empty($featured['materials']) ? ['label' => 'Materials', 'value' => $featured['materials']] : null,
                !empty($featured['city']) ? ['label' => 'Location', 'value' => $featured['city']] : null,
            ])),
            'tight' => true,
        ]); ?>
        <a href="<?= e('/projects/' . $featured['slug']) ?>" class="link-arrow">See how this one was built</a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ============ 04 · What Changes ========================================
     The dense list. Reuses the approved craft-row roles. -->
<?php if ($changes !== []): ?>
<section class="ch ch--svc-changes" id="changes" data-chapter="<?= e($ch['changes']) ?>" data-chapter-label="What changes">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow eyebrow--accent">What changes</span>
      <h2 data-reveal>What you actually get back.</h2>
    </div>

    <div class="craft">
      <?php foreach ($changes as $item): ?>
        <?php \App\Core\View::component('craft-row', ['item' => [
            'n' => $item['n'],
            'title' => $item['title'],
            'benefit' => $item['body'],
            'href' => $hasTransformation ? '#transformation' : '#contact',
            'ctaLabel' => $hasTransformation ? 'See this in a finished kitchen' : 'Ask about this',
            'image' => $item['image_path'],
            'imageAlt' => $item['image_alt'],
            'support' => null,
            'supportAlt' => '',
            'layout' => $item['role'],
        ]]); ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ============ 05 · Process =============================================
     Horizontal — the page's one axis break. Reuses the homepage track. -->
<?php if ($steps !== []): ?>
<section class="ch ch--svc-process" id="process" data-chapter="<?= e($ch['process']) ?>" data-chapter-label="Process">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow eyebrow--accent">How it goes</span>
      <h2 data-reveal>Five steps, and you will know which week is the hard one.</h2>
    </div>

    <ol class="track">
      <?php foreach ($steps as $step): ?>
        <li class="track__step" data-reveal>
          <span class="track__num"><?= e($step['n']) ?></span>
          <h3 class="track__title"><?= e($step['title']) ?></h3>
          <p class="track__body"><?= e($step['body']) ?></p>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
<?php endif; ?>


<!-- ============ 06 · Materials & Craftsmanship ===========================
     ★ THE SIGNATURE CHAPTER. Which composition renders is chosen by the
     service's registered gesture, never by taste and never at random —
     one gesture per page type, and no gesture appears on two
     (DESIGN_SYSTEM.md §10.1):

       material  → Kitchen. Horizontal band, unequal crops, lead bleeds
                   off the right edge.
       vertical  → Bathroom. Three tall portrait frames descending on a
                   broken baseline. No bleed; the lead already exceeds
                   the fold.

     A service with no registered signature falls back to the material
     band rather than inventing a seventh device. -->
<?php if ($materials !== []): ?>
<?php $signature = $service['signature'] ?? 'material'; ?>
<section class="ch ch--svc-materials ch--sig-<?= e($signature) ?>" id="materials" data-chapter="<?= e($ch['materials']) ?>" data-chapter-label="Materials">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow eyebrow--accent">Materials &amp; craftsmanship</span>
      <h2 data-reveal>
        <?= $signature === 'vertical'
            ? 'The parts you will never see again.'
            : 'What it is made of, and how it was done.' ?>
      </h2>
    </div>
  </div>

  <?php if ($signature === 'vertical'): ?>
    <?php \App\Core\View::component('vertical-band', ['items' => $materials]); ?>
  <?php else: ?>
    <?php \App\Core\View::component('material-band', ['items' => $materials]); ?>
  <?php endif; ?>
</section>
<?php endif; ?>


<!-- ============ 07 · Questions Homeowners Ask ============================
     Warm ground, native <details>, no JavaScript. -->
<?php if ($faqs !== []): ?>
<section class="ch ch--svc-questions" id="questions" data-chapter="<?= e($ch['questions']) ?>" data-chapter-label="Questions">
  <div class="container svc-questions">
    <div class="section-head">
      <span class="eyebrow eyebrow--accent">Questions homeowners ask</span>
      <h2 data-reveal>The things people ask before they call.</h2>
    </div>

    <?php \App\Core\View::component('faq-list', ['faqs' => $faqs]); ?>
  </div>
</section>
<?php endif; ?>


<!-- ============ 08 · Related Projects ====================================
     Editorial, unequal sizes. NOT the contact sheet — that spread is
     reserved to the Portfolio page type. -->
<?php if ($related !== []): ?>
<section class="ch ch--svc-projects" id="projects" data-chapter="<?= e($ch['projects']) ?>" data-chapter-label="Projects">
  <div class="container">
    <?php
      // An h2, not a <p>. The filing label is this section's heading — the
      // project titles below it are h3s, and a section landmark whose only
      // headings are h3 has no accessible name and reads as an orphaned
      // level. Styled as a filing reference rather than as a section title,
      // so it looks like a label and behaves like a heading.
    ?>
    <h2 class="filing">Also completed — Tuolumne County</h2>

    <div class="pgrid">
      <?php foreach ($related as $i => $relatedProject): ?>
        <?php \App\Core\View::component('project-card', [
            'project' => $relatedProject,
            'size' => $i === 0 ? 'lead' : 'support',
        ]); ?>
      <?php endforeach; ?>
    </div>

    <?php
      // Service → Projects → Related services → Consultation. Only
      // services that actually have a published page appear here, so this
      // can never link to a Tier D URL that does not exist.
      if (!empty($relatedServices)):
    ?>
      <p class="also">
        <span class="also__label">Often part of the same job</span>
        <?php foreach ($relatedServices as $sibling): ?>
          <a href="<?= e('/services/' . $sibling['slug']) ?>"><?= e($sibling['title']) ?></a>
        <?php endforeach; ?>
      </p>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>


<!-- ============ 09 · Consultation ========================================
     Reuses the homepage's approved .ch--begin chapter verbatim — the dark
     ground, the warm photographic wash, and the fifteen dark-ground form
     overrides that make the inputs legible on ink. Re-implementing any of
     that would be two versions of one component waiting to drift apart.

     The form component itself is untouched; the only addition is the
     preselected project type, which spares the visitor re-declaring what
     they have just spent a page reading about. -->
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
      <h2 data-reveal>Tell us what is not working.</h2>
      <p class="begin__body" data-reveal>
        No obligation and no sales visit. We will look at the room, tell you what it
        involves, and put a number in writing.
      </p>
      <?php if (license_line() !== ''): ?>
        <p class="begin__license"><?= e(license_line()) ?></p>
      <?php endif; ?>
    </div>

    <div class="begin__form">
      <?php \App\Core\View::component('project-form', [
          'projectTypes' => $projectTypes ?? [],
          'preselect' => $service['form_project_type'] ?? '',
      ]); ?>
    </div>
  </div>
</section>
