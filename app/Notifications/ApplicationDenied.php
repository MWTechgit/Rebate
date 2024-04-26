<?php

namespace App\Notifications;

use App\Applicant;
use App\Application;
use App\Mail\ApplicationDeniedEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicationDenied extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(Applicant $applicant)
    {
        return (new ApplicationDeniedEmail($this->application))
            ->to($applicant->email, $applicant->full_name);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
