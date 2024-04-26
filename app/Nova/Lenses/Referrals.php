<?php

namespace App\Nova\Lenses;

use App\Jobs\TracksNextFromLens;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;

class Referrals extends Lens {
	public static function query(LensRequest $request, $query) {
		# Get the approved applications that have a reference
		$query = $request->withOrdering($request->withFilters(
			$query
				->with(['applicant.reference.type', 'application', 'transaction'])
				->has('applicant.reference.type')
				->select('claims.*')
				->approved()
		));

		rescue(function () use ($query) {
			TracksNextFromLens::saveLensData($query, get_called_class(), 'claims.id');
		});

		return $query;
	}

	public function fields(Request $request) {
		return [
			Text::make('Applicant', function () {
				return '<a class="no-underline dim text-primary font-bold" href="/admin/resources/claims/' . $this->id . '">' . $this->applicant->full_name . '</a>';
			})->asHtml()->onlyOnIndex(),

			BelongsTo::make('Application', 'application', 'App\Nova\Application'),

			Text::make('Reference Type', function () {
				return optional($this->applicant->reference)->source();
			}),

			Text::make('Reference Info', function () {
				return Str::limit(optional($this->applicant->reference)->description(), 35);
			}),

			Date::make('Approved On', 'approved_on')->format('MMM D, Y'),
		];
	}

	public function uriKey() {
		return 'referrals';
	}

	public function filters(Request $request) {
		return [
			new \App\Nova\Filters\ApprovedClaimStatus,
			new \App\Nova\Filters\ReferenceType,
		];
	}
}
