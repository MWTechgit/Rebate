<?php

namespace App\Console\Commands;

use App\Jobs\YearEndRollover;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class FiscalYearRolloverCommand extends Command {
	use DispatchesJobs;

	protected $signature = 'fy_year:rollover';

	protected $description = 'Rollover pending applications to the next fiscal year';

	public function handle() {

		Log::debug('Dispatching year end rollover script.');
		$this->dispatchNow(new YearEndRollover(Carbon::now()->year));
	}
}
