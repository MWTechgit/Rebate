<?php

namespace App\Jobs;

use App\ExportBatch;
use Illuminate\Support\Collection;

/**
 * Available only on Claims.
 *
 * Assigns the claim.application.rebate to the provided rebate.
 */
final class CreateExportBatch
{

    protected $claimIds;

    protected $url;

    protected $adminId;

    protected $filename;

    public function __construct($claimIds = [], $url = '', $filename = '')
    {
        $this->claimIds   = $claimIds;
        $this->url      = $url;
        $this->adminId  = auth()->id();
        $this->filename = $filename;
    }

    public function handle(): void
    {
        $batch = ExportBatch::create([
            'url' => $this->url,
            'admin_id' => $this->adminId,
            'filename' => $this->filename,
        ]);

        $batch->claims()->sync($this->claimIds);

    }
}
