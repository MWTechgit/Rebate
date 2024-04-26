<?php

namespace App\Mail;

use App\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class SpecialAttentionEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->view('emails.applicant.application.special_attention')
            ->subject('Reminder: Your Rebate Application')
            ->with([
                'application' => $this->application,
                'applicant' => $this->application->applicant,
                'terms_url'         => config('broward.t&c')
            ])
        ;
    }
}
