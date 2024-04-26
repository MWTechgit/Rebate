<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\Select;

/**
 * Only to be used for Wait Listed Applications
 */
class SetStatus extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function handle(ActionFields $fields, Collection $models)
    {
        $count = 0;
        foreach ($models as $application) {
            if ($application->isWaitListed()) {
                $application->status = $fields->status;
                $application->save();
                $count++;
            }
        }

        return Action::message("$count applications updated!");
    }

    public function fields()
    {
        return [
            Select::make('Status')->options([
                'Called' => 'Contacted by Phone',
                'E-Mailed' => 'Contacted by E-Mail',
                'Follow Up' => 'Follow Up',
            ])->rules('required', 'string')
        ];
    }
}
