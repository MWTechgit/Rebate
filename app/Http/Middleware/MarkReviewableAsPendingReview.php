<?php

namespace App\Http\Middleware;

use App\Application;
use App\Claim;
use App\Jobs\MarkPendingReview;
use Closure;

/**
 * Applications and Claims can be reviewed.
 *
 * If the admin views the "show/detail" page of the resource in nova
 * and the status is "new", it should be marked as "pending-review".
 *
 * Most times a nova request is not a real page request. It's a Vue router
 * request (single page app style request). So we have to intercept the nova
 * get requests for the resource detail and the actual page request.
 */
class MarkReviewableAsPendingReview
{
    public function handle($request, Closure $next)
    {
        $novaPrefix = trim(config('nova.path'), '/');

        $claimDetail = collect(["$novaPrefix/resources/claims/*", 'nova-api/claims/*'])->contains(function($uri) use ($request) {
            return $request->is($uri);
        });

        $appDetail = collect(["$novaPrefix/resources/applications/*", 'nova-api/applications/*'])->contains(function($uri) use ($request) {
            return $request->is($uri);
        });

        if (false == $claimDetail && false == $appDetail) {
            return $next($request);
        }

        $modelSegment = $request->is('nova-api/*') ? 2 : 3;
        $idSegment = $request->is('nova-api/*') ? 3 : 4;

        $id    = $request->segment($idSegment);
        $type  = $request->segment($modelSegment);
        $model = 'claims' == $type ? Claim::class : Application::class;

        MarkPendingReview::dispatch($model,$id);

        return $next($request);
    }
}
