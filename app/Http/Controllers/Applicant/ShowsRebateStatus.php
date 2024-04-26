<?php

namespace App\Http\Controllers\Applicant;

use App\Claim;
use App\Application;

trait ShowsRebateStatus
{
    protected function redirectToClaimStatus(Claim $claim)
    {
        return redirect()->route('claims.status', ['claim' => $claim->id ]);
    }

    protected function redirectToApplicationStatus(Application $application)
    {
        return redirect()->route('applications.status', ['application' => $application->id ]);
    }

    protected function statusRoute(Application $application)
    {
        if ( !$application->claim || $application->claim->isUnclaimed()) {
            return route('applications.status', ['application' => $application->id ]);
        }

        return route('claims.status', ['claim' => $application->claim->id ]);
    }
}