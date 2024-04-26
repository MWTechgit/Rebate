<?php

namespace App\Http\Controllers\Api;

use App\Claim;
use App\Http\Controllers\Controller;
use App\Nova\Claim as NovaResource;
use App\Http\Resources\Claim as ClaimResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;

use App\Jobs\TracksNextFromLens;

class ClaimsController extends Controller
{
    public function show(Request $request, Claim $claim)
    {
        return new ClaimResource($claim);

    }

    public function next(Request $request, Claim $claim, $lens = null)
    {
        $id = TracksNextFromLens::getNextId($request, $claim, $lens);

        return response()->json(['id' => $id ]);
    }

}
