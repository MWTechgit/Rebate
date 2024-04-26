<?php

namespace Tests\Unit\Model;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Application;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_special_attention_if_pending_review_greater_than_14_days()
    {
        $isSpecialAttention = factory(Application::class)->create([
            'status' => Application::ST_PENDING_REVIEW,
            'admin_first_viewed_at' => now()->subDays(15)
        ]);

        $notSpecialAttention = factory(Application::class)->create([
            'status' => Application::ST_NEW,
            'admin_first_viewed_at' => null
        ]);

        $this->assertTrue($isSpecialAttention->isSpecialAttention());
        $this->assertFalse($notSpecialAttention->isSpecialAttention());
    }
}
