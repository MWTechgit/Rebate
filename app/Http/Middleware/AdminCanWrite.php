<?php

namespace App\Http\Middleware;

use App\Admin;
use App\Policies\AdminPolicy;
use Closure;

class AdminCanWrite
{
    public function handle($request, Closure $next)
    {
        $admin = \Auth::guard('admin')->user();

        if (empty($admin) || false === $admin->canWrite()) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
