<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\Text;

class UtilityAccount extends Resource {
	public static $model = 'App\UtilityAccount';

	public static $group = 'Applications';

	public static $title = 'id';

	public static $globallySearchable = false;

	public static $displayInNavigation = false;

	public function fields(Request $request) {
		return [
			Text::make('Application', function () {
				if (!isset($this->property)) {
					return '';
				}

				$id = $this->getApplication()->id;
				$name = $this->getApplication()->rebate_code;

				return trim('
                    <a class="no-underline font-bold dim text-primary"
                        href="' . config('nova.path') . '/resources/applications/' . $id . '">' . $name . '</a>
                ');
			})->canSee(function ($request) {
				return isset($this->property);
			})->asHtml()->onlyOnDetail(),

			Text::make('Account Number'),

			MorphOne::make('Address')->onlyOnDetail(),
		];
	}
}