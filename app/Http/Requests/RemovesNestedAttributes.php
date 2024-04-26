<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;

trait RemovesNestedAttributes
{
    /**
     * To be used only within the
     * "attributes" method of a form request.
     *
     * Used for formatting validation rule attribute names
     * to a sensible error message :attribute when using
     * dot syntax to define validation rules.
     *
     * Examples:
     * applicant.first_name => "First Name"
     * account.address.city => "City"
     * city => "City"
     */
    public function removeNestedAttributes(array $rules): array
    {
        return collect($rules)->map(function($item, $key) {
            if (Str::contains($key, '.')) $key = array_reverse(explode('.', $key))[0];
            $key = str_replace('_', ' ', $key);
            return trim(Str::title($key));
        })->all();
    }

    abstract public function rules();

    abstract public function attributes();
}