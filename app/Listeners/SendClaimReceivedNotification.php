<?php

namespace App\Listeners;

use App\Admin;
use App\Notifications\ClaimReceived;
use App\Notifications\StaffClaimReceived;
use Illuminate\Support\Facades\Notification;

final class SendClaimReceivedNotification
{
    public function handle($event): void
    {

        rescue( function () use ($event) {

            $applicant = $event->claim->applicant;

            $applicant->notify(new ClaimReceived($event->claim));

            Notification::send( Admin::whereReceivesAlerts()->get(), new StaffClaimReceived($event->claim) );
        });
    }
}
