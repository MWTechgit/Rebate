<?php

namespace App\Mail;

use App\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class ClaimExpiredEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function build()
    {
        return $this->view('emails.applicant.claim.expired')
            ->subject('Expired Rebate Claim')
            ->with([
                'claim' => $this->claim,
            ])
        ;
    }
}
