<?php

namespace App\Nova\Lenses;

use App\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\BelongsTo;
use App\Jobs\TracksNextFromLens;

class ApprovedClaims extends Lens
{
    public $name = 'Approved';

    public static function query(LensRequest $request, $query)
    {
        $query = $request->withOrdering($request->withFilters(
            $query
                ->with(['transaction','application','applicant'])
                ->approved()
                ->orderByTransactionDate('desc')
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

            BelongsTo::make('Transaction', 'transaction', 'App\Nova\ClaimTransaction')->nullable(),

            Boolean::make('Fullfilled', function () {
                return $this->status === Claim::ST_FULFILLED;
            })->hideWhenUpdating(),

            DateTime::make('Approved On', 'approved_on')->format('MMM D, Y h:mma'),

            Currency::make('Awarded', 'amount_awarded')->currency('USD')->nullable()
        ];
    }

    public function filters(Request $request)
    {
        return [
            new \App\Nova\Filters\ApprovedClaimStatus,
            new \App\Nova\Filters\ReferenceType,
            new \App\Nova\Filters\Claims\ApprovedAfter,
            new \App\Nova\Filters\DateClaimSubmitted,
            new \App\Nova\Filters\FiscalYear
        ];
    }

}
