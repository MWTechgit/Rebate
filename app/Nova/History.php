<?php

namespace App\Nova;

use App\History as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class History extends Resource {

	public static $model = 'App\History';

	public static $title = 'full_name';

	public static $displayInNavigation = false;

	public static $search = ['line_one', 'line_two', 'city', 'state', 'postcode', 'account_number', 'full_name', 'email', 'address_index'];

	public function fields(Request $request) {

		return [
			ID::make()->sortable()->onlyOnIndex(),

			Text::make('Full Name')
                ->sortable()
                ->rules('required', 'max:255'),

             Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254'),

            BelongsTo::make('Application', 'application', 'App\Nova\Application'),

            Text::make('Partner'),

            Text::make('Account Number'),

			Text::make('Address Line 1', 'line_one')
				->rules('required'),

			Text::make('Address Line 2', 'line_two'),

			Text::make('City', 'city')
				->rules('required'),

			Text::make('State', 'state')
				->rules('required'),

			Text::make('Postal Code', 'postcode')
				->rules('required'),

			Text::make('Status'),

			Date::make('Submitted At')
				->format('MMM D, Y'),

		];
	}

	public function title() {
		return "Archive: " . $this->full_name;
	}

	/**
	 * The global search result subtitle for the resource.
	 */
	public function subtitle() {
		return  '<' . $this->email . '>, ' . $this->line_one . ' ' . $this->line_two . ', ' . $this->city;
	}

	public function fullPageSearchTitle() {
		return $this->rebate_code . " --- " . $this->full_name;
	}

	public function fullPageSearchSubtitle() {
		return $this->line_one . ' ' . $this->line_two . ', ' . $this->city . ' ' . $this->state . ' ' . $this->postcode;
	}

	public static function quickAudit($string, $ignoreId)
	{
		$query = Model::where('application_id', '!=', $ignoreId);
		return static::applySearch($query, $string);
	}

	public static function applySearchToQuery($query, $string)
	{
		return static::applySearch($query, $string);
	}

	/**
	 * Detetermine whether the global search links will take the user to the detail page.
	 *
	 * @param \Laravel\Nova\Http\Requests\NovaRequest $request
	 *
	 * @return string
	 */
	public function globalSearchLink(NovaRequest $request)
	{
	    return $this->application_id ? '/admin/resources/applications/' . $this->application_id : null;
	}
}
