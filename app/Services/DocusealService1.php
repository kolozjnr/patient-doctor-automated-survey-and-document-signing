<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DocuSealService
{
    private string $apiKey;
    private string $baseUrl;

     private const PLACEHOLDER_PATTERNS = [
        '{{Signature',
        '{{Name}}',
        '{{Date',
        '{{Email',
        '{{Phone',
        '{{Checkbox',
        '{{Select',
        '{{Initials',
    ];


    public function __construct()
    {
        $this->apiKey  = config('services.docuseal.api_key');
        $this->baseUrl = rtrim(config('services.docuseal.base_url', 'https://api.docuseal.com'), '/');
    }

// public function test()
//     {
//         return "This is a test docuseal service.";
//     }

  /**
     * Create a DocuSeal template.
     * - If PDF has placeholders → send as-is (DocuSeal reads them natively)
     * - If PDF has no placeholders → append a signature page then send
     * - If custom placeholders passed from UI → use coordinate-based fields
     */
    public function createTemplate(string $name, UploadedFile $file, array $placeholders = []): int
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $tempPath  = $file->getRealPath();

        $endpoint = match($extension) {
            'pdf'         => '/templates/pdf',
            'docx', 'doc' => '/templates/docx',
            default       => throw new \InvalidArgumentException(
                "Unsupported file type: {$extension}. Use PDF or DOCX."
            ),
        };

        // For DOCX — always use coordinate fields (can't inspect text easily)
        if ($extension !== 'pdf') {
            return $this->sendToDocuSeal($name, $tempPath, $endpoint, $placeholders);
        }

        // Check if PDF already has DocuSeal placeholders
        if ($this->pdfHasPlaceholders($tempPath)) {
            Log::info('PDF has placeholders — sending as-is to DocuSeal', ['name' => $name]);
            // Send raw — DocuSeal will auto-detect {{...}} placeholders
            return $this->sendToDocuSeal($name, $tempPath, $endpoint, []);
        }

        // No placeholders in PDF
        if (!empty($placeholders)) {
            // User defined custom fields via UI — use coordinate-based approach
            Log::info('Using custom UI placeholders', ['count' => count($placeholders)]);
            return $this->sendToDocuSeal($name, $tempPath, $endpoint, $placeholders);
        }

        // No placeholders anywhere — append a signature page to the PDF
        Log::info('No placeholders found — appending signature page', ['name' => $name]);
        $mergedPath = $this->appendSignaturePage($tempPath, $name);

        try {
            return $this->sendToDocuSeal($name, $mergedPath, $endpoint, []);
        } finally {
            // Clean up merged temp file
            if (file_exists($mergedPath)) {
                unlink($mergedPath);
            }
        }
    }

    /**
     * Scan PDF text content for DocuSeal placeholder patterns.
     */
    private function pdfHasPlaceholders(string $pdfPath): bool
    {
        try {
            $content = file_get_contents($pdfPath);

            foreach (self::PLACEHOLDER_PATTERNS as $pattern) {
                if (str_contains($content, $pattern)) {
                    return true;
                }
            }

            return false;
        } catch (\Throwable $e) {
            Log::warning('Could not scan PDF for placeholders', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Append a new signature page to the PDF using FPDI.
     * The page uses DocuSeal's {{...}} placeholder syntax so they are
     * auto-detected without needing coordinate fields.
     */
    private function appendSignaturePage(string $originalPdfPath, string $documentName): string
    {
        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false);

        // 1. Import all existing pages from the original PDF
        $pageCount = $pdf->setSourceFile($originalPdfPath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);

            $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
        }

        // 2. Add signature page (A4)
        $pdf->AddPage('P', [210, 297]);
        $pdf->SetMargins(20, 20, 20);

        // Header
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetY(20);
        $pdf->Cell(0, 10, 'Signature Page', 0, 1, 'C');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 6, 'Please complete the fields below to sign this document.', 0, 1, 'C');
        $pdf->Ln(10);

        // Divider
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
        $pdf->Ln(10);

        // Full Name field
        $pdf->SetTextColor(60, 60, 60);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(0, 7, 'Full Name', 0, 1);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 7, '{{Name}}', 0, 1);  // DocuSeal text placeholder
        $pdf->Ln(6);

        // Date field
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(0, 7, 'Date', 0, 1);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 7, '{{Date;type=date}}', 0, 1);
        $pdf->Ln(6);

        // Signature field — takes more vertical space
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(0, 7, 'Signature', 0, 1);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 20, '{{Signature;type=signature}}', 0, 1);
        $pdf->Ln(4);

        // Footer divider
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
        $pdf->Ln(6);
        $pdf->SetFont('Helvetica', 'I', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 6, 'This document was prepared by ' . $documentName, 0, 1, 'C');

        // Save merged PDF to temp file
        $mergedPath = storage_path('app/tmp/merged_' . uniqid() . '.pdf');

        if (!is_dir(dirname($mergedPath))) {
            mkdir(dirname($mergedPath), 0755, true);
        }

        $pdf->Output('F', $mergedPath);

        Log::info('Signature page appended', [
            'original_pages' => $pageCount,
            'merged_path'    => $mergedPath,
        ]);

        return $mergedPath;
    }

    /**
     * Encode PDF and send to DocuSeal.
     * If $placeholders empty → DocuSeal auto-detects {{...}} from PDF text.
     * If $placeholders provided → use coordinate-based fields.
     */
    private function sendToDocuSeal(
        string $name,
        string $filePath,
        string $endpoint,
        array $placeholders
    ): int {
        $base64  = base64_encode(file_get_contents($filePath));
        $docName = pathinfo($filePath, PATHINFO_FILENAME);

        $document = [
            'name' => $docName,
            'file' => $base64,
        ];

        // Only add fields array if using coordinate-based approach
        if (!empty($placeholders)) {
            $lastPage            = $this->getPdfPageCount($filePath);
            $document['fields'] = $this->buildFieldsFromPlaceholders($placeholders, $lastPage);
        }

        $payload = [
            'name'      => $name,
            'documents' => [$document],
        ];

        $response = Http::withHeaders([
            'X-Auth-Token' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}{$endpoint}", $payload);

        if (!$response->successful()) {
            Log::error('DocuSeal template creation failed', [
                'status'   => $response->status(),
                'body'     => $response->body(),
                'endpoint' => $endpoint,
            ]);
            throw new \RuntimeException('DocuSeal template creation failed: ' . $response->body());
        }

        $templateId = $response->json('id');

        if (!$templateId) {
            throw new \RuntimeException('DocuSeal returned no template ID. Response: ' . $response->body());
        }

        Log::info('DocuSeal template created', ['template_id' => $templateId, 'name' => $name]);

        return (int) $templateId;
    }

    /**
     * Build coordinate-based fields from UI placeholder definitions.
     */
    private function buildFieldsFromPlaceholders(array $placeholders, int $lastPage): array
    {
        $fields = [];
        $yStart = 0.75;
        $yStep  = 0.08;
        $xLeft  = 0.05;
        $xRight = 0.55;
        $col    = 0;

        foreach ($placeholders as $ph) {
            $type        = $this->sanitizeFieldType($ph['type'] ?? 'text');
            $label       = $ph['label'] ?? $ph['key'] ?? 'Field';
            $required    = $ph['required'] ?? false;
            $options     = $ph['options'] ?? '';
            $isSignature = $type === 'signature';

            $x = $isSignature ? $xLeft : ($col === 0 ? $xLeft : $xRight);
            $w = $isSignature ? 0.40 : 0.38;
            $h = $isSignature ? 0.07 : 0.04;

            $field = [
                'name'     => $label,
                'type'     => $type,
                'role'     => 'Patient',
                'required' => (bool) $required,
                'areas'    => [[
                    'x' => $x, 'y' => $yStart,
                    'w' => $w, 'h' => $h,
                    'page' => $lastPage,
                ]],
            ];

            if ($type === 'select' && !empty($options)) {
                $field['options'] = array_map('trim', explode(',', $options));
            }

            $fields[] = $field;

            if ($isSignature || $col === 1) {
                $yStart += $yStep;
                $col = 0;
            } else {
                $col = 1;
            }
        }

        return $fields;
    }

    private function sanitizeFieldType(string $type): string
    {
        $valid = [
            'text', 'number', 'signature', 'initials', 'date', 'image',
            'file', 'payment', 'verification', 'stamp', 'select', 'checkbox',
            'multiple', 'radio', 'cells', 'phone', 'heading', 'kba',
        ];

        $map = [
            'email'    => 'text',
            'textarea' => 'text',
            'bool'     => 'checkbox',
            'boolean'  => 'checkbox',
            'datetime' => 'date',
        ];

        $type = strtolower(trim($type));
        return $map[$type] ?? (in_array($type, $valid) ? $type : 'text');
    }

    private function getPdfPageCount(string $filePath): int
    {
        try {
            $content = file_get_contents($filePath);
            $count   = preg_match_all('/\/Page\b/', $content, $matches);
            return $count > 0 ? $count : 1;
        } catch (\Throwable $e) {
            return 1;
        }
    }


    //THIS XXX METHODS WERE WORKING WHEN IT WAS FLEXIBLE ALLOWING USERS TO SELECT PLACEHOLDERS FROM FRONT END
    /**
     * Create a DocuSeal template with a signature field at the bottom.
     *
     * Field area coordinates are percentages (0.0 – 1.0) of the page dimensions:
     *   x, y = top-left corner of the field
     *   w, h = width and height of the field
     *
     * Signature box placed at bottom-center of the LAST page:
     *   x: 0.05  (5% from left  — left edge of box)
     *   y: 0.88  (88% from top  — near bottom)
     *   w: 0.40  (40% of page width)
     *   h: 0.07  (7% of page height — enough height to draw a signature)
     */
    public function createTemplateXXX(string $name, UploadedFile $file, array $placeholders = []): int
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $base64    = base64_encode(file_get_contents($file->getRealPath()));
        $docName   = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $lastPage  = max(1, $this->getPdfPageCount($file));

        $endpoint = match($extension) {
            'pdf'         => '/templates/pdf',
            'docx', 'doc' => '/templates/docx',
            default       => throw new \InvalidArgumentException(
                "Unsupported file type: {$extension}. Use PDF or DOCX."
            ),
        };

        // Build fields from placeholders or fall back to default
        $fields = !empty($placeholders)
            ? $this->buildFieldsFromPlaceholders($placeholders, $lastPage)
            : $this->buildDefaultFields($lastPage);

        $payload = [
            'name'      => $name,
            'documents' => [[
                'name'   => $docName,
                'file'   => $base64,
                'fields' => $fields,
            ]],
        ];

        $response = Http::withHeaders([
            'X-Auth-Token' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}{$endpoint}", $payload);

        if (!$response->successful()) {
            Log::error('DocuSeal template creation failed', [
                'status'   => $response->status(),
                'body'     => $response->body(),
                'endpoint' => $endpoint,
            ]);
            throw new \RuntimeException('DocuSeal template creation failed: ' . $response->body());
        }

        $templateId = $response->json('id');

        if (!$templateId) {
            throw new \RuntimeException('DocuSeal returned no template ID. Response: ' . $response->body());
        }

        return (int) $templateId;
    }

    private function sanitizeFieldTypeXXX(string $type): string
    {
        $validTypes = [
            'text', 'number', 'signature', 'initials', 'date', 'image',
            'file', 'payment', 'verification', 'stamp', 'select', 'checkbox',
            'multiple', 'radio', 'cells', 'phone', 'heading', 'kba',
        ];

        // Map unsupported types to closest valid equivalent
        $typeMap = [
            'email'     => 'text',
            'textarea'  => 'text',
            'string'    => 'text',
            'integer'   => 'number',
            'float'     => 'number',
            'bool'      => 'checkbox',
            'boolean'   => 'checkbox',
            'datetime'  => 'date',
            'time'      => 'date',
        ];

        $type = strtolower(trim($type));

        return $typeMap[$type] ?? (in_array($type, $validTypes) ? $type : 'text');
    }

    /**
     * Default fields: Signature (bottom-left) + Date (bottom-right)
     */
    private function buildDefaultFieldsXXX(int $lastPage): array
    {
        return [
            [
                'name'     => 'Signature',
                'type'     => 'signature',
                'role'     => 'Patient',
                'required' => true,
                'areas'    => [[
                    'x' => 0.05, 'y' => 0.88,
                    'w' => 0.40, 'h' => 0.07,
                    'page' => $lastPage,
                ]],
            ],
            [
                'name'     => 'Signed Date',
                'type'     => 'date',
                'role'     => 'Patient',
                'required' => true,
                'areas'    => [[
                    'x' => 0.55, 'y' => 0.88,
                    'w' => 0.20, 'h' => 0.04,
                    'page' => $lastPage,
                ]],
            ],
        ];
    }

    /**
     * Build DocuSeal fields from frontend placeholder definitions.
     * Fields are stacked vertically starting from y=0.75, spaced by 0.08.
     * Signature gets more height; others are standard.
     */
    private function buildFieldsFromPlaceholdersXXX(array $placeholders, int $lastPage): array
{
    $fields = [];
    $yStart = 0.75;
    $yStep  = 0.08;
    $xLeft  = 0.05;
    $xRight = 0.55;
    $col    = 0;

    foreach ($placeholders as $ph) {
        $type     = $this->sanitizeFieldType($ph['type'] ?? 'text'); // ← sanitize here
        $label    = $ph['label']    ?? $ph['key'] ?? 'Field';
        $required = $ph['required'] ?? false;
        $options  = $ph['options']  ?? '';

        $isSignature = $type === 'signature';
        $x = $isSignature ? $xLeft : ($col === 0 ? $xLeft : $xRight);
        $w = $isSignature ? 0.40   : 0.38;
        $h = $isSignature ? 0.07   : 0.04;

        $field = [
            'name'     => $label,
            'type'     => $type,
            'role'     => 'Patient',
            'required' => (bool) $required,
            'areas'    => [[
                'x'    => $x,
                'y'    => $yStart,
                'w'    => $w,
                'h'    => $h,
                'page' => $lastPage,
            ]],
        ];

        if ($type === 'select' && !empty($options)) {
            $field['options'] = array_map('trim', explode(',', $options));
        }

        $fields[] = $field;

        if ($isSignature || $col === 1) {
            $yStart += $yStep;
            $col = 0;
        } else {
            $col = 1;
        }
    }

    return $fields;
}


public function createTemplateXX(string $name, UploadedFile $file, array $placeholders = []): int
{
    $extension = strtolower($file->getClientOriginalExtension());
    $base64    = base64_encode(file_get_contents($file->getRealPath()));
    $docName   = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $lastPage  = max(1, $this->getPdfPageCount($file));

    $endpoint = match($extension) {
        'pdf'         => '/templates/pdf',
        'docx', 'doc' => '/templates/docx',
        default       => throw new \InvalidArgumentException(
            "Unsupported file type: {$extension}. Use PDF or DOCX."
        ),
    };

    // Build fields from placeholders or fall back to default
    $fields = !empty($placeholders)
        ? $this->buildFieldsFromPlaceholders($placeholders, $lastPage)
        : $this->buildDefaultFields($lastPage);

    $payload = [
        'name'      => $name,
        'documents' => [[
            'name'   => $docName,
            'file'   => $base64,
            'fields' => $fields,
        ]],
    ];

    $response = Http::withHeaders([
        'X-Auth-Token' => $this->apiKey,
        'Content-Type' => 'application/json',
    ])->post("{$this->baseUrl}{$endpoint}", $payload);

    if (!$response->successful()) {
        Log::error('DocuSeal template creation failed', [
            'status'   => $response->status(),
            'body'     => $response->body(),
            'endpoint' => $endpoint,
        ]);
        throw new \RuntimeException('DocuSeal template creation failed: ' . $response->body());
    }

    $templateId = $response->json('id');

    if (!$templateId) {
        throw new \RuntimeException('DocuSeal returned no template ID. Response: ' . $response->body());
    }

    return (int) $templateId;
}


public function uploadFromPathXX(string $filePath, string $storagePath): string
{
    $response = Http::withHeaders([
        'AccessKey'    => $this->password,
        'Content-Type' => 'application/octet-stream',
    ])->withBody(
        file_get_contents($filePath),
        'application/octet-stream'
    )->put("{$this->baseUrl}/{$storagePath}");

    if (!$response->successful()) {
        throw new \RuntimeException('Bunny upload failed: ' . $response->body());
    }

    // Return CDN URL not storage URL
    return "{$this->cdnUrl}/{$storagePath}";
}


   

    public function createSubmission(int $templateId, int $userId, string $email, string $name, string $externalId): array 
    {
        $response = Http::withHeaders([
            'X-Auth-Token' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/submissions", [
            'template_id' => $templateId,
            'submitters'  => [
                [
                    'role'        => 'Patient',
                    'email'       => $email,
                    'name'        => $name,
                    'external_id' => (string) $externalId,
                    'send_email'  => false,
                ],
            ],
        ]);

        if (!$response->successful()) {
            Log::error('DocuSeal submission creation failed', [
                'status'      => $response->status(),
                'body'        => $response->body(),
                'template_id' => $templateId,
                'user_id'     => $userId,
            ]);
            throw new \RuntimeException('DocuSeal submission creation failed: ' . $response->body());
        }

        // Response is an array of submitters
        $submitters = $response->json();
        $submitter  = $submitters[0] ?? null;

        if (!$submitter || empty($submitter['slug'])) {
            throw new \RuntimeException(
                'DocuSeal submission returned no submitter slug. Response: ' . $response->body()
            );
        }

        return [
            'submission_id' => $submitter['submission_id'],
            'slug' => $submitter['slug'],  
            'signing_url'   => "{$this->baseUrl}/s/{$submitter['slug']}",
        ];
    }



    /**
     * Get the number of pages in a PDF using a lightweight byte scan.
     * Falls back to 1 if count cannot be determined (e.g. DOCX files).
     */
    private function getPdfPageCountXXX(UploadedFile $file): int
    {
        try {
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension !== 'pdf') {
                return 1; // DOCX — DocuSeal handles page detection internally
            }

            $content = file_get_contents($file->getRealPath());
            $count   = preg_match_all('/\/Page\b/', $content, $matches);

            return $count > 0 ? $count : 1;

        } catch (\Throwable $e) {
            Log::warning('Could not determine PDF page count, defaulting to 1', [
                'error' => $e->getMessage(),
            ]);
            return 1;
        }
    }
}