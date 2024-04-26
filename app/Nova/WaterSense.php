<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Http\Request;

class WaterSense extends Resource
{
    public static $model = 'App\WaterSense';

    public static $group = 'Other';

    // public static $title = 'reason';

    // public static $search = ['reason', 'email'];

    public static $globallySearchable = false;

    public static $displayInNavigation = false;

    public function fields(Request $request)
    {
        return [
            Text::make('name')
                ->rules('required', 'max:100'),

            Date::make('created_at')->format('MMM D, Y')
                ->onlyOnIndex(),

            BelongsTo::make('Applicant', 'applicant', 'App\Nova\Applicant')
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
