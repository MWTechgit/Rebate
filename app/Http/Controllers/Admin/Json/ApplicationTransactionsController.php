<?php

namespace App\Http\Controllers\Admin\Json;

use App\Application;
use App\Denial;
use App\Http\Controllers\Controller;
use App\Jobs\ApproveApplication;
use App\Jobs\DenyApplication;
use Illuminate\Http\Request;

class ApplicationTransactionsController extends Controller
{
    public function approve(Request $request, Application $application)
    {
        $admin = \Auth::guard('admin')->user();

        try {
            $this->dispatchNow(new ApproveApplication($application, $admin));
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Application successfully approved!'
        ]);
    }

    public function denyApplication(Request $request, Application $application)
    {
        $request->validate([
            'reason' => 'required',
        ]);

        $admin = \Auth::guard('admin')->user();
        $reason = strip_tags($request->input('reason'));

        $job = new DenyApplication($application, $admin, new Denial($reason));
        $this->dispatchNow($job);

        return response()->json([
            'message'  => 'Application successfully denied!',
        ]);
    }
}
