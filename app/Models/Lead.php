<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/**
 * Extends the existing Phase 1 `leads` / `lead_attachments` tables
 * (see database/schema.sql) — no schema changes. The guided form
 * collects a couple of fields the table has no dedicated column for
 * (timeframe, budget range, preferred contact method); those are
 * folded into the existing free-text `message` column as a labeled
 * summary rather than altering the data model.
 */
final class Lead extends Model
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO leads (name, email, phone, city, project_type, message, source_page, status, ip_address, created_at, updated_at)
             VALUES (:name, :email, :phone, :city, :project_type, :message, :source_page, :status, :ip_address, NOW(), NOW())'
        );

        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'project_type' => $data['project_type'],
            'message' => $data['message'],
            'source_page' => $data['source_page'],
            'status' => 'new',
            'ip_address' => $data['ip_address'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function addAttachment(int $leadId, string $filePath, string $originalFilename, string $mimeType, int $fileSizeBytes): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO lead_attachments (lead_id, file_path, original_filename, mime_type, file_size_bytes, created_at)
             VALUES (:lead_id, :file_path, :original_filename, :mime_type, :file_size_bytes, NOW())'
        );

        $stmt->execute([
            'lead_id' => $leadId,
            'file_path' => $filePath,
            'original_filename' => $originalFilename,
            'mime_type' => $mimeType,
            'file_size_bytes' => $fileSizeBytes,
        ]);
    }
}
