<?php

namespace Tests\Feature\Api;

use App\Applicant;
use App\Application;
use App\Notifications\ApplicationPendingReview;
use App\Notifications\ApplicationWaitListed;
use App\Partner;
use App\Property;
use App\Rebate;
use App\RebateType;
use App\ReferenceType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class SubmissionsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_multiple_models_using_request_data()
    {
        \Notification::fake();

        $models = $this->createModels();
        $rebate = $models['rebate'];
        $data = $this->getJsonData('submissions-store');

        # Remove hardcoded IDs, use IDs from DB
        $data['rebate_id'] = $rebate->id;
        $data['reference']['reference_type_id'] = ReferenceType::first()->id;

        $remainingBefore = $rebate->remaining;
        $claiming = $data['application']['rebate_count'];

        $response = $this->json('POST', '/api/submissions', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ])
        ;

        $this->assertDatabaseHas('applicants', $data['applicant']);

        $applicant = Applicant::orderBy('created_at', 'desc')->first();

        # Assert we have retrieved the correct applicant
        $this->assertEquals($data['applicant']['email'], $applicant->email);

        $this->assertDatabaseHas('applications', [
            'rebate_id' => $data['rebate_id'],
            'applicant_id' => $applicant->id,
            'rebate_count' => $data['application']['rebate_count'],
            'fy_year' => fiscal_year(),
            'status' => Application::ST_NEW,
            'wait_listed' => false,
        ]);

        $partner = Partner::first();
        $property = Property::first();

        $this->assertStringStartsWith($partner->account_key, $applicant->application->rebate_code);

        # Property
        $this->assertDatabaseHas('properties', Arr::except($data['property'], ['address', 'property_type_group']));
        $this->assertDatabaseHas('addresses', $data['property']['address']);

        # UtilityAccount
        $this->assertDatabaseHas('utility_accounts', [
            'property_id' => $property->id,
            'account_number' => $data['utility_account']['account_number'],
        ]);
        $this->assertDatabaseHas('addresses', $data['utility_account']['address']);

        # Owner
        $this->assertDatabaseHas('owners', Arr::except($data['owner'], 'address'));
        $this->assertDatabaseHas('addresses', $data['utility_account']['address']);

        # Reference
        $this->assertDatabaseHas('references', $data['reference']);

        # Claimed Rebates
        $this->assertEquals($remainingBefore - $claiming, $rebate->refresh()->remaining);

        \Notification::assertSentTo($applicant, ApplicationPendingReview::class);
        \Notification::assertNotSentTo($applicant, ApplicationWaitListed::class);
    }

    /** @test */
    function it_doesnt_claim_rebates_for_wait_listed_application()
    {
        \Notification::fake();

        $models = $this->createModels();
        $rebate = $models['rebate'];
        $data = $this->getJsonData('submissions-store');

        # Remove hardcoded IDs, use IDs from DB
        $data['rebate_id'] = $rebate->id;
        $data['reference']['reference_type_id'] = ReferenceType::first()->id;

        # Make sure there won't be enough rebates for the application
        $rebate->remaining = 1;
        $rebate->save();

        $response = $this->json('POST', '/api/submissions', $data);
        $response->assertStatus(201);

        $this->assertDatabaseHas('applicants', $data['applicant']);
        $applicant = Applicant::orderBy('created_at', 'desc')->first();

        $app = Application::withoutGlobalScopes()->orderBy('created_at', 'desc')->first();
        $this->assertTrue($app->isWaitListed());
        $this->assertEquals(1, $rebate->refresh()->remaining);

        \Notification::assertSentTo($applicant, ApplicationWaitListed::class);
        \Notification::assertNotSentTo($applicant, ApplicationPendingReview::class);
    }

    protected function createModels()
    {
        $this->createReferenceTypes();

        $partner = factory(Partner::class)->create();

        $type = factory(RebateType::class)->create([
            'name' => 'Toilet',
        ]);

        $props = [
            'rebate_type_id' => $type->id,
            'partner_id' => $partner->id,
            'fy_year' => fiscal_year(),
            'remaining' => 20,
            'inventory' => 200,
            'value' => 100,
        ];

        return [
            'rebate' => factory(Rebate::class)->create($props),
            'partner' => $partner,
        ];
    }

    protected function createReferenceTypes()
    {
        $this->runSeed('ReferenceTypes');
    }
}
