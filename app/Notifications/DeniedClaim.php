<?php

namespace App\Notifications;

use App\Claim;
use App\Applicant;
use App\Mail\DeniedClaimEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

final class DeniedClaim extends Notification implements ShouldQueue
{
    use Queueable;

    protected $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function getClaim()
    {
        return $this->claim;
    }

    public function via(Applicant $applicant)
    {
        return ['mail'];
    }

    public function toMail(Applicant $applicant)
    {
        return (new DeniedClaimEmail($this->claim))
            ->to($applicant->email, $applicant->full_name)
        ;
    }

    public function toArray($applicant)
    {
        return [];
    }
}
