<?php

namespace App\Nova\Metrics;

use App\Claim as Model;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Partition;

class ClaimsByStatus extends Partition {
	public $width = '1/2';

	/**
	 * Calculate the value of the metric.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return mixed
	 */
	public function calculate(Request $request) {
		return $this->count($request, Model::class, 'status');
	}

	/**
	 * Determine for how many minutes the metric should be cached.
	 *
	 * @return  \DateTimeInterface|\DateInterval|float|int
	 */
	public function cacheFor() {
		return now()->addMinutes(5);
	}

	/**
	 * Get the URI key for the metric.
	 *
	 * @return string
	 */
	public function uriKey() {
		return 'claims-by-status';
	}
}
