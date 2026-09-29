<?php

namespace App\Http\Controllers\Api\Document;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\DocumentRepository;
use App\Services\BunnyStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    public function __construct(DocumentRepository $docsrepo, BunnyStorageService $bunnyService)
    {
        $this->docsrepo = $docsrepo;
        $this->bunnyService = $bunnyService;
    }

   public function getSignedDocuments()
    {
        $documents = $this->docsrepo->getSignedDocuments();

        $documents->map(function ($assignment) {
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
            'data' => $documents->isEmpty() ? null : $documents,
            'message' => $documents->isEmpty() ? 'No documents found' : null
        ]);
    }

    public function getSimpleDocuments()
    {
         $documents = $this->docsrepo->getSimpleDocuments();
         $documents->map(function ($assignment) {
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
            'data' => $documents,
        ]);
    }

     public function download(Request $request, string $token): JsonResponse
    {
        try {
            $downloadUrl = $this->docsrepo->getDownloadUrl($token);

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
