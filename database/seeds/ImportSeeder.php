<?php

use Illuminate\Database\Seeder;

class ImportSeeder extends Seeder
{
    public function run()
    {
        $this->call(RebateTypes::class);
        $this->call(ReferenceTypes::class);
    }
}
