<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Aciva tu cuenta";

    protected $userId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.accountactivation')->with([
            'url' => "http://192.168.0.9:8000/api/activateAccount?userid=".$this->userId
        ]);
    }
}
