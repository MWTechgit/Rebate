<?php

namespace App\Jobs;

use App\Admin;
use App\Claim;
use App\ApplicationTransaction;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Denying an application through denying a claim
 * is different than directly denying an application.
 *
 * When an application is denied via denying the claim,
 * we don't send notifications for the application being denied
 * because the claim denial notification will be sent.
 *
 * The logic is also a little different as the application
 * will already have an "approved" transaction before the
 * claim can even exist.
 */
final class DenyClaimApplication
{
    use DispatchesJobs;

    protected $admin;

    protected $claim;

    public function __construct(Claim $claim, Admin $admin)
    {
        $this->claim = $claim;
        $this->admin = $admin;
    }

    public function handle(): void
    {
        \DB::transaction(function() {

            $transaction = $this->claim->application->transaction;
            $transaction->admin_id = $this->admin->id;
            $transaction->type = ApplicationTransaction::ST_DENIED;
            $transaction->description = 'The claim was denied';
            $transaction->extended_description = null;
            $transaction->save();

            $this->dispatchNow(new ReleaseRebates($this->claim->application));
        }, 5);
    }
}
