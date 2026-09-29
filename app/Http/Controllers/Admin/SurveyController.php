<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\LabelRepository;
use App\Repositories\Admin\PatientRepository;
use App\Repositories\Admin\QuestionRepository;
use App\Repositories\Admin\SurveyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use SaveSurveyRequest;

class SurveyController extends Controller
{
    public function __construct(SurveyRepository $surveyRepository, PatientRepository $patientRepository, QuestionRepository $questionRepository, LabelRepository $labelRepository)
    {
        $this->surveyRepository = $surveyRepository;
        $this->patientRepository = $patientRepository;
        $this->questionRepository = $questionRepository;
        $this->labelRepository = $labelRepository;
    }
    public function index()
    {
        return view('survey.index');
    }

    public function create()
    {
        return view('survey.create');
    }
      public function editSurvey($id)
        {
            $survey = $this->surveyRepository->getSingleSurvey($id);
            //dd($survey);
            return view('survey.create', compact('survey'));
        }

    public function getEditData($id)
    {
        $survey = $this->surveyRepository->getSingleSurvey($id);
        //dd($survey);
        return response()->json([
            'success' => true,
            'data' => $survey
        ]);
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
   
    public function show($id)
    {
        $survey = $this->surveyRepository->getSingleSurvey($id);
        //dd($survey);
        return view('survey.show', compact('survey'));
    }

    public function getSurveyPatients($id)
    {
        try{
            $survey = $this->surveyRepository->getSingleSurvey($id);
           
            return response()->json([
                'success' => true,
                'data' => $survey,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
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

    public function fetchTreatments()
    {

    
        try {
            $treatments = $this->labelRepository->getTreatmentLabel();

            return response()->json([
                'success' => true,
                'data' => $treatments,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function fetchQuestions()
    {
        try {
            $questions = $this->questionRepository->getSurveyQuestions();

            return response()->json([
                'success' => true,
                'data' => $questions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

      
    public function saveOrUpdate(Request $request): JsonResponse
    {

        //dd($request->all());

        // Validate the request
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:surveys,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'repetition' => 'nullable|string|in:once,daily,weekly,every_week,monthly,yearly,custom',

            // 'send_to' => 'required|string|in:individual_patient,patient_group',
            
            // // Pivot-related fields
            // 'patient_group_id' => 'required_if:send_to,patient_group|nullable|exists:users,id', // Optional if you plan to map groups
            // 'patient_ids' => 'required_if:send_to,individual_patient|array|min:1',
            // 'patient_ids.*' => 'exists:users,id',

            'send_to' => 'required|in:department,individual_patient,treatment_label',
            'department_id' => 'required_if:send_to,department|exists:departments,id',
            'patient_ids' => 'required_if:send_to,individual_patient|array',
            'patient_ids.*' => 'exists:users,id',
            
            'question_ids' => 'required|array|min:1',
            'question_ids.*' => 'exists:questions,id',

            'treatment_ids' => 'required_if:send_to,treatment_label|array',
            'treatment_ids.*' => 'exists:labels,id',

            'custom_reoccurrence' => 'nullable|array',
            'custom_reoccurrence.repeat.interval' => 'required_if:repetition,custom|integer|min:1',
            'custom_reoccurrence.repeat.unit' => 'required_if:repetition,custom|string|in:day,week,month,year',
            'custom_reoccurrence.repeat_on' => 'nullable|array',
            'custom_reoccurrence.ends.type' => 'required_if:repetition,custom|string|in:never,on,after',
            'custom_reoccurrence.ends.on' => 'required_if:custom_reoccurrence.ends.type,on|nullable|date',
            'custom_reoccurrence.ends.after' => 'required_if:custom_reoccurrence.ends.type,after|nullable|integer|min:1',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        try {
            DB::beginTransaction();

            // Check if we're updating or creating
            if ($request->filled('id')) {
                // Update existing survey - use Prettus signature: update(array $attributes, $id)
                $survey = $this->surveyRepository->update($validated, $request->id);
                $message = 'Survey updated successfully';
            } else {
                // Create new survey - use custom method
                $survey = $this->surveyRepository->saveSurvey($validated);
                $message = 'Survey created successfully';
            }

            $patientIds = $this->surveyRepository->resolvePatientIds($validated);
            $survey->users()->sync($patientIds);


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $survey,
                //'redirect' => route('surveys.index')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Survey save/update error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the survey',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

       /**
     * Show edit form
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        try {
            $survey = $this->surveyRepository->findWithRelations($id);
            
            // Get all surveys in the batch to populate selected patients
            $batchSurveys = $this->surveyRepository->getBatchSurveys($survey->batch_uuid);
            
            return view('surveys.edit', compact('survey', 'batchSurveys'));
        } catch (\Exception $e) {
            Log::error('Survey edit error: ' . $e->getMessage());
            return redirect()->route('surveys.index')->with('error', 'Survey not found');
        }
    }

    /**
     * Delete survey
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $survey = $this->surveyRepository->find($id);
            $batchUuid = $survey->batch_uuid;

            // Delete all surveys in the batch
            $this->surveyRepository->deleteWhere(['batch_uuid' => $batchUuid]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Survey deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Survey delete error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete survey'
            ], 500);
        }
    }

 



}
