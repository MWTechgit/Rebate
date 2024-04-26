<?php

namespace App\Nova\Filters;

use App\Claim;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ClaimStatus extends Filter {
	public $name = 'Status';

	public $component = 'select-filter';

	public function apply(Request $request, $query, $status) {
		return $query->hasStatus($status);
	}

	public function options(Request $request) {
		return [
			'New & Pending' => Claim::ST_NEW . '|' . Claim::ST_PENDING_REVIEW,
			'New' => Claim::ST_NEW,
			'Pending Review' => Claim::ST_PENDING_REVIEW,
			'Pending Fulfillment' => Claim::ST_PENDING_FULFILLMENT,
			'Expired' => Claim::ST_EXPIRED,
			'Unclaimed' => Claim::ST_UNCLAIMED,
			'Denied' => Claim::ST_DENIED,
			'Approved' => Claim::ST_PENDING_FULFILLMENT . '|' . Claim::ST_FULFILLED,
			'Fulfilled' => Claim::ST_FULFILLED,
		];
	}

	// /**
	//  * The default filter to apply
	//  */
	// public function default()
	// {
	//     return Claim::ST_NEW.'|'.Claim::ST_PENDING_REVIEW;
	// }
}
