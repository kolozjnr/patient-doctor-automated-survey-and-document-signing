<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Repositories\Admin\FaqRepository;
use App\Services\BunnyStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
      public function __construct(private FaqRepository $FaqRepository, BunnyStorageService $bunnyService)
    {
        $this->FaqRepository = $FaqRepository;
        $this->bunnyService = $bunnyService;
    }

    public function index()
    {
        $faq = Faq::with('category')
        ->orderBy('question_order', 'asc')
        ->get();
        $faq->map(function ($faq) {

            if ($faq) {
                if (!empty($faq->imageurl)) {
                    $cdnUrl = rtrim(config('services.bunny.cdn_url'), '/');
                    $path = ltrim(str_replace($cdnUrl, '', $faq->imageurl), '/');
                    $faq->signed_url = $this->bunnyService->getSignedUrl($path, 86400);
                } else {
                    $faq->signed_url = $faq->imageurl;
                }
            }

            return $faq;
        });

        return response()->json([
            'success' => true,
            'data'    => $faq,
        ]);
    }

    public function trackVideo(Request $request)
    {
        $userId = auth()->user('web')->id;
        $validator = Validator::make($request->all(), [
            'faq_id' => 'required|integer|exists:faqs,id',
            'hide'   => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $this->FaqRepository->trackVideo($validator->validated(), $userId);
            return response()->json([
                'success' => true,
                'message' => 'Video status saved successfully.'
            ], 200);

        } catch (\Exception $e) {

            \Log::error('hide/show Saving Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving.'
            ], 500);
        }
    }
}
