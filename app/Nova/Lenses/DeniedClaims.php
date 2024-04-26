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
use App\Jobs\TracksNextFromLens;

class DeniedClaims extends Lens
{
    public $name = 'Denied';

    public static function query(LensRequest $request, $query)
    {
        $query = $request->withOrdering($request->withFilters(
            $query
                ->with(['applicant', 'application','transaction'])
                ->denied()
                ->orderByTransactionDate('desc')
        ));

        rescue( function () use ($query) {
            TracksNextFromLens::saveLensData($query, get_called_class(), 'claims.id' );
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

            BelongsTo::make('Transaction', 'transaction', 'App\Nova\ClaimTransaction')->nullable(),

            DateTime::make('Claim Denied On', 'denied_on')->format('MMM D, Y h:mma'),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new \App\Nova\Filters\DateClaimSubmitted,
            new \App\Nova\Filters\FiscalYear
        ];
    }

}
