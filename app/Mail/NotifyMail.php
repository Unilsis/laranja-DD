<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyMail extends Mailable
{
    use Queueable, SerializesModels;
    public $custumer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($custumer)
    {
        $this->custumer = $custumer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       $nome = $this->custumer;
       return $this->from('commercial@orangebank.ao', 'Domingos Dias')
                   ->replyTo('commercial@orangebank.ao', 'Domingos Dias')
                   ->subject ('Welcome Orange Bank')
                   ->view('mails.welcome', compact('nome'));
    }
}
