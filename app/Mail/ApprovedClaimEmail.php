<?php

namespace App\Mail;

use App\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class ApprovedClaimEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function build()
    {
        return $this->view('emails.applicant.claim.approved')
            ->subject('Your Rebate Claim was Approved')
            ->with([
                'claim' => $this->claim,
            ])
        ;
    }
}
