<?php

namespace App\Nova\Filters\Claims;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;

class ApprovedAfter extends DateFilter {
	public $name = 'Approved After';

	public function apply(Request $request, $query, $value) {
		$value = Carbon::parse($value);

		return $query->whereHas('transaction', function ($query) use ($value) {
			$query->where('claim_transactions.created_at', '>=', Carbon::parse($value));
		});
	}

	public function default() {
		return Carbon::parse('-1 year')->toDateTimeString();
	}
}
