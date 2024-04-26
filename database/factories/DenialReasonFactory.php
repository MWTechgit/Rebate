<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(App\DenialReason::class, function (Faker $faker) {
    return [
        'type'    => Arr::random(['Application', 'Claim']),
        'reason'  => 'Already received rebate',
        'message' => 'Our Records Indicate That You Already Received A Rebate Through This Program',
    ];
});
