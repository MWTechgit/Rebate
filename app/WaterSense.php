<?php

namespace App;

use App\Applicant;
use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterSense extends Model
{
    use Cacheable;

    protected $table = 'water_sense';

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

}
