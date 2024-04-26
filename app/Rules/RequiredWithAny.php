<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * This rule is just shorthand for required_with
 * It allow you to pass an array to required_with
 * and build the required_with string out for you.
 */
class RequiredWithAny
{
    /**
     * The fields that make this field required when not empty
     */
    protected $required;

    /**
     * Rule in "required" to ignore, usually the field itself
     */
    protected $ignoredField;

    public function __construct(array $required, string $ignoredField)
    {
        $this->required = $required;
        $this->ignoredField = $ignoredField;
    }

    public function __toString()
    {
        $rule = 'required_with:';

        foreach ($this->required as $field) {
            if ($field == $this->ignoredField) continue;
            $rule .= $field.',';
        }

        return trim($rule, ',');
    }
}
