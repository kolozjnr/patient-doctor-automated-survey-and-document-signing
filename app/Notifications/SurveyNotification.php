<?php

namespace App\Notifications;

use App\Models\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;

class SurveyNotification extends Notification
{
    use Queueable;

    //protected $survey;

    public function __construct(public Survey $survey) {

    }

    /**
     * Delivery channels
     */
    public function via(object $notifiable): array
    {
        return ['database', 'fcm'];
    }

    /**
     * Data stored in the database
     */
    public function toMail(object $notifiable): MailMessage
    {
        //$firstName = explode(' ', $notifiable->last_name)[0];
        $firstName = $notifiable->last_name;
        $message   = $this->buildMessage($firstName);
        $label     = $this->friendlyFrequency();

        return (new MailMessage)
            ->subject("📋 You have a pending {$label} survey")
            ->greeting("Hi {$firstName}!")
            ->line($message)
            ->action('Take Survey Now', url("/surveys/survey/{$this->survey->id}"))
            ->line('Please complete it at your earliest convenience.')
            ->salutation('Thank you, ECC Team');
    } 

    public function toArray(object $notifiable): array
    {
        $firstName = $notifiable->last_name;

        return [
            'survey_id'   => $this->survey->id,
            'survey_title'=> $this->survey->title,
            'message'     => $this->buildMessage($firstName),
            'frequency'   => $this->survey->frequency,
            'url'         => "/surveys/survey/{$this->survey->id}",
        ];
    }

    public function toFcm($notifiable): ?CloudMessage
    {
        if (empty($notifiable->fcm_token)) {
            return null;
        }

        $firstName = $notifiable->last_name;
        $message   = $this->buildMessage($firstName);

        return CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification(FcmNotification::create(
                title: $this->survey->title,
                body:  $message,
            ))
            ->withAndroidConfig(AndroidConfig::fromArray([
                'priority' => 'high',
                'notification' => [
                    'channel_id' => 'patient_alerts',
                    'notification_priority' => 'PRIORITY_HIGH',
                    'visibility' => 'PUBLIC',
                ],
            ]))
            ->withApnsConfig(ApnsConfig::fromArray([
                'headers' => ['apns-priority' => '10'],
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => '⏰ ' . $this->survey->title,
                            'body'  => $message,
                        ],
                        'sound' => 'default',
                        'interruption-level' => 'time-sensitive',
                    ],
                ],
            ]));
    }


    private function buildMessage(string $firstName): string
    {
        return match ($this->survey->frequency) {
            'daily'   => "Hi {$firstName}, you have a pending daily survey \"{$this->survey->title}\" to attend to.",
            'weekly'  => "Hi {$firstName}, you have a pending weekly survey \"{$this->survey->title}\" to attend to.",
            'monthly' => "Hi {$firstName}, you have a pending monthly survey \"{$this->survey->title}\" to attend to.",
            'once'    => "Hi {$firstName}, you have a one-time survey \"{$this->survey->title}\" waiting for you.",
            'custom'  => "Hi {$firstName}, you have a survey \"{$this->survey->title}\" available for you to complete.",
            default   => "Hi {$firstName}, you have a pending survey \"{$this->survey->title}\" to attend to.",
        };
    }

    private function friendlyFrequency(): string
    {
        return match ($this->survey->frequency) {
            'daily'   => 'daily',
            'weekly'  => 'weekly',
            'monthly' => 'monthly',
            'once'    => 'once',
            'custom'  => '',
            default   => '',
        };
    }
}
