<?php

namespace App\Notifications;

use App\Application;
use App\Applicant;
use App\Mail\ApplicationWaitListedEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Trigger:
 * Events\ApplicationWasCreated
 *  => Listeners\SendApplicationReceivedNotification
 *
 * Notify the applicant that their application was successfully
 * submitted but it has been put on the waiting list.
 */
final class ApplicationWaitListed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function via(Applicant $applicant)
    {
        return ['mail'];
    }

    public function toMail(Applicant $applicant)
    {
        return (new ApplicationWaitListedEmail($this->application))
            ->to($applicant->email, $applicant->full_name)
        ;
    }

    public function toArray($applicant)
    {
        return [];
    }
}
