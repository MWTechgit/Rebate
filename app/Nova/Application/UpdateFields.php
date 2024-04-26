<?php

namespace App\Nova\Application;

use App\Nova\GroupDisplayMethods;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphOne;

trait UpdateFields
{
    public function getUpdateFields(Request $request)
    {
        $fields = [
            # =====================================
            # Application (self)
            # =====================================
            Heading::make('Application Details'),

            Select::make('Status')
                ->options(static::STATUS_OPTIONS)
                ->displayUsingLabels()
                ->rules('required', 'in:new,pending-review'),

            Boolean::make('Sp. Attn Notification Sent', 'notification_sent')
                ->rules('required'),

            DateTime::make('Notification Sent At')
                ->format('MMM D, Y h:mma'),

            DateTime::make('Submitted At', 'created_at')
                ->rules('required')
                ->format('MMM D, Y h:mma'),

            Text::make('Rebate Count')
                ->metaReadOnly()
                ->help('Use the <b>Change Rebate Count</b> action to change this.'),

            Text::make('Desired Rebate Count')
                ->rules('required'),

            Text::make('Submission Type')
                ->help('How was the application submitted? e.g. online, mail, email, phone'),
        ];

        return $this->onlyWhenUpdating($fields);
    }
}