<?php

namespace App\Http\Requests;

use App\Http\Requests\HasApplicationSubmissionRules;
use Illuminate\Foundation\Http\FormRequest;

class StepThreeRequest extends FormRequest {
	use RemovesNestedAttributes;
    use HasApplicationSubmissionRules;

	public function authorize(): bool {
		return true;
	}

	public function rules(): array
	{
		$rules = array_merge(
            [
                'application.rebate_count'            => 'required|integer',
                'application.desired_rebate_count'    => 'nullable|integer',
            ],
            $this->getPropertyRules(),
            $this->getOwnerRules()
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
