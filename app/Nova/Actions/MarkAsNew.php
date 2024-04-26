<?php

namespace App\Nova\Actions;

use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use App\Application;

class MarkAsNew extends Action
{
    public $onlyOnIndex = true;

    public function handle(ActionFields $fields, Collection $models)
    {
        $updated = 0;
        foreach ($models as $application) {
            if ($application->isPendingReview()) {
                $application->update(['status' => Application::ST_NEW]);
                $updated++;
            }
        }

        return Action::message("$updated applications marked as new");
    }

    public function fields()
    {
        return [];
    }
}
