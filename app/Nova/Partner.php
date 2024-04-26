<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;

class Partner extends Resource
{
    public static $model = 'App\Partner';

    public static $group = 'People';

    public static $title = 'name';

    public static $search = ['name'];

    public static $globallySearchable = false;

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('name')
                ->rules('required', 'max:60'),

            Text::make('description')
                ->rules('nullable')
                ->hideFromIndex(),

            Text::make('Account Key')
                ->rules('required', 'size:3')
                ->creationRules('unique:partners,account_key')
                ->hideWhenUpdating()
                ->hideFromDetail()
                ->hideFromIndex(),

            Text::make('Account Key')
                ->updateRules('unique:partners,account_key,{{resourceId}}')
                ->metaReadOnly()
                ->hideWhenCreating(),

            BelongsTo::make('Partner', 'parent')
                ->help("If this partner will be distributing rebates under another partner and have its rebate count subtracted from another partner select the other partner.")
                ->nullable(),

            HasMany::make('Rebates'),
        ];
    }

    public function cards(Request $request)
    {
        return [];
    }

    public function filters(Request $request)
    {
        return [];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [];
    }
}
