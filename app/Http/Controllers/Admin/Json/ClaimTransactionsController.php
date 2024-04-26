<?php

namespace App\Http\Controllers\Admin\Json;

use App\Claim;
use App\Denial;
use App\Http\Controllers\Controller;
use App\Jobs\DenyClaim;
use App\Jobs\ApproveClaim;
use App\Exceptions\NotEnoughRebates;
use Illuminate\Http\Request;

class ClaimTransactionsController extends Controller
{
    public function approve(Request $request, Claim $claim)
    {
        $maxValue = $claim->maxValue();

        $this->validate($request, [
            'awarded' => "required|numeric|min:0|max:$maxValue",
        ]);

        $admin = \Auth::guard('admin')->user();

        try {
            $this->dispatchNow(new ApproveClaim($claim, $admin, $request->input('awarded')));
        } catch (NotEnoughRebates $e) {
            return response()->json([
                'message' => "There aren't enough rebates left to approve the claim!"
            ], 404);
        }

        return response()->json([
            'message' => 'Claim successfully approved!'
        ]);
    }

    public function denyClaim(Request $request, Claim $claim)
    {
        $request->validate([
            'reason' => 'required',
        ]);

        $admin = \Auth::guard('admin')->user();
        $reason = strip_tags($request->input('reason'));

        $job = new DenyClaim($claim, $admin, new Denial($reason));
        $this->dispatchNow($job);

        return response()->json([
            'message'  => 'Claim successfully denied!',
        ]);
    }
}
