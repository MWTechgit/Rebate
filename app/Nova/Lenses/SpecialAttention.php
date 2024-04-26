<?php

namespace App\Nova\Lenses;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;

/**
 * List applications that have been new
 * or pending review for more than 14 days.
 */
class SpecialAttention extends Lens
{
    public static function defaultOrderBy(LensRequest $request, $query): void
    {
        $query->orderBy('applications.created_at', 'asc');
    }

    public static function applyFilters(LensRequest $request, $query): void
    {
        $query->specialAttention();
    }

    public function fields(Request $request)
    {
        return [
            ID::make('ID', 'id')->sortable(),

            Text::make('Applicant', function() {
                return '<a class="no-underline dim text-primary font-bold" href="/admin/resources/applications/'.$this->id.'">'.$this->applicant->full_name.'</a>';
            })->asHtml(),

            Text::make('Application Number', 'rebate_code'),

            DateTime::make('Submitted On', 'created_at')->format('MMM D, Y')->sortable(),

            Text::make('Fiscal Year', 'fy_year')
                ->metaReadOnly(),

            # Special Attention Notification
            Boolean::make('Notification Sent'),

            Text::make('Sending', 'notification_status'),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new \App\Nova\Filters\NewApplicationStatus,
        ];
    }

}
