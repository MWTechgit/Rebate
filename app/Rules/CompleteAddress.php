<?php

namespace App\Rules;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * This rule is just shorthand for required_with
 * It allow you to pass an array to required_with
 * and build the required_with string out for you.
 */
class CompleteAddress
{
    protected $prependKey;

    public function __construct(string $prependKey = null)
    {
        $this->prependKey = $prependKey;
    }

    public function __toString()
    {
        $p = $this->prependKey ? $this->prependKey . '.' : '';

        return 'required_with:' . 
            $p.'address.line_one'. ','.
            $p.'address.line_two'. ','.
            $p.'address.city'. ','.
            $p.'address.state'. ','.
            $p.'address.post_code';
    }

}
