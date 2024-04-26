<?php

namespace App\Nova\Actions;

use App\Claim;
use App\ExportBatch;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ExportPendingClaims extends Action {

	use DispatchesJobs;

    /**
     * The number of models that should be included in each chunk.
     *
     * @var int
     */
    public static $chunkCount = 50000;

	protected $filename;

	public function handle(ActionFields $fields, Collection $claims) {

		$filename = $this->getFilename();
		$claimIds = $claims->pluck('id');

		$batch = ExportBatch::create([
			'admin_id' => auth()->id(),
			'filename' => $filename,
		]);
		$batch->claims()->sync($claimIds);

		Claim::whereIn('id', $claimIds)->update(['status' => Claim::ST_FULFILLED]);

		$url = route('export_batches.download', $batch);

		return Action::openInNewTab($url);
	}

	public function withFilename(string $filename = null) {
		$this->filename = $filename;

		return $this;
	}

	protected function getFilename() {
		return $this->filename;
	}
}