<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Address extends Resource {

	public static $model = 'App\Address';

	public static $group = 'Applications';

	public static $title = 'full';

	public static $globallySearchable = false;

	public static $displayInNavigation = false;

	public function fields(Request $request) {
		return [

			$this->when(($application = optional($this->addressable)->getApplication()), function () use ($application) {
				return Heading::make(optional($application->applicant)->full_name . ', ' . trim('
                    <a class="no-underline font-bold dim text-primary"
                        href="' . config('nova.path') . '/resources/applications/' . $application->id . '">' . $application->rebate_code . '</a>
                '))->asHtml();
			}),

			# Address could belong to any of the following
			# - Owner
			# - Property
			# - UtilityAccount
			# - Application
			Text::make('Parent Resource', function () {
				
				if (!$this->addressable_type || !$this->addressable_id) {
					return;
				}

				$modelName = class_basename($this->addressable_type);
				$resource = 'App\Nova\\' . $modelName;
				$uriKey = $resource::uriKey();

				return trim('
                    <a class="no-underline font-bold dim text-primary"
                        href="' . config('nova.path') . '/resources/' . $uriKey . '/' . $this->addressable_id . '">' . $modelName . '</a>
                ');
			})->onlyOnDetail()->asHtml(),

			Text::make('Address Line 1', 'line_one')
				->rules('required'),

			Text::make('Address Line 2', 'line_two'),

			Text::make('City', 'city')
				->rules('required'),

			Text::make('State', 'state')
				->rules('required'),

			Text::make('Postal Code', 'postcode')
				->rules('required'),
		];
	}
}