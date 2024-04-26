<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ReferenceType extends Filter
{
    public $component = 'select-filter';

    public function apply(Request $request, $query, $value)
    {
        return $query->whereHas('applicant.reference.type', function ($query) use ($value) {
            return $query->where('reference_types.type', $value);
        });
    }

    public function options(Request $request)
    {
        $types = \App\ReferenceType::pluck('type')->all();
        return array_combine($types, $types);
    }

    // public function default()
    // {
    //     $default = \App\ReferenceType::where('type', 'like', '%friend%')->first();

    //     if ($default) {
    //         return $default->type;
    //     }

    //     return \App\ReferenceType::first()->type;
    // }
}
