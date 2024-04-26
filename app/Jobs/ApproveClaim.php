<?php

namespace App\Jobs;

use App\Admin;
use App\Claim;
use App\ClaimTransaction;
use App\Events\ClaimWasApproved;
use App\Exceptions\InvalidTransactionAttempt;

final class ApproveClaim {
	private $claim;

	private $admin;

	private $awarded;

	use TransactionAssertions;

	public function __construct(Claim $claim, Admin $admin, float $awarded) {
		$this->claim = $claim;
		$this->admin = $admin;
		$this->awarded = $awarded;
	}

	public function handle(): void{
		$this->assertApplicationApproved();
		$this->assertNoClaimTransaction();
		$this->assertValidAmountAwarded($this->awarded);

		# We do NOT do this. The rebates are already claimed when the application was created
		// if (false === $this->claim->application->rebatesAreClaimable()) {
		//     throw new NotEnoughRebates;
		// }

		\DB::transaction(function () {
			ClaimTransaction::create([
				'admin_id' => $this->admin->id,
				'claim_id' => $this->claim->id,
				'type' => ClaimTransaction::ST_APPROVED,
			]);

			$this->claim->amount_awarded = $this->awarded;
			$this->claim->status = Claim::ST_PENDING_FULFILLMENT;
			$this->claim->save();
		}, 5);

		event(new ClaimWasApproved($this->claim));
	}

	public function claim(): Claim {
		return $this->claim;
	}

	public function action(): string {
		return 'Approve Claim';
	}

	protected function assertValidAmountAwarded(float $amount): void {
		if ($this->claim->maxValue() < $amount) {
			throw new InvalidTransactionAttempt('Approve Claim Failed. Attempted award amount exceeds claim max award amount.');
		}
	}
}
