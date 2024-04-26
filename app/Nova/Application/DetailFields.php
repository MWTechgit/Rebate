<?php

namespace App\Nova\Application;

use App\Nova\GroupDisplayMethods;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Panel;

trait DetailFields
{
    protected function applicationFields(Request $request)
    {
        return $this->onlyOnDetail([
            Select::make('Status')
                ->options(static::STATUS_OPTIONS)
                ->displayUsingLabels(),

            Boolean::make('Sp. Attention Notification Sent', 'notification_sent'),

            DateTime::make('Notification Sent At')
                ->format('MMM D, Y h:mma'),

            DateTime::make('Submitted At', 'created_at')
                ->format('MMM D, Y h:mma'),

            Text::make('Referral Type', 'applicant.reference.type.type'),

            Text::make('Referral Source', 'applicant.reference.info_response'),
        ]);
    }

    protected function applicantFields(Request $request): array
    {
        return $this->onlyOnDetail([
            BelongsTo::make('Applicant'),

            Text::make('Application Number', 'rebate_code'),

            Text::make('Rebate Mail To Address', function() {
                # Nova bug, shouldn't have to check isset here.
                # Closures are being called on index/detail/edit/create
                # regardless of whether they are allowed to be displayed there.
                # There isn't a property if we are creating and getAddress needs one.
                if (isset($this->property)) {
                    return (string) $this->getMailToAddress();
                }
            }),

            $this->merge($this->remittanceAddress()),

            Text::make('Phone', 'applicant.phone'),

            Text::make('E-Mail', 'applicant.email'),

            Boolean::make('Feature On Water Saver', 'applicant.feature_on_water_saver'),
        ]);
    }

    /**
     * Some ugly isset stuff here due to nova bug.
     * Same bug as mentioned above.
     */
    protected function remittanceAddress()
    {
        return [
            Text::make('Edit Remittance Address', function() {
                $id = isset($this->property) && isset($this->address) ? $this->address->id : '';
                return trim('
                    <a
                        class="no-underline font-bold dim text-primary"
                        href="'.config('nova.path').'/resources/remittance-addresses/'.$id.'/edit"
                        >
                        Edit Remittance Address
                    </a>
                ');
            })->canSee(function($request) {
                return isset($this->property) && $this->hasRemittanceAddress();
            })->asHtml(),
        ];
    }

    protected function rebateFields(Request $request): array
    {
        return $this->onlyOnDetail([
            Text::make('Partner', 'rebate.partner.name'),

            Text::make('Name', 'rebate.name'),

            Text::make('Fiscal Year', 'rebate.fy_year'),

            Number::make('Applied For', 'rebate_count'),

            Number::make('Additional Desired', 'desired_rebate_count'),

            Text::make('Remaining', 'rebate.remaining'),
        ]);
    }

    protected function propertyFields(Request $request)
    {
        return $this->onlyOnDetail([
            Text::make('Property ID', 'property.id'),

            Text::make('Address', function() {
                if ($this->property) return (string) $this->property->address;
            }),

            Text::make('Water Utility Address', function() {
                # Have to check this because this function
                # is still called on the create page.
                # Nova should fix this.
                if ($this->property) return (string) $this->property->utilityAccount->address;
            }),

            Text::make('Water Account Number', 'property.utilityAccount.account_number'),

            Text::make('Property Type', 'property.property_type'),

            Text::make('Building Type', 'property.building_type'),

            Text::make('Full Baths', 'property.full_bathrooms'),

            Text::make('Half Baths', 'property.half_bathrooms'),

            Text::make('Year Built', 'property.year_built'),

            Text::make('Years Lived', 'property.years_lived'),

            Text::make('Residents', 'property.occupants'),

            Text::make('Original Toilet', 'property.original_toilet'),

            Text::make('Gallons Per Flush', 'property.gallons_per_flush'),

            Boolean::make('Own Residence?', function() {
                if ($this->property) return $this->property->ownedByApplicant();
            }),

            $this->linkToPropertyOwner(),
        ]);
    }

    protected function linkToPropertyOwner()
    {
        return Text::make('Property Owner', function() {

                if (!isset($this->property) || !isset($this->property->owner)) {
                    return '';
                }

                $id = $this->property->owner->id;
                $name = $this->property->owner->full_name;

                return trim('
                    <a class="no-underline font-bold dim text-primary"
                        target="_blank" href="'.config('nova.path').'/resources/owners/'.$id.'/edit">'.$name.' (edit)</a>
                ');

            })->canSee(function($request) {
                return isset($this->property) && false == $this->property->ownedByApplicant();
            })->asHtml()
        ;
    }
}

