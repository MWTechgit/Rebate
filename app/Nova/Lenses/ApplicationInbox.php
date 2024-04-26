<?php

namespace App\Nova\Lenses;

use App\Jobs\TracksNextFromLens;
use App\Nova\Application;
use App\Nova\Lenses\Lens;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;

/**
 * This lens only exists so we can apply
 * a default filter to the applications that
 * show up here, without that filter applying
 * to the "HasOne" view of the application when
 * viwing the Applicant detail page.
 *
 * If the filter is global, you would only see "new & pending"
 * applications by default so the application "hasOne" box on the
 * applicant detail page would be empty until you change the filter
 * if the application is not "new or pending"
 */
class ApplicationInbox extends Lens {
	public $name = 'Application Inbox';

	public static function query(LensRequest $request, $query) {
		$query = $request->withOrdering($request->withFilters(
			$query->hasStatus('new|pending-review')
		));

		if (empty($request->get('orderBy'))) {
			$query->getQuery()->orders = [];
			$query->orderByRaw("FIELD(applications.status, 'new', 'pending-review'), applications.created_at desc");
		}

		rescue(function () use ($query) {
			TracksNextFromLens::saveLensData($query, get_called_class(), 'applications.id');
		});

		return $query;
	}

	public function fields(Request $request): array
	{
		return [
			ID::make()->sortable(),

			Text::make('Partner', 'rebate.partner.name')
				->onlyOnIndex(),

			Text::make('Applicant', function () {
				if (empty($this->applicant)) {
					return;
				}

				return '<a class="no-underline dim text-primary font-bold" href="/admin/resources/applications/' . $this->id . '">' . $this->applicant->full_name . '</a>';
			})->asHtml(),

			Text::make('Application Number', 'rebate_code')
				->metaReadOnly(),

			Select::make('Status')
				->options(Application::STATUS_OPTIONS)
				->displayUsingLabels(),

			DateTime::make('Submitted At', 'created_at')
				->format('MMM D, Y h:mma')
				->sortable(),

			Text::make('Fiscal Year', 'fy_year')
				->metaReadOnly(),
		];
	}

	public function filters(Request $request) {
		return [
			new \App\Nova\Filters\NewApplicationStatus,
			new \App\Nova\Filters\ApplicationPartner,
			new \App\Nova\Filters\FiscalYear,
			new \App\Nova\Filters\DateApplicationSubmitted

		];
	}

	public function uriKey() {
		return 'application-inbox';
	}
}
