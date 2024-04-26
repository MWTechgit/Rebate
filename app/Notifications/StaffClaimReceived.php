<?php

namespace App\Notifications;

use App\Admin;
use App\Claim;
use App\Mail\StaffClaimReceivedEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class StaffClaimReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(Admin $admin)
    {
        return (new StaffClaimReceivedEmail($this->claim, $admin))
            ->to($admin->email, $admin->full_name);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
