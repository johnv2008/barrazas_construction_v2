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
     * XML sitemap: the homepage plus every PUBLISHED service and project.
     *
     * Enumerated from the same catalogue the routes resolve against, so the
     * sitemap cannot advertise a URL that 404s and cannot omit one that
     * exists. Services with no page — ADU, which is Tier D — have no
     * catalogue entry at all, so they are absent here by construction
     * rather than by a filter somebody has to remember to write.
     *
     * /services and /projects index pages are deliberately not listed:
     * they do not exist yet.
     */
    public function sitemap(Request $request, array $params): void
    {
        header('Content-Type: application/xml; charset=utf-8');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        echo '  <url><loc>' . e(base_url('/')) . '</loc></url>' . "\n";

        foreach (\App\Content\Catalog::publishedServiceSlugs() as $slug) {
            echo '  <url><loc>' . e(base_url('services/' . $slug)) . '</loc></url>' . "\n";
        }

        foreach (\App\Content\Catalog::publishedProjectSlugs() as $slug) {
            echo '  <url><loc>' . e(base_url('projects/' . $slug)) . '</loc></url>' . "\n";
        }

        echo '</urlset>';
    }

    /**
     * robots.txt, rendered rather than served as a static file.
     *
     * The sitemap directive must be a fully-qualified URL — the spec
     * requires it, and a relative path is silently ignored (Lighthouse
     * reports it as an invalid sitemap URL). Rendering it here means the
     * host comes from APP_URL, so local, staging and production each
     * advertise their own sitemap without anyone remembering to edit a
     * static file.
     *
     * public/robots.txt was removed when this was added: Apache serves
     * real files before consulting the front controller, so a static
     * copy would shadow this route and silently win.
     */
    public function robots(Request $request, array $params): void
    {
        header('Content-Type: text/plain; charset=utf-8');

        $adminPath = trim((string) config('app.admin_path', 'admin'), '/');

        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo 'Disallow: /' . $adminPath . "/\n";
        echo "Disallow: /uploads/\n";
        echo "\n";
        echo 'Sitemap: ' . base_url('sitemap.xml') . "\n";
    }

    /**
     * Homepage content, organised as the eight approved chapters.
     *
     * Emotional order is Dream -> Proof -> Trust -> Consultation:
     * finished work leads, process substantiates, it never replaces.
     * Target content balance is roughly 55% completed results,
     * 25% process, 20% trust.
     *
     * Structured to mirror the tables each chapter will eventually be
     * sourced from once the CMS modules exist:
     *   hero/philosophy/middle/process -> page_sections (page slug "home")
     *   craft                          -> services
     *   work                           -> projects + project_images
     *   trust                          -> site_settings
     * Swapping this method's body for real queries is the only change
     * a future phase needs to make.
     *
     * NOTHING HERE IS INVENTED. Project locations, durations, dates and
     * testimonials are deliberately absent rather than estimated — see
     * prototypes/homepage-v3-greybox/README.md §12 for the outstanding
     * content checklist.
     *
     * @return array<string, mixed>
     */
    private function homeData(): array
    {
        // Specificity is the trust signal — "Licensed, bonded, and insured"
        // is a claim anyone can make, the same line carrying a verifiable
        // CSLB number is not. Falls back to the plain claim when unset.
        $licensed = license_line() !== ''
            ? 'Licensed, bonded, and insured — ' . license_line()
            : 'Licensed, bonded, and insured';

        return [
            // Datum: the persistent reference line. Labels are short by
            // design — this is a plumb line, not a table of contents.
            'chapters' => [
                ['n' => '01', 'id' => 'top', 'label' => 'Arrival'],
                ['n' => '02', 'id' => 'about', 'label' => 'Approach'],
                ['n' => '03', 'id' => 'proof', 'label' => 'The Middle'],
                ['n' => '04', 'id' => 'services', 'label' => 'What We Build'],
                ['n' => '05', 'id' => 'projects', 'label' => 'Selected Work'],
                ['n' => '06', 'id' => 'trust', 'label' => 'Credentials'],
                ['n' => '07', 'id' => 'process', 'label' => 'What Happens'],
                ['n' => '08', 'id' => 'contact', 'label' => 'Begin'],
            ],

            // ---------- 01 ARRIVAL — aspiration first ----------
            'hero' => [
                'eyebrow' => 'Tuolumne County, California',
                'heading' => 'Your home, the way you always meant to live in it.',
                'lead' => 'Kitchen, bathroom, and whole-home remodeling for foothill homeowners '
                    . 'in Jamestown, Sonora, and across Tuolumne County.',
                'primaryCta' => ['label' => 'Start your project', 'href' => '#contact'],
                'secondaryCta' => ['label' => 'See our work', 'href' => '#projects'],
                'image' => asset('images/projects/service-kitchen.jpg'),
                'imageAlt' => 'Remodeled kitchen with soft grey shaker cabinetry, quartz counters '
                    . 'and a marble-veined tile backsplash',
                // Floating project card over the hero image.
                'plate' => [
                    'label' => 'Featured',
                    'title' => 'Kitchen Remodel',
                    'meta' => 'Tuolumne County',
                ],
                'trust' => $licensed,
                'since' => 'Building here since 2006',
            ],

            // ---------- 02 APPROACH — human, warm, quiet ----------
            'philosophy' => [
                'eyebrow' => 'Our approach',
                'heading' => 'A remodel starts with how you actually live in the house.',
                'body' => "Before anyone talks about cabinets or tile, we want to understand what isn't "
                    . 'working — where the family really gathers, which room you avoid, what you have put '
                    . 'up with for years because fixing it seemed like more disruption than it was worth. '
                    . 'The plan comes out of that conversation.',
                'image' => asset('images/projects/service-additions.jpg'),
                'imageAlt' => 'New knotty alder entry door with wrought iron detailing, freshly installed',
                'caption' => 'Entry door installation — detail',
            ],

            // ---------- 03 THE MIDDLE IS THE PROOF ----------
            //
            // TWO states, not three. This is deliberate and should not be
            // "fixed" by adding a state.
            //
            // The chapter documents one real project — a hillside roof
            // replacement — and the library contains exactly two unique
            // photographs of it: the roof open to bare sheathing, and the
            // finished result. Verified by file hash, since several
            // filenames are byte-identical duplicates of one another
            // (narrative-result.jpg, narrative-detail.jpg and
            // contact-sheet-hillside-deck.jpg are the same file).
            //
            // An earlier revision filled the middle slot with
            // process-detail.jpg — a bathroom mid-tile, from an entirely
            // different job. Presented inside one pinned frame under
            // During / In progress / Complete, that reads as a single
            // project and is a fabricated progression. It was removed.
            //
            // The argument survives without a third photograph: the
            // "middle" is carried by the bridge copy and the site notes
            // below, which describe only what is visible in the first
            // image. When genuine additional photography of THIS project
            // exists, add a state here and the markup picks it up.
            'middle' => [
                'eyebrow' => 'The middle is the proof',
                'heading' => 'Anyone can show you the after.',
                'lead' => 'The part that decides whether a remodel goes well is the part most homeowners '
                    . 'never get to see before they sign. We would rather show you.',
                'project' => 'Roof replacement · Tuolumne County',
                'states' => [
                    [
                        'key' => 'underway',
                        'label' => 'Work underway',
                        'step' => '01',
                        'title' => 'Open, and nothing hidden.',
                        'body' => 'The work homeowners rarely see is where planning, protection, '
                            . 'sequencing and craftsmanship matter most. This is the same house you '
                            . 'see finished below, photographed while it was still open.',
                        'image' => asset('images/projects/narrative-progress.jpg'),
                        'imageAlt' => 'Hillside home with the roof stripped to bare plywood sheathing '
                            . 'and new shingle bundles staged in the foreground',
                    ],
                    [
                        'key' => 'complete',
                        'label' => 'Completed result',
                        'step' => '02',
                        'title' => 'And then you get your house back.',
                        'body' => 'The finished result is only possible because the difficult middle was '
                            . 'handled carefully — the part you will live with for the next twenty years '
                            . 'depended entirely on the weeks before it.',
                        'image' => asset('images/projects/narrative-result.jpg'),
                        'imageAlt' => 'The same hillside home with its new shingle roof complete and the '
                            . 'redwood deck back in use, surrounded by pines',
                    ],
                ],
                // The text-led bridge that stands in for a third frame.
                // Every note describes something directly visible in the
                // first photograph — no process promises are made here,
                // because the company's actual site protocol has not been
                // confirmed yet. See the blocker list.
                'bridge' => [
                    'label' => 'On site',
                    'body' => 'One roof, two photographs, taken weeks apart. What happened in between is '
                        . 'the part worth asking any contractor about.',
                    'notes' => [
                        'Stripped back to bare sheathing',
                        'New material staged on site',
                        'Structure open and visible',
                    ],
                ],
                'disclosure' => 'Two photographs of one project. We would rather show a short, honest '
                    . 'sequence than pad it out with somebody else\'s job.',
            ],

            // ---------- 04 WHAT WE BUILD — services as experiences ----------
            // Five entries, deliberately unequal. `wide` and `plain` drive
            // three distinct row compositions so this never resolves into a
            // grid of matching cards.
            'craft' => [
                'eyebrow' => 'What we build',
                'heading' => 'Five ways a house becomes the one you wanted.',
                'items' => [
                    [
                        'n' => '01',
                        'title' => 'Kitchen Remodeling',
                        'benefit' => 'The room everything else happens around — planned so it is out of '
                            . 'service once, not four separate times.',
                        'image' => asset('images/projects/contact-sheet-kitchen.jpg'),
                        'imageAlt' => 'Remodeled kitchen opening onto a dining area with a sliding door to the patio',
                        'support' => asset('images/projects/transformation-complete.jpg'),
                        'supportAlt' => 'Kitchen counter detail with quartz surface and tile backsplash',
                        // Was '#projects' — a fragment on this same page.
                        // The service pages exist now; this is the only
                        // route a visitor has to them.
                        'href' => '/services/kitchen-remodeling',
                        'layout' => 'dominant',
                    ],
                    [
                        'n' => '02',
                        'title' => 'Bathroom Remodeling',
                        'benefit' => 'Small rooms and the least forgiving work in the house. Waterproofing '
                            . 'first, finishes second.',
                        'image' => asset('images/projects/project-featured.jpg'),
                        'imageAlt' => 'Remodeled bathroom with marble-look floor tile, tiled tub surround '
                            . 'and matte black fixtures',
                        'support' => asset('images/projects/intro-detail.jpg'),
                        'supportAlt' => 'Tub surround in large-format marble-look panels with a black framed shower door',
                        'href' => '/services/bathroom-remodeling',
                        'layout' => 'detail',
                    ],
                    [
                        'n' => '03',
                        'title' => 'Whole-Home Renovation',
                        'benefit' => 'When the house needs to change all at once — sequenced so you know '
                            . 'which rooms stay usable, and when.',
                        'image' => asset('images/projects/service-wholehome-primary.jpg'),
                        'imageAlt' => 'Open renovated living space with new plank flooring and a brick fireplace',
                        'support' => asset('images/projects/project-supporting-1.jpg'),
                        'supportAlt' => 'Renovated kitchen with white shaker cabinetry and marble tile backsplash',
                        'href' => '/services/whole-home-renovation',
                        'layout' => 'stack',
                    ],
                    [
                        'n' => '04',
                        'title' => 'Home Additions',
                        'benefit' => 'More house without leaving the county — framed, tied in, and finished '
                            . 'so the addition never reads as an addition.',
                        'image' => asset('images/projects/service-whole-home.jpg'),
                        'imageAlt' => 'Two-storey home exterior with fresh paint and mature landscaping',
                        'support' => null,
                        'supportAlt' => '',
                        'href' => '#contact',
                        'layout' => 'plate',
                    ],
                    [
                        // No ADU photography exists in the library yet, so this
                        // row is typographic by design rather than padded with
                        // an unrelated image. It also breaks the rhythm of the
                        // four rows above it, which the composition wants.
                        'n' => '05',
                        'title' => 'Accessory Dwelling Units',
                        'benefit' => 'A second home on the same lot — for family, for guests, or for '
                            . 'rental income. We coordinate the permitting that comes with it.',
                        'image' => null,
                        'imageAlt' => '',
                        'support' => null,
                        'supportAlt' => '',
                        'href' => '#contact',
                        'layout' => 'plain',
                    ],
                ],
            ],

            // ---------- 05 SELECTED WORK — architecture portfolio ----------
            'work' => [
                'eyebrow' => 'Selected work',
                'heading' => 'Rooms worth the disruption.',
                'featured' => [
                    'label' => 'Featured project',
                    'title' => 'A primary suite that finally looks at the view it was built for.',
                    'body' => 'A tiled walk-in shower with a bench and custom niche, a soaking tub set into '
                        . 'the window wall, and a cedar ceiling carried across both. The glass accent band '
                        . 'runs unbroken from the shower through to the tub surround — one continuous line '
                        . 'through a room that used to read as two disconnected halves.',
                    'image' => asset('images/projects/service-bathroom.jpg'),
                    'imageAlt' => 'Custom tiled walk-in shower with a glass mosaic accent band, built-in '
                        . 'bench and cedar plank ceiling',
                    'detail' => asset('images/projects/project-supporting-2.jpg'),
                    'detailAlt' => 'Soaking tub set beneath a window overlooking the foothills and reservoir',
                    'meta' => [
                        ['label' => 'Scope', 'value' => 'Primary suite bathroom, full remodel'],
                        ['label' => 'Location', 'value' => 'Tuolumne County'],
                    ],
                    'href' => '#contact',
                ],
                // Filing label for the contact sheet, not a second heading.
                'sheetLabel' => 'Also completed — Tuolumne County',
                // A contact sheet, not a gallery: four frames at one size.
                // Varied tile sizes here competed with the featured spread
                // above and turned the chapter into two focal points. Every
                // source in the library is a 3:4 portrait, so the sheet uses
                // that ratio rather than cropping portraits into landscape.
                'tiles' => [
                    [
                        'image' => asset('images/projects/hero-inset.jpg'),
                        'imageAlt' => 'Carrara marble walk-in shower with a built-in niche',
                        'title' => 'Marble Walk-In Shower',
                        'meta' => 'Bathroom',
                    ],
                    [
                        'image' => asset('images/projects/service-wholehome-detail.jpg'),
                        'imageAlt' => 'Renovated kitchen corner with white cabinetry and a new refrigerator',
                        'title' => 'Kitchen & Hallway',
                        'meta' => 'Whole-home renovation',
                    ],
                    [
                        'image' => asset('images/projects/contact-sheet-bath-vanity.jpg'),
                        'imageAlt' => 'Bathroom vanity with framed mirror and new lighting',
                        'title' => 'Vanity & Lighting',
                        'meta' => 'Bathroom',
                    ],
                    [
                        'image' => asset('images/projects/contact-sheet-bath-tub.jpg'),
                        'imageAlt' => 'Remodeled bathroom with a new tub surround and plank flooring',
                        'title' => 'Family Bathroom',
                        'meta' => 'Whole-home renovation',
                    ],
                ],
            ],

            // ---------- 06 CREDENTIALS — said once, with numbers ----------
            'trust' => [
                'eyebrow' => 'Why homeowners choose us',
                'heading' => 'Nearly twenty years in the same county.',
                'body' => 'Long enough that the work speaks for itself, and small enough that the person '
                    . 'who walks your property is the person running your job.',
                'image' => asset('images/projects/transformation-progress.jpg'),
                'imageAlt' => 'New plank flooring being installed in a kitchen during a whole-home renovation',
                'caption' => 'Flooring installation — whole-home renovation, Tuolumne County',
                'items' => [
                    ['label' => 'Since 2006', 'value' => 'Residential remodeling across Tuolumne County'],
                    ['label' => 'Licensed', 'value' => license_line() !== '' ? license_line() : 'Licensed general building contractor'],
                    ['label' => 'Bonded & insured', 'value' => 'General liability and workers\' compensation'],
                    ['label' => 'Based in Jamestown', 'value' => '16561 Jacksonville Rd, Jamestown, CA 95327'],
                ],
            ],

            // ---------- 07 WHAT HAPPENS — the homeowner's experience ----------
            'process' => [
                'eyebrow' => 'What happens',
                'heading' => 'You will know what is happening in your house, and when.',
                'steps' => [
                    ['n' => '01', 'title' => 'A conversation', 'body' => 'We ask what is not working and roughly what you are hoping to spend.'],
                    ['n' => '02', 'title' => 'We walk the property', 'body' => 'Together, looking at access, age, and what is behind the walls.'],
                    ['n' => '03', 'title' => 'Scope and estimate', 'body' => 'In writing. You will know the number before you commit to anything.'],
                    ['n' => '04', 'title' => 'The work', 'body' => 'You will know who is coming, which rooms stay usable, and what we find.'],
                    ['n' => '05', 'title' => 'Walkthrough', 'body' => 'We go through it together and write down anything still outstanding.'],
                ],
            ],

            // ---------- 08 BEGIN ----------
            'consultation' => [
                'eyebrow' => 'Begin',
                'heading' => "Let's talk about your project.",
                'body' => 'Tell us what you are thinking about. No obligation, no pressure, and no sales '
                    . 'visit unless you ask for one.',
                'image' => asset('images/projects/hero-exterior.jpg'),
                'imageAlt' => 'Completed exterior remodel on a home with fresh paint and a covered porch',
            ],

            // Keys and labels are the server-side allow-list in
            // LeadController::PROJECT_TYPES. Additions and ADUs are presented
            // as separate services in chapter 04, but they share one form
            // value here — changing these would break lead validation.
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
