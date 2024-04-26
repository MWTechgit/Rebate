<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Currency;
use App\Jobs\TracksNextFromLens;

class Unclaimed extends Lens
{
    public $name = 'Unclaimed';

    public static function query(LensRequest $request, $query)
    {
        $query = $request->withOrdering($request->withFilters(
            $query
                ->with(['application', 'application.transaction'])
                ->unclaimed()
                ->orderByApplicationTransactionDate('desc')
        ));

        rescue( function () use ($query) {
            TracksNextFromLens::saveLensData($query, get_called_class(), 'claims.id');
        });

        return $query;
    }

    public function fields(Request $request)
    {
        return [
            ID::make('ID', 'id'),

            Text::make('Applicant', function() {
                return '<a class="no-underline dim text-primary font-bold" href="/admin/resources/claims/'.$this->id.'">'.$this->applicant->full_name.'</a>';
            })->asHtml()->onlyOnIndex(),

            BelongsTo::make('Application', 'application', 'App\Nova\Application'),

            Text::make('Application Status', 'application_status'),

            Text::make('Claim Status', 'status')->hideWhenUpdating(),

            DateTime::make('Application Approved On', 'application_approved_on')->format('MMM D, Y h:mma'),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new \App\Nova\Filters\ApprovedClaimStatus,
            new \App\Nova\Filters\DateClaimSubmitted,
            new \App\Nova\Filters\FiscalYear
        ];
    }
}
