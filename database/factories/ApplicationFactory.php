<?php

use App\Application;
use App\ApplicationTransaction;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Application::class, function (Faker $faker) {
    return [
        'rebate_id' => function() {
            return factory(App\Rebate::class)->create()->id;
        },

        'applicant_id' => function() {
            return factory(App\Applicant::class)->create()->id;
        },

        'admin_id' => function() {
            return factory(App\Admin::class)->create()->id;
        },

        'rebate_code' => function(array $application) {
            $rebate = App\Rebate::find($application['rebate_id']);
            return Application::generateUniqueCode($rebate->partner);
        },

        'fy_year'               => Arr::random(range(2016, 2018)),
        'status'                => Arr::random(['expired', 'approved', 'denied', 'pending-review', 'new']),
        'rebate_count'          => Arr::random([1,2]),
        'desired_rebate_count'  => Arr::random([1,15]),
        'wait_listed'           => 0,
        'notification_sent'     => 0,
        'notification_sent_at'  => null,
        'created_at'            => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
    ];
});

$factory->afterCreating(Application::class, function($app, $faker) {

    factory(App\ApplicationComment::class, 3)->create([
        'application_id' => $app->id,
        'admin_id'       => $app->admin_id,
    ]);

    if ($app->wait_listed) {
        return;
    }

    switch ($app->status) {
        case Application::ST_APPROVED:
            factory(ApplicationTransaction::class)->create([
                'application_id' => $app->id,
                'admin_id'       => $app->admin_id ?? 1,
                'type'           => ApplicationTransaction::ST_APPROVED,
            ]);
            break;
        case Application::ST_DENIED:
            factory(ApplicationTransaction::class)->create([
                'application_id' => $app->id,
                'admin_id'       => $app->admin_id ?? 1,
                'type'           => ApplicationTransaction::ST_DENIED,
            ]);
            break;
        case Application::ST_PENDING_REVIEW:
                $app->admin_first_viewed_at = $app->created_at;
                $app->save();
        case Application::ST_NEW:
        case Application::ST_EXPIRED:
            # do nothing
            break;
    }
});
