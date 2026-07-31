<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? config('app.name')) ?></title>
  <meta name="description" content="<?= e($metaDescription ?? '') ?>">
  <link rel="canonical" href="<?= e(base_url(current_path())) ?>">

  <?php \App\Core\View::component('head-icons'); ?>

  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= e($title ?? config('app.name')) ?>">
  <meta property="og:description" content="<?= e($metaDescription ?? '') ?>">
  <meta property="og:url" content="<?= e(base_url(current_path())) ?>">
  <?php
    // Optional per-page share image. Absent on the homepage, which had no
    // og:image before this and still does not — adding one there would be
    // a homepage change, and the homepage is the frozen reference standard.
    if (!empty($ogImage)):
  ?>
  <meta property="og:image" content="<?= e(base_url(ltrim(asset($ogImage), '/'))) ?>">
  <meta name="twitter:card" content="summary_large_image">
  <?php endif; ?>

  <meta name="robots" content="index, follow">

  <?php
    // Marks that scripting is available BEFORE first paint. The reveal
    // animations start at opacity:0, so they are scoped to .js — if this
    // script never runs (JS disabled, blocked, or app.js fails), every
    // element renders visible instead of an empty page.
  ?>
  <script nonce="<?= e(csp_nonce()) ?>">document.documentElement.className += ' js';</script>

  <?php
    // Fonts are self-hosted (CSP font-src 'self'), so there is no
    // third-party origin to preconnect to. These two carry the first
    // screen — General Sans the headline, Inter every word under it —
    // and without a preload they are not discovered until the CSS has
    // parsed, which lands them after first paint and shows a visible
    // swap on the largest text on the page.
    //
    // crossorigin is required even though these are same-origin: fonts
    // are always fetched in CORS mode, and a preload whose mode does not
    // match the real request is discarded and fetched twice.
    //
    // Fraunces is deliberately NOT preloaded. It appears twice, both
    // below the fold, and preloading it would compete with the two
    // fonts that are on the critical path.
  ?>
  <link rel="preload" href="<?= e(asset('fonts/general-sans-700.woff2')) ?>" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="<?= e(asset('fonts/inter-latin-var.woff2')) ?>" as="font" type="font/woff2" crossorigin>

  <link rel="stylesheet" href="<?= e(asset('css/variables.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/components.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/frontend.css')) ?>">
  <?php
    // Per-page stylesheets, loaded AFTER frontend.css so a page type can
    // extend the approved language without editing it. Service and project
    // pages use this for their own chapters; the homepage passes nothing,
    // so its rendering is byte-identical to the approved reference.
    foreach (($extraStyles ?? []) as $extraStyle):
  ?>
  <link rel="stylesheet" href="<?= e(asset($extraStyle)) ?>">
  <?php endforeach; ?>

  <?php
    // LocalBusiness / GeneralContractor structured data. Only fields we
    // actually know are populated — no invented license numbers,
    // ratings, or review counts (see project brief).
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'GeneralContractor',
        'name' => "Barraza's Construction",
        'url' => base_url('/'),
        'areaServed' => [
            '@type' => 'AdministrativeArea',
            'name' => 'Tuolumne County, California',
        ],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => '16561 Jacksonville Rd',
            'addressLocality' => 'Jamestown',
            'addressRegion' => 'CA',
            'postalCode' => '95327',
            'addressCountry' => 'US',
        ],
        'sameAs' => [
            'https://share.google/IZSCTK6fzXc9Gi9wm',
        ],
    ];

    // Only emitted once a real number is configured — an empty or
    // placeholder credential is worse than none for both trust and
    // structured-data validity.
    if (($licenseNumber = trim((string) config('business.license_number', ''))) !== '') {
        $structuredData['hasCredential'] = [
            '@type' => 'EducationalOccupationalCredential',
            'credentialCategory' => 'license',
            'identifier' => $licenseNumber,
            'recognizedBy' => [
                '@type' => 'GovernmentOrganization',
                'name' => 'California Contractors State License Board',
                'url' => 'https://www.cslb.ca.gov/',
            ],
        ];
    }
  ?>
  <script type="application/ld+json" nonce="<?= e(csp_nonce()) ?>"><?= json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?></script>
  <?php
    // Additional per-page nodes (Service, CreativeWork, FAQPage,
    // BreadcrumbList). Emitted as separate scripts rather than merged into
    // the node above, so the business identity stays one canonical object
    // that every page references rather than redefines.
    foreach (($pageSchema ?? []) as $schemaNode):
  ?>
  <script type="application/ld+json" nonce="<?= e(csp_nonce()) ?>"><?= json_encode($schemaNode, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?></script>
  <?php endforeach; ?>
</head>
<body>
  <a href="#main-content" class="skip-link">Skip to content</a>

  <?php \App\Core\View::component('header'); ?>
  <?php \App\Core\View::component('datum', ['chapters' => $chapters ?? []]); ?>

  <main id="main-content">
    <?= $content ?>
  </main>

  <?php \App\Core\View::component('footer'); ?>

  <script src="<?= e(asset('js/app.js')) ?>" defer></script>

  <?php \App\Core\View::component('analytics'); ?>
</body>
</html>
