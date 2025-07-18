<?php

namespace App\Mail\Auth;

use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public OneTimePassword $otp)
    {
    }

    public function envelope(): Envelope
    {
        $subject = config('app.name').$this->otp->type === 'email_verification' ? ' - Email Verification' : ' - Password Reset';
        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
