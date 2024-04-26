<?php

namespace App\Nova\Actions;

use App\ExportBatch;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class TestExport extends Action {

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

		// ONLY TESTING! DON'T MARK THEM
		// Claim::whereIn('id', $claimIds)->update(['status' => Claim::ST_FULFILLED]);

		$url = route('export_batches.downloadAndDelete', $batch);

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