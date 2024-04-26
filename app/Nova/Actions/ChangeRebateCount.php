<?php

namespace App\Nova\Actions;

use App\Application;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use App\Jobs\ChangeApplicationRebateCount;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Change the number of rebates applied for on Application
 */
class ChangeRebateCount extends Action
{
    use DispatchesJobs;

    public $onlyOnDetail = true;

    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() > 1) {
            throw new \LogicException("ChangeRebateCount action should only be availabe on detail page");
        }

        /** @var Application */
        $application = $models->first();
        $oldAmount = $application->rebate_count;
        $oldRemaining = $application->rebate->remaining;

        try {
            $this->dispatchNow(new ChangeApplicationRebateCount($application, $fields->new_amount));
        } catch (\Exception $e) {
            return Action::danger($e->getMessage());
        }

        $application->refresh();

        $message = "Application rebate count changed from {$oldAmount} to {$application->rebate_count}<br>";
        $message .= "{$application->rebate->name} remaining changed from {$oldRemaining} to {$application->rebate->remaining}";

        return Action::message($message);
    }

    public function fields()
    {
        return [
            Number::make('New Amount')
                ->rules('required', 'integer'),
        ];
    }
}
