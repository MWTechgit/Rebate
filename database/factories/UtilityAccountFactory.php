<?php

use Faker\Generator as Faker;

$factory->define(App\UtilityAccount::class, function (Faker $faker) {
    return [
        'property_id' => function() {
            return factory(App\Property::class)->create()->id;
        },

        'account_number' => rand(100000, 9999999),
    ];
});

$factory->afterCreating(App\UtilityAccount::class, function($account, $faker) {
    factory(App\Address::class)->create([
        'addressable_id' => $account->id,
        'addressable_type' => get_class($account),
    ]);
});