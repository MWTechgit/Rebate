<?php

use App\Address;
use App\Application;
use App\History;
use App\Partner;
use App\Rebate;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(App\History::class, function (Faker $faker) {
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
        'application_id' => function() {
            return factory(App\Application::class)->create()->id;
        },
        'line_one' => '901 NW 11 Ave',
        'line_two' => null,
        'city'     => 'Fort Lauderdale',
        'state'    => 'FL',
        'postcode' => '33311',
        'rebate_code' => function(array $application) {
            return Application::generateUniqueCode( new Partner(['account_key' => 'ABC']) );
        },
        'partner'        => $faker->word,
        'email'          => $faker->email,
        'full_name'      => $faker->name,
        'account_number' => rand(100000, 9999999),
        'submitted_at'   => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
        'status' => Arr::random(['denied', 'approved', 'pending-review']),
    ];
});

$factory->afterCreating(App\History::class, function($history, $faker) {
    $history->address_index = Address::buildIndex($history);
});
