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

class DeniedApplications extends Lens
{
    public $name = 'Denied';

    public static function query(LensRequest $request, $query)
    {
        $query = $request->withOrdering($request->withFilters(
            $query
                ->with('transaction')
                ->denied()
                ->orderByTransactionDate('desc')
        ));

        rescue( function () use ($query) {
            TracksNextFromLens::saveLensData($query, get_called_class(), 'applications.id');
        });

        return $query;
    }

    public function fields(Request $request)
    {
        return [
            ID::make('ID', 'id'),

            Text::make('Applicant', function() {
                return '<a class="no-underline dim text-primary font-bold" href="/admin/resources/applications/'.$this->id.'">'.$this->applicant->full_name.'</a>';
            })->asHtml()->onlyOnIndex(),

            Text::make('Application Number', 'rebate_code'),

            BelongsTo::make('Transaction', 'transaction', 'App\Nova\ApplicationTransaction')->nullable(),

            DateTime::make('Denied On', 'denied_on')->format('MMM D, Y h:mma'),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new \App\Nova\Filters\FiscalYear,
            new \App\Nova\Filters\DateApplicationSubmitted
        ];
    }

}
