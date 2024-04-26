<?php

namespace App\Listeners;

use App\Notifications\ClaimExpiring;
use App\Notifications\SpecialAttention;
use Illuminate\Notifications\Events\NotificationSent;

/**
 * Set application.notification_sent to true
 * if special attention notification was sent.
 */
final class NotificationSentHandler
{
    public function handle(NotificationSent $event): void
    {
        $notification = $event->notification;

        if ($notification instanceof SpecialAttention) {
            $application = $notification->getApplication();
            $application->update([
                'notification_sent' => true,
                'notification_sent_at' => now(),
                # Laravel Nova is auto setting this to 'finished' after this
                # operation runs so it actually ends up being set to 'finished'
                'notification_status' => null,
            ]);
        }

        if ($notification instanceof ClaimExpiring) {
            $claim = $notification->getClaim();
            $claim->update([
                'expire_notification_sent' => true
            ]);
        }
    }
}