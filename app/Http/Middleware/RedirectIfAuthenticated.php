<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();

            if ($user instanceof \App\Admin) {
                return redirect()->route('admin/resources/applications');
            }

            if ($user instanceof \App\Applicant) {
                return redirect()->route('applications.status', [
                    'application' => $user->application->id
                ]);
            }

            throw new \RuntimeException('Auth user authenticated without configured guard');
        }

        return $next($request);
    }
}
