<?php

namespace App\Mail;

use App\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class ClaimExpiringEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function build()
    {
        return $this->view('emails.applicant.claim.expiring')
            ->subject('Hurry! Your Rebate Claim will Expire Soon')
            ->with([
                'claim'             => $this->claim,
                'buttonText'        => 'Rebate Center',
                'buttonUrl'         => route('login'),
                'rebate_center_url' => route('login'),
                'contact'           => (object) config('broward.contact'),
                'terms_url'         => config('broward.t&c')
            ])
        ;
    }
}