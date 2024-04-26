<?php

namespace App\Jobs;

use App\Address;
use App\Admin;
use App\Applicant;
use App\Application;
use App\Events\ApplicationWasCreated;
use App\Jobs\ClaimRebates;
use App\Owner;
use App\Property;
use App\Rebate;
use App\Reference;
use App\UtilityAccount;
use App\WaterSense;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final class ProcessSubmission
{
    use DispatchesJobs;

    private $data;
    private $admin;

    private $waitListed;

    private $application;

    public function __construct(array $data, Admin $admin = null )
    {
        $this->data = $data;
        $this->admin = $admin;
    }

    public function handle(): void
    {
        DB::transaction(function () {
            $applicant = $this->createApplicant();
            $application = $this->createApplication($applicant);
            $property = $this->createProperty($application);
            $this->createUtilityAccount($property);
            $this->createOwner($property);
            $this->createReference($applicant);

            $this->dispatchNow(new ClaimRebates($application));

            event(new ApplicationWasCreated($application));
        }, 5);
    }

    public function isWaitListed()
    {
        return $this->waitListed;
    }

    public function getRebateCode()
    {
        return $this->application->rebate_code;
    }

    protected function createApplicant(): Applicant
    {
        $watersense = Arr::pull($this->data['applicant'], 'watersense');

        $applicant = Applicant::create($this->data['applicant']);

        if ($watersense) {
            $applicant->watersense()->save(new WaterSense(['reason' => $watersense]));
        }

        return $applicant;
    }

    protected function createApplication(Applicant $applicant): Application
    {
        $app = $this->data['application'];

        $rebate = Rebate::findOrFail($this->data['rebate_id']);

        $this->waitListed = false === $rebate->canSatisfyAmount($app['rebate_count']);

        $this->application = Application::create([
            'rebate_id'            => $rebate->id,
            'applicant_id'         => $applicant->id,
            'rebate_count'         => $app['rebate_count'],
            'desired_rebate_count' => $app['desired_rebate_count'] ?? 0,
            'fy_year'              => fiscal_year(),
            'status'               => Application::ST_NEW,
            'rebate_code'          => Application::generateUniqueCode($rebate->partner),
            'wait_listed'          => $this->waitListed,
            'submission_type'      => Arr::get($app, 'submission_type') ?: ($this->admin ? 'admin' : 'online'),
            'created_by_admin_id'  => optional($this->admin)->id,
        ]);

        if ( isset($app['address'])) {
            Address::create(array_merge([
                'addressable_id'   => $this->application->id,
                'addressable_type' => Application::class
            ], $app['address']));
        }

        return $this->application;
    }

    protected function createProperty(Application $application): Property
    {
        $property = Property::make(Arr::except($this->data['property'], ['address', 'property_type_group']));
        $property->application_id = $application->id;
        $property->save();

        Address::create(array_merge([
            'addressable_id' => $property->id,
            'addressable_type' => Property::class,
        ], $this->data['property']['address']));

        return $property;
    }

    protected function createUtilityAccount(Property $property): void
    {
        $account = UtilityAccount::create([
            'property_id' => $property->id,
            'account_number' => $this->data['utility_account']['account_number'],
        ]);

        Address::create(array_merge([
            'addressable_id' => $account->id,
            'addressable_type' => UtilityAccount::class,
        ], $this->data['utility_account']['address']));
    }

    protected function createOwner(Property $property): void
    {
        if (false === isset($this->data['owner']) || !isset($this->data['owner']['first_name'])) {
            return;
        }

        $owner = Owner::make(Arr::except($this->data['owner'], 'address'));
        $owner->property_id = $property->id;
        $owner->save();

        Address::create(array_merge([
            'addressable_id' => $owner->id,
            'addressable_type' => Owner::class,
        ], $this->data['owner']['address']));
    }

    protected function createReference(Applicant $applicant): void
    {
        Reference::create(array_merge([
            'applicant_id' => $applicant->id,
        ], $this->data['reference']));
    }
}
