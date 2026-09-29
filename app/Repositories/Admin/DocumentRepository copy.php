<?php
namespace App\Repositories\Admin;

use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentAssignment;
use App\Models\User;
use App\Services\BunnyStorageService;
use App\Services\DocuSealService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository;

class DocumentRepository extends BaseRepository
{
    public function model()
    {
        return Document::class;
    }

    /**
     * Create a new document.
     */
    public function saveDocument(array $data): Document
    {
        return Document::create([
            'title'                => $data['title'],
            'description'          => $data['description'] ?? null,
            'file_type'            => $data['file_type'] ?? null,
            'document_type'        => $data['document_type'] ?? null,
            'file_name'            => $data['file_name'] ?? null,
            'file_url'             => $data['bunny_url'] ?? null,
            'docuseal_template_id' => $data['docuseal_template_id'] ?? null,
            'created_by'           => Auth::id(),
        ]);
    }

     public function getAllDocuments()
    {
        $docs = Document::with('assignments')
            ->withCount('assignments')
            ->withCount([
                'assignments as signed_counts' => function ($query) {
                    $query->where('status', 'completed');
                }
            ])
            ->get();

        return $docs;
    }

    public function getSingleDocument($id)
    {
        return Document::with(['users','assignments'])->withCount('assignments')->findOrFail($id);
    }


    /**
     * Update an existing document.
     */
    public function updateDocument(array $data, int $id): Document
    {
        $document = Document::findOrFail($id);

        $document->update([
            'title'                => $data['title'],
            'description'          => $data['description'] ?? $document->description,
            'file_type'            => $data['file_type'] ?? $document->file_type,
            'file_name'            => $data['file_name'] ?? $document->file_name,
            // Only overwrite file_url if a new file was uploaded
            'file_url'             => $data['bunny_url'] ?? $document->file_url,
            'docuseal_template_id' => $data['docuseal_template_id'] ?? $document->docuseal_template_id,
        ]);

        return $document->fresh();
    }

    /**
     * Resolve patient IDs from either direct selection or department.
     * If department_id is provided, fetch all user IDs in that department.
     */
    public function resolvePatientIds(array $data): array
    {
        if (!empty($data['department_id'])) {
            $department = Department::with('users')->findOrFail($data['department_id']);
            return $department->users->pluck('id')->toArray();
        }

        return $data['patient_ids'] ?? [];
    }

    /**
     * Sync document assignments.
     * Inserts new assignments, leaves existing ones untouched (preserves signing data).
     * Removes assignments for users no longer in the list.
     */
   public function syncAssignments(Document $document, array $userIds): void
{
    $needsSigning = !empty($document->docuseal_template_id);
    $docuSeal     = $needsSigning ? app(DocuSealService::class) : null;

    $existingUserIds = DocumentAssignment::withTrashed()
        ->where('document_id', $document->id)
        ->pluck('user_id')
        ->toArray();

    // Restore soft-deleted assignments for returning users
    DocumentAssignment::onlyTrashed()
        ->where('document_id', $document->id)
        ->whereIn('user_id', $userIds)
        ->restore();

    // Add new assignments
    $newUserIds = array_diff($userIds, $existingUserIds);

    foreach ($newUserIds as $userId) {
        $submissionId = null;
        $signingUrl   = null;
        $docusealSlug  = null;
        $token = Str::uuid()->toString();

        if ($needsSigning) {
            try {
                $user = User::findOrFail($userId);

                $submission = $docuSeal->createSubmission(
                    templateId: (int) $document->docuseal_template_id,
                    userId:     $userId,
                    email:      $user->email,
                    name:       trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->email,
                    externalId: $token,
                );

                $submissionId = $submission['submission_id'];
                $docusealSlug = $submission['slug'];

            } catch (\Throwable $e) {
                Log::error("DocuSeal submission failed for user {$userId}", [
                    'document_id' => $document->id,
                    'error'       => $e->getMessage(),
                ]);
            }
        }

        // Build permanent signed URL using token (no expiry)
        $signingUrl = URL::signedRoute('admin.document.sign', [
            'token' => $token,
        ]);
        $downloadUrl = URL::signedRoute('admin.documents.download', ['token' => $token]);

        DocumentAssignment::create([
            'document_id' => $document->id,
            'user_id' => $userId,
            'token'=> $token,
            'docuseal_submission_id' => $submissionId,
            'docuseal_slug' => $docusealSlug,
            'signing_url' => $signingUrl,
            'download_url' => $downloadUrl,
            'status'  => 'pending',
        ]);
    }

    // Soft-delete removed assignments
    $removedUserIds = array_diff($existingUserIds, $userIds);
    if (!empty($removedUserIds)) {
        DocumentAssignment::where('document_id', $document->id)
            ->whereIn('user_id', $removedUserIds)
            ->delete();
    }
}

    //API METHODS STARTS HERE
   
    public function getSignedDocuments()
    {
        $patientId = auth()->user('web')->id;
        return DocumentAssignment::where('user_id', $patientId)
            ->whereHas('document', fn ($q) => $q->sign())
            ->with('document')
            ->latest()
            ->get();
    }

    public function getSimpleDocuments()
    {
        $patientId = auth()->user('web')->id;
        return DocumentAssignment::where('user_id', $patientId)
            ->whereHas('document', fn ($q) => $q->simple())
            ->with('document')
            ->latest()
            ->get();
    }

    public function updateConsent(string $token, string $path): bool
    {
        return DocumentAssignment::where('token', $token)
            ->update([
                'status' => 'completed',
                'signed_at'      => now(),
                'signed_doc_url' => $path,
            ]) > 0;
    }

    public function getDownloadUrl(string $token): ?string
    {
        $assignment = DocumentAssignment::with('document')
            ->where('token', $token)
            ->firstOrFail();

        $document = $assignment->document;

        if (!$document->file_url) {
            return null;
        }

        $bunny = app(BunnyStorageService::class);
        $path  = ltrim(parse_url($document->file_url, PHP_URL_PATH), '/');

        try {
            return $bunny->getSignedUrl($path, expiresInSeconds: 300); // 5 min — just return the URL
        } catch (\Throwable $e) {
            Log::error('Failed to generate Bunny signed URL', [
                'token' => $token,
                'path'  => $path,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

}