<?php

namespace App\Notifications;

use App\Applicant;
use App\Application;
use App\Mail\SpecialAttentionEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

final class SpecialAttention extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via(Applicant $applicant): array
    {
        return ['mail'];
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function toMail(Applicant $applicant): SpecialAttentionEmail
    {
        return (new SpecialAttentionEmail($this->application))
            ->to($applicant->email, $applicant->full_name)
        ;
    }

    public function toArray($applicant): array
    {
        return [];
    }
}
