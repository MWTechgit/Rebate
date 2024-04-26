<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\DateTime;

class ClaimTransaction extends Resource
{
    public static $model = 'App\ClaimTransaction';

    public static $group = 'Claims';

    public static $with = ['claim'];

    public static $title = 'type';

    public static $search = ['id'];

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
