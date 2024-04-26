<?php

namespace App\Jobs;

use App\Claim;
use App\Events\ClaimExpiringSoon;

final class ClaimsExpiringSoon
{

    public function handle()
    {
        Claim::expiringSoon()->where('expire_notification_sent', false)
            ->get()
            ->each(function($claim) {
            event(new ClaimExpiringSoon($claim));
        });
    }

}
