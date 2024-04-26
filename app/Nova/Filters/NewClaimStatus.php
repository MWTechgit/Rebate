<?php

namespace App\Nova\Filters;

use App\Claim;
use Illuminate\Http\Request;

class NewClaimStatus extends ClaimStatus
{
    public function options(Request $request)
    {
        return [
            'New & Pending'       => Claim::ST_NEW.'|'.Claim::ST_PENDING_REVIEW,
            'New'                 => Claim::ST_NEW,
            'Pending Review'      => Claim::ST_PENDING_REVIEW
        ];
    }

    /**
     * The default filter to apply
     */
    public function default()
    {
        return Claim::ST_NEW.'|'.Claim::ST_PENDING_REVIEW;
    }
}
