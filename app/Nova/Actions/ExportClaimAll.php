<?php

namespace App\Nova\Actions;

use App\Nova\Actions\ExportAll;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Collection;

class ExportClaimAll extends ExportAll
{

    /**
     * The number of models that should be included in each chunk.
     *
     * @var int
     */
    public static $chunkCount = 50000;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $models->load('application');
        $applications = $models->map(function ($claim) {
            return $claim->application;
        });
        return parent::handle($fields,$applications);
    }
}
