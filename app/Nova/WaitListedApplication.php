<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Titasgailius\SearchRelations\SearchesRelations;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;

class WaitListedApplication extends Resource
{
    public static $model = 'App\WaitListedApplication';

    public static $group = 'Applications';

    public static $with = ['applicant'];

    public static $title = 'rebate_code';

    public static $search = ['rebate_code'];

    public static $searchRelations = [
        'applicant' => ['full_name', 'email'],
    ];

    /**
     * The global search result subtitle for the resource.
     */
    public function subtitle()
    {
        return "Applicant: {$this->applicant->full_name}";
    }

    public static function label()
    {
        return 'Wait Listed';
    }

    public static function singularLabel()
    {
        return 'Wait Listed';
    }

    public function fullPageSearchTitle() {
        return $this->rebate_code . " --- " . optional($this->applicant)->full_name;
    }

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Applicant')->onlyOnIndex(),

            Text::make('Applicant', 'applicant.full_name')
                ->hideFromIndex()
                ->metaReadOnly(),

            Text::make('Application Number', 'rebate_code')->onlyOnIndex(),

            Text::make('Application Number', 'rebate_code')
                ->hideFromIndex()
                ->metaReadOnly(),

            Select::make('Status')->options([
                'Called' => 'Contacted by Phone',
                'E-Mailed' => 'Contacted by E-Mail',
                'Follow Up' => 'Follow Up',
            ])->rules('required', 'string'),

            DateTime::make('Submitted At', 'created_at')
                ->format('MMM D, Y h:mma')
                ->sortable(),
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
        return [
            new Actions\AcceptApplication,
            new Actions\SetStatus,
        ];
    }
}
