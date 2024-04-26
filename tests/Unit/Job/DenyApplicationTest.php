<?php

namespace Tests\Unit\Job;

use App\Admin;
use App\Application;
use App\Jobs\DenyApplication;
use App\Denial;
use App\Events\ApplicationWasDenied;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DenyApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_denies_an_application()
    {
        \Event::fake();

        $admin = factory(Admin::class)->create();

        $application = factory(Application::class)->create([
            'status' => Application::ST_PENDING_REVIEW,
            'admin_id' => $admin->id
        ]);

        $job = new DenyApplication($application, $admin);

        $this->dispatch($job);

        $application->refresh();

        $this->assertTrue($application->isDenied());
        $this->assertEquals(Application::ST_DENIED, $application->status);
        $this->assertEquals($admin->id, $application->transactionBy()->id);

        \Event::assertDispatched(ApplicationWasDenied::class);
    }

    /** @test */
    function it_makes_rebates_requested_available_again()
    {
        $application = factory(Application::class)->create([
            'status' => Application::ST_PENDING_REVIEW,
            'rebate_count' => 5,
        ]);

        $available = $application->rebate->remaining;

        $admin = factory(Admin::class)->create();

        $job = new DenyApplication($application, $admin);

        $this->dispatch($job);

        $application->refresh();

        $this->assertEquals($available + 5, $application->rebate->remaining);
    }

    /** @test */
    function it_can_have_a_denial_reason()
    {
        $application = factory(Application::class)->create([
            'status' => Application::ST_PENDING_REVIEW,
        ]);
        $admin = factory(Admin::class)->create();

        $job = new DenyApplication($application, $admin, new Denial('reason', 'more info'));
        $this->dispatch($job);

        $application->refresh();

        $this->assertEquals('reason', $application->transaction->description);
        $this->assertEquals('more info', $application->transaction->extended_description);
    }

    /** @test */
    function it_throws_an_exception_if_transaction_already_exists_for_application()
    {
        $caught = false;

        $application = factory(Application::class)->create([
            'status' => Application::ST_PENDING_REVIEW,
        ]);

        factory(\App\ApplicationTransaction::class)->create([
            'application_id' => $application->id
        ]);

        $admin = factory(Admin::class)->create();

        $job = new DenyApplication($application, $admin);

        try {
            $this->dispatch($job);
        } catch (\App\Exceptions\InvalidTransactionAttempt $e) {
            $caught = true;
        }

        $this->assertTrue($caught);
    }
}
