<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;

class User extends Resource
{
    public static $model = 'App\User';

    public static $group = 'People';

    public static $globallySearchable = false;

    public static $displayInNavigation = false;

    public function fields(Request $request)
    {
        return [
        ];
    }
}