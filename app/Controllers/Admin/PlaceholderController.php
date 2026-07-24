<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;

/**
 * Renders the polished "coming in a later phase" screen for nav items
 * that don't have a working management module yet. Deliberately does
 * not fabricate any data-management behavior.
 */
final class PlaceholderController extends Controller
{
    public function pages(Request $request, array $params): void
    {
        $this->render('Pages', 'Build and edit site pages — homepage sections, About, Contact, and more — from a structured block editor.');
    }

    public function services(Request $request, array $params): void
    {
        $this->render('Services', 'Manage the services offered, their descriptions, icons, and homepage ordering.');
    }

    public function projects(Request $request, array $params): void
    {
        $this->render('Projects', 'Publish project case studies with categories, before/after photo galleries, and captions.');
    }

    public function testimonials(Request $request, array $params): void
    {
        $this->render('Testimonials', 'Collect and publish client testimonials with optional photos and project links.');
    }

    public function serviceAreas(Request $request, array $params): void
    {
        $this->render('Service Areas', 'Maintain the list of Bay Area cities and neighborhoods served.');
    }

    public function leads(Request $request, array $params): void
    {
        $this->render('Leads', 'Review and respond to consultation requests submitted through the website, including any attached photos.');
    }

    public function seo(Request $request, array $params): void
    {
        $this->render('SEO', 'Manage page titles, meta descriptions, Open Graph data, and structured data for every page.');
    }

    public function settings(Request $request, array $params): void
    {
        $this->render('Site Settings', 'Configure global site details such as contact information, social links, and business hours.');
    }

    public function activityLog(Request $request, array $params): void
    {
        $this->render('Activity Log', 'Full, searchable history of administrator actions across the site.');
    }

    public function administrators(Request $request, array $params): void
    {
        $this->render('Administrators', 'Invite and manage administrator accounts, roles, and access.');
    }

    private function render(string $title, string $description): void
    {
        $this->view('admin/placeholder', [
            'title' => $title,
            'description' => $description,
        ], 'layouts/admin');
    }
}
