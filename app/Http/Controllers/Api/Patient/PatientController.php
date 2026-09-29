<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessConsents;
use App\Mail\AdminConsentNotification;
use App\Models\DocumentAssignment;
use App\Repositories\Admin\PatientRepository;
use App\Services\BunnyStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function __construct(PatientRepository $patientRepository, BunnyStorageService $bunnyStorageService)
    {
        $this->patientRepository = $patientRepository;
        $this->bunnyStorageService = $bunnyStorageService;
    }
     // $documentUrl = $data['documents'][0]['url'] 
        //             ?? $data['submission']['combined_document_url'] 
        //             ?? null;
        // $signature = $request->header('X-Docuseal-Signature');
        // $webhookSecret = env('DOCUSEAL_WEBHOOK_SECRET');
        // if ($webhookSecret) {
        //     if (!$signature || !hash_equals($webhookSecret, $signature)) {
        //         Log::warning('Invalid DocuSeal webhook secret', [
        //             'received' => $signature,
        //         ]);

        //         return response()->json(['error' => 'Invalid signature'], 401);
        //     }
        // }

    public function storeFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->update(['fcm_token' => $request->fcm_token]);
        return response()->json(['message' => 'Token opgeslagen.']);
    }

    public function uploadConsentXX(Request $request)
    {
        //dd($request->all());
        Log::info('DocuSeal webhook', $request->all());
        $payload = $request->all();
        $eventType = $payload['event_type'] ?? 'unknown';
        $data = $payload['data'] ?? [];
        $patientId = $data['external_id'] ?? $data['metadata']['patient_id'] ?? null;
        $documentUrl = $data['documents'][0]['url'] ?? null;
        $submissionId = $data['id'] ?? null;

        Log::info('Processing DocuSeal webhook', [
            'event_type' => $eventType,
            'patient_id' => $patientId,
            'document_url' => $documentUrl,
            'submission_id' => $submissionId,
        ]);

        if ($eventType !== 'form.completed') {
            return response()->json(['message' => 'Event acknowledged'], 200);
        }

        if ($patientId && $documentUrl) {
            Log::info('we are processing job');
            //ProcessConsents::dispatch($patientId, $documentUrl, $submissionId);
            
            return response()->json(['message' => 'Processing started'], 200);
        }

        return response()->json(['error' => 'Invalid data'], 422);

    }



  public function uploadConsent(Request $request)
{
    Log::info('DocuSeal webhook', $request->all());

    $payload   = $request->all();
    $eventType = $payload['event_type'] ?? 'unknown';
    $data      = $payload['data'] ?? [];

    if ($eventType !== 'form.completed') {
        Log::info('Non-completion event received', ['event_type' => $eventType]);
        return response()->json(['message' => 'Event acknowledged'], 200);
    }

    $submissionId = $data['submission_id'] ?? null;
    $token    = $data['external_id'] ?? null;
    $documentUrl  = $data['documents'][0]['url'] ?? null;

    // Debug exactly what we have
    Log::info('DocuSeal extracted values', [
        'submission_id' => $submissionId,
        'token'    => $token,
        'document_url'  => $documentUrl,
        'documents_raw' => $data['documents'] ?? 'KEY MISSING',
        'data_keys'     => array_keys($data),
    ]);

    if (!$documentUrl) {
        Log::warning('Missing document URL — returning 422', [
            'data' => $data,
        ]);
        return response()->json(['error' => 'Invalid data — missing document URL'], 422);
    }

    Log::info('Dispatching ProcessConsents job', [
        'token'    => $token,
        'submission_id' => $submissionId,
    ]);

   // ProcessConsents::dispatch($token, $documentUrl, $submissionId);
    
    Log::info('ProcessConsents job STARTED', [
        'patient_id'    => $token,
        'submission_id' => $submissionId,
        'document_url'  => $documentUrl,
    ]);

    $tempPath = null;

    try {
        $assignment = null;

        if ($submissionId) {
            $assignment = DocumentAssignment::with('user')
                ->where('docuseal_submission_id', $submissionId)
                ->first();

            Log::info('Assignment lookup by submission_id', [
                'submission_id' => $submissionId,
                'found'         => (bool) $assignment,
            ]);
        }

        if (!$assignment && $token) {
            $assignment = DocumentAssignment::with('user')
                ->where('token', $token)  // token UUID, not patient id
                ->first();

            Log::info('Assignment lookup by token', [
                'token' => $token,
                'found' => (bool) $assignment,
            ]);
        }

        if (!$assignment) {
            throw new \Exception(
                "Cannot find assignment. submission_id={$submissionId}, token={$token}"
            );
        }

        // 2. Get actual user_id from assignment and load patient via patientRepo
        $userId  = $assignment->user_id;
        $patient = $this->patientRepository->show($userId);

        Log::info('Patient resolved from assignment', [
            'assignment_id' => $assignment->id,
            'user_id'       => $userId,
            'patient_found' => (bool) $patient,
        ]);

        if (!$patient) {
            throw new \Exception("Patient not found for user_id={$userId}");
        }

        // 3. Download signed PDF from DocuSeal
        $response = Http::timeout(60)->get($documentUrl);

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
        if ($assignment->signed_doc_url) {
            try {
                $oldPath = ltrim(parse_url($assignment->signed_doc_url, PHP_URL_PATH), '/');
                $this->bunnyStorageService->delete($oldPath);
                Log::info("Old signed doc deleted", ['path' => $oldPath]);
            } catch (\Exception $e) {
                Log::warning("Failed to delete old signed doc: " . $e->getMessage());
            }
        }

        // 5. Upload to Bunny
        $uploadedUrl = $this->bunnyStorageService->uploadFromPath($tempPath, $bunnyPath);
        Log::info("Signed PDF uploaded to Bunny", ['url' => $uploadedUrl]);

        // 6. Update assignment
        $assignment->update([
            'status'              => 'completed',
            'signed_at'           => now(),
            'signed_doc_url' => $uploadedUrl,
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
            'submission_id' => $submissionId,
            'token'         => $token,
            'error'         => $e->getMessage(),
            'trace'         => $e->getTraceAsString(),
        ]);

        if ($tempPath && file_exists($tempPath)) {
            unlink($tempPath);
        }

        throw $e;
    }

    return response()->json(['message' => 'Processing started'], 200);
}

    /**
     * Delete patient document
     */
    public function deleteDocument(Request $request, $token): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $path = $request->input('path');

        // Verify the path belongs to this patient
        if (!str_starts_with($path, "patients/{$token}/")) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to document',
            ], 403);
        }

        if ($this->bunnyStorageService->delete($path)) {
            // Delete from database
            // $this->patientRepository->deleteDocument($path);

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete document',
        ], 500);
    }

    /**
     * Download patient document
     */
    public function downloadDocument(Request $request, $token): mixed
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $path = $request->input('path');

        // Verify the path belongs to this patient
        if (!str_starts_with($path, "patients/{$token}/")) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to document',
            ], 403);
        }

        $content = $this->bunnyStorageService->download($path);

        if ($content) {
            $filename = basename($path);
            
            return response($content)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
        }

        return response()->json([
            'success' => false,
            'message' => 'Document not found',
        ], 404);
    }

    public function updateprofile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'   => 'nullable|string',
            'last_name'    => 'nullable|string',
            'email'        => 'nullable|email',
            'phone'        => 'nullable|string',
            'country_code' => 'nullable|string',
            'date_of_birth'=> 'nullable|date',
            'height'       => 'nullable|numeric',
            'weight'       => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }
        //dd(Auth::user());

        $user = $this->patientRepository->updateProfile(
            Auth::user(),
            $validator->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data'    => $user
        ]);
    }

}
