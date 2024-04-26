<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Applicants extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Applicant::class)->create([
            'first_name'     => 'Justin',
            'last_name'      => 'Tallant',
            'full_name'      => null,
            'email'          => 'applicant@example.com',
            'remember_token' => Str::random(10)
        ]);

        # Applicant represents a person who has applied for a Rebate.
        # Applicants table has no FK's so let's just create 100 random applicants.
        factory(App\Applicant::class, 800)->create();
    }
}
