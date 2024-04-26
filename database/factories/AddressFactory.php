<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(App\Address::class, function (Faker $faker) {
    // return [
    //     'addressable_id'   => null,
    //     'addressable_type' => null,
    //     'line_one' => rand(100, 999).' '.$faker->streetName,
    //     'line_two' => Arr::random([$faker->secondaryAddress, null, null]),
    //     'city'     => $faker->city,
    //     'state'    => $faker->state,
    //     'postcode' => $faker->postcode,
    //     'country'  => 'USA',
    //     'lat'      => $faker->latitude,
    //     'lng'      => $faker->longitude,
    // ];
    return [
        'addressable_id'   => null,
        'addressable_type' => null,
        'line_one' => '901 NW 11 Ave',
        'line_two' => null,
        'city'     => 'Fort Lauderdale',
        'state'    => 'FL',
        'postcode' => '33311',
        'country'  => 'USA',
        'lat'      => null,
        'lng'      => null,
    ];
});
