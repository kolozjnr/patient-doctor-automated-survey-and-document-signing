<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class generalAnswer extends Model
{
    protected $table = 'general_answers';

    protected $guarded = [];

    protected $casts = [
        'answers' => 'array',
        'table_answer' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option() {
        return $this->belongsTo(QuestionOption::class, 'option_id');
    }
}
