<?php

// app/Notifications/BellscaleReminderNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BellscaleReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Delivery channels
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data stored in database
     */

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Bellscale Assessment Due',
            'message' => 'It\'s time to complete your monthly Bellscale assessment.',
            //'action_url' => url('/questions/bellscale'),
            'url' => config('app.url') . '/questions/bellscale/',
        ];
    }
}