<?php

namespace App\Listeners;

use App\Notifications\ApprovedClaim;

final class SendApprovedClaimNotification
{
    public function handle($event): void
    {
        $claim = $event->claim;

        $claim->applicant->notify(new ApprovedClaim($claim));
    }
}
