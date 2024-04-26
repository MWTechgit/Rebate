<?php

use Illuminate\Database\Seeder;
use App\Rebate;

class Rebates extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numRebateTypes = App\RebateType::count();
        $numPartners = App\Partner::count();

        factory(Rebate::class, 21)->create([
            'rebate_type_id' => function() use ($numRebateTypes) {
                return rand(1, $numRebateTypes);
            },

            'partner_id' => function() use ($numPartners) {
                return rand(1, $numPartners);
            },
        ]);
    }
}
