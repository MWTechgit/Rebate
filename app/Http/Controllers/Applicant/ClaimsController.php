<?php

namespace App\Http\Controllers\Applicant;

use App\Claim;
use App\DocumentSet;
use App\Events\ClaimWasReceived;
use App\Http\Controllers\Applicant\ShowsRebateStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class ClaimsController extends Controller
{
    use ShowsRebateStatus;

    public function status(Claim $claim)
    {
        if ( $claim->isUnclaimed() ) {
            return $this->redirectToApplicationStatus( $claim->application );
        }

        $data = [
            'applicant'     => $claim->applicant,
            'application'   => $claim->application,
            'claim'         => $claim,
            'claimLifetime' => Claim::EXPIRES_IN,
            'partial'       => $this->getStatusView($claim),
            'awarded'       => $claim->isApproved() && $claim->amount_awarded ? money_format('$%n', (float) $claim->amount_awarded) : null
        ];

        return view('applicant.claims.status', $data);
    }

    public function show(Claim $claim, Request $request)
    {
        if ($claim->isInReview()) {
            return $this->redirectToClaimStatus( $claim );
        }

        return view('applicant.claims.show', [
            'claim' => $claim,
            'documentSets' => $claim->documentSets->toArray(),
            'status_url'  => $this->statusRoute($claim->application)
        ]);
    }

    public function edit(Claim $claim, Request $request)
    {
        if ($claim->isInReview()) {
            return $this->redirectToClaimStatus( $claim );
        }

        return view('applicant.claims.edit', [
            'claim' => $claim,
            'documentSets' => $claim->documentSets->toArray(),
            'status_url'  => $this->statusRoute($claim->application)
        ]);
    }

    public function update(Claim $claim, Request $request)
    {
        if ($claim->isInReview()) {
            throw ValidationException::withMessages(['status' => ['The claim is already being reviewed.']]);
        }

        // @todo - Validation here!! WTH Justin?!

        $sets = $request->all()['docsets'];

        collect($sets)->each(function($set, $index) use ($claim) {

            $id = Arr::get($set,'id');

            $documentSet = $id
                ? DocumentSet::where('claim_id', $claim->id)->find($id)
                : new DocumentSet;

            $documentSet->claim_id = $claim->id;

            if (isset($set['purchased_at'])) {
                $documentSet->purchased_at = Carbon::parse($set['purchased_at']);
            }

            collect(DocumentSet::FILES)->each(function($prop) use ($set, $documentSet) {
                if (false === isset($set[$prop]) || !$set[$prop]) return;
                // TODO: delete the old file if it exists
                $file = $set[$prop];
                $path = $file->storePublicly('claims', ['disk' => 'public']);
                $documentSet->$prop = $path;
            });

            $documentSet->save();
        });

        return redirect()->route('claims.show', [$claim]);
    }

    public function submit(Claim $claim, Request $request)
    {
        $claim->status = Claim::ST_NEW;
        $claim->submitted_at = $claim->submitted_at ?? now();
        $claim->touch();
        $claim->save();

        event(new ClaimWasReceived($claim));

        return $this->redirectToClaimStatus($claim);
    }

    protected function getStatusView(Claim $claim)
    {
        $prefix = 'applicant.claims.partials.';

        if ($claim->isNew()) {
            return $prefix.'complete';
        }

        if ($claim->isApproved()) {
            return $prefix.'approved';
        }

        if ($claim->isDenied() || $claim->isExpired()) {
            return $prefix.'denied';
        }

        if ($claim->isInReview()) {
            return $prefix.'in_review';
        }

        throw new \RuntimeException('Claim status page reached when claim not approved, in review, or denied/expired. Claim ID: '.$claim->id);
    }
}
