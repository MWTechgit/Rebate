<?php

namespace App\Http\Resources;

use App\Property;
use Illuminate\Http\Resources\Json\JsonResource;

class Application extends JsonResource
{
    public function toArray($request)
    {

        $this->load('applicant.reference.type');

        return [
            'id' => $this->id,
            'rebate_code' => $this->rebate_code,
            'status' => $this->status,
            'mail_to_address' => (string) $this->getMailToAddress(),
            'has_remittance_address' => $this->hasRemittanceAddress(),
            'address' => $this->when($this->hasRemittanceAddress(), function() {
                return [
                    'id' => $this->address->id
                ];
            }),
            'rebate_count' => $this->rebate_count,
            'property' => [
                'id' => optional($this->property)->id,
                'address' => [
                    'full' => (string) optional($this->property)->address,
                    'id' => optional($this->property)->address ? optional($this->property)->address->id : null,
                ],
                'years_lived' => optional($this->property)->years_lived,
                'occupants' => optional($this->property)->occupants,
                'owner' => $this->when(! empty(optional($this->property)->owner), function() {
                    return [
                        'id' => optional($this->property)->owner->id,
                        'is_applicant' => optional($this->property)->ownedByApplicant(),
                        'full_name' => optional($this->property)->owner->full_name,
                    ];
                }),
                'building_type' => optional($this->property)->building_type,
                'bathrooms' => optional($this->property)->bathrooms,
                'half_bathrooms' => optional($this->property)->half_bathrooms,
                'full_bathrooms' => optional($this->property)->full_bathrooms,
                'toilets' => optional($this->property)->toilets,
                'year_built' => optional($this->property)->year_built,
                'original_toilet' => optional($this->property)->original_toilet,
                'gallons_per_flush' => optional($this->property)->gallons_per_flush,
                'is_residential' => optional($this->property)->property_type === Property::RESIDENTIAL,
            ],
            'submission_type' => $this->submission_type,
            'utilityAccount' => optional($this->property)->utilityAccount ?
                optional($this->property)->utilityAccount->load('address') :
                null,
            'rebate' => $this->rebate->load('partner'),
            'applicant' => $this->applicant,
            'approved' => $this->isApproved(),
            'denied' => $this->isDenied(),
            'claim' => [
                'id' => optional($this->claim)->id
            ],
            'reference' => optional($this->applicant)->reference ?
                [
                    'info_response' => $this->applicant->reference->info_response,
                    'type' => optional($this->applicant->reference->type)->type
                ] :
                null,

            $this->mergeWhen($this->isDenied() && $this->transaction, function () {
                return [
                    'reason' => $this->transaction->description,
                    'denied_by' => optional($this->transaction->admin)->full_name ?? 'an admin',
                    'longer_reason' => $this->transaction->extended_description,
                    'denied_on' => (string) $this->transaction->created_at,
                    'denied_diff' => $this->transaction->created_at->diffForHumans(),
                ];
            }),

            $this->mergeWhen($this->isApproved() && $this->transaction, function () {
                return [
                    'reason' => $this->transaction->description,
                    'approved_by' => optional($this->transaction->admin)->full_name ?? 'an admin',
                    'longer_reason' => $this->transaction->extended_description,
                    'approved_on' => (string) $this->transaction->created_at,
                    'approved_diff' => $this->transaction->created_at->diffForHumans(),
                ];
            }),

            'watersense' => optional($this->applicant)->waterSenseReason

        ];
    }
}
