<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BellscaleAnswer extends Model
{
    
    use SoftDeletes;
    protected $guarded = [];

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
