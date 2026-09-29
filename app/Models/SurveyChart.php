<?php

namespace App\Models;

use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyChart extends Model
{
    use HasFactory;
    
    protected $table = 'survey_charts';
    protected $fillable = ['survey_id', 'question_id', 'chart_type'];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
