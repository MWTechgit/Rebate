<?php

namespace App\Nova\Metrics;

use App\Application;
use App\Rebate as Model;
use App\Rebate;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Partition;

class RefusedToiletsPerRebate extends Partition
{

    public $name = 'Unsuccessful Applications';

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
            'Application Denied' => (int) $rebate->applications()->denied()->sum('rebate_count'),

            'Claim Denied'       => (int) $rebate->applications()->whereHas('claim', function ($query) {
                return $query->denied();
            })->sum('rebate_count'),

            'Expired'            => (int) $rebate->applications()->whereHas('claim', function ($query) {
                return $query->expired();
            })->sum('rebate_count'),

            'Wait Listed'          => (int) $rebate->totalToiletsOnWaitlist(),
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
        return 'refused-toilets-per-rebate';
    }
}
