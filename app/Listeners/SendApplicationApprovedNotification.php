<?php

namespace App\Listeners;

use App\Notifications\ApplicationApproved;

/**
 * Notify the applicant that they are either on the
 * waiting list or their application is pending review.
 */
final class SendApplicationApprovedNotification
{
    public function handle($event): void
    {

        $application = $event->application;

        rescue( function () use ($application) {
            $application->applicant->notify(new ApplicationApproved($application));
        });

    }
}
