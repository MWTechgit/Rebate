<?php

namespace App\Http\Controllers\Api;

use App\Application;
use App\Http\Controllers\Controller;
use App\Http\Resources\Application as ApplicationResource;
use Illuminate\Http\Request;
use App\Jobs\TracksNextFromLens;

/**
 * Applications JSON Controller
 *
 * Used in ReviewApplication Nova Resource
 */
class ApplicationsController extends Controller
{
    public function show(Application $application)
    {
        return new ApplicationResource($application);
    }

    public function next(Request $request, Application $application, $lens=null)
    {
       
        $id = TracksNextFromLens::getNextId($request, $application, $lens);

        return response()->json(['id' => $id ]);
    }

}
