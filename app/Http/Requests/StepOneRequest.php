<?php

namespace App\Http\Requests;

use App\Http\Requests\HasApplicationSubmissionRules;
use App\Property;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StepOneRequest extends FormRequest {
	use RemovesNestedAttributes;
    use HasApplicationSubmissionRules;

	public function authorize(): bool
    {
		return true;
	}

	public function rules(): array
	{
		$rules = array_merge(
            $this->getApplicantRules( $confirmEmail = true, $watersense = false ),
            $this->getPropertyTypeRules(),
            [
                'partner_id' => 'required|exists:partners,id'
    		]
        );

        return $rules;
	}

	public function attributes(): array
	{
		$attributes = $this->removeNestedAttributes($this->rules());

		$attributes['partner_id'] = 'City/Municipality';

		return $attributes;
	}
}
