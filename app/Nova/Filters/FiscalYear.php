<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class FiscalYear extends Filter {

	public function options(Request $request) {

		$years = range(2011, fiscal_year() + 1);
		return array_combine($years, $years);

	}

	public function apply(Request $request, $query, $year) {

		return $query->where('fy_year', $year);
	}

}
