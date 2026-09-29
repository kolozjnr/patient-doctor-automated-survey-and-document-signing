<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyQuestion extends Model
{
    use SoftDeletes;
    protected $table = 'survey_questions';

    protected $guarded = [];
    
    protected $casts = [
        'sort_order' => 'integer',
        'response_rating' => 'integer',
        'response_multi_options' => 'array',
        'response_multi_options_multi_answer' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function hasResponse(): bool
    {
        return !is_null($this->response_text) ||
               !is_null($this->response_rating) ||
               !is_null($this->response_yes_no) ||
               !is_null($this->response_multi_options) ||
               !is_null($this->response_multi_options_multi_answer);
    }
}