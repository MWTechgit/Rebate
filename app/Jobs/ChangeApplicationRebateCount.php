<?php

namespace App\Jobs;

use App\Application;
use Illuminate\Support\Facades\DB;
use App\Exceptions\NotEnoughRebates;

/**
 * Change the number of rebates applying for
 */
final class ChangeApplicationRebateCount
{
    protected $application;
    protected $newAmount;

    public function __construct(Application $application, $newAmount)
    {
        $this->application = $application;
        $this->newAmount = $newAmount;
    }

    public function handle(): void
    {
        DB::transaction(function() {
            /** @var \App\Rebate */
            $rebate = $this->application->rebate;

            $oldAmount = $this->application->rebate_count;
            $newAmount = $this->newAmount;
            $diff = $oldAmount - $newAmount;

            $rebate->remaining = $rebate->remaining + $diff;

            if ($rebate->remaining < 0) {
                throw new NotEnoughRebates;
            }

            $rebate->save();

            $this->application->rebate_count = $newAmount;
            $this->application->save();
        }, 5);
    }
}
