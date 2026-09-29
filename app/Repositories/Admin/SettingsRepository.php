<?php

namespace App\Repositories\Admin;

use App\Models\QuestionChart;
use App\Models\SurveyChart;
use Exception;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Spatie\Permission\Models\Role;

class SettingsRepository extends BaseRepository
{
    function model()
    {
        return QuestionChart::class;
    }

    public function storeOrUpdate(array $charts)
    {
        // 1. Get all IDs being sent from the Frontend (filter out nulls for new items)
        $incomingIds = collect($charts)->pluck('id')->filter()->toArray();

        // 2. Remove records that are NOT in the incoming request
        // This handles the "if missing from incoming, remove them" part
        QuestionChart::whereNotIn('id', $incomingIds)->delete();

        // 3. Loop through incoming data to Update or Create
        foreach ($charts as $chartData) {
            QuestionChart::updateOrCreate(
                ['id' => $chartData['id'] ?? null], // Look for this ID
                [
                    'question_id' => $chartData['question_id'],
                    'chart_type'  => $chartData['chart_type']
                ]
            );
        }
    }

    public function storeOrUpdateSurvey(array $charts)
    {
        // 1. Get all IDs being sent from the Frontend (filter out nulls for new items)
        $incomingIds = collect($charts)->pluck('id')->filter()->toArray();

        // 2. Remove records that are NOT in the incoming request
        // This handles the "if missing from incoming, remove them" part
        SurveyChart::whereNotIn('id', $incomingIds)->delete();

        // 3. Loop through incoming data to Update or Create
        foreach ($charts as $chartData) {
            SurveyChart::updateOrCreate(
                ['id' => $chartData['id'] ?? null], // Look for this ID
                [
                    'survey_id' => $chartData['survey_id'],
                    'question_id' => $chartData['question_id'],
                    'chart_type'  => $chartData['chart_type']
                ]
            );
        }
    }

}