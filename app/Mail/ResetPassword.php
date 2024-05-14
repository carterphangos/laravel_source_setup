<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    public $expiresAt;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $expiresAt)
    {
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }

    public function build()
    {
        return $this->subject('Password Reset')
            ->view('emails.reset')
            ->with([
                'resetLink' => route('password.create', $this->token),
                'expiresAt' => $this->expiresAt->format('H:i, d M Y'),
            ]);
    }
}
