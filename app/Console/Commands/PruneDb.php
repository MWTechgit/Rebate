<?php

namespace App\Console\Commands;

use App\Application;
use Illuminate\Console\Command;

/**
 * Deletes old applications and related models.
 *
 * There is no need to keep this data as it is stored
 * in the history table.
 *
 * @see \App\Listeners\WriteApplicationToHistory
 */
final class PruneDb extends Command
{
    protected $signature = 'prune:db';

    protected $description = 'Deletes old applications and related models';

    public function handle()
    {
        $subYears = config('broward.years_to_store_full_applications');

        $cutOff = now()->subYears($subYears);

        $count = Application::where('created_at', '<', $cutOff)->delete();

        $message = "Pruned $count Applications";

        $this->info($message);

        \Log::info($message);
    }
}
