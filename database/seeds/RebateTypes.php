<?php

use Illuminate\Database\Seeder;
use App\RebateType;

class RebateTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RebateType::create(['name' => 'Toilet']);
    }
}
