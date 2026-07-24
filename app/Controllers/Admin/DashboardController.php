<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Models\ActivityLog;
use App\Models\ContentSummary;
use App\Services\SessionService;

final class DashboardController extends Controller
{
    public function index(Request $request, array $params): void
    {
        $this->view('admin/dashboard', [
            'title' => 'Dashboard',
            'adminName' => SessionService::get('admin_name'),
            'summary' => (new ContentSummary())->counts(),
            'recentActivity' => (new ActivityLog())->recent(8),
        ], 'layouts/admin');
    }
}
