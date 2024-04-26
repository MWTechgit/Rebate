<?php

namespace App\Listeners;

use App\Admin;
use App\Notifications\ApplicationPendingReview;
use App\Notifications\ApplicationWaitListed;
use App\Notifications\StaffApplicationReceived;
use Illuminate\Support\Facades\Notification;

/**
 * Notify the applicant that they are either on the
 * waiting list or their application is pending review.
 */
final class SendApplicationReceivedNotification
{
    public function handle($event): void
    {
        $application = $event->application;
        $applicant = $application->applicant;

        rescue( function () use ($application, $applicant) {

            if ($application->isWaitListed()) {
                $applicant->notify(new ApplicationWaitListed($application));
            } else {
                $applicant->notify(new ApplicationPendingReview($application));
            }
        });

        Notification::send( Admin::whereReceivesAlerts()->get(), new StaffApplicationReceived($application) );

    }
}
