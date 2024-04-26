<?php

namespace Tests\Unit\Job;

use App\Admin;
use App\Claim;
use App\Application;
use App\Rebate;
use App\Jobs\ApproveClaim;
use App\Events\ClaimWasApproved;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApproveClaimTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_approves_a_claim()
    {
        \Event::fake();

        $admin = factory(Admin::class)->create();

        $claim = factory(Claim::class)->create([
            'status' => Claim::ST_PENDING_REVIEW,
            'application_id' => factory(Application::class)->create([
                'admin_id' => $admin->id,
                'rebate_id' => factory(Rebate::class)->create([
                    'value' => 120,
                    'remaining' => 100,
                    'inventory' => 100,
                ])->id,
                'status' => Application::ST_APPROVED,
                'rebate_count' => 2,
            ])->id
        ]);

        $awarded = 240;

        $job = new ApproveClaim($claim, $admin, $awarded);
        $this->dispatch($job);

        $claim->refresh();

        $this->assertTrue($claim->isApproved());
        $this->assertEquals(Claim::ST_PENDING_FULFILLMENT, $claim->status);
        $this->assertEquals(240, $claim->amount_awarded);

        \Event::assertDispatched(ClaimWasApproved::class);
    }

    /** @test */
    function it_wont_approve_claim_for_unapproved_application()
    {
        \Event::fake();

        $admin = factory(Admin::class)->create();

        $claim = factory(Claim::class)->create([
            'status' => Claim::ST_PENDING_REVIEW,
            'application_id' => factory(Application::class)->create([
                'admin_id' => $admin->id,
                'rebate_id' => factory(Rebate::class)->create([
                    'value' => 100,
                    'remaining' => 100,
                    'inventory' => 100,
                ])->id,
                # claim factory creates denied transaction when status denied
                'status'       => Application::ST_NEW,
                'rebate_count' => 1,
            ])->id
        ]);

        $job = new ApproveClaim($claim, $admin, 100);
        $caught = false;

        try {
            $this->dispatch($job);
        } catch (\App\Exceptions\InvalidTransactionAttempt $e) {
            $caught = true;
        }

        $this->assertTrue($caught);
        \Event::assertNotDispatched(ClaimWasApproved::class);
    }

    /** @test */
    function it_wont_approve_claim_for_denied_application()
    {
        \Event::fake();

        $admin = factory(Admin::class)->create();

        $claim = factory(Claim::class)->create([
            'status' => Claim::ST_PENDING_REVIEW,
            'application_id' => factory(Application::class)->create([
                'admin_id' => $admin->id,
                'rebate_id' => factory(Rebate::class)->create([
                    'value' => 100,
                    'remaining' => 100,
                    'inventory' => 100,
                ])->id,
                # claim factory creates denied transaction when status denied
                'status' => Application::ST_DENIED,
                'rebate_count' => 1,
            ])->id
        ]);

        $job = new ApproveClaim($claim, $admin, 100);
        $caught = false;

        try {
            $this->dispatch($job);
        } catch (\App\Exceptions\InvalidTransactionAttempt $e) {
            $caught = true;
        }

        $this->assertTrue($caught);
        \Event::assertNotDispatched(ClaimWasApproved::class);
    }

    /** @test */
    function it_throws_an_exception_if_the_claim_is_already_approved_or_denied()
    {
        \Event::fake();

        $admin = factory(Admin::class)->create();

        $claim = factory(Claim::class)->create([
            'status' => Claim::ST_FULFILLED,
            'application_id' => factory(Application::class)->create([
                'admin_id' => $admin->id,
                'rebate_id' => factory(Rebate::class)->create([
                    'value' => 100,
                    'remaining' => 100,
                    'inventory' => 100,
                ])->id,
                'status' => Application::ST_APPROVED,
                'rebate_count' => 1,
            ])->id
        ]);

        $job = new ApproveClaim($claim, $admin, 100);
        $caught = false;

        try {
            $this->dispatch($job);
        } catch (\App\Exceptions\InvalidTransactionAttempt $e) {
            $caught = true;
        }

        $this->assertTrue($caught);
    }

    /** @test */
    function it_throws_an_exception_if_amount_awarded_exceeds_max()
    {
        \Event::fake();

        $admin = factory(Admin::class)->create();

        $claim = factory(Claim::class)->create([
            'status' => Claim::ST_PENDING_REVIEW,
            'application_id' => factory(Application::class)->create([
                'admin_id' => $admin->id,
                'rebate_id' => factory(Rebate::class)->create([
                    'value' => 120,
                    'remaining' => 100,
                    'inventory' => 100,
                ])->id,
                'status' => Application::ST_PENDING_REVIEW,
                'rebate_count' => 2,
            ])->id
        ]);

        $awarded = 300;

        $job = new ApproveClaim($claim, $admin, $awarded);
        $caught = false;

        try {
            $this->dispatch($job);
        } catch (\App\Exceptions\InvalidTransactionAttempt $e) {
            $caught = true;
        }

        $this->assertTrue($caught);
    }

    function it_throws_an_exception_if_rebates_arent_available()
    {
        $this->markTestSkipped();
    }
}
