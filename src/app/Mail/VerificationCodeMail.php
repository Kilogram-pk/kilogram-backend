<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $verification_code;

    /**
     * Create a new message instance.
     *
     * @param string $email
     * @param string $verification_code
     */
    public function __construct(string $email, string $verification_code)
    {
        $this->email = $email;
        $this->verification_code = $verification_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        extract(get_object_vars($this));
        return $this->view('emails.VerificationCodeMail')->with(compact('email', 'verification_code'));
    }
}
