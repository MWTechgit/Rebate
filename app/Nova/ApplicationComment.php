<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;

class ApplicationComment extends Resource
{
    public static $model = 'App\ApplicationComment';

    public static $group = 'Applications';

    public static $with = ['admin', 'application'];

    public static $title = 'id';

    public static $search = false;

    public static $globallySearchable = false;

    public static $displayInNavigation = false;

    public static function label()
    {
        return 'Comments';
    }

    public static function singularLabel()
    {
        return 'Comment';
    }

    public static function getDefaultOrderBy(NovaRequest $request, $query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function fields(Request $request)
    {
        return [
            BelongsTo::make('Admin')
                ->onlyOnDetail(),

            BelongsTo::make('Application')
                ->hideWhenUpdating()
                ->hideWhenCreating(),

            DateTime::make('Posted On', 'created_at')
                ->format('MMM D, Y h:mma')
                ->onlyOnDetail(),

            Text::make('Content')->resolveUsing(function($text) {
                $max = 120;
                if (strlen($text) >= $max) return substr($text, 0, $max).'...';
                return $text;
            })->onlyOnIndex(),

            Textarea::make('Content')
                # Hidden from index by default
                ->alwaysShow()
                ->rules('required'),
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
