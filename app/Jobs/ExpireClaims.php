<?php

namespace App\Jobs;

use App\Claim;
use App\Application;
use App\Notifications\ClaimExpired;
use Illuminate\Foundation\Bus\DispatchesJobs;

final class ExpireClaims
{
    use DispatchesJobs;

    public function handle()
    {
        Claim::shouldBeExpired()->get()->each(function($claim) {
            $this->expireClaim($claim);
        });
    }

    protected function expireClaim(Claim $claim)
    {
        $app = $claim->application;

        \DB::transaction(function() use ($app, $claim) {
            $app->status = Application::ST_EXPIRED;
            $app->save();

            $claim->status = Claim::ST_EXPIRED;
            $claim->expired_at = now();
            $claim->save();

            $this->dispatchNow(new ReleaseRebates($app));
        }, 5);

        # Don't send out notifications for very old claims
        if ($claim->isNotAncient()) {
            rescue( function () use ($app, $claim) {
                $app->applicant->notify(new ClaimExpired($claim));
            });
        }
    }
}
