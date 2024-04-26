<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\BelongsTo;
use App\Jobs\TracksNextFromLens;

class PendingExport extends Lens
{
    public $name = 'Pending Export';

    public static function query(LensRequest $request, $query)
    {
        $query = $request->withOrdering($request->withFilters(
            $query
                ->with(['transaction', 'application','applicant'])
                ->approved()
                ->whereNotAncient()
                ->pendingExport()
                ->orderByTransactionDate('desc')
        ));

        rescue( function () use ($query) {
            TracksNextFromLens::saveLensData($query, get_called_class() );
        });


        return $query;
    }

    public function fields(Request $request)
    {
        return [
            ID::make('ID', 'id')->sortable(),

            Text::make('Applicant', function() {
                # The export action fails because this closure is called by nova when it should not be
                # and when it is called, the applicant is null because it's called from the wrong context
                if (empty($this->applicant)) {
                    return;
                }
                return '<a class="no-underline dim text-primary font-bold" href="/admin/resources/claims/'.$this->id.'">'.$this->applicant->full_name.'</a>';
            })->asHtml()->onlyOnIndex(),

            BelongsTo::make('Application', 'application', 'App\Nova\Application'),

            BelongsTo::make('Transaction', 'transaction', 'App\Nova\ClaimTransaction')->nullable(),

            DateTime::make('Claim Approved On', 'approved_on')->format('MMM D, Y h:mma'),

            Currency::make('Awarded', 'amount_awarded')->currency('USD')->nullable(),
        ];
    }

    public function filters(Request $request)
    {
        return [];
    }
}
