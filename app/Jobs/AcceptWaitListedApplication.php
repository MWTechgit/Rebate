<?php

namespace App\Jobs;

use App\Application;
use App\Events\ApplicationWasAcceptedFromWaitList;
use Illuminate\Foundation\Bus\DispatchesJobs;

final class AcceptWaitListedApplication
{
    use DispatchesJobs;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function handle(): void
    {
        $this->assertWaitListed();

        \DB::transaction(function() {
            $this->application->update([
                'status' => Application::ST_NEW,
                'wait_listed' => false,
            ]);

            $this->dispatchNow(new ClaimRebates($this->application));

            event(new ApplicationWasAcceptedFromWaitList($this->application));
        }, 5);
    }

    protected function assertWaitListed()
    {
        if (false === $this->application->isWaitListed()) {
            $message = 'Attempting to accept non wait listed application';
            throw new \RuntimeException($message);
        }
    }
}
