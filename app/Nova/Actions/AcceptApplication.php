<?php

namespace App\Nova\Actions;

use App\Jobs\AcceptWaitListedApplication;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

/**
 * Accept a wait listed application to applications
 */
class AcceptApplication extends Action
{
    use DispatchesJobs;

    public function handle(ActionFields $fields, Collection $applications)
    {
        $numAccepted = 0;

        foreach ($applications as $app) {
            try {
                $this->dispatchNow(new AcceptWaitListedApplication($app));
                $numAccepted++;
            } catch (\Exception $e) {
                // pass
            }
        }

        $message = "$numAccepted of {$applications->count()} applications accepted";

        if (0 == $numAccepted) {
            return Action::danger($message);
        }

        return Action::message($message);
    }

    public function fields()
    {
        return [];
    }
}
