<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;

class RebateType extends Resource
{
    public static $model = 'App\RebateType';

    public static $group = 'Settings';

    public static $title = 'name';

    public static $search = ['name'];

    public static $globallySearchable = false;

    public static function label()
    {
        return 'Rebate Types';
    }

    public static function singularLabel()
    {
        return 'Rebate Type';
    }

    public function fields(Request $request)
    {
        return [
            Text::make('name')
                ->rules('required', 'max:100'),

            Date::make('created_at')->format('MMM D, Y')
                ->onlyOnIndex(),
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
