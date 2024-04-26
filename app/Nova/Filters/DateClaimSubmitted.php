<?php

namespace App\Nova\Filters;

use Ampeco\Filters\DateRangeFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DateClaimSubmitted extends DateRangeFilter {

	public $name = 'Claim Submitted';

	public function apply(Request $request, $query, $value) {

        $from = Carbon::parse($value[0])->startOfDay();
        $to = Carbon::parse($value[1])->endOfDay();

        return $query->whereBetween('claims.submitted_at', [$from, $to]);
	}

}
