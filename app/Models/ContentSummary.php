<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/**
 * Read-only counts used by the admin dashboard cards. Intentionally
 * separate from any future Page/Service/Project CRUD models — this
 * exists purely to summarize content status, not to manage it.
 */
final class ContentSummary extends Model
{
    public function counts(): array
    {
        return [
            'pages' => (int) $this->db->query('SELECT COUNT(*) FROM pages')->fetchColumn(),
            'services' => (int) $this->db->query('SELECT COUNT(*) FROM services')->fetchColumn(),
            'projects' => (int) $this->db->query('SELECT COUNT(*) FROM projects')->fetchColumn(),
            'testimonials' => (int) $this->db->query('SELECT COUNT(*) FROM testimonials')->fetchColumn(),
            'leads_new' => (int) $this->db->query("SELECT COUNT(*) FROM leads WHERE status = 'new'")->fetchColumn(),
            'leads_total' => (int) $this->db->query('SELECT COUNT(*) FROM leads')->fetchColumn(),
        ];
    }
}
