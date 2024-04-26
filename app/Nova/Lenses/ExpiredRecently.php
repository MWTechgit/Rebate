<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use App\Jobs\TracksNextFromLens;

class ExpiredRecently extends Lens
{
    public static function query(LensRequest $request, $query)
    {
        $query = $request->withOrdering($request->withFilters(
            $query
                ->expiredRecently()
                ->orderBy('claims.expired_at', 'desc')
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

            Text::make('Status')->hideWhenUpdating(),

            DateTime::make('Expired On', 'expired_at')->format('MMM D, Y'),
        ];
    }

    public function uriKey()
    {
        return 'expired-recently';
    }

    public function filters(Request $request)
    {
        return [
            new \App\Nova\Filters\ClaimStatus,
        ];
    }
}
