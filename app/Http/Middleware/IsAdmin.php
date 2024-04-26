<?php

namespace App\Http\Middleware;

use Closure;
use App\Policies\AdminPolicy;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        $admin = \Auth::guard('admin')->user();

        if (empty($admin)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
