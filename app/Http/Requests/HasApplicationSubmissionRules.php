<?php

namespace App\Http\Requests;

use App\Property;
use App\Rules\CompleteAddress;
use Illuminate\Validation\Rule;

trait HasApplicationSubmissionRules {

    protected function getAddressRules($prepend = null, $requireAll): array
    {
        $p = $prepend ? $prepend . '.' : '';
        return [
            $p.'address.line_one'           => 'string|max:191|' . ( $requireAll ? 'required' : $this->requiredWithAddress($prepend,'line_one')),
            $p.'address.line_two'           => 'nullable|string|max:191',
            $p.'address.city'               => 'string|max:100|' . ( $requireAll ? 'required' : $this->requiredWithAddress($prepend,'city') ),
            $p.'address.state'              => 'string|max:30|' . ( $requireAll ? 'required' : $this->requiredWithAddress($prepend,'state') ),
            $p.'address.postcode'           => 'string|max:30|' . ( $requireAll ? 'required' : $this->requiredWithAddress($prepend,'postcode') ),
        ];
    }

	protected function getApplicantRules( $confirmEmail = false, $watersense = true ): array
	{
		return [
            'application.submission_type' => 'nullable|string',
            'applicant.first_name'   => 'required|string|max:45',
            'applicant.last_name'    => 'required|string|max:45',
            'applicant.email'        => 'required|email|max:100' . ($confirmEmail ? '|confirmed' : ''),
            'applicant.email_opt_in' => 'required|boolean',
            'applicant.phone'        => 'required|min:10|max:15',
            'applicant.mobile'       => 'nullable|min:10|max:15',
            // 'applicant.feature_on_water_saver' => 'nullable|boolean',
            'applicant.watersense'   => ($watersense ? 'required' : 'nullable') . '|string|between:5,100'
		];
	}

	protected function getPropertyRules(): array
	{
		return [
            'property.subdivision_or_development' => 'nullable|string|max:45',
            'property.occupants'                  => 'required|string|max:25',
            'property.full_bathrooms'             => 'required|integer',
            'property.year_built'                 => 'required|string|max:35',
            'property.property_type_group'        => 'required|in:' . Property::RESIDENTIAL . ',' . Property::COMMERCIAL,
            'property.toilets'                    => 'required_if:property.property_type_group,' . Property::COMMERCIAL . '|string|max:15',
            'property.half_bathrooms'             => 'required_if:property.property_type_group,' . Property::RESIDENTIAL . '|integer',
            'property.years_lived'                => 'required_if:property.property_type_group,' . Property::RESIDENTIAL . '|string|max:25',
            'property.original_toilet'            => 'required|in:Yes,No,Maybe',
            'property.gallons_per_flush'          => 'required_if:property.original_toilet,No|string|max:191',
		];
	}

    protected function getPropertyTypeRules(): array
    {
        return [
            'property.property_type'              => ['required', Rule::in(Property::PROPERTY_TYPES)],
            'property.building_type'              => ['required_if:property.property_type,Residential', 'nullable', Rule::in(Property::BUILDING_TYPES)],
        ];
    }

	protected function getUtilityAccountRules(): array
	{
		return array_merge(
            ['utility_account.account_number'   => 'required|string|max:45'],
            $this->getAddressRules('utility_account', true)
        );
	}

	protected function getOwnerRules(): array
	{
		return array_merge(
            [
                'owner.first_name'       => ['string', 'max:45', $this->requiredWithOwner('first_name')],
                'owner.last_name'        => ['string', 'max:45', $this->requiredWithOwner('last_name')],
                'owner.email'            => ['email', 'max:100', $this->requiredWithOwner('email')],
                'owner.company'          => ['nullable', 'string', 'max:45'],
                'owner.phone'            => ['string', 'max:15', $this->requiredWithOwner('phone')],
                'owner.mobile'           => ['nullable', 'string', 'max:15']
            ],
            $this->getAddressRules('owner', false)
        );
	}

    private function requiredWithOwner($except)
    {
        $r = ['first_name','last_name','email','company','phone', 'mobile'];
        $str = '';
        foreach ($r as $key) {
            if ($key===$except) {
                continue;
            }
            $str.='owner.'.$key.',';
        }
        return 'required_with:'.$str;
    }

    private function requiredWithAddress($prepend,$except)
    {
        $r = ['line_one','line_two','city','state','postcode'];
        $p = $prepend ? $prepend . '.' : '';
        $str = '';
        foreach ($r as $key) {
            if ($key===$except) {
                continue;
            }
            $str.=$p.'address.'.$key.',';
        }
        return 'required_with:'.$str;
    }

}
