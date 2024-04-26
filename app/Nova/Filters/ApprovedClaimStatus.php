<?php

namespace App\Nova\Filters;

use App\Claim;
use Illuminate\Http\Request;

class ApprovedClaimStatus extends ClaimStatus
{

    public function options(Request $request)
    {
        return [
            'Pending Fulfillment' => Claim::ST_PENDING_FULFILLMENT,
            'Approved'            => Claim::ST_PENDING_FULFILLMENT.'|'.Claim::ST_FULFILLED,
            'Fulfilled'           => Claim::ST_FULFILLED
        ];
    }

    /**
     * The default filter to apply
     */
    public function default()
    {
        return Claim::ST_PENDING_FULFILLMENT.'|'.Claim::ST_FULFILLED;
    }
}
