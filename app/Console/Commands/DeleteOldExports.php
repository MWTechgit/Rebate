<?php

namespace App\Console\Commands;

use App\Jobs\ClaimsExpiringSoon;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DeleteOldExports extends Command
{
    protected $signature = 'exports:clean';

    protected $description = 'Delete old exported files';

    public function handle()
    {
        $files = collect(Storage::disk('exports')->listContents());

        $count = $files->count();

        $deleted = 0;

        $files->each(function($file) use (&$deleted) {

			$time = Carbon::createFromTimestamp($file['timestamp']);

			if ($file['type'] == 'file' && $time->addMinutes(5)->isPast()) {
				if (Storage::disk('exports')->delete($file['path'])) {
					$deleted++;
				}
			}
		});

        echo \Log::notice('Deleted ' . $deleted . ' of ' . $count . ' files.');
    }
}
