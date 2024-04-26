<?php

namespace App;

use App\Cacheable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimComment extends Model implements Comment
{
    use Cacheable;    
    
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }
}
