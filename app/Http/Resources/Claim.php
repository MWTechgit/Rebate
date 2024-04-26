<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Claim as Resource;

class Claim extends JsonResource
{
    public function toArray($request)
    {
        static::withoutWrapping();

        return [
            'id' => $this->id,
            'application_id' => $this->application_id,
            'approved' => $this->isApproved(),
            'denied' => $this->isDenied(),
            'expired' => $this->isExpired(),
            'expiring_soon' => !$this->isExpired() && $this->expires_at->lte( now()->addDays(Resource::EXPIRES_SOON) ),
            'awarded' => $this->amount_awarded,

            $this->mergeWhen($this->expires_at, function () {
                return [
                    'expires_on' => (string) $this->expires_at,
                    'expires_diff' => $this->expires_at->diffForHumans(),
                ];
            }),

            $this->mergeWhen($this->isDenied() && $this->transaction, function () {
                return [
                    'reason' => $this->transaction->description,
                    'denied_by' => optional($this->transaction->admin)->full_name ?? 'an admin',
                    'denied_on' => (string) $this->transaction->created_at,
                    'denied_diff' => $this->transaction->created_at->diffForHumans(),
                ];
            }),

            $this->mergeWhen($this->isApproved() && $this->transaction, function () {
                return [
                    'reason' => $this->transaction->description,
                    'approved_by' => optional($this->transaction->admin)->full_name ?? 'an admin',
                    'approved_on' => (string) $this->transaction->created_at,
                    'approved_diff' => $this->transaction->created_at->diffForHumans(),
                ];
            }),

            $this->mergeWhen($this->isExpired(), function () {
                return [
                    'expired_on' => (string) $this->expired_at,
                    'expired_diff' => $this->expired_at->diffForHumans(),
                ];
            })

        ];
    }
}
