<?php

namespace App\Nova\Actions;

use App\Application;
use App\Notifications\SpecialAttention;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class SendSpecialAttentionNotification extends Action
{
    public function handle(ActionFields $fields, Collection $models)
    {
        $numSent = 0;
        foreach ($models as $application) {
            if ($application->isSpecialAttention()) {
                $application->update(['notification_status' => 'queued for sending']);
                $application->applicant->notify(new SpecialAttention($application));
                $numSent++;
            }
        }

        return Action::message("$numSent special attention notification queued for delivery!");
    }

    public function fields()
    {
        return [];
    }
}
