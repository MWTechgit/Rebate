<?php

namespace App;

use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Transactions represent an approval or denial
 * of a Claim or an Application. Claims have their
 * own transaction table as do applications.
 *
 * We need to know who approved or denied
 * and why the claim was denied if it was.
 *
 * When a claim is approved, the status on
 * the claim should be set to pending-fulfillment.
 */
class ClaimTransaction extends Transaction
{
    use Cacheable;
    
    /**
     * Claim has one transaction
     */
    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }
}
