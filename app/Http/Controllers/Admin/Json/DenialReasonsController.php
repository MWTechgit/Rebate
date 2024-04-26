<?php

namespace App\Http\Controllers\Admin\Json;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

/**
 * JSON controller used in admin nova components
 */
class DenialReasonsController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'type' => [
                'nullable',
                Rule::in(['application', 'claim'])
            ]
        ]);

        if ($request->filled('type')) {
            return \App\DenialReason::where('type', $request->input('type'))->get();
        }

        return \App\DenialReason::all();
    }
}
