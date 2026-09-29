<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
// use Illuminate\Contracts\Queue\ShouldQueue;

class AdminConsentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $filePath;
    public $fileName;

    public function __construct($patient, $filePath, $fileName)
    {
        $this->patient = $patient;
        $this->filePath = $filePath;
        $this->fileName = $fileName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Patient Consent Signed - ' . ($this->patient->patient_id ?? 'Unknown'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-consent-notification',
        );
    }

    public function attachments(): array
    {
        if (!file_exists($this->filePath)) {
            \Log::error('Attachment file not found', ['path' => $this->filePath]);
            return [];
        }

        return [
            Attachment::fromPath($this->filePath)
                ->as($this->fileName)
                ->withMime('application/pdf'),
        ];
    }
}