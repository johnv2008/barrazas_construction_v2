<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

final class HomeController extends Controller
{
    public function index(Request $request, array $params): void
    {
        $this->view('frontend/home', array_merge(
            [
                'title' => "Barraza's Construction | Bay Area Residential Remodeling & Renovation",
                'metaDescription' => 'Barraza\'s Construction delivers residential remodeling, additions, and complete home improvements throughout the San Francisco Bay Area — kitchens, bathrooms, whole-home renovations, and ADUs.',
            ],
            $this->homeData()
        ), 'layouts/frontend');
    }

    /**
     * Minimal XML sitemap. Lists only the homepage in Phase 1; extend
     * this as published pages/projects/services exist to list.
     */
    public function sitemap(Request $request, array $params): void
    {
        header('Content-Type: application/xml; charset=utf-8');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        echo '  <url><loc>' . e(base_url('/')) . '</loc></url>' . "\n";
        echo '</urlset>';
    }

    /**
     * Homepage content, structured to mirror the tables it will be
     * sourced from once the CMS modules exist:
     *   hero/manifesto/trustManifesto/planningGuide -> page_sections (page slug "home")
     *   services                                    -> services
     *   transformation/contactSheet/narrative        -> projects + project_images
     *   capabilities/blueprintSteps                  -> site_settings / page_sections
     * Swapping this method's body for real queries is the only change
     * a future phase needs to make.
     *
     * @return array<string, mixed>
     */
    private function homeData(): array
    {
        return array_merge(
            $this->heroAndCapabilities(),
            $this->transformationAndManifesto(),
            $this->servicesAndContactSheet(),
            $this->narrativeAndTrust(),
            $this->processAndPlanning(),
            $this->projectFormOptions()
        );
    }

    private function heroAndCapabilities(): array
    {
        return [
            'hero' => [
                'eyebrow' => "Barraza's Construction Inc.",
                'headingLines' => ['Building Better', 'Places to Live.'],
                'lead' => 'Residential remodeling, additions, and complete home improvements delivered with '
                    . 'experienced craftsmanship throughout the San Francisco Bay Area.',
                'primaryCta' => ['label' => 'Start Your Project', 'href' => '#start-your-project'],
                'secondaryCta' => ['label' => 'View Transformations', 'href' => '#projects'],
                'primaryImage' => asset('images/projects/hero-exterior.jpg'),
                'primaryAlt' => "Bay Area home exterior, a Barraza's Construction project",
                'insetImage' => asset('images/projects/hero-inset.jpg'),
                'insetAlt' => 'Marble walk-in shower detail from a completed remodel',
                'tag' => 'Residential Remodeling · Bay Area',
                'trust' => [
                    'Serving Bay Area homeowners since 2006',
                    'Licensed, bonded and insured',
                    'Residential remodeling specialists',
                ],
            ],
            'capabilities' => [
                'Serving the Bay Area Since 2006',
                'Kitchen Remodeling',
                'Bathroom Remodeling',
                'Whole-Home Renovations',
                'Additions',
                'ADUs',
                'Interior Improvements',
                'Exterior Improvements',
            ],
        ];
    }

    private function transformationAndManifesto(): array
    {
        return [
            'transformation' => [
                'progressImage' => asset('images/projects/transformation-progress.jpg'),
                'progressAlt' => 'Kitchen renovation in progress: original cabinetry still in place over newly installed flooring',
                'completeImage' => asset('images/projects/transformation-complete.jpg'),
                'completeAlt' => 'Completed kitchen remodel with white cabinetry, subway tile, and black hardware',
                'category' => 'Kitchen Remodeling',
                'title' => 'From Renovation to Reveal',
                'city' => 'San Francisco Bay Area',
                'summary' => 'New cabinetry, tile, and finishes over a rebuilt layout.',
                'href' => '#start-your-project',
                'note' => "These photos document Barraza's construction process and finished craftsmanship — not "
                    . 'a single continuous transformation of one room.',
            ],
            'manifesto' => [
                'line1' => "We don't just remodel homes.",
                'line2' => 'We rebuild how they feel.',
            ],
        ];
    }

    private function servicesAndContactSheet(): array
    {
        return [
            'services' => [
                [
                    'title' => 'Kitchen Remodeling',
                    'description' => 'Thoughtful layouts, durable materials, and finishes built for the way your '
                        . 'family actually cooks and gathers.',
                    'scope' => ['Cabinetry', 'Countertops', 'Layout Changes', 'Lighting'],
                    'primaryImage' => asset('images/projects/service-kitchen.jpg'),
                    'primaryAlt' => 'Renovated kitchen with white shaker cabinetry and marble backsplash',
                    'detailImage' => asset('images/projects/project-supporting-1.jpg'),
                    'detailAlt' => 'Kitchen sink and countertop detail',
                    'tag' => 'Kitchens',
                    'serviceHref' => '#projects',
                    'consultHref' => '#start-your-project',
                ],
                [
                    'title' => 'Bathroom Remodeling',
                    'description' => 'Spa-inspired bathrooms designed for comfort, durability, and everyday function.',
                    'scope' => ['Showers & Tubs', 'Tile Work', 'Vanities', 'Fixtures'],
                    'primaryImage' => asset('images/projects/service-bathroom.jpg'),
                    'primaryAlt' => 'Marble bathroom with black rainfall shower fixture',
                    'detailImage' => asset('images/projects/intro-detail.jpg'),
                    'detailAlt' => 'Glass-enclosed tub and shower with marble surround',
                    'tag' => 'Bathrooms',
                    'serviceHref' => '#projects',
                    'consultHref' => '#start-your-project',
                ],
                [
                    'title' => 'Whole-Home Renovations',
                    'description' => "Comprehensive renovations that update a home's structure, systems, and style "
                        . 'together.',
                    'scope' => ['Flooring', 'Interior Layouts', 'Fixtures', 'Finishes'],
                    'primaryImage' => asset('images/projects/service-wholehome-primary.jpg'),
                    'primaryAlt' => 'Renovated living room with refaced brick fireplace and new flooring',
                    'detailImage' => asset('images/projects/service-wholehome-detail.jpg'),
                    'detailAlt' => 'Kitchen and hallway with new stainless appliances and flooring',
                    'tag' => 'Whole-Home',
                    'serviceHref' => '#projects',
                    'consultHref' => '#start-your-project',
                ],
                [
                    'title' => 'Additions & ADUs',
                    'description' => 'Thoughtfully designed additions and accessory dwelling units that expand how '
                        . 'you live.',
                    'scope' => ['Room Additions', 'ADUs', 'Entryways', 'Roofing'],
                    'primaryImage' => asset('images/projects/service-additions.jpg'),
                    'primaryAlt' => 'New entry door installation on a covered porch',
                    'detailImage' => asset('images/projects/service-additions-detail.jpg'),
                    'detailAlt' => 'Roof replacement in progress',
                    'tag' => 'Additions',
                    'serviceHref' => '#projects',
                    'consultHref' => '#start-your-project',
                ],
            ],

            'contactSheet' => [
                [
                    'size' => 'landscape',
                    'image' => asset('images/projects/contact-sheet-kitchen.jpg'),
                    'alt' => 'Kitchen remodel with gray cabinetry and marble backsplash',
                    'title' => 'Kitchen Remodel',
                    'category' => 'Kitchens',
                    'city' => 'San Francisco Bay Area',
                    'scope' => 'Custom cabinetry and marble tile backsplash',
                ],
                [
                    'size' => 'portrait',
                    'image' => asset('images/projects/project-featured.jpg'),
                    'alt' => 'Primary bathroom remodel with marble tile tub and shower surround',
                    'title' => 'Primary Bathroom Remodel',
                    'category' => 'Bathrooms',
                    'city' => 'San Francisco Bay Area',
                    'scope' => 'Marble tile, glass enclosure, redesigned layout',
                ],
                [
                    'size' => 'landscape',
                    'image' => asset('images/projects/project-supporting-2.jpg'),
                    'alt' => 'Primary suite bathroom remodel with granite vanity and a large window',
                    'title' => 'Primary Suite Remodel',
                    'category' => 'Primary Suite',
                    'city' => 'San Francisco Bay Area',
                    'scope' => 'Dark granite vanity with a picture window',
                ],
                [
                    'size' => 'square',
                    'image' => asset('images/projects/hero-inset.jpg'),
                    'alt' => 'Marble walk-in shower with bench and hand shower',
                    'title' => 'Walk-In Shower Remodel',
                    'category' => 'Bathrooms',
                    'city' => 'San Francisco Bay Area',
                    'scope' => 'Full marble walk-in shower with bench',
                ],
                [
                    'size' => 'portrait',
                    'image' => asset('images/projects/contact-sheet-bath-tub.jpg'),
                    'alt' => 'Finished bathroom with tub, shower, and toilet',
                    'title' => 'Bathroom Renovation',
                    'category' => 'Bathrooms',
                    'city' => 'San Francisco Bay Area',
                    'scope' => 'Full fixture and finish replacement',
                ],
                [
                    'size' => 'square',
                    'image' => asset('images/projects/contact-sheet-bath-vanity.jpg'),
                    'alt' => 'Bathroom vanity with framed mirror and new lighting',
                    'title' => 'Vanity & Lighting Refresh',
                    'category' => 'Bathrooms',
                    'city' => 'San Francisco Bay Area',
                    'scope' => 'New vanity, mirror, and light fixture',
                ],
                [
                    'size' => 'detail',
                    'image' => asset('images/projects/contact-sheet-hillside-deck.jpg'),
                    'alt' => 'Hillside home deck with mountain view after exterior work',
                    'title' => 'Hillside Deck & Exterior',
                    'category' => 'Exteriors',
                    'city' => 'San Francisco Bay Area',
                    'scope' => 'Roof and deck renewal with a hillside view',
                ],
            ],
        ];
    }

    private function narrativeAndTrust(): array
    {
        return [
            'narrative' => [
                'eyebrow' => 'Featured Project',
                'heading' => 'A Hillside Home, Ready for the Bay Area Elements',
                'goal' => "Renew a hillside home's roof and outdoor living space to hold up to Bay Area weather "
                    . 'while opening up the surrounding views.',
                'workCompleted' => 'Full roof replacement, deck detailing, and exterior refresh, coordinated from '
                    . 'tear-off through final walkthrough.',
                'result' => 'A weather-ready roofline and a deck built for taking in the hillside view.',
                'resultImage' => asset('images/projects/narrative-result.jpg'),
                'resultAlt' => 'Hillside home deck with a view of the surrounding hills after exterior work',
                'progressImage' => asset('images/projects/narrative-progress.jpg'),
                'progressAlt' => 'Roof replacement in progress on the same hillside home',
                'relatedLabel' => 'Related: Additions & ADUs',
                'relatedHref' => '#services',
            ],

            'trustManifesto' => [
                'headline1' => 'Experienced Hands.',
                'headline2' => 'Clear Communication.',
                'headline3' => 'Work That Endures.',
                'items' => [
                    'Serving Bay Area homeowners since 2006',
                    'Licensed, bonded and insured',
                    'Residential remodeling experience across kitchens, bathrooms, and whole-home projects',
                    'Coordinated project planning from consultation through completion',
                    'Attention to final details before every handoff',
                ],
            ],
        ];
    }

    private function processAndPlanning(): array
    {
        return [
            'blueprintSteps' => [
                [
                    'title' => 'Initial Conversation',
                    'description' => 'We learn about the property, your goals, priorities, and expectations for the project.',
                ],
                [
                    'title' => 'Property Visit',
                    'description' => 'We walk the space in person to understand existing conditions before anything is planned on paper.',
                ],
                [
                    'title' => 'Scope & Estimate',
                    'description' => 'We define the scope of work and provide an estimate so you know what to expect before moving forward.',
                ],
                [
                    'title' => 'Planning & Permits',
                    'description' => 'We finalize plans and coordinate any required permits before construction begins.',
                    'detailImage' => asset('images/projects/process-detail.jpg'),
                    'detailAlt' => 'Bathroom mid-renovation with new tile flooring and rough-in plumbing',
                ],
                [
                    'title' => 'Construction',
                    'description' => 'Our team completes the work with careful coordination, communication, and attention to detail.',
                ],
                [
                    'title' => 'Final Walkthrough',
                    'description' => 'We review the completed work together and address final details before handoff.',
                ],
            ],

            'planningGuide' => [
                [
                    'heading' => 'Project Scope',
                    'body' => 'Every remodel starts with a clear scope — which rooms or systems are changing, and '
                        . 'what stays the same. A defined scope keeps a kitchen remodel, bathroom renovation, or '
                        . 'whole-home project on schedule.',
                ],
                [
                    'heading' => 'Budget Preparation',
                    'body' => 'Bay Area residential construction costs vary widely by scope, materials, and home '
                        . 'condition. Having a general investment range in mind before your consultation helps us '
                        . 'talk through realistic options together.',
                ],
                [
                    'heading' => 'Materials',
                    'body' => 'From cabinetry to tile to fixtures, material choices affect both budget and timeline. '
                        . "We'll walk through options that fit your project's style and durability needs.",
                ],
                [
                    'heading' => 'Permits',
                    'body' => 'Many additions, ADUs, and structural changes require permitting through your local '
                        . 'Bay Area jurisdiction. We coordinate the permitting process as part of planning your project.',
                ],
                [
                    'heading' => 'Timelines',
                    'body' => 'Residential remodeling timelines depend on scope, permitting, and material lead '
                        . "times. We'll set expectations for your project's timeline during planning, before "
                        . 'construction begins.',
                ],
                [
                    'heading' => 'Preparing for a Consultation',
                    'body' => "Have photos of your space, a general idea of your goals, and any inspiration in "
                        . "mind. It helps us understand your project from the very first conversation.",
                ],
            ],
        ];
    }

    /**
     * These option sets are intentionally duplicated in
     * LeadController's constants (used for server-side validation of
     * the same form) rather than shared, to keep the two controllers
     * decoupled — HomeController only ever renders, LeadController
     * only ever validates/persists. Keep both lists in sync.
     */
    private function projectFormOptions(): array
    {
        return [
            'projectTypes' => [
                'kitchen' => 'Kitchen Remodeling',
                'bathroom' => 'Bathroom Remodeling',
                'whole-home' => 'Whole-Home Renovation',
                'addition-adu' => 'Addition / ADU',
                'other' => 'Other / Not Sure Yet',
            ],
            'timeframes' => [
                'asap' => 'As soon as possible',
                '1-3-months' => '1–3 months',
                '3-6-months' => '3–6 months',
                '6-plus-months' => '6+ months',
                'exploring' => 'Just exploring',
            ],
            'budgets' => [
                'under-25k' => 'Under $25,000',
                '25k-75k' => '$25,000 – $75,000',
                '75k-150k' => '$75,000 – $150,000',
                '150k-plus' => '$150,000+',
                'not-sure' => 'Not sure yet',
            ],
            'contactMethods' => [
                'email' => 'Email',
                'phone' => 'Phone',
                'either' => 'Either',
            ],
        ];
    }
}
