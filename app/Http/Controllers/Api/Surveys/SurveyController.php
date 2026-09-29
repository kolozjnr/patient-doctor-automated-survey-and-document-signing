<?php

namespace App\Http\Controllers\Api\Surveys;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Admin\SurveyRepository;

class SurveyController extends Controller
{
    public function __construct(SurveyRepository $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;
    }

     public function getPatientSurveyApi()
    {
        return $this->surveyRepository->getPatientSurveyApi();
    }

    public function getSingleSurveyApi($id)
    {
        try{
            $single =  $this->surveyRepository->getSingleSurveyApi($id);
            //return $single;
            return response()->json([
                'success' => true,
                'data' => $single
            ]);
        } catch (\Exception $e) {
            Log::error('Get surveys data error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load surveys data',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    public function getData(): JsonResponse
    {
        try {
            // Get surveys grouped by batch_uuid
            $surveys = $this->surveyRepository->getBatchSurveys();

            return response()->json([
                'success' => true,
                'data' => $surveys
            ]);

        } catch (\Exception $e) {
            Log::error('Get surveys data error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load surveys data',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    
    public function saveAnswers(Request $request)
    {
        //dd($request->all());
        // Validate the incoming bulk payload
        $validator = Validator::make($request->all(), [
            'survey_id' => 'required|exists:surveys,id',
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|exists:survey_questions,question_id',
            'answers.*.type' => 'required|string',
            // 'answer' is nullable because some types might use 'selections' or 'extra'
            'answers.*.answer' => 'nullable', 
            'answers.*.option_id' => 'nullable|integer|exists:question_options,id',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get the user ID from the session or token
            $userId = auth()->user('web')->id;

            // Pass the validated data and user ID to the repository
            $this->surveyRepository->storeBulkAnswersApi($request->all(), $userId);

            return response()->json([
                'success' => true,
                'message' => 'Survey answers saved successfully.'
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Survey Saving Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving answers.'
            ], 500);
        }
    }

   
}
