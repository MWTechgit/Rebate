<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;

/**
 * Tie remittance address directly to an application
 *
 * Address is polymorphic.
 * The default address could belong to several other models.
 *
 * We need to keep a link directly back to the application
 * on the remittance address detail page.
 *
 * A link to the remittance address edit page is displayed on the
 * Application detail page if the app has a remittance address.
 */
class RemittanceAddress extends Resource {
	public static $model = 'App\Address';

	public static $group = 'Applications';

	public static $title = 'id';

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

			MorphTo::make('Application', 'addressable', 'App\Nova\Application')
				->onlyOnDetail(),

			Text::make('Address Line 1', 'line_one'),
			Text::make('Address Line 2', 'line_two'),
			Text::make('City', 'city'),
			Text::make('State', 'state'),
			Text::make('Postal Code', 'postcode'),
		];
	}
}