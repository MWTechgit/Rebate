<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(App\ClaimTransaction::class, function (Faker $faker) {

    return [
        'admin_id' => function() {
            return factory(App\Admin::class)->create()->id;
        },

        'claim_id' => function() {
            return factory(App\Claim::class)->create()->id;
        },

        'type' => Arr::random(['approved', 'denied']),

        'description' => function(array $transaction) {
            return 'denied' === $transaction['type']
                ? 'Hello, Unfortunately, since you did not respond to our requests for additional information we will have to deny this rebate. We encourage you to apply again if you are still interested in the program and funds are still available. Thank you.'
                : null
            ;
        },

        'created_at' => $faker->dateTimeBetween('-12 months', 'now')
    ];
});
