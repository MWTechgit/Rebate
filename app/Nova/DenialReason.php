<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;

class DenialReason extends Resource
{
    public static $model = 'App\DenialReason';

    public static $group = 'Settings';

    public static $title = 'reason';

    public static $search = ['reason'];

    public static $globallySearchable = false;

    public static function label()
    {
        return 'Denial Reasons';
    }

    public static function singularLabel()
    {
        return 'Denial Reason';
    }

    public function fields(Request $request)
    {
        return [
            Select::make('Type')->options([
                'application' => 'Application',
                'claim' => 'Claim',
            ])->rules('required', 'string')
                ->sortable(),

            Text::make('reason')
                ->rules('required', 'string'),

            Textarea::make('message')
                ->alwaysShow()
                ->rules('required')
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
