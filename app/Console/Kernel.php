<?php

namespace App\Console;

use App\Console\Commands\DeleteOldExports;
use App\Console\Commands\FiscalYearRolloverCommand;
use App\Console\Commands\HistoryIndexCommand;
use App\Jobs\ClaimsExpiringSoon;
use App\Jobs\ExpireClaims;
use App\Jobs\UpdateHistory;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel {
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		\App\Console\Commands\HistoryIndexCommand::class,
		\App\Console\Commands\DeleteOldExports::class
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule) {
		if (!config('broward.prune_disabled')) {
			$schedule->command('prune:db')->daily();
		}
		$schedule->job(new ExpireClaims)->twiceDaily();
		$schedule->job(new ClaimsExpiringSoon)->daily();
		$schedule->job(new UpdateHistory)->hourly();
		$schedule->job(new DeleteOldExports)->everyFiveMinutes();

		// Roll over jobs at midnight on Sept 30th
		$schedule->job(new FiscalYearRolloverCommand)
			->dailyAt('23:59')
			->when(function () {
				$now = Carbon::now();
				return $now->month == 9 && $now->day == 30;
			});

		$schedule->job(new HistoryIndexCommand)->twiceDaily();
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands() {
		$this->load(__DIR__ . '/Commands');

		require base_path('routes/console.php');
	}
}
