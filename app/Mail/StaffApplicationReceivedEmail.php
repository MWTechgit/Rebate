<?php

namespace App\Mail;

use App\Admin;
use App\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class StaffApplicationReceivedEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $application;
    protected $admin;

    public function __construct(Application $application, Admin $admin)
    {
        $this->application = $application;
        $this->admin       = $admin;
    }

    public function build()
    {
        $url = url('/admin/resources/applications/' . $this->application->id );

        return $this->view('emails.staff.application.received')
            ->subject('New Application Received')
            ->with([
                'staff_name'        => $this->admin->first_name,
                'applicant_name'    => $this->application->applicant->first_name,
                'partner_name'      => $this->application->rebate->partner->name,
                'address'           => $this->application->property->address,
                'rebate_name'       => $this->application->rebate->name,
                'rebates_remaining' => $this->application->rebate->remaining,
                'rebate_code'       => $this->application->rebate_code,
                'application_url'   => $url,
                'buttonText'        => 'View Application',
                'buttonUrl'         => $url,
            ])
        ;
    }
}
