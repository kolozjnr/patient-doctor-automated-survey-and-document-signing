<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    const CREATED_AT = '_created_at';
    const UPDATED_AT = '_updated_at';

    // protected $casts = [
    //     'options' => 'array',
    // ];

    public function parent()
    {
        return $this->belongsTo(Question::class, 'parent_question_id');
    }

    public function children()
    {
        return $this->hasMany(Question::class, 'parent_question_id');
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id')->orderBy('display_order');
    }

    public function hasOptions()
    {
        return in_array($this->type, ['single_choice', 'multiple_choice']);
    }

    public function label()
    {
        return $this->belongsTo(Label::class,'question_label');
    }

    public function surveyQuestions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function generalAnswers()
    {
        return $this->hasMany(generalAnswer::class);
    }

    public function surveyAnswers()
    {
        return $this->hasMany(surveyAnswer::class);
    }

    public function bellscaleAnswers()
    {
        return $this->hasMany(BellscaleAnswer::class);
    }

    public function surveys()
    {
        return $this->belongsToMany(
            Survey::class,
            'survey_questions',
            'question_id',
            'survey_id'
        );
    }

}
