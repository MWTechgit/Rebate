<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\BelongsTo;

class ApprovedApplications extends Lens
{
    public $name = 'Approved';

    public static function defaultOrderBy(LensRequest $request, $query): void
    {
        $query->orderByTransactionDate('desc');
    }

    public static function applyFilters(LensRequest $request, $query): void
    {
        $query->with('claim', 'transaction')->approved();
    }

    public function fields(Request $request)
    {
        return [
            ID::make('ID', 'id'),

            Text::make('Applicant', function() {
                return '<a class="no-underline dim text-primary font-bold" href="/admin/resources/applications/'.$this->id.'">'.$this->applicant->full_name.'</a>';
            })->asHtml(),

            Text::make('Application Number', 'rebate_code'),

            BelongsTo::make('Transaction', 'transaction', 'App\Nova\ApplicationTransaction')->nullable(),

            Text::make('Claim Status', function () {
                return optional($this->claim)->status;
            }),

            Text::make('Fiscal Year', 'fy_year')
                ->metaReadOnly(),

            DateTime::make('Approved On', 'approved_on')->format('MMM D, Y h:mma'),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new \App\Nova\Filters\ApplicationPartner,
            new \App\Nova\Filters\FiscalYear,
            new \App\Nova\Filters\DateApplicationSubmitted
        ];
    }

}
