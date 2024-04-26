<?php

namespace App\Notifications;

use App\Admin;
use App\Application;
use App\Mail\StaffApplicationReceivedEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class StaffApplicationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(Admin $admin)
    {
        return (new StaffApplicationReceivedEmail($this->application, $admin))
            ->to($admin->email, $admin->full_name);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
