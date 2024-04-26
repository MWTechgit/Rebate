<?php

namespace App\Events;

use App\Claim;
use App\Admin;
use Illuminate\Queue\SerializesModels;

final class ClaimWasDenied
{
    use SerializesModels;

    public $claim;

    public $admin;

    public function __construct(Claim $claim, Admin $admin)
    {
        $this->claim = $claim;
        $this->admin = $admin;
    }
}
