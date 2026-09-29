<?php

namespace App\Jobs;

use App\Models\BellscaleCycle;
use App\Notifications\BellscaleReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBellscaleReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public BellscaleCycle $cycle) {}

    public function handle(): void
    {
        try {
            $this->cycle->user->notify(new BellscaleReminderNotification());

            $this->cycle->update(['reminder_sent' => true]);

            // create next cycle so the scheduler can pick it up next month
            BellscaleCycle::create([
                'user_id'           => $this->cycle->user_id,
                'last_completed_at' => null,
                'next_due_at'       => now()->addMinutes(3),
                'reminder_sent'     => false,
            ]);
            Log::info('Reminder senrtn');

        } catch (\Exception $e) {
            Log::error('BellscaleReminderJob failed', [
                'cycle_id' => $this->cycle->id,
                'user_id'  => $this->cycle->user_id,
                'error'    => $e->getMessage(),
            ]);

            $this->fail($e);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('BellscaleReminderJob permanently failed', [
            'cycle_id' => $this->cycle->id,
            'user_id'  => $this->cycle->user_id,
            'error'    => $exception->getMessage(),
        ]);
    }
}
