<?php

namespace App\Nova\Metrics;

use App\Application;
use App\Rebate;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;

class AwardedAveragePerRebate extends Value
{

    public $name = 'Average Rebate';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {

        $rebate = Rebate::find($request->resourceId);

        $query = $rebate->relevantApplications()
            ->join('claims', 'claims.application_id', '=', 'applications.id')
            ->selectRaw('AVG(claims.amount_awarded / applications.rebate_count) AS amount_average')
            ->getQuery();

        return $this->result( $query->value('amount_average') )->currency();
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
        return 'toilets-per-claim-status';
    }
}
