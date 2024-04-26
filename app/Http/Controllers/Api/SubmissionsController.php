<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmissionRequest;
use App\Jobs\ProcessSubmission;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class SubmissionsController extends Controller
{
    public function store(SubmissionRequest $request)
    {
        rescue(function() use ($request) {
            $this->logSubmission($request);
        });

        $job = new ProcessSubmission($request->validated(), $request->user());

        try {
            $this->dispatchNow($job);
        } catch (\Exception $e) {
            # Report exception to be emailed by handler
            report($e);

            return response()->json([
                'message' => 'There was an error processing the submission.'
            ], 500);
        }

        return response()->json([
            'created' => true,
            'on_waitlist' => $job->isWaitListed(),
            'rebate_code' => $job->getRebateCode()

        ], 201);
    }

    private function logSubmission(SubmissionRequest $request)
    {
        $sLog = new Logger('submissions');
        $sLog->pushHandler(new StreamHandler(storage_path('logs/applications-'.date('Y-m').'.log')), Logger::INFO);
        $sLog->info('New Submission', ['request' => $request->all() ]);
    }
}