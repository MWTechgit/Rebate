<?php

use Faker\Generator as Faker;

$factory->define(App\ApplicationComment::class, function (Faker $faker) {
    return [
        'application_id' => function() {
            return factory(App\Application::class)->create();
        },

        'admin_id' => null,

        'content' => $faker->paragraph,
    ];
});
