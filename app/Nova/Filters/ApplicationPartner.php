<?php

namespace App\Nova\Filters;

use App\Partner;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ApplicationPartner extends Filter {

	public function options(Request $request) {

		return Partner::pluck('id', 'name')->toArray();

	}

	public function apply(Request $request, $query, $partnerId) {

		return $query->whereHas('rebate.partner', function ($query) use ($partnerId) {
			return $query->where('partners.id', $partnerId);
		});
	}

}
