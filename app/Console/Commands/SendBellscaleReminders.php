<?php
namespace App\Console\Commands;

use App\Jobs\SendBellscaleReminderJob;
use App\Models\BellscaleCycle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendBellscaleReminders extends Command
{
    protected $signature   = 'bellscale:send-reminders';
    protected $description = 'Send reminders to users whose bellscale cycle is due';

    public function handle(): void
    {
        $cycles = BellscaleCycle::query()
            ->where('next_due_at', '<=', now())
            ->where('reminder_sent', false)
            //->whereNull('deleted_at')
            ->with('user')
            ->get();

        if ($cycles->isEmpty()) {
            $this->info('No reminders to send.');
            return;
        }
        Log::info("Found {$cycles->count()} cycles to send reminders for.");

        foreach ($cycles as $cycle) {
            // skip if user doesn't exist or is inactive
            if (!$cycle->user) {
                $this->warn("Skipping cycle {$cycle->id} — user not found or inactive.");
                Log::warning("User {$cycle->user_id} not found or inactive.");
                continue;
            }

            SendBellscaleReminderJob::dispatch($cycle);
            $this->info("Dispatched reminder for user {$cycle->user_id}");
        }

        $this->info("Done. Dispatched {$cycles->count()} reminder(s).");
        Log::info('Sent bellscale reminders.');
    }
}