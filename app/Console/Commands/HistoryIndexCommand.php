<?php

namespace App\Console\Commands;

use App\Address;
use App\History;
use App\Jobs\ClaimsExpiringSoon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class HistoryIndexCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'history:index';

    protected $description = 'Build history index';

    public function handle()
    {
        $count = History::whereNull('address_index')->count();
        \Log::notice($count . ' history addresses are null.');

        History::whereNull('address_index')
        ->chunk(100, function ($historys) {
            $historys->each( function ($h) {
                $h->address_index = Address::buildIndex($h);
                $h->save();
            });
            \Log::notice($historys->count() . ' address indices built.');
        });
    }
}
