<?php

namespace App\Console\Commands;

use App\Jobs\ExpireClaims;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ExpireClaimsCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'claims:expire';

    protected $description = 'Expire claims';

    public function handle()
    {
        $this->dispatchNow(new ExpireClaims);
    }
}
