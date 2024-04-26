<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{StepOneRequest, StepTwoRequest, StepThreeRequest, StepFourRequest};
use App\Http\Resources\Partner as PartnerResource;
use App\Partner;
use App\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * This controller is to validate steps in the external form.
 *
 * The form is at https://conservationpays.com/rebate-applicaton
 */
class StepsController extends Controller
{
    public function one(StepOneRequest $request)
    {
        $data = $request->validated();

        $partner = Partner::findOrFail($data['partner_id']);

        $data['partner'] = new PartnerResource($partner);

        $data['property']['property_type_group'] = 'Residential' == Arr::get($data, 'property.property_type')
            ? Property::RESIDENTIAL
            : Property::COMMERCIAL;

        return response()->json($data);
    }

    public function two(StepTwoRequest $request)
    {
        return response()->json($request->validated());
    }

    public function three(StepThreeRequest $request)
    {
        return response()->json($request->validated());
    }

    public function four(StepFourRequest $request)
    {
        return response()->json($request->validated());
    }
}