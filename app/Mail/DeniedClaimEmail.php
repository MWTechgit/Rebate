<?php

namespace App\Mail;

use App\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class DeniedClaimEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function build()
    {
        return $this->view('emails.applicant.claim.denied')
            ->subject('Your Rebate Claim was Denied')
            ->with([
                'firstName' => $this->claim->applicant->first_name,
                'reason' => $this->claim->transaction->reason(),
            ])
        ;
    }
}
