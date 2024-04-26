<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class BalanceRebateInventory extends Action {
	use InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Perform the action on the given models.
	 *
	 * @param  \Laravel\Nova\Fields\ActionFields  $fields
	 * @param  \Illuminate\Support\Collection  $models
	 * @return mixed
	 */
	public function handle(ActionFields $fields, Collection $models) {

		$balanced = 0;
		$models->each(function ($rebate) use (&$balanced) {

			$used_should_be = $rebate->totalToiletsInApplications();

			$rebate->remaining = $rebate->inventory - $used_should_be;
			if (!$rebate->isDirty('remaining')) {
				$this->markAsFailed($rebate, 'Rebate ' . $rebate->id . ' is already balanced.');
			} else {

				if ($rebate->remaining < 0) {
					$rebate->remaining = 0; // Make sure no one else applies here. It will still be unbalanced
					$this->markAsFailed($rebate, 'Rebate ' . $rebate->id . ' breached');
				} else {
					$balanced++;
					$this->markAsFinished($rebate);
				}

				$rebate->save();
			}
		});

		return Action::message($balanced . ' of ' . $models->count() . ' rebate(s) were balanced.');
	}

	/**
	 * Get the fields available on the action.
	 *
	 * @return array
	 */
	public function fields() {
		return [];
	}
}
