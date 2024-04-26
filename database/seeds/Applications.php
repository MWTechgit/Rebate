<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class Applications extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numRebates = App\Rebate::count();
        $numAdmins = App\Admin::count();

        # Applicants have ONE Application
        # Create an application for every applicant.
        # Note that not every rebate will always have an application.
        # This is ok but it reflects how the application works in production.
        App\Applicant::each(function($applicant) use ($numRebates, $numAdmins) {

            $fields = [
                'applicant_id' => $applicant->id,
                'rebate_id'    => rand(1, $numRebates),
                'admin_id'     => Arr::random([rand(1, $numAdmins), null]),
            ];

            $waitList = rand(1,8) == 8;

            if ($waitList) {
                $fields['wait_listed'] = 1;
                $fields['status'] = 'new';
            }

            $application = factory(App\Application::class)->create($fields);

            $property = factory(App\Property::class)->create([
                'application_id' => $application->id,
            ]);

            # Create remittance address for subset of applications
            if (rand(1, 4) == 4) {
                factory(App\Address::class)->create([
                    'addressable_id' => $application->id,
                    'addressable_type' => App\Application::class,
                ]);
            }
        });
    }
}
