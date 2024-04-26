<?php

namespace Tests\Unit\Model;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Claim;
use App\Application;
use App\DocumentSet;

class DocumentSetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_num_files()
    {
        $claim = factory(Claim::class)->create([
            'application_id' => factory(Application::class)->create([
                'rebate_count' => 1,
            ])->id
        ]);

        $set = factory(DocumentSet::class)->create([
            'claim_id'           => $claim->id,
            'upc'                => 'filepath',
            'receipt'            => 'filepath',
            'installation_photo' => 'filepath',
        ]);

        $this->assertEquals(3, $set->numFiles());

        $set = factory(DocumentSet::class)->create([
            'claim_id'           => $claim->id,
            'upc'                => null,
            'receipt'            => 'filepath',
            'installation_photo' => 'filepath',
        ]);

        $this->assertEquals(2, $set->numFiles());
    }
}
