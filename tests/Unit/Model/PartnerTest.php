<?php

namespace Tests\Unit\Model;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Partner;

class PartnerTest extends TestCase
{
    // use RefreshDatabase;

    /** @test */
    public function test_rebate_partner_parent()
    {
        $this->markTestSkipped();
        // test that hasActiveRebate returns
        // only partners with a rebate in provided fiscal year

        // test that partners with a parent_id are included
        // if the parent has an active rebate
    }
}
