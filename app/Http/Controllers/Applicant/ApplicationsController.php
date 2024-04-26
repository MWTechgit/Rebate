<?php

namespace App\Http\Controllers\Applicant;

use App\Application;
use App\Claim;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Applicant\ShowsRebateStatus;

/**
 * Provides application views for authenticated applicant
 */
class ApplicationsController extends Controller
{
    use ShowsRebateStatus;

    public function status(Application $application)
    {
        if ( $application->claim && !$application->claim->isUnclaimed() ) {
            return $this->redirectToClaimStatus( $application->claim );
        }

        $data = [
            'applicant'     => $application->applicant,
            'application'   => $application,
            'claim'         => $application->claim,
            'claimLifetime' => Claim::EXPIRES_IN,
            'partial'       => $this->getStatusView($application),
        ];

        return view('applicant.applications.status', $data);
    }

    public function show(Application $application)
    {
        return view('applicant.applications.show', [
            'application' => $application,
            'applicant'   => $application->applicant,
            'property'    => $application->property,
            'utility'     => $application->property->utilityAccount,
            'reference'   => $application->applicant->reference,
            'rebate'      => $application->rebate,
            'partner'     => $application->rebate->partner,
            'status_url'  => $this->statusRoute($application)
        ]);
    }

    protected function getStatusView(Application $application)
    {
        $prefix = 'applicant.applications.partials.';

        if ($application->isApproved()) {
            return $prefix.'approved';
        }

        if ($application->isDenied() || $application->isExpired()) {
            return $prefix.'denied';
        }

        if ($application->isInReview()) {
            return $prefix.'in_review';
        }

        throw new \RuntimeException('Application status page reached when application not approved, in review, or denied/expired. Application ID: '.$application->id);
    }
}