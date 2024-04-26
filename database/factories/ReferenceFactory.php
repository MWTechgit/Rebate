<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(App\Reference::class, function (Faker $faker) {
    return [
        'reference_type_id' => function() {
            return factory(App\ReferenceType::class)->create()->id;
        },

        'applicant_id' => function() {
            return factory(App\Applicant::class)->create()->id;
        },

        'info_response' => Arr::random([
            null,
            'Description of how I found reference here. Description of how I found reference here. Description of how I found reference here.',
            'Description',
            'https://twitter.com/jtallant',
            'https://youtube.com'
        ]),
    ];
});
