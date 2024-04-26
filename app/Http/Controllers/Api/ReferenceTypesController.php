<?php

namespace App\Http\Controllers\Api;

use App\ReferenceType;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReferenceType as ReferenceTypeResource;
use Illuminate\Http\Request;

class ReferenceTypesController extends Controller
{
    public function index()
    {
        return ReferenceTypeResource::collection(ReferenceType::all());
    }
}
