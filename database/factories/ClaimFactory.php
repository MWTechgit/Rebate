<?php

use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

$factory->define(App\Claim::class, function (Faker $faker) {
    return [
        'application_id' => function() {
            return factory(App\Application::class)->create()->id;
        },

        'applicant_id' => function(array $claim) {
            $app = App\Application::find($claim['application_id']);
            return $app->applicant_id;
        },

        'amount_awarded' => null,

        'submission_type' => Arr::random(['online', 'online', 'email', 'mail', '']),

        'status' => function(array $claim) {
            $status = Arr::random([
                'not-claimed',
                'new',
                'pending-review',
                'denied',
                'pending-fulfillment',
                'fulfilled',
                'expired',
            ]);

            # Mailed claims don't expire
            if ('expired' == $status && 'mail' == $claim['submission_type']) {
                return 'not-claimed';
            }

            return $status;
        },


        'skip_document_upload' => rand(0,1),

        'expire_notification_sent' => false,

        'post_marked_at' => function(array $claim) {
            return 'mail' === $claim['submission_type'] ? now() : null;
        },

        'fy_year' => function(array $claim) {
            if ($claim['submitted_at']($claim)) {
                return $claim['submitted_at']($claim)->format('Y');
            }

            return $claim['created_at']->format('Y');
        },

        'submitted_at' => function(array $claim) use ($faker) {
            return $claim['status'] !== 'not-claimed'
                ? $faker->dateTimeBetween('-6 months', 'now')
                : null
            ;
        },

        'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
    ];
});

$factory->afterCreating(App\Claim::class, function($claim, $faker) {

    $expired = 'expired' == $claim->status;

    # Set expiration values if status is expired
    if ($expired) {
        $expiredAt = now()->subDays(rand(1, 30));
        $claim->created_at = $expiredAt->copy()->subDays(45);
        $claim->submitted_at = $claim->created_at->copy()->subDays(10);
        $claim->expires_at = $expiredAt;
        $claim->expired_at = $expiredAt;
        $claim->expire_notification_sent = rand(0,1);
        $claim->save();
    }

    # If the claim has a status of approved or denied,
    # there should be a corresponding claim transaction.
    if (in_array($claim->status, ['fulfilled', 'pending-fulfillment', 'denied'])) {
        $numAdmins = App\Admin::count();

        $adminId = app()->environment('testing')
            ? factory(App\Admin::class)->create()
            : rand(1, $numAdmins)
        ;

        factory(App\ClaimTransaction::class)->create([
            'admin_id' => $adminId,
            'claim_id' => $claim->id,
            'type'     => $claim->status == 'denied' ? 'denied' : 'approved',
        ]);
    }

    if ($claim->isApproved()) {
        $claim->amount_awarded = Arr::random([100, 100, 100, 200, 94.24]);
        $claim->save();
    }

    if ($claim->status == App\Claim::ST_PENDING_REVIEW) {
        $claim->admin_first_viewed_at = $claim->created_at;
        $claim->save();
    }
});
