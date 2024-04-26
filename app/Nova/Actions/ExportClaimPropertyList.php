<?php

namespace App\Nova\Actions;

use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ExportClaimPropertyList extends Action {

    /**
     * The number of models that should be included in each chunk.
     *
     * @var int
     */
    public static $chunkCount = 50000;

	public $onlyOnDetail = true;

	public function handle(ActionFields $fields, Collection $models) {
		if ($models->count() > 1) {
			throw new \LogicException("ChangeRebate action should only be available on detail page");
		}

		$batch = $models->first();

		$url = route('export_batches.downloadPropertyList', $batch);

		return Action::openInNewTab($url);
	}
}