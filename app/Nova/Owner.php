<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;

class Owner extends Resource
{
    public static $model = 'App\Owner';

    public static $group = 'Applications';

    public static $title = 'name';

    public static $search = ['id'];

    public static $with = ['property'];

    public static $globallySearchable = false;

    public static $displayInNavigation = false;

    public function fields(Request $request)
    {
        return [
            Text::make('Application', function() {
                if (!isset($this->property)) return '';

                $id = $this->application()->id;
                $name = $this->application()->rebate_code;

                return trim('
                    <a class="no-underline font-bold dim text-primary"
                        href="'.config('nova.path').'/resources/applications/'.$id.'">'.$name.'</a>
                ');
            })->canSee(function($request) {
                return isset($this->property);
            })->asHtml()->onlyOnDetail(),

            // Using belongs to instead of has one for user experience
            BelongsTo::make('Address', 'address', 'App\Nova\Address')
                ->onlyOnDetail(),

            Text::make('First Name'),

            Text::make('Last Name'),

            Text::make('Email')
                ->sortable()
                ->rules('email', 'max:254')
                ->onlyOnForms(),

            Text::make('Email', function() {
                return $this->email ? '<a href="mailto:'.$this->email.'">'.$this->email.'</a>' : '';
            })
                ->sortable()
                ->asHtml()
                ->exceptOnForms(),

            Text::make('Company'),

            Text::make('Phone'),

            Text::make('Mobile'),
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
