<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;

class ChangeRebateInventory extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $models->each(function ($rebate) use ($fields) {
            // Add the number to the inventory, and also add to the number remaining
            $rebate->inventory += $fields->inventory_change;
            $rebate->remaining += $fields->inventory_change;
            $rebate->save();

            $this->markAsFinished($rebate);
        });

        return Action::message( $fields->inventory_change . ' toilet(s) were added to the rebates.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Number::make('Number of toilets to add', 'inventory_change')
                ->help('Enter the number of toilets to add to this rebate. To subtract, enter a negative number')
        ];
    }
}
