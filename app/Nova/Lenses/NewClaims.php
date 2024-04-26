<?php

namespace App\Nova\Lenses;

use App\Claim;
use App\Jobs\TracksNextFromLens;
use App\Nova\Filters\ClaimStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;

class NewClaims extends Lens
{
    public $name = 'New Claims';

    public static function query(LensRequest $request, $query)
    {
        $query = $request->withOrdering($request->withFilters(
            $query
                ->with('application')
                ->hasStatus(Claim::ST_NEW.'|'.Claim::ST_PENDING_REVIEW)
        ));

        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            $query->orderBy('claims.submitted_at', 'desc');
        }

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

            DateTime::make('Submitted', 'submitted_at')
                ->format('MMM D, Y h:mma')
                ->sortable(),

            Text::make('Status', 'status')->hideWhenUpdating(),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new \App\Nova\Filters\NewClaimStatus,
            new \App\Nova\Filters\ReferenceType,
            new \App\Nova\Filters\DateClaimSubmitted,
            new \App\Nova\Filters\FiscalYear
        ];
    }

}
