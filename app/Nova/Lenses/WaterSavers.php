<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;

class WaterSavers extends Lens
{
    public static function defaultOrderBy(LensRequest $request, $query): void
    {
        $query->orderBy('applications.created_at', 'desc');
    }

    public static function applyFilters(LensRequest $request, $query): void
    {
        $query->featuredOnWaterSaver();
    }

    public function fields(Request $request)
    {
        return [
            ID::make('ID', 'id'),

            Text::make('Applicant', function() {
                return '<a class="no-underline dim text-primary font-bold" href="/admin/resources/applications/'.$this->id.'">'.$this->applicant->full_name.'</a>';
            })->asHtml()->onlyOnIndex(),

            Text::make('Status')->sortable(),

            DateTime::make('Submitted On', 'created_at')->format('MMM D, Y')->sortable(),

            Boolean::make('Feature', 'applicant.feature_on_water_saver'),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new \App\Nova\Filters\ApplicationStatus,
            new \App\Nova\Filters\ApplicationPartner,
            new \App\Nova\Filters\FiscalYear,
            new \App\Nova\Filters\DateApplicationSubmitted
        ];
    }

}
