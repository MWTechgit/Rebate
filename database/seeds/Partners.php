<?php

use Illuminate\Database\Seeder;
use App\Partner;

class Partners extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        rescue( function () {
        	factory(Partner::class, 17)->create();
        });
    }
}
