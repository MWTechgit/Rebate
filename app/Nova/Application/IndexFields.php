<?php

namespace App\Nova\Application;

use App\Nova\GroupDisplayMethods;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

trait IndexFields {
	use GroupDisplayMethods;

	public function getIndexFields() {
		return $this->onlyOnIndex([
			ID::make()->sortable(),

			Text::make('Applicant', function () {
				if (empty($this->applicant)) {
					return;
				}

				return '<a class="no-underline dim text-primary font-bold" href="/admin/resources/applications/' . $this->id . '">' . $this->applicant->full_name . '</a>';
			})->asHtml(),

			Text::make('Application Number', 'rebate_code')
				->metaReadOnly(),

			Select::make('Status')
				->options(static::STATUS_OPTIONS)
				->displayUsingLabels(),

			DateTime::make('Submitted At', 'created_at')
				->format('MMM D, Y h:mma')
				->sortable(),
		]);
	}
}
