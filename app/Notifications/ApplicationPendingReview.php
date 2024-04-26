<?php

namespace App\Notifications;

use App\Application;
use App\Applicant;
use App\Mail\ApplicationPendingReviewEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Trigger:
 * Events\ApplicationWasCreated
 *  => Listeners\SendApplicationReceivedNotification
 *
 * For notifying successful applicants that are NOT wait listed,
 * that their application is pending review.
 */
final class ApplicationPendingReview extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function getApplication(): Application
    {
        return $this->application;
    }

    public function via(Applicant $applicant): array
    {
        return ['mail'];
    }

    public function toMail(Applicant $applicant): ApplicationPendingReviewEmail
    {
        return (new ApplicationPendingReviewEmail($this->application))
            ->to($applicant->email, $applicant->full_name)
        ;
    }

    public function toArray($applicant): array
    {
        return [];
    }
}
