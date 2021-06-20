<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LogInMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Inicio de sesion";

    protected $idUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = env('SECURITY_CLOSE');
        return $this->view('mails.login')->with([
            'url' => $url,
            'idUser' => $this->idUser
        ]);
    }
}
