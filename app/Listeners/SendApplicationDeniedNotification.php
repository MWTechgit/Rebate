<?php

namespace App\Listeners;

use App\Notifications\ApplicationDenied;

/**
 * Notify the applicant that they are either on the
 * waiting list or their application is pending review.
 */
final class SendApplicationDeniedNotification
{
    public function handle($event): void
    {

        $application = $event->application;

        $application->applicant->notify(new ApplicationDenied($application));

    }
}
