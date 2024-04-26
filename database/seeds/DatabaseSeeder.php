<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Admins::class);
        $this->call(RebateTypes::class);
        $this->call(Partners::class);
        $this->call(Rebates::class);
        $this->call(ReferenceTypes::class);
        $this->call(Applicants::class);
        $this->call(Applications::class);
        $this->call(DenialReasons::class);

        \Artisan::call('populate:history');
    }
}
