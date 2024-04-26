<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(App\ReferenceType::class, function (Faker $faker) {
    return [
        'type'      => Arr::random(['Website', 'Flyer', 'Advertisement']),
        'info_text' => Arr::random(['What website, flyer, etc.', null]),
    ];
});
