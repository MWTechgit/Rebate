<?php

namespace Tests\Unit\Job;

use App\Rebate;
use Tests\TestCase;
use App\Application;
use App\Jobs\ChangeApplicationRebateCount;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangeApplicationRebateCountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_properly_updates_rebate_inventory()
    {
        $application = factory(Application::class)->create([
            'rebate_count' => 2,
            'rebate_id' => factory(Rebate::class)->create(['inventory' => 50])->id,
        ]);

        # Should be set through RebateObserver
        $this->assertEquals(50, $application->rebate->remaining);

        $job = new ChangeApplicationRebateCount($application, 4);
        $this->dispatch($job);

        $application->refresh();

        $this->assertEquals(48, $application->rebate->remaining);
        $this->assertEquals(4, $application->rebate_count);

        $application = factory(Application::class)->create([
            'rebate_count' => 8,
            'rebate_id' => factory(Rebate::class)->create(['inventory' => 50])->id,
        ]);

        $job = new ChangeApplicationRebateCount($application, 4);
        $this->dispatch($job);

        $application->refresh();

        $this->assertEquals(54, $application->rebate->remaining);
        $this->assertEquals(4, $application->rebate_count);
    }
}