<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StepFourRequest extends FormRequest
{
    use RemovesNestedAttributes;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference.reference_type_id'      => 'required|exists:reference_types,id',
            'reference.info_response'          => 'nullable|string|max:191',
            // 'applicant.feature_on_water_saver' => 'nullable|boolean',
            'applicant.watersense'             => 'required|string|between:5,100'
        ];
    }

    public function attributes(): array
    {
        $attributes = $this->removeNestedAttributes($this->rules());

        return $attributes;
    }

    public function messages()
    {
        return [
            'reference.reference_type_id.required' => 'Please select a referral option',
            'reference.reference_type_id.exists'   => 'The selected referral source is invalid',
            'reference.info_response.required'     => 'Please specify',
        ];
    }
}
