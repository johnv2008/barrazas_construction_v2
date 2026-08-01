<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Logger;
use App\Core\Request;
use App\Models\Lead;
use App\Services\MailService;
use App\Services\SessionService;
use App\Validation\Validator;

/**
 * Handles the guided "Start Your Project" form submission. Extends
 * the existing leads / lead_attachments tables (Phase 1 schema) —
 * no database changes. See app/Models/Lead.php for how fields the
 * table has no dedicated column for (timeframe, budget, preferred
 * contact method) are folded into the existing message column.
 */
final class LeadController extends Controller
{
    private const PROJECT_TYPES = [
        'kitchen' => 'Kitchen Remodeling',
        'bathroom' => 'Bathroom Remodeling',
        'whole-home' => 'Whole-Home Renovation',
        'addition-adu' => 'Addition / ADU',
        'other' => 'Other / Not Sure Yet',
    ];

    private const TIMEFRAMES = [
        'asap' => 'As soon as possible',
        '1-3-months' => '1–3 months',
        '3-6-months' => '3–6 months',
        '6-plus-months' => '6+ months',
        'exploring' => 'Just exploring',
    ];

    private const BUDGETS = [
        'under-25k' => 'Under $25,000',
        '25k-75k' => '$25,000 – $75,000',
        '75k-150k' => '$75,000 – $150,000',
        '150k-plus' => '$150,000+',
        'not-sure' => 'Not sure yet',
    ];

    private const CONTACT_METHODS = [
        'email' => 'Email',
        'phone' => 'Phone',
        'either' => 'Either',
    ];

    private const MAX_UPLOAD_BYTES = 8 * 1024 * 1024; // 8MB
    private const ALLOWED_UPLOAD_MIME = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    public function store(Request $request, array $params): void
    {
        // Honeypot: a hidden field real visitors never fill in. Bots
        // that auto-fill every field will trip it. We accept silently
        // (redirect to the same success state) without creating a
        // lead, so the bot has no signal its submission was rejected.
        if ($request->string('website') !== '') {
            SessionService::flash('lead_success', "Thanks — we'll be in touch soon.");
            $this->redirect(base_url('/#start-your-project'));
        }

        $validator = (new Validator($request->all()))
            ->required('name', 'Name')
            ->required('email', 'Email')
            ->email('email', 'Email')
            ->required('project_type', 'Project type')
            ->in('project_type', array_keys(self::PROJECT_TYPES), 'Project type')
            ->required('city', 'Project city')
            ->required('consent', 'Consent');

        if ($validator->fails()) {
            SessionService::setOldInput($request->only([
                'name', 'email', 'phone', 'city', 'project_type', 'timeframe', 'budget', 'preferred_contact', 'description',
            ]));
            SessionService::flash('lead_error', $validator->firstError() ?? 'Please check the form and try again.');
            $this->redirect(base_url('/#start-your-project'));
        }

        $attachmentPath = null;
        $attachmentMeta = null;

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->handleUpload($_FILES['photo']);

            if ($uploadResult === null) {
                SessionService::setOldInput($request->only([
                    'name', 'email', 'phone', 'city', 'project_type', 'timeframe', 'budget', 'preferred_contact', 'description',
                ]));
                SessionService::flash('lead_error', 'That photo could not be uploaded. Please use a JPG, PNG, or WebP under 8MB.');
                $this->redirect(base_url('/#start-your-project'));
            }

            [$attachmentPath, $attachmentMeta] = $uploadResult;
        }

        $message = $this->composeMessage($request);

        $leadId = (new Lead())->create([
            'name' => $request->string('name'),
            'email' => $request->string('email'),
            'phone' => $request->string('phone'),
            'city' => $request->string('city'),
            'project_type' => self::PROJECT_TYPES[$request->string('project_type')] ?? $request->string('project_type'),
            'message' => $message,
            'source_page' => '/',
            'ip_address' => $request->ip(),
        ]);

        if ($attachmentPath !== null && $attachmentMeta !== null) {
            (new Lead())->addAttachment(
                $leadId,
                $attachmentPath,
                $attachmentMeta['original_filename'],
                $attachmentMeta['mime_type'],
                $attachmentMeta['size']
            );
        }

        // The lead is already stored, so notification is best-effort by
        // design: a mail failure must never turn a captured lead into an
        // error page for the homeowner. It is logged instead, and the
        // record survives in the leads table either way.
        try {
            $this->notify($request, $leadId, $message, $attachmentPath);
        } catch (\Throwable $e) {
            Logger::exception($e);
        }

        SessionService::flash('lead_success', "Thanks, {$request->string('name')} — we've received your project details and will be in touch soon.");
        $this->redirect(base_url('/#start-your-project'));
    }

    /**
     * Emails the office a readable summary of a submission.
     *
     * Reply-To is the homeowner, so replying from the inbox reaches them
     * directly with no copying of addresses. Everything needed to respond
     * is in the body, because the admin leads page is still a placeholder
     * and this email is currently the only place a lead is legible.
     */
    private function notify(Request $request, int $leadId, string $message, ?string $attachmentPath): void
    {
        $to = trim((string) config('mail.lead_to', ''));

        if ($to === '') {
            return;
        }

        $name = $request->string('name');
        $city = $request->string('city');
        $projectType = self::PROJECT_TYPES[$request->string('project_type')] ?? $request->string('project_type');
        $phone = $request->string('phone');

        $subject = 'New consultation request — ' . $projectType . ($city !== '' ? ', ' . $city : '');

        $lines = [
            'A new consultation request came in through barrazasconstruction.com.',
            '',
            'Name:     ' . $name,
            'Email:    ' . $request->string('email'),
            'Phone:    ' . ($phone !== '' ? $phone : 'Not provided'),
            'City:     ' . $city,
            'Project:  ' . $projectType,
            '',
            $message,
        ];

        if ($attachmentPath !== null) {
            $lines[] = '';
            $lines[] = 'Photo attached: ' . base_url($attachmentPath);
        }

        $lines[] = '';
        $lines[] = '--';
        $lines[] = 'Reply to this email to answer ' . $name . ' directly.';
        $lines[] = 'Lead #' . $leadId . ' · received ' . date('j F Y, g:ia');

        MailService::send($to, $subject, implode("\n", $lines), $request->string('email'));
    }

    private function composeMessage(Request $request): string
    {
        $timeframe = self::TIMEFRAMES[$request->string('timeframe')] ?? 'Not specified';
        $budget = self::BUDGETS[$request->string('budget')] ?? 'Not specified';
        $contactMethod = self::CONTACT_METHODS[$request->string('preferred_contact')] ?? 'Not specified';
        $description = trim((string) $request->input('description', ''));

        $lines = [
            "Desired timeframe: {$timeframe}",
            "Estimated investment range: {$budget}",
            "Preferred contact method: {$contactMethod}",
            '',
            'Project description:',
            $description !== '' ? $description : '(none provided)',
        ];

        return implode("\n", $lines);
    }

    /**
     * @return array{0: string, 1: array{original_filename: string, mime_type: string, size: int}}|null
     */
    private function handleUpload(array $file): ?array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if ($file['size'] <= 0 || $file['size'] > self::MAX_UPLOAD_BYTES) {
            return null;
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if ($mimeType === false || !isset(self::ALLOWED_UPLOAD_MIME[$mimeType])) {
            return null;
        }

        $extension = self::ALLOWED_UPLOAD_MIME[$mimeType];
        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        // public_path() rather than APP_ROOT . '/public', which is wrong on
        // the shared host: it flattens public/ into the web root, so this
        // wrote photographs into a public/uploads/ directory it created
        // outside the served tree while the database recorded the path
        // uploads/general/leads/... — leaving every attachment unreachable.
        $destinationDir = public_path('uploads/general/leads');

        if (!is_dir($destinationDir) && !mkdir($destinationDir, 0755, true) && !is_dir($destinationDir)) {
            return null;
        }

        if (!move_uploaded_file($file['tmp_name'], $destinationDir . '/' . $filename)) {
            return null;
        }

        return [
            'uploads/general/leads/' . $filename,
            [
                'original_filename' => basename((string) $file['name']),
                'mime_type' => $mimeType,
                'size' => (int) $file['size'],
            ],
        ];
    }
}
