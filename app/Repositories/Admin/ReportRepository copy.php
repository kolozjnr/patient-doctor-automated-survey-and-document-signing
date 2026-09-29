<?php

namespace App\Repositories\Admin;

use App\Models\User;
use App\Models\Survey;
use App\Models\Question;
use App\Models\generalAnswer;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class ReportRepository extends BaseRepository
{
    public function model()
    {
        return Question::class;
    }

public function getModuleReport(string $moduleName): array
{
    // Single  query with all calculations
    $questions = Question::with(['options', 'label'])
        ->where('module_name', $moduleName)
        ->withCount('generalAnswers as total_responses')
        ->get();

    // Batch load all answer data in one query per type
    $questionIds = $questions->pluck('id');
    
    // Pre-load all option counts
    $optionCounts = DB::table('general_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('option_id')
        ->select('question_id', 'option_id', DB::raw('COUNT(*) as count'))
        ->groupBy('question_id', 'option_id')
        ->get()
        ->groupBy('question_id');

    // Pre-load averages for single choice with option values
    $averages = DB::table('general_answers')
        ->join('question_options', 'general_answers.option_id', '=', 'question_options.id')
        ->whereIn('general_answers.question_id', $questionIds)
        ->select('general_answers.question_id', DB::raw('AVG(question_options.option_value) as avg_value'))
        ->groupBy('general_answers.question_id')
        ->pluck('avg_value', 'question_id');

    // Pre-load numeric stats
    $numericStats = DB::table('general_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('numeric_answer_value')
        ->select(
            'question_id',
            DB::raw('COUNT(*) as count'),
            DB::raw('AVG(numeric_answer_value) as avg_val'),
            DB::raw('MIN(numeric_answer_value) as min_val'),
            DB::raw('MAX(numeric_answer_value) as max_val')
        )
        ->groupBy('question_id')
        ->get()
        ->keyBy('question_id');

    // Pre-load multiple choice answer arrays
    $multipleChoiceData = DB::table('general_answers')
        ->whereIn('question_id', $questionIds)
        //->whereNull('option_id')
        ->whereNotNull('answers')
        ->select('question_id', 'answers')
        ->get()
        ->groupBy('question_id');

    // Pre-load text answers
    $textAnswers = DB::table('general_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('answer')
        ->whereNull('option_id')
        ->select('question_id', 'answer')
        ->get()
        ->groupBy('question_id');

    return $questions->map(function ($question) use ($optionCounts, $averages, $numericStats, $multipleChoiceData, $textAnswers) {
        return [
            'question_id' => $question->id,
            'question'    => $question->question,
            'type'        => $question->type,
            'label'      => $question->label ? ['label' => $question->label->label, 'color' => $question->label->color] : null,
            'report'      => $this->buildReportByType($question, $optionCounts, $averages, $numericStats, $multipleChoiceData, $textAnswers),
        ];
    })->toArray();
}

protected function buildReportByType(
    Question $question, 
    $optionCounts, 
    $averages, 
    $numericStats, 
    $multipleChoiceData, 
    $textAnswers
): array {
    return match ($question->type) {
        'single_option', 'dropdown', 'yes_no_with_checkbox', 'yes_no_with_multi_checkbox' 
            => $this->singleChoiceReport($question, $optionCounts, $averages),
        'multiple_choice' 
            => $this->multipleChoiceReport($question, $multipleChoiceData),
        'rating' 
            => $this->numericReport($question, $numericStats),
        default 
            => $this->textReport($question, $textAnswers),
    };
}

protected function singleChoiceReport(Question $question, $optionCounts, $averages): array
{
    $questionCounts = $optionCounts->get($question->id, collect());
    $totalResponses = $questionCounts->sum('count');
    $average = $averages->get($question->id);

    return [
        'total_responses' => $totalResponses,
        'average' => $average ? round($average, 2) : null,
        'options' => $question->options->map(function ($option) use ($questionCounts, $totalResponses) {
            $optionData = $questionCounts->firstWhere('option_id', $option->id);
            $count = $optionData->count ?? 0;

            return [
                'option_id' => $option->id,
                'text'      => $option->option_text,
                'value'     => $option->option_value,
                'count'     => $count,
                'percentage'=> $totalResponses ? round(($count / $totalResponses) * 100, 2) : 0,
            ];
        })->values(),
    ];
}

protected function multipleChoiceReport(Question $question, $multipleChoiceData): array
{
    $questionData = $multipleChoiceData->get($question->id, collect());
    
    $counts = $questionData
        ->pluck('answers')
        ->map(fn($json) => is_string($json) ? json_decode($json, true) : $json)
        ->flatten()
        ->countBy();
    $totalSelections = $counts->sum();
    return [
        'total_responses' => $questionData->count(),
        'options' => $question->options->map(function ($option) use ($counts, $totalSelections) {
            $count = $counts[$option->id] ?? 0;

            return [
                'option_id' => $option->id,
                'text'      => $option->option_text,
                'value'     => $option->option_value,
                'count'     => $count,
                'percentage'=> $totalSelections ? round(($count / $totalSelections) * 100, 2) : 0,
            ];
        })->values(),
    ];
}

protected function numericReport(Question $question, $numericStats): array
{
    $stats = $numericStats->get($question->id);

    if (!$stats) {
        return [
            'total_responses' => 0,
            'average' => null,
            'min' => null,
            'max' => null,
        ];
    }

    return [
        'total_responses' => $stats->count,
        'average' => round($stats->avg_val, 2),
        'min'     => $stats->min_val,
        'max'     => $stats->max_val,
    ];
}

protected function textReport(Question $question, $textAnswers): array
{
    $questionAnswers = $textAnswers->get($question->id, collect());

    return [
        'total_responses' => $questionAnswers->count(),
        'answers'         => $questionAnswers->pluck('answer')->values(),
    ];
}



    //Survey report
    
        public function getBatchSurveys()
        {
            return Survey::withCount([
                    'users as batch_patient_count',
                    'surveyQuestions as questions_count'
                ])
                ->orderBy('created_at', 'desc')
                ->get(['id', 'title', 'description', 'frequency', 'survey_delivery_date', 'cron_status', 'created_at']);
        }

        public function getSurveyUsers(int $surveyId): Collection
        {
            return Survey::with('users')
                ->findOrFail($surveyId)
                ->users;
        }


        
       public function getSurveyReport(int $surveyId, ?int $userId = null): array
    {
        // Load survey with relationships
        $survey = Survey::with(['surveyQuestions.question.options', 'users'])
            ->findOrFail($surveyId);

        // Get all question IDs from this survey
        $questionIds = $survey->surveyQuestions->pluck('question_id');

       // dd($questionIds);

        // Build base query for survey answers
        $answersQuery = DB::table('survey_answers')
            ->join('survey_questions', 'survey_answers.question_id', '=', 'survey_questions.question_id')
            ->where('survey_questions.survey_id', $surveyId)
            ->whereIn('survey_questions.question_id', $questionIds);

        // Filter by user if specified
        if ($userId) {
            $answersQuery->where('survey_answers.user_id', $userId);
        }

        // Pre-load all option counts
        $optionCounts = (clone $answersQuery)
            ->whereNotNull('survey_answers.option_id')
            ->select(
                'survey_questions.question_id',
                'survey_answers.option_id',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('survey_questions.question_id', 'survey_answers.option_id')
            ->get()
            ->groupBy('question_id');

        // Pre-load averages for single choice with option values
        $averages = (clone $answersQuery)
            ->join('question_options', 'survey_answers.option_id', '=', 'question_options.id')
            ->select(
                'survey_questions.question_id',
                DB::raw('AVG(question_options.option_value) as avg_value')
            )
            ->groupBy('survey_questions.question_id')
            ->pluck('avg_value', 'question_id');

        // Pre-load numeric stats
        $numericStats = (clone $answersQuery)
            ->whereNotNull('survey_answers.numeric_answer_value')
            ->select(
                'survey_questions.question_id',
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(survey_answers.numeric_answer_value) as avg_val'),
                DB::raw('MIN(survey_answers.numeric_answer_value) as min_val'),
                DB::raw('MAX(survey_answers.numeric_answer_value) as max_val')
            )
            ->groupBy('survey_questions.question_id')
            ->get()
            ->keyBy('question_id');

        // Pre-load multiple choice answer arrays
        $multipleChoiceData = (clone $answersQuery)
            ->whereNotNull('survey_answers.answer')
            ->select('survey_questions.question_id', 'survey_answers.answer')
            ->get()
            ->groupBy('question_id');

        // Pre-load text answers
        $textAnswers = (clone $answersQuery)
            ->whereNotNull('survey_answers.answer')
            ->whereNull('survey_answers.option_id')
            ->select('survey_questions.question_id', 'survey_answers.answer')
            ->get()
            ->groupBy('question_id');

       
                    
      $optionAnswers = DB::table('survey_answers')
    ->join('survey_questions', 'survey_answers.question_id', '=', 'survey_questions.question_id')
    ->leftJoin('question_options', 'question_options.id', '=', 'survey_answers.option_id')
    ->where('survey_questions.survey_id', $surveyId)
    ->where('survey_answers.user_id', $userId)
    ->whereIn('survey_questions.question_id', $questionIds)
    ->whereNotNull('survey_answers.option_id')
    ->whereNull('survey_answers.answer')
    ->select(
        'survey_questions.question_id',
        'survey_answers.option_id',
        'question_options.option_text'
    )
    ->get()
    ->groupBy('question_id');


   

        
       $responseCounts = DB::table('survey_answers')
    ->join('survey_questions', 'survey_answers.question_id', '=', 'survey_questions.question_id')
    ->where('survey_questions.survey_id', $surveyId)
    ->where('survey_answers.user_id', $userId)
    ->whereIn('survey_questions.question_id', $questionIds)
    ->groupBy('survey_answers.question_id')
    ->select('survey_answers.question_id', DB::raw('COUNT(*) as total'))
    ->pluck('total', 'question_id');


            //dd($responseCounts);

        return [
            'survey_id' => $survey->id,
            'survey_title' => $survey->title,
            'survey_description' => $survey->description,
            'total_users' => $survey->users->count(),
            'frequency' => $survey->frequency,
            'status' => $survey->cron_status,
            'questions' => $survey->surveyQuestions->map(function ($surveyQuestion) use (
                $optionCounts,
                $averages,
                $numericStats,
                $multipleChoiceData,
                $textAnswers,
                $optionAnswers,
                $responseCounts
            ) {
                $question = $surveyQuestion->question;
                
                if (!$question) {
                    return null; // Skip deleted questions
                }

                return [
                    'survey_question_id' => $surveyQuestion->id,
                    'question_id' => $question->id,
                    'question' => $question->question,
                    'type' => $question->type,
                    'sort_order' => $surveyQuestion->sort_order,
                    'label' => $question->label ? [
                        'label' => $question->label->label,
                        'color' => $question->label->color
                    ] : null,
                    'report' => $this->SurveyBuildReportByType(
                        $question,
                        $optionCounts,
                        $averages,
                        $numericStats,
                        $multipleChoiceData,
                        $textAnswers,
                        $optionAnswers,
                        $responseCounts
                    ),
                ];
            })->filter()->values()->toArray(),
        ];
    }

    public function getUserSurveyReport(int $surveyId, int $userId): array
    {
        return $this->getSurveyReport($surveyId, $userId);
    }

    protected function SurveyBuildReportByType(
        $question,
        $optionCounts,
        $averages,
        $numericStats,
        $multipleChoiceData,
        $textAnswers,
        $optionAnswers,
        $responseCounts
    ): array {
        return match ($question->type) {
            'single_option', 'dropdown', 'yes_no_with_checkbox', 'yes_no_with_multi_checkbox'
                => $this->surveySingleChoiceReport($question, $optionCounts, $averages, $responseCounts, $optionAnswers),
            'multiple_choice'
                => $this->surveyMultipleChoiceReport($question, $multipleChoiceData, $responseCounts),
            'rating'
                => $this->surveyNumericReport($question, $numericStats),
            default
                => $this->surveyTextReport($question, $textAnswers, $responseCounts),
        };
    }

    protected function surveySingleChoiceReport($question, $optionCounts, $averages, $responseCounts, $optionAnswers): array
    {
        $questionCounts = $optionCounts->get($question->id, collect());
        $totalResponses = $responseCounts->get($question->id, 0);
        $average = $averages->get($question->id);
        $selectedOption = optional(
        $optionAnswers->get($question->id)?->first())->option_text;



        // Use getRelation to avoid JSON column conflict
        $questionOptions = $question->relationLoaded('options') 
            ? $question->getRelation('options') 
            : $question->options()->get();

        return [
            'total_responses' => $totalResponses,
            'selected_option' => $selectedOption,
            'average' => $average ? round($average, 2) : null,
            'options' => $questionOptions->map(function ($option) use ($questionCounts, $totalResponses) {
                $optionData = $questionCounts->firstWhere('option_id', $option->id);
                $count = $optionData->count ?? 0;

                return [
                    'option_id' => $option->id,
                    'text' => $option->option_text,
                    'value' => $option->option_value,
                    'count' => $count,
                    'percentage' => $totalResponses ? round(($count / $totalResponses) * 100, 2) : 0,
                ];
            })->values()->toArray(),
        ];
    }

    protected function surveyMultipleChoiceReport($question, $multipleChoiceData, $responseCounts): array
    {
        $questionData = $multipleChoiceData->get($question->id, collect());
        
        $counts = $questionData
            ->pluck('answer')
            ->map(fn($json) => is_string($json) ? json_decode($json, true) : $json)
            ->filter()
            ->flatten()
            ->countBy();

        $totalSelections = $counts->sum();
        $totalResponses = $responseCounts->get($question->id, 0);

        // Use getRelation to avoid JSON column conflict
        $questionOptions = $question->relationLoaded('options')
            ? $question->getRelation('options')
            : $question->options()->get();

        return [
            'total_responses' => $totalResponses,
            'options' => $questionOptions->map(function ($option) use ($counts, $totalSelections) {
                $count = $counts[$option->id] ?? 0;

                return [
                    'option_id' => $option->id,
                    'text' => $option->option_text,
                    'value' => $option->option_value,
                    'count' => $count,
                    'percentage' => $totalSelections ? round(($count / $totalSelections) * 100, 2) : 0,
                ];
            })->values()->toArray(),
        ];
    }

    protected function surveyNumericReport($question, $numericStats): array
    {
        $stats = $numericStats->get($question->id);

        if (!$stats) {
            return [
                'total_responses' => 0,
                'average' => null,
                'min' => null,
                'max' => null,
            ];
        }

        return [
            'total_responses' => (int) $stats->count,
            'average' => $stats->avg_val ? round($stats->avg_val, 2) : null,
            'min' => $stats->min_val,
            'max' => $stats->max_val,
        ];
    }

    protected function surveyTextReport($question, $textAnswers, $responseCounts): array
    {
        $questionAnswers = $textAnswers->get($question->id, collect());

        return [
            'total_responses' => $responseCounts->get($question->id, 0),
            'answers' => $questionAnswers->pluck('answer')->values()->toArray(),
        ];
    }


}