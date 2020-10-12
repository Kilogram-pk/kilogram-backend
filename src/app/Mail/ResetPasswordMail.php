<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable implements ShouldQueue
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
     * @var string
     */
    private $username;

    /**
     * Create a new message instance.
     *
     * @param string $email
     * @param string $verification_code
     * @param string $username
     */
    public function __construct(string $email, string $verification_code, string $username)
    {
        //
        $this->email = $email;
        $this->verification_code = $verification_code;
        $this->username = $username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        extract(get_object_vars($this));
        return $this->view('emails.ResetPasswordMail')->with(compact('email', 'verification_code', 'username'));
    }
}
