<?php

namespace App\Jobs;

use App\Application;
use App\Exceptions\NotEnoughRebates;

final class ClaimRebates {
	protected $application;

	public function __construct(Application $application) {
		$this->application = $application;
	}

	public function handle(): void{
		$application = $this->application;

		if (false === $application->shouldClaimRebates()) {
			return;
		}

		if (false === $application->rebatesAreClaimable()) {
			throw new NotEnoughRebates;
		}

		$this->claimApplicationRebates($application);
	}

	/**
	 * "claim" isn't the best word for this but I'm not
	 * sure what else to use.
	 *
	 * When an application is created, we decrease
	 * the number of rebates avaiable (for app.rebate)
	 * until the application is denied (if ever).
	 *
	 * This is more like a "hold" on a rebate
	 * but we don't "hold" rebates in the database,
	 * we decrease the quantity available and then
	 * increase it again if the application gets denied.
	 *
	 * When a claim is denied the application is denied
	 * so we don't have rebate inventory logic in
	 * any claim denial/approval code at all.
	 */
	protected function claimApplicationRebates(Application $application): void{
		$rebate = $application->rebate;
		$rebate->remaining -= $application->rebate_count;
		$rebate->save();
		// \Log::debug('Claimed ' . $application->rebate_count . ' from Rebate ' . $rebate->id . ' for App ' . $application->id . '. ' . $rebate->remaining . ' remaining.');
	}
}
