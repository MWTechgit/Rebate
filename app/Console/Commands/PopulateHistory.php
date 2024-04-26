<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\ApplicationWasImported;

/**
 * Populate history using all application records.
 *
 * NEVER USE IN PROD! Only for after running seeds local.
 */
final class PopulateHistory extends Command
{
    protected $signature = 'populate:history';

    protected $description = 'Create a history record for every single application record';

    public function handle()
    {
        if (false === app()->environment('local')) {
            throw new \RuntimeException('PopulateHistory Command for local env only!!! Run away! Far away!');
        }

        $apps = \App\Application::withoutGlobalScopes()->get();

        foreach ($apps as $app ) {
            event(new ApplicationWasImported($app));
        }
    }
}
