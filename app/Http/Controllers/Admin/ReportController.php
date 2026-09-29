<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionChart;
use App\Models\SurveyChart;
use App\Repositories\Admin\ReportRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }
    public function index()
    {
        return view('reports.index');
    }

    public function questionReport()
    {
        return view('reports.questions-report');
    }

    public function bellscaleReport()
    {
        return view('reports.bellscale-report');
    }

    public function chartReport()
    {
        return view('reports.chart-report');
    }

    public function surveyChartReport()
    {
        return view('reports.survey-chart-report');
    }

      public function getSurveyReport()
    {
        return view('reports.surveys-report');
    }

      public function question(int $id)
    {
        return response()->json([
            'success' => true,
            'data'    => $this->reportRepository->getQuestionReport($id),
        ]);
    }

    public function questionsReport(Request $request, string $module)
    {
        try{
            $from = $request->query('from');
            $to = $request->query('to');
            //dd($module, $from, $to);
            return response()->json([
            'success' => true,
            'data'    => $this->reportRepository->getModuleReport($module, $from, $to),
        ]);
        } catch (\Exception $e) {
             Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve report: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function singleQuestionReport(Request $request, string $questionId, string $module)
    {
        try{
            
            $from = null; // $request->query('from');
            $to = null; // $request->query('to');
            return response()->json([
                'success' => true,
                'data'    => $this->reportRepository->getSingleReport($module, $questionId, $from, $to),
            ]);
        } catch (\Exception $e) {
             Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve report: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getBellscaleReport(string $module)
    {
        try{
            return response()->json([
                'success' => true,
                'data'    => $this->reportRepository->getBellscaleReport($module),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve report: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getQuestionCharts()
    {
        try {
            $questionCharts = QuestionChart::with('question')
                ->get()
                ->map(fn($qc) => [
                    'id'          => $qc->id,
                    'question_id' => $qc->question_id,
                    'chart_type'  => $qc->chart_type,
                    'question'    => $qc->question?->question,
                ]);

            return response()->json([
                'success' => true,
                'data'    => $questionCharts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve question charts: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getSurveyChartSettings()
    {
        try {
            $questionCharts = SurveyChart::with('question')
                ->get()
                ->map(fn($qc) => [
                    'id'          => $qc->id,
                    'question_id' => $qc->question_id,
                    'chart_type'  => $qc->chart_type,
                    'question'    => $qc->question?->question,
                ]);

            return response()->json([
                'success' => true,
                'data'    => $questionCharts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve question charts: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getChartReport()
    {
        try {
            // Only get question IDs that have been configured in question_charts
            $configuredQuestionIds = QuestionChart::pluck('question_id')->toArray();

            return response()->json([
                'success' => true,
                'data'    => $this->reportRepository->getChartReport($configuredQuestionIds),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve report: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getSurveyChartReport()
    {
        try {
            // Only get question IDs that have been configured in question_charts
            $configuredQuestionIds = SurveyChart::pluck('question_id')->toArray();

            return response()->json([
                'success' => true,
                'data'    => $this->reportRepository->getSurveyChartReport($configuredQuestionIds),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve report: ' . $e->getMessage(),
            ], 500);
        }
    }

    // public function surveyReport()
    // {
    //     try{
    //         return response()->json([
    //         'success' => true,
    //         'data'    => $this->reportRepository->getSurveyReport(),
    //     ]);
    //     } catch(\Exception $e){

    //     }
    // }


    //survey report

    public function getBatchSurveys(){
        try{
            return response()->json([
                'success' => true,
                'data' => $this->reportRepository->getBatchSurveys()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve batch surveys: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function surveyReport(int $surveyId): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->reportRepository->getSurveyReport($surveyId),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve survey report: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function userSurveys(int $userId): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->reportRepository->getSurveyUsers($userId),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user survey report: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function userSurveyReport(int $surveyId, int $userId): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->reportRepository->getSurveyReport($surveyId, $userId),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user survey report: ' . $e->getMessage(),
            ], 500);
        }
    }

   
}
