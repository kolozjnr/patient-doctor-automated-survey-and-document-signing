<?php

namespace App\Jobs;

use App\Mail\AdminConsentNotification;
use App\Models\DocumentAssignment;
use App\Repositories\Admin\PatientRepository;
use App\Services\BunnyStorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessConsents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries   = 3;
    public $timeout = 120;

    public function __construct(
        protected $token,
        protected $documentUrl,
        protected $submissionId
    ) {}

    public function handle(PatientRepository $patientRepo, BunnyStorageService $bunnyService): void
    {
        Log::info('ProcessConsents job STARTED', [
            'token'         => $this->token,
            'submission_id' => $this->submissionId,
            'document_url'  => $this->documentUrl,
        ]);

        $tempPath = null;

        try {
            // 1. Find assignment via submission_id first, then token fallback
            $assignment = null;

            if ($this->submissionId) {
                $assignment = DocumentAssignment::with('user')
                    ->where('docuseal_submission_id', $this->submissionId)
                    ->first();

                Log::info('Assignment lookup by submission_id', [
                    'submission_id' => $this->submissionId,
                    'found'         => (bool) $assignment,
                ]);
            }

            if (!$assignment && $this->token) {
                $assignment = DocumentAssignment::with('user')
                    ->where('token', $this->token)
                    ->first();

                Log::info('Assignment lookup by token', [
                    'token' => $this->token,
                    'found' => (bool) $assignment,
                ]);
            }

            if (!$assignment) {
                throw new \Exception(
                    "Cannot find assignment. submission_id={$this->submissionId}, token={$this->token}"
                );
            }

            // 2. Get actual user_id from assignment and load patient via patientRepo
            $userId  = $assignment->user_id;
            $patient = $patientRepo->show($userId);

            Log::info('Patient resolved from assignment', [
                'assignment_id' => $assignment->id,
                'user_id'       => $userId,
                'patient_found' => (bool) $patient,
            ]);

            if (!$patient) {
                throw new \Exception("Patient not found for user_id={$userId}");
            }

            // 3. Download signed PDF from DocuSeal
            $response = Http::timeout(60)->get($this->documentUrl);

            if (!$response->successful()) {
                throw new \Exception("Failed to download PDF: HTTP {$response->status()}");
            }

            $fileName  = 'signed_doc_' . $userId . '_' . time() . '.pdf';
            $bunnyPath = "patients/{$userId}/signed/{$fileName}";
            $tempPath  = storage_path("app/tmp/{$fileName}");

            if (!is_dir(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }

            file_put_contents($tempPath, $response->body());
            Log::info("PDF downloaded to temp", ['path' => $tempPath]);

            // 4. Delete old signed doc from Bunny if exists
            if ($assignment->signed_document_url) {
                try {
                    $oldPath = ltrim(parse_url($assignment->signed_document_url, PHP_URL_PATH), '/');
                    $bunnyService->delete($oldPath);
                    Log::info("Old signed doc deleted", ['path' => $oldPath]);
                } catch (\Exception $e) {
                    Log::warning("Failed to delete old signed doc: " . $e->getMessage());
                }
            }

            // 5. Upload to Bunny
            $uploadedUrl = $bunnyService->uploadFromPath($tempPath, $bunnyPath);
            Log::info("Signed PDF uploaded to Bunny", ['url' => $uploadedUrl]);

            // 6. Update assignment
            $assignment->update([
                'status'              => 'completed',
                'signed_at'           => now(),
                'signed_document_url' => $uploadedUrl,
            ]);

            Log::info("DocumentAssignment #{$assignment->id} marked completed");

            // 7. Send admin email
            try {
                Mail::to(config('mail.admin_email'))
                    ->send(new AdminConsentNotification($patient, $tempPath, $fileName));

                Log::info("Admin email sent", ['user_id' => $userId]);
            } catch (\Exception $e) {
                Log::error("Admin email failed (non-critical)", ['error' => $e->getMessage()]);
            }

            // 8. Cleanup
            if (file_exists($tempPath)) {
                unlink($tempPath);
                Log::info("Temp file cleaned up");
            }

            Log::info("Consent processing completed", ['user_id' => $userId]);

        } catch (\Exception $e) {
            Log::error('Consent processing failed', [
                'token'         => $this->token,
                'submission_id' => $this->submissionId,
                'error'         => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);

            if ($tempPath && file_exists($tempPath)) {
                unlink($tempPath);
            }

            throw $e;
        }
    }


// public function handleXXX(PatientRepository $patientRepo, BunnyStorageService $bunnyService): void
// {
//     Log::info('ProcessConsents job STARTED', [
//         'patient_id'    => $this->token,
//         'submission_id' => $this->submissionId,
//         'document_url'  => $this->documentUrl,
//     ]);

//     $tempPath = null;

//     try {
//         // 1. Find assignment via submission_id first, then token fallback
//         $assignment = null;

//         if ($this->submissionId) {
//             $assignment = DocumentAssignment::with('user')
//                 ->where('docuseal_submission_id', $this->submissionId)
//                 ->first();

//             Log::info('Assignment lookup by submission_id', [
//                 'submission_id' => $this->submissionId,
//                 'found'         => (bool) $assignment,
//             ]);
//         }

//         if (!$assignment && $this->token) {
//             $assignment = DocumentAssignment::with('user')
//                 ->where('token', $this->token)  // token UUID, not patient id
//                 ->first();

//             Log::info('Assignment lookup by token', [
//                 'token' => $this->token,
//                 'found' => (bool) $assignment,
//             ]);
//         }

//         if (!$assignment) {
//             throw new \Exception(
//                 "Cannot find assignment. submission_id={$this->submissionId}, token={$this->token}"
//             );
//         }

//         // 2. Get actual user_id from assignment and load patient via patientRepo
//         $userId  = $assignment->user_id;
//         $patient = $patientRepo->show($userId);

//         Log::info('Patient resolved from assignment', [
//             'assignment_id' => $assignment->id,
//             'user_id'       => $userId,
//             'patient_found' => (bool) $patient,
//         ]);

//         if (!$patient) {
//             throw new \Exception("Patient not found for user_id={$userId}");
//         }

//         // 3. Download signed PDF from DocuSeal
//         $response = Http::timeout(60)->get($this->documentUrl);

//         if (!$response->successful()) {
//             throw new \Exception("Failed to download PDF: HTTP {$response->status()}");
//         }

//         $fileName  = 'signed_doc_' . $userId . '_' . time() . '.pdf';
//         $bunnyPath = "patients/{$userId}/signed/{$fileName}";
//         $tempPath  = storage_path("app/tmp/{$fileName}");

//         if (!is_dir(dirname($tempPath))) {
//             mkdir(dirname($tempPath), 0755, true);
//         }

//         file_put_contents($tempPath, $response->body());
//         Log::info("PDF downloaded to temp", ['path' => $tempPath]);

//         // 4. Delete old signed doc from Bunny if exists
//         if ($assignment->signed_document_url) {
//             try {
//                 $oldPath = ltrim(parse_url($assignment->signed_document_url, PHP_URL_PATH), '/');
//                 $bunnyService->delete($oldPath);
//                 Log::info("Old signed doc deleted", ['path' => $oldPath]);
//             } catch (\Exception $e) {
//                 Log::warning("Failed to delete old signed doc: " . $e->getMessage());
//             }
//         }

//         // 5. Upload to Bunny
//         $uploadedUrl = $bunnyService->uploadFromPath($tempPath, $bunnyPath);
//         Log::info("Signed PDF uploaded to Bunny", ['url' => $uploadedUrl]);

//         // 6. Update assignment
//         $assignment->update([
//             'status'              => 'completed',
//             'signed_at'           => now(),
//             'signed_document_url' => $uploadedUrl,
//         ]);

//         Log::info("DocumentAssignment #{$assignment->id} marked completed");

//         // 7. Send admin email
//         try {
//             Mail::to(config('mail.admin_email'))
//                 ->send(new AdminConsentNotification($patient, $tempPath, $fileName));

//             Log::info("Admin email sent", ['user_id' => $userId]);
//         } catch (\Exception $e) {
//             Log::error("Admin email failed (non-critical)", ['error' => $e->getMessage()]);
//         }

//         // 8. Cleanup
//         if (file_exists($tempPath)) {
//             unlink($tempPath);
//             Log::info("Temp file cleaned up");
//         }

//         Log::info("Consent processing completed", ['user_id' => $userId]);

//     } catch (\Exception $e) {
//         Log::error('Consent processing failed', [
//             'patient_id'    => $this->patientId,
//             'submission_id' => $this->submissionId,
//             'error'         => $e->getMessage(),
//             'trace'         => $e->getTraceAsString(),
//         ]);

//         if ($tempPath && file_exists($tempPath)) {
//             unlink($tempPath);
//         }

//         throw $e;
//     }
// }
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessConsents job FAILED permanently', [
            'token'         => $this->token,   // ← correct
            'submission_id' => $this->submissionId,
            'document_url'  => $this->documentUrl,
            'error'         => $exception->getMessage(),
        ]);
    }
}