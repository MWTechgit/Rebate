<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
{
    public function handle($request, Closure $next)
    {
        $user = \Auth::guard('admin')->user();

        if (empty($user)) {
            abort(404);
        }

        if (!$user->isSuperAdmin()) {
            abort(404);
        }

        return $next($request);
    }
}
