<?php

namespace App\Notifications;

use App\Applicant;
use App\Claim;
use App\Mail\ClaimExpiringEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClaimExpiring extends Notification implements ShouldQueue
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

    public function toMail(Applicant $applicant)
    {
        return (new ClaimExpiringEmail($this->claim))
            ->to($applicant->email, $applicant->full_name);
    }

    public function toArray($notifiable)
    {
        return [];
    }

    public function getClaim()
    {
        return $this->claim;
    }
}
