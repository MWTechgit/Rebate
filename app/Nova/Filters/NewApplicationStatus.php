<?php

namespace App\Nova\Filters;

use App\Nova\Filters\ApplicationStatus;
use Illuminate\Http\Request;

class NewApplicationStatus extends ApplicationStatus
{

    public function options(Request $request)
    {
        return [
            'New & Pending'     => 'new|pending-review',
            'New'               => 'new',
            'Pending Review'    => 'pending-review'
        ];
    }

    public function default() {
        return 'new|pending-review';
    }

}
