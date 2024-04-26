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
use Laravel\Nova\Fields\Boolean;
use App\Jobs\TracksNextFromLens;

class ExpiringSoon extends Lens
{
    public static function query(LensRequest $request, $query)
    {
        $query = $request->withOrdering($request->withFilters(
            $query->expiringSoon()
                ->unclaimed()
                ->orderBy('claims.expires_at', 'asc')
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

                return ($this->applicant??false) ?
                    '<a class="no-underline dim text-primary font-bold" href="/admin/resources/claims/'.$this->id.'">'.$this->applicant->full_name.'</a>' :
                    '';
            })->asHtml()->onlyOnIndex(),

            BelongsTo::make('Application', 'application', 'App\Nova\Application'),

            DateTime::make('Expires On', 'expires_at')
                ->format('MMM D, Y'),

            Text::make('Status')
                ->hideWhenUpdating()
                ->sortable(),

            Boolean::make('Notification Sent', 'expire_notification_sent'),
        ];
    }

    public function uriKey()
    {
        return 'expiring-soon';
    }

    public function filters(Request $request)
    {
        return [
            new \App\Nova\Filters\ClaimStatus,
        ];
    }
}
