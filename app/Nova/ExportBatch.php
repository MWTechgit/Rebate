<?php

namespace App\Nova;

use App\Nova\Actions\ExportClaimPropertyList;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ExportBatch extends Resource {
	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = 'App\ExportBatch';

	public static $group = 'Claims';

	public static $globallySearchable = false;

	protected $withCount = [
		'claims',
	];

	public static function getDefaultOrderBy(NovaRequest $request, $query) {
		return $query->orderBy('created_at', 'desc');
	}

	// // Overwrite the indexQuery to include relationship count
	// public static function indexQuery(NovaRequest $request, $query)
	// {
	//     // Give relationship name as alias else Laravel will name it as comments_count
	//     return $query->withCount('comments as comments');
	// }

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'id';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function fields(Request $request) {
		return [
			ID::make()->sortable(),

			BelongsTo::make('Admin'),

			DateTime::make('Exported At', 'created_at')
				->format('MMM D, Y h:mma')
				->sortable(),

			Number::make('Claims', 'claims_count')
				->sortable()
				->onlyOnIndex()
				->metaReadOnly(),

			BelongsToMany::make('Claims')->searchable(),

			Text::make('Filename')
				->hideFromIndex()
				->hideFromDetail(),

			Text::make('Download', function () {
				return trim('
                    <a class="no-underline font-bold dim text-primary" href="' .
					route('export_batches.download', $this) . '" target="_blank">' . ($this->filename ?? 'Download') . '</a>
                ');
			})->asHtml()->metaReadOnly(),

		];
	}

	/**
	 * Get the cards available for the request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function cards(Request $request) {
		return [];
	}

	/**
	 * Get the filters available for the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function filters(Request $request) {
		return [];
	}

	/**
	 * Get the lenses available for the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function lenses(Request $request) {
		return [];
	}

	/**
	 * Get the actions available for the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function actions(Request $request) {
		return [
			(new ExportClaimPropertyList)
				->canSee(function ($request) {
					return $request->user()->canWrite();
				}),
		];
	}
}
