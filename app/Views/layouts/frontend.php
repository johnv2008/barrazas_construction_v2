<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? config('app.name')) ?></title>
  <meta name="description" content="<?= e($metaDescription ?? '') ?>">
  <link rel="canonical" href="<?= e(base_url(current_path())) ?>">

  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= e($title ?? config('app.name')) ?>">
  <meta property="og:description" content="<?= e($metaDescription ?? '') ?>">
  <meta property="og:url" content="<?= e(base_url(current_path())) ?>">

  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#17191d">

  <?php
    // Marks that scripting is available BEFORE first paint. The reveal
    // animations start at opacity:0, so they are scoped to .js — if this
    // script never runs (JS disabled, blocked, or app.js fails), every
    // element renders visible instead of an empty page.
  ?>
  <script nonce="<?= e(csp_nonce()) ?>">document.documentElement.className += ' js';</script>

  <link rel="stylesheet" href="<?= e(asset('css/variables.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/components.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/frontend.css')) ?>">

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
</body>
</html>
