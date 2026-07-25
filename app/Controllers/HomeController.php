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
                'title' => "Barraza's Construction | Tuolumne County Residential Remodeling & Renovation",
                'metaDescription' => 'Barraza\'s Construction delivers residential remodeling, additions, and complete home renovations throughout Tuolumne County — kitchens, bathrooms, whole-home renovations, and ADUs.',
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
     *   hero/intro/whyChoose/process/planningGuide -> page_sections (page slug "home")
     *   services                                   -> services
     *   featuredProject/selectedProjects            -> projects + project_images
     *   trustStrip                                  -> site_settings
     * Swapping this method's body for real queries is the only change
     * a future phase needs to make.
     *
     * @return array<string, mixed>
     */
    private function homeData(): array
    {
        return [
            'hero' => [
                'eyebrow' => "Barraza's Construction Inc.",
                'heading' => 'Tuolumne County remodeling, built with care.',
                'lead' => 'Kitchens, bathrooms, additions, and complete home renovations delivered with '
                    . 'experienced craftsmanship and clear communication.',
                'primaryCta' => ['label' => 'Schedule a Consultation', 'href' => '#contact'],
                'secondaryCta' => ['label' => 'View Our Work', 'href' => '#projects'],
                'trust' => [
                    'Serving Tuolumne County homeowners since 2006',
                    'Licensed, bonded, and insured',
                ],
                'primaryImage' => asset('images/projects/hero-full.jpg'),
                'primaryAlt' => 'Custom Tuolumne County home exterior at dusk',
            ],

            'trustStrip' => [
                'Serving homeowners since 2006',
                'Licensed, bonded, and insured',
                'Residential remodeling specialists',
            ],

            'intro' => [
                'heading' => 'Thoughtful remodeling for better everyday living.',
                'body' => "Barraza's Construction helps Tuolumne County homeowners improve their homes through "
                    . 'carefully planned remodeling, additions, and residential construction. Every project is '
                    . 'approached with attention to communication, coordination, and final detail.',
                'image' => asset('images/projects/service-kitchen.jpg'),
                'imageAlt' => 'Renovated kitchen with white shaker cabinetry and marble backsplash',
            ],

            'services' => [
                [
                    'title' => 'Kitchen Remodeling',
                    'description' => 'Layouts, cabinetry, and finishes built for the way your family cooks and gathers.',
                    'image' => asset('images/projects/service-kitchen.jpg'),
                    'imageAlt' => 'Renovated kitchen with white shaker cabinetry and marble backsplash',
                    'href' => '#contact',
                ],
                [
                    'title' => 'Bathroom Remodeling',
                    'description' => 'Spa-inspired bathrooms designed for comfort, durability, and everyday function.',
                    'image' => asset('images/projects/service-bathroom.jpg'),
                    'imageAlt' => 'Marble bathroom with black rainfall shower fixture',
                    'href' => '#contact',
                ],
                [
                    'title' => 'Whole-Home Renovations',
                    'description' => "Coordinated updates to a home's structure, systems, and style together.",
                    'image' => asset('images/projects/service-wholehome-primary.jpg'),
                    'imageAlt' => 'Renovated living room with refaced brick fireplace and new flooring',
                    'href' => '#contact',
                ],
                [
                    'title' => 'Additions & ADUs',
                    'description' => 'Thoughtfully designed additions and accessory dwelling units that expand how you live.',
                    'image' => asset('images/projects/service-additions.jpg'),
                    'imageAlt' => 'New entry door installation on a covered porch',
                    'href' => '#contact',
                ],
            ],

            'featuredProject' => [
                'eyebrow' => 'Featured Project',
                'heading' => 'A hillside home, ready for the Tuolumne County elements',
                'category' => 'Additions & Exteriors',
                'city' => 'Tuolumne County',
                'summary' => 'A full roof replacement and deck refresh, coordinated from tear-off through final '
                    . 'walkthrough — renewed for Tuolumne County weather while opening up the surrounding hillside view.',
                'mainImage' => asset('images/projects/narrative-result.jpg'),
                'mainAlt' => 'Hillside home deck with a view of the surrounding hills after exterior work',
                'thumbs' => [
                    [
                        'image' => asset('images/projects/narrative-progress.jpg'),
                        'alt' => 'Roof replacement in progress on the same hillside home',
                    ],
                ],
                'href' => '#contact',
            ],

            'selectedProjects' => [
                [
                    'image' => asset('images/projects/project-featured.jpg'),
                    'imageAlt' => 'Primary bathroom remodel with marble tile tub and shower surround',
                    'title' => 'Primary Bathroom Remodel',
                    'category' => 'Bathrooms',
                    'city' => 'Tuolumne County',
                    'href' => '#contact',
                ],
                [
                    'image' => asset('images/projects/contact-sheet-kitchen.jpg'),
                    'imageAlt' => 'Kitchen remodel with gray cabinetry and marble backsplash',
                    'title' => 'Kitchen Remodel',
                    'category' => 'Kitchens',
                    'city' => 'Tuolumne County',
                    'href' => '#contact',
                ],
                [
                    'image' => asset('images/projects/project-supporting-2.jpg'),
                    'imageAlt' => 'Primary suite bathroom remodel with granite vanity and a large window',
                    'title' => 'Primary Suite Remodel',
                    'category' => 'Primary Suite',
                    'city' => 'Tuolumne County',
                    'href' => '#contact',
                ],
                [
                    'image' => asset('images/projects/contact-sheet-bath-vanity.jpg'),
                    'imageAlt' => 'Bathroom vanity with framed mirror and new lighting',
                    'title' => 'Vanity & Lighting Refresh',
                    'category' => 'Bathrooms',
                    'city' => 'Tuolumne County',
                    'href' => '#contact',
                ],
            ],

            'whyChoose' => [
                'heading' => 'Experience you can build on.',
                'items' => [
                    ['title' => 'Since 2006', 'body' => 'Serving Tuolumne County homeowners for nearly two decades.'],
                    ['title' => 'Licensed & Insured', 'body' => 'Licensed, bonded, and insured for residential work.'],
                    ['title' => 'Clear Communication', 'body' => 'Coordinated project planning from consultation through completion.'],
                    ['title' => 'Final Details', 'body' => 'Careful attention to the finishing touches before handoff.'],
                ],
            ],

            'process' => [
                ['title' => 'Consultation', 'description' => 'We learn about your goals and priorities for the project.'],
                ['title' => 'Site Visit', 'description' => 'We walk the property to understand existing conditions.'],
                ['title' => 'Scope & Proposal', 'description' => 'We define the scope of work and provide a clear proposal.'],
                ['title' => 'Construction', 'description' => 'Our team completes the work with careful coordination.'],
                ['title' => 'Final Walkthrough', 'description' => 'We review the finished work together before handoff.'],
            ],

            'planningGuide' => [
                'heading' => 'Planning a residential remodel in Tuolumne County',
                'items' => [
                    ['heading' => 'Project Scope', 'body' => 'A clear scope — which rooms or systems are changing — keeps any remodel on schedule.'],
                    ['heading' => 'Budget Preparation', 'body' => 'Costs vary by scope and materials; a general range in mind helps guide the conversation.'],
                    ['heading' => 'Permits', 'body' => 'Additions, ADUs, and structural changes often require permitting, which we help coordinate.'],
                    ['heading' => 'Timelines', 'body' => 'Timelines depend on scope, permitting, and material lead times, set clearly during planning.'],
                    ['heading' => 'Material Selections', 'body' => "Cabinetry, tile, and fixture choices affect both budget and timeline — we'll help you choose."],
                ],
            ],

            'consultation' => [
                'heading' => "Let's talk about your project.",
                'body' => "Tell us what you're planning and our team will contact you to discuss the next step.",
            ],

            'projectTypes' => [
                'kitchen' => 'Kitchen Remodeling',
                'bathroom' => 'Bathroom Remodeling',
                'whole-home' => 'Whole-Home Renovation',
                'addition-adu' => 'Addition / ADU',
                'other' => 'Other / Not Sure Yet',
            ],
        ];
    }
}
