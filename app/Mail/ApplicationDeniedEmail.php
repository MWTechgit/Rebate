<?php

namespace App\Mail;

use App\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class ApplicationDeniedEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->view('emails.applicant.application.denied')
            ->subject('Your Application was Denied')
            ->with([
                'name'   => $this->application->applicant->full_name,
                'reason' => $this->application->transaction->reason(),
            ])
        ;
    }
}
