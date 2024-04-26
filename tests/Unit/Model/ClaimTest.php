<?php

namespace Tests\Unit\Model;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Claim;
use App\Application;
use App\DocumentSet;

class ClaimTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function all_files_uploaded_checks_document_sets()
    {
        $claimNoFiles = factory(Claim::class)->create();

        $claimNotEnoughSets = factory(Claim::class)->create([
            'application_id' => factory(Application::class)->create([
                'rebate_count' => 2,
            ])->id
        ]);

        factory(DocumentSet::class)->create([
            'claim_id'           => $claimNotEnoughSets->id,
            'upc'                => 'filepath',
            'receipt'            => 'filepath',
            'installation_photo' => 'photo',
        ]);

        $claimSome = factory(Claim::class)->create([
            'application_id' => factory(Application::class)->create([
                'rebate_count' => 2,
            ])->id
        ]);

        factory(DocumentSet::class)->create([
            'claim_id'           => $claimSome->id,
            'upc'                => 'filepath',
            'receipt'            => 'filepath',
            'installation_photo' => 'photo',
        ]);

        factory(DocumentSet::class)->create([
            'claim_id'           => $claimSome->id,
            'upc'                => 'filepath',
            'receipt'            => null,
            'installation_photo' => 'photo',
        ]);

        $all = factory(Claim::class)->create([
            'application_id' => factory(Application::class)->create([
                'rebate_count' => 1,
            ])->id
        ]);

        factory(DocumentSet::class)->create([
            'claim_id'           => $all->id,
            'upc'                => 'filepath',
            'receipt'            => 'filepath',
            'installation_photo' => 'photo',
        ]);

        $this->assertFalse($claimNoFiles->allFilesUploaded());
        $this->assertFalse($claimNotEnoughSets->allFilesUploaded());
        $this->assertFalse($claimSome->allFilesUploaded());
        $this->assertTrue($all->allFilesUploaded());
    }

    /** @test */
    function num_files_counts_document_set_files()
    {
        $claim = factory(Claim::class)->create([
            'application_id' => factory(Application::class)->create([
                'rebate_count' => 1,
            ])->id
        ]);

        $this->assertEquals(0, $claim->numFiles());

        $set = factory(DocumentSet::class)->create([
            'claim_id'           => $claim->id,
            'upc'                => 'filepath',
            'receipt'            => 'filepath',
            'installation_photo' => 'filepath',
        ]);

        $claim->refresh();

        $this->assertEquals(3, $claim->numFiles());

        $this->assertEquals(3, $claim->expectedFileCount());
    }
}
