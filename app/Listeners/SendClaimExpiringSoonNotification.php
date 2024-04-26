<?php

namespace App\Listeners;

use App\Notifications\ClaimExpiring;

final class SendClaimExpiringSoonNotification
{
    public function handle($event): void
    {
    	if ($event->claim->isNotAncient()) {
	        $applicant = $event->claim->applicant;
	        $applicant->notify(new ClaimExpiring($event->claim));
    	}
    }
}
