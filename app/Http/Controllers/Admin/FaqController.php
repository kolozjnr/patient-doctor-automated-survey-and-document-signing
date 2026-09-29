<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Repositories\Admin\FaqRepository;
use App\Services\BunnyStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $faqCategory = FaqCategory::all();
        //dd($faqCategory);
        return view('faq.index', compact('faqCategory'));
    }

    public function fetch()
    {
        $faqs = $this->FaqRepository->getAllFaq();

        $faqs->map(function ($faq) {

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
            'data' => $faqs
        ]);
    }

    public function edit($id)
    {
        $faq = $this->FaqRepository->show($id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'Question not found!',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $faq,
        ]);
    }
      

    public function storeFaqCat(Request $request)
    {
          \Log::info('Labels data received:', $request->all());
          
        $validator = Validator::make($request->all(), [
            'cats' => 'required|array',
            'cats.*.id' => 'nullable|exists:faq_categories,id',
            'cats.*.header' => 'required|string|max:255',
            'cats.*.category' => 'required|string|max:255',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
         $this->FaqRepository->storeOrUpdate($request->cats);

        return response()->json([
            'message' => 'Labels processed successfully'
        ]);
    }

    
    public function saveOrUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:faqs,id',
            'question' => 'required|string|max:255',
            'answer' => 'nullable|string',
            'category_id'   => 'required|exists:faq_categories,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:20480',
        ]);

        //dd($request->all());
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        //dd($validated['question']);

        try {
            $bunnyUrl = null;

            // 1. Upload to Bunny + create DocuSeal template
            if ($request->hasFile('file')) {
                $file  = $request->file('file');
               // dd($file);
                $bunnyUrl = $this->bunnyService->upload($file, $validated['question']);
               
            }
            DB::beginTransaction();

            //dd($request->document_type);

            // 2. Build document data
            $faqData = array_merge($validated, [
                'bunny_url' => $bunnyUrl,
            ]);

            // 3. Save or update
            if ($request->filled('id')) {
                $faq = $this->FaqRepository->updateFaq($faqData, (int) $request->id);
                $message  = 'Faq updated successfully';
            } else {
                $faq = $this->FaqRepository->saveFaq($faqData);
                //dd($document);
                $message  = 'Faq uploaded successfully';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'bunny_url' => $bunnyUrl,
                'data' => $faq,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Faq save error: ' . $e->getMessage(), [
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


    public function updateOrder(Request $request)
    {
        try {
            $orders = $request->input('orders');
            
            foreach ($orders as $orderData) {
                DB::table('faqs')
                    ->where('id', $orderData['id'])
                    //->where('module_name', $module)
                    ->update(['question_order' => $orderData['order']]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->FaqRepository->deleteQuestion($id);

            return response()->json([
                'success' => true,
                'message' => 'FAQ deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete FAQ: ' . $e->getMessage(),
            ], 500);
        }
    }

}
