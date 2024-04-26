<?php

namespace Tests\Unit\Job;

use App\Claim;
use App\Admin;
use App\Application;
use App\Jobs\DenyClaim;
use App\Notifications\DeniedClaim;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DenyClaimTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_denies_a_claim()
    {
        \Notification::fake();

        $application = factory(Application::class)->create([
            'status' => Application::ST_APPROVED,
            'rebate_count' => 3,
        ]);

        $available = $application->rebate->remaining;

        $admin = $application->admin;

        $claim = factory(Claim::class)->create([
            'application_id' => $application->id,
            'status' => Claim::ST_PENDING_REVIEW,
        ]);

        $job = new DenyClaim($claim, $admin, new \App\Denial('Denial reason'));
        $this->dispatch($job);

        $claim->refresh();

        $this->assertTrue($claim->isDenied());
        $this->assertEquals(Claim::ST_DENIED, $claim->status);
        $this->assertEquals($admin->id, $claim->transactionBy()->id);
        $this->assertEquals('Denial reason', $claim->transaction->description);
        $this->assertTrue($claim->application->isDenied());

        # Assert rebates released
        $this->assertEquals($available + 3, $claim->rebate()->remaining);

        \Notification::assertSentTo(
            $claim->applicant,
            DeniedClaim::class,
            function ($notification, $channels) use ($claim) {
                return $notification->getClaim()->id === $claim->id;
            }
        );
    }
}
