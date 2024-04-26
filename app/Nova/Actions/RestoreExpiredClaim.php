<?php

namespace App\Nova\Actions;

use App\Application;
use App\Jobs\RestoreExpiredClaim as RestoreExpiredClaimJob;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Foundation\Bus\DispatchesJobs;

class RestoreExpiredClaim extends Action
{
    use DispatchesJobs;

    public function handle(ActionFields $fields, Collection $claims)
    {
        $successCount = 0;
        $failCount = 0;

        foreach ($claims as $claim) {
            try {
                $this->dispatchNow(new RestoreExpiredClaimJob($claim));
                $successCount++;
            } catch (\Exception $e) {
                $failCount++;
            }
        }

        return Action::message("$successCount claims restored! $failCount claims ignored!");
    }

    public function fields()
    {
        return [];
    }
}
