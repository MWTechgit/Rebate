<?php

use Faker\Generator as Faker;

$factory->define(App\RebateType::class, function (Faker $faker) {

    if (app()->environment('testing')) {
        return [
            'name' => $faker->unique()->colorName
        ];
    }

    return [
        'name' => 'Toilet2',
    ];
});
