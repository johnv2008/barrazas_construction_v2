<?php

declare(strict_types=1);

namespace App\Content;

/**
 * Seeded reference content for service and project pages.
 *
 * WHY THIS FILE EXISTS
 * --------------------
 * The approved phase order is: Kitchen, Bathroom, Whole Home, Project
 * Detail, Portfolio, CMS — deliberately building the CMS last so it
 * implements a proven content architecture instead of inventing one.
 * This file is the interim source of truth for that architecture.
 *
 * THE CMS SEAM
 * ------------
 * Every array below is shaped like the database row that will replace
 * it. Keys are column names, not view-convenience names:
 *
 *   service()        -> SELECT * FROM services WHERE slug = ?
 *   sections()       -> SELECT * FROM service_sections WHERE service_id = ?
 *                       ORDER BY sort_order        (grouped by section_key)
 *   faqs()           -> SELECT * FROM service_faqs  WHERE service_id = ?
 *   project()        -> SELECT * FROM projects      WHERE slug = ?
 *   images()         -> SELECT * FROM project_images WHERE project_id = ?
 *                       ORDER BY sort_order
 *   relatedProjects()-> JOIN service_projects
 *
 * Swapping this class for repositories that run those queries should not
 * require touching a single view. That is the whole point of building it
 * this way round: if the views need changing when the CMS lands, the
 * architecture was not actually proven.
 *
 * TRUTHFULNESS RULES OBSERVED HERE
 * --------------------------------
 * 1. No project groups two photographs unless they are verifiably the
 *    same room. Only `galley-kitchen-remodel` qualifies; the rest are
 *    single-photograph records and say so.
 * 2. `completion_year` and `duration_weeks` are NULL wherever the real
 *    value is unknown. The templates omit empty spec rows rather than
 *    printing "Varies" or "TBD". Nothing here is invented to fill a row.
 * 3. Materials lists describe only what is visible in the photograph.
 * 4. `hero-full.jpg` is excluded. It is stock (see the greybox README
 *    finding I-1) and the brief prohibits stock photography.
 * 5. ADU has no entry at all — Tier D means no page, no nav entry, no
 *    sitemap entry, no schema. Not a draft, not a placeholder.
 */
final class Catalog
{
    /**
     * Services that have a published page. ADU is absent by design.
     *
     * `inventory_tier` is stored here for clarity but is DERIVED in the
     * CMS (counted from linked images) and shown to editors read-only —
     * see DESIGN_SYSTEM.md §8.1.
     */
    public static function services(): array
    {
        return [
            'kitchen-remodeling' => [
                'slug' => 'kitchen-remodeling',
                'title' => 'Kitchen Remodeling',
                'summary' => 'Kitchen remodeling in Tuolumne County — cabinetry, counters, '
                    . 'tile, flooring and lighting, planned so the room is out of service once.',
                'inventory_tier' => 'A',
                'signature' => 'material',

                // Ch 01 — the emotional H1, not the page title. The keyword
                // lives in meta_title and the breadcrumb.
                'h1_statement' => 'The room where birthdays, holidays and ordinary Tuesdays happen.',
                'eyebrow' => 'Kitchen remodeling',
                'lead' => 'A kitchen is the only room the whole house walks through. Ours are planned '
                    . 'around that — how you actually move through it, where the light lands, and how '
                    . 'long you have to live without it.',

                // Ch 01 hero. Deliberately the SECOND angle of the kitchen
                // whose first angle is the homepage hero: a homeowner
                // arriving here sees the same room they just met, from a
                // new position. Continuity, not repetition.
                'hero_image_path' => 'images/projects/contact-sheet-kitchen.jpg',
                'hero_image_alt' => 'Remodeled galley kitchen looking toward a sliding door, with grey '
                    . 'shaker cabinetry, quartz counters and a marble-look tile backsplash',
                'hero_caption_label' => 'Featured',
                'hero_caption_title' => 'Galley kitchen remodel',
                'hero_caption_meta' => 'Tuolumne County',

                'featured_project_slug' => 'galley-kitchen-remodel',
                'related_project_slugs' => [
                    'whole-home-kitchen-flooring',
                    'kitchen-and-hallway-renovation',
                    'two-tone-kitchen-finishes',
                ],
                'related_service_slugs' => ['whole-home-renovation', 'bathroom-remodeling'],

                // Preselects the consultation form's project-type field.
                // Must be a key from HomeController's $projectTypes, which
                // LeadController validates against server-side.
                'form_project_type' => 'kitchen',

                'meta_title' => "Kitchen Remodeling in Tuolumne County, CA | Barraza's Construction",
                'meta_description' => 'Kitchen remodeling in Jamestown, Sonora and across Tuolumne '
                    . 'County — cabinetry, quartz counters, tile and flooring, sequenced so your '
                    . 'kitchen is out of service once rather than four separate times.',
            ],
        ];
    }

    /**
     * Repeatable chapter content, grouped by section_key exactly as
     * `service_sections` rows would be after ORDER BY sort_order.
     */
    public static function sections(string $serviceSlug): array
    {
        $all = [
            'kitchen-remodeling' => [

                // Ch 02 — Why This Room Matters. Fraunces italic statement,
                // 1 of the page's 2 sanctioned Fraunces appearances.
                'why_it_matters' => [
                    'heading' => 'A kitchen is the only room the whole house walks through.',
                    'body' => 'Every other room has a door you can close. The kitchen is where the '
                        . 'day starts, where homework happens on the counter because the table is '
                        . 'covered, and where everyone ends up standing at a party regardless of how '
                        . 'much space you gave them elsewhere. When it works badly, it works badly '
                        . 'several times a day — which is why it is usually the first room people '
                        . 'ask us about, and the one they regret waiting on.',
                ],

                // Ch 04 — What Changes. Outcomes, not features. Reuses the
                // approved craft-row roles so no two adjacent rows match.
                'what_changes' => [
                    [
                        'n' => '01',
                        'title' => 'You stop losing the counter',
                        'body' => 'Most kitchens do not need more square footage, they need the '
                            . 'appliances and storage in a sensible order. When the toaster, the '
                            . 'kettle and the mail all have somewhere to live, the counter you '
                            . 'already own comes back.',
                        'role' => 'dominant',
                        'image_path' => 'images/projects/service-kitchen.jpg',
                        'image_alt' => 'Quartz counter run with an undermount sink and a marble-look '
                            . 'tile backsplash beneath grey shaker upper cabinets',
                    ],
                    [
                        'n' => '02',
                        'title' => 'Light you can actually work by',
                        'body' => 'A single ceiling fixture puts your own shadow on the chopping '
                            . 'board. Under-cabinet lighting on the work surfaces, and switching '
                            . 'split so the room can be bright or quiet, changes how the kitchen '
                            . 'feels after dark more than any finish does.',
                        'role' => 'detail',
                        'image_path' => 'images/projects/transformation-complete.jpg',
                        'image_alt' => 'Under-cabinet lighting washing down a gloss white stacked-tile '
                            . 'backsplash onto a quartz counter',
                    ],
                    [
                        'n' => '03',
                        'title' => 'A room that connects to the rest of the house',
                        'body' => 'Widening an opening or moving one wall is often the difference '
                            . 'between cooking alone and cooking with the room. It is also the part '
                            . 'that needs deciding early, because it changes the sequence of '
                            . 'everything after it.',
                        'role' => 'plain',
                        'image_path' => null,
                        'image_alt' => '',
                    ],
                    [
                        'n' => '04',
                        'title' => 'Surfaces that survive a family',
                        'body' => 'Quartz does not need sealing, luxury vinyl plank tolerates a '
                            . 'dropped pan and a wet dog, and a tiled backsplash wipes down rather '
                            . 'than staining. None of that is glamorous. All of it is why the room '
                            . 'still looks like this in five years.',
                        'role' => 'plate',
                        'image_path' => 'images/projects/service-wholehome-detail.jpg',
                        'image_alt' => 'White shaker cabinetry and marble-look quartz beside a new '
                            . 'stainless refrigerator, opening onto a hallway',
                    ],
                    [
                        'n' => '05',
                        'title' => 'One disruption, not four',
                        'body' => 'The single most valuable thing a plan buys you is sequence. '
                            . 'Counters templated while the cabinets cure, flooring after the wet '
                            . 'work, paint before the hardware. Done in the wrong order the same '
                            . 'kitchen takes twice as long and you lose it twice.',
                        'role' => 'plain',
                        'image_path' => null,
                        'image_alt' => '',
                    ],
                ],

                // Ch 05 — Process. Titles may be shared across services;
                // BODIES must be service-specific (duplicate-content rule,
                // SERVICE_PAGE_SYSTEM.md §6.4). These are kitchen-specific.
                'process_step' => [
                    [
                        'n' => '01',
                        'title' => 'A conversation',
                        'body' => 'What is not working, and roughly what you are hoping to spend. '
                            . 'Kitchens vary more by scope than by taste, so this is mostly about '
                            . 'whether walls are moving.',
                    ],
                    [
                        'n' => '02',
                        'title' => 'We walk the kitchen',
                        'body' => 'Where the plumbing and gas actually run, whether the panel can '
                            . 'take another circuit, and what is behind the wall you want opened. '
                            . 'This is where surprises get found instead of discovered later.',
                    ],
                    [
                        'n' => '03',
                        'title' => 'Scope and estimate',
                        'body' => 'In writing, itemised, with the cabinet and counter allowances '
                            . 'stated separately so you can see what a finish choice costs before '
                            . 'you make it.',
                    ],
                    [
                        'n' => '04',
                        'title' => 'We build it',
                        'body' => 'Cabinets, then counters templated to what was actually installed, '
                            . 'then tile, then flooring, then paint and hardware. You will have a '
                            . 'temporary sink arrangement for part of it, and we will tell you which '
                            . 'part before we start.',
                    ],
                    [
                        'n' => '05',
                        'title' => 'You get your kitchen back',
                        'body' => 'Walkthrough with the drawers and doors adjusted, the caulk lines '
                            . 'checked, and anything on the list fixed before we invoice the balance.',
                    ],
                ],

                // Ch 06 — Materials & Craftsmanship. THE SIGNATURE CHAPTER
                // for the Kitchen page type: a band of unequal tight crops,
                // each annotated with a method note. Crops are taken from
                // the same photographs used elsewhere on the page — at
                // macro scale a surface is a different subject from the room
                // it sits in, and this is what "material-driven" means with
                // the library that exists. See PHOTOGRAPHY_REQUIREMENTS.md:
                // purpose-shot material squares would strengthen this most.
                'materials_item' => [
                    // Four crops, four DIFFERENT source files. Two crops from
                    // one photograph looked like the same photograph twice,
                    // which is the one thing this chapter cannot afford — a
                    // set of material studies has to read as a set of
                    // different materials.
                    //
                    // Alt text describes what is actually visible in the
                    // CROP, not what the slot is nominally about. A square
                    // crop of a 3:4 portrait can only move vertically, so
                    // these frames show a slice of room around the material;
                    // claiming a tight hardware macro would be inaccurate.
                    // PHOTOGRAPHY_REQUIREMENTS.md §2 flags purpose-shot
                    // material squares as the highest-value fix for exactly
                    // this — it is the difference between a material study
                    // and a well-chosen slice.
                    [
                        'label' => 'Tile',
                        'title' => 'Set to a plane, not to a wall',
                        'body' => 'An old wall is never flat. Tile laid straight onto it telegraphs '
                            . 'every hollow along the grout lines. We float the substrate first, '
                            . 'which is invisible in the finished room and the reason the light '
                            . 'runs evenly across it.',
                        'image_path' => 'images/projects/service-kitchen.jpg',
                        'image_alt' => 'Marble-look ceramic subway tile running the full height behind '
                            . 'a quartz counter and a pull-down kitchen faucet',
                        'focal' => ['x' => 0.34, 'y' => 0.50],
                        'scale' => 'lead',
                    ],
                    [
                        'label' => 'Stone',
                        'title' => 'Templated to the cabinets that exist',
                        'body' => 'Counters are measured after the boxes are set and levelled, not '
                            . 'from the drawing. It costs a few days in the middle and it is why the '
                            . 'seam sits where it should and the overhang is even end to end.',
                        'image_path' => 'images/projects/transformation-complete.jpg',
                        'image_alt' => 'Quartz counter running to a slide-in range beside a gloss '
                            . 'white stacked-tile backsplash, lit from under the cabinets',
                        'focal' => ['x' => 0.42, 'y' => 0.62],
                        'scale' => 'medium',
                    ],
                    [
                        'label' => 'Cabinetry',
                        'title' => 'Judged by the box, not the door',
                        'body' => 'The door is what you look at; the box is what fails. Plywood '
                            . 'carcases, full-extension runners and doors hung with adjustable hinges '
                            . 'so they can be brought back into line years later.',
                        'image_path' => 'images/projects/service-wholehome-detail.jpg',
                        'image_alt' => 'White shaker upper cabinets with brushed nickel bar pulls, '
                            . 'beside the hallway opening of a renovated kitchen',
                        'focal' => ['x' => 0.5, 'y' => 0.18],
                        'scale' => 'small',
                    ],
                    [
                        'label' => 'Hardware',
                        'title' => 'Drilled from a jig, every time',
                        'body' => 'Pulls set by eye are out by two millimetres and the eye finds it '
                            . 'immediately across a run of drawers. A jig is slower on the first '
                            . 'door and faster on the twentieth.',
                        'image_path' => 'images/projects/contact-sheet-kitchen.jpg',
                        'image_alt' => 'A run of drawer fronts with bar pulls beside a stainless '
                            . 'dishwasher, under a quartz counter',
                        'focal' => ['x' => 0.5, 'y' => 0.62],
                        'scale' => 'small',
                    ],
                ],
            ],
        ];

        return $all[$serviceSlug] ?? [];
    }

    /** Ch 07 — genuine questions. Drives FAQPage schema. */
    public static function faqs(string $serviceSlug): array
    {
        $all = [
            'kitchen-remodeling' => [
                [
                    'question' => 'Do I need a permit to remodel a kitchen?',
                    'answer' => 'For a like-for-like replacement of cabinets, counters and finishes, '
                        . 'usually not. Once you move plumbing, add circuits, or take out any part of '
                        . 'a wall, Tuolumne County will want a permit — and you want one too, because '
                        . 'it is what proves the work was inspected when you eventually sell. We '
                        . 'handle the application and the inspections as part of the job.',
                ],
                [
                    'question' => 'How long will I be without a kitchen?',
                    'answer' => 'For a straightforward remodel, plan on three to five weeks with the '
                        . 'sink out of action for part of it. The honest answer for your kitchen comes '
                        . 'after we have seen it, because the variable is almost never the cabinets — '
                        . 'it is what we find behind the wall and how long the counter fabricator '
                        . 'takes once the template is cut.',
                ],
                [
                    'question' => 'Can I stay in the house while you work?',
                    'answer' => 'Almost always, yes, and most people do. We will set up a temporary '
                        . 'arrangement for washing up before we take the sink out, and we will tell '
                        . 'you which week is the disruptive one so you can plan around it rather than '
                        . 'discover it.',
                ],
                [
                    'question' => 'How do you handle dust?',
                    'answer' => 'Plastic at the openings, floor protection on the routes we walk, and '
                        . 'clean-up at the end of each day rather than at the end of the job. '
                        . 'Renovation is dusty and we would rather say so than promise otherwise — '
                        . 'what we will promise is that you get the rest of your house back every '
                        . 'evening.',
                ],
                [
                    'question' => 'What happens if I change my mind halfway through?',
                    'answer' => 'It depends entirely on when. A finish or hardware change before the '
                        . 'order goes in costs nothing. Moving a cabinet run after the counters are '
                        . 'templated means new counters. We will always tell you the cost of a change '
                        . 'before we make it, in writing, and nothing gets built off a conversation.',
                ],
                [
                    'question' => 'Will a kitchen remodel add value to my home?',
                    'answer' => 'A kitchen is consistently one of the better returns in a remodel, but '
                        . 'we would not sell you one on that basis. If you are remodelling to sell '
                        . 'within a year, tell us and we will keep the scope tight and the finishes '
                        . 'neutral. If you are staying, build the kitchen you actually want.',
                ],
            ],
        ];

        return $all[$serviceSlug] ?? [];
    }

    /**
     * Projects. Shaped as `projects` rows plus a nested image set shaped
     * as `project_images` rows.
     *
     * `duration_weeks` and `completion_year` are NULL where unknown. The
     * spec table omits empty rows — see 'materials' vs 'duration_weeks'
     * on the first record for the contrast.
     */
    public static function projects(): array
    {
        return [

            // ---- The one project with two verified photographs of the
            // same room. Reference implementation for Project Detail.
            'galley-kitchen-remodel' => [
                'slug' => 'galley-kitchen-remodel',
                'title' => 'A galley kitchen that stopped wasting its own length',
                'project_type' => 'Kitchen remodel',
                'category' => 'Kitchen',
                'city' => 'Tuolumne County',
                'completion_year' => null,   // unknown — row is not rendered
                'duration_weeks' => null,    // unknown — row is not rendered
                'is_featured' => 1,
                'service_slug' => 'kitchen-remodeling',
                'short_description' => 'Grey shaker cabinetry, quartz counters and a marble-look tile '
                    . 'backsplash in a narrow galley kitchen that opens onto the garden.',
                'full_description' => 'A galley kitchen is mostly a corridor, and the mistake is to '
                    . 'treat the whole of it as work surface. Here the working run — sink, dishwasher, '
                    . 'drawers — was concentrated on one wall so the other side stays clear as a route '
                    . 'through to the garden door. The counter was carried unbroken past the sink to '
                    . 'give one genuinely usable stretch rather than three interrupted ones, and the '
                    . 'backsplash tile runs the full height under the uppers so there is no painted '
                    . 'strip to keep clean.',
                'materials' => 'Quartz counters · marble-look ceramic tile · shaker cabinetry · '
                    . 'luxury vinyl plank · brushed nickel hardware',
                'scope' => 'Cabinetry, counters, backsplash, sink and faucet, flooring, paint',
                // Ch 03 of the project page — annotated construction notes.
                // Every note describes something visible in a photograph on
                // this page. No process claims beyond the evidence.
                'notes' => [
                    [
                        'n' => '01',
                        'title' => 'The run is unbroken',
                        'body' => 'Sink centred under the window, dishwasher immediately to its left, '
                            . 'drawer bank to the right. Nothing interrupts the counter between them.',
                    ],
                    [
                        'n' => '02',
                        'title' => 'Tile to the underside of the uppers',
                        'body' => 'Full-height tile rather than a four-inch splash, so the wall behind '
                            . 'the work surface wipes down instead of marking.',
                    ],
                    [
                        'n' => '03',
                        'title' => 'The far end was left alone',
                        'body' => 'No cabinetry past the doorway. The daylight from the slider is worth '
                            . 'more to a narrow room than the storage would have been.',
                    ],
                ],
                'disclosure' => null,
                'images' => [
                    [
                        'image_path' => 'images/projects/service-kitchen.jpg',
                        'image_role' => 'hero',
                        'alt_text' => 'Remodeled galley kitchen with grey shaker cabinetry, quartz '
                            . 'counters, an undermount sink and a marble-look tile backsplash',
                        'caption' => 'The working run — sink, dishwasher, drawers',
                        'sort_order' => 1,
                    ],
                    [
                        'image_path' => 'images/projects/contact-sheet-kitchen.jpg',
                        'image_role' => 'context',
                        'alt_text' => 'The same kitchen looking toward a sliding door onto a paved '
                            . 'patio, with luxury vinyl plank flooring',
                        'caption' => 'Looking through to the garden door — the end left deliberately open',
                        'sort_order' => 2,
                    ],
                ],
            ],

            // ---- Single-photograph records. Tier D does NOT apply to a
            // record of one job (DESIGN_SYSTEM.md §8.1): the page documents
            // one specific job rather than promising a body of work, so the
            // bar is lower. Each states plainly that it is one photograph.
            'whole-home-kitchen-flooring' => [
                'slug' => 'whole-home-kitchen-flooring',
                'title' => 'New floor going down while the old kitchen was still standing',
                'project_type' => 'Whole-home renovation',
                'category' => 'Whole home',
                'city' => 'Tuolumne County',
                'completion_year' => null,
                'duration_weeks' => null,
                'is_featured' => 0,
                'service_slug' => 'kitchen-remodeling',
                'short_description' => 'Luxury vinyl plank being installed through a whole-home '
                    . 'renovation, photographed before the original oak kitchen came out.',
                'full_description' => 'This is the part of a remodel homeowners almost never see, and '
                    . 'it is the part that decides how long they lose the room for. The flooring was '
                    . 'run through the kitchen and the adjoining living space while the original oak '
                    . 'cabinets were still in use, so the house kept a working kitchen for several '
                    . 'more days and the new floor was already down and protected when the cabinetry '
                    . 'was replaced. Sequencing is not a detail; it is most of what a plan is for.',
                'materials' => 'Luxury vinyl plank',
                'scope' => 'Flooring through kitchen and living area, as part of a whole-home renovation',
                'notes' => [
                    [
                        'n' => '01',
                        'title' => 'Original cabinetry still in place',
                        'body' => 'Honey-oak raised-panel doors and the original tiled counter, '
                            . 'photographed on the day the flooring started.',
                    ],
                    [
                        'n' => '02',
                        'title' => 'Floor first, kitchen second',
                        'body' => 'Plank run continuously into the living area so there is no threshold '
                            . 'strip between the two rooms.',
                    ],
                ],
                'disclosure' => 'One photograph of this job, taken mid-installation. We would rather '
                    . 'show a single honest frame than pad the page out with somebody else\'s work.',
                'images' => [
                    [
                        'image_path' => 'images/projects/transformation-progress.jpg',
                        'image_role' => 'during',
                        'alt_text' => 'New luxury vinyl plank flooring part-installed in a kitchen that '
                            . 'still has its original honey-oak cabinets and tiled counter',
                        'caption' => 'Flooring underway — original kitchen still in service',
                        'sort_order' => 1,
                    ],
                ],
            ],

            'kitchen-and-hallway-renovation' => [
                'slug' => 'kitchen-and-hallway-renovation',
                'title' => 'A kitchen that finally reaches the hallway',
                'project_type' => 'Whole-home renovation',
                'category' => 'Whole home',
                'city' => 'Tuolumne County',
                'completion_year' => null,
                'duration_weeks' => null,
                'is_featured' => 0,
                'service_slug' => 'kitchen-remodeling',
                'short_description' => 'White shaker cabinetry and marble-look quartz carried to the '
                    . 'hallway opening, with new flooring run through both.',
                'full_description' => 'The kitchen and the hallway beside it were treated as one room '
                    . 'rather than two, because that is how the house is actually used. The same '
                    . 'flooring runs through both with no threshold, the wall colour changes only at '
                    . 'the opening, and the cabinetry stops cleanly at the corner instead of being '
                    . 'squeezed around it. New appliances were set last so the finished surfaces were '
                    . 'never used as a workbench.',
                'materials' => 'Marble-look quartz · white shaker cabinetry · luxury vinyl plank · '
                    . 'brushed nickel hardware',
                'scope' => 'Kitchen cabinetry and counters, appliances, hallway flooring and paint',
                'notes' => [
                    [
                        'n' => '01',
                        'title' => 'One floor, two rooms',
                        'body' => 'Plank carried through the opening without a threshold strip, which '
                            . 'is what makes a small kitchen read as part of the house.',
                    ],
                    [
                        'n' => '02',
                        'title' => 'Cabinetry stops at the corner',
                        'body' => 'Nothing wrapped awkwardly around the opening. The run ends and the '
                            . 'hallway begins.',
                    ],
                ],
                'disclosure' => 'One photograph of this job.',
                'images' => [
                    [
                        'image_path' => 'images/projects/service-wholehome-detail.jpg',
                        'image_role' => 'hero',
                        'alt_text' => 'Renovated kitchen corner with white shaker cabinetry, '
                            . 'marble-look quartz and a new stainless refrigerator, opening onto a '
                            . 'hallway with new flooring',
                        'caption' => 'The corner where the kitchen meets the hallway',
                        'sort_order' => 1,
                    ],
                ],
            ],

            'two-tone-kitchen-finishes' => [
                'slug' => 'two-tone-kitchen-finishes',
                'title' => 'Two-tone cabinetry, matte black, and light where it is needed',
                'project_type' => 'Kitchen remodel',
                'category' => 'Kitchen',
                'city' => 'Tuolumne County',
                'completion_year' => null,
                'duration_weeks' => null,
                'is_featured' => 0,
                'service_slug' => 'kitchen-remodeling',
                'short_description' => 'White uppers over grey lowers, a gloss stacked-tile '
                    . 'backsplash, quartz counters and under-cabinet lighting.',
                'full_description' => 'A finish decision that costs nothing structurally and changes '
                    . 'the room completely: white upper cabinets to keep the walls light, darker '
                    . 'lowers so the working half of the kitchen does not show every mark, and matte '
                    . 'black hardware as the only strong contrast in the room. The under-cabinet '
                    . 'lighting is the part people notice last and value most — it is the difference '
                    . 'between a kitchen you can cook in after dark and one you cannot.',
                'materials' => 'Quartz counters · gloss stacked ceramic tile · two-tone shaker '
                    . 'cabinetry · matte black hardware · under-cabinet lighting',
                'scope' => 'Cabinetry, counters, backsplash, under-cabinet lighting, appliances',
                'notes' => [
                    [
                        'n' => '01',
                        'title' => 'Light on the work surface, not the ceiling',
                        'body' => 'Continuous under-cabinet strip, so the person at the counter is not '
                            . 'standing in their own shadow.',
                    ],
                    [
                        'n' => '02',
                        'title' => 'Contrast kept to the hardware',
                        'body' => 'One strong material in the room. Matte black pulls and knobs against '
                            . 'otherwise quiet surfaces.',
                    ],
                ],
                'disclosure' => 'One photograph of this job.',
                'images' => [
                    [
                        'image_path' => 'images/projects/transformation-complete.jpg',
                        'image_role' => 'detail',
                        'alt_text' => 'Kitchen counter detail with a quartz surface, gloss white '
                            . 'stacked-tile backsplash, under-cabinet lighting and matte black hardware',
                        'caption' => 'Quartz, gloss tile, and under-cabinet light',
                        'sort_order' => 1,
                    ],
                ],
            ],
        ];
    }

    public static function service(string $slug): ?array
    {
        return self::services()[$slug] ?? null;
    }

    /**
     * Consultation-form project types. Duplicated from HomeController for
     * now because both read from what will become a single `site_settings`
     * / lookup table; LeadController validates the submitted value against
     * its own constants regardless, so the two cannot silently diverge into
     * an accepted-but-invalid option.
     */
    public static function projectTypes(): array
    {
        return [
            'kitchen' => 'Kitchen Remodeling',
            'bathroom' => 'Bathroom Remodeling',
            'whole-home' => 'Whole-Home Renovation',
            'addition-adu' => 'Addition / ADU',
            'other' => 'Other / Not Sure Yet',
        ];
    }

    public static function project(string $slug): ?array
    {
        return self::projects()[$slug] ?? null;
    }

    /** @return array<int, array> */
    public static function projectsBySlugs(array $slugs): array
    {
        $all = self::projects();
        $out = [];

        foreach ($slugs as $slug) {
            if (isset($all[$slug])) {
                $out[] = $all[$slug];
            }
        }

        return $out;
    }

    /**
     * Published service slugs, for the sitemap and for nav. ADU is not
     * here because it does not exist — Tier D.
     */
    public static function publishedServiceSlugs(): array
    {
        return array_keys(self::services());
    }

    public static function publishedProjectSlugs(): array
    {
        return array_keys(self::projects());
    }

    /**
     * The first image of a given role, or null. Templates ask for a role
     * and never for a filename — the same contract the CMS will honour.
     */
    public static function imageByRole(array $project, string ...$roles): ?array
    {
        foreach ($roles as $role) {
            foreach ($project['images'] ?? [] as $image) {
                if (($image['image_role'] ?? '') === $role) {
                    return $image;
                }
            }
        }

        return null;
    }
}
