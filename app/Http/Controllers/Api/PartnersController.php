<?php

namespace App\Http\Controllers\Api;

use App\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Partner as PartnerResource;

class PartnersController extends Controller
{
    public function active()
    {
        $partners = Partner::hasActiveRebate();

        return PartnerResource::collection($partners->orderBy('name')->get());
    }
}
