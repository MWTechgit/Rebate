<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

$factory->define(App\Applicant::class, function (Faker $faker) {
    $phone = function() {
        return '555'.rand(1000000, 9999999);
    };

    return [
        'first_name'             => $faker->firstName,
        'last_name'              => $faker->lastName,
        'company'                => Arr::random(['', $faker->company]),
        'email'                  => $faker->safeEmail, // emails are not unique
        'phone'                  => $phone(),
        'mobile'                 => $phone(),
        'feature_on_water_saver' => Arr::random([0, 1]),
        'email_opt_in'           => Arr::random([0, 1]),
        'remember_token'         => Str::random(10),
        'created_at'             => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
    ];
});

$factory->afterCreating(App\Applicant::class, function($applicant, $faker) {
    $numTypes = App\ReferenceType::count();
    $referenceTypeId = app()->environment('testing')
        ? factory(App\ReferenceType::class)->create()->id
        : rand(1, $numTypes)
    ;

    factory(App\Reference::class)->create([
        'reference_type_id' => $referenceTypeId,
        'applicant_id'      => $applicant->id,
    ]);
});
