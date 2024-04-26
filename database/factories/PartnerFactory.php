<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(App\Partner::class, function (Faker $faker) {
    return [
        'parent_id'     => null,
        'name'          => $faker->city,
        'description'   => Arr::random(['full', 'Full', 'FULL', 'Active', null]),
        'account_key'   => function() {
            $letters = range('A','Z');
            shuffle($letters);
            return join('', array_slice($letters, 0, 3));
        },
    ];
});
