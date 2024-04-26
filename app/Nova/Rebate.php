<?php

namespace App\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class Rebate extends Resource {
	public static $model = 'App\Rebate';

	public static $group = 'Settings';

	public static $title = 'name';

	public static $search = ['name'];

	public static $searchRelations = [
		'partner' => ['name', 'account_key'],
	];

	public static $globallySearchable = false;

	public static function getDefaultOrderBy(NovaRequest $request, $query) {
		return $query->orderByDesc('fy_year')->orderBy('id');
	}

	public function fields(Request $request) {
		return [
			ID::make()->sortable(),

			BelongsTo::make('RebateType')
				->onlyWhenCreating(),

			BelongsTo::make('Partner')
				->onlyWhenCreating()
				->sortable()
				->rules('required'),

			Text::make('Partner', 'partner.name')
				->exceptOnForms(),

			Text::make('Partner Key', 'partner.account_key')
				->onlyOnDetail(),

			Text::make('Name', function () {
				return Str::limit($this->name, 10);
			})
				->onlyOnIndex(),

			Text::make('Name')
				->hideFromIndex()
				->rules('required', 'string')
				->withMeta(['value' => $this->name ?? 'HET Toilet Rebate']),

			Text::make('Description')
				->rules('nullable')
				->hideFromIndex()
				->withMeta(['value' => $this->description ?? 'Full rebate']),

			Number::make('Fiscal Year', 'fy_year')
				->min(2000)->max(2100)->step(1)
				->sortable()
				->rules('required', 'integer', 'min:2000', 'max:2100')
				->withMeta(['value' => $this->fy_year ?? fiscal_year()+1])
				->onlyWhenCreating(),

			Number::make('Fiscal Year', 'fy_year')
				->metaReadOnly()
				->hideWhenCreating(),

			Number::make('Inventory')
				->min(0)->step(1)
				->rules('required', 'integer')
				->hideWhenUpdating()
				->help('The total number of toilets in this rebate'),

			Number::make('Inventory')
				->metaReadOnly() # Don't let admins change this, it messes up the count. Use the 'Change Rebate Inventory' action
				->onlyWhenUpdating()
				->help('The total number of toilets in this rebate. Run the "Change Rebate Inventory" action to change this.'),

			Number::make('Remaining')
			// ->min(0)->step(1)
			// ->rules('required', 'integer') # Don't let admins change this, it messes up the count. Use the 'Balance Rebate Inventory' action
				->metaReadOnly()
				->help('The number of unallocated (available) rebates from the inventory. Run the "Balance Rebate Inventory" action to change this.'),
			// ->hideWhenCreating(),

			Currency::make('Value')
				->currency('USD')
				->nullable()
				->rules('required', 'numeric')
				->withMeta(['value' => $this->value ?? 100])
				->help('The maximum value allowed per rebate'),

			Boolean::make('Balanced', function () {
				return $this->totalToiletsInApplications() + $this->remaining === $this->inventory;
			}),

			Number::make('Used', function () {
				return $this->used . ' <span class="text-xs text-80 leading-normal">(Should be ' . $this->totalToiletsInApplications() . ')</span>';
			})
				->asHtml()
				->onlyOnDetail(),

			DateTime::make('Created', 'created_at')
				->metaReadOnly()
				->sortable()
				->onlyOnIndex()
				->hideWhenCreating()
                ->format('MMM Y'),

			HasMany::make('Applications'),

		];
	}

	public function cards(Request $request) {
		return [
			(new \App\Nova\Metrics\HeldToiletsPerStatus)->onlyOnDetail(),
			(new \App\Nova\Metrics\RefusedToiletsPerRebate)->onlyOnDetail(),
			(new \App\Nova\Metrics\AwardedAveragePerRebate)->onlyOnDetail(),

		];
	}

	public function filters(Request $request) {
		return [
			new \App\Nova\Filters\RebatePartner,
			new \App\Nova\Filters\FiscalYear,
		];
	}

	public function lenses(Request $request) {
		return [];
	}

	public function actions(Request $request) {
		return [
			(new \App\Nova\Actions\BalanceRebateInventory)->canSee(function ($request) {
				return $request->user()->canWrite();
			}),
			(new \App\Nova\Actions\ChangeRebateInventory)->canSee(function ($request) {
				return $request->user()->canWrite();
			}),
		];
	}
}
