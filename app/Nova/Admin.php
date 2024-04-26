<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;

class Admin extends Resource
{
    public static $model = 'App\Admin';

    public static $group = 'People';

    public static $title = 'full_name';

    public static $search = [
        'full_name', 'email', 'company',
    ];

    public static $globallySearchable = false;

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Select::make('Role')
                ->options([
                    \App\Admin::WRITE => 'Admin',
                    \App\Admin::READ  => 'Call Center Operator',
                ])
                ->canSee(function($request) {
                    return $request->user()->canWrite();
                })
                ->rules('required'),

            Text::make('First Name')
                ->sortable()
                ->hideFromIndex()
                ->rules('required', 'max:45'),

            Text::make('Last Name')
                ->sortable()
                ->hideFromIndex()
                ->rules('required', 'max:45'),

            Text::make('Name', 'full_name')
                ->sortable()
                ->onlyOnIndex(),                

            Text::make('Email', function() {
                return $this->email ? '<a href="mailto:'.$this->email.'">'.$this->email.'</a>' : '';
            })
                ->sortable()
                ->asHtml()
                ->exceptOnForms(),

            Text::make('Email')
                ->sortable()
                ->onlyOnForms()
                ->rules('required', 'email', 'max:100')
                ->creationRules('unique:admins,email')
                ->updateRules('unique:admins,email,{{resourceId}}'),

            Text::make('Company')
                ->sortable()
                ->rules('required', 'max:120'),

            Text::make('Phone')
                ->rules('nullable', 'max:25')
                ->hideFromIndex(),

            Boolean::make('Receive alerts')
                ->rules('required')
                ->hideFromIndex(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6')
                ->canSee(function($request) {
                    return $request->user()->canWrite();
                }),
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
