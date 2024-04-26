<?php

namespace App\Http\Controllers;

use App\Application;
use App\Claim;
use App\Http\Controllers\Controller;
use App\Mail\ApplicationPendingReviewEmail;
use App\Mail\ApplicationWaitListedEmail;
use App\Mail\ApprovedClaimEmail;
use App\Mail\DeniedClaimEmail;
use App\Mail\SpecialAttentionEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class DevController extends Controller
{

    public function pending()
    {
        if ( app()->environment('production') ) {
            abort(404);
        }

        $application = Application::first() ?: abort(404);
        return new ApplicationPendingReviewEmail($application);
    }

    public function waitlist()
    {
        if ( app()->environment('production') ) {
            abort(404);
        }

        $application = Application::first() ?: abort(404);
        return new ApplicationWaitListedEmail($application);
    }

    public function special()
    {
        if ( app()->environment('production') ) {
            abort(404);
        }

        $application = Application::first() ?: abort(404);
        return new SpecialAttentionEmail($application);
    }

    public function approved()
    {
        if ( app()->environment('production') ) {
            abort(404);
        }

        $claim = Claim::first() ?: abort(404);
        return new ApprovedClaimEmail($claim);
    }

    public function denied()
    {
        if ( app()->environment('production') ) {
            abort(404);
        }

        $claim = Claim::first() ?: abort(404);
        return new DeniedClaimEmail($claim);
    }

    public function adminAs($status)
    {
        if ( app()->environment('production') ) {
            abort(404);
        }

        $app = Application::where('status', $status)
            ->whereHas('claim', function ($query) {
                $query->where('status', Claim::ST_UNCLAIMED);
            })
            ->first()

            ?? Application::where('status', $status)->first()
            ?? abort(404);

        $applicant = $app->applicant;

        Auth::guard('applicant')->login($applicant);

        return redirect()->route('applications.status', [$applicant->application]);
    }

}
