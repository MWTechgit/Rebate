<?php

namespace App\Observers;

use App\Rebate;

class RebateObserver
{
    public function creating(Rebate $rebate)
    {
        $rebate->remaining = $rebate->inventory;
    }
}
