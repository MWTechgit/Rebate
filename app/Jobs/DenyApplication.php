<?php

namespace App\Jobs;

use App\Admin;
use App\Application;
use App\Denial;
use App\ApplicationTransaction as Transaction;
use App\Exceptions\InvalidTransactionAttempt;
use App\Events\ApplicationWasDenied;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Used for directly denying an application,
 * NOT for denying an application via denying a claim
 */
final class DenyApplication
{
    use DispatchesJobs;

    private $application;

    private $admin;

    private $denial;

    public function __construct(Application $application, Admin $admin, Denial $denial = null)
    {
        $this->application = $application;
        $this->admin = $admin;
        $this->denial = $denial ?? new Denial;
    }

    public function handle(): void
    {
        $app = $this->application;

        if ($app->hasTransaction()) {
            $message = "The application already has a transaction of type '{$app->transaction->type}'";
            throw new InvalidTransactionAttempt($message);
        }

        \DB::transaction(function() use ($app) {
            Transaction::create([
                'admin_id'             => $this->admin->id,
                'application_id'       => $app->id,
                'type'                 => Transaction::ST_DENIED,
                'description'          => $this->denial->reason(),
                'extended_description' => $this->denial->moreInfo(),
            ]);

            $app->status = Application::ST_DENIED;
            $app->save();

            $this->dispatchNow(new ReleaseRebates($app));
        }, 5);

        event(new ApplicationWasDenied($app));
    }
}
