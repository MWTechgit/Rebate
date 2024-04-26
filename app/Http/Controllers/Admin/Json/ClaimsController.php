<?php

namespace App\Http\Controllers\Admin\Json;

use App\Claim;
use App\Http\Controllers\Controller;

class ClaimsController extends Controller
{
    /**
     * The maximum dollar amount the claim can be awarded
     */
    public function awardable(Claim $claim)
    {
        $amount = $claim->rebate()->value;

        return [
            'awardable'         => $amount * $claim->application->rebate_count,
            'rebates_requested' => $claim->application->rebate_count,
            'rebate_max_value'  => $amount,
        ];
    }
}
