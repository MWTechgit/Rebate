<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(App\ApplicationTransaction::class, function (Faker $faker) {
    return [
        'admin_id' => function() {
            return factory(App\Admin::class)->create()->id;
        },

        'application_id' => function() {
            return factory(App\Application::class)->create()->id;
        },

        'type' => Arr::random(['approved', 'denied']),

        'description' => function(array $transaction) {
            return 'denied' === $transaction['type']
                ? 'Hello, Unfortunately, since you did not respond to our requests for additional information we will have to deny this rebate. We encourage you to apply again if you are still interested in the program and funds are still available. Thank you.'
                : null
            ;
        },

        'extended_description' => function(array $transaction) {
            return 'denied' === $transaction['type']
                ? 'The longer description goes here.'
                : null
            ;
        },

        'created_at' => function(array $transaction) {
            $app = App\Application::find($transaction['application_id']);
            return $app->created_at->addWeeks(rand(1, 4));
        },
    ];
});

$factory->afterCreating(App\ApplicationTransaction::class, function($transaction, $faker) {
    if ($transaction->isApproved()) {
        factory(App\Claim::class)->create([
            'application_id' => $transaction->application_id,
        ]);
    }
});
