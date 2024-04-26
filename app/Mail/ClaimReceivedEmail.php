<?php

namespace App\Mail;

use App\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class ClaimReceivedEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function build()
    {
        return $this->view('emails.applicant.claim.received')
            ->subject('Reviewing Your Rebate Claim')
            ->with([
                'claim'   => $this->claim,
                'contact' => (object) config('broward.contact'),
            ])
        ;
    }
}
