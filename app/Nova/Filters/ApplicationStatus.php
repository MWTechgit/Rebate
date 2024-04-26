<?php

namespace App\Nova\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ApplicationStatus extends Filter
{
    public $name = 'Status';

    public $component = 'select-filter';

    public function apply(Request $request, $query, $status)
    {
        return $query->hasStatus($status);
    }

    public function options(Request $request)
    {
        return [
            'New & Pending'     => 'new|pending-review',
            'New'               => 'new',
            'Pending Review'    => 'pending-review',
            'Expired'           => 'expired',
            'Denied'            => 'denied',
            'Approved'          => 'approved'
        ];
    }

}
