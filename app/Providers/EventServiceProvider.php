<?php

namespace App\Providers;

use App\Listeners\NotificationSentHandler;
use App\Events\{
    ApplicationWasCreated,
    ApplicationWasImported,
    ApplicationWasApproved,
    ApplicationWasDenied,
    ApplicationWasAcceptedFromWaitList,
    ClaimExpiringSoon,
    ClaimWasApproved,
    ClaimWasDenied,
    ClaimWasExpired,
    ClaimWasReceived
};
use App\Listeners\{
    SendDeniedClaimNotification,
    SendApprovedClaimNotification,
    SendApplicationReceivedNotification,
    SendApplicationApprovedNotification,
    SendApplicationDeniedNotification,
    SendClaimExpiringSoonNotification,
    SendClaimReceivedNotification,
    WriteApplicationToHistory
};
// use Illuminate\Support\Facades\Event;
// use Illuminate\Auth\Events\Registered;
// use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Notifications\Events\NotificationSent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        NotificationSent::class => [
            NotificationSentHandler::class,
        ],
        ApplicationWasCreated::class => [
            WriteApplicationToHistory::class,
            SendApplicationReceivedNotification::class,
        ],
        ApplicationWasImported::class => [
            WriteApplicationToHistory::class,
        ],
        ApplicationWasAcceptedFromWaitList::class => [
            # Send notifications
        ],
        ApplicationWasApproved::class => [
            SendApplicationApprovedNotification::class
        ],
        ApplicationWasDenied::class => [
            SendApplicationDeniedNotification::class
        ],
        ClaimWasApproved::class => [
            SendApprovedClaimNotification::class,
        ],
        ClaimWasDenied::class => [
            SendDeniedClaimNotification::class,
        ],
        ClaimExpiringSoon::class => [
            SendClaimExpiringSoonNotification::class
        ],
        ClaimWasReceived::class => [
            SendClaimReceivedNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
