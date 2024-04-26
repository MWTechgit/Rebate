<?php

namespace App\Mail;

use App\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class ApplicationApprovedEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->view('emails.applicant.application.approved')
            ->subject('Your Application Was Approved')
            ->with([
                'application'       => $this->application,
                'applicant'         => $this->application->applicant,
                'claim'             => $this->application->claim,
                'rebate_value'      => $this->rebateValue(),
                'rebate_center_url' => route('login'),
                'contact'           => (object) config('broward.contact'),
                'buttonText'        => 'Rebate Center',
                'buttonUrl'         => route('login'),
            ])
        ;
    }

    protected function rebateValue()
    {
        $value = number_format($this->application->rebate->value, 2);
        return money_format('$%n', (float) $value); 
    }
}
