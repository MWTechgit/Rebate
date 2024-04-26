<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Illuminate\Http\Request;

class Applicant extends Resource
{
    public static $model = 'App\Applicant';

    public static $group = 'People';

    public static $title = 'full_name';

    public static $search = ['full_name', 'email'];

    public static $globallySearchable = false;

    public static $displayInNavigation = false;

    public function fields(Request $request)
    {
        return [
            // Relation is actually HasOne but BelongsTo puts the link at the top
            // of the detail page which makes more sense for our use case.
            BelongsTo::make('Application', 'application', 'App\Nova\Application')
                ->onlyOnDetail(),

            Text::make('First Name')
                ->sortable()
                ->hideFromIndex()
                ->rules('required', 'max:255'),

            Text::make('Last Name')
                ->sortable()
                ->hideFromIndex()
                ->rules('required', 'max:255'),

            Text::make('Name', function() {
                    return $this->full_name;
                })
                ->sortable()
                ->onlyOnIndex(),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->onlyOnForms(),

            Text::make('Email', function() {
                return $this->email ? '<a href="mailto:'.$this->email.'">'.$this->email.'</a>' : '';
            })
                ->sortable()
                ->asHtml()
                ->exceptOnForms(),

            Text::make('Phone')
                ->rules('required')
                ->hideFromIndex(),

            Text::make('Mobile')
                ->hideFromIndex(),

            Boolean::make('E-Mail Opt-In', 'email_opt_in')
                ->rules('required')
                ->hideFromIndex(),

            Boolean::make('Feature On Water Saver')
                ->rules('required')
                ->hideFromIndex(),

            Text::make('Reference Type', function() {
                return $this->reference ? $this->reference->source() : '';
            })->onlyOnDetail(),

            Text::make('Reference Info', function() {
                return $this->reference ? $this->reference->description() : '';
            })->onlyOnDetail(),

            Text::make('WaterSense Reason', function() {
                return optional($this->watersense)->reason ?: '';
            })->onlyOnDetail()
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
