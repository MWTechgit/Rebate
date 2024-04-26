<?php

namespace Tests\Unit\Job;

use App\Admin;
use App\Claim;
use Tests\TestCase;
use App\Application;
use App\ApplicationTransaction;
use App\Jobs\ApproveApplication;
use App\Exceptions\NotEnoughRebates;
use App\Events\ApplicationWasApproved;
use App\Exceptions\InvalidTransactionAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApproveApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_approves_an_application()
    {
        \Event::fake();

        $admin = factory(Admin::class)->create(['role' => Admin::WRITE]);

        $application = factory(Application::class)->make();
        $application->rebate_count = 2;
        $application->save();

        $rebate = $application->rebate;
        $rebate->remaining = 10;
        $rebate->save();

        $job = new ApproveApplication($application, $admin);
        $this->dispatch($job);

        $application->refresh();

        $this->assertEquals($admin->id, $application->transaction->admin_id);
        $this->assertTrue($application->isApproved());
        $this->assertEquals(Application::ST_APPROVED, $application->status);

        $this->assertDatabaseHas('claims', [
            'application_id' => $application->id,
            'applicant_id' => $application->applicant_id,
            'fy_year' => $application->rebate->fy_year,
            'status' => Claim::ST_UNCLAIMED,
            'submission_type' => 'online',
        ]);

        \Event::assertDispatched(ApplicationWasApproved::class);
    }

    /** @test */

    // No, This test is bunk. The rebates were already claimed when the application
    // was created. Approving the application does not take more rebates

    // function it_fails_if_not_enough_rebates()
    // {
    //     \Event::fake();

    //     $admin = factory(Admin::class)->create(['role' => Admin::WRITE]);

    //     $application = factory(Application::class)->make();
    //     $application->rebate_count = 2;
    //     $application->save();

    //     $rebate = $application->rebate;
    //     $rebate->remaining = 1;
    //     $rebate->save();

    //     $job = new ApproveApplication($application, $admin);
    //     $caught = false;

    //     try {
    //         $this->dispatch($job);
    //     } catch (NotEnoughRebates $e) {
    //         $caught = true;
    //     }

    //     $this->assertTrue($caught);
    // }

    /** @test */
    function it_fails_if_already_approved()
    {
        \Event::fake();

        $admin = factory(Admin::class)->create(['role' => Admin::WRITE]);

        $application = factory(Application::class)->make();
        $application->rebate_count = 1;
        $application->save();

        factory(ApplicationTransaction::class)->create([
            'admin_id' => $admin->id,
            'application_id' => $application->id,
            'type' => 'approved',
        ]);

        $rebate = $application->rebate;
        $rebate->remaining = 4;
        $rebate->save();

        $job = new ApproveApplication($application, $admin);
        $caught = false;

        try {
            $this->dispatch($job);
        } catch (InvalidTransactionAttempt $e) {
            $caught = true;
        }

        $this->assertTrue($caught);
    }
}
