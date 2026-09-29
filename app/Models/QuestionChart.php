<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionChart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['question_id', 'chart_type'];

    public function question() {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}

