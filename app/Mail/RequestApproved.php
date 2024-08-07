<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $requestNumber;

    public function __construct($requestNumber)
    {
        $this->requestNumber = $requestNumber;
    }

    public function build()
    {
        return $this->subject('Заявка оброблена')
                    ->from(env('MAIL_FROM_ADDRESS'))
                    ->view('emails.approved')
                    ->with([
                        'requestNumber' => $this->requestNumber,
                    ]);
    }
}
