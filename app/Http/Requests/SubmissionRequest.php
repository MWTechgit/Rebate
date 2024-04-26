<?php

namespace App\Http\Requests;

use App\Http\Requests\HasApplicationSubmissionRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmissionRequest extends FormRequest {
	use RemovesNestedAttributes;
    use HasApplicationSubmissionRules;

	public function authorize() {
		return true;
	}

	public function rules() {
		
        $rules = array_merge(
			$this->getApplicantRules(),                          // name, email phone
            $this->getAddressRules('property', true),      // property.address
            $this->getPropertyRules(),                           // bathrooms, year built, etc
			$this->getPropertyTypeRules(),                       // property_type, building_type
            $this->getAddressRules('application', false),
            $this->getUtilityAccountRules(),
			$this->getOwnerRules(),
            [
                'rebate_id'                   => 'required|exists:rebates,id',
                'application.rebate_count'    => 'required|integer',
                'reference.reference_type_id' => 'required|exists:reference_types,id',
                'reference.info_response'     => 'nullable|string|max:191'
            ]
		);

        return $rules;
	}

	public function messages(): array
	{
		return [
			'required_with' => 'The :attribute field is required',
		];
	}

	public function attributes(): array
	{
		$attributes = $this->removeNestedAttributes($this->rules());

		return $attributes;
	}
}
