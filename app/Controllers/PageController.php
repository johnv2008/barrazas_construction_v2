<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

/**
 * Standing pages that are not services or projects.
 *
 * Currently just the privacy notice, which exists because the consent
 * banner links to it — a request for consent that does not say what is
 * being consented to is not consent.
 */
final class PageController extends Controller
{
    public function privacy(Request $request, array $params): void
    {
        $this->view('frontend/privacy', [
            'title' => "Privacy · Barraza's Construction",
            'metaDescription' => 'What Barraza\'s Construction collects through this website, '
                . 'why, and how to have it removed.',
            'chapters' => [
                ['n' => '01', 'id' => 'top', 'label' => 'Privacy'],
            ],
            // Analytics is described here, so the page must not be the one
            // place a visitor cannot read about it without being counted.
            // It behaves like every other page: nothing loads without consent.
            'analyticsEnabled' => trim((string) config('analytics.ga_measurement_id', '')) !== '',
            'updated' => 'July 2026',
        ], 'layouts/frontend');
    }

    /**
     * Terms of USE — for the website. Deliberately not the construction
     * contract, and the page says so first, because conflating the two
     * would invite the argument that a website disclaimer limits liability
     * for the actual building work.
     */
    public function terms(Request $request, array $params): void
    {
        $this->view('frontend/terms', [
            'title' => "Terms of Use · Barraza's Construction",
            'metaDescription' => "Terms for using the Barraza's Construction website. "
                . 'Project work is governed by a separate written agreement.',
            'chapters' => [
                ['n' => '01', 'id' => 'top', 'label' => 'Terms'],
            ],
            'updated' => 'July 2026',
        ], 'layouts/frontend');
    }
}
