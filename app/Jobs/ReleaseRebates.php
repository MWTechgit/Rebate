<?php

namespace App\Jobs;

use App\Application;
use Illuminate\Foundation\Bus\Dispatchable;

final class ReleaseRebates {

	use Dispatchable;

	protected $application;

	public function __construct(Application $application) {
		$this->application = $application;
	}

	public function handle(): void{
		$application = $this->application;

		$rebate = $application->rebate;
		$rebate->remaining += $application->rebate_count;
		$rebate->save();

		// \Log::debug('Released ' . $application->rebate_count . ' from Rebate ' . $rebate->id . ' for App ' . $application->id . '. ' . $rebate->remaining . ' remaining.');
	}
}
