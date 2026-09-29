<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionChart;
use App\Models\Survey;
use App\Models\SurveyChart;
use App\Repositories\Admin\SettingsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function __construct(SettingsRepository $settingsRepo)
    {
        $this->settingsRepo = $settingsRepo;
    }
    public function index(Request $request){
        $questions = Question::pluck('question', 'id');
        $surveys = Survey::pluck('title', 'id');
        $charts = config('constant.CHART_TYPE');
        //$settings = QuestionChart::with('question')->get();
        $settings  = QuestionChart::all()->map(fn($s) => [
            'id'          => $s->id,
            'question_id' => (string) $s->question_id,  // must be string
            'chart_type'  => (string) $s->chart_type,   // must be string
        ])->values();
        $surveySettings = SurveyChart::all()->map(fn($s) => [
            'id'          => $s->id,
            'survey_id'   => (string) $s->survey_id,
            'question_id' => (string) $s->question_id,
            'chart_type'  => (string) $s->chart_type,
        ])->values();

        //dd($settings);

        return view('settings.index', compact('questions','surveys', 'charts', 'settings', 'surveySettings'));
    }
    public function surveyQuestions(Survey $survey)
{
    return response()->json([
        'questions' => $survey->surveyQuestions()
            ->join('questions', 'questions.id', '=', 'survey_questions.question_id')
            ->pluck('questions.question', 'questions.id')
    ]);
}

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'charts' => 'required|array',
            'charts.*.question_id' => 'required|exists:questions,id',
            'charts.*.chart_type' => 'required|string',
        ]);

        try {
            $this->settingsRepo->storeOrUpdate($request->charts);

            // Return fresh data so the frontend can update IDs (null → real DB ID)
            $charts = QuestionChart::all()->map(fn($s) => [
                'id'          => $s->id,
                'question_id' => (string) $s->question_id,
                'chart_type'  => (string) $s->chart_type,
            ])->values();

            return response()->json([
                'message' => 'Settings updated successfully',
                'charts'  => $charts,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error saving data'], 500);
        }
    }

    public function storeOrUpdateSurvey(Request $request)
    {
        $request->validate([
            'charts' => 'required|array',
            'charts.*.survey_id' => 'required|exists:surveys,id',
            'charts.*.question_id' => 'required|exists:questions,id',
            'charts.*.chart_type' => 'required|string',
        ]);

        try {
            $this->settingsRepo->storeOrUpdateSurvey($request->charts);

            // Return fresh data so the frontend can update IDs (null → real DB ID)
            $charts = SurveyChart::all()->map(fn($s) => [
                'id' => $s->id,
                'survey_id' => (string) $s->survey_id,
                'question_id' => (string) $s->question_id,
                'chart_type'  => (string) $s->chart_type,
            ])->values();

            return response()->json([
                'message' => 'Settings updated successfully',
                'charts'  => $charts,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error saving data'], 500);
        }
    }
}
