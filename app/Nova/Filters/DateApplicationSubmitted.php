<?php

namespace App\Nova\Filters;

use Ampeco\Filters\DateRangeFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DateApplicationSubmitted extends DateRangeFilter {

	public $name = 'Application Submitted';

	public function apply(Request $request, $query, $value) {

		$from = Carbon::parse($value[0])->startOfDay();
		$to   = Carbon::parse($value[1])->endOfDay();

        return $query->whereBetween('applications.created_at', [$from, $to]);
	}

}
