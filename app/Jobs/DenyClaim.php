<?php

namespace App\Jobs;

use App\Admin;
use App\Claim;
use App\Denial;
use App\ClaimTransaction;
use App\Events\ClaimWasDenied;
use App\Jobs\DenyClaimApplication;
use Illuminate\Foundation\Bus\DispatchesJobs;

final class DenyClaim
{
    use DispatchesJobs;

    protected $claim;

    protected $admin;

    protected $denial;

    use TransactionAssertions;

    public function __construct(Claim $claim, Admin $admin, Denial $denial)
    {
        $this->claim = $claim;
        $this->admin = $admin;
        $this->denial = $denial;
    }

    public function handle(): void
    {
        # The claim should not already be approved or denied
        $this->assertNoClaimTransaction();

        # You can't deny claims for application that aren't approved.
        # Claims should not exist for unapproved apps.
        $this->assertApplicationApproved();

        \DB::transaction(function() {
            ClaimTransaction::create([
                'admin_id'    => $this->admin->id,
                'claim_id'    => $this->claim->id,
                'type'        => Claim::ST_DENIED,
                'description' => $this->denial->reason(),
            ]);

            $this->claim->status = 'denied';
            $this->claim->save();

            $this->dispatchNow(new DenyClaimApplication($this->claim->refresh(), $this->admin));
        }, 5);

        event(new ClaimWasDenied($this->claim, $this->admin));
    }

    public function claim(): Claim
    {
        return $this->claim;
    }

    public function action(): string
    {
        return 'Deny Claim';
    }
}
