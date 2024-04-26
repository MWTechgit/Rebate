<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;

class ApplicationTransaction extends Resource
{
    public static $model = 'App\ApplicationTransaction';

    public static $group = 'Applications';

    public static $with = ['application'];

    public static $title = 'type';

    public static $search = ['rebate_code'];

    public static $searchRelations = [
        'application' => ['rebate_code'],
    ];

    public static $globallySearchable = false;

    public static $displayInNavigation = false;

    public static function label()
    {
        return 'Transactions';
    }

    public static function singularLabel()
    {
        return 'Transaction';
    }

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Type'),

            Textarea::make('Message', 'description')->alwaysShow(),

            BelongsTo::make('Admin'),
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
