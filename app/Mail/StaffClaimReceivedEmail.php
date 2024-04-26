<?php

namespace App\Mail;

use App\Admin;
use App\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class StaffClaimReceivedEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $claim;
    protected $admin;

    public function __construct(Claim $claim, Admin $admin)
    {
        $this->claim = $claim;
        $this->admin       = $admin;
    }

    public function build()
    {
        $url = url('/admin/resources/claims/' . $this->claim->id );
        $rebate = $this->claim->rebate();

        return $this->view('emails.staff.claim.received')
            ->subject('New Claim Received')
            ->with([
                'staff_name'     => $this->admin->first_name,
                'claim'          => $this->claim,
                'applicant_name' => $this->claim->applicant->first_name,
                'rebate_code'    => $this->claim->application->rebate_code,
                'approved_on'    => (string) $this->claim->application->approved_on,
                'partner_name'   => $rebate->partner->name,
                'rebate_name'    => $rebate->name,
                'claim_url'      => $url,
                'buttonText'     => 'View Claim',
                'buttonUrl'      => $url,
            ])
        ;
    }
}
