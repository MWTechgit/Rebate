<?php

namespace App\Console\Commands;

use App\Jobs\ClaimsExpiringSoon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ClaimsExpiringSoonCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'claims:expiring';

    protected $description = 'Claims expiring soon';

    public function handle()
    {
        $this->dispatchNow(new ClaimsExpiringSoon);
    }
}
