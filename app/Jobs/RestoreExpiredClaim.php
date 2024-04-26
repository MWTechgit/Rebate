<?php

namespace App\Jobs;

use App\Application;
use App\Claim;
use App\Jobs\ClaimRebates;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;

final class RestoreExpiredClaim
{
    use DispatchesJobs;

    protected $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function handle(): void
    {
        $application = $this->claim->application;

        if (false === $this->claim->isExpired()) {
            throw new \RuntimeException("Can't restore non-expired claim");
        }

        \DB::transaction(function() use ($application) {
            $application->status = Application::ST_APPROVED;
            $application->save();

            $this->claim->deleteTransaction();
            $this->claim->status = Claim::ST_PENDING_REVIEW;
            $this->claim->expired_at = null;
            $this->claim->expires_at = Carbon::tomorrow();
            $this->claim->save();

            $this->dispatchNow(new ClaimRebates($application->refresh()));
        }, 5);
    }
}
