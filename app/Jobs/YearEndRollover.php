<?php

namespace App\Jobs;

use App\Application;
use App\Rebate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Mail;

final class YearEndRollover {

	use DispatchesJobs;

	protected $current_fiscal_year;
	protected $next_year_rebates;
	protected $count = 0;

	public function __construct($current_fiscal_year) {
		$this->current_fiscal_year = $current_fiscal_year;
	}

	public function handle(): void
	{
		$this->getApplications()
			->each(function ($application) {
				# Loop through all new and pending applications
				if (($newRebate = $this->newRebateFor($application))) {
					# Get next year's rebate equivalent
					try {
						$this->dispatchNow(new ChangeApplicationRebate($application, $newRebate));
						$this->count++;
					} catch (\Exception $e) {
						\Log::warning('Problem with rollover on application ' . $application->id . ':', ['exception' => $e]);
					}
				} else {
					# Else, no rebate was found. No rollover.
					\Log::warning('No rebate was found for the rollover for rebate id ' . $application->rebate_id);
				}
			});

		\Log::info('Rollover changed ' . $this->count . ' records.');


		Mail::raw('Browards year rollover is finished.', function ($message) {
			$message->to('erinlambro@gmail.com');
			$message->subject('Browards Rollover ' . $this->current_fiscal_year . ' to ' . ($this->current_fiscal_year + 1));
		});
	}

	private function getApplications(): Collection
	{
		return Application::with('rebate')  # Eager load the rebate

			->where( function ($query) {
				$query->pending();
			})
			->orWhere( function ($query) {
				$query->hasPendingClaim();
			})

			->get();
	}

	/**
	 * Get the Rebate that should be used for the upcoming year
	 * @param  Application $application [description]
	 * @return Rebate
	 */
	private function newRebateFor(Application $application) {
		if (is_null($this->next_year_rebates)) {
			$this->next_year_rebates = Rebate::where('fy_year', $this->current_fiscal_year + 1)->get();
		}

		return $this->next_year_rebates
			->where('partner_id', $application->rebate->partner_id)
			->where('remaining', '>', $application->rebate_count)
			->first();
	}

}
