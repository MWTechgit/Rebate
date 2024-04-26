<?php

namespace App\Mail;

use App\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class ApplicationPendingReviewEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->view('emails.applicant.application.pending_review')
            ->subject('Thank You for your Application')
            ->with([
                'buttonText' => 'Rebate Center',
                'buttonUrl' => route('login'),
                'application' => $this->application,
                'applicant' => $this->application->applicant,
            ])
        ;
    }
}
