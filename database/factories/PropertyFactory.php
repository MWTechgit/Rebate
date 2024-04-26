<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(App\Property::class, function (Faker $faker) {
    $numBathrooms = rand(2,6);
    $full = $numBathrooms > 2 ? 2 : $numBathrooms;
    $half = $numBathrooms - $full;

    # TODO: If commercial, don't calculate number of toilets
    return [
        'application_id' => function() {
            return factory(App\Application::class)->create()->id;
        },

        'property_type'              => Arr::random(App\Property::PROPERTY_TYPES),
        'building_type'              => Arr::random(App\Property::BUILDING_TYPES),
        'subdivision_or_development' => Arr::random(['Westbridge', 'Lakeview', '']),
        'bathrooms'                  => $numBathrooms,
        'toilets'                    => $numBathrooms,
        'full_bathrooms'             => $full,
        'half_bathrooms'             => $half,
        'year_built'                 => rand(1960, 2018),
        'original_toilet'            => Arr::random(['Yes', 'No', 'Maybe']),
        'gallons_per_flush'          => Arr::random(['', 3, 3.5, '2-5', "don't know", '7 gallons', '3 gallons per flush']),

        'occupants' => function(array $property) {
            if ('Residential' == $property['property_type']) {
                return rand(1, 8);
            }

            return Arr::random(['1-10', '11-20', '21-30', '31-40', 'more than 100']);
        },

        'years_lived' => Arr::random(['', rand(0, 42), rand(0, 42)]),
    ];
});

$factory->afterCreating(App\Property::class, function($property, $faker) {
    factory(App\UtilityAccount::class)->create([
        'property_id' => $property->id
    ]);

    factory(App\Address::class)->create([
        'addressable_id' => $property->id,
        'addressable_type' => App\Property::class,
    ]);

    # Some application.properties are not owned by application.applicant
    if (4 === rand(1,4)) {
        factory(App\Owner::class)->create([
            'property_id' => $property->id,
        ]);
    }
});
