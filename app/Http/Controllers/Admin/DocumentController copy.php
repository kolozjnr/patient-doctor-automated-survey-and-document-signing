<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentAssignment;
use App\Repositories\Admin\DocumentRepository;
use App\Repositories\Admin\PatientRepository;
use App\Services\BunnyStorageService;
use App\Services\DocuSealService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{


    public function __construct(DocumentRepository $documentRepository, PatientRepository $patientRepository,
        BunnyStorageService $bunnyService, DocuSealService $docuSealService)
    {
        $this->documentRepository = $documentRepository;
        $this->patientRepository = $patientRepository;
        $this->bunnyService = $bunnyService;
        $this->docuSealService = $docuSealService;
    }
    public function index()
    {
        $documents = [];
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

     public function show($id)
    {
        $document = $this->documentRepository->getSingleDocument($id);
        //dd($document);
        return view('documents.show', compact('document'));
    }

   public function getPatientsAssignment($id)
    {
        try {
            $document = $this->documentRepository->getSingleDocument($id);

            $document->assignments->map(function ($assignment) {
                if ($assignment->status === 'completed' && !empty($assignment->signed_doc_url)) {
                    $cdnUrl = rtrim(config('services.bunny.cdn_url'), '/');
                    $path = ltrim(str_replace($cdnUrl, '', $assignment->signed_doc_url), '/');
                    $assignment->consent_signed_url = $this->bunnyService->getSignedUrl($path, 86400);
                } else {
                    $assignment->consent_signed_url = $assignment->download_url;
                }

                return $assignment;
            });

            return response()->json([
                'success' => true,
                'data' => $document,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function getAllDocuments()
    {
        $documents = $this->documentRepository->getAllDocuments();
        return response()->json([
            'success' => true,
            'documents' => $documents,
        ]);
    }

    public function showConsent(Request $request, string $token)
{
    $assignment = DocumentAssignment::with(['document', 'user'])
        ->where('token', $token)
        ->firstOrFail();

    $patient = $assignment->user;

    abort_if(!$patient, 404);

    if ($assignment->status === 'completed') {
        return redirect('myapp://consent-complete?status=already_signed');
    }

    // DocuSeal embed uses just the slug — NOT your app's signed URL
    $submissionUrl = $assignment->docuseal_slug
        ? rtrim(config('services.docuseal.embed_url', 'https://docuseal.com'), '/') . '/s/' . $assignment->docuseal_slug
        : null;

    if (!$submissionUrl) {
        abort(500, 'Unable to generate consent form');
    }

    $fileUrl = null;
    if ($assignment->document->file_url) {
        $bunny   = app(\App\Services\BunnyStorageService::class);
        $path    = ltrim(parse_url($assignment->document->file_url, PHP_URL_PATH), '/');
        $fileUrl = $bunny->getSignedUrl($path, expiresInSeconds: 86400);
    }

    return view('patients.consent-sign', [
        'patient'       => $patient,
        'submissionUrl' => $submissionUrl,
        'fileUrl'       => $fileUrl,
    ]);
}

   public function saveOrUpdate(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'id'            => 'nullable|exists:documents,id',
        'title'         => 'required|string|max:255',
        'description'   => 'nullable|string',
        'send_to'       => 'required|in:department,individual_patient',
        'document_type' => 'required|string',
        'department_id' => 'required_if:send_to,department|exists:departments,id',
        'patient_ids'   => 'required_if:send_to,individual_patient|array',
        'patient_ids.*' => 'exists:users,id',
        'file'          => 'nullable|file|mimes:pdf,doc,docx|max:20480',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors'  => $validator->errors()
        ], 422);
    }

    $validated = $validator->validated();

   try {
    DB::beginTransaction();

    $bunnyUrl           = null;
    $docusealTemplateId = null;
    $fileName           = null;
    $fileType           = null;

    // 1. Upload to Bunny + create DocuSeal template
    if ($request->hasFile('file')) {
        $file     = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileType = $file->getClientOriginalExtension();
        $placeholders = [];
        if ($request->filled('placeholders')) {
            $placeholders = json_decode($request->input('placeholders'), true) ?? [];
        }

        $bunnyUrl = $this->bunnyService->upload($file, $validated['title']);
        if($request->document_type === 'sign') {
            $docusealTemplateId = $this->bunnyService->createTemplate($validated['title'], $file, $placeholders);
        }
    }

    //dd($request->document_type);

    // 2. Build document data
    $documentData = array_merge($validated, [
        'bunny_url' => $bunnyUrl,
        'docuseal_template_id' => $docusealTemplateId,
        'document_type' => $request->document_type, 
        'file_name' => $fileName,
        'file_type' => $fileType,
    ]);

    // 3. Save or update
    if ($request->filled('id')) {
        $document = $this->documentRepository->updateDocument($documentData, (int) $request->id);
        $message  = 'Document updated successfully';
    } else {
        $document = $this->documentRepository->saveDocument($documentData);
        //dd($document);
        $message  = 'Document uploaded successfully';
    }

    // 4. Resolve patient IDs and sync assignments
    $patientIds = $this->documentRepository->resolvePatientIds($validated);
    $this->documentRepository->syncAssignments($document, $patientIds);

    DB::commit();

    return response()->json([
        'success'              => true,
        'message'              => $message,
        'bunny_url'            => $bunnyUrl,
        'docuseal_template_id' => $docusealTemplateId,
        'data'                 => $document,
    ]);

} catch (\Exception $e) {
    DB::rollBack();
    Log::error('Document save error: ' . $e->getMessage(), [
        'trace'   => $e->getTraceAsString(),
        'request' => $request->except('file'),
    ]);

    return response()->json([
        'success' => false,
        'message' => 'An error occurred while saving the document',
        'error'   => config('app.debug') ? $e->getMessage() : null
    ], 500);
}
}
    
    public function fetchPatients()
    {
        try {
            $patients = $this->patientRepository->getPatients();

            return response()->json([
                'success' => true,
                'data' => $patients,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function download(Request $request, string $token): JsonResponse
    {
        try {
            $downloadUrl = $this->documentRepository->getDownloadUrl($token);

            if (!$downloadUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'No file available for download',
                ], 404);
            }

            return response()->json([
                'success'      => true,
                'download_url' => $downloadUrl,
            ]);

        } catch (\Exception $e) {
            Log::error('Download failed', [
                'token' => $token,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to generate download link',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
