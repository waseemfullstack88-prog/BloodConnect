<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $otp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $otp)
    {
        $this->name = $name;
        $this->otp = $otp;
    }

    /**
     * Build the message.
     * Anti-spam headers are added to improve deliverability.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to BloodConnect - Verify Your Email')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->replyTo(config('mail.from.address'), config('mail.from.name'))
                    ->withSymfonyMessage(function ($message) {
                        $message->getHeaders()->addTextHeader('X-Priority', '1');
                    })
                    ->view('emails.registration_otp')
                    ->text('emails.registration_otp_plain');
    }
}
