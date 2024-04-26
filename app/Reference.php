<?php

namespace App;

use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reference extends Model
{
    use Cacheable;
    
    public function type(): BelongsTo
    {
        return $this->belongsTo(ReferenceType::class, 'reference_type_id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function source()
    {
        return optional($this->type)->type;
    }

    public function description()
    {
        return $this->info_response;
    }
}
