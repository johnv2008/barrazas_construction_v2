<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Content\Catalog;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;

/**
 * Project detail pages — first-class content, not a lightbox.
 *
 * Signature gesture for this page type: construction documentation and
 * annotated details (DESIGN_SYSTEM.md §10.1). The page is built around
 * numbered notes tied to a materials-and-methods schedule, which is what
 * makes it work at every inventory tier — a project with one good
 * photograph and real scope data is still a legitimate record, because it
 * documents one job rather than promising a body of work.
 */
final class ProjectController extends Controller
{
    public function show(Request $request, array $params): void
    {
        $slug = (string) ($params['slug'] ?? '');
        $project = Catalog::project($slug);

        if ($project === null) {
            Response::abort(404, 'errors/404');
        }

        $service = $project['service_slug'] !== null
            ? Catalog::service($project['service_slug'])
            : null;

        // Sibling projects — everything else, minus this one.
        $related = array_values(array_filter(
            Catalog::projects(),
            static fn (array $p): bool => $p['slug'] !== $slug,
        ));
        $related = array_slice($related, 0, 3);

        $images = $project['images'] ?? [];
        $hero = Catalog::imageByRole($project, 'hero', 'during', 'detail', 'context');
        $supporting = array_values(array_filter(
            $images,
            static fn (array $i): bool => $i['image_path'] !== ($hero['image_path'] ?? null),
        ));

        $chapters = $this->chapters($supporting !== [], $related !== []);

        $this->view('frontend/project', [
            'title' => $project['title'] . " | Barraza's Construction",
            'metaDescription' => $project['short_description'],
            'ogImage' => $hero['image_path'] ?? null,
            'extraStyles' => ['css/service.css'],
            'pageSchema' => $this->schema($project, $hero),
            'chapters' => $chapters,
            'ch' => $this->numbers($chapters),
            'project' => $project,
            'service' => $service,
            'projectTypes' => Catalog::projectTypes(),
            'preselectType' => $service['form_project_type'] ?? '',
            'hero' => $hero,
            'supporting' => $supporting,
            'related' => $related,
            'specRows' => $this->specRows($project),
            // The service crumb DOES link — that page exists. "Projects"
            // does not yet, so it stays unlinked (see ServiceController).
            //
            // The final crumb is the PROJECT, not its type. It used to be
            // the project type, which is also the eyebrow rendered 40px
            // directly beneath it — so every project page showed the same
            // two words stacked twice and read as a template bug. A
            // breadcrumb's last item is where you are, which is this
            // project, not its category.
            'breadcrumb' => array_values(array_filter([
                ['label' => 'Home', 'href' => '/'],
                $service !== null
                    ? ['label' => $service['title'], 'href' => '/services/' . $service['slug']]
                    : null,
                ['label' => $project['short_title'] ?? $project['title'], 'href' => null],
            ])),
        ], 'layouts/frontend');
    }

    /**
     * The spec table, built to omit rather than to fill.
     *
     * `completion_year` and `duration_weeks` are null on every seeded
     * project because the real values are not known. Rather than printing
     * "Varies" or "TBD" — which is worse than silence for both trust and
     * structured data — the row is simply not produced. This is the
     * mechanism the homepage build flagged as missing when the storyboard
     * wanted a Duration row and there was no data for it.
     */
    private function specRows(array $project): array
    {
        $candidates = [
            'Scope' => $project['scope'] ?? null,
            'Materials' => $project['materials'] ?? null,
            'Type' => $project['project_type'] ?? null,
            'Location' => $project['city'] ?? null,
            'Duration' => isset($project['duration_weeks']) && $project['duration_weeks'] !== null
                ? $project['duration_weeks'] . ' weeks'
                : null,
            'Completed' => isset($project['completion_year']) && $project['completion_year'] !== null
                ? (string) $project['completion_year']
                : null,
        ];

        $rows = [];

        foreach ($candidates as $label => $value) {
            if ($value !== null && trim((string) $value) !== '') {
                $rows[] = ['label' => $label, 'value' => (string) $value];
            }
        }

        return $rows;
    }

    private function chapters(bool $documentation, bool $related): array
    {
        $chapters = [
            ['id' => 'top', 'label' => 'The project'],
            ['id' => 'record', 'label' => 'Record'],
        ];

        if ($documentation) {
            $chapters[] = ['id' => 'documentation', 'label' => 'Documentation'];
        }

        if ($related) {
            $chapters[] = ['id' => 'projects', 'label' => 'Other work'];
        }

        $chapters[] = ['id' => 'contact', 'label' => 'Consultation'];

        foreach ($chapters as $i => $chapter) {
            $chapters[$i]['n'] = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
        }

        return $chapters;
    }

    private function numbers(array $chapters): array
    {
        $map = [];

        foreach ($chapters as $chapter) {
            $map[$chapter['id']] = $chapter['n'];
        }

        return $map;
    }

    /**
     * CreativeWork is the honest type for a documented job: it is not an
     * Offer, a Product, or a Review, and claiming any of those would be
     * fabricating commercial data the site does not have.
     */
    private function schema(array $project, ?array $hero): array
    {
        $url = base_url('projects/' . $project['slug']);

        $node = [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $project['title'],
            'description' => $project['short_description'],
            'url' => $url,
            'creator' => [
                '@type' => 'GeneralContractor',
                'name' => "Barraza's Construction",
                'url' => base_url('/'),
            ],
            'locationCreated' => [
                '@type' => 'Place',
                'name' => $project['city'],
            ],
        ];

        if ($hero !== null) {
            $node['image'] = base_url(ltrim(asset($hero['image_path']), '/'));
        }

        // Home → Service → Project. Every item resolves to a real page;
        // /projects is omitted until that index exists.
        $crumbs = [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => base_url('/')],
        ];

        if ($project['service_slug'] !== null && Catalog::service($project['service_slug']) !== null) {
            $crumbs[] = [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => Catalog::service($project['service_slug'])['title'],
                'item' => base_url('services/' . $project['service_slug']),
            ];
        }

        $crumbs[] = [
            '@type' => 'ListItem',
            'position' => count($crumbs) + 1,
            'name' => $project['title'],
            'item' => $url,
        ];

        return [
            $node,
            [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $crumbs,
            ],
        ];
    }
}
