<?php

namespace App\Jobs;

use App\Admin;
use App\Application;
use App\ApplicationTransaction;
use App\Claim;
use App\Events\ApplicationWasApproved;
use App\Exceptions\InvalidTransactionAttempt;

final class ApproveApplication {
	private $application;

	private $admin;

	public function __construct(Application $application, Admin $admin) {
		$this->application = $application;
		$this->admin = $admin;
	}

	public function handle(): void {
		if ($this->application->isApproved()) {
			throw new InvalidTransactionAttempt('The application is already approved');
		}

		# We do NOT do this. The rebates are already claimed when the application was created
		// if (false === $this->application->rebatesAreClaimable()) {
		//     throw new NotEnoughRebates;
		// }

		\DB::transaction(function () {
			ApplicationTransaction::create([
				'admin_id' => $this->admin->id,
				'application_id' => $this->application->id,
				'type' => Application::ST_APPROVED,
			]);

			$this->application->status = Application::ST_APPROVED;
			$this->application->save();

			$claim = Claim::create([
				'application_id' => $this->application->id,
				'applicant_id' => $this->application->applicant_id,
				'fy_year' => $this->application->rebate->fy_year,
				'status' => Claim::ST_UNCLAIMED,
				'submission_type' => 'online',
			]);
		}, 5);

		event(new ApplicationWasApproved($this->application));
	}
}
