<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(App\Owner::class, function (Faker $faker) {
    return [
        'property_id' => function() {
            return factory(App\Property::class)->create()->id;
        },

        'first_name' => $faker->firstName,
        'last_name'  => $faker->lastName,
        'email'      => $faker->email,
        'company'    => Arr::random([null, $faker->company]),
        'phone'      => Arr::random([null, '555'.rand(1000000,9999999)]),
        'mobile'     => Arr::random([null, '555'.rand(1000000,9999999)]),
    ];
});

$factory->afterCreating(App\Owner::class, function($owner, $faker) {
    factory(App\Address::class)->create([
        'addressable_id' => $owner->id,
        'addressable_type' => App\Owner::class,
    ]);
});