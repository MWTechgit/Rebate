<?php

namespace App\Nova\Actions;

use App\Jobs\ChangeApplicationRebate;
use App\Rebate;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

/**
 * Available only on Claims
 *
 * Assigns the claim.application.rebate to the provided rebate
 */
class ChangeRebate extends Action {
	use DispatchesJobs;

	public $onlyOnDetail = true;

	public function handle(ActionFields $fields, Collection $models) {
		if ($models->count() > 1) {
			throw new \LogicException("ChangeRebate action should only be available on detail page");
		}

		$application = $models->first()->getApplication();

		$newRebate = Rebate::find($fields->rebate);

		if (empty($newRebate)) {
			return Action::danger('The selected rebate could not be found');
		}

		try {
			$this->dispatchNow(new ChangeApplicationRebate($application, $newRebate));
		} catch (\Exception $e) {
			return Action::danger($e->getMessage());
		}

		return Action::message('Claim successfully assigned to ' . $newRebate->partnerYear());
	}

	public function fields() {
		$rebates = Rebate::where('fy_year', '>=', fiscal_year() - 1)
			->where('remaining', '>', 0)
			->get()
		;

		$options = [];
		foreach ($rebates as $rebate) {
			$options[$rebate->id] = $rebate->partner->account_key . ' - ' . $rebate->fy_year;
		}

		return [
			Select::make('Rebate')
				->options($options)
				->rules('required', 'string'),
		];
	}
}
