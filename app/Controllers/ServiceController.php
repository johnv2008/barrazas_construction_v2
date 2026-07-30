<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Content\Catalog;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;

/**
 * Service pages — the master template every service inherits.
 *
 * The controller's whole job is to assemble a chapter list and hand the
 * view content that is already shaped like a database row. It deliberately
 * contains no copy: swapping Catalog for a repository backed by the
 * `services` / `service_sections` / `service_faqs` tables should require
 * no change here and none in the view.
 *
 * Chapters are built conditionally. A service whose inventory tier does
 * not support the featured-transformation chapter simply does not get one
 * — and, importantly, does not get a numbered gap either: numbering is
 * derived from the chapters that actually render, so a Tier C page reads
 * 01-07 rather than 01-09 with holes. See DESIGN_SYSTEM.md §8.1.
 */
final class ServiceController extends Controller
{
    public function show(Request $request, array $params): void
    {
        $slug = (string) ($params['slug'] ?? '');
        $service = Catalog::service($slug);

        if ($service === null) {
            Response::abort(404, 'errors/404');
        }

        $sections = Catalog::sections($slug);
        $faqs = Catalog::faqs($slug);
        $featured = $service['featured_project_slug'] !== null
            ? Catalog::project($service['featured_project_slug'])
            : null;
        $related = Catalog::projectsBySlugs($service['related_project_slugs'] ?? []);

        // Tier gate for the featured-transformation chapter. Two images of
        // one room is enough for a diptych; one is enough for a single
        // lead; none removes the chapter rather than thinning it.
        $featuredImageCount = $featured !== null ? count($featured['images'] ?? []) : 0;
        $hasTransformation = $featured !== null && $featuredImageCount >= 1;

        $materials = $sections['materials_item'] ?? [];
        $hasMaterials = $materials !== [];

        $chapters = $this->chapters($hasTransformation, $hasMaterials, $faqs !== [], $related !== []);

        $this->view('frontend/service', [
            'title' => $service['meta_title'],
            'metaDescription' => $service['meta_description'],
            'ogImage' => $service['hero_image_path'],
            'extraStyles' => ['css/service.css'],
            'pageSchema' => $this->schema($service, $faqs),
            'chapters' => $chapters,
            'ch' => $this->numbers($chapters),
            'service' => $service,
            'projectTypes' => Catalog::projectTypes(),
            'sections' => $sections,
            'faqs' => $faqs,
            'featured' => $featured,
            'hasTransformation' => $hasTransformation,
            'related' => $related,
            // "Services" is deliberately unlinked: the index page does not
            // exist yet, and a breadcrumb link to a 404 is worse than a
            // breadcrumb without one. It gains an href when /services ships.
            'breadcrumb' => [
                ['label' => 'Home', 'href' => '/'],
                ['label' => 'Services', 'href' => null],
                ['label' => $service['title'], 'href' => null],
            ],
        ], 'layouts/frontend');
    }

    /**
     * Only chapters that will actually render. The Datum rail reads this,
     * so its ticks always match the page.
     */
    private function chapters(bool $transformation, bool $materials, bool $faqs, bool $related): array
    {
        $chapters = [
            ['id' => 'top', 'label' => 'Arrival'],
            ['id' => 'why', 'label' => 'Why it matters'],
        ];

        if ($transformation) {
            $chapters[] = ['id' => 'transformation', 'label' => 'Transformation'];
        }

        $chapters[] = ['id' => 'changes', 'label' => 'What changes'];
        $chapters[] = ['id' => 'process', 'label' => 'Process'];

        if ($materials) {
            $chapters[] = ['id' => 'materials', 'label' => 'Materials'];
        }

        if ($faqs) {
            $chapters[] = ['id' => 'questions', 'label' => 'Questions'];
        }

        if ($related) {
            $chapters[] = ['id' => 'projects', 'label' => 'Projects'];
        }

        $chapters[] = ['id' => 'contact', 'label' => 'Consultation'];

        foreach ($chapters as $i => $chapter) {
            $chapters[$i]['n'] = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
        }

        return $chapters;
    }

    /** id => two-digit number, so views never hardcode a chapter number. */
    private function numbers(array $chapters): array
    {
        $map = [];

        foreach ($chapters as $chapter) {
            $map[$chapter['id']] = $chapter['n'];
        }

        return $map;
    }

    /**
     * Service + FAQPage + BreadcrumbList. The layout emits the
     * GeneralContractor node; this references it as the provider rather
     * than duplicating the business details.
     *
     * Nothing is fabricated: no aggregateRating, no priceRange, no offers.
     * FAQPage is only emitted with at least two real pairs.
     */
    private function schema(array $service, array $faqs): array
    {
        $url = base_url('services/' . $service['slug']);

        $nodes = [[
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service['title'],
            'serviceType' => $service['title'],
            'description' => $service['summary'],
            'url' => $url,
            'provider' => [
                '@type' => 'GeneralContractor',
                'name' => "Barraza's Construction",
                'url' => base_url('/'),
            ],
            'areaServed' => [
                '@type' => 'AdministrativeArea',
                'name' => 'Tuolumne County, California',
            ],
        ]];

        // Two levels only. A ListItem pointing at /services would advertise
        // a URL that 404s; the level is added when that index page ships.
        $nodes[] = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => base_url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $service['title'], 'item' => $url],
            ],
        ];

        if (count($faqs) >= 2) {
            $nodes[] = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array_map(static fn (array $faq): array => [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
                ], $faqs),
            ];
        }

        return $nodes;
    }
}
