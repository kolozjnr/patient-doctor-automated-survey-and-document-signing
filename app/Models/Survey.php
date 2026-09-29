<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Survey extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'surveys';

    protected $guarded = [];
    
    protected $casts = [
        'survey_delivery_date' => 'date',
        'is_completed' => 'boolean',
        'last_sent_at' => 'datetime',
        'send_count' => 'integer',
        'custom_reoccurrence' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'survey_users')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function surveyQuestions()
    {
        return $this->hasMany(SurveyQuestion::class)
                    ->orderBy('sort_order');
    }

    public function labels(){
        return $this->belongsToMany(Label::class, 'survey_labels')
                    ->withTimestamps();
    }

    // public function surveyQuestions(): HasMany
    // {
    //     return $this->hasMany(SurveyQuestion::class)->orderBy('sort_order');
    // }

    public function scopeActive($query)
    {
        return $query->where('cron_status', 'active')
                     ->where('is_completed', false);
    }

    public function scopeByBatch($query, string $batchUuid)
    {
        return $query->where('batch_uuid', $batchUuid);
    }

    public function scopeDueForDelivery($query)
    {
        return $query->where('survey_delivery_date', '<=', now()->toDateString())
                     ->where('cron_status', 'active')
                     ->where('is_completed', false);
    }
}
