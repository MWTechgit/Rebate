<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Property extends Resource {
	public static $model = 'App\Property';

	public static $group = 'Applications';

	public static $title = 'id';

	public static $with = ['address', 'application.applicant'];

	public static $globallySearchable = false;

	public static $displayInNavigation = false;

	public function fields(Request $request) {
		return [

			$this->when(optional($this->application)->applicant, function () {
				return Heading::make(optional($this->application->applicant)->full_name . ', ' . trim('
                    <a class="no-underline font-bold dim text-primary"
                        href="' . config('nova.path') . '/resources/applications/' . $this->application->id . '">' . $this->application->rebate_code . '</a>
                '))->asHtml();
			}),

			DateTime::make('Updated At')->metaReadOnly(),

			Select::make('Property Type')
				->options(array_combine(static::$model::PROPERTY_TYPES, static::$model::PROPERTY_TYPES)),

			Select::make('Building Type')
				->options(array_combine(static::$model::BUILDING_TYPES, static::$model::BUILDING_TYPES)),

			Text::make('Subdivision/Development', 'subdivision_or_development'),

			Text::make('Bathrooms'),

			Text::make('Toilets'),

			Text::make('Full Bathrooms'),

			Text::make('Half Bathrooms'),

			Text::make('Year Built'),

			Text::make('Original Toilet'),

			Text::make('Gallons Per Flush'),

			Text::make('Occupants')
				->rules('required'),

			Text::make('Years Lived')
				->rules('required'),

			HasOne::make('Utility Account', 'utilityAccount')->onlyOnDetail(),
		];
	}
}