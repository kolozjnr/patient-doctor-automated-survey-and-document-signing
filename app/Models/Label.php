<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $table = 'labels';
    protected $guarded = [];

    public function surveys()
    {
        return $this->belongsToMany(Survey::class, 'survey_labels')
                    ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_treatment_labels')
                    ->withTimestamps();
    }
}
