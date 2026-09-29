<?php

namespace App\Repositories\Admin;

use App\Models\bellscaleAnswer;
use App\Models\generalAnswer;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class ReportRepository extends BaseRepository
{
    public function model()
    {
        return Question::class;
    }

public function getModuleReport(string $moduleName, ?string $from = null, ?string $to = null): array
{
    // Single  query with all calculations
     $questions = Question::with([
        'options',
        'label',
        'generalAnswers' => function ($q) use ($from, $to) {
            if ($from && $to) {
                $q->whereBetween('created_at', [
                    Carbon::parse($from)->startOfDay(),
                    Carbon::parse($to)->endOfDay(),
                ]);
            }
            $q->with('user'); // eager load user on each answer
        }
    ])
    ->where('module_name', $moduleName)
    ->withCount([
        'generalAnswers as total_responses' => function ($q) use ($from, $to) {
            if ($from && $to) {
                $q->whereBetween('created_at', [
                    Carbon::parse($from)->startOfDay(),
                    Carbon::parse($to)->endOfDay(),
                ]);
            }
        }
    ])->get();


    //dd($questions);

    // Batch load all answer data in one query per type
    $questionIds = $questions->pluck('id');

    $dateScope = function ($q) use ($from, $to) {
        if ($from && $to) {
            $q->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        }
    };

    
    // Pre-load all option counts
    $optionCounts = DB::table('general_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('option_id')
        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        })
        ->select('question_id', 'option_id', DB::raw('COUNT(*) as count'))
        ->groupBy('question_id', 'option_id')
        ->get()
        ->groupBy('question_id');

    // Pre-load averages for single choice with option values
    $averages = DB::table('general_answers')
        ->join('question_options', 'general_answers.option_id', '=', 'question_options.id')
        ->whereIn('general_answers.question_id', $questionIds)
        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('general_answers.created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        })
        ->select('general_answers.question_id', DB::raw('AVG(question_options.option_value) as avg_value'))
        ->groupBy('general_answers.question_id')
        ->pluck('avg_value', 'question_id');

    // Pre-load numeric stats
    $numericStats = DB::table('general_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('numeric_answer_value')
        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        })
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
        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        })
        ->select('question_id', 'answers')
        ->get()
        ->groupBy('question_id');

    // Pre-load text answers
    $textAnswers = DB::table('general_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('answer')
        ->whereNull('option_id')
        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        })
        ->select('question_id', 'answer')
        ->get()
        ->groupBy('question_id');

    return $questions->map(function ($question) use ($optionCounts, $averages, $numericStats, $multipleChoiceData, $textAnswers) {
        return [
            'question_id' => $question->id,
            'question'    => $question->question,
            'type'        => $question->type,
            'created_at' => $question->_created_at?->toISOString(),
            'label'      => $question->label ? ['label' => $question->label->label, 'color' => $question->label->color] : null,
            'report'      => $this->buildReportByType($question, $optionCounts, $averages, $numericStats, $multipleChoiceData, $textAnswers),
            'respondents' => $question->generalAnswers->map(fn($answer) => [
            'answer_id'  => $answer->id,
            'created_at' => $answer->created_at?->toISOString(),
            'user' => $answer->user ? [
                'id'   => $answer->user->id,
                'patient_id' => $answer->user->patient_id,
                'name' => $answer->user->first_name . ' '. $answer->user->last_name,
                'email'=> $answer->user->email,
            ] : null,
        ])->values(),
        ];
    })->toArray();
}

//GET A SINGLE QUESTION RESPONSE
public function getSingleReport(string $moduleName, ?string $questionId = null, ?string $from = null, ?string $to = null): array
{
    // Single  query with all calculations
    $dateScope = function ($q) use ($from, $to) {
        return $q->whereBetween('created_at', [
            Carbon::parse($from)->startOfDay(),
            Carbon::parse($to)->endOfDay(),
        ]);
    };
    ///->when($from && $to, $dateScope)
     $questions = Question::with([
        'options',
        'label',
        'generalAnswers' => function ($q) use ($dateScope, $from, $to) {
            if ($from && $to) {
                $dateScope($q);
            }
            $q->with(['user:id,patient_id,first_name,last_name,email']);
        }
    ])
    ->where('module_name', $moduleName)
    ->where('id', $questionId)
    ->withCount([
        'generalAnswers as total_responses' => function ($q) use ($from, $to) {
            if ($from && $to) {
                $q->whereBetween('created_at', [
                    Carbon::parse($from)->startOfDay(),
                    Carbon::parse($to)->endOfDay(),
                ]);
            }
        }
    ])->first();


    //dd($questions);

    // Batch load all answer data in one query per type
    $questionIds = collect([$questions->id]);

    $dateScope = function ($q) use ($from, $to) {
        if ($from && $to) {
            $q->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        }
    };

    
    // Pre-load all option counts
    $optionCounts = DB::table('general_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('option_id')
        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        })
        ->select('question_id', 'option_id', DB::raw('COUNT(*) as count'))
        ->groupBy('question_id', 'option_id')
        ->get()
        ->groupBy('question_id');

    // Pre-load averages for single choice with option values
    $averages = DB::table('general_answers')
        ->join('question_options', 'general_answers.option_id', '=', 'question_options.id')
        ->whereIn('general_answers.question_id', $questionIds)
        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('general_answers.created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        })
        ->select('general_answers.question_id', DB::raw('AVG(question_options.option_value) as avg_value'))
        ->groupBy('general_answers.question_id')
        ->pluck('avg_value', 'question_id');

    // Pre-load numeric stats
    $numericStats = DB::table('general_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('numeric_answer_value')
        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        })
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
        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        })
        ->select('question_id', 'answers')
        ->get()
        ->groupBy('question_id');

    // Pre-load text answers
    $textAnswers = DB::table('general_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('answer')
        ->whereNull('option_id')
        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        })
        ->select('question_id', 'answer')
        ->get()
        ->groupBy('question_id');

    return [
        'question' => [
            'id'         => $questions->id,
            'text'       => $questions->question,
            'type'       => $questions->type,
            'created_at' => $questions->created_at?->toISOString(),
            'label'      => $questions->label ? [
                'label' => $questions->label->label,
                'color' => $questions->label->color,
            ] : null,
        ],

        'summary' => $this->buildReportByType(
            $questions,
            $optionCounts,
            $averages,
            $numericStats,
            $multipleChoiceData,
            $textAnswers
        ),

        'respondents' => $questions->generalAnswers->map(function ($answer) use ($questions) {
            $resolvedAnswer = $answer->answer
                ?? $answer->numeric_answer_value
                ?? (is_string($answer->answers)
                    ? json_decode($answer->answers, true)
                    : $answer->answers);

            if ($resolvedAnswer === null && $answer->option_id) {
                $option = $questions->options->firstWhere('id', $answer->option_id);
                $resolvedAnswer = $option?->option ?? $option?->option_text ?? null;
            }

            return [
                'answer_id'    => $answer->id,
                'submitted_at' => $answer->created_at?->toISOString(),
                'answer'       => $resolvedAnswer,
                'option_id'    => $answer->option_id,
                'user'         => $answer->user ? [
                    'id'         => $answer->user->id,
                    'patient_id' => $answer->user->patient_id,
                    'name'       => trim(($answer->user->first_name ?? '') . ' ' . ($answer->user->last_name ?? '')),
                    'email'      => $answer->user->email,
                ] : null,
            ];
        })->values(),

        'meta' => [
            'total_responses' => $questions->total_responses ?? $questions->generalAnswers->count(),
            'has_answers'     => $questions->generalAnswers->isNotEmpty(),
        ],
    ];
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
//GENERAL QUESTIONS REPORT ENDS HERE

//BELLSCALE REPORT STARTS HERE 

public function getBellscaleReport(string $moduleName): array
{
    // Single  query with all calculations
    $questions = Question::with(['options'])
        ->where('module_name', $moduleName)
        ->withCount('bellscaleAnswers as total_responses')
        ->get();

    // Batch load all answer data in one query per type
    $questionIds = $questions->pluck('id');
    
    // Pre-load all option counts
    $optionCounts = DB::table('bellscale_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('option_id')
        ->select('question_id', 'option_id', DB::raw('COUNT(*) as count'))
        ->groupBy('question_id', 'option_id')
        ->get()
        ->groupBy('question_id');

    // Pre-load averages for single choice with option values
    $averages = DB::table('bellscale_answers')
        ->join('question_options', 'bellscale_answers.option_id', '=', 'question_options.id')
        ->whereIn('bellscale_answers.question_id', $questionIds)
        ->select('bellscale_answers.question_id', DB::raw('AVG(question_options.option_value) as avg_value'))
        ->groupBy('bellscale_answers.question_id')
        ->pluck('avg_value', 'question_id');

    // Pre-load numeric stats
    $numericStats = DB::table('bellscale_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('answer_number')
        ->select(
            'question_id',
            DB::raw('COUNT(*) as count'),
            DB::raw('AVG(answer_number) as avg_val'),
            DB::raw('MIN(answer_number) as min_val'),
            DB::raw('MAX(answer_number) as max_val')
        )
        ->groupBy('question_id')
        ->get()
        ->keyBy('question_id');


    // Pre-load text answers
    $textAnswers = DB::table('bellscale_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('answer')
        ->whereNull('option_id')
        ->select('question_id', 'answer')
        ->get()
        ->groupBy('question_id');

    return $questions->map(function ($question) use ($optionCounts, $averages, $numericStats, $textAnswers) {
        return [
            'question_id' => $question->id,
            'question'    => $question->question,
            'type'        => $question->type,
            'label'      => $question->label ? ['label' => $question->label->label, 'color' => $question->label->color] : null,
            'report'      => $this->buildBellscaleReportByType($question, $optionCounts, $averages, $numericStats, $textAnswers),
        ];
    })->toArray();
}

protected function buildBellscaleReportByType(
    Question $question, 
    $optionCounts, 
    $averages, 
    $numericStats, 
    $textAnswers
): array {
    return match ($question->type) {
        'scores', 'dropdown', 'yes_no' 
            => $this->singleChoiceBellscaleReport($question, $optionCounts, $averages),
        'rating' 
            => $this->numericBellscaleReport($question, $numericStats),
        default 
            => $this->textBellscaleReport($question, $textAnswers),
    };
}

protected function singleChoiceBellscaleReport(Question $question, $optionCounts, $averages): array
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


protected function numericBellscaleReport(Question $question, $numericStats): array
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

protected function textBellscaleReport(Question $question, $textAnswers): array
{
    $questionAnswers = $textAnswers->get($question->id, collect());

    return [
        'total_responses' => $questionAnswers->count(),
        'answers'         => $questionAnswers->pluck('answer')->values(),
    ];
}

//BELLSCALE REPORT ENDS HERE

    //Survey report STARTS HERE
    
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
    // Load survey structure only
    $survey = Survey::with([
        'surveyQuestions.question:id,question,type',
        'surveyQuestions.question.options:id,question_id,option_text'
    ])->findOrFail($surveyId);

    $questionIds = $survey->surveyQuestions->pluck('question_id');

    // Single optimized query for ALL answers
    $answersQuery = DB::table('survey_answers')
        ->leftJoin('question_options', 'question_options.id', '=', 'survey_answers.option_id')
        ->join('survey_questions', 'survey_questions.question_id', '=', 'survey_answers.question_id')
        ->where('survey_questions.survey_id', $surveyId)
        ->whereIn('survey_answers.question_id', $questionIds)
        ->select(
            'survey_answers.question_id',
            'survey_answers.answer',
            'survey_answers.numeric_answer_value',
            'survey_answers.option_id',
            'question_options.option_text'
        );

    if ($userId) {
        $answersQuery->where('survey_answers.user_id', $userId);
    }

    $answers = $answersQuery->get()->groupBy('question_id');

    return [
        'survey_id' => $survey->id,
        'survey_title' => $survey->title,
        'questions' => $survey->surveyQuestions->map(function ($surveyQuestion) use ($answers) {

            $question = $surveyQuestion->question;

            if (!$question) {
                return null;
            }

            $questionAnswers = $answers->get($question->id, collect());

            return [
                'question_id' => $question->id,
                'question' => $question->question,
                'type' => $question->type,
                'report' => $this->buildSimpleReport($question, $questionAnswers),
            ];
        })->filter()->values()->toArray(),
    ];
}
protected function buildSimpleReport($question, $answers): array
{
    switch ($question->type) {

        case 'single_option':
        case 'dropdown':
        case 'yes_no_with_checkbox':
        case 'yes_no_with_multi_checkbox':

            return [
                'selected_options' => $answers
                    ->pluck('option_text')
                    ->filter()
                    ->values()
                    ->toArray(),
            ];

        case 'multiple_choice':

            return [
                'answers' => $answers
                    ->pluck('answer')
                    ->map(fn ($json) => is_string($json) ? json_decode($json, true) : $json)
                    ->filter()
                    ->values()
                    ->toArray(),
            ];

        case 'rating':

            return [
                'values' => $answers
                    ->pluck('numeric_answer_value')
                    ->filter()
                    ->values()
                    ->toArray(),
            ];

        default:

            return [
                'answers' => $answers
                    ->pluck('answer')
                    ->filter()
                    ->values()
                    ->toArray(),
            ];
    }
}

//Survey Report Ends Here

//General  Questions CHARTS REPORT STARTS HERE

public function getChartReport(array $questionIds = []): array
{
    $query = Question::with(['options', 'label'])
        ->withCount('generalAnswers as total_responses');

    // Filter to only configured question IDs if provided
    if (!empty($questionIds)) {
        $query->whereIn('id', $questionIds);
    }

    $questions = $query->get();

    $questionIds = $questions->pluck('id');

    // Pre-load all option counts
    $optionCounts = DB::table('general_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('option_id')
        ->select('question_id', 'option_id', DB::raw('COUNT(*) as count'))
        ->groupBy('question_id', 'option_id')
        ->get()
        ->groupBy('question_id');

    // Pre-load averages
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

    // Pre-load multiple choice answers
    $multipleChoiceData = DB::table('general_answers')
        ->whereIn('question_id', $questionIds)
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
            'label'       => $question->label
                ? ['label' => $question->label->label, 'color' => $question->label->color]
                : null,
            'report'      => $this->buildChartReportByType(
                $question, $optionCounts, $averages, $numericStats, $multipleChoiceData, $textAnswers
            ),
        ];
    })->toArray();
}


//SURVEY CHARTS REPORT
public function getSurveyChartReport(array $questionIds = []): array
{
    $query = Question::with(['options', 'label'])
        ->withCount('surveyAnswers as total_responses');

    // Filter to only configured question IDs if provided
    if (!empty($questionIds)) {
        $query->whereIn('id', $questionIds);
    }

    $questions = $query->get();

    $questionIds = $questions->pluck('id');

    // Pre-load all option counts
    $optionCounts = DB::table('survey_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('option_id')
        ->select('question_id', 'option_id', DB::raw('COUNT(*) as count'))
        ->groupBy('question_id', 'option_id')
        ->get()
        ->groupBy('question_id');

    // Pre-load averages
    $averages = DB::table('survey_answers')
        ->join('question_options', 'survey_answers.option_id', '=', 'question_options.id')
        ->whereIn('survey_answers.question_id', $questionIds)
        ->select('survey_answers.question_id', DB::raw('AVG(question_options.option_value) as avg_value'))
        ->groupBy('survey_answers.question_id')
        ->pluck('avg_value', 'question_id');

    // Pre-load numeric stats
    $numericStats = DB::table('survey_answers')
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

    // Pre-load multiple choice answers
    $multipleChoiceData = DB::table('survey_answers')
        ->whereIn('question_id', $questionIds)
        ->whereNotNull('answer')
        ->select('question_id', 'answer')
        ->get()
        ->groupBy('question_id');

    // Pre-load text answers
    $textAnswers = DB::table('survey_answers')
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
            'label'       => $question->label
                ? ['label' => $question->label->label, 'color' => $question->label->color]
                : null,
            'report'      => $this->buildChartReportByType(
                $question, $optionCounts, $averages, $numericStats, $multipleChoiceData, $textAnswers
            ),
        ];
    })->toArray();
}

// Also fix the method name mismatch — you call buildReportByType but defined buildChartReportByType
// Make sure this is consistent. Use buildChartReportByType everywhere.

protected function buildChartReportByType(
    Question $question,
    $optionCounts,
    $averages,
    $numericStats,
    $multipleChoiceData,
    $textAnswers
): array {
    return match ($question->type) {
        'single_option', 'dropdown', 'yes_no_with_checkbox', 'yes_no_with_multi_checkbox'
            => $this->singleChoiceChartReport($question, $optionCounts, $averages),
        'multiple_choice'
            => $this->multipleChoiceChartReport($question, $multipleChoiceData),
        'rating'
            => $this->numericChartReport($question, $numericStats),
        default
            => $this->textChartReport($question, $textAnswers),
    };
}

protected function singleChoiceChartReport(Question $question, $optionCounts, $averages): array
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

protected function multipleChoiceChartReport(Question $question, $multipleChoiceData): array
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

protected function numericChartReport(Question $question, $numericStats): array
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

protected function textChartReport(Question $question, $textAnswers): array
{
    $questionAnswers = $textAnswers->get($question->id, collect());

    return [
        'total_responses' => $questionAnswers->count(),
        'answers'         => $questionAnswers->pluck('answer')->values(),
    ];
}
//CHART REPORTS ENDS HERE


}