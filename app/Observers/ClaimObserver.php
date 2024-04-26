<?php

namespace App\Observers;

use App\Claim;

class ClaimObserver
{
    public function creating(Claim $claim)
    {
        $this->setExpirationDate($claim);
    }

    /**
     * Set the expiration date at the time the claim is created.
     *
     * A claim should only be created after the application is approved.
     */
    protected function setExpirationDate(Claim $claim)
    {
        if ($claim->created_at) {
            $claim->expires_at = $claim->created_at->addDays(45);
        } else {
            $claim->expires_at = now()->addDays(45);
        }
    }
}
