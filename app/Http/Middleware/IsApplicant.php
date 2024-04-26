<?php

namespace App\Http\Middleware;

use Closure;
use App\Applicant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IsApplicant
{
    public function handle($request, Closure $next)
    {
        $this->assertRouteHasRequiredParams($request);

        $applicant = $request->user();
        $application = $this->getApplication($request);

        if (empty($application) || false === $applicant instanceof Applicant) {
            return redirect()->route('login');
        }

        if ($applicant->id != $application->applicant->id) {
            Auth::logout();
            abort(404);
        }

        return $next($request);
    }

    protected function getApplication($request): Model
    {
        return $request->route('application') ?: $request->route('claim')->application;
    }

    protected function assertRouteHasRequiredParams($request): void
    {
        if (empty($request->route('application')) && empty($request->route('claim'))) {
            # You must typehint an application or claim ID for this middleware to work!
            throw new \LogicException('is_applicant middleware requires either an application or claim route binding');
        }
    }
}
