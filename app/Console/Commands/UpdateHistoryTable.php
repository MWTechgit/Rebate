<?php

namespace App\Console\Commands;

use App\Jobs\UpdateHistory;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateHistoryTable extends Command
{
    use DispatchesJobs;

    protected $signature = 'update:history';

    protected $description = 'Update history table';

    public function handle()
    {
        $this->dispatchNow(new UpdateHistory(50));
    }
}
