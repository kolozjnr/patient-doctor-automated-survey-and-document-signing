<?php

namespace App\Repositories\Admin;

use App\Enums\UserStatus;
use App\Jobs\SendSurveyNotificationJob;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository;

class SurveyRepository extends BaseRepository
{
    function model()
    {
        return Survey::class;
    }

        public function getAllSurveys()
        {
            return $this->model->all();
        }

        public function show($id)
        {
            return $this->model->find($id);
        }

        public function getSingleSurvey($id)
        {
            return $this->model::with([
                'users',
                'labels',
                'surveyQuestions.question'
            ])->findOrFail($id);
        }



        public function getBatchSurveys()
        {
            return Survey::withCount([
                    'users as batch_patient_count',
                    'surveyQuestions as questions_count'
                ])
                ->orderBy('created_at', 'desc')
                ->get(['id', 'title', 'description', 'frequency', 'survey_delivery_date', 'cron_status', 'created_at']);
        }

    

         /**
     * Save new survey(s)
     * 
     * @param array $data
     * @return Survey
     */
    public function saveSurvey(array $data)
    {
        $batchUuid = Str::uuid();
        // Create a single survey
        $survey = $this->model->create([
            'batch_uuid' => $batchUuid,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'frequency' => $data['repetition'],
            'send_to' => $data['send_to'],
            'custom_reoccurrence' => $data['repetition'] === 'custom' 
                ? json_encode($data['custom_reoccurrence']) 
                : null,
            'survey_delivery_date' => $this->calculateNextDeliveryDate($data),
            'cron_status' => 'active',
        ]);

        // Attach users (patients) to survey
        $patientIds = $this->resolvePatientIds($data);
        $survey->users()->attach($patientIds, ['status' => 'pending']);

        // Attach questions
        $this->attachQuestions($survey, $data['question_ids']);

         // Attach treatment labels if applicable
        if ($data['send_to'] === 'treatment_label') {
            $survey->labels()->attach($data['treatment_ids'] ?? []);
        }

        SendSurveyNotificationJob::dispatch($survey, $patientIds);
        //->delay($survey->survey_delivery_date);

        return $survey->load('users', 'surveyQuestions.question');
    }

    /**
     * Update survey(s) - Prettus compatible signature
     * 
     * @param array $attributes
     * @param mixed $id
     * @return Survey
     */
    public function update(array $attributes, $id)
    {
        $survey = $this->find($id);

        // Update basic survey info
        $survey->update([
            'title' => $attributes['title'],
            'description' => $attributes['description'] ?? null,
            'frequency' => $attributes['repetition'],
            'send_to' => $attributes['send_to'],
            'custom_reoccurrence' => $attributes['repetition'] === 'custom'
                ? json_encode($attributes['custom_reoccurrence'])
                : null,
            'survey_delivery_date' => $this->calculateNextDeliveryDate($attributes),
        ]);

        //dd($attributes['question_ids']);

        // Sync survey questions
        if (!empty($attributes['question_ids'])) {
            $this->syncQuestions($survey, $attributes['question_ids']);
        } else {
            $survey->surveyQuestions()->detach();
        }

        // Resolve patient IDs based on send_to
        $patientIds = $this->resolvePatientIds($attributes);

        // Sync users (patients)
        if (!empty($patientIds)) {
            $survey->users()->sync($patientIds); // attach/update pivot
        } else {
            $survey->users()->detach(); // remove all if none
        }

         // Sync treatment labels if applicable
        if ($attributes['send_to'] === 'treatment_label') {
            $survey->labels()->sync($attributes['treatment_ids'] ?? []);
        } else {
            $survey->labels()->detach(); // clear if send_to changed away from treatment
        }

        return $survey->fresh()->load(['surveyQuestions.question', 'users']);
    }
    public function resolvePatientIds(array $data): array
    {
        if ($data['send_to'] === 'individual_patient') {
            return $data['patient_ids'] ?? [];
        }

        if ($data['send_to'] === 'department') {
            return DB::table('department_user')
                ->where('department_id', $data['department_id'])
                ->pluck('user_id')
                ->toArray();
        }

        if ($data['send_to'] === 'treatment_label') {
            return DB::table('user_treatment_labels')
                ->whereIn('label_id', $data['treatment_ids'] ?? [])
                ->pluck('user_id')
                ->unique()
                ->toArray();
        }

        return [];
    }

    /**
     * Get patient IDs based on send_to selection
     * 
     * @param array $data
     * @return array
     */
    // protected function getPatientIds(array $data): array
    // {
    //     if ($data['send_to'] === 'individual_patient') {
    //         return $data['patient_ids'] ?? [];
    //     }

    //     return $data['patient_group_id'] ?? [];
    // }

    /**
     * Attach questions to a survey
     * 
     * @param Survey $survey
     * @param array $questionIds
     * @return void
     */
    protected function attachQuestions(Survey $survey, array $questionIds): void
    {
        $sortOrder = 0;
        foreach ($questionIds as $questionId) {
            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question_id' => $questionId,
                'sort_order' => $sortOrder++,
            ]);
        }
    }

    /**
     * Sync questions for a survey (delete old, add new)
     * 
     * @param Survey $survey
     * @param array $questionIds
     * @return void
     */
    protected function syncQuestions(Survey $survey, array $questionIds): void
    {
        // Delete existing questions
        $survey->surveyQuestions()->delete();

        // Add new questions
        $this->attachQuestions($survey, $questionIds);
    }

    /**
     * Calculate next delivery date based on frequency
     * 
     * @param array $data
     * @return string|null
     */
    protected function calculateNextDeliveryDate(array $data): ?string
    {
        $now = now();

        switch ($data['repetition']) {
            case 'once':
                return $now->toDateString();
            
            case 'daily':
                return $now->toDateString();
            
            case 'weekly':
                return $now->addWeek()->toDateString();
            
            case 'monthly':
                return $now->addMonth()->toDateString();
            
            case 'custom':
                $interval = $data['custom_reoccurrence']['repeat']['interval'] ?? 1;
                $unit = $data['custom_reoccurrence']['repeat']['unit'] ?? 'day';
                
                switch ($unit) {
                    case 'day':
                        return $now->addDays($interval)->toDateString();
                    case 'week':
                        return $now->addWeeks($interval)->toDateString();
                    case 'month':
                        return $now->addMonths($interval)->toDateString();
                    case 'year':
                        return $now->addYears($interval)->toDateString();
                }
                break;
        }

        return $now->toDateString();
    }


    /**
     * Get patient surveys from API
     * 
     * @return mixed
     */
    public function getPatientSurveyApi()
    {
        // Get the current user ID from the web guard
        $patientId = auth()->user('web')->id;

       // dd($patientId);

        // Retrieve the surveys for the patient, including relationships
        $survey = $this->model
        ->with(['surveyQuestions.question.options','users','labels'])
        ->whereHas('users', function($query) use ($patientId) {
            $query->where('user_id', $patientId);
        })
        ->where('is_completed', 0)
        ->where('cron_status', 'active') 
        ->whereNull('deleted_at')
        ->get();

        return $survey;
    }
public function getSingleSurveyApi($id)
{
    try {
        $survey = $this->model
            ->with(['users', 'surveyQuestions.question.options', 'labels'])
            ->findOrFail($id);

        Log::info('Survey fetched successfully', [
            'survey_id' => $id,
            'user_id' => auth()->id() ?? null
        ]);

        return $survey;

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

        Log::warning('Survey not found', [
            'survey_id' => $id,
            'error' => $e->getMessage()
        ]);

        throw $e; // or return custom response

    } catch (\Exception $e) {

        Log::error('Error fetching survey', [
            'survey_id' => $id,
            'error_message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        throw $e; // or handle gracefully
    }
}

    // public function getSingleSurveyApi($id)
    // {
    //     return $this->model
    //         ->with(['users','surveyQuestions.question.options','labels'])
    //         ->findOrFail($id);
    // }

    public function storeBulkAnswersApi(array $payload, int $userId)
    {
        //dd($payload);
        $questionIds = collect($payload['answers'])->pluck('question_id')->unique()->toArray();
       // $questionsMap = SurveyQuestion::whereIn('question_id', $questionIds)->pluck('survey_id', 'question_id');
        $questionsMap = DB::table('survey_questions')
            ->where('survey_id', $payload['survey_id'])
            ->pluck('question_id')
            ->toArray();

        $rowsToInsert = [];
        $now = now();
        foreach ($payload['answers'] as $item) {
            $qId = $item['question_id'];
             if (!in_array($item['question_id'], $questionsMap)) {
                continue; // question not part of this survey
            }

            $answerValue = null;
            $rating = null;
            $properties = null;
            $option_id = null;

            switch ($item['type']) {
                case 'rating':
                case 'text':
                case 'date':
                case 'month_year':
                    $rating = $item['answer'];
                    break;
                case 'single_option':
                case 'dropdown':
                case 'yes_no':
                case 'yes_no_not_sure':
                case 'yes_no_with_checkbox':
                case 'yes_no_with_multi_checkbox':
                    $option_id = $item['option_id'] ?? null;
                    break;

                case 'yes_no_with_extra':
                case 'yes_no_with_question':
                case 'yes_no_with_popup':
                case 'rating_with_text':
                   // dd($answerValue);
                    // answer_value stores "Yes", properties stores the extra text
                    $answerValue = $item['answer']; 
                    $properties = ['extra_info' => $item['extra'] ?? ''];
                    break;

                    // answer_value stores "Yes", properties stores the array of checked items
                    //$answerValue = $item['answer'];
                   // $properties = ['selections' => $item['selections'] ?? []];
                    break;

                default:
                    $answerValue = $item['answer'] ?? null;
                    break;
            }

            $rowsToInsert[] = [
                'user_id'     => $userId,
                'survey_id' => $payload['survey_id'],
                'question_id' => $item['question_id'],
                'option_id' => $option_id,
                'answer'=> $answerValue,
                'rating'      => $rating,
                'properties'  => $properties ? json_encode($properties) : null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        // return DB::transaction(function () use ($rowsToInsert) {
        //     // Use upsert to prevent duplicate answers for the same user/question
        //     return SurveyAnswer::upsert(
        //         $rowsToInsert,
        //         ['user_id', 'survey_id', 'question_id'], // Unique key
        //         ['answer_value', 'rating', 'properties', 'updated_at'] // Update if exists
        //     );

        //     $updateSurveyUser = DB::table('survey_users')
        //         ->where('user_id', $userId)
        //         ->where('survey_id', $rowsToInsert[0]['survey_id'] ?? 0)
        //         ->update(['status' => 'completed', 'updated_at' => now()]);
        // });

        // dd($rowsToInsert);

        //dd($userId);
        if (empty($rowsToInsert)) {
            throw new \Exception('No survey answers to save');
        }
        $result = DB::transaction(function () use ($rowsToInsert, $userId) {
            $upserted = SurveyAnswer::upsert(
                $rowsToInsert,
                ['user_id', 'survey_id', 'question_id'],
                ['answer', 'rating', 'properties', 'option_id', 'updated_at']
            );

            DB::table('survey_users')
                ->where('user_id', $userId)
                ->where('survey_id', $rowsToInsert[0]['survey_id'] ?? 0)
                ->update([
                    'status' => 'completed',
                    'updated_at' => now()
                ]);

                  User::where('id', $userId)->update([
                        'status' => UserStatus::Active,
                    ]);

               // dd($rowsToInsert);

            return true;
        });
    }
}