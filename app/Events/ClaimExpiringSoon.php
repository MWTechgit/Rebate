<?php

namespace App\Events;

use App\Claim;
use Illuminate\Queue\SerializesModels;

final class ClaimExpiringSoon
{
    use SerializesModels;

    public $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }
}
