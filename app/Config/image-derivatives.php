<?php

declare(strict_types=1);

/**
 * Focal points for images whose subject is not in the centre.
 *
 * Twenty of the twenty-one project photographs are phone-shot portraits.
 * When one is displayed in a landscape or square frame, a centre crop
 * frequently lands on a floor or a ceiling rather than the work. Rather
 * than re-crop the originals (which are the only re-derivable source),
 * the focal point is stored here and emitted as `object-position`, so the
 * same file crops correctly in every frame it appears in.
 *
 * Values are 0..1 fractions of the image: x=0 is the left edge, y=0 the
 * top. Anything not listed defaults to dead centre (0.5, 0.5).
 *
 * Keys are paths relative to public/assets/, matching the manifest.
 *
 * Phase 2 note: once the CMS exists these move to `focal_x` / `focal_y`
 * columns on the image record and are set from the admin on upload. This
 * file is the interim source of truth and the shape is deliberately the
 * same, so the migration is a straight copy.
 */

/**
 * @return array<string, array{x: float, y: float}>
 */
function image_focal_overrides(): array
{
    return [
        // Roof tear-off: the sheathing and staged bundles are the subject,
        // upper-middle of the frame. A centre crop drifts onto the deck.
        'images/projects/narrative-progress.jpg' => ['x' => 0.5, 'y' => 0.38],

        // Finished hillside roof: the roofline sits high; a centre crop
        // fills with deck boards and loses the thing that was built.
        'images/projects/narrative-result.jpg' => ['x' => 0.5, 'y' => 0.55],

        // Tall kitchen portrait — the run of cabinets and the backsplash
        // are in the upper half.
        'images/projects/service-kitchen.jpg' => ['x' => 0.45, 'y' => 0.42],

        // Bathroom mid-installation: the open drain and new tile are low
        // in the frame and are the entire point of the photograph.
        'images/projects/process-detail.jpg' => ['x' => 0.5, 'y' => 0.62],
    ];
}
