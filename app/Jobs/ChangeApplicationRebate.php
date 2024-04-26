<?php

namespace App\Jobs;

use App\Application;
use App\Rebate;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Available only on Claims.
 *
 * Assigns the claim.application.rebate to the provided rebate.
 */
final class ChangeApplicationRebate {
	use DispatchesJobs;

	protected $application;

	protected $rebate;

	public function __construct(Application $application, Rebate $rebate) {
		$this->application = $application;
		$this->rebate = $rebate;
	}

	public function handle(): void {
		\DB::transaction(function () {
			$this->dispatchNow(new ReleaseRebates($this->application));

			$this->application->rebate_id   = $this->rebate->id;
			$this->application->fy_year     = $this->rebate->fy_year;

			if ($this->application->rebate->partner_id !== $this->rebate->partner_id)
			{
				$this->application->rebate_code = Application::generateUniqueCode($this->rebate->partner, $this->application->created_at );
			}

			$this->application->save();

			if ($this->application->claim) {
				$this->application->claim->fy_year = $this->rebate->fy_year;
				$this->application->claim->save();
			}

			$this->dispatchNow(new ClaimRebates($this->application->refresh()));
		}, 5);
	}
}
