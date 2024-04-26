<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use App\Rules\RequiredWithAny;

class RequiredWithAnyTest extends TestCase
{
    /** @test */
    public function it_requires_provided_values_except_except_value()
    {
        $required = [
            'application.address.line_one',
            'application.address.line_two',
            'application.address.street',
            'application.address.city',
        ];

        $except = 'application.address.street';

        $rule = new RequiredWithAny($required, $except);
        $ruleString = (string) $rule;

        # It should require anything except application.address.street
        $expected = 'required_with:application.address.line_one,application.address.line_two,application.address.city';
        $this->assertEquals($expected, $ruleString);
    }
}
