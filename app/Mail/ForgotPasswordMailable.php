<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Codigo de confirmación";

    protected $confirmationCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($confirmationCode)
    {
        $this->confirmationCode = $confirmationCode;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.forgotpassword')->with([
            'confirmationCode' => $this->confirmationCode
        ]);
    }
}
