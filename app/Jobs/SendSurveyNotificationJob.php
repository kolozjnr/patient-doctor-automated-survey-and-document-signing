<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\SurveyNotification;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendSurveyNotificationJob implements ShouldQueue
{
    use Queueable;
    protected $survey;
    protected array $patientIds;
    public $tries = 3;
    public $timeout = 120;
    /**
     * Create a new job instance.
     */
    public function __construct($survey, array $patientIds)
    {
        $this->survey = $survey;
        $this->patientIds = $patientIds;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $users = User::whereIn('id', $this->patientIds)->get();
            Notification::send($users, new SurveyNotification($this->survey));
        } catch (\Exception $e) {
            // Log the error
            \Log::error("Failed to send survey notifications: " . $e->getMessage());
        }
    }
}
