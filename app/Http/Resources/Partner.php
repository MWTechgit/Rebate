<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;

class Partner extends JsonResource
{
    public function toArray($request)
    {
        $activeRebate = $this->getActiveRebate();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'rebate' => $this->when($activeRebate, function() use ($activeRebate) {
                return [
                    'id' => $activeRebate->id,
                    'name' => $activeRebate->name,
                    'value' => $activeRebate->value,
                ];
            }),
        ];
    }

    private function getActiveRebate() {
        try {
            return $this->activeRebate();

        } catch (\Exception $e ) {
            // No rebates available
            throw ValidationException::withMessages([
                'partner_id' => [ $e->getMessage() ]
            ]);
        }
    }
}
