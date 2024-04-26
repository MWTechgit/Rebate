<?php

namespace App\Listeners;

use App\Notifications\DeniedClaim;

final class SendDeniedClaimNotification
{
    public function handle($event): void
    {
        $applicant = $event->claim->applicant;
        $applicant->notify(new DeniedClaim($event->claim));
    }
}
