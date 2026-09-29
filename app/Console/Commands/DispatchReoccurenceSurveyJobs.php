<?php

namespace App\Console\Commands;

use App\Jobs\SendSurveyJob;
use App\Models\Survey;
use App\Models\SurveyUser;
use App\Notifications\SurveyNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DispatchReoccurenceSurveyJobs extends Command
{
    //IMPORTAND, WE CONSIDER REMOVING THE ELSE STATEMENT FOR THE 4 FUNCTIONS IN LATER
    protected $signature   = 'app:dispatch-reoccurence-survey-jobs';
    protected $description = 'Dispatch surveys based on their recurrence schedule';

    public function handle()
    {
        $this->info('Starting scheduled survey processing...');

        $today = Carbon::now()->startOfDay();

        try {
            $this->processDailySurveys($today);
            $this->processWeeklySurveys($today);
            $this->processMonthlySurveys($today);
            $this->processOnceSurveys($today);
            $this->processCustomFrequencySurveys($today);

            $this->info('Survey processing completed successfully!');
            return 0;

        } catch (\Exception $e) {
            Log::error('Survey scheduling error: ' . $e->getMessage());
            $this->error('Error processing surveys: ' . $e->getMessage());
            return 1;
        }
    }

    // Frequency processors

    private function processDailySurveys(Carbon $today)
    {
        $surveys = Survey::where('frequency', 'daily')
            ->where('cron_status', 'active')
            ->get();

        $activeIds = $surveys->pluck('id')->toArray();
        

        if (empty($activeIds)) return;

        Survey::whereIn('id', $activeIds)->update([
            'is_completed'   => 0,
            'available_date' => today(),
        ]);

        SurveyUser::whereIn('survey_id', $activeIds)->update(['status' => 'pending']);

        foreach ($surveys as $survey) {
            $this->sendSurvey($survey);
            $this->info("Daily survey #{$survey->id} dispatched");
        }
    }


    private function processWeeklySurveys(Carbon $today)
    {
        $todayIndex = (string) $today->dayOfWeek;

        $surveys = Survey::where('frequency', 'weekly')
            ->where('cron_status', 'active')
            ->get();

        $activeIds   = [];
        $inactiveIds = [];

        foreach ($surveys as $survey) {
            if ($this->matchesWeeklySchedule($survey->custom_frequency, $todayIndex)) {
                $activeIds[] = $survey->id;
            } else {
                $inactiveIds[] = $survey->id;
            }
        }

        if (!empty($activeIds)) {
            Survey::whereIn('id', $activeIds)->update([
                'is_completed'   => 0,
                'available_date' => today(),
            ]);
            SurveyUser::whereIn('survey_id', $activeIds)->update(['status' => 'pending']);
        }

        if (!empty($inactiveIds)) {
            Survey::whereIn('id', $inactiveIds)->update(['is_completed' => 1]);
        }

        foreach ($surveys->whereIn('id', $activeIds) as $survey) {
            $this->sendSurvey($survey);
            $this->info("Weekly survey #{$survey->id} dispatched (day index {$todayIndex})");
        }
    }

    private function processMonthlySurveys(Carbon $today)
    {
        $dayOfMonth = $today->day;

        $surveys = Survey::where('frequency', 'monthly')
            ->where('cron_status', 'active')
            ->get();

        $activeIds   = [];
        $inactiveIds = [];

        foreach ($surveys as $survey) {
            if ($this->matchesMonthlySchedule($survey->custom_frequency, $dayOfMonth)) {
                $activeIds[] = $survey->id;
            } else {
                $inactiveIds[] = $survey->id;
            }
        }

        if (!empty($activeIds)) {
            Survey::whereIn('id', $activeIds)->update([
                'is_completed'   => 0,
                'available_date' => today(),
            ]);
            SurveyUser::whereIn('survey_id', $activeIds)->update(['status' => 'pending']);
        }

        if (!empty($inactiveIds)) {
            Survey::whereIn('id', $inactiveIds)->update(['is_completed' => 1]);
        }

        foreach ($surveys->whereIn('id', $activeIds) as $survey) {
            $this->sendSurvey($survey);
            $this->info("Monthly survey #{$survey->id} dispatched (day {$dayOfMonth})");
        }
    }

    private function processOnceSurveys(Carbon $today)
    {
        $surveys = Survey::where('frequency', 'once')
            ->where('cron_status', 'active')
            ->get();

        $activeIds = $surveys
            ->filter(fn($s) => $s->survey_delivery_date &&
                Carbon::parse($s->survey_delivery_date)->isSameDay($today))
            ->pluck('id')
            ->toArray();

        if (empty($activeIds)) return;

        Survey::whereIn('id', $activeIds)->update([
            'is_completed'   => 0,
            'available_date' => today(),
            'cron_status'    => 'completed',
        ]);

        SurveyUser::whereIn('survey_id', $activeIds)->update(['status' => 'pending']);

        foreach ($surveys->whereIn('id', $activeIds) as $survey) {
            $this->sendSurvey($survey);
            $this->info("Once survey #{$survey->id} dispatched and deactivated");
        }
    }


    private function processCustomFrequencySurveys(Carbon $today)
    {
        $surveys = Survey::where('frequency', 'custom')
            ->where('cron_status', 'active')
            ->get();

        $activeIds   = [];
        $inactiveIds = [];

        foreach ($surveys as $survey) {
            if ($this->shouldSendCustomRecurrenceSurvey($survey, $today)) {
                $activeIds[] = $survey->id;
            } else {
                $inactiveIds[] = $survey->id;
            }
        }

        if (!empty($activeIds)) {
            Survey::whereIn('id', $activeIds)->update([
                'is_completed'   => 0,
                'available_date' => today(),
            ]);
            SurveyUser::whereIn('survey_id', $activeIds)->update(['status' => 'pending']);
        }

        if (!empty($inactiveIds)) {
            Survey::whereIn('id', $inactiveIds)->update(['is_completed' => 1]);
        }

        foreach ($surveys->whereIn('id', $activeIds) as $survey) {
            $this->sendSurvey($survey);
            $this->info("Custom survey #{$survey->id} dispatched");
        }
    }

    // private function processCustomFrequencySurveys(Carbon $today)
    // {
    //     $surveys = Survey::where('frequency', 'custom')
    //         ->where('cron_status', 'active')
    //         ->get();

    //     foreach ($surveys as $survey) {
    //         if ($this->shouldSendCustomRecurrenceSurvey($survey, $today)) {
    //             $survey->update([
    //                 'is_completed' => 0,
    //                 'available_date' => today(),
    //                 ]);
    //             $this->sendSurvey($survey);
    //             $this->info("Custom survey #{$survey->id} dispatched");
    //         } else {
    //             $survey->update(['is_completed' => 1]);
    //         }
    //     }
    // }

  

    private function shouldSendCustomRecurrenceSurvey($survey, Carbon $today): bool
    {
        try {
            $recurrence = is_string($survey->custom_reoccurrence)
                ? json_decode($survey->custom_reoccurrence, true)
                : $survey->custom_reoccurrence;

            if (!$recurrence || !is_array($recurrence)) {
                Log::warning("Invalid custom_reoccurrence for survey #{$survey->id}");
                return false;
            }

            if ($this->hasRecurrenceEnded($survey, $recurrence, $today)) {
                return false;
            }

            $repeat   = $recurrence['repeat'] ?? [];
            $repeatOn = $recurrence['repeat_on'] ?? null;
            $repeatOn = is_array($repeatOn) ? $repeatOn : [];

            $interval = (int) ($repeat['interval'] ?? 1);
            $unit     = strtolower($repeat['unit'] ?? 'day');

            $startDate = $survey->last_sent_at
                ? Carbon::parse($survey->last_sent_at)->startOfDay()
                : Carbon::parse($survey->created_at)->startOfDay();

            return match ($unit) {
                'day'   => $this->checkDailyRecurrence($startDate, $today, $interval),
                'week'  => $this->checkWeeklyRecurrence($startDate, $today, $interval, $repeatOn),
                'month' => $this->checkMonthlyRecurrence($startDate, $today, $interval, $repeatOn),
                'year'  => $this->checkYearlyRecurrence($startDate, $today, $interval),
                default => false,
            };

        } catch (\Exception $e) {
            Log::error("Error processing custom recurrence for survey #{$survey->id}: " . $e->getMessage());
            return false;
        }
    }

    private function hasRecurrenceEnded($survey, array $recurrence, Carbon $today): bool
    {
        $ends = $recurrence['ends'] ?? [];
        $type = $ends['type'] ?? 'never';

        switch ($type) {
            case 'after':
                $maxCount  = (int) ($ends['after'] ?? 0);
                $sendCount = (int) ($survey->send_count ?? 0);
                if ($maxCount > 0 && $sendCount >= $maxCount) {
                    $survey->update(['cron_status' => 'completed']); // ✅ was 'is_completed'
                    Log::info("Survey #{$survey->id} reached max occurrences ({$maxCount})");
                    return true;
                }
                break;

            case 'on':
                if (!empty($ends['on'])) {
                    $endDate = Carbon::parse($ends['on'])->endOfDay();
                    if ($today->greaterThan($endDate)) {
                        $survey->update(['cron_status' => 'completed']);
                        Log::info("Survey #{$survey->id} passed end date ({$ends['on']})");
                        return true;
                    }
                }
                break;

            case 'never':
            default:
                return false;
        }

        return false;
    }

    // -----------------------------------------------------------------------
    // Interval checkers
    // -----------------------------------------------------------------------

    private function checkDailyRecurrence(Carbon $startDate, Carbon $today, int $interval): bool
    {
        $daysDiff = $startDate->diffInDays($today);

        // Fire on day 0 (first run) OR every N days after
        return $daysDiff === 0 || $daysDiff % $interval === 0;
    }

    private function checkWeeklyRecurrence(Carbon $startDate, Carbon $today, int $interval, array $repeatOn): bool
    {
        $weeksDiff = (int) $startDate->diffInWeeks($today);

        if ($weeksDiff % $interval !== 0) {
            return false;
        }

        // repeatOn holds numeric indexes (0=Sun...6=Sat) from your blade
        if (!empty($repeatOn)) {
            return in_array((string) $today->dayOfWeek, array_map('strval', $repeatOn));
        }

        // No specific days: fire on same weekday as start
        return $today->dayOfWeek === $startDate->dayOfWeek;
    }

    private function checkMonthlyRecurrence(Carbon $startDate, Carbon $today, int $interval, array $repeatOn): bool
    {
        $monthsDiff = (int) $startDate->diffInMonths($today);

        if ($monthsDiff % $interval !== 0) {
            return false;
        }

        if (!empty($repeatOn)) {
            return in_array($today->day, array_map('intval', $repeatOn));
        }

        return $today->day === $startDate->day;
    }

    private function checkYearlyRecurrence(Carbon $startDate, Carbon $today, int $interval): bool
    {
        $yearsDiff = (int) $startDate->diffInYears($today);

        return $yearsDiff % $interval === 0
            && $today->month === $startDate->month
            && $today->day   === $startDate->day;
    }

    // -----------------------------------------------------------------------
    // Weekly / Monthly schedule matchers (for non-custom frequencies)
    // -----------------------------------------------------------------------

    private function matchesWeeklySchedule($customFrequency, string $todayIndex): bool
    {
        if (empty($customFrequency)) return false;

        $days = is_string($customFrequency)
            ? json_decode($customFrequency, true)
            : $customFrequency;

        return is_array($days)
            ? in_array($todayIndex, array_map('strval', $days))
            : (string) $customFrequency === $todayIndex;
    }

    private function matchesMonthlySchedule($customFrequency, int $dayOfMonth): bool
    {
        if (empty($customFrequency)) return false;

        $days = is_string($customFrequency)
            ? json_decode($customFrequency, true)
            : $customFrequency;

        return is_array($days)
            ? in_array($dayOfMonth, array_map('intval', $days))
            : (int) $customFrequency === $dayOfMonth;
    }

    // -----------------------------------------------------------------------
    // Sender
    // -----------------------------------------------------------------------
    private function sendSurvey($survey): void
    {
        try {

            $users = $survey->users;

            foreach ($users as $user) {
                $user->notify(new SurveyNotification($survey));
            }

            if ($survey->frequency === 'custom') {
                $survey->increment('send_count');
            }

            $survey->update(['last_sent_at' => now()]);

            $this->info("Notified {$users->count()} user(s) for survey #{$survey->id}");

            Log::info("Survey #{$survey->id} dispatched", [
                'frequency'      => $survey->frequency,
                'notified_users' => $users->count(),
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to dispatch survey #{$survey->id}: " . $e->getMessage());
        }
    }
}