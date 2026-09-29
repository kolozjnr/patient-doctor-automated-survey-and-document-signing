<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use setasign\Fpdi\Fpdi;

class BunnyStorageService
{
    protected string $storageZone;
    protected string $password;
    protected string $hostname;
    protected string $baseUrl;
    protected string $cdnUrl;

    private string $apiKey;
    private string $docusealBaseUrl;

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
         $this->validateConfiguration();

        $this->storageZone = config('services.bunny.storage_zone');
        $this->password = config('services.bunny.storage_password');
        $this->hostname = config('services.bunny.storage_hostname');
        $this->cdnUrl = rtrim(config('services.bunny.cdn_url'), '/');
        $this->securityKey = config('services.bunny.security_key');
        $this->baseUrl = "https://{$this->hostname}/{$this->storageZone}";


        //we are using docuseal here because of the rror
        $this->apiKey  = config('services.docuseal.api_key');
        $this->docusealBaseUrl = rtrim(config('services.docuseal.base_url', 'https://api.docuseal.com'), '/');
    }

    /**
     * Validate that all required configuration values are set
     */
    protected function validateConfiguration(): void
    {
        $requiredConfigs = [
            'storage_zone' => config('services.bunny.storage_zone'),
            'storage_password' => config('services.bunny.storage_password'),
            'storage_hostname' => config('services.bunny.storage_hostname'),
            'cdn_url' => config('services.bunny.cdn_url'),
            'security_key' => config('services.bunny.security_key'),
        ];

        $missingConfigs = [];

        foreach ($requiredConfigs as $key => $value) {
            if (empty($value)) {
                $missingConfigs[] = "services.bunny.{$key}";
            }
        }

        if (!empty($missingConfigs)) {
            throw new RuntimeException(
                'Bunny Storage configuration is incomplete. Missing: ' . 
                implode(', ', $missingConfigs) . 
                '. Please check your .env file and config/services.php'
            );
        }
    }


    /**
     * Upload a file to Bunny Storage
     */

    public function upload(UploadedFile $file, string $title = ''): string
    {
         set_time_limit(300);
         
        $extension = $file->getClientOriginalExtension();
        $filename  = Str::slug($title ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '-' . time() . '.' . $extension;

        $path = "documents/{$filename}";

        $response = Http::timeout(120)->withHeaders([
            'AccessKey'    => $this->password, // use $this->password not $this->securityKey
            'Content-Type' => 'application/octet-stream',
        ])->withBody(
            file_get_contents($file->getRealPath()),
            'application/octet-stream'
        )->put("https://{$this->hostname}/{$this->storageZone}/{$path}");

        if (!$response->successful()) {
            throw new RuntimeException('Bunny CDN upload failed: ' . $response->body());
        }

        // Return CDN URL (public), not storage URL
        return "{$this->cdnUrl}/{$path}";
    }
    public function uploadFromPath(string $filePath, string $storagePath): string
    {
        $response = Http::withHeaders([
            'AccessKey'    => $this->password,
            'Content-Type' => mime_content_type($filePath) ?: 'application/octet-stream',
        ])->withBody(
            file_get_contents($filePath),
            'application/octet-stream'
        )->put("{$this->baseUrl}/{$storagePath}");

        if (!$response->successful()) {
            throw new \RuntimeException('Bunny upload failed: ' . $response->body());
        }

        return "{$this->cdnUrl}/{$storagePath}";
    }

    public function uploadXXX(UploadedFile|string $file, string $path, bool $makePublic = true): array
    {
        try {
            $content = $file instanceof UploadedFile 
                ? file_get_contents($file->getRealPath())
                : file_get_contents($file);

            $response = Http::withHeaders([
                'AccessKey' => $this->password,
                'Content-Type' => $this->getMimeType($file),
            ])->withBody($content)
              ->put("{$this->baseUrl}/{$path}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $this->getPublicUrl($path),
                    'storage_url' => "{$this->baseUrl}/{$path}",
                ];
            }

            Log::error('Bunny Storage upload failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('Bunny Storage upload exception', [
                'message' => $e->getMessage(),
                'path' => $path,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

        /**
     * Generate a signed URL that expires after a certain time
     * 
     * @param string $path The file path
     * @param int $expiresInSeconds Time until expiration (default: 1 hour)
     * @param string|null $userIp Optional: Restrict to specific IP address
     * @return string Signed URL
     */
   public function getSignedUrl(string $path, int $expiresInSeconds = 3600, ?string $userIp = null): string
{
    // path should be: documents/file.pdf  (no leading slash, no zone prefix)
    $path    = ltrim($path, '/');
    $expires = time() + $expiresInSeconds;
    $url     = "{$this->cdnUrl}/{$path}";  // https://ecc-pain.b-cdn.net/documents/file.pdf
    $urlPath = parse_url($url, PHP_URL_PATH); // /documents/file.pdf

    $signatureBase = $this->securityKey . $urlPath . $expires;

    if ($userIp) {
        $signatureBase .= $userIp;
    }

    $token     = base64_encode(hash('sha256', $signatureBase, true));
    $token     = strtr($token, '+/', '-_');
    $token     = rtrim($token, '=');
    $signedUrl = $url . '?token=' . $token . '&expires=' . $expires;

    if ($userIp) {
        $signedUrl .= '&ip=' . $userIp;
    }

    return $signedUrl;
}
    /**
     * Get public URL (for backwards compatibility when not using signed URLs)
     */
    public function getPublicUrl(string $path): string
    {
        $path = ltrim($path, '/');
        return "{$this->cdnUrl}/{$path}";
    }



    /**
     * Delete a file from Bunny Storage
     */
    public function delete(string $path): bool
    {
        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->password,
            ])->delete("{$this->baseUrl}/{$path}");

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Bunny Storage delete exception', [
                'message' => $e->getMessage(),
                'path' => $path,
            ]);

            return false;
        }
    }

    /**
     * Download a file from Bunny Storage
     */
    public function download(string $path): ?string
    {
        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->password,
            ])->get("{$this->baseUrl}/{$path}");

            return $response->successful() ? $response->body() : null;

        } catch (\Exception $e) {
            Log::error('Bunny Storage download exception', [
                'message' => $e->getMessage(),
                'path' => $path,
            ]);

            return null;
        }
    }

    /**
     * List files in a directory
     */
    public function list(string $path = '/'): ?array
    {
        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->password,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/{$path}");

            return $response->successful() ? $response->json() : null;

        } catch (\Exception $e) {
            Log::error('Bunny Storage list exception', [
                'message' => $e->getMessage(),
                'path' => $path,
            ]);

            return null;
        }
    }

    /**
     * Get the public CDN URL for a file
     */
    // public function getPublicUrl(string $path): string
    // {
    //     return $this->cdnUrl . '/' . ltrim($path, '/');
    // }

    /**
     * Get MIME type of file
     */
    protected function getMimeType($file): string
    {
        if ($file instanceof UploadedFile) {
            return $file->getMimeType();
        }
        
        return mime_content_type($file) ?: 'application/octet-stream';
    }

    /**
     * Check if file exists
     */
    public function exists(string $path): bool
    {
        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->password,
            ])->head("{$this->baseUrl}/{$path}");

            return $response->successful();

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Create a directory
     */
    public function createDirectory(string $path): bool
    {
        try {
            $path = rtrim($path, '/') . '/';
            
            $response = Http::withHeaders([
                'AccessKey' => $this->password,
            ])->put("{$this->baseUrl}/{$path}");

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Bunny Storage create directory exception', [
                'message' => $e->getMessage(),
                'path' => $path,
            ]);

            return false;
        }
    }


    // DOCUSEAL METHODS

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

    // DOCX — always use coordinate fields
    if ($extension !== 'pdf') {
        return $this->sendToDocuSeal($name, $tempPath, $endpoint, $placeholders);
    }

    // PDF already has {{...}} placeholders — send directly, NO FPDI needed
    if ($this->pdfHasPlaceholders($tempPath)) {
        Log::info('PDF has placeholders — sending as-is', ['name' => $name]);
        return $this->sendToDocuSeal($name, $tempPath, $endpoint, []);
    }

    // User added custom fields via UI — use coordinate-based fields, NO FPDI needed
    if (!empty($placeholders)) {
        Log::info('Using custom UI placeholders', ['count' => count($placeholders)]);
        return $this->sendToDocuSeal($name, $tempPath, $endpoint, $placeholders);
    }

    // No placeholders anywhere — need to append signature page (uses FPDI)
    // Wrap in try/catch to handle compressed PDFs gracefully
    Log::info('No placeholders — appending signature page', ['name' => $name]);

    try {
        $mergedPath = $this->appendSignaturePage($tempPath, $name);

        try {
            return $this->sendToDocuSeal($name, $mergedPath, $endpoint, []);
        } finally {
            if (file_exists($mergedPath)) {
                unlink($mergedPath);
            }
        }
    } catch (\Exception $e) {
        // FPDI can't parse this PDF (compressed/encrypted) — fall back to coordinate fields
        Log::warning('FPDI failed to parse PDF — falling back to coordinate-based default fields', [
            'error' => $e->getMessage(),
        ]);

        return $this->sendToDocuSeal($name, $tempPath, $endpoint, $this->getDefaultFields());
    }
}

private function getDefaultFields(): array
{
    return [
        ['key' => 'Name',      'label' => 'Full Name',   'type' => 'text',      'required' => true,  'options' => ''],
        ['key' => 'Date',      'label' => 'Date',         'type' => 'date',      'required' => true,  'options' => ''],
        ['key' => 'Signature', 'label' => 'Signature',    'type' => 'signature', 'required' => true,  'options' => ''],
    ];
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
        $tpl  = $pdf->importPage($i);
        $size = $pdf->getTemplateSize($tpl);

        $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
        $pdf->useTemplate($tpl);
    }

    // 2. Add clean signature page (A4) — no header, no logo area
    $pdf->AddPage('P', [210, 297]);
    $pdf->SetMargins(20, 20, 20);
    $pdf->SetY(20);

    // Divider line at top
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->Line(20, 20, 190, 20);
    $pdf->Ln(10);

    $colWidth = 170; // full usable width

    // --- Date field ---
    $pdf->SetTextColor(60, 60, 60);
    $pdf->SetFont('Helvetica', 'B', 10);
    $pdf->SetX(20);
    $pdf->Cell($colWidth, 7, 'Date', 0, 1); // 1 = move to next line
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->SetX(20);
    $pdf->Cell($colWidth, 7, '{{Date;type=date}}', 0, 1);
    $pdf->Ln(8);

    // --- Full Name field ---
    $pdf->SetFont('Helvetica', 'B', 10);
    $pdf->SetX(20);
    $pdf->Cell($colWidth, 7, 'Full Name', 0, 1);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->SetX(20);
    $pdf->Cell($colWidth, 7, '{{Name}}', 0, 1);
    $pdf->Ln(8);

    // --- Signature field ---
    $pdf->SetFont('Helvetica', 'B', 10);
    $pdf->SetX(20);
    $pdf->Cell($colWidth, 7, 'Signature', 0, 1);
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->SetX(20);
    $pdf->Cell($colWidth, 20, '{{Signature;type=signature}}', 0, 1);

    // Bottom divider
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->Line(20, $pdf->GetY() + 4, 190, $pdf->GetY() + 4);

    // Save merged PDF
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
    private function sendToDocuSeal(string $name, string $filePath, string $endpoint, array $placeholders ): int {
        //dd($this->apiKey, $this->docusealBaseUrl);
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
        ])->post("{$this->docusealBaseUrl}{$endpoint}", $payload);

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


    public function createSubmission(int $templateId, int $userId, string $email, string $name, string $externalId): array 
    {
        $today = Carbon::today()->toDateString();
        //dd($this->apiKey, $this->docusealBaseUrl);
        $response = Http::withHeaders([
            'X-Auth-Token' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->docusealBaseUrl}/submissions", [
            'template_id' => $templateId,
            'submitters'  => [
                [
                    'role'        => 'First Party',
                    'email'       => $email,
                    'name'        => $name,
                    'external_id' => (string) $externalId,
                    'send_email'  => false,
                    'fields' => [
                        [
                            'name'          => 'Date',
                            'default_value' => $today,
                            'readonly'      => true,
                        ],
                    ],
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
            'signing_url'   => "{$this->docusealBaseUrl}/s/{$submitter['slug']}",
        ];
    }
}