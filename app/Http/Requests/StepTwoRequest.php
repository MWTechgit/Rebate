<?php

namespace App\Http\Requests;

use App\Http\Requests\HasApplicationSubmissionRules;
use App\Rules\RequiredWithAny;
use Illuminate\Foundation\Http\FormRequest;

class StepTwoRequest extends FormRequest {
	use RemovesNestedAttributes;
    use HasApplicationSubmissionRules;

	public function authorize(): bool 
    {
		return true;
	}

	public function rules(): array
	{
        $rules = array_merge(
            $this->getUtilityAccountRules(),
            $this->getAddressRules('property', true),
            $this->getAddressRules('application', false)
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

        $attributes['application.address.line_one']     = 'mailing address';
        $attributes['property.address.line_one']        = 'physical address';
        $attributes['utility_account.address.line_one'] = 'utility address';

		return $attributes;
	}
}
