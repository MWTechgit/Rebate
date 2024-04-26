<?php

namespace App\Nova\Metrics;

use App\Application;
use App\Rebate;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Partition;

class HeldToiletsPerStatus extends Partition
{

    public $name = 'Rebate Allocation';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {

        $rebate = Rebate::find($request->resourceId);

        return $this->result([
            'New'       => (int) $rebate->relevantApplications()->pending()->sum('rebate_count'),
            'Pending'   => (int) $rebate->totalToiletsPending(),
            'Fulfilled' => (int) $rebate->totalToiletsFulfilled(),
            'Available' => (int) $rebate->remaining,
        ]);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'held-toilets-per-status';
    }
}
