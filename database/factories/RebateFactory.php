<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(App\Rebate::class, function (Faker $faker) {
    return [
        'rebate_type_id' => function() {
            return App\RebateType::inRandomOrder()->value('id') ?? factory(App\RebateType::class)->create()->id;
        },
        'partner_id' => function() {
            return App\Partner::inRandomOrder()->value('id') ?? factory(App\Partner::class)->create()->id;
        },
        'fy_year'           => Arr::random(range(2016, (int) now()->format('Y'))),
        'name'              => $faker->colorName.' '.rand(100,999). ' Rebate',
        'remaining'         => rand(0, 80),
        'inventory'         => rand(0, 500),
        'value'             => Arr::random([42, 50, 100]),
        'description'       => Arr::random(['Full', 'Full', 'Full', ''])
    ];
});
